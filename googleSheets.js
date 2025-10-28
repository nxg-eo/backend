const { google } = require('googleapis');
const path = require('path');

// Path to your service account JSON key file
const KEYFILEPATH = path.join(__dirname, '../eo-payments-tracking-94a63ba12b44.json');

// Google Sheet ID (from the sheet’s URL)
const SPREADSHEET_ID = '1YniUUzizIjG8UeUKGhbLAevunkQ7gcIq6GgNsSk_s-0';

async function writeToSheet(transactionData) {
  const auth = new google.auth.GoogleAuth({
    keyFile: KEYFILEPATH,
    scopes: ['https://www.googleapis.com/auth/spreadsheets'],
  });

  const sheets = google.sheets({ version: 'v4', auth });

  const values = [
    [
      transactionData.timestamp,
      transactionData.sessionId,
      transactionData.name,
      transactionData.email,
      transactionData.phone,
      transactionData.chapter,
      transactionData.plan,
      transactionData.paymentAmount,
      transactionData.paymentCurrency,
      transactionData.transactionId,
      transactionData.telrCardToken,
      transactionData.noShowConsent,
      transactionData.penaltyAmount,
      transactionData.registrationStatus,
    ],
  ];

  await sheets.spreadsheets.values.append({
    spreadsheetId: SPREADSHEET_ID,
    range: 'Sheet1!A:N', // Adjust based on your columns (14 columns)
    valueInputOption: 'USER_ENTERED',
    requestBody: { values },
  });

  console.log('Transaction written to Google Sheet!');
}

module.exports = { writeToSheet };
