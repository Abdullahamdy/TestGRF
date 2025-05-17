<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>{{ __('general.welcome_message') }}</title>
    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
            line-height: 1.8;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: right;
            direction: rtl;
        }
        .email-container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .email-header {
            background-color: #004a99;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .email-content {
            padding: 20px;
        }
        .email-footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #004a99;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="email-container">
    <div class="email-header">
        <h2> {{ __('general.welcome_message') }}</h2>
    </div>

    <div class="email-content">
        <h3>{{ __('general.welcome') }} {{ $userName }}،</h3>
        <p> {{ __('general.user_added') }} </p>

        <p><strong> {{ __('general.login_details') }} :</strong></p>
        <ul>
            <li><strong> {{ __('general.username') }} :</strong> {{ $userName }}</li>

            <li><strong> {{ __('general.password') }} :</strong> {{ $password }}</li>
        </ul>

        <p>{{ __('general.change_password') }}</p>

        {{-- <p style="text-align: center;">
            <a href="https://mal.news/login" class="btn">تسجيل الدخول الآن</a>
        </p> --}}
    </div>

    {{-- <div class="email-footer">
        <p>إذا كنت بحاجة إلى أي مساعدة، لا تتردد في <a href="https://mal.news/contact">الاتصال بنا</a>.</p>
        <p>شكرًا لاختيارك جريدة مال.</p>
        <p>© 2025 جريدة مال - جميع الحقوق محفوظة.</p>
    </div> --}}
</div>

</body>
</html>
