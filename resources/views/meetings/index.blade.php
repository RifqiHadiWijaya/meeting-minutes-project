<x-app-layout>
    <h1>Daftar Rapat</h1>

    @if(auth()->user()->role === 'notulis')
        <a href="{{ route('meetings.create') }}">Buat Rapat</a>
    @endif

    @foreach($meetings as $meeting)
        <div style="border:1px solid #ccc; margin:10px; padding:10px;">
            <h3>{{ $meeting->judul }}</h3>
            <p>{{ $meeting->tanggal }} - {{ $meeting->lokasi }}</p>

            <a href="{{ route('meetings.show', $meeting->id) }}">Detail</a>

            @can('update', $meeting)
                <a href="{{ route('meetings.edit', $meeting->id) }}">Edit</a>
            @endcan
        </div>
    @endforeach
</x-app-layout>
