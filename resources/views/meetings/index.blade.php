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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daftar Rapat
            </h2>
        </x-slot>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 rounded shadow">
                    <table class="w-full border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2">Judul</th>
                                <th class="border p-2">Tanggal</th>
                                <th class="border p-2">Waktu</th>
                                <th class="border p-2">Lokasi</th>
                                <th class="border p-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($meetings as $meeting)
                                <tr>
                                    <td class="border p-2">{{ $meeting->title }}</td>
                                    <td class="border p-2">{{ $meeting->date }}</td>
                                    <td class="border p-2">{{ $meeting->time }}</td>
                                    <td class="border p-2">{{ $meeting->location }}</td>
                                    <td class="border p-2">{{ $meeting->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4">
                                        Belum ada data rapat
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>
</html>