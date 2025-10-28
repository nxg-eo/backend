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
const csv = require('csv-parser'); // Import csv-parser
const { writePaidRegistrationToSheet, writeFreeRegistrationToSheet, checkExistingRegistration } = require('./googleSheets'); // Import the helper functions

// Environment variables with fallbacks
const TELR_STORE_ID = process.env.TELR_STORE_ID || '33890';
const TELR_AUTH_KEY = process.env.TELR_AUTH_KEY || 'h9nFv#Hf8L^Lgkth';
const TELR_API_URL = 'https://secure.telr.com/gateway/order.json';
const resend = new Resend(process.env.RESEND_API_KEY || 're_9GB3ogth_H5qP89wpBXvcDkNQ1g8aadwX');
const fromEmail = process.env.FROM_EMAIL?.match(/<([^>]+)>/)?.[1] || 'noreply@eodubai.com';
const PORT = process.env.PORT || 3001;
const isProduction = process.env.NODE_ENV === 'production';
const FRONTEND_URL = process.env.FRONTEND_URL || 'https://eodubai.com/ai-for-business';

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

// Log request origins for debugging
app.use((req, res, next) => {
  console.log(`[CORS] Request origin: ${req.get('origin')} [${req.method} ${req.url}]`);
  next();
});

// Initialize CSV files
function ensureCSVFiles() {
  const registrationsFile = path.join(__dirname, 'registrations.csv');
  const penaltiesFile = path.join(__dirname, 'penalty_records.csv');
  
  const registrationHeader = 'timestamp,sessionId,name,email,phone,chapter,plan,paymentAmount,paymentCurrency,transactionId,telrCardToken,noShowConsent,penaltyAmount,registrationStatus\n';
  const penaltyHeader = 'timestamp,sessionId,name,email,originalTransactionId,penaltyAmount,penaltyStatus,penaltyTransactionId,notes\n';
  
  if (!fs.existsSync(registrationsFile)) {
    fs.writeFileSync(registrationsFile, registrationHeader);
  }
  
  if (!fs.existsSync(penaltiesFile)) {
    fs.writeFileSync(penaltiesFile, penaltyHeader);
  }
}

// Save registration to CSV
function saveRegistrationToCSV(registrationData) {
  const filePath = path.join(__dirname, 'registrations.csv');
  
  try {
    const csvLine = `${new Date().toISOString()},${registrationData.sessionId},"${registrationData.name}",${registrationData.email},${registrationData.phone},${registrationData.chapter},${registrationData.plan},${registrationData.paymentAmount},${registrationData.paymentCurrency},${registrationData.transactionId},${registrationData.telrCardToken || 'N/A'},${registrationData.noShowConsent},${registrationData.penaltyAmount || 0},${registrationData.registrationStatus}\n`;
    
    fs.appendFileSync(filePath, csvLine);
    console.log(`[saveRegistrationToCSV] Registration saved for ${registrationData.email}`);
  } catch (error) {
    console.error(`[saveRegistrationToCSV] Error saving registration:`, error);
    throw error;
  }
}

// Save penalty record to CSV
function savePenaltyToCSV(penaltyData) {
  const filePath = path.join(__dirname, 'penalty_records.csv');
  
  try {
    const csvLine = `${new Date().toISOString()},${penaltyData.sessionId},"${penaltyData.name}",${penaltyData.email},${penaltyData.originalTransactionId},${penaltyData.penaltyAmount},${penaltyData.penaltyStatus},${penaltyData.penaltyTransactionId || 'N/A'},"${penaltyData.notes}"\n`;
    
    fs.appendFileSync(filePath, csvLine);
    console.log(`[savePenaltyToCSV] Penalty record saved for ${penaltyData.email}`);
  } catch (error) {
    console.error(`[savePenaltyToCSV] Error saving penalty:`, error);
    throw error;
  }
}

