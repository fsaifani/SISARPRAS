<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SISARPRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; background-color: #f4f6fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        
        .login-card { background: white; border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); overflow: hidden; width: 100%; max-width: 900px; display: flex; flex-direction: row; }
        .login-left { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); padding: 60px 40px; color: white; display: flex; flex-direction: column; justify-content: center; width: 45%; }
        .login-right { padding: 60px 50px; width: 55%; background: #ffffff; }
        
        /* Trik agar ikon dan input menyatu sempurna */
        .input-wrapper { display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 10px; background: white; transition: all 0.3s ease; overflow: hidden; }
        .input-wrapper:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15); }
        .input-icon { padding: 12px 0 12px 16px; color: #94a3b8; background: transparent; display: flex; align-items: center; }
        .input-wrapper input { border: none; box-shadow: none; padding: 12px 16px 12px 12px; width: 100%; outline: none; background: transparent; color: #334155; }
        .input-wrapper button { background: transparent; border: none; padding: 0 16px; color: #94a3b8; cursor: pointer; display: flex; align-items: center; }
        .input-wrapper button:hover { color: #3b82f6; }

        .btn-login { background-color: #3b82f6; color: white; border-radius: 10px; padding: 12px; font-weight: 600; width: 100%; transition: all 0.3s; border: none; }
        .btn-login:hover { background-color: #2563eb; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3); }
        
        @media (max-width: 768px) { 
            .login-card { flex-direction: column; max-width: 400px; margin: 20px; } 
            .login-left { width: 100%; padding: 40px 30px; text-align: center; } 
            .login-right { width: 100%; padding: 40px 30px; } 
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-left">
            <i class="bi bi-box-seam mb-3" style="font-size: 3rem; color: #60a5fa;"></i>
            <h2 class="fw-bold mb-3">SISARPRAS</h2>
            <p class="text-light opacity-75" style="font-size: 0.95rem; line-height: 1.6;">Sistem Informasi Sarana dan Prasarana terintegrasi. Silakan masuk untuk mengakses panel kendali Anda.</p>
        </div>
        <div class="login-right">
            <div class="mb-5">
                <h3 class="fw-bold text-dark mb-1">Masuk ke Akun</h3>
                <p class="text-muted small">Masukkan email dan kata sandi Anda</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger text-sm border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-4">
                    <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.proses') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-semibold text-secondary small mb-2">Alamat Email</label>
                    <div class="input-wrapper">
                        <div class="input-icon"><i class="bi bi-envelope"></i></div>
                        <input type="email" name="email" placeholder="nama@email.com" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-semibold text-secondary small mb-0">Kata Sandi</label>
                        <a href="#" class="text-decoration-none small text-primary">Lupa sandi?</a>
                    </div>
                    <div class="input-wrapper">
                        <div class="input-icon"><i class="bi bi-lock"></i></div>
                        <input type="password" name="password" id="passwordField" placeholder="••••••••" required>
                        <button type="button" id="togglePassword">
                            <i class="bi bi-eye-slash" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label text-muted small" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login mb-4">Masuk</button>
                
                <div class="text-center text-muted small">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('passwordField');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>
</html>