<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-left">
            <div class="login-art">
                <div class="art-circle art-circle-1"></div>
                <div class="art-circle art-circle-2"></div>
                <div class="art-circle art-circle-3"></div>
                <div class="login-illustration">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Password Baru</h2>
                <p>{{ config('app.name') }}</p>
            </div>
        </div>
        <div class="login-right">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <div class="login-logo">
                        <i class="fas fa-key"></i>
                    </div>
                    <h1>Buat Password Baru</h1>
                    <p>Masukkan password baru Anda di bawah ini.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('password.store') }}" method="POST" class="login-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email', $email ?? '') }}"
                                   placeholder="email@sekolah.sch.id"
                                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password"
                                   placeholder="Minimal 8 karakter"
                                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePass('password','eyeIcon1')">
                                <i class="fas fa-eye" id="eyeIcon1"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   placeholder="Ulangi password baru"
                                   class="form-control" required>
                            <button type="button" class="toggle-password" onclick="togglePass('password_confirmation','eyeIcon2')">
                                <i class="fas fa-eye" id="eyeIcon2"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-check-circle"></i> Reset Password
                    </button>
                </form>

                <p class="forgot-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i> Kembali ke Login
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePass(fieldId, iconId) {
            const pwd  = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>