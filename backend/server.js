const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const verifyUserRouter = require('./routes/verify-user');
const path = require('path');
require('dotenv').config({ path: path.resolve(__dirname, '.env') });
const axios = require('axios');
const { Resend } = require('resend');
const fs = require('fs');
const querystring = require('querystring');

// Environment variables with fallbacks
const TELR_STORE_ID = process.env.TELR_STORE_ID || '33890';
const TELR_AUTH_KEY = process.env.TELR_AUTH_KEY || 'h9nFv#Hf8L^Lgkth';
const TELR_API_URL = 'https://secure.telr.com/gateway/order.json'; // Verify with Telr documentation
const resend = new Resend(process.env.RESEND_API_KEY || 're_9GB3ogth_H5qP89wpBXvcDkNQ1g8aadwX');
const fromEmail = process.env.FROM_EMAIL?.match(/<([^>]+)>/)?.[1] || 'noreply@eodubai.com';
const PORT = process.env.PORT || 3001;
const isProduction = process.env.NODE_ENV === 'production';
const FRONTEND_URL = process.env.FRONTEND_URL || 'https://eodubai.com/synapse';

const app = express();

// CORS configuration
const corsOptions = {
  origin: [
    'http://localhost:8000',
    'https://eodubai.com',
    'http://eodubai.com',
    'https://www.eodubai.com',
    'http://www.eodubai.com',
    FRONTEND_URL
  ],
  credentials: true,
  optionsSuccessStatus: 200,
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization']
};

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(cors(corsOptions));

// Log request origins for debugging
app.use((req, res, next) => {
  console.log(`[CORS] Request origin: ${req.get('origin')} [${req.method} ${req.url}]`);
  next();
});

// Initialize CSV files
function ensureCSVFiles() {
  const presentFile = path.join(__dirname, 'attendance_present.csv');
  const absentFile = path.join(__dirname, 'attendance_absent.csv');
  
  const csvHeader = 'sessionId,name,email,phone,chapter,plan,paymentAmount,paymentCurrency,transactionId,dateRegistered,penaltyAmount,penaltyStatus\n';
  
  if (!fs.existsSync(presentFile)) {
    fs.writeFileSync(presentFile, csvHeader);
  }
  
  if (!fs.existsSync(absentFile)) {
    fs.writeFileSync(absentFile, csvHeader);
  }
}

// Save Telr transaction
async function saveTelrTransaction(transaction) {
  const transactionId = transaction.ref;
  const dirPath = path.join(__dirname, 'telr_transactions');
  
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true });
  }
  
  const filePath = path.join(dirPath, `${transactionId}.json`);
  try {
    await fs.promises.writeFile(filePath, JSON.stringify(transaction, null, 2), 'utf8');
    console.log(`[saveTelrTransaction] Transaction ${transactionId} saved to ${filePath}`);
  } catch (error) {
    console.error(`[saveTelrTransaction] Error saving transaction ${transactionId}:`, error);
    throw error;
  }
}

ensureCSVFiles();

// Test Telr API connectivity
app.get('/api/test-telr', async (req, res) => {
  try {
    const { method = 'create', order_ref } = req.query; // Allow method and order_ref as query params
    const isCheckMethod = method === 'check';

    if (isCheckMethod && !order_ref) {
      return res.status(400).json({
        success: false,
        error: 'order_ref is required for check method'
      });
    }

    const testParams = isCheckMethod
      ? {
          ivp_method: 'check',
          ivp_store: TELR_STORE_ID,
          ivp_authkey: TELR_AUTH_KEY,
          order_ref: order_ref
        }
      : {
          ivp_method: 'create',
          ivp_store: TELR_STORE_ID,
          ivp_authkey: TELR_AUTH_KEY,
          ivp_cart: `TEST-${Date.now()}`,
          ivp_test: isProduction ? '0' : '1',
          ivp_amount: '1.00',
          ivp_currency: 'AED',
          ivp_desc: 'Test Transaction',
          ivp_framed: '0',
          return_auth: `${isProduction ? 'https://eodubai.com/synapse/backend' : `http://localhost:${PORT}`}/payment/success?order_ref=[ORDER_REF]`,
          return_decl: `${isProduction ? 'https://eodubai.com/synapse/backend' : `http://localhost:${PORT}`}/payment/fail`,
          return_can: `${isProduction ? 'https://eodubai.com/synapse/backend' : `http://localhost:${PORT}`}/payment/cancel`,
          bill_fname: 'Test',
          bill_sname: 'User',
          bill_email: 'test@example.com',
          bill_addr1: 'UAE',
          bill_city: 'Dubai',
          bill_country: 'AE'
        };

    console.log(`[test-telr] Testing Telr API with method=${method}, params:`, testParams);

    const telrResponse = await axios.post(TELR_API_URL, querystring.stringify(testParams), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Expect': ''
      },
      timeout: 30000
    });

    console.log('[test-telr] Telr API response:', JSON.stringify(telrResponse.data, null, 2));

    res.json({
      success: true,
      method: method,
      response: telrResponse.data
    });
  } catch (error) {
    console.error('[test-telr] Telr API test failed:', error.response?.data || error.message);
    res.status(500).json({
      success: false,
      error: 'Telr API test failed',
      details: error.response?.data || error.message
    });
  }
});

