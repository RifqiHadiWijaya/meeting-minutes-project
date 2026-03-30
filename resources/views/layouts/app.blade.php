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
        @vite(['resources/css/main.css', 'resources/css/responsive.css', 'resources/css/auth.css', 'resources/css/app.css', 'resources/js/app.js'])

        {{-- Styles --}}


    </head>

    <body>
        <div class="layout">

            {{-- ═══════════════ SIDEBAR ═══════════════ --}}

            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <aside class="sidebar" id="sidebar">

                <button class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Tutup menu">
                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>

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

                    <button class="hamburger-btn" id="hamburgerBtn" aria-label="Buka menu">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"/>
                            <line x1="3" y1="12" x2="21" y2="12"/>
                            <line x1="3" y1="18" x2="21" y2="18"/>
                        </svg>
                    </button>

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

        {{-- Sidebar drawer script --}}
        <script>
        (function () {
            var sidebar   = document.getElementById('sidebar');      // <-- id="sidebar" di <aside>
            var overlay   = document.getElementById('sidebarOverlay');
            var hamburger = document.getElementById('hamburgerBtn');
            var closeBtn  = document.getElementById('sidebarCloseBtn');

            function triggerCalendarResize() {
                // Paksa FullCalendar re-hitung ukuran setelah sidebar animasi selesai
                setTimeout(function () {
                    var calEl = document.getElementById('calendar');
                    if (calEl && calEl._calendar) {
                        calEl._calendar.updateSize();
                    }
                    // Fallback: dispatch resize event
                    window.dispatchEvent(new Event('resize'));
                }, 300); // setelah animasi 280ms selesai
            }

            function openSidebar() {
                if (!sidebar) return;
                sidebar.classList.add('sidebar-open');
                if (overlay) overlay.classList.add('active');
                document.documentElement.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (!sidebar) return;
                sidebar.classList.remove('sidebar-open');
                if (overlay) overlay.classList.remove('active');
                document.documentElement.style.overflow = '';
                document.body.style.overflow = '';
                triggerCalendarResize(); // re-render kalender setelah sidebar tutup
            }

            if (hamburger) hamburger.addEventListener('click', openSidebar);
            if (closeBtn)  closeBtn.addEventListener('click', closeSidebar);
            if (overlay)   overlay.addEventListener('click', closeSidebar);

            // Tutup sidebar saat klik nav-link (navigasi)
            if (sidebar) {
                sidebar.querySelectorAll('.nav-link').forEach(function (link) {
                    link.addEventListener('click', function () {
                        setTimeout(closeSidebar, 150);
                    });
                });
            }

            // Tutup sidebar & reset overflow saat resize ke desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    closeSidebar();
                }
            });

            // ESC key untuk tutup sidebar
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeSidebar();
            });

            // Table scroll mask helper
            document.querySelectorAll('.table-wrap').forEach(function (wrap) {
                wrap.addEventListener('scroll', function () {
                    var atEnd = wrap.scrollLeft + wrap.clientWidth >= wrap.scrollWidth - 4;
                    wrap.classList.toggle('scrolled-end', atEnd);
                });
            });
        })();
        </script>

        @stack('scripts')

    </body>
</html>