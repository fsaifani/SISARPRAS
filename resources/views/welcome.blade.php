<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang - SISARPRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; }
        .hero-section { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%); color: white; position: relative; overflow: hidden; }
        .hero-content { position: relative; z-index: 2; text-align: center; max-width: 800px; padding: 0 20px; }
        .hero-title { font-weight: 700; font-size: 3.5rem; letter-spacing: -1px; margin-bottom: 20px; }
        .hero-subtitle { font-size: 1.25rem; font-weight: 300; color: #cbd5e1; margin-bottom: 40px; line-height: 1.6; }
        .btn-masuk { background-color: #3b82f6; color: white; padding: 14px 36px; font-weight: 600; font-size: 1.1rem; border-radius: 50px; transition: all 0.3s ease; text-decoration: none; border: 2px solid #3b82f6; box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4); }
        .btn-masuk:hover { background-color: transparent; color: white; transform: translateY(-3px); box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5); }
        .blob { position: absolute; filter: blur(80px); z-index: 1; opacity: 0.5; }
        .blob-1 { top: -10%; left: -10%; width: 400px; height: 400px; background: #3b82f6; border-radius: 50%; }
        .blob-2 { bottom: -20%; right: -10%; width: 500px; height: 500px; background: #0ea5e9; border-radius: 50%; }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        
        <div class="hero-content">
            <div class="mb-4">
                <i class="bi bi-box-seam" style="font-size: 4rem; color: #60a5fa;"></i>
            </div>
            <h1 class="hero-title">Sistem Informasi Sarana & Prasarana</h1>
            <p class="hero-subtitle">Kelola inventaris, pengajuan barang, dan peminjaman sekolah dengan lebih mudah, terpusat, dan efisien melalui platform digital SISARPRAS.</p>
            <a href="{{ route('login') }}" class="btn-masuk">Masuk ke Sistem <i class="bi bi-arrow-right ms-2"></i></a>
        </div>
    </div>
</body>
</html>