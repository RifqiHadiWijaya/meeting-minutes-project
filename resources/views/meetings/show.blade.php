<x-app-layout>
    <h1>{{ $meeting->judul }}</h1>

    <p>Tanggal: {{ $meeting->tanggal }}</p>
    <p>Lokasi: {{ $meeting->lokasi }}</p>

    <hr>

    <h3>Notulensi</h3>

    {!! $meeting->notulensi !!}
</x-app-layout>
