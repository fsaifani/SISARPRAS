<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SISARPRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #0b2a55 0%, #1d4e89 100%); min-height: 100vh; }
        .kartu-auth { max-width: 440px; border: 0; border-radius: 14px; }
        .logo { font-weight: 700; letter-spacing: 1px; color: #0b2a55; }
    </style>
</head>
<body class="d-flex align-items-center py-4">
    <div class="container">
        <div class="card kartu-auth shadow mx-auto">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <div class="logo fs-3">SISARPRAS</div>
                    <div class="text-muted small">Sistem Informasi Sarana dan Prasarana Sekolah</div>
                </div>

                @if (session('sukses'))
                    <div class="alert alert-success py-2">{{ session('sukses') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
    <script>
        function tampilkanSandi(cek) {
            document.querySelectorAll('input[data-sandi]').forEach(function (el) {
                el.type = cek.checked ? 'text' : 'password';
            });
        }
    </script>
</body>
</html>
