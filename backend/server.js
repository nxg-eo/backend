const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const verifyUserRouter = require('./routes/verify-user');
require('dotenv').config();
const stripe = require('stripe')(process.env.STRIPE_SECRET_KEY);
const { Resend } = require('resend');
const fs = require('fs');
const path = require('path');
const resend = new Resend(process.env.RESEND_API_KEY);
const fromEmail = process.env.FROM_EMAIL.match(/<([^>]+)>/)[1];

const app = express();
const PORT = process.env.PORT || 3001;

// Environment-based configuration
const isProduction = process.env.NODE_ENV === 'production';
const FRONTEND_URL = process.env.FRONTEND_URL || 'http://localhost:8000';

// CORS configuration for FTP hosted frontend
const corsOptions = {
  origin: [
    'http://localhost:8000', // For local development
    'https://eodubai.com',   // Your FTP domain
    'http://eodubai.com',    // Your FTP domain (if HTTP)
    'https://www.eodubai.com', // With www
    'http://www.eodubai.com',  // With www (if HTTP)
    FRONTEND_URL
  ],
  credentials: true,
  optionsSuccessStatus: 200,
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization']
};

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(cors(corsOptions)); // Updated CORS configuration

// Use routes
app.use('/api', verifyUserRouter);

// Helper function to ensure CSV files exist
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

// Initialize CSV files
ensureCSVFiles();

// Stripe Payment Endpoint for Payment Intent
app.post('/api/create-payment-intent', async (req, res) => {
  try {
    const { amount, currency } = req.body;

    console.log('Received payment intent request:', { amount, currency });

    // Validate inputs
    if (!amount || isNaN(amount) || amount <= 0) {
      console.error('Invalid amount:', amount);
      return res.status(400).json({ error: 'Invalid amount provided' });
    }

    // Validate currency
    if (!currency || typeof currency !== 'string') {
      console.error('Invalid currency:', currency);
      return res.status(400).json({ error: 'Invalid currency provided' });
    }

    // The amount is already in cents from the frontend (1500 = $15.00)
    // So we don't need to multiply by 100
    const amountInCents = parseInt(amount);

    console.log('Creating payment intent with amount:', amountInCents, 'currency:', currency);

    const paymentIntent = await stripe.paymentIntents.create({
      amount: amountInCents,
      currency: currency.toLowerCase(),
      payment_method_types: ['card'],
      metadata: {
        source: 'SYNAPSE Registration'
      }
    });

    console.log('Payment intent created successfully:', paymentIntent.id);

    res.json({
      clientSecret: paymentIntent.client_secret,
      amount: amountInCents,
      currency: currency.toLowerCase()
    });
  } catch (error) {
    console.error('Error creating payment intent:', error);
    res.status(500).json({ 
      error: 'Failed to create payment intent',
      details: error.message 
    });
  }
});

