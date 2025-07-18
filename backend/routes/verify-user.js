const express = require('express');
const router = express.Router();
const fs = require('fs');
const csv = require('csv-parser');
const path = require('path');

// Utility function to normalize member type strings
function normalizeMemberType(memberType) {
  if (!memberType) return 'guest';
  
  const normalized = memberType.toLowerCase().trim();
  
  // Handle various possible formats
  if (normalized.includes('eo dubai member') || normalized === 'member') {
    return 'eo dubai member';
  }
  if (normalized.includes('eo dubai spouse') || normalized.includes('spouse') || normalized.includes('partner')) {
    return 'eo dubai spouse';
  }
  if (normalized.includes('eo dubai accelerator') || normalized.includes('accelerator')) {
    return 'eo dubai accelerator';
  }
  
  return 'guest';
}

// Function to get proper display name for member type
function getMemberTypeDisplay(normalizedType) {
  const displayNames = {
    'eo dubai member': 'EO Dubai Member',
    'eo dubai spouse': 'EO Dubai Spouse',
    'eo dubai accelerator': 'EO Dubai Accelerator',
    'guest': 'Guest'
  };
  return displayNames[normalizedType] || 'Guest';
}

// Function to read CSV and find user
function verifyUserFromCSV(email, csvPath) {
  return new Promise((resolve, reject) => {
    const results = [];
    let found = false;
    
    if (!fs.existsSync(csvPath)) {
      return reject(new Error('CSV file not found'));
    }
    
    fs.createReadStream(csvPath)
      .pipe(csv())
      .on('data', (data) => {
        // Handle different possible column names
        const csvEmail = (data['Email'] || data['email'] || data['EMAIL'] || '').toLowerCase().trim();
        const csvMemberType = data['Member Type'] || data['member type'] || data['MEMBER TYPE'] || data['MemberType'] || '';
        
        if (csvEmail === email.toLowerCase().trim()) {
          found = true;
          const normalizedType = normalizeMemberType(csvMemberType);
          const displayType = getMemberTypeDisplay(normalizedType);
          
          console.log(`DEBUG: Email: ${csvEmail}, CSV Member Type: '${csvMemberType}', Normalized Type: '${normalizedType}', Display Type: '${displayType}'`);

          resolve({
            found: true,
            email: csvEmail,
            member_type: displayType,
            original_member_type: csvMemberType
          });
        }
      })
      .on('end', () => {
        if (!found) {
          resolve({
            found: false,
            email: email.toLowerCase().trim(),
            member_type: 'Guest',
            original_member_type: null
          });
        }
      })
      .on('error', (error) => {
        reject(error);
      });
  });
}

// GET or POST /verify-user
router.all('/verify-user', async (req, res) => {
  try {
    const email_input = (req.body.email || req.query.email || '').trim();
    
    // Validate email input
    if (!email_input) {
      return res.status(400).json({ 
        error: 'Email is required',
        success: false 
      });
    }
    
    // Basic email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email_input)) {
      return res.status(400).json({ 
        error: 'Invalid email format',
        success: false 
      });
    }
    
    const csvPath = path.join(__dirname, '../members.csv');
    
    // Verify user from CSV
    const userInfo = await verifyUserFromCSV(email_input, csvPath);
    
    // Define pricing and discount information
    const discountMessages = {
      'EO Dubai Member': 'As an EO Dubai member your entry fee is discounted by USD 1,500!',
      'EO Dubai Spouse': 'As an EO Dubai Spouse your entry fee is discounted by USD 250!',
      'EO Dubai Accelerator': 'As an EO Dubai Accelerator, your entry fee is discounted by USD 250!',
      'Guest': 'Standard pricing applies (no discount)'
    };
    
    const prices = {
      'EO Dubai Member': { 
        regular: 0, 
        vip: 1000,
        currency: 'USD'
      },
      'EO Dubai Spouse': { 
        regular: 1250, 
        vip: 2250,
        currency: 'USD'
      },
      'EO Dubai Accelerator': { 
        regular: 1250, 
        vip: 2250,
        currency: 'USD'
      },
      'Guest': { 
        regular: 1500, 
        vip: 2500,
        currency: 'USD'
      }
    };
    
    // Prepare response
    const response = {
      success: true,
      email: userInfo.email,
      member_type: userInfo.member_type,
      is_member: userInfo.found,
      discount_message: discountMessages[userInfo.member_type],
      pricing: prices[userInfo.member_type],
      verification_timestamp: new Date().toISOString()
    };
    
    // Log the verification for audit purposes
    console.log(`User verification: ${userInfo.email} - ${userInfo.member_type} - ${userInfo.found ? 'Found' : 'Not Found'}`);
    
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
    const csvPath = path.join(__dirname, '../members.csv');
    const memberTypes = new Set();
    
    fs.createReadStream(csvPath)
      .pipe(csv())
      .on('data', (data) => {
        const memberType = data['Member Type'] || data['member type'] || data['MEMBER TYPE'] || '';
        if (memberType) {
          memberTypes.add(memberType.trim());
        }
      })
      .on('end', () => {
        res.json({
          success: true,
          member_types: Array.from(memberTypes).sort()
        });
      })
      .on('error', (error) => {
        res.status(500).json({ 
          error: 'Error reading member types',
          success: false 
        });
      });
  } catch (error) {
    res.status(500).json({ 
      error: 'Internal server error',
      success: false 
    });
  }
});

module.exports = router;
