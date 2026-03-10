<x-app-layout>

    {{-- ══ PAGE HEADER ══ --}}
    <div class="page-topbar">
        <div>
            <div class="page-title">Edit User</div>
            <div class="page-subtitle">{{ $user->name }}</div>
        </div>
        @if($user->role === 'admin')
            <span class="role-badge role-admin">Admin</span>
        @elseif($user->role === 'notulis')
            <span class="role-badge role-notulis">Notulis</span>
        @else
            <span class="role-badge role-viewer">Viewer</span>
        @endif
    </div>

    {{-- Session success --}}
    @if(session('success'))
        <div class="alert-success">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if($errors->any())
        <div class="alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;margin-top:1px;">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <div>
                <strong style="font-weight:700;">Terdapat kesalahan pada form:</strong>
                <ul style="margin:4px 0 0 16px; list-style:disc;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ── Card 1: Info User ── --}}
        <div class="card">
            <div class="card-header">
                <span class="card-header-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Informasi Pengguna
                </span>
            </div>
            <div class="card-body">
                <div class="form-grid">

                    {{-- Nama --}}
                    <div class="form-group full">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input type="text" name="name"
                                class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                                value="{{ old('name', $user->name) }}" required>
                        </div>
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Username --}}
                    <div class="form-group">
                        <label class="form-label">Username <span class="required">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                            <input type="text" name="username"
                                class="form-input {{ $errors->has('username') ? 'is-error' : '' }}"
                                placeholder="Contoh: budi.santoso"
                                value="{{ old('username', $user->username) }}" required>
                        </div>
                        @error('username') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    {{-- Role --}}
                    <div class="form-group">
                        <label class="form-label">Role <span class="required">*</span></label>
                        <select name="role" class="form-select {{ $errors->has('role') ? 'is-error' : '' }}" required>
                            <option value="viewer"  {{ old('role', $user->role) === 'viewer'  ? 'selected' : '' }}>Viewer</option>
                            <option value="notulis" {{ old('role', $user->role) === 'notulis' ? 'selected' : '' }}>Notulis</option>
                            <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Card 2: Ganti Password ── --}}
        <div class="card">
            <div class="card-header">
                <span class="card-header-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    Ganti Password
                </span>
                <span style="font-size:11px; color:#94a3b8;">Kosongkan jika tidak ingin mengganti password</span>
            </div>
            <div class="card-body">
                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                            </span>
                            <input type="password" name="password"
                                class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                                placeholder="Minimal 8 karakter">
                        </div>
                        @error('password') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-wrap">
                            <span class="input-icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                            </span>
                            <input type="password" name="password_confirmation"
                                class="form-input"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="card" style="overflow:visible;">
            <div class="form-footer" style="border-radius:0 0 12px 12px;">
                <a href="{{ route('users.index') }}" class="btn-secondary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>

</x-app-layout>