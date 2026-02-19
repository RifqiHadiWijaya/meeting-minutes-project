<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rapat</title>
</head>
<body>
    <x-app-layout>
        <h1>Daftar Rapat</h1>

        {{-- Tombol filter status --}}
        <div style="margin: 10px;">
            <form method="GET">
                <label for="status">Filter Status:</label>
                <select name="status" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </form>
        </div>

        @if(auth()->user()->role === 'notulis')
            <a href="{{ route('meetings.create') }}">Buat Rapat</a>
        @endif

        @foreach($meetings as $meeting)
            <div style="border:1px solid #ccc; margin:10px; padding:10px;">
                <h3>{{ $meeting->judul }}</h3>
                <p>{{ $meeting->tanggal }} - {{ $meeting->lokasi }}</p>

                <p>Dibuat oleh: {{ $meeting->creator->name }}</p>

                <p>Jumlah Pertanyaan: {{ $meeting->questions_count }}</p>

                <a href="{{ route('meetings.show', $meeting->id) }}">Detail</a>

                @can('update', $meeting)
                    <a href="{{ route('meetings.edit', $meeting->id) }}">Edit</a>
                @endcan
            </div>
        @endforeach
        <div style="margin-top: 20px;">
            {{ $meetings->links() }}
        </div>
    </x-app-layout>
</body>
</html>