<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Account Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            padding: 20px 0;
        }

        .email-header h1 {
            font-size: 24px;
            margin: 0;
            color: #333;
        }

        .email-body {
            padding: 20px;
            text-align: center;
        }

        .email-body p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .verification-code {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            background-color: #f4f4f4;
            padding: 10px;
            letter-spacing: 4px;
            display: inline-block;
            border-radius: 5px;
        }

        .email-footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>
                Xác minh tài khoản của bạn</h1>
        </div>
        <div class="email-body">
            <p>Chào [User],</p>
            <p>
                Để hoàn tất quá trình đăng ký, vui lòng sử dụng mã xác minh
                dưới. Mã này sẽ hết hạn sau 5 phút.
            </p>
            <div class="verification-code">123456</div>
            <p>
                Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này.</p>
        </div>
        <div class="email-footer">
            <p>&copy; 2024 Công ty của bạn. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
</body>

</html>
