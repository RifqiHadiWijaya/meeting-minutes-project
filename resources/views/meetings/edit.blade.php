<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800">
                Isi Notulensi Rapat
            </h2>
        </x-slot>

        <div class="py-6">
            <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

                <p class="mb-2"><strong>Judul:</strong> {{ $meeting->title }}</p>
                <p class="mb-2"><strong>Tanggal:</strong> {{ $meeting->date }}</p>
                <p class="mb-4"><strong>Lokasi:</strong> {{ $meeting->location }}</p>

                <form method="POST" action="{{ route('meetings.update', $meeting->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block font-semibold">Agenda</label>
                        <textarea name="agenda" class="w-full border rounded p-2" required>{{ $meeting->agenda }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold">Hasil Pembahasan</label>
                        <textarea name="discussion" class="w-full border rounded p-2" required>{{ $meeting->discussion }}</textarea>
                    </div>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded">
                        Simpan Notulensi
                    </button>
                </form>

            </div>
        </div>
    </x-app-layout>
</body>
</html>