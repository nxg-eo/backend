<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 600px;
            overflow: hidden;
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            font-size: 28px;
            margin: 0;
        }
        .content {
            line-height: 1.6;
            font-size: 16px;
        }
        .qr-code-section {
            text-align: center;
            margin: 30px 0;
        }
        .qr-code-section img {
            max-width: 150px;
            height: auto;
            border: 1px solid #eee;
            padding: 5px;
            border-radius: 5px;
        }
        .event-details {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .event-details p {
            margin: 5px 0;
        }
        .event-details strong {
            color: #555;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Confirmed!</h1>
        </div>
        <div class="content">
            <p>Dear <?php echo htmlspecialchars($name); ?>,</p>
            <p>Thank you for registering for the AI FOR BUSINESS  hosted by EO Dubai.</p>

            <div class="qr-code-section">
                <h3>Your Event QR Code</h3>
                <img src="<?php echo $qrCodeUrl; ?>" alt="Your QR Code">
                <p>Please present this QR code at the event entrance</p>
            </div>

            <div class="event-details">
                <h3>Event Details</h3>
                <p><strong>Date:</strong> 23-24 January 2026</p>
                <p><strong>Venue:</strong> Marriott Palm Jumeirah, Dubai</p>
                <p><strong>Chapter:</strong> <?php echo htmlspecialchars($chapter); ?></p>
                <p><strong>Registration ID:</strong> <?php echo htmlspecialchars($registrationId); ?></p>
                <?php echo $penaltyMessage; ?>
            </div>

            <p>We look forward to seeing you at the event!</p>
            <p>Best regards,<br>EO Dubai Team</p>
        </div>
        <div class="footer">
            <p>If you have any questions, please contact our support team.</p>
            <p>&copy; <?php echo date('Y'); ?> EO Dubai. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
