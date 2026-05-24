<x-filament-panels::page>
    <style>
        .help-guide-page {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .help-guide-hero {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        }

        .help-guide-hero-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .help-guide-title {
            margin: 0;
            color: #111827;
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-weight: 600;
        }

        .help-guide-subtitle {
            max-width: 64rem;
            margin: 0.5rem 0 0;
            color: #4b5563;
            font-size: 0.875rem;
            line-height: 1.5rem;
        }

        .help-guide-badge {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            border-radius: 9999px;
            background: #ecfdf5;
            color: #047857;
            padding: 0.375rem 0.625rem;
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 600;
        }

        .help-guide-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .help-guide-group-title {
            margin: 0.75rem 0 0;
            color: #111827;
            font-size: 1rem;
            line-height: 1.5rem;
            font-weight: 600;
        }

        .help-guide-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .help-guide-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        }

        .help-guide-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .help-guide-card-title {
            margin: 0;
            color: #111827;
            font-size: 1rem;
            line-height: 1.5rem;
            font-weight: 600;
        }

        .help-guide-menu {
            display: inline-flex;
            align-items: center;
            max-width: 100%;
            border-radius: 6px;
            background: #f3f4f6;
            color: #374151;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .help-guide-description {
            margin: 0 0 0.75rem;
            color: #4b5563;
            font-size: 0.875rem;
            line-height: 1.5rem;
        }

        .help-guide-steps {
            margin: 0;
            padding-left: 1.25rem;
            color: #374151;
            font-size: 0.875rem;
            line-height: 1.625rem;
        }

        .help-guide-note {
            margin: 0.75rem 0 0;
            border-left: 3px solid #10b981;
            border-radius: 6px;
            background: #f0fdf4;
            color: #166534;
            padding: 0.625rem 0.75rem;
            font-size: 0.8125rem;
            line-height: 1.25rem;
        }

        .help-guide-actions {
            display: flex;
            justify-content: center;
            padding-top: 0.5rem;
        }

        .help-guide-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: #10b981;
            color: white;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 600;
            text-decoration: none;
        }

        .help-guide-back:hover {
            background: #059669;
        }

        @media (min-width: 768px) {
            .help-guide-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {

            .help-guide-hero-header,
            .help-guide-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .help-guide-menu,
            .help-guide-badge {
                white-space: normal;
            }
        }
    </style>

    <div class="help-guide-page">
        <section class="help-guide-hero">
            <div class="help-guide-hero-header">
                <div>
                    <h1 class="help-guide-title">Panduan Penggunaan</h1>
                    <p class="help-guide-subtitle">{{ $this->getGuideIntro() }}</p>
                </div>

                <span class="help-guide-badge">{{ $this->getGuideAudienceLabel() }}</span>
            </div>
        </section>

        @foreach ($this->getGuideSections() as $section)
            @if (isset($section['group'], $section['items']))
                <section class="help-guide-group">
                    <h2 class="help-guide-group-title">{{ $section['group'] }}</h2>

                    <div class="help-guide-grid">
                        @foreach ($section['items'] as $item)
                            <article class="help-guide-card">
                                <div class="help-guide-card-header">
                                    <h3 class="help-guide-card-title">{{ $item['title'] }}</h3>
                                    <span class="help-guide-menu">{{ $item['menu'] }}</span>
                                </div>

                                <p class="help-guide-description">{{ $item['description'] }}</p>

                                <ol class="help-guide-steps">
                                    @foreach ($item['steps'] as $step)
                                        <li>{{ $step }}</li>
                                    @endforeach
                                </ol>

                                @if (!empty($item['note']))
                                    <p class="help-guide-note">{{ $item['note'] }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @else
                <article class="help-guide-card">
                    <div class="help-guide-card-header">
                        <h2 class="help-guide-card-title">{{ $section['title'] }}</h2>
                        <span class="help-guide-menu">{{ $section['menu'] }}</span>
                    </div>

                    <p class="help-guide-description">{{ $section['description'] }}</p>

                    <ol class="help-guide-steps">
                        @foreach ($section['steps'] as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>

                    @if (!empty($section['note']))
                        <p class="help-guide-note">{{ $section['note'] }}</p>
                    @endif
                </article>
            @endif
        @endforeach
    </div>
</x-filament-panels::page>
