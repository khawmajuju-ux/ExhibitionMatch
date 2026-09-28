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
        <div class="admin-layout">
            <aside class="admin-sidebar">
                <a class="admin-brand" href="{{ route('admin.dashboard') }}" aria-label="Admin dashboard">
                    <span class="admin-brand__logo">
                        <img
                            class="admin-brand__logoImg"
                            src="{{ asset('images/logo_01.png') }}"
                            alt="ALT design office"
                            loading="eager"
                        />
                    </span>
                </a>

                <nav class="admin-nav">
                    <a class="admin-nav__item {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="admin-nav__icon" aria-hidden="true">
                            <img src="{{ asset('images/pie-chart.png') }}" alt="Dashboard" style="width: 24px; height: 24px; object-fit: contain;" />
                        </span>
                        <span class="admin-nav__label">{{ __('admin.nav.dashboard') }}</span>
                    </a>

                    <div class="admin-nav__group">
                        @php($usersOpen = request()->routeIs('admin.exhibitors.*') || request()->routeIs('admin.visitors.*'))
                        <button
                            type="button"
                            class="admin-nav__item admin-nav__toggle"
                            aria-expanded="{{ $usersOpen ? 'true' : 'false' }}"
                            aria-controls="admin-nav-users-sub"
                        >
                            <span class="admin-nav__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M20 21v-1a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="admin-nav__label">{{ __('admin.nav.user') }}</span>
                            <span class="admin-nav__caret" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <div id="admin-nav-users-sub" class="admin-nav__sub" @if(!$usersOpen) hidden @endif>
                            <a class="admin-nav__subitem {{ request()->routeIs('admin.exhibitors.*') ? 'is-active' : '' }}" href="{{ route('admin.exhibitors.index') }}">{{ __('admin.nav.exhibitors') }}</a>
                            <a class="admin-nav__subitem {{ request()->routeIs('admin.visitors.*') ? 'is-active' : '' }}" href="{{ route('admin.visitors.index') }}">{{ __('admin.nav.visitors') }}</a>
                        </div>
                    </div>

                    <a class="admin-nav__item {{ request()->routeIs('admin.matching.*') ? 'is-active' : '' }}" href="{{ route('admin.matching.index') }}">
                        <span class="admin-nav__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M8 12h8M8 16h5M8 8h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                <path d="M6 3h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <span class="admin-nav__label">{{ __('admin.nav.matching') }}</span>
                    </a>

                    <div class="admin-nav__group">
                        @php($settingsOpen = request()->routeIs('admin.problemtags.*') || request()->routeIs('admin.websettings.*'))
                        <button
                            type="button"
                            class="admin-nav__item admin-nav__toggle"
                            aria-expanded="{{ $settingsOpen ? 'true' : 'false' }}"
                            aria-controls="admin-nav-settings-sub"
                        >
                            <span class="admin-nav__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 0 0-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 0 0-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 0 0-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 0 0-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 0 0 1.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.065Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span class="admin-nav__label">{{ __('admin.nav.setting') }}</span>
                            <span class="admin-nav__caret" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <div id="admin-nav-settings-sub" class="admin-nav__sub" @if(!$settingsOpen) hidden @endif>
                            <a class="admin-nav__subitem {{ request()->routeIs('admin.problemtags.*') ? 'is-active' : '' }}" href="{{ route('admin.problemtags.index') }}">{{ __('admin.nav.problemtag') }}</a>
                            <a class="admin-nav__subitem {{ request()->routeIs('admin.websettings.*') ? 'is-active' : '' }}" href="{{ route('admin.websettings.index') }}">{{ __('admin.nav.webdetail') }}</a>
                        </div>
                    </div>
                </nav>
            </aside>

            <main class="admin-main">
                <header class="admin-topbar">
                    <div class="admin-topbar__left">
                        @yield('topbar_left')
                    </div>

                    <div class="admin-topbar__actions">
                        @unless (request()->routeIs('admin.dashboard'))
                            <a
                                class="admin-actionBtn"
                                href="{{ route('admin.export.excel', array_merge(['from' => request()->route()?->getName()], request()->except('page'))) }}"
                            >{{ __('admin.topbar.export_excel') }}</a>
                        @endunless

                        @php($locale = session('locale', app()->getLocale()))
                        @php($localeLabel = strtoupper($locale === 'ja' ? 'JP' : $locale))
                        <div class="admin-locale" tabindex="0">
                            <button type="button" class="admin-actionBtn admin-actionBtn--select" aria-haspopup="menu">
                                {{ $localeLabel }}
                                <span class="admin-locale__caret" aria-hidden="true">▼</span>
                            </button>
                            <div class="admin-locale__menu" role="menu">
                                <a class="admin-locale__item" href="{{ route('admin.locale.set', ['locale' => 'ja', 'redirect' => url()->full()]) }}">JP</a>
                                <a class="admin-locale__item" href="{{ route('admin.locale.set', ['locale' => 'en', 'redirect' => url()->full()]) }}">EN</a>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="admin-actionBtn admin-actionBtn--dark">{{ __('admin.topbar.logout') }}</button>
                        </form>
                    </div>
                </header>

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
        </div>

        <script>
            (function () {
                function updateFileName(input) {
                    if (!input || input.type !== 'file') return;
                    const id = input.getAttribute('id');
                    if (!id) return;
                    const target = document.querySelector('[data-file-name-for="' + CSS.escape(id) + '"]');
                    if (!target) return;
                    const placeholder = input.getAttribute('data-placeholder') || '';
                    const file = input.files && input.files[0] ? input.files[0] : null;
                    target.textContent = file ? file.name : placeholder;
                }

                document.querySelectorAll('input[type="file"][data-file-input]').forEach(function (input) {
                    updateFileName(input);
                    input.addEventListener('change', function () {
                        updateFileName(input);
                    });
                });
            })();
        </script>

        <script>
            (function () {
                const toggles = Array.from(document.querySelectorAll('.admin-nav__toggle[aria-controls]'));
                if (toggles.length === 0) return;

                function setExpanded(btn, expanded) {
                    const id = btn.getAttribute('aria-controls');
                    const target = id ? document.getElementById(id) : null;
                    btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                    if (target) {
                        target.hidden = !expanded;
                    }
                }

                toggles.forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const expanded = btn.getAttribute('aria-expanded') === 'true';
                        setExpanded(btn, !expanded);
                    });
                });
            })();
        </script>
    </body>
</html>


