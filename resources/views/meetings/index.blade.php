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
        @if(auth()->user()->role !== 'viewer')
            <a href="{{ route('meetings.create') }}"
            class="bg-green-600 text-white px-4 py-2 rounded mb-4 inline-block">
                + Tambah Rapat
            </a>
        @endif
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
                                <th class="border p-2">Aksi</th>
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
                                    <td class="border p-2">
                                        @if(auth()->id() === $meeting->created_by && auth()->user()->role !== 'viewer')
                                            <a href="{{ route('meetings.edit', $meeting->id) }}"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded">
                                                Isi Notulensi
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
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