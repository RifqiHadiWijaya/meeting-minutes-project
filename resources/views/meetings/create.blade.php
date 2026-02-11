<x-app-layout>
    <h1>Buat Rapat</h1>

    <form action="{{ route('meetings.store') }}" method="POST">
        @csrf

        <input type="text" name="judul" placeholder="Judul"><br>
        <input type="date" name="tanggal"><br>
        <input type="time" name="waktu"><br>
        <input type="text" name="lokasi" placeholder="Lokasi"><br>
        <textarea name="topik" placeholder="Topik"></textarea><br>

        <button type="submit">Simpan</button>
    </form>
</x-app-layout>
