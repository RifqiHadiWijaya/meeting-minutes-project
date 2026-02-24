<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notulensi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
    </style>
</head>
<body>
    <h2>{{ $meeting->judul }}</h2>

    <p>Tanggal: {{ $meeting->tanggal }}</p>
    <p>Lokasi: {{ $meeting->lokasi }}</p>

    <hr>

    {!! $meeting->notulensi !!}
</body>
</html>