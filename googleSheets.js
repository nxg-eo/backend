const { google } = require('googleapis');

// Google Sheet ID for paid registrations
const PAID_REGISTRATION_SPREADSHEET_ID = '1fYGoo61srzZGk4I1baffIAeVcQDldNlT2UuNAaLB1mQ';
// Google Sheet ID for free registrations
const FREE_REGISTRATION_SPREADSHEET_ID = '1YniUUzizIjG8UeUKGhbLAevunkQ7gcIq6GgNsSk_s-0';

async function getAuth() {
  const credentials = {
    type: "service_account",
    project_id: process.env.GOOGLE_PROJECT_ID,
    private_key_id: process.env.GOOGLE_PRIVATE_KEY_ID,
    private_key: process.env.GOOGLE_PRIVATE_KEY,
    client_email: process.env.GOOGLE_CLIENT_EMAIL,
    token_uri: "https://oauth2.googleapis.com/token",
  };

  try {
    const auth = new google.auth.GoogleAuth({
      credentials,
      scopes: ['https://www.googleapis.com/auth/spreadsheets'],
    });

    await auth.getClient(); // test auth
    console.log('[GoogleSheets] ✅ Authentication successful');
    return auth;
  } catch (error) {
    console.error('[GoogleSheets] ❌ Authentication failed:', error.message);
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