// Stripe Checkout Session Endpoint
app.post('/api/create-checkout-session', async (req, res) => {
  try {
    const { amount, currency, plan, name, email, phone, chapter } = req.body;

    console.log('Received checkout session request:', { amount, currency, plan, email });

    // Validate inputs
    if (!amount || isNaN(amount) || amount <= 0) {
      console.error('Invalid amount:', amount);
      return res.status(400).json({ error: 'Invalid amount provided' });
    }

    if (!currency || typeof currency !== 'string') {
      console.error('Invalid currency:', currency);
      return res.status(400).json({ error: 'Invalid currency provided' });
    }

    if (!plan || typeof plan !== 'string') {
      console.error('Invalid plan:', plan);
      return res.status(400).json({ error: 'Invalid plan provided' });
    }

    if (!email || typeof email !== 'string') {
      console.error('Invalid email:', email);
      return res.status(400).json({ error: 'Invalid email provided' });
    }

    const amountInCents = parseInt(amount);

    console.log('Creating checkout session with amount:', amountInCents, 'currency:', currency);

    // Updated success and cancel URLs for production
    const successUrl = `${FRONTEND_URL}/thanks.php?session_id={CHECKOUT_SESSION_ID}`;
    const cancelUrl = `${FRONTEND_URL}/registration.php?cancelled=true`; 

    const session = await stripe.checkout.sessions.create({
      payment_method_types: ['card'],
      line_items: [{
        price_data: {
          currency: currency.toLowerCase(),
          product_data: {
            name: plan === 'vip' ? 'SYNAPSE VIP Pass' : 'SYNAPSE Regular Pass',
            description: `Registration for SYNAPSE Event - ${plan === 'vip' ? 'VIP' : 'Regular'} Pass`
          },
          unit_amount: amountInCents,
        },
        quantity: 1,
      }],
      mode: 'payment',
      success_url: successUrl,
      cancel_url: cancelUrl,
      customer_email: email,
      metadata: {
        source: 'SYNAPSE Registration',
        plan: plan,
        name: name || 'N/A',
        phone: phone || 'N/A',
        chapter: chapter || 'N/A'
      }
    });

    console.log('Checkout session created successfully:', session.id);

    // Generate QR Code URL
    const qrCodeData = JSON.stringify({
      sessionId: session.id,
      name: name,
      email: email,
      plan: plan,
    });
    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=200x200`;

    // Send email using Resend
    try {
      const result = await resend.emails.send({
        from: process.env.FROM_EMAIL,
        to: [email],
        subject: 'Your SYNAPSE Event QR Code',
        html: `
          <html>
            <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
              <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <h1 style="color: #2c3e50; text-align: center;">Your SYNAPSE Event QR Code</h1>
                <p>Dear ${name || 'Guest'},</p>
                <p>Thank you for registering for the SYNAPSE event! Your <strong>${plan || 'regular'}</strong> pass is confirmed.</p>
                <div style="text-align: center; margin: 30px 0;">
                  <img src="${qrCodeUrl}" alt="QR Code" style="border: 1px solid #ddd; padding: 10px;" />
                </div>
                <p>Please present this QR code at the event entrance.</p>
                <p>Best regards,<br>The SYNAPSE Team</p>
                <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
                <p style="font-size: 12px; color: #666;">Session ID: ${session.id}</p>
              </div>
            </body>
          </html>
        `,
      });

      if (result.error) {
        console.error('Email sending failed:', result.error);
      } else {
        console.log('QR code email sent successfully:', result);
      }
    } catch (emailError) {
      console.error('Error sending QR code email:', emailError);
    }

    res.json({
      sessionId: session.id
    });

  } catch (error) {
    console.error('Error creating checkout session:', error);
    res.status(500).json({ 
      error: 'Failed to create checkout session',
      details: error.message 
    });
  }
});

// Endpoint to record attendance
app.post('/api/record-attendance', async (req, res) => {
  try {
    const { sessionId, attended } = req.body;

    console.log('Received attendance record request:', { sessionId, attended });

    // Validate input
    if (!sessionId) {
      console.error('Invalid session ID:', sessionId);
      return res.status(400).json({ error: 'Invalid session ID provided' });
    }

    // Retrieve session data from Stripe to get user details
    const session = await stripe.checkout.sessions.retrieve(sessionId);
    if (!session) {
      console.error('Session not found:', sessionId);
      return res.status(404).json({ error: 'Session not found' });
    }

    const userData = {
      sessionId: sessionId,
      name: session.metadata.name || 'N/A',
      email: session.customer_email || 'N/A',
      phone: session.metadata.phone || 'N/A',
      chapter: session.metadata.chapter || 'N/A',
      plan: session.metadata.plan || 'N/A',
      paymentAmount: session.amount_total / 100, // Convert from cents to dollars
      paymentCurrency: session.currency.toUpperCase(),
      transactionId: session.payment_intent || 'N/A',
      dateRegistered: new Date().toISOString()
    };

    // Read the appropriate CSV file based on attendance
    const filePath = path.join(__dirname, attended ? 'attendance_present.csv' : 'attendance_absent.csv');
    
    try {
      const csvContent = fs.readFileSync(filePath, 'utf8');
      const lines = csvContent.split('\n');
      const header = lines[0];
      const dataLines = lines.slice(1).filter(line => line.trim() !== '');

      // Add the new entry
      const newLine = `${userData.sessionId},${userData.name},${userData.email},${userData.phone},${userData.chapter},${userData.plan},${userData.paymentAmount},${userData.paymentCurrency},${userData.transactionId},${userData.dateRegistered}` + (attended ? ',,' : ',1250,Pending');
      dataLines.push(newLine);

      // Write back to the CSV file
      fs.writeFileSync(filePath, header + '\n' + dataLines.join('\n') + '\n');

      if (!attended) {
        // Apply penalty for absent users
        try {
          const penaltyIntent = await stripe.paymentIntents.create({
            amount: 125000, // 1250 USD in cents
            currency: 'usd',
            customer: session.customer || undefined,
            payment_method_types: ['card'],
            metadata: {
              source: 'SYNAPSE Penalty for No-Show',
              originalSessionId: sessionId
            }
          });

          console.log('Penalty payment intent created successfully:', penaltyIntent.id);

          // Update penalty status in absent CSV
          const absentCsvPath = path.join(__dirname, 'attendance_absent.csv');
          const absentCsv = fs.readFileSync(absentCsvPath, 'utf8');
          const absentLines = absentCsv.split('\n');
          const updatedLines = absentLines.map(line => {
            if (line.startsWith(sessionId + ',')) {
              const parts = line.split(',');
              parts[parts.length - 1] = 'Processed';
              return parts.join(',');
            }
            return line;
          });
          fs.writeFileSync(absentCsvPath, updatedLines.join('\n'));

          res.json({
            success: true,
            penaltyIntentId: penaltyIntent.id,
            clientSecret: penaltyIntent.client_secret
          });
        } catch (error) {
          console.error('Error creating penalty payment intent:', error);
          res.status(500).json({ 
            error: 'Failed to create penalty payment intent',
            details: error.message 
          });
        }
      } else {
        res.json({ success: true });
      }
    } catch (fileError) {
      console.error('Error reading/writing CSV file:', fileError);
      res.status(500).json({ 
        error: 'Failed to process attendance record',
        details: fileError.message 
      });
    }
  } catch (error) {
    console.error('Error recording attendance:', error);
    res.status(500).json({ 
      error: 'Failed to record attendance',
      details: error.message 
    });
  }
});

// Health check endpoint
app.get('/api/health', (req, res) => {
  res.json({ 
    status: 'OK', 
    timestamp: new Date().toISOString(),
    environment: process.env.NODE_ENV || 'development',
    frontendUrl: FRONTEND_URL
  });
});

// Email test endpoint
app.get('/api/email-test', async (req, res) => {
  try {
    console.log('=== EMAIL TEST ===');
    console.log('FROM_EMAIL:', process.env.FROM_EMAIL);
    console.log('RESEND_API_KEY:', process.env.RESEND_API_KEY ? 'Present' : 'Missing');
    
    const { toEmail } = req.query;
    if (!toEmail) {
      return res.status(400).json({ success: false, error: 'toEmail query parameter is required' });
    }

    const result = await resend.emails.send({
      from: fromEmail,
      to: [toEmail],
      subject: 'Test Email from SYNAPSE',
      html: `
        <h1>Test Email Success!</h1>
        <p>If you receive this, your email configuration is working.</p>
        <p>From: ${process.env.FROM_EMAIL}</p>
        <p>Environment: ${process.env.NODE_ENV || 'development'}</p>
        <p>Time: ${new Date().toISOString()}</p>
      `,
    });

    console.log('Email result:', result);
    
    if (result.error) {
      console.error('Resend error:', result.error);
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
    console.error('Email test error:', error);
    res.status(500).json({ 
      success: false, 
      error: error.message 
    });
  }
});

// Send confirmation email endpoint
app.post('/api/send-confirmation-email', async (req, res) => {
  try {
    const { sessionId } = req.body;

    if (!sessionId) {
      return res.status(400).json({ error: 'Session ID is required' });
    }

    const session = await stripe.checkout.sessions.retrieve(sessionId);

    if (session.payment_status === 'paid') {
      const qrCodeData = JSON.stringify({
        sessionId: session.id,
        name: session.metadata.name,
        email: session.customer_email,
        plan: session.metadata.plan,
      });

      const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrCodeData)}&size=200x200`;

      const emailHtml = `
        <html>
          <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
              <h1 style="color: #2c3e50; text-align: center;">Your SYNAPSE Event QR Code</h1>
              <p>Dear ${session.metadata.name || 'Guest'},</p>
              <p>Thank you for registering for the SYNAPSE event! Your <strong>${session.metadata.plan || 'regular'}</strong> pass is confirmed.</p>
              <div style="text-align: center; margin: 30px 0;">
                <img src="${qrCodeUrl}" alt="QR Code" style="border: 1px solid #ddd; padding: 10px;" />
              </div>
              <p>Please present this QR code at the event entrance.</p>
              <p>Best regards,<br>The SYNAPSE Team</p>
              <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
              <p style="font-size: 12px; color: #666;">Session ID: ${session.id}</p>
            </div>
          </body>
        </html>
      `;

      const result = await resend.emails.send({
        from: fromEmail,
        to: [session.customer_email],
        subject: 'Your SYNAPSE Event QR Code',
        html: emailHtml,
      });

      if (result.error) {
        console.error('Email sending failed:', result.error);
        return res.status(500).json({ error: 'Failed to send email' });
      }

      res.json({ success: true, message: 'Email sent successfully' });
    } else {
      res.status(400).json({ error: 'Payment not successful' });
    }
  } catch (error) {
    console.error('Error sending confirmation email:', error);
    res.status(500).json({ error: 'Failed to send confirmation email' });
  }
});

// Error handling middleware
app.use((err, req, res, next) => {
  console.error('Unhandled error:', err);
  res.status(500).json({
    error: 'Internal server error',
    message: isProduction ? 'Something went wrong' : err.message
  });
});

// 404 handler
app.use((req, res) => {
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
  console.log(`CORS enabled for: ${corsOptions.origin}`);
});

module.exports = app;
