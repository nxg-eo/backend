const express = require('express');
const router = express.Router();
const fs = require('fs');
const csv = require('csv-parser');
const path = require('path');

// Define paths for the new CSV databases
const MEMBERSHIP_DB_PATH = path.join(__dirname, '../EO Dubai Database.csv');

// Utility function to normalize member type strings
function normalizeMemberType(memberType) {
  // console.log(`[normalizeMemberType] Input: '${memberType}'`); // Commented out for reduced logging
  if (!memberType) {
    // console.log(`[normalizeMemberType] Output: 'guest' (due to empty input)`); // Commented out for reduced logging
    return 'guest';
  }
  
  const normalized = memberType.toLowerCase().trim();
  
  if (normalized.includes('eo dubai member') || normalized === 'member') {
    // console.log(`[normalizeMemberType] Output: 'eo dubai member'`); // Commented out for reduced logging
    return 'eo dubai member';
  }
  if (normalized.includes('eo dubai spouse') || normalized.includes('spouse') || normalized.includes('partner')) {
    // console.log(`[normalizeMemberType] Output: 'eo dubai spouse'`); // Commented out for reduced logging
    return 'eo dubai spouse';
  }
  if (normalized.includes('eo dubai accelerator') || normalized.includes('accelerator')) {
    // console.log(`[normalizeMemberType] Output: 'eo dubai accelerator'`); // Commented out for reduced logging
    return 'eo dubai accelerator';
  }
  if (normalized.includes('eo dubai next gen') || normalized.includes('next gen')) {
    // console.log(`[normalizeMemberType] Output: 'eo dubai next gen'`); // Commented out for reduced logging
    return 'eo dubai next gen';
  }
  if (normalized.includes('eo global key executive')) {
    // console.log(`[normalizeMemberType] Output: 'eo global key executive'`); // Commented out for reduced logging
    return 'eo global key executive';
  }
  if (normalized.includes('eo dubai key executive') || normalized.includes('key executive')) {
    // console.log(`[normalizeMemberType] Output: 'eo dubai key executive'`); // Commented out for reduced logging
    return 'eo dubai key executive';
  }
  
  // console.log(`[normalizeMemberType] Output: 'guest' (default)`); // Commented out for reduced logging
  return 'guest';
}

// Function to get proper display name for member type
function getMemberTypeDisplay(normalizedType) {
  const displayNames = {
    'eo dubai member': 'EO Dubai Member',
    'eo dubai spouse': 'EO Dubai Spouse',
    'eo dubai accelerator': 'EO Dubai Accelerator',
    'eo dubai next gen': 'EO Dubai Next Gen',
    'eo dubai key executive': 'EO Dubai Key Executive',
    'eo global key executive': 'EO Global Key Executive',
    'guest': 'Guest'
  };
  return displayNames[normalizedType] || 'Guest';
}

// Function to read the consolidated CSV and find a user
function findUserInConsolidatedCSV(email, phone) {
  return new Promise((resolve, reject) => {
    const users = [];
    fs.createReadStream(MEMBERSHIP_DB_PATH)
      .pipe(csv())
      .on('data', (row) => {
        users.push(row);
      })
      .on('end', () => {
        const normalizedEmail = email.toLowerCase().trim();
        const normalizedPhone = phone ? phone.replace(/\D/g, '') : '';

        for (const user of users) {
          const memberType = (user['Member Type'] || user['﻿Member Type'] || user['MemberType'] || '').trim();
          const userEmail = user['Email ID']?.toLowerCase().trim();
          const userPhone = user['Mobile'] ? user['Mobile'].replace(/\D/g, '') : '';

          // console.log(`[findUserInConsolidatedCSV] Checking row: Member Type: '${memberType}', Email: '${userEmail}', Phone: '${userPhone}'`); // Commented out for reduced logging
          // console.log(`[findUserInConsolidatedCSV] Comparing with: Normalized Email: '${normalizedEmail}', Normalized Phone: '${normalizedPhone}'`); // Commented out for reduced logging

          if (userEmail === normalizedEmail || (normalizedPhone && userPhone === normalizedPhone)) {
            // console.log(`[findUserInConsolidatedCSV] Match found for input email: '${email}', input phone: '${phone}'.`); // Commented out for reduced logging
            // console.log(`[findUserInConsolidatedCSV] Matched user data from CSV:`, user); // Commented out for reduced logging
            // console.log(`[findUserInConsolidatedCSV] Resolved member_type: '${getMemberTypeDisplay(normalizeMemberType(memberType))}'`); // Commented out for reduced logging
            return resolve({
              found: true,
              email: userEmail,
              name: user['Name'],
              phone: user['Mobile'],
              member_type: getMemberTypeDisplay(normalizeMemberType(memberType)),
              original_member_type: normalizeMemberType(memberType),
              plan: 'regular'
            });
          }
        }
        // console.log(`[findUserInConsolidatedCSV] No match found for input email: '${email}', input phone: '${phone}'.`); // Commented out for reduced logging
        resolve({ found: false });
      })
      .on('error', (error) => {
        console.error(`Error reading consolidated CSV file ${MEMBERSHIP_DB_PATH}:`, error);
        reject(error);
      });
  });
}

