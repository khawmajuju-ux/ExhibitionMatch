<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'ExhibitionMatch') }}</title>

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
            $settings = $settings ?? null;
            $landingImageUrl = $landingImageUrl ?? null;
        @endphp

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
                        @if (!empty($settings?->web_title))
                            {!! nl2br(e($settings->web_title)) !!}
                        @else
                            出展者マッチン<br />グ管理システム
                        @endif
                    </h1>

                    <p class="landing-desc">
                        {{ $settings?->web_detail ?: "jj;k; l’pooliftydtrfkuhjikolpoitdr fghjklijutrfiojjokkk;jklml.mlklml klkoklhhoij" }}
                    </p>
                </section>

                @php
                    $registerUrl = Route::has('visitor.register') ? route('visitor.register') : url('/visitor/register');
                @endphp

                <section class="landing-actions">
                    <a
                        href="{{ $registerUrl ?: '#' }}"
                        class="landing-btn"
                    >
                        申込
                    </a>
                </section>
            </main>
        </div>
    </body>
</html>


