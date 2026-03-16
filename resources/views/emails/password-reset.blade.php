<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - SIAKAD</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .content {
            padding: 40px 30px;
        }

        .content h2 {
            color: #2d3748;
            font-size: 24px;
            margin: 0 0 20px 0;
            font-weight: 600;
        }

        .content p {
            color: #4a5568;
            font-size: 16px;
            margin: 0 0 20px 0;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: transform 0.2s ease;
        }

        .button:hover {
            transform: translateY(-2px);
        }

        .info-box {
            background-color: #f7fafc;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-box p {
            margin: 0;
            color: #2d3748;
            font-size: 14px;
        }

        .footer {
            background-color: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            color: #718096;
            font-size: 14px;
            margin: 0;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">🎓 SIAKAD</div>
            <p>School Management System</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Password Reset Request</h2>

            <p>Hello!</p>

            <p>We received a request to reset your password for your SIAKAD account. If you made this request, click the
                button below to reset your password:</p>

            <div style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">Reset Password</a>
            </div>

            <div class="info-box">
                <p><strong>Important:</strong> This password reset link will expire in {{ $count }} minutes. If
                    you don't reset your password within this time, you'll need to request a new reset link.</p>
            </div>

            <p>If you didn't request a password reset, you can safely ignore this email. Your password will remain
                unchanged.</p>

            <p>For security reasons, this link can only be used once. If you need to reset your password again, please
                visit our login page and click "Forgot Password".</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This email was sent from SIAKAD School Management System.</p>
            <p>If you have any questions, please contact our support team.</p>
            <p>&copy; {{ date('Y') }} SIAKAD. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
