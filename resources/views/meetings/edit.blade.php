<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rapat</title>
</head>
<body>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

    <script>
    tinymce.init({
        selector: 'textarea[name="notulensi"]',
        license_key: 'gpl',
        promotion: false,
        height: 400,
        menubar: true,
        plugins: 'lists link image table code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright | code'
    });
    </script>

    <x-app-layout>
        <h1>Edit Rapat</h1>

        <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="text" name="judul" value="{{ $meeting->judul }}"><br>
            <input type="date" name="tanggal" value="{{ $meeting->tanggal }}"><br>
            <input type="time" name="waktu" value="{{ $meeting->waktu }}"><br>
            <input type="text" name="lokasi" value="{{ $meeting->lokasi }}"><br>

            <textarea name="notulensi">{{ $meeting->notulensi }}</textarea><br>

            <select name="status">
                <option value="scheduled" {{ $meeting->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="completed" {{ $meeting->status == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <button type="submit">Update</button>
        </form>
    </x-app-layout>
</body>
</html>