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

        menubar: 'file edit view insert format tools table',

        plugins: 'lists link table',

        toolbar: `
            undo redo |
            formatselect |
            bold italic underline |
            alignleft aligncenter alignright |
            bullist numlist |
            table
        `,

        block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3',

        valid_elements: `
            p,h1,h2,h3,strong/b,em/i,u,
            ul,ol,li,
            table,thead,tbody,tr,th,td,
            a[href|target=_blank]
        `,

        forced_root_block: 'p',
        branding: false
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