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
                Tambah Rapat
            </h2>
        </x-slot>

        <div class="py-6">
            <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

                <form method="POST" action="{{ route('meetings.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block">Judul Rapat</label>
                        <input type="text" name="title" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Tanggal</label>
                        <input type="date" name="date" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Waktu</label>
                        <input type="time" name="time" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Lokasi</label>
                        <input type="text" name="location" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Peserta</label>
                        <textarea name="participants" class="w-full border rounded p-2" required></textarea>
                    </div>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded">
                        Simpan
                    </button>

                </form>

            </div>
        </div>
    </x-app-layout>
</body>
</html>