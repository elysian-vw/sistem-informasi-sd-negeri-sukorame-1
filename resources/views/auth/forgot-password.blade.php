<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            padding: 2rem 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }
        .avatar i { font-size: 26px; color: #9ca3af; }
        .card-header { text-align: center; margin-bottom: 1.5rem; }
        .card-header h2 { font-size: 15px; font-weight: 600; color: #111827; }
        .card-header p  { font-size: 13px; color: #6b7280; margin-top: 2px; }
        .divider { border-top: 1px solid #f3f4f6; padding-top: 1.25rem; margin-bottom: 1.25rem; }
        .divider h3 { font-size: 14px; font-weight: 600; color: #111827; margin-bottom: 4px; }
        .divider p  { font-size: 13px; color: #6b7280; line-height: 1.5; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 10px 16px;
            border: 1px solid #d1d5db;
            border-radius: 999px;
            font-size: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #111827;
            background: #fff;
            outline: none;
            transition: border-color 0.15s;
        }
        .form-group input:focus { border-color: #2563eb; }
        .form-group input::placeholder { color: #9ca3af; }
        .hint { font-size: 12px; color: #9ca3af; margin-top: 6px; padding-left: 4px; }
        .btn-submit {
            width: 100%;
            padding: 11px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            margin-bottom: 0.875rem;
            transition: background 0.15s;
        }
        .btn-submit:hover { background: #1d4ed8; }
        .back-link { text-align: center; }
        .back-link a { font-size: 13px; color: #2563eb; text-decoration: none; }
        .back-link a:hover { text-decoration: underline; }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .alert-danger  { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    </style>
</head>
<body>
    <div class="card">

        <div class="card-header">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2>Lupa Password</h2>
            <p>{{ config('app.name') }}</p>
        </div>

        <div class="divider">
            <h3>Reset Password</h3>
            <p>Masukkan email terdaftar untuk menerima link reset password</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="margin-top:1px;"></i>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle" style="margin-top:1px;"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email Terdaftar</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="Masukkan email Anda"
                       required autofocus>
                <p class="hint">Link reset akan dikirim ke email ini</p>
            </div>

            <button type="submit" class="btn-submit">Kirim Link Reset</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </div>

    </div>
</body>
</html>