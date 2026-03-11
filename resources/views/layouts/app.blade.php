<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name'))</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Styles --}}
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">


    </head>

    <body>
        <div class="layout">

            {{-- ═══════════════ SIDEBAR ═══════════════ --}}
            <aside class="sidebar">

                <div class="sidebar-brand">
                    <div class="sidebar-brand-icon">
                        <img src="{{ asset('images/logo-diskominfo.png') }}" alt="Logo" class="sidebar-logo-img">
                    </div>
                    <div>
                        <div class="sidebar-brand-text">Sistem Notulensi</div>
                        <div class="sidebar-brand-sub">Rapat Dinas</div>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <div class="nav-section-label">Menu Utama</div>

                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('meetings.index') }}"
                       class="nav-link {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Riwayat Rapat
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <div class="nav-section-label" style="margin-top: 8px;">Administrasi</div>

                        <a href="{{ route('users.index') }}"
                           class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                            Kelola User
                        </a>
                    @endif
                </nav>


                <div class="sidebar-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>

            </aside>

            {{-- ═══════════════ MAIN AREA ═══════════════ --}}
            <div class="main-area">

                {{-- HEADER --}}
                <header class="top-header">
                    <div class="header-title">
                        <span class="sinora-si">SI</span><span class="sinora-nora">NORA</span>
                        <span class="sinora-full">Sistem Informasi Notulensi Rapat Dinas</span>
                    </div>

                    <div class="header-right">

                        <div class="clock-badge">
                            <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                            <span id="clock"></span>
                        </div>

                        <div class="user-chip">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="user-name">{{ auth()->user()->name }}</div>
                                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                            </div>
                        </div>

                    </div>
                </header>

                {{-- PAGE CONTENT --}}
                <main class="page-content">
                    {{ $slot }}
                </main>

            </div>

        </div>

        {{-- Clock Script --}}
        <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerText =
                now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
                + '  ' +
                now.toLocaleTimeString('id-ID');
        }
        setInterval(updateClock, 1000);
        updateClock();
        </script>

        @stack('scripts')

    </body>
</html>