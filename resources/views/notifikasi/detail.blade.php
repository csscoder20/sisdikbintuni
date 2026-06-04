<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notifikasi->subject }} — Rilis Note</title>
    <style>
        :root {
            --color-primary: #10b981;
            --color-primary-light: #d1fae5;
            --color-primary-dark: #065f46;
            --color-bg: #f8fafc;
            --color-surface: #ffffff;
            --color-border: #e2e8f0;
            --color-text: #1e293b;
            --color-muted: #64748b;
            --color-accent: #0ea5e9;
            --radius: 12px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Inter', sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* ===== HEADER ===== */
        .page-header {
            background: linear-gradient(135deg, #065f46 0%, #10b981 60%, #34d399 100%);
            padding: 2.5rem 1.5rem 3.5rem;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -40px;
            width: 280px;
            height: 280px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }

        .header-inner {
            max-width: 820px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
        }

        .back-btn:hover {
            color: white;
        }

        .back-btn svg {
            width: 16px;
            height: 16px;
        }

        .release-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
            backdrop-filter: blur(4px);
        }

        .release-badge svg {
            width: 14px;
            height: 14px;
        }

        .page-header h1 {
            font-size: clamp(1.4rem, 4vw, 2rem);
            font-weight: 800;
            color: white;
            line-height: 1.3;
            letter-spacing: -0.02em;
            margin-bottom: 0.75rem;
        }

        .header-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem;
            color: rgba(255,255,255,0.75);
            font-size: 0.875rem;
        }

        .header-meta-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .header-meta-item svg {
            width: 15px;
            height: 15px;
            opacity: 0.85;
        }

        /* ===== CARD WRAPPER ===== */
        .page-body {
            max-width: 820px;
            margin: -2rem auto 3rem;
            padding: 0 1.25rem;
            position: relative;
            z-index: 2;
        }

        .content-card {
            background: var(--color-surface);
            border-radius: var(--radius);
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.10), 0 1px 4px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--color-border);
            overflow: hidden;
        }

        /* ===== DECORATIVE TOP BAR ===== */
        .card-topbar {
            height: 4px;
            background: linear-gradient(90deg, #10b981, #34d399, #0ea5e9);
        }

        /* ===== CONTENT BODY ===== */
        .card-body {
            padding: 2rem 2.25rem;
        }

        /* ===== RICH CONTENT STYLING ===== */
        .release-content {
            font-size: 0.95rem;
            line-height: 1.85;
            color: #334155;
        }

        .release-content h1,
        .release-content h2,
        .release-content h3,
        .release-content h4 {
            color: #0f172a;
            font-weight: 700;
            margin-top: 1.75rem;
            margin-bottom: 0.6rem;
            line-height: 1.35;
        }

        .release-content h1 { font-size: 1.5rem; }
        .release-content h2 { font-size: 1.25rem; }
        .release-content h3 { font-size: 1.1rem; }

        .release-content p {
            margin-bottom: 1rem;
        }

        .release-content ul,
        .release-content ol {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }

        .release-content li {
            margin-bottom: 0.4rem;
        }

        .release-content strong {
            color: #0f172a;
            font-weight: 700;
        }

        .release-content blockquote {
            border-left: 4px solid #10b981;
            background: #f0fdf4;
            padding: 0.75rem 1.25rem;
            border-radius: 0 8px 8px 0;
            margin: 1.25rem 0;
            color: #065f46;
            font-style: italic;
        }

        .release-content code {
            background: #f1f5f9;
            color: #0f172a;
            font-family: 'Courier New', monospace;
            font-size: 0.875em;
            padding: 0.15em 0.4em;
            border-radius: 4px;
        }

        /* ===== DIVIDER ===== */
        .card-divider {
            border: none;
            border-top: 1px solid #f1f5f9;
            margin: 1.75rem 0;
        }

        /* ===== FOOTER INSIDE CARD ===== */
        .card-footer {
            padding: 1.25rem 2.25rem 1.75rem;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .footer-info {
            font-size: 0.8rem;
            color: var(--color-muted);
        }

        .btn-back-panel {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.25rem;
            background: #10b981;
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-back-panel:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-back-panel svg {
            width: 15px;
            height: 15px;
        }

        /* ===== GENERAL FALLBACK (non release_note) ===== */
        .general-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }

        @media (max-width: 600px) {
            .card-body { padding: 1.5rem 1.25rem; }
            .card-footer { padding: 1rem 1.25rem 1.25rem; }
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="page-header">
        <div class="header-inner">
            <a href="javascript:history.back()" class="back-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>

            @if($notifikasi->type === 'release_note')
                <div class="release-badge">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Rilis Note
                </div>
            @else
                <div class="general-badge" style="background:rgba(255,255,255,0.18);border-color:rgba(255,255,255,0.3);color:white;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    Pemberitahuan
                </div>
            @endif

            <h1>{{ $notifikasi->subject }}</h1>

            <div class="header-meta">
                <span class="header-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $notifikasi->sender?->name ?? 'Sistem' }}
                </span>
                <span class="header-meta-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($notifikasi->created_at)->translatedFormat('d F Y, H:i') }} WIT
                </span>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="page-body">
        <div class="content-card">
            <div class="card-topbar"></div>

            <div class="card-body">
                <div class="release-content">
                    {!! $notifikasi->content !!}
                </div>
            </div>

            <div class="card-footer">
                <span class="footer-info">
                    Dikirim oleh <strong>{{ $notifikasi->sender?->name ?? 'Sistem' }}</strong>
                    pada {{ \Carbon\Carbon::parse($notifikasi->created_at)->translatedFormat('d F Y') }}
                </span>

                <a href="javascript:history.back()" class="btn-back-panel">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Panel
                </a>
            </div>
        </div>
    </div>

</body>
</html>
