const express = require('express');
const router = express.Router();
const fs = require('fs');
const csv = require('csv-parser');
const path = require('path');

// Define paths for the new CSV databases
const EO_MEMBERS_CSV = path.join(__dirname, '../EO Dubai database 22nd Oct.xlsx - EO Dubai Members 2025-26.csv');
const ACCELERATORS_CSV = path.join(__dirname, '../EO Dubai database 22nd Oct.xlsx - Accelerators 2025-26.csv');

// Utility function to normalize member type strings
function normalizeMemberType(memberType) {
  if (!memberType) return 'guest';
  
  const normalized = memberType.toLowerCase().trim();
  
  if (normalized.includes('eo dubai member') || normalized === 'member') {
    return 'eo dubai member';
  }
  if (normalized.includes('eo dubai spouse') || normalized.includes('spouse') || normalized.includes('partner')) {
    return 'eo dubai spouse';
  }
  if (normalized.includes('eo dubai accelerator') || normalized.includes('accelerator')) {
    return 'eo dubai accelerator';
  }
  if (normalized.includes('eo dubai next gen') || normalized.includes('next gen')) {
    return 'eo dubai next gen';
  }
  if (normalized.includes('eo dubai key executive') || normalized.includes('key executive')) {
    return 'eo dubai key executive';
  }
  
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
    'guest': 'Guest'
  };
  return displayNames[normalizedType] || 'Guest';
}

// Function to read a specific CSV and find a user
function findUserInCSV(email, csvFilePath, memberCategory) {
  return new Promise((resolve, reject) => {
    const users = [];
    fs.createReadStream(csvFilePath)
      .pipe(csv({ 
        mapHeaders: ({ header }) => {
          const trimmedHeader = header.trim();
          if (trimmedHeader === 'MEMBER E-MAIL' || trimmedHeader === 'EMAIL') return 'Email';
          if (trimmedHeader === 'MEMBER') return 'Name';
          if (trimmedHeader === 'MOBILE' || trimmedHeader === 'Mobile') return 'Phone';
          if (trimmedHeader === 'SPOUSE E-MAIL') return 'Spouse Email';
          if (trimmedHeader === 'SPOUSE') return 'Spouse Name';
          if (trimmedHeader === 'SPOUSE MOBILE') return 'Spouse Phone';
          return trimmedHeader;
        }
      }))
      .on('data', (row) => {
        users.push(row);
      })
      .on('end', () => {
        let foundUser = null;
        let memberType = memberCategory;

        // Check for member email
        foundUser = users.find(user => user.Email && user.Email.toLowerCase().trim() === email.toLowerCase().trim());
        if (foundUser) {
          // If found in EO Members CSV, check if it's a spouse email
          if (memberCategory === 'EO Dubai Member' && foundUser['Spouse Email'] && foundUser['Spouse Email'].toLowerCase().trim() === email.toLowerCase().trim()) {
            memberType = 'EO Dubai Spouse';
            // Use spouse's name and phone if email matches spouse email
            foundUser.Name = foundUser['Spouse Name'] || foundUser.Name;
            foundUser.Phone = foundUser['Spouse Phone'] || foundUser.Phone;
          }
          resolve({
            found: true,
            email: foundUser.Email,
            name: foundUser.Name,
            phone: foundUser.Phone,
            member_type: getMemberTypeDisplay(memberType),
            original_member_type: memberType,
            plan: 'regular' // Default plan, will be overridden by pricing logic
          });
          return;
        }

        // If not found as a member, check for spouse email in EO Members CSV
        if (memberCategory === 'EO Dubai Member') {
          foundUser = users.find(user => user['Spouse Email'] && user['Spouse Email'].toLowerCase().trim() === email.toLowerCase().trim());
          if (foundUser) {
            resolve({
              found: true,
              email: foundUser['Spouse Email'],
              name: foundUser['Spouse Name'] || foundUser.MEMBER, // Fallback to MEMBER if Spouse Name is empty
              phone: foundUser['Spouse Phone'] || foundUser.MOBILE, // Fallback to MOBILE if Spouse Phone is empty
              member_type: getMemberTypeDisplay('eo dubai spouse'),
              original_member_type: 'eo dubai spouse',
              plan: 'regular'
            });
            return;
          }
        }
        
        resolve({ found: false });
      })
      .on('error', (error) => {
        console.error(`Error reading CSV file ${csvFilePath}:`, error);
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
    
    let userInfo = { found: false, email: email_input, name: '', phone: '', member_type: 'Guest', original_member_type: null, plan: 'regular' };

    // 1. Check EO Dubai Members CSV
    try {
      const memberInfo = await findUserInCSV(email_input, EO_MEMBERS_CSV, 'EO Dubai Member');
      if (memberInfo.found) {
        userInfo = memberInfo;
      }
    } catch (error) {
      console.warn(`Could not read EO Members CSV: ${error.message}`);
    }

    // 2. If not found as a member/spouse, check Accelerators CSV
    if (!userInfo.found) {
      try {
        const acceleratorInfo = await findUserInCSV(email_input, ACCELERATORS_CSV, 'EO Dubai Accelerator');
        if (acceleratorInfo.found) {
          userInfo = acceleratorInfo;
        }
      } catch (error) {
        console.warn(`Could not read Accelerators CSV: ${error.message}`);
      }
    }
    
    const discountMessages = {
      'EO Dubai Member': 'As an EO Dubai member your entry fee is discounted!',
      'EO Dubai Spouse': 'As an EO Dubai Spouse your entry fee is discounted!',
      'EO Dubai Accelerator': 'As an EO Dubai Accelerator, your entry fee is discounted!',
      'EO Dubai Next Gen': 'As an EO Dubai Next Gen, your entry fee is discounted!',
      'EO Dubai Key Executive': 'As an EO Dubai Key Executive, your entry fee is discounted!',
      'Guest': 'Standard pricing applies (no discount)'
    };
    
    const prices = {
      'EO Dubai Member': { 
        amount: 1, // AED 1 reversible charge
        penalty: 3999,
        currency: 'AED'
      },
      'EO Dubai Spouse': { 
        amount: 1, // AED 1 reversible charge
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
        amount: 3999,
        currency: 'AED'
      },
      'Guest': { 
        amount: 5999, // Default for Others/Guest
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
      verification_timestamp: new Date().toISOString()
    };
    
    console.log(`User verification response for ${userInfo.email}:`, response);
    
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
    // This route was likely for debugging and used a generic 'members.csv'
    // It's not directly related to the new database structure for verification.
    // For now, we can return a static list or adapt it if needed.
    const allMemberTypes = [
      'EO Dubai Member',
      'EO Dubai Spouse',
      'EO Dubai Accelerator',
      'EO Dubai Next Gen',
      'EO Dubai Key Executive',
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
