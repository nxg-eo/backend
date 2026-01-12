const { google } = require('googleapis');
// Google Sheet ID for paid registrations
const PAID_REGISTRATION_SPREADSHEET_ID = '1fYGoo61srzZGk4I1baffIAeVcQDldNlT2UuNAaLB1mQ';
// Google Sheet ID for free registrations
const FREE_REGISTRATION_SPREADSHEET_ID = '1YniUUzizIjG8UeUKGhbLAevunkQ7gcIq6GgNsSk_s-0';

async function getAuth() {
  try {
    const credentials = {
      type: 'service_account',
      project_id: process.env.GOOGLE_PROJECT_ID,
      private_key_id: process.env.GOOGLE_PRIVATE_KEY_ID,
      private_key: Buffer.from(process.env.GOOGLE_PRIVATE_KEY, 'base64').toString('utf8'),
      client_email: process.env.GOOGLE_CLIENT_EMAIL,
      client_id: process.env.GOOGLE_CLIENT_ID, // Assuming CLIENT_ID is also in .env
      auth_uri: 'https://accounts.google.com/o/oauth2/auth',
      token_uri: 'https://oauth2.googleapis.com/token',
      auth_provider_x509_cert_url: 'https://www.googleapis.com/oauth2/v1/certs',
      client_x509_cert_url: `https://www.googleapis.com/robot/v1/metadata/x509/${process.env.GOOGLE_CLIENT_EMAIL}`,
      universe_domain: 'googleapis.com',
    };

    console.log('[GoogleSheets] Credentials loaded successfully from environment variables');
    console.log('[GoogleSheets] Client email:', credentials.client_email);

    const auth = new google.auth.GoogleAuth({
      credentials,
      scopes: ['https://www.googleapis.com/auth/spreadsheets'],
    });

    await auth.getClient();
    console.log('[GoogleSheets] Authentication successful');

    return auth;
  } catch (error) {
    console.error('[GoogleSheets] Authentication failed:', error.message);
    throw new Error('Google Sheets authentication failed: ' + error.message);
  }
}

async function writePaidRegistrationToSheet(registrationData) {
  try {
    const auth = await getAuth();
    const sheets = google.sheets({ version: 'v4', auth });

    const values = [
      [
        registrationData.timestamp,
        registrationData.sessionId,
        registrationData.name,
        registrationData.email,
        registrationData.phone,
        registrationData.chapter,
        registrationData.plan,
        registrationData.paymentAmount,
        registrationData.paymentCurrency,
        registrationData.transactionId,
        registrationData.telrCardToken,
        registrationData.noShowConsent,
        registrationData.penaltyAmount,
        registrationData.registrationStatus,
      ],
    ];

    await sheets.spreadsheets.values.append({
      spreadsheetId: PAID_REGISTRATION_SPREADSHEET_ID,
      range: 'Sheet1!A:N', // 14 columns
      valueInputOption: 'USER_ENTERED',
      requestBody: { values },
    });

    console.log('[GoogleSheets] Paid registration written successfully!');
  } catch (error) {
    console.error('[GoogleSheets] Error writing paid registration:', error);
    throw error;
  }
}

async function writeFreeRegistrationToSheet(registrationData) {
  try {
    const auth = await getAuth();
    const sheets = google.sheets({ version: 'v4', auth });

    const values = [
      [
        registrationData.chapter, // Member Type
        registrationData.name,    // Name
        registrationData.phone,   // Mobile
        registrationData.email,   // Email ID
      ],
    ];

    await sheets.spreadsheets.values.append({
      spreadsheetId: FREE_REGISTRATION_SPREADSHEET_ID,
      range: 'Sheet1!A:D', // 4 columns
      valueInputOption: 'USER_ENTERED',
      requestBody: { values },
    });

    console.log('[GoogleSheets] Free registration written successfully!');
  } catch (error) {
    console.error('[GoogleSheets] Error writing free registration:', error);
    throw error;
  }
}

module.exports = {
  writePaidRegistrationToSheet,
  writeFreeRegistrationToSheet,
};
