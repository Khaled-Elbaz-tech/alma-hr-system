<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ALMA HR System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --alma: #8B251E;
            --alma-dark: #5F1812;
            --alma-darker: #47100B;
            --alma-hover: #701C16;
            --gray-500: #6B7280;
            --gray-300: #D1D5DB;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--alma-darker) 0%, var(--alma-dark) 55%, var(--alma) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,.35);
            overflow: hidden;
            max-width: 430px;
            width: 100%;
        }
        .login-header {
            padding: 34px 36px 26px;
            text-align: center;
            background: linear-gradient(135deg, var(--alma-darker) 0%, var(--alma-dark) 60%, var(--alma) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .login-header p { color: rgba(255,255,255,.7); font-size: 11.5px; margin: 0; letter-spacing: 3px; font-weight: 600; }
        .login-body { padding: 32px 36px 36px; }
        .form-label { font-weight: 600; font-size: 13.5px; color: #374151; }
        .form-control {
            border-radius: 10px;
            padding: 12px 14px;
            border: 1px solid var(--gray-300);
            font-size: 14px;
        }
        .form-control:focus {
            border-color: var(--alma);
            box-shadow: 0 0 0 3px rgba(139,37,30,.12);
        }
        .btn-login {
            background: var(--alma);
            color: #fff;
            border: none;
            padding: 13px;
            width: 100%;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            transition: background .15s ease;
        }
        .btn-login:hover { background: var(--alma-hover); color: #fff; }
        .alert { border-radius: 10px; font-size: 13.5px; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            @include('partials.alma-logo', ['height' => 52])
            <p>HR SYSTEM</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="you@company.com">
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>