// Generate QR Code and send email
async function sendQRCodeEmail(registrationData) {
  try {
    console.log('[sendQRCodeEmail] Attempting to send QR code email for:', registrationData.email);
    console.log('[sendQRCodeEmail] Registration Data:', JSON.stringify(registrationData, null, 2));

    const qrCodeData = JSON.stringify({
      sessionId: registrationData.sessionId,
      name: registrationData.name,
      email: registrationData.email,
      plan: registrationData.plan,
      chapter: registrationData.chapter
    });

    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=300x300`;
    console.log('[sendQRCodeEmail] Generated QR Code URL:', qrCodeUrl);

    const templatePath = path.join(__dirname, 'email-templates', 'qr-code-template.php');
    let emailHtml = fs.readFileSync(templatePath, 'utf8');

    // Replace placeholders in the template
    emailHtml = emailHtml.replace(/<\?php echo htmlspecialchars\(\$name\); \?>/g, registrationData.name);
    emailHtml = emailHtml.replace(/<\?php echo \$qrCodeUrl; \?>/g, qrCodeUrl);
    emailHtml = emailHtml.replace(/<\?php echo htmlspecialchars\(\$chapter\); \?>/g, registrationData.chapter);
    emailHtml = emailHtml.replace(/<\?php echo htmlspecialchars\(\$registrationId\); \?>/g, registrationData.sessionId);
    // Conditional penalty message
    let penaltyMessageHtml = '';
    if (registrationData.noShowConsent && (registrationData.chapter === 'EO Dubai Member' || registrationData.chapter === 'EO Dubai Spouse')) {
        // Hardcode AED 3,999 for EO Dubai Members and Spouses as per feedback
        penaltyMessageHtml = `
            <p style="margin: 5px 0;"><strong>Important:</strong> As per your agreement, a no-show penalty of AED 3,999 will be charged if you do not attend the event.</p>
        `;
    }
    // The penalty amount is now handled conditionally within penaltyMessageHtml,
    // so this direct replacement is no longer needed and could cause confusion.
    // emailHtml = emailHtml.replace(/<\?php echo htmlspecialchars\(number_format\(\$penaltyAmount, 0, '\.', ','\)\); \?>/g, registrationData.penaltyAmount.toLocaleString('en-US'));
    // Replace placeholders in the template
    emailHtml = emailHtml.replace(/<\?php echo htmlspecialchars\(\$date\); \?>/g, '23-24 January 2026');
    emailHtml = emailHtml.replace(/<\?php echo htmlspecialchars\(\$venue\); \?>/g, 'Marriott Palm Jumeirah, Dubai');
    emailHtml = emailHtml.replace(/<\?php echo \$penaltyMessage; \?>/g, penaltyMessageHtml);

    const result = await resend.emails.send({
      from: fromEmail,
      to: [registrationData.email],
      subject: 'Your AI FOR BUSINESS Event QR Code - Registration Confirmed',
      html: emailHtml,
    });

    if (result.error) {
      console.error('[sendQRCodeEmail] Email sending failed:', result.error);
      return { success: false, error: result.error };
    }

    console.log('[sendQRCodeEmail] QR code email sent successfully to:', registrationData.email);
    console.log('[sendQRCodeEmail] Resend API result:', result);
    return { success: true, messageId: result.id };
  } catch (error) {
    console.error('[sendQRCodeEmail] Error sending email:', error);
    return { success: false, error: error.message };
  }
}

// Refund AED 1 for EO Dubai members/spouses
async function processRefund(orderRef, amount = '1.00') {
  try {
    console.log(`[processRefund] Initiating refund for order ${orderRef}, amount: AED ${amount}`);
    
    // Note: Telr refund API endpoint - verify with Telr documentation
    const refundParams = {
      ivp_method: 'refund',
      ivp_store: TELR_STORE_ID,
      ivp_authkey: TELR_AUTH_KEY,
      order_ref: orderRef,
      ivp_amount: amount,
      ivp_currency: 'AED'
    };

    const refundResponse = await axios.post(TELR_API_URL, querystring.stringify(refundParams), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Expect': ''
      },
      timeout: 30000
    });

    console.log('[processRefund] Refund response:', refundResponse.data);
    return { success: true, data: refundResponse.data };
  } catch (error) {
    console.error('[processRefund] Refund failed:', error.response?.data || error.message);
    return { success: false, error: error.message };
  }
}

const MEMBERSHIP_DB_PATH = path.join(__dirname, 'EO Dubai Database.csv');

async function isMemberInDatabase(email, phone) {
  return new Promise((resolve, reject) => {
    const members = [];
    fs.createReadStream(MEMBERSHIP_DB_PATH)
      .pipe(csv())
      .on('data', (row) => {
        members.push(row);
      })
      .on('end', () => {
        const normalizedEmail = email.toLowerCase().trim();
        const normalizedPhone = phone ? phone.replace(/\D/g, '') : '';

        for (const member of members) {
          const memberType = member['Member Type']?.trim();
          const memberEmail = member['Email ID']?.toLowerCase().trim();
          const memberPhone = member['Mobile'] ? member['Mobile'].replace(/\D/g, '') : '';

          const isTargetMemberType = ['EO Dubai Member', 'EO Dubai Spouse', 'EO Dubai Accelerator'].includes(memberType);

          if (isTargetMemberType) {
            if (memberEmail === normalizedEmail) {
              console.log(`[isMemberInDatabase] Matched email for ${memberType}: ${email}`);
              return resolve(true);
            }
            if (normalizedPhone && memberPhone === normalizedPhone) {
              console.log(`[isMemberInDatabase] Matched phone for ${memberType}: ${phone}`);
              return resolve(true);
            }
          }
        }
        resolve(false);
      })
      .on('error', (error) => {
        console.error('[isMemberInDatabase] Error reading membership database:', error);
        reject(error);
      });
  });
}

ensureCSVFiles();

// Create Telr checkout session
app.post('/api/create-checkout-session', cors(corsOptions), async (req, res) => {
  try {
    const { amount, currency, plan, name, email, phone, chapter, noShowConsent } = req.body;

    console.log('[create-checkout-session] Received request:', { amount, currency, plan, name, email, phone, chapter, noShowConsent });

    // Check for duplicate registration
    const isDuplicate = await checkExistingRegistration(email, phone);
    if (isDuplicate) {
      return res.status(400).json({ error: 'You have already registered for this event with this email or phone number.' });
    }

    // Generate unique session ID
    const sessionId = `AIWS-${Date.now()}-${Math.floor(Math.random() * 10000)}`;

    // Handle free registrations (amount === 0)
    if (parseFloat(amount) === 0) {
      console.log('DEBUG: Free registration. Bypassing Telr checkout.');

      const registrationData = {
        timestamp: new Date().toISOString(),
        sessionId: sessionId,
        name: name || 'N/A',
        email: email || 'N/A',
        phone: phone || 'N/A',
        chapter: chapter || 'N/A',
        plan: plan || 'regular',
        paymentAmount: '0.00',
        paymentCurrency: currency ? currency.toUpperCase() : 'AED',
        transactionId: 'FREE_REGISTRATION',
        telrCardToken: 'N/A',
        noShowConsent: noShowConsent || false,
        penaltyAmount: 0,
        registrationStatus: 'completed'
      };

      // Save to CSV
      saveRegistrationToCSV(registrationData);

      // Write to Google Sheet
      await writeFreeRegistrationToSheet(registrationData);

      // Send QR code email
      await sendQRCodeEmail(registrationData);

      // Redirect to thank you page
      const redirectUrl = `${FRONTEND_URL}/thanks.php#session_id=${sessionId}`;
      return res.redirect(302, redirectUrl);
    }

    // Validate inputs for paid registrations
    if (!amount || isNaN(amount) || amount <= 0) {
      return res.status(400).json({ error: 'Invalid amount provided' });
    }
    if (!currency || typeof currency !== 'string') {
      return res.status(400).json({ error: 'Invalid currency provided' });
    }

    if (!email || typeof email !== 'string') {
      return res.status(400).json({ error: 'Invalid email provided' });
    }

    const amountInDecimal = parseFloat(amount).toFixed(2);
    const backendUrl = isProduction 
      ? 'https://backend-production-c14ce.up.railway.app' 
      : `http://localhost:${PORT}`;

    // Split name
    const nameParts = name.trim().split(' ');
    const firstName = nameParts[0] || name;
    const lastName = nameParts.slice(1).join(' ') || 'User';

    // Telr parameters with card tokenization enabled
    const telrParams = {
      ivp_method: 'create',
      ivp_store: TELR_STORE_ID,
      ivp_authkey: TELR_AUTH_KEY,
      ivp_cart: sessionId,
      ivp_test: isProduction ? '0' : '1',
      ivp_amount: amountInDecimal,
      ivp_currency: currency.toUpperCase(),
      ivp_desc: `AI FOR BUSINESS - ${chapter}`,
      ivp_framed: '0',
      return_auth: `${backendUrl}/payment/success?session_id=${sessionId}`,
      return_decl: `${backendUrl}/payment/fail?session_id=${sessionId}`,
      return_can: `${backendUrl}/payment/cancel?session_id=${sessionId}`,
      bill_fname: firstName,
      bill_sname: lastName,
      bill_email: email,
      bill_addr1: 'UAE',
      bill_city: 'Dubai',
      bill_country: 'AE'
      // Temporarily disabled ivp_create_token to debug Telr E01:Invalid request
      // ivp_create_token: '1'
    };

    const isMember = await isMemberInDatabase(email, phone);

    const isVerifyTransaction = (
      isMember && amountInDecimal === '1.00'
    );

    if (isVerifyTransaction) {
      telrParams.ivp_type = 'verify';
      telrParams.ivp_amount = '1.00'; // Ensure amount is 1.00 for verify transactions
      telrParams.ivp_desc = 'Card Verification AED 1 (Refundable)';
      console.log('[create-checkout-session] Initiating Telr "verify" transaction for AED 1.');
    }

    if (phone) {
      telrParams.bill_phone = phone;
    }

    console.log('[create-checkout-session] Sending Telr params:', telrParams);

    const telrResponse = await axios.post(TELR_API_URL, querystring.stringify(telrParams), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Expect': ''
      },
      timeout: 30000
    });

    console.log('[create-checkout-session] Telr API response:', JSON.stringify(telrResponse.data, null, 2));

    if (telrResponse.data && telrResponse.data.error) {
      return res.status(400).json({ 
        error: 'Telr API error',
        details: telrResponse.data.error.message || 'Unknown error from Telr'
      });
    }

    if (telrResponse.data && telrResponse.data.order && telrResponse.data.order.url) {
      const orderRef = telrResponse.data.order.ref;
      
      // Save metadata including penalty info
      const metadataPath = path.join(__dirname, 'telr_transactions', `${orderRef}_metadata.json`);
      const metadata = {
        sessionId,
        plan,
        chapter,
        noShowConsent,
        email,
        name,
        phone,
        amount: amountInDecimal,
        currency: currency.toUpperCase(),
        penaltyAmount: noShowConsent ? 3999 : 0,
        requiresRefund: (chapter === 'EO Dubai Member' || chapter === 'EO Dubai Spouse'),
        createdAt: new Date().toISOString()
      };

      const metadataDir = path.join(__dirname, 'telr_transactions');
      if (!fs.existsSync(metadataDir)) {
        fs.mkdirSync(metadataDir, { recursive: true });
      }

      fs.writeFileSync(metadataPath, JSON.stringify(metadata, null, 2));

      res.json({
        redirectUrl: telrResponse.data.order.url,
        telrRef: orderRef,
        sessionId: sessionId
      });
    } else {
      throw new Error('Failed to get redirect URL from Telr');
    }
  } catch (error) {
    console.error('[create-checkout-session] Error:', error.message);
    res.status(500).json({ 
      error: 'Failed to create checkout session',
      details: error.message
    });
  }
});

