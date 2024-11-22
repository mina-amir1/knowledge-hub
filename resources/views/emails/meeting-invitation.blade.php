<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Invitation</title>
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
            <h1>You're Invited to a Meeting!</h1>
        </td>
    </tr>
    <tr>
        <td class="content">
            <p>Hello {{ $user->name }},</p>
            <p>{{ $meeting->user->name }} has invited you to an upcoming meeting! Here are the details:</p>
            <p><strong>Meeting Title:</strong> {{ $meeting->title }}</p>
            <p><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($meeting->start_time)->format('M d, Y - h:i A') }}</p>
            <p><strong>End Time:</strong> {{ \Carbon\Carbon::parse($meeting->end_time)->format('M d, Y - h:i A') }}</p>
            <p><strong>Meeting URL:</strong> <a href="{{ $meeting->url }}" target="_blank">{{ $meeting->url }}</a></p>
            <p>To join the meeting, click the button below:</p>
            <a href="{{ $meeting->url }}" class="btn">Join Meeting</a>
            <p>Want to add this meeting to your calendar? Click the button below:</p>
            <a href="{{ route('meeting.calendar', ['user_id'=>$user->id,'id' => $meeting->id]) }}" class="btn" >Add to Calendar</a>
            <p>Looking forward to your participation!<br>Thank you,<br>The {{ config('app.name') }} Team</p>
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