// Create Telr checkout session
app.post('/api/create-checkout-session', async (req, res) => {
  try {
    const { amount, currency, plan, name, email, phone, chapter, noShowConsent } = req.body;

    console.log('[create-checkout-session] Received request:', { amount, currency, plan, email, noShowConsent });

    // Validate inputs
    if (!amount || isNaN(amount) || amount <= 0) {
      console.error('[create-checkout-session] Invalid amount:', amount);
      return res.status(400).json({ error: 'Invalid amount provided' });
    }

    if (!currency || typeof currency !== 'string') {
      console.error('[create-checkout-session] Invalid currency:', currency);
      return res.status(400).json({ error: 'Invalid currency provided' });
    }

    if (!plan || typeof plan !== 'string') {
      console.error('[create-checkout-session] Invalid plan:', plan);
      return res.status(400).json({ error: 'Invalid plan provided' });
    }

    if (!email || typeof email !== 'string') {
      console.error('[create-checkout-session] Invalid email:', email);
      return res.status(400).json({ error: 'Invalid email provided' });
    }

    const amountInDecimal = parseFloat(amount).toFixed(2);

    // Backend server URLs for Telr callbacks
    const backendUrl = isProduction 
      ? 'https://eodubai.com/synapse/backend' 
      : `http://localhost:${PORT}`;

    // Telr is expected to append the order_ref to this URL.
    // Some payment gateways use placeholders like [ORDER_REF] or {ORDER_REF}.
    // Telr does not support placeholders in return_auth.
    // Redirecting directly to frontend thanks.php, which will then call backend to send email.
    const successUrl = `${FRONTEND_URL}/thanks.php`;
    const cancelUrl = `${backendUrl}/payment/cancel`;
    const declinedUrl = `${backendUrl}/payment/fail`;

    // Split name into first and last name
    const nameParts = name.trim().split(' ');
    const firstName = nameParts[0] || name;
    const lastName = nameParts.slice(1).join(' ') || 'User';

    // Generate unique cart ID
    const cartId = `SYNAPSE-${Date.now()}-${Math.floor(Math.random() * 1000)}`;

    // Telr parameters
    const telrParams = {
      ivp_method: 'create',
      ivp_store: TELR_STORE_ID,
      ivp_authkey: TELR_AUTH_KEY,
      ivp_cart: cartId,
      ivp_test: isProduction ? '0' : '1',
      ivp_amount: amountInDecimal,
      ivp_currency: currency.toUpperCase(),
      ivp_desc: `SYNAPSE Event - ${plan === 'vip' ? 'VIP' : 'Regular'} Pass`,
      ivp_framed: '0',
      return_auth: successUrl,
      return_decl: declinedUrl,
      return_can: cancelUrl,
      bill_fname: firstName,
      bill_sname: lastName,
      bill_email: email,
      bill_addr1: 'UAE',
      bill_city: 'Dubai',
      bill_country: 'AE'
    };

    if (phone) {
      telrParams.bill_phone = phone;
    }

    console.log('[create-checkout-session] Sending Telr params:', telrParams);

    // Send request to Telr
    try {
      const telrResponse = await axios.post(TELR_API_URL, querystring.stringify(telrParams), {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Expect': ''
        },
        timeout: 30000
      });

      console.log('[create-checkout-session] Telr API response:', JSON.stringify(telrResponse.data, null, 2));

      if (telrResponse.data && telrResponse.data.error) {
        console.error('[create-checkout-session] Telr API error:', telrResponse.data.error);
        return res.status(400).json({ 
          error: 'Telr API error',
          details: telrResponse.data.error.message || 'Unknown error from Telr',
          note: telrResponse.data.error.note || ''
        });
      }

      if (telrResponse.data && telrResponse.data.order && telrResponse.data.order.url) {
        const orderRef = telrResponse.data.order.ref;
        const metadataPath = path.join(__dirname, 'telr_transactions', `${orderRef}_metadata.json`);
        const metadata = {
          plan,
          chapter,
          noShowConsent,
          email,
          name,
          phone,
          amount: amountInDecimal,
          currency: currency.toUpperCase(),
          cartId,
          createdAt: new Date().toISOString()
        };

        const metadataDir = path.join(__dirname, 'telr_transactions');
        if (!fs.existsSync(metadataDir)) {
          fs.mkdirSync(metadataDir, { recursive: true });
        }

        fs.writeFileSync(metadataPath, JSON.stringify(metadata, null, 2));

        res.json({
          redirectUrl: telrResponse.data.order.url,
          telrRef: orderRef
        });
      } else {
        console.error('[create-checkout-session] Unexpected Telr response structure:', telrResponse.data);
        throw new Error('Failed to get redirect URL from Telr');
      }
    } catch (telrError) {
      console.error('[create-checkout-session] Telr API request failed:', telrError.response?.data || telrError.message);
      throw new Error(`Telr API request failed: ${telrError.message}`);
    }
  } catch (error) {
    console.error('[create-checkout-session] Error creating Telr checkout session:', error.message);
    res.status(500).json({ 
      error: 'Failed to create Telr checkout session',
      details: error.message,
      telrStoreId: TELR_STORE_ID ? 'Present' : 'Missing',
      telrAuthKey: TELR_AUTH_KEY ? 'Present' : 'Missing'
    });
  }
});

