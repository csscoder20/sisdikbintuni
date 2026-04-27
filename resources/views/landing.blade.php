<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sisdik Bintuni') }} — Sistem Informasi Pendidikan</title>
    <link rel="icon" type="image/png" href="/favicon.png" sizes="32x32">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { width: 100%; min-height: 100vh; overflow-x: hidden; }
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }
        a { text-decoration: none; color: inherit; }

        /* ===== BACKGROUND ===== */
        .bg-decor {
            position: fixed;
            inset: 0;
            z-index: -10;
            overflow: hidden;
            pointer-events: none;
        }
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(to right, #e2e8f0 1px, transparent 1px),
                linear-gradient(to bottom, #e2e8f0 1px, transparent 1px);
            background-size: 4rem 4rem;
            opacity: 0.25;
            -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 60%, transparent 100%);
            mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, #000 60%, transparent 100%);
        }
        .bg-blob-1 {
            position: absolute;
            top: -200px; right: -200px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: rgba(96, 165, 250, 0.12);
            filter: blur(80px);
        }
        .bg-blob-2 {
            position: absolute;
            bottom: -200px; left: -200px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: rgba(129, 140, 248, 0.10);
            filter: blur(80px);
        }

        /* ===== HEADER ===== */
        .site-header {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            width: 92%;
            max-width: 1200px;
            z-index: 50;
        }
        .nav-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 9999px;
            padding: 8px 8px 8px 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            animation: slideDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        /* ===== LOGO ===== */
        .logo-link {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .logo-icon:hover { transform: scale(1.08); }
        .logo-text {
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #1e293b;
        }
        .logo-text span { color: #3b82f6; }

        /* ===== NAV LINKS ===== */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .nav-links a {
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
            transition: color 0.15s ease;
        }
        .nav-links a:hover { color: #1e293b; }

        @media (max-width: 768px) { .nav-links { display: none; } }

        /* ===== NAV ACTIONS ===== */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .btn-login {
            padding: 8px 20px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            border-radius: 9999px;
            transition: color 0.15s ease;
        }
        .btn-login:hover { color: #0f172a; }
        .btn-register {
            padding: 10px 22px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            background: #0f172a;
            border-radius: 9999px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.18);
            transition: background 0.15s ease, transform 0.15s ease;
        }
        .btn-register:hover { background: #1e293b; transform: translateY(-1px); }

        /* ===== HERO SECTION ===== */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 160px 24px 80px;
            animation: fadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            border-radius: 9999px;
            background: rgba(239, 246, 255, 0.8);
            border: 1px solid #bfdbfe;
            color: #2563eb;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 32px;
        }
        .badge-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #3b82f6;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            flex-shrink: 0;
        }
        .hero-title {
            font-size: clamp(2.5rem, 7vw, 5rem);
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: #0f172a;
            margin-bottom: 24px;
            max-width: 800px;
        }
        .hero-title span {
            background: linear-gradient(135deg, #2563eb, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: italic;
        }
        .hero-desc {
            font-size: 1.1rem;
            font-weight: 500;
            color: #64748b;
            line-height: 1.75;
            max-width: 580px;
            margin-bottom: 40px;
        }
        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            justify-content: center;
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 15px 36px;
            background: #2563eb;
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.30);
            transition: all 0.2s ease;
        }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 12px 32px rgba(37, 99, 235, 0.38); }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 15px 36px;
            background: #fff;
            color: #1e293b;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover { background: #f8fafc; transform: translateY(-2px); }

        /* ===== STATS ===== */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            margin-top: 80px;
            padding-top: 64px;
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            width: 100%;
            max-width: 700px;
        }
        @media (max-width: 640px) { .stats { grid-template-columns: repeat(2, 1fr); } }
        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
        }
        .stat-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 4px;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.4; }
        }
    </style>
</head>
<body>

    <!-- Background -->
    <div class="bg-decor">
        <div class="bg-grid"></div>
        <div class="bg-blob-1"></div>
        <div class="bg-blob-2"></div>
    </div>

    <!-- Header -->
    <header class="site-header">
        <nav class="nav-bar">
            <!-- Logo -->
            <a href="/" class="logo-link">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
                        <path d="M2 12h20"/>
                    </svg>
                </div>
                <span class="logo-text">Sisdik<span>Bintuni</span></span>
            </a>

            <!-- Nav Links -->
            <div class="nav-links">
                <a href="#">Beranda</a>
                <a href="#">Informasi</a>
                <a href="#">Bantuan</a>
            </div>

            <!-- Actions -->
            <div class="nav-actions">
                @auth
                    <a href="/admin" class="btn-register">Panel</a>
                @else
                    <a href="/admin/login" class="btn-login">Login</a>
                    <a href="/admin/register" class="btn-register">Register</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Hero -->
    <main>
        <section class="hero">
            <div class="badge">
                <span class="badge-dot"></span>
                Sistem Informasi Terpadu
            </div>

            <h1 class="hero-title">
                Transformasi Digital<br>
                <span>Pendidikan Bintuni</span>
            </h1>

            <p class="hero-desc">
                Platform modern untuk manajemen data sekolah, siswa, dan tenaga kependidikan secara efisien, akurat, dan transparan dalam satu ekosistem digital.
            </p>

            <div class="hero-actions">
                <a href="/admin" class="btn-primary">
                    @auth
                        Panel Admin
                    @else
                        Mulai Sekarang
                    @endauth
                </a>
                <a href="#" class="btn-secondary">
                    Pelajari Fitur
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </a>
            </div>

            <div class="stats">
                <div>
                    <div class="stat-num">120+</div>
                    <div class="stat-label">Sekolah Aktif</div>
                </div>
                <div>
                    <div class="stat-num">15k+</div>
                    <div class="stat-label">Siswa Terdata</div>
                </div>
                <div>
                    <div class="stat-num">2k+</div>
                    <div class="stat-label">GTK Terdaftar</div>
                </div>
                <div>
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Digitalisasi</div>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