// GET or POST /verify-user
router.all('/verify-user', async (req, res) => {
  try {
    const email_input = (req.body.email || req.query.email || '').trim();
    
    if (!email_input) {
      return res.status(400).json({ 
        error: 'Email is required',
        success: false 
      });
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email_input)) {
      return res.status(400).json({ 
        error: 'Invalid email format',
        success: false 
      });
    }
    
    let userInfo = { 
      found: false, 
      email: email_input, 
      name: '', 
      phone: '', 
      member_type: 'Guest', 
      original_member_type: null, 
      plan: 'regular' 
    };
    const phone_input = (req.body.phone || req.query.phone || '').trim();

    // Check consolidated EO Dubai Database CSV
    try {
      const memberInfo = await findUserInConsolidatedCSV(email_input, phone_input);
      if (memberInfo.found) {
        userInfo = memberInfo;
      }
    } catch (error) {
      console.warn(`Could not read consolidated EO Dubai Database CSV: ${error.message}`);
    }
    
    const discountMessages = {
      'EO Dubai Member': 'As an EO Dubai Member, your entry fee is discounted!',
      'EO Dubai Spouse': 'As an EO Dubai Spouse, your entry fee is discounted!',
      'EO Dubai Accelerator': 'As an EO Dubai Accelerator, your entry fee is discounted!',
      'EO Dubai Next Gen': 'As an EO Dubai Next Gen, your entry fee is discounted!',
      'EO Dubai Key Executive': 'As an EO Dubai Key Executive, your entry fee is discounted!',
      'EO Global Key Executive': 'As an EO Global Key Executive, your entry fee is discounted!',
      'Guest': 'Standard pricing applies (no discount)'
    };
    
    const prices = {
      'EO Dubai Member': { 
        amount: 0,
        penalty: 3999,
        currency: 'AED'
      },
      'EO Dubai Spouse': { 
        amount: 0,
        penalty: 3999,
        currency: 'AED'
      },
      'EO Dubai Accelerator': { 
        amount: 3999,
        currency: 'AED'
      },
      'EO Dubai Next Gen': { 
        amount: 3999,
        currency: 'AED'
      },
      'EO Dubai Key Executive': {
        amount: 2999,
        currency: 'AED'
      },
      'EO Global Key Executive': {
        amount: 3999,
        currency: 'AED'
      },
      'Guest': {
        amount: 5999,
        currency: 'AED'
      }
    };
    
    const response = {
      success: true,
      email: userInfo.email,
      name: userInfo.name,
      phone: userInfo.phone,
      member_type: userInfo.member_type,
      is_member: userInfo.found,
      discount_message: discountMessages[userInfo.member_type],
      pricing: prices[userInfo.member_type],
      plan: userInfo.plan,
      verification_timestamp: new Date().toISOString(),
      // Autopopulate fields for form
      autopopulate: {
        name: userInfo.name || '',
        phone: userInfo.phone || '',
        member_type: userInfo.member_type || 'Guest',
        email: userInfo.email
      }
    };
    
    // console.log(`[verify-user route] Input email: ${email_input}, Input phone: ${phone_input}`); // Commented out for reduced logging
    // console.log(`[verify-user route] Final userInfo:`, userInfo); // Commented out for reduced logging
    // console.log(`[verify-user route] User verification response for ${userInfo.email}:`, response); // Commented out for reduced logging
    
    return res.json(response);
    
  } catch (error) {
    console.error('Error in user verification:', error);
    return res.status(500).json({ 
      error: 'Internal server error during user verification',
      success: false 
    });
  }
});

// Additional route to get all member types (for debugging/admin)
router.get('/member-types', async (req, res) => {
  try {
    const allMemberTypes = [
      'EO Dubai Member',
      'EO Dubai Spouse',
      'EO Dubai Accelerator',
      'EO Dubai Next Gen',
      'EO Dubai Key Executive',
      'EO Global Key Executive',
      'Guest'
    ];
    res.json({
      success: true,
      member_types: allMemberTypes.sort()
    });
  } catch (error) {
    console.error('Error in /member-types route:', error);
    res.status(500).json({ 
      error: 'Internal server error',
      success: false 
    });
  }
});

module.exports = router;
