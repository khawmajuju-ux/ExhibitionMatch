<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Admin') - {{ config('app.name', 'ExhibitionMatch') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) ?: time() }}">
        @endif
    </head>
    <body class="admin-body">
        <header class="admin-crumbbar">
            <div class="admin-crumbbar__inner">
                <div class="admin-crumb">
                    @yield('breadcrumb')
                </div>

                <div class="admin-crumbbar__actions">
                    @yield('top_actions')
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="admin-actionBtn admin-actionBtn--dark">ログアウト</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="admin-plain">
            <div class="admin-content">
                @if (session('success'))
                    <div class="admin-alert admin-alert--success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="admin-alert admin-alert--error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </body>
</html>

