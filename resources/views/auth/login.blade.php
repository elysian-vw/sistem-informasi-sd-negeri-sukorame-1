<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ config('app.name') }}</title>
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
                    <i class="fas fa-school"></i>
                </div>
                <h2>Sistem Akademik Terpadu</h2>
                <p>{{ config('app.name') }}</p>
            </div>
        </div>
        <div class="login-right">
            <div class="login-form-wrapper">
                <div class="login-header">
                    <div class="login-logo">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h1>Selamat Datang</h1>
                    <p>Masuk ke akun Anda untuk melanjutkan</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="login-form">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email"
                                   value="{{ old('email') }}"
                                   placeholder="email@sekolah.sch.id"
                                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-icon-wrap">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" id="password" name="password"
                                   placeholder="Masukkan password"
                                   class="form-control" required>
                            <button type="button" class="toggle-password" onclick="togglePass()">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </button>
                </form>

                <p class="forgot-link">
                    <a href="{{ route('password.request') }}">Lupa password?</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePass() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
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