// Record attendance
app.post('/api/record-attendance', async (req, res) => {
  try {
    const { sessionId, attended } = req.body;

    console.log('[record-attendance] Received request:', { sessionId, attended });

    if (!sessionId) {
      console.error('[record-attendance] Invalid session ID:', sessionId);
      return res.status(400).json({ error: 'Invalid session ID provided' });
    }

    const telrParams = {
      ivp_method: 'check',
      ivp_store: TELR_STORE_ID,
      ivp_authkey: TELR_AUTH_KEY,
      order_ref: sessionId
    };

    console.log('[record-attendance] Verifying Telr transaction with params:', telrParams);

    const telrVerifyResponse = await axios.post(TELR_API_URL, querystring.stringify(telrParams), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Expect': ''
      }
    });

    console.log('[record-attendance] Telr verification response:', telrVerifyResponse.data);

    if (!telrVerifyResponse.data || !telrVerifyResponse.data.order) {
      console.error('[record-attendance] Telr transaction details not found for session ID:', sessionId);
      return res.status(404).json({ error: 'Telr transaction details not found' });
    }

    const telrOrder = telrVerifyResponse.data.order;
    
    const metadataPath = path.join(__dirname, 'telr_transactions', `${sessionId}_metadata.json`);
    let metadata = { chapter: 'N/A', plan: 'N/A' };
    
    if (fs.existsSync(metadataPath)) {
      metadata = JSON.parse(fs.readFileSync(metadataPath, 'utf8'));
    }

    const userData = {
      sessionId: sessionId,
      name: telrOrder.customer?.name?.forenames || metadata.name || 'N/A',
      email: telrOrder.customer?.email || metadata.email || 'N/A',
      phone: telrOrder.customer?.address?.mobile || metadata.phone || 'N/A',
      chapter: metadata.chapter || 'N/A',
      plan: metadata.plan || 'N/A',
      paymentAmount: telrOrder.amount || metadata.amount || '0',
      paymentCurrency: telrOrder.currency ? telrOrder.currency.toUpperCase() : metadata.currency || 'AED',
      transactionId: telrOrder.transaction?.ref || sessionId,
      dateRegistered: new Date().toISOString()
    };

    const filePath = path.join(__dirname, attended ? 'attendance_present.csv' : 'attendance_absent.csv');
    
    try {
      const csvContent = fs.readFileSync(filePath, 'utf8');
      const lines = csvContent.split('\n');
      const header = lines[0];
      const dataLines = lines.slice(1).filter(line => line.trim() !== '');

      const newLine = `${userData.sessionId},${userData.name},${userData.email},${userData.phone},${userData.chapter},${userData.plan},${userData.paymentAmount},${userData.paymentCurrency},${userData.transactionId},${userData.dateRegistered},,`;
      dataLines.push(newLine);

      fs.writeFileSync(filePath, header + '\n' + dataLines.join('\n') + '\n');

      res.json({ success: true });
    } catch (fileError) {
      console.error('[record-attendance] Error reading/writing CSV file:', fileError);
      res.status(500).json({ 
        error: 'Failed to process attendance record',
        details: fileError.message 
      });
    }
  } catch (error) {
    console.error('[record-attendance] Error recording attendance:', error);
    res.status(500).json({ 
      error: 'Failed to record attendance',
      details: error.message 
    });
  }
});

