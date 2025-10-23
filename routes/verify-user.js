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

// Function to read CSV and find user
function verifyUserFromCSV(email, csvPath) {
  return new Promise((resolve, reject) => {
    const users = [];
    fs.createReadStream(csvPath)
      .pipe(csv({ 
        skipLines: 1, // Skip the first empty row
        mapHeaders: ({ header }) => {
          const trimmedHeader = header.trim();
          if (trimmedHeader === 'Category') return 'Member Type';
          if (trimmedHeader === 'Mobile') return 'Phone';
          return trimmedHeader;
        }
      }))
      .on('data', (row) => {
        users.push(row);
      })
      .on('end', () => {
        const foundUser = users.find(user => user.Email && user.Email.toLowerCase().trim() === email.toLowerCase().trim());

        if (foundUser) {
          const normalizedType = normalizeMemberType(foundUser['Member Type']);
          const displayType = getMemberTypeDisplay(normalizedType);

          console.log(`DEBUG: Email: ${foundUser.Email}, Name: ${foundUser.Name}, Phone: ${foundUser.Phone}, CSV Member Type: '${foundUser['Member Type']}', Normalized Type: '${normalizedType}', Display Type: '${displayType}', Plan: ${foundUser.Plan}`);

          resolve({
            found: true,
            email: foundUser.Email,
            name: foundUser.Name,
            phone: foundUser.Phone, // Assuming 'Phone' column in CSV is now 'Mobile'
            member_type: displayType,
            original_member_type: foundUser['Member Type'],
            plan: foundUser.Plan // Include the plan from CSV
          });
        } else {
          resolve({
            found: false,
            email: email.toLowerCase().trim(),
            name: '',
            phone: '',
            member_type: 'Guest',
            original_member_type: null,
            plan: 'regular' // Default plan for guests
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
    
    const csvPath = path.join(__dirname, '../Test Database - Sheet1.csv');
    
    // Verify user from CSV
    const userInfo = await verifyUserFromCSV(email_input, csvPath);
    
    // Define pricing and discount information based on user feedback
    const discountMessages = {
      'EO Dubai Member': 'As an EO Dubai member your entry fee is discounted!',
      'EO Dubai Spouse': 'As an an EO Dubai Spouse your entry fee is discounted!',
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
    
    // Prepare response
    const response = {
      success: true,
      email: userInfo.email,
      name: userInfo.name,
      phone: userInfo.phone,
      member_type: userInfo.member_type,
      is_member: userInfo.found,
      discount_message: discountMessages[userInfo.member_type],
      pricing: prices[userInfo.member_type],
      plan: userInfo.plan, // Include the plan in the response
      verification_timestamp: new Date().toISOString()
    };
    
    // Log the full response being sent for audit purposes
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