// New endpoint for free registrations
app.post('/api/free-registration', cors(corsOptions), async (req, res) => {
  try {
    const { name, email, phone, chapter, noShowConsent, plan } = req.body;

    console.log('[free-registration] Received request:', { name, email, phone, chapter, noShowConsent, plan });

    // Check for duplicate registration
    const isDuplicate = await checkExistingRegistration(email, phone);
    if (isDuplicate) {
      return res.status(400).json({ error: 'You have already registered for this event with this email or phone number.' });
    }

    // Generate unique session ID
    const sessionId = `AIWS-${Date.now()}-${Math.floor(Math.random() * 10000)}`;

    const registrationData = {
      timestamp: new Date().toISOString(),
      sessionId: sessionId,
      name: name || 'N/A',
      email: email || 'N/A',
      phone: phone || 'N/A',
      chapter: chapter || 'N/A',
      plan: plan || 'regular',
      paymentAmount: '0.00',
      paymentCurrency: 'AED',
      transactionId: 'FREE_REGISTRATION',
      telrCardToken: 'N/A',
      noShowConsent: noShowConsent || false,
      penaltyAmount: 0,
      registrationStatus: 'completed'
    };

    // Save to CSV
    saveRegistrationToCSV(registrationData);

    // Write to Google Sheet
    await writeFreeRegistrationToSheet(registrationData);

    // Send QR code email
    await sendQRCodeEmail(registrationData);

    const redirectUrl = `${FRONTEND_URL}/thanks.php#session_id=${sessionId}`;
    res.json({ success: true, redirectUrl: redirectUrl });

  } catch (error) {
    console.error('[free-registration] Error:', error.message);
    res.status(500).json({
      error: 'Failed to process free registration',
      details: error.message
    });
  }
});


