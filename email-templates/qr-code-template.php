<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your SYNAPSE Event QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 600px;
            overflow: hidden;
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .qr-code {
            margin: 20px 0;
        }
        .footer {
            background-color: #f4f4f4;
            color: #666666;
            font-size: 12px;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>SYNAPSE Event Registration</h1>
        </div>
        <div class="content">
            <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
            <p>Thank you for registering for the SYNAPSE event. Please present this QR code at the entrance.</p>
            <div class="qr-code">
                <img src="<?php echo $qrCodeUrl; ?>" alt="Your QR Code">
            </div>
            <p><strong>Plan:</strong> <?php echo htmlspecialchars(ucfirst($plan)); ?> Pass</p>
        </div>
        <div class="footer">
            <p>If you have any questions, please contact our support team.</p>
            <p><?php printf('&copy; %s SYNAPSE. All rights reserved.', date('Y')); ?></p>
        </div>
    </div>
</body>
</html>
