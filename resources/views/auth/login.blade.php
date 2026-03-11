<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – Sistem Notulensi</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

<div class="login-wrapper">

    {{-- Brand --}}
    <div class="brand">
        <div class="brand-icon">
            <img src="{{ asset('images/logo-diskominfo.png') }}" alt="Logo" class="brand-logo-img">
        </div>
        <div class="brand-text">
            <div class="brand-name">Sistem Notulensi</div>
            <div class="brand-sub">Rapat Dinas</div>
        </div>
    </div>

    {{-- Card --}}
    <div class="login-card">

        <div class="login-heading">Selamat Datang</div>
        <div class="login-subheading">Masuk untuk melanjutkan ke sistem</div>

        {{-- Error --}}
        @if($errors->any())
            <div class="alert-error">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Username --}}
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <input id="username" type="text" name="username"
                        class="form-input {{ $errors->has('username') ? 'is-error' : '' }}"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        required autofocus autocomplete="username">
                </div>
                @error('username') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrap">
                    <span class="input-icon">
                        <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                    </span>
                    <input id="password" type="password" name="password"
                        class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                        placeholder="Masukkan password"
                        required autocomplete="current-password">
                    <button type="button" class="toggle-pw" id="toggle-pw-btn"
                        onclick="togglePassword()" title="Tampilkan password">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
                </div>
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            {{-- Remember me --}}
            <div class="remember-row">
                <input type="checkbox" id="remember_me" name="remember">
                <label for="remember_me">Ingat saya</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-login">
                <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Masuk
            </button>

        </form>
    </div>

    <div class="login-footer">
        &copy; {{ date('Y') }} Sistem Informasi Notulensi Rapat Dinas
    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const btnEye = document.getElementById('toggle-pw-btn');
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';

    btnEye.innerHTML = isHidden
        ? `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
               <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
               <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
               <line x1="1" y1="1" x2="23" y2="23"/>
           </svg>`
        : `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
               <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
               <circle cx="12" cy="12" r="3"/>
           </svg>`;
}
</script>

</body>
</html>