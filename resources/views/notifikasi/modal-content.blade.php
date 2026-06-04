@if($notifikasi)
    <div style="padding: 0.25rem 0 0.5rem;">
        {{-- Badge tipe --}}
        @if($notifikasi->type === 'release_note')
            <div style="
                display: inline-flex;
                align-items: center;
                gap: 0.375rem;
                background: #d1fae5;
                color: #065f46;
                font-size: 0.7rem;
                font-weight: 700;
                letter-spacing: 0.06em;
                text-transform: uppercase;
                padding: 0.3rem 0.7rem;
                border-radius: 999px;
                margin-bottom: 1.25rem;
                border: 1px solid #a7f3d0;
            ">
                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Rilis Note / Pembaruan Sistem
            </div>
        @endif

        {{-- Konten HTML dari RichEditor --}}
        <div class="notifikasi-modal-prose">
            {!! $notifikasi->content !!}
        </div>
    </div>

    <style>
        .notifikasi-modal-prose {
            font-size: 0.9rem;
            line-height: 1.8;
            color: #374151;
        }
        .notifikasi-modal-prose h1,
        .notifikasi-modal-prose h2,
        .notifikasi-modal-prose h3 {
            font-weight: 700;
            color: #111827;
            margin-top: 1.25rem;
            margin-bottom: 0.5rem;
            line-height: 1.35;
        }
        .notifikasi-modal-prose h1 { font-size: 1.25rem; }
        .notifikasi-modal-prose h2 { font-size: 1.1rem; }
        .notifikasi-modal-prose h3 { font-size: 1rem; }
        .notifikasi-modal-prose p {
            margin-bottom: 0.85rem;
        }
        .notifikasi-modal-prose ul,
        .notifikasi-modal-prose ol {
            padding-left: 1.5rem;
            margin-bottom: 0.85rem;
        }
        .notifikasi-modal-prose li {
            margin-bottom: 0.3rem;
        }
        .notifikasi-modal-prose strong {
            font-weight: 700;
            color: #111827;
        }
        .notifikasi-modal-prose blockquote {
            border-left: 3px solid #10b981;
            background: #f0fdf4;
            padding: 0.6rem 1rem;
            border-radius: 0 6px 6px 0;
            margin: 1rem 0;
            color: #065f46;
            font-style: italic;
        }
        .notifikasi-modal-prose code {
            background: #f1f5f9;
            color: #0f172a;
            font-family: monospace;
            font-size: 0.875em;
            padding: 0.1em 0.35em;
            border-radius: 3px;
        }
    </style>
@else
    <p style="color: #6b7280; text-align: center; padding: 1rem 0;">
        Detail pemberitahuan tidak ditemukan.
    </p>
@endif
