<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        /* Include Bootstrap CSS inline styles */
        a{
            text-decoration: none;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        a.btn{
            color: white;
        }
        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            color: #fff;
            background-color: #0056b3;
            border-color: #004085;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .header, .footer {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
        }
        .content {
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 0.25rem;
        }
        .footer {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ config('app.name') }}</h2>
        </div>
        <div class="content">
            <h3>Email Verification</h3>
            <p>Thank you for registering with {{ config('app.name') }}. To complete your registration and activate your account, please verify your email address by clicking the button below:</p>
            <a href="{{ $url }}" class="btn btn-primary">Verify Email</a>
            <p>If the button above does not work, please copy and paste the following URL into your web browser:</p>
            <p><a href="{{ $url }}">{{ $url }}</a></p>
            <p>Verification is important to ensure that we have the correct email address associated with your account. This helps us to keep your account secure and to provide you with the best possible experience.</p>
            <p>If you did not create an account with {{ config('app.name') }}, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>Thanks,<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
