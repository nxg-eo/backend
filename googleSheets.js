const { google } = require('googleapis');
const path = require('path');

// Google Sheet ID for paid registrations
const PAID_REGISTRATION_SPREADSHEET_ID = '1fYGoo61srzZGk4I1baffIAeVcQDldNlT2UuNAaLB1mQ';
// Google Sheet ID for free registrations
const FREE_REGISTRATION_SPREADSHEET_ID = '1YniUUzizIjG8UeUKGhbLAevunkQ7gcIq6GgNsSk_s-0';

async function getAuth() {
  const credentialsJson = process.env.GOOGLE_APPLICATION_CREDENTIALS;

  if (!credentialsJson) {
    console.error('GOOGLE_APPLICATION_CREDENTIALS environment variable is not set.');
    throw new Error('Google Sheets credentials are missing.');
  }

  let credentials;
  try {
    credentials = JSON.parse(credentialsJson);
    // Ensure private_key newlines are correctly interpreted
    if (credentials.private_key) {
      credentials.private_key = credentials.private_key.replace(/\\n/g, '\n');
    }
  } catch (error) {
    console.error('Error parsing GOOGLE_APPLICATION_CREDENTIALS JSON:', error);
    throw new Error('Invalid Google Sheets credentials JSON.');
  }

  const auth = new google.auth.GoogleAuth({
    credentials,
    scopes: ['https://www.googleapis.com/auth/spreadsheets'],
  });
  return auth;
}

async function writePaidRegistrationToSheet(registrationData) {
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

  console.log('Paid registration written to Google Sheet!');
}

async function writeFreeRegistrationToSheet(registrationData) {
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

  console.log('Free registration written to Google Sheet!');
}

module.exports = {
  writePaidRegistrationToSheet,
  writeFreeRegistrationToSheet,
};
