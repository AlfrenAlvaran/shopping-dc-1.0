export const SIGNUP = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to Our Service</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    .header {
      padding: 20px;
      text-align: center;
      background: linear-gradient(to right, black, black);
    }
    .header h1 {
      color: white;
      margin: 0;
    }
    @media (prefers-color-scheme: dark) {
      .header {
        background: linear-gradient(to right, white, white);
      }
      .header h1 {
        color: black;
      }
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>Welcome!</h1>
  </div>
  <div style="background-color: #f9f9f9; padding: 20px; border-radius: 0 0 5px 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <p>Hello {name},</p>
    <p>Thank you for joining us! We’re thrilled to have you as part of our community.</p>
    <p>Your registered email: <strong>{email}</strong></p>
    <p>This link will expire in 1 hour for security reasons.</p>
    <p>Best regards,<br>Your App Team</p>
  </div>
  <div style="text-align: center; margin-top: 20px; color: #888; font-size: 0.8em;">
    <p>This is an automated message, please do not reply to this email.</p>
  </div>
</body>
</html>
`;
