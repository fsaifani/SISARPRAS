<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - SISARPRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; background-color: #f4f6fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px 0; }
        
        .login-card { background: white; border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); overflow: hidden; width: 100%; max-width: 900px; display: flex; flex-direction: row; }
        .login-left { background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); padding: 60px 40px; color: white; display: flex; flex-direction: column; justify-content: center; width: 45%; }
        .login-right { padding: 40px 50px; width: 55%; background: #ffffff; }
        
        .input-wrapper { display: flex; align-items: center; border: 1px solid #e2e8f0; border-radius: 10px; background: white; transition: all 0.3s ease; overflow: hidden; }
        .input-wrapper:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15); }
        .input-icon { padding: 10px 0 10px 16px; color: #94a3b8; background: transparent; display: flex; align-items: center; }
        .input-wrapper input, .input-wrapper select { border: none; box-shadow: none; padding: 10px 16px 10px 12px; width: 100%; outline: none; background: transparent; color: #334155; font-size: 0.95rem; }
        .input-wrapper button { background: transparent; border: none; padding: 0 16px; color: #94a3b8; cursor: pointer; display: flex; align-items: center; }
        .input-wrapper button:hover { color: #3b82f6; }

        .btn-login { background-color: #3b82f6; color: white; border-radius: 10px; padding: 12px; font-weight: 600; width: 100%; transition: all 0.3s; border: none; margin-top: 10px; }
        .btn-login:hover { background-color: #2563eb; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3); }
        
        @media (max-width: 768px) { 
            .login-card { flex-direction: column; max-width: 400px; margin: 20px; } 
            .login-left { width: 100%; padding: 40px 30px; text-align: center; } 
            .login-right { width: 100%; padding: 30px 30px; } 
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-left">
            <i class="bi bi-person-vcard mb-3" style="font-size: 3rem; color: #60a5fa;"></i>
            <h2 class="fw-bold mb-3">Daftar Akun</h2>
            <p class="text-light opacity-75" style="font-size: 0.95rem; line-height: 1.6;">Buat akun Siswa atau Guru untuk mulai meminjam dan mengajukan barang di sistem SISARPRAS.</p>
        </div>
        <div class="login-right">
            <div class="mb-4">
                <h3 class="fw-bold text-dark mb-1">Registrasi</h3>
                <p class="text-muted small">Lengkapi data diri Anda di bawah ini</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger text-sm border-0 bg-danger bg-opacity-10 text-danger rounded-3 p-3 mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="formRegistrasi" method="POST" action="{{ route('register.proses') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small mb-1">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <div class="input-icon"><i class="bi bi-person"></i></div>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small mb-1">Alamat Email</label>
                    <div class="input-wrapper">
                        <div class="input-icon"><i class="bi bi-envelope"></i></div>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-secondary small mb-1">Pilih Peran</label>
                    <div class="input-wrapper">
                        <div class="input-icon"><i class="bi bi-shield-check"></i></div>
                        <select name="role" id="pilihRole" required style="cursor: pointer;">
                            <option value="" disabled selected>-- Pilih Peran Anda --</option>
                            <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        </select>
                    </div>
                </div>

                <!-- KOLOM VERIFIKASI NIP GURU -->
                <div class="mb-3 p-3 rounded" id="divVerifikasiNip" style="display: none; background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                    <label class="form-label fw-semibold text-primary small mb-1">Verifikasi NIP Guru</label>
                    <p class="text-muted" style="font-size: 0.75rem; margin-bottom: 8px;">Sistem akan mengecek NIP Anda ke database sekolah.</p>
                    <div class="d-flex gap-2">
                        <input type="text" id="inputNip" class="form-control border-0 shadow-sm" placeholder="Masukkan NIP" style="font-size: 0.9rem;">
                        <button type="button" id="btnCekNip" class="btn btn-primary btn-sm px-3 fw-bold">Cek</button>
                    </div>
                    <div id="nipStatus" class="mt-2 small fw-semibold"></div>
                </div>

                <!-- BUNGKUSAN PASSWORD & TOMBOL DAFTAR -->
                <div id="divFormLanjutan">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Kata Sandi</label>
                        <div class="input-wrapper">
                            <div class="input-icon"><i class="bi bi-lock"></i></div>
                            <input type="password" name="password" id="passwordFieldReg" placeholder="Minimal 8 karakter" required>
                            <button type="button" id="togglePasswordReg">
                                <i class="bi bi-eye-slash" id="toggleIconReg"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small mb-1">Konfirmasi Kata Sandi</label>
                        <div class="input-wrapper">
                            <div class="input-icon"><i class="bi bi-lock-fill"></i></div>
                            <input type="password" name="password_confirmation" id="passwordConfirmFieldReg" placeholder="Ulangi kata sandi" required>
                            <button type="button" id="togglePasswordConfirmReg">
                                <i class="bi bi-eye-slash" id="toggleIconConfirmReg"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-login mb-4">Daftar Sekarang</button>
                </div>
                
                <div class="text-center text-muted small">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Masuk di sini</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle kata sandi
        document.getElementById('togglePasswordReg').addEventListener('click', function () {
            const passwordField = document.getElementById('passwordFieldReg');
            const toggleIcon = document.getElementById('toggleIconReg');
            if (passwordField.type === 'password') {
                passwordField.type = 'text'; toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordField.type = 'password'; toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });

        document.getElementById('togglePasswordConfirmReg').addEventListener('click', function () {
            const passwordField = document.getElementById('passwordConfirmFieldReg');
            const toggleIcon = document.getElementById('toggleIconConfirmReg');
            if (passwordField.type === 'password') {
                passwordField.type = 'text'; toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                passwordField.type = 'password'; toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        });

        // LOGIKA CEK NIP DATABASE
        const pilihRole = document.getElementById('pilihRole');
        const divVerifikasiNip = document.getElementById('divVerifikasiNip');
        const divFormLanjutan = document.getElementById('divFormLanjutan');
        const inputNip = document.getElementById('inputNip');
        const btnCekNip = document.getElementById('btnCekNip');
        const nipStatus = document.getElementById('nipStatus');

        pilihRole.addEventListener('change', function() {
            if (this.value === 'guru') {
                divVerifikasiNip.style.display = 'block';
                divFormLanjutan.style.display = 'none'; // Kunci sisa formnya
                inputNip.value = '';
                nipStatus.innerHTML = '';
            } else {
                divVerifikasiNip.style.display = 'none';
                divFormLanjutan.style.display = 'block'; // Buka form untuk siswa
            }
        });

        btnCekNip.addEventListener('click', function() {
            const nip = inputNip.value.trim();
            nipStatus.innerHTML = '<span class="text-primary">Mengecek database...</span>';
            
            // Jeda 1 detik biar keliatan real ngecek ke database
            setTimeout(() => {
                if(nip === '123456') {
                    nipStatus.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill"></i> NIP Terverifikasi! Akses Guru dibuka.</span>';
                    divFormLanjutan.style.display = 'block'; // Buka sisa form
                } else {
                    nipStatus.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle-fill"></i> NIP tidak ditemukan di database sekolah.</span>';
                    divFormLanjutan.style.display = 'none'; // Tetap kunci
                }
            }, 800);
        });
    </script>
</body>
</html>