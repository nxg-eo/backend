const { google } = require('googleapis');

// Google Sheet ID for paid registrations
const PAID_REGISTRATION_SPREADSHEET_ID = '1fYGoo61srzZGk4I1baffIAeVcQDldNlT2UuNAaLB1mQ';
// Google Sheet ID for free registrations
const FREE_REGISTRATION_SPREADSHEET_ID = '1YniUUzizIjG8UeUKGhbLAevunkQ7gcIq6GgNsSk_s-0';

async function getAuth() {
  // Prefer the JSON env
  const credentialsJson =
    process.env.GOOGLE_APPLICATION_CREDENTIALS_JSON ||
    (process.env.GOOGLE_APPLICATION_CREDENTIALS && fs.existsSync(process.env.GOOGLE_APPLICATION_CREDENTIALS)
      ? fs.readFileSync(process.env.GOOGLE_APPLICATION_CREDENTIALS, 'utf8')
      : null);

  if (!credentialsJson) {
    throw new Error('Google Sheets credentials are missing.');
  }

  let credentials;
  try {
    credentials = JSON.parse(credentialsJson);

    // Fix escaped newlines if present
    if (credentials.private_key.includes('\\n')) {
      credentials.private_key = credentials.private_key.replace(/\\n/g, '\n');
    }

    console.log('[GoogleSheets] Credentials loaded successfully');
  } catch (err) {
    console.error('Error parsing credentials JSON:', err);
    throw err;
  }

  try {
    const auth = new google.auth.GoogleAuth({
      credentials,
      scopes: ['https://www.googleapis.com/auth/spreadsheets'],
    });
    await auth.getClient();
    console.log('[GoogleSheets] Authentication successful');
    return auth;
  } catch (authError) {
    console.error('[GoogleSheets] Authentication failed:', authError.message);
    throw new Error('Google Sheets authentication failed: ' + authError.message);
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