// Payment success handler
app.get('/payment/success', async (req, res) => {
  try {
    const sessionId = req.query.session_id;
    let orderRef = req.query.order_ref; // Try to get orderRef from query

    console.log('[payment/success] Processing successful payment:', { sessionId, orderRef });

    if (!sessionId) {
      console.error('[payment/success] Missing session_id');
      return res.redirect(`${FRONTEND_URL}/registration.php?error=missing_reference`);
    }

    // If orderRef is missing from query, try to find it from metadata files
    if (!orderRef) {
      console.log('[payment/success] orderRef missing from query, searching metadata files...');
      const metadataDir = path.join(__dirname, 'telr_transactions');
      if (fs.existsSync(metadataDir)) {
        const files = fs.readdirSync(metadataDir);
        for (const file of files) {
          if (file.endsWith('_metadata.json')) {
            const filePath = path.join(metadataDir, file);
            try {
              const metadataContent = fs.readFileSync(filePath, 'utf8');
              const metadata = JSON.parse(metadataContent);
              if (metadata.sessionId === sessionId) {
                orderRef = file.replace('_metadata.json', ''); // Extract orderRef from filename
                console.log(`[payment/success] Found orderRef ${orderRef} for sessionId ${sessionId} from metadata.`);
                break; // Found it, no need to search further
              }
            } catch (readError) {
              console.error(`[payment/success] Error reading or parsing metadata file ${file}:`, readError);
            }
          }
        }
      }
    }

    if (!orderRef) {
      console.error('[payment/success] Could not determine orderRef for sessionId:', sessionId);
      return res.redirect(`${FRONTEND_URL}/registration.php?error=order_ref_not_found`);
    }

    // Load metadata (now we have orderRef)
    const metadataPath = path.join(__dirname, 'telr_transactions', `${orderRef}_metadata.json`);
    let metadata = {};
    
    if (fs.existsSync(metadataPath)) {
      metadata = JSON.parse(fs.readFileSync(metadataPath, 'utf8'));
    } else {
      console.warn(`[payment/success] Metadata file not found for orderRef: ${orderRef}`);
      // This might happen if the metadata was not saved correctly, or if the orderRef was inferred.
      // Proceed with caution, some metadata fields might be missing.
    }

    // Verify transaction with Telr
    const telrParams = {
      ivp_method: 'check',
      ivp_store: TELR_STORE_ID,
      ivp_authkey: TELR_AUTH_KEY,
      order_ref: orderRef
    };

    const telrResponse = await axios.post(TELR_API_URL, querystring.stringify(telrParams), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      }
    });

    const telrOrder = telrResponse.data.order;

    if (telrOrder && telrOrder.status && (telrOrder.status.code === 3 || telrOrder.status.code === 2)) {
      // Payment successful - extract card token
      const cardToken = telrOrder.token || null;
      
      // Prepare registration data
      const registrationData = {
        timestamp: new Date().toISOString(), // Add timestamp
        sessionId: metadata.sessionId || sessionId,
        name: metadata.name || telrOrder.customer?.name?.forenames || 'N/A',
        email: metadata.email || telrOrder.customer?.email || 'N/A',
        phone: metadata.phone || telrOrder.customer?.address?.mobile || 'N/A',
        chapter: metadata.chapter || 'N/A',
        plan: metadata.plan || 'regular',
        paymentAmount: telrOrder.amount || metadata.amount || '0',
        paymentCurrency: telrOrder.currency || metadata.currency || 'AED',
        transactionId: telrOrder.transaction?.ref || orderRef,
        telrCardToken: cardToken,
        noShowConsent: metadata.noShowConsent || false,
        penaltyAmount: metadata.penaltyAmount || 0,
        registrationStatus: 'completed'
      };

      // Save to CSV
      saveRegistrationToCSV(registrationData);

      // Write to Google Sheet
      await writePaidRegistrationToSheet(registrationData);

      // Send QR code email
      await sendQRCodeEmail(registrationData);

      // Process refund for EO Dubai members/spouses (AED 1)
      if (metadata.requiresRefund) {
        console.log('[payment/success] Initiating refund for EO Dubai member/spouse');
        const refundResult = await processRefund(orderRef, '1.00');
        
        if (refundResult.success) {
          console.log('[payment/success] Refund processed successfully');
        } else {
          console.error('[payment/success] Refund failed:', refundResult.error);
          // Note: Registration is still valid, but log for manual refund
        }
      }

      // Redirect to thank you page using a URL fragment to avoid mod_security issues
      const redirectUrl = `${FRONTEND_URL}/thanks.php#session_id=${metadata.sessionId || sessionId}`;
      res.redirect(302, redirectUrl);
    } else {
      console.error('[payment/success] Payment not successful:', telrOrder.status);
      const redirectUrl = `${FRONTEND_URL}/registration.php?error=payment_failed`;
      res.redirect(302, redirectUrl);
    }
  } catch (error) {
    console.error('[payment/success] Error:', error);
    const redirectUrl = `${FRONTEND_URL}/registration.php?error=processing_failed`;
    res.redirect(302, redirectUrl);
  }
});

