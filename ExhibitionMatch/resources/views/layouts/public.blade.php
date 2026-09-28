<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Visitor') - {{ config('app.name', 'ExhibitionMatch') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('css/visitor.css') }}?v={{ @filemtime(public_path('css/visitor.css')) ?: time() }}">
        @endif
    </head>
    <body>
        <div class="landing-shell">
            <main class="landing-screen">
                @if (session('success'))
                    <div class="visitor-alert visitor-alert--success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="visitor-alert visitor-alert--error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </body>
</html>


