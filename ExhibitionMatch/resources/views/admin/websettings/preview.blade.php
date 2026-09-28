<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Preview - {{ config('app.name', 'ExhibitionMatch') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ @filemtime(public_path('css/landing.css')) ?: time() }}">
        @endif
    </head>
    <body>
        @php
            $landingImageUrl = $landingImageUrl ?? null;
            $mapImageUrl = $mapImageUrl ?? null;
        @endphp

        <div style="position: sticky; top: 0; z-index: 50; background: rgba(17, 24, 39, 0.92); color: #fff;">
            <div style="max-width: 430px; margin: 0 auto; padding: 10px 14px; display:flex; align-items:center; justify-content:space-between; gap: 12px;">
                <a href="{{ route('admin.websettings.index', ['edit' => $set->getKey()]) }}" style="color:#fff; text-decoration:none; font-weight:800;">
                    ← Back
                </a>
                <div style="font-weight:900; opacity:0.95;">Preview</div>
            </div>
        </div>

        <div class="landing-shell">
            <main class="landing-screen">
                <section class="landing-hero">
                    @if (!empty($landingImageUrl))
                        <img src="{{ $landingImageUrl }}" alt="Landing image" class="landing-image" />
                    @else
                        <div class="landing-placeholder"></div>
                    @endif

                    <div class="landing-fade"></div>
                </section>

                <section class="landing-content">
                    <h1 class="landing-title">
                        @if (!empty($set?->web_title))
                            {!! nl2br(e($set->web_title)) !!}
                        @else
                            出展者マッチン<br />グ管理システム
                        @endif
                    </h1>

                    <p class="landing-desc">
                        {{ $set?->web_detail ?: '' }}
                    </p>
                </section>

                @if (!empty($mapImageUrl))
                    <section style="padding: 18px 28px 34px;">
                        <div style="font-weight:900; color:#111827; margin-bottom: 10px;">Map</div>
                        <img src="{{ $mapImageUrl }}" alt="Map image" style="width: 100%; border-radius: 14px; border: 1px solid rgba(17,24,39,0.12);" />
                    </section>
                @else
                    <div style="padding: 18px 28px 34px;"></div>
                @endif
            </main>
        </div>
    </body>
</html>