// Payment failure redirect
app.get('/payment/fail', (req, res) => {
  console.log('[payment/fail] Payment declined:', req.query);
  const sessionId = req.query.session_id;
  res.redirect(`${FRONTEND_URL}/registration.php?declined=true&session_id=${sessionId || 'unknown'}`);
});

// Payment cancellation redirect
app.get('/payment/cancel', (req, res) => {
  console.log('[payment/cancel] Payment cancelled:', req.query);
  const sessionId = req.query.session_id;
  res.redirect(`${FRONTEND_URL}/registration.php?cancelled=true&session_id=${sessionId || 'unknown'}`);
});

// Charge penalty for no-show
app.post('/api/charge-penalty', async (req, res) => {
  try {
    const { sessionId, notes } = req.body;

    console.log('[charge-penalty] Processing penalty for sessionId:', sessionId);

    if (!sessionId) {
      return res.status(400).json({ error: 'Session ID is required' });
    }

    // Find registration in CSV
    const registrationsFile = path.join(__dirname, 'registrations.csv');
    const csvContent = fs.readFileSync(registrationsFile, 'utf8');
    const lines = csvContent.split('\n');
    
    let registrationFound = null;
    for (let i = 1; i < lines.length; i++) {
      if (lines[i].includes(sessionId)) {
        const fields = lines[i].split(',');
        registrationFound = {
          sessionId: fields[1],
          name: fields[2].replace(/"/g, ''),
          email: fields[3],
          chapter: fields[5],
          originalTransactionId: fields[9],
          telrCardToken: fields[10],
          noShowConsent: fields[11] === 'true',
          penaltyAmount: parseFloat(fields[12]) || 0
        };
        break;
      }
    }

    if (!registrationFound) {
      return res.status(404).json({ error: 'Registration not found' });
    }

    if (!registrationFound.noShowConsent) {
      return res.status(400).json({ error: 'No penalty consent found for this registration' });
    }

    if (!registrationFound.telrCardToken || registrationFound.telrCardToken === 'N/A') {
      return res.status(400).json({ error: 'No card token found for charging penalty' });
    }

    // Charge penalty using stored card token
    const penaltyParams = {
      ivp_method: 'create',
      ivp_store: TELR_STORE_ID,
      ivp_authkey: TELR_AUTH_KEY,
      ivp_cart: `PENALTY-${sessionId}-${Date.now()}`,
      ivp_test: isProduction ? '0' : '1',
      ivp_amount: registrationFound.penaltyAmount.toFixed(2),
      ivp_currency: 'AED',
      ivp_desc: `No-Show Penalty - ${sessionId}`,
      ivp_token: registrationFound.telrCardToken,
      bill_email: registrationFound.email
    };

    console.log('[charge-penalty] Charging penalty with params:', penaltyParams);

    const telrResponse = await axios.post(TELR_API_URL, querystring.stringify(penaltyParams), {
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      timeout: 30000
    });

    console.log('[charge-penalty] Telr response:', telrResponse.data);

    if (telrResponse.data && telrResponse.data.order) {
      const penaltyTransactionId = telrResponse.data.order.ref;
      
      // Save penalty record
      const penaltyData = {
        sessionId: registrationFound.sessionId,
        name: registrationFound.name,
        email: registrationFound.email,
        originalTransactionId: registrationFound.originalTransactionId,
        penaltyAmount: registrationFound.penaltyAmount,
        penaltyStatus: 'charged',
        penaltyTransactionId: penaltyTransactionId,
        notes: notes || 'No-show penalty charged'
      };

      savePenaltyToCSV(penaltyData);

      res.json({ 
        success: true, 
        message: 'Penalty charged successfully',
        transactionId: penaltyTransactionId
      });
    } else {
      throw new Error('Failed to charge penalty');
    }
  } catch (error) {
    console.error('[charge-penalty] Error:', error);
    res.status(500).json({ 
      error: 'Failed to charge penalty',
      details: error.message 
    });
  }
});

// Get registrations (for admin)
app.get('/api/registrations', (req, res) => {
  try {
    const registrationsFile = path.join(__dirname, 'registrations.csv');
    
    if (!fs.existsSync(registrationsFile)) {
      return res.json({ registrations: [] });
    }

    const csvContent = fs.readFileSync(registrationsFile, 'utf8');
    res.setHeader('Content-Type', 'text/csv');
    res.setHeader('Content-Disposition', 'attachment; filename=registrations.csv');
    res.send(csvContent);
  } catch (error) {
    console.error('[api/registrations] Error:', error);
    res.status(500).json({ error: 'Failed to retrieve registrations' });
  }
});

// Get penalty records (for admin)
app.get('/api/penalties', (req, res) => {
  try {
    const penaltiesFile = path.join(__dirname, 'penalty_records.csv');
    
    if (!fs.existsSync(penaltiesFile)) {
      return res.json({ penalties: [] });
    }

    const csvContent = fs.readFileSync(penaltiesFile, 'utf8');
    res.setHeader('Content-Type', 'text/csv');
    res.setHeader('Content-Disposition', 'attachment; filename=penalty_records.csv');
    res.send(csvContent);
  } catch (error) {
    console.error('[api/penalties] Error:', error);
    res.status(500).json({ error: 'Failed to retrieve penalties' });
  }
});

// Get single registration by session ID
app.get('/api/registration/:sessionId', async (req, res) => {
  try {
    const { sessionId } = req.params;
    const registrationsFile = path.join(__dirname, 'registrations.csv');

    if (!fs.existsSync(registrationsFile)) {
      return res.status(404).json({ error: 'Registrations file not found' });
    }

    const csvContent = fs.readFileSync(registrationsFile, 'utf8');
    const lines = csvContent.split('\n');
    const headers = lines[0].split(',');

    let foundRegistration = null;
    for (let i = 1; i < lines.length; i++) {
      const line = lines[i].trim();
      if (line) {
        const fields = line.match(/(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|([^,]*))/g).map(field => field.replace(/^"|"$/g, '').replace(/""/g, '"'));
        if (fields[1] === sessionId) { // Assuming sessionId is the second field (index 1)
          foundRegistration = {};
          headers.forEach((header, index) => {
            foundRegistration[header.trim()] = fields[index];
          });
          break;
        }
      }
    }

    if (foundRegistration) {
      // Generate QR code URL for the found registration
      const qrCodeData = JSON.stringify({
        sessionId: foundRegistration.sessionId,
        name: foundRegistration.name,
        email: foundRegistration.email,
        plan: foundRegistration.plan,
        chapter: foundRegistration.chapter
      });
      foundRegistration.qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=300x300`;
      
      return res.json({ success: true, registration: foundRegistration });
    } else {
      return res.status(404).json({ error: 'Registration not found' });
    }
  } catch (error) {
    console.error('[api/registration/:sessionId] Error:', error);
    res.status(500).json({ error: 'Failed to retrieve registration details' });
  }
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

// Mount verify-user router with CORS
app.use('/api', cors(corsOptions), verifyUserRouter);

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
