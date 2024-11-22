<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation</title>
    <style>
        /* Basic Reset */
        body, table, td, a {
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            color: #333;
            text-decoration: none;
            margin: 0;
            padding: 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #ffffff;
        }
        /* Container */
        .container {
            max-width: 600px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        /* Header */
        .header {
            background-color: #4a90e2;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        /* Content */
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .content p {
            line-height: 1.6;
            margin: 10px 0;
        }
        /* Button */
        .btn {
            background-color: #4a90e2;
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 5px;
            text-align: center;
            display: inline-block;
            font-weight: bold;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #357ab8;
        }
        /* Footer */
        .footer {
            background-color: #f1f1f1;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
<table role="presentation" class="container">
    <tr>
        <td class="header">
            <h1>Welcome to Knowledge Hub!</h1>
        </td>
    </tr>
    <tr>
        <td class="content">
            <p>Hello {{ $user->name }},</p>
            <p>We're excited to invite you to join our system! Click the button below to activate your account and get started:</p>
            <a href="{{ route('activate.show',[$user->activation_token]) }}" class="btn">Activate Your Account</a>
            <p>If you have any issues, simply reply to this email, and we’ll be happy to assist you.</p>
            <p>Thank you,<br>The {{ config('app.name') }} Team</p>
        </td>
    </tr>
    <tr>
        <td class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </td>
    </tr>
</table>
</body>
</html>
