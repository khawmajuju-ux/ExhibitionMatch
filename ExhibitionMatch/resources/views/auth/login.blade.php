<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'ExhibitionMatch') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/login.css'])
    @else
        <link rel="stylesheet" href="{{ asset('css/login.css') }}?v={{ @filemtime(public_path('css/login.css')) ?: time() }}">
    @endif
</head>
<body class="login-body">
    @php
        $landingImageUrl = $landingImageUrl ?? null;
    @endphp
    <div class="login-container">
        <!-- Left Side: Promotional Poster -->
        <div
            class="login-poster {{ !empty($landingImageUrl) ? 'has-image' : '' }}"
            @if (!empty($landingImageUrl))
                style="--login-poster-image: url('{{ $landingImageUrl }}');"
            @endif
        >
            @if (empty($landingImageUrl))
                <div class="login-poster__content">
                    <div class="login-poster__logo">wac</div>
                    <div class="login-poster__intro">Introducing</div>
                    <div class="login-poster__title">Beyond</div>
                    <div class="login-poster__arc"></div>
                    <div class="login-poster__subtitle">Technology & Marketing Summit - 2025</div>
                </div>
            @endif
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-form-wrapper">
            <div class="login-form-container">
                <h1 class="login-form__title">Hello Again!</h1>
                <p class="login-form__subtitle">Welcome Back</p>

                @if ($errors->any())
                    <div class="login-alert">
                        <ul class="login-alert__list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="login-alert">
                        <ul class="login-alert__list">
                            <li>{{ session('error') }}</li>
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <div class="login-field">
                        <div class="login-input-wrapper">
                            <svg class="login-input__icon" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0Z" clip-rule="evenodd" />
                                <path fill="currentColor" fill-rule="evenodd" d="M3.75 20.105a8.25 8.25 0 0 1 16.5 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5a18.683 18.683 0 0 1-7.813-1.7.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                            </svg>
                            <input
                                id="username"
                                name="username"
                                type="text"
                                class="login-input @error('username') is-invalid @enderror"
                                value="{{ old('username') }}"
                                placeholder="Username"
                                required
                                autocomplete="username"
                                autofocus
                            />
                        </div>
                        @error('username')
                            <div class="login-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="login-field">
                        <div class="login-input-wrapper">
                            <svg class="login-input__icon" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3H6a3 3 0 0 0-3 3v7.5a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3v-7.5a3 3 0 0 0-3-3h-.75v-3A5.25 5.25 0 0 0 12 1.5Zm-3.75 5.25a3.75 3.75 0 0 1 7.5 0v3h-7.5v-3Z" clip-rule="evenodd" />
                            </svg>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="login-input @error('password') is-invalid @enderror"
                                placeholder="Password"
                                required
                                autocomplete="current-password"
                            />
                        </div>
                        @error('password')
                            <div class="login-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="login-button">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


