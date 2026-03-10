<x-app-layout>

    {{-- FullCalendar CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">


    {{-- ══════════════════════════════
         STAT CARDS
    ══════════════════════════════ --}}
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,#3b82f6,#1d4ed8)">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Total Rapat</div>
                <div class="stat-value">{{ $totalMeetings }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,#16a34a,#15803d)">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ $completedCount }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,#f59e0b,#d97706)">
                <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="stat-label">Terjadwal</div>
                <div class="stat-value">{{ $scheduledCount }}</div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════
         MAIN GRID: KALENDER + RAPAT TERAKHIR
    ══════════════════════════════ --}}
    <div class="main-grid">

        {{-- Kalender --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Kalender Rapat
                </div>
                <div style="display:flex;gap:12px;font-size:11.5px;color:#64748b;">
                    <span style="display:flex;align-items:center;gap:4px;">
                        <span style="width:10px;height:10px;background:#2563eb;border-radius:3px;display:inline-block;"></span>
                        Terjadwal
                    </span>
                    <span style="display:flex;align-items:center;gap:4px;">
                        <span style="width:10px;height:10px;background:#16a34a;border-radius:3px;display:inline-block;"></span>
                        Selesai
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

        {{-- 5 Rapat Terakhir --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Rapat Terakhir
                </div>
                <a href="{{ route('meetings.index') }}" class="btn-view-all">
                    Lihat Semua
                    <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="meeting-list">
                @forelse($recentMeetings as $meeting)
                    <a href="{{ route('meetings.show', $meeting->id) }}" class="meeting-item">

                        {{-- Kotak tanggal --}}
                        <div class="meeting-date-box">
                            <div class="meeting-date-day">
                                {{ \Carbon\Carbon::parse($meeting->tanggal)->format('d') }}
                            </div>
                            <div class="meeting-date-month">
                                {{ \Carbon\Carbon::parse($meeting->tanggal)->translatedFormat('M') }}
                            </div>
                        </div>

                        {{-- Info rapat --}}
                        <div class="meeting-info">
                            <div class="meeting-title">{{ $meeting->judul }}</div>
                            <div class="meeting-meta">
                                @if($meeting->lokasi)
                                <span>
                                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $meeting->lokasi }}
                                </span>
                                @endif
                                <span>
                                    <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M12 6v6l4 2"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($meeting->waktu)->format('H:i') }}
                                </span>
                            </div>
                        </div>

                        {{-- Badge status --}}
                        @if($meeting->status === 'completed')
                            <span class="badge badge-completed">
                                <span class="badge-dot"></span> Selesai
                            </span>
                        @else
                            <span class="badge badge-scheduled">
                                <span class="badge-dot"></span> Terjadwal
                            </span>
                        @endif

                    </a>
                @empty
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Belum ada data rapat
                    </div>
                @endforelse
            </div>

        </div>

    </div>

    {{-- FullCalendar JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: @json($events),
            eventClick: function(info) {
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            },
            height: 'auto',
        });
        calendar.render();
    });
    </script>
    @endpush

</x-app-layout>