// Free registration
app.post('/api/register-free', async (req, res) => {
  try {
    const { name, email, phone, chapter, plan, noShowConsent } = req.body;

    console.log('[register-free] Received request:', { name, email, chapter, plan });

    if (!name || !email || !chapter || !plan) {
      console.error('[register-free] Missing required fields:', { name, email, chapter, plan });
      return res.status(400).json({ error: 'Missing required fields for free registration' });
    }

    const userData = {
      sessionId: `FREE-${Date.now()}-${Math.floor(Math.random() * 1000)}`,
      name: name,
      email: email,
      phone: phone || 'N/A',
      chapter: chapter,
      plan: plan,
      paymentAmount: 0,
      paymentCurrency: 'AED',
      transactionId: 'FREE_REGISTRATION',
      dateRegistered: new Date().toISOString(),
      penaltyAmount: noShowConsent ? 3699 : 0,
      penaltyStatus: noShowConsent ? 'Pending' : 'N/A'
    };

    const filePath = path.join(__dirname, 'attendance_present.csv');
    
    try {
      const csvContent = fs.readFileSync(filePath, 'utf8');
      const lines = csvContent.split('\n');
      const header = lines[0];
      const dataLines = lines.slice(1).filter(line => line.trim() !== '');

      const newLine = `${userData.sessionId},${userData.name},${userData.email},${userData.phone},${userData.chapter},${userData.plan},${userData.paymentAmount},${userData.paymentCurrency},${userData.transactionId},${userData.dateRegistered},${userData.penaltyAmount},${userData.penaltyStatus}`;
      dataLines.push(newLine);

      fs.writeFileSync(filePath, header + '\n' + dataLines.join('\n') + '\n');

      const qrCodeData = JSON.stringify({
        sessionId: userData.sessionId,
        name: userData.name,
        email: userData.email,
        plan: userData.plan,
      });

      const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=200x200`;

      const emailHtml = `
        <html>
          <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
              <h1 style="color: #2c3e50; text-align: center;">Your SYNAPSE Event QR Code</h1>
              <p>Dear ${userData.name},</p>
              <p>Thank you for registering for the SYNAPSE event! Your <strong>${userData.plan}</strong> pass is confirmed.</p>
              <div style="text-align: center; margin: 30px 0;">
                <img src="${qrCodeUrl}" alt="QR Code" style="border: 1px solid #ddd; padding: 10px;" />
              </div>
              <p>Please present this QR code at the event entrance.</p>
              <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
              <p style="font-size: 12px; color: #666;">Session ID: ${userData.sessionId}</p>
            </div>
          </body>
        </html>
      `;

      const emailResult = await resend.emails.send({
        from: fromEmail,
        to: [userData.email],
        subject: 'Your SYNAPSE Event QR Code (Free Registration)',
        html: emailHtml,
      });

      if (emailResult.error) {
        console.error('[register-free] Email sending failed:', emailResult.error);
      } else {
        console.log('[register-free] QR code email sent successfully for free registration.');
      }

      res.json({ success: true, message: 'Free registration successful', sessionId: userData.sessionId });
    } catch (fileError) {
      console.error('[register-free] Error reading/writing CSV file:', fileError);
      res.status(500).json({ 
        error: 'Failed to process free registration',
        details: fileError.message 
      });
    }
  } catch (error) {
    console.error('[register-free] Error processing free registration:', error);
    res.status(500).json({ 
      error: 'Failed to process free registration',
      details: error.message 
    });
  }
});

// Telr webhook
app.post('/api/telr-webhook', async (req, res) => {
  console.log('[telr-webhook] Received webhook:', req.body);

  try {
    const order_ref = req.body.order_ref || req.body.ref;

    if (!order_ref) {
      console.error('[telr-webhook] Missing order_ref in webhook payload.');
      return res.status(400).json({ error: 'Missing transaction reference' });
    }

    const telrParams = {
      ivp_method: 'check',
      ivp_store: TELR_STORE_ID,
      ivp_authkey: TELR_AUTH_KEY,
      order_ref: order_ref
    };

    console.log('[telr-webhook] Verifying Telr transaction with params:', telrParams);

    const telrVerifyResponse = await axios.post(TELR_API_URL, querystring.stringify(telrParams), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Expect': ''
      }
    });

    console.log('[telr-webhook] Telr verification response:', telrVerifyResponse.data);

    const telrOrder = telrVerifyResponse.data.order;

    if (!telrOrder) {
      console.error('[telr-webhook] Failed to retrieve transaction details for:', order_ref);
      return res.status(404).json({ error: 'Transaction details not found' });
    }

    if (telrOrder.status && telrOrder.status.code === 3) {
      await saveTelrTransaction(telrOrder);
      console.log('[telr-webhook] Transaction saved successfully:', order_ref);
    } else {
      console.warn('[telr-webhook] Transaction not successful, not saving:', telrOrder.status?.code);
    }

    res.json({ success: true, message: 'Webhook processed successfully' });
  } catch (error) {
    console.error('[telr-webhook] Error processing webhook:', error.response?.data || error.message);
    res.status(500).json({ error: 'Failed to process webhook', details: error.message });
  }
});


// Payment failure redirect
app.get('/payment/fail', (req, res) => {
  console.log('[payment/fail] Received redirect from Telr:', req.query);
  const orderRef = req.query.order_ref || req.query.ref;
  res.redirect(`${FRONTEND_URL}/registration.php?declined=true&telr_ref=${orderRef || 'unknown'}`);
});

// Payment cancellation redirect
app.get('/payment/cancel', (req, res) => {
  console.log('[payment/cancel] Received redirect from Telr:', req.query);
  const orderRef = req.query.order_ref || req.query.ref;
  res.redirect(`${FRONTEND_URL}/registration.php?cancelled=true&telr_ref=${orderRef || 'unknown'}`);
});

// Health check
app.get('/api/health', (req, res) => {
  res.json({ 
    status: 'OK', 
    timestamp: new Date().toISOString(),
    environment: process.env.NODE_ENV || 'development',
    frontendUrl: FRONTEND_URL,
    telrConfigured: !!(TELR_STORE_ID && TELR_AUTH_KEY),
    resendConfigured: !!(process.env.RESEND_API_KEY && fromEmail)
  });
});

// Email test
app.get('/api/email-test', async (req, res) => {
  try {
    console.log('[email-test] Starting email test');
    console.log('[email-test] FROM_EMAIL:', fromEmail);
    console.log('[email-test] RESEND_API_KEY:', process.env.RESEND_API_KEY ? 'Present' : 'Missing');
    
    const { toEmail } = req.query;
    if (!toEmail) {
      console.error('[email-test] toEmail query parameter is required');
      return res.status(400).json({ success: false, error: 'toEmail query parameter is required' });
    }

    const result = await resend.emails.send({
      from: fromEmail,
      to: [toEmail],
      subject: 'Test Email from SYNAPSE',
      html: `
        <h1>Test Email Success!</h1>
        <p>If you receive this, your email configuration is working.</p>
        <p>From: ${fromEmail}</p>
        <p>Environment: ${process.env.NODE_ENV || 'development'}</p>
      `,
    });

    console.log('[email-test] Email result:', result);
    
    if (result.error) {
      console.error('[email-test] Resend error:', result.error);
      return res.status(500).json({ 
        success: false, 
        error: result.error 
      });
    }

    res.json({ 
      success: true, 
      result: result,
      message: 'Email sent successfully!' 
    });
  } catch (error) {
    console.error('[email-test] Error sending test email:', error);
    res.status(500).json({ 
      success: false, 
      error: error.message 
    });
  }
});

// Send confirmation email
app.post('/api/send-confirmation-email', async (req, res) => {
  try {
    const { sessionId } = req.body;

    if (!sessionId) {
      console.error('[send-confirmation-email] Session ID is required');
      return res.status(400).json({ error: 'Session ID is required' });
    }

    console.log(`[send-confirmation-email] Received request for Telr order ID: ${sessionId}`);
    
    const telrParams = {
      ivp_method: 'check',
      ivp_store: TELR_STORE_ID,
      ivp_authkey: TELR_AUTH_KEY,
      order_ref: sessionId
    };

    console.log('[send-confirmation-email] Verifying Telr transaction with params:', telrParams);

    const telrVerifyResponse = await axios.post(TELR_API_URL, querystring.stringify(telrParams), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Expect': ''
      }
    });

    console.log('[send-confirmation-email] Telr verification response:', telrVerifyResponse.data);

    if (!telrVerifyResponse.data || !telrVerifyResponse.data.order) {
      console.error('[send-confirmation-email] Telr transaction details not found for order ID:', sessionId);
      return res.status(404).json({ error: 'Telr transaction details not found' });
    }

    const telrOrder = telrVerifyResponse.data.order;
    
    const metadataPath = path.join(__dirname, 'telr_transactions', `${sessionId}_metadata.json`);
    let metadata = {};
    
    if (fs.existsSync(metadataPath)) {
      metadata = JSON.parse(fs.readFileSync(metadataPath, 'utf8'));
    }

    if (telrOrder.status && telrOrder.status.code === 3) {
      console.log(`[send-confirmation-email] Payment status is 'paid'. Proceeding to send email.`);
      
      const customerName = metadata.name || telrOrder.customer?.name?.forenames || 'Guest';
      const customerEmail = metadata.email || telrOrder.customer?.email;
      const customerPlan = metadata.plan || 'regular';
      
      const qrCodeData = JSON.stringify({
        sessionId: sessionId,
        name: customerName,
        email: customerEmail,
        plan: customerPlan,
      });

      const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=200x200`;

      const emailHtml = `
        <html>
          <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
              <h1 style="color: #2c3e50; text-align: center;">Your SYNAPSE Event QR Code</h1>
              <p>Dear ${customerName},</p>
              <p>Thank you for registering for the SYNAPSE event! Your <strong>${customerPlan}</strong> pass is confirmed.</p>
              <div style="text-align: center; margin: 30px 0;">
                <img src="${qrCodeUrl}" alt="QR Code" style="border: 1px solid #ddd; padding: 10px;" />
              </div>
              <p>Please present this QR code at the event entrance.</p>
              <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
              <p style="font-size: 12px; color: #666;">Session ID: ${sessionId}</p>
            </div>
          </body>
        </html>
      `;

      const result = await resend.emails.send({
        from: fromEmail,
        to: [customerEmail],
        subject: 'Your SYNAPSE Event QR Code',
        html: emailHtml,
      });

      if (result.error) {
        console.error('[send-confirmation-email] Email sending failed:', result.error);
        return res.status(500).json({ error: 'Failed to send email' });
      }

      console.log('[send-confirmation-email] QR code email sent successfully.');
      res.json({ success: true, message: 'Email sent successfully' });
    } else {
      console.warn(`[send-confirmation-email] Payment not successful for Telr order ID ${sessionId}. Telr status: ${telrOrder.status?.code}. Email not sent.`);
      res.status(400).json({ error: 'Payment not successful', statusCode: telrOrder.status?.code });
    }
  } catch (error) {
    console.error('[send-confirmation-email] Error sending confirmation email:', error);
    res.status(500).json({ error: 'Failed to send confirmation email', details: error.message });
  }
});

// Mount verify-user router after specific routes
app.use('/api', verifyUserRouter);

// Error handling middleware
app.use((err, req, res, next) => {
  console.error('[error] Unhandled error:', err);
  res.status(500).json({
    error: 'Internal server error',
    message: isProduction ? 'Something went wrong' : err.message
  });
});

// 404 handler
app.use((req, res) => {
  console.warn(`[404] Route not found: ${req.method} ${req.url}`);
  res.status(404).json({
    error: 'Not found',
    message: `Route ${req.method} ${req.url} not found`
  });
});

// Start server
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
  console.log(`Environment: ${process.env.NODE_ENV || 'development'}`);
  console.log(`Frontend URL: ${FRONTEND_URL}`);
  console.log(`CORS enabled for: ${corsOptions.origin.join(', ')}`);
  console.log(`Telr Store ID: ${TELR_STORE_ID ? 'Configured' : 'MISSING'}`);
  console.log(`Telr Auth Key: ${TELR_AUTH_KEY ? 'Configured' : 'MISSING'}`);
  console.log(`Resend API Key: ${process.env.RESEND_API_KEY ? 'Configured' : 'MISSING'}`);
  console.log(`From Email: ${fromEmail}`);
});

module.exports = app;
