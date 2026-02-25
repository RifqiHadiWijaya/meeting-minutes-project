<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">

        <h1 class="text-2xl font-bold mb-4">
            {{ $meeting->judul }}
        </h1>

        <div class="bg-white shadow rounded p-6 mb-6">
            <p><strong>Tanggal:</strong> {{ $meeting->tanggal }}</p>
            <p><strong>Lokasi:</strong> {{ $meeting->lokasi }}</p>
            <p><strong>Status:</strong> 
                <span class="
                    {{ $meeting->status === 'completed' ? 'bg-green-600' : 'bg-blue-600' }}">
                    {{ ucfirst($meeting->status) }}
                </span>
            </p>
        </div>

        <div class="bg-white shadow rounded p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Notulensi</h3>
            <div class="prose max-w-none">
                {!! $meeting->notulensi !!}
            </div>
        </div>

        <a href="{{ route('meetings.pdf', $meeting->id) }}" 
           class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
            Download PDF
        </a>
    
<h3>Pertanyaan & Klarifikasi</h3>

{{-- Form Pertanyaan (Hanya Viewer) --}}
@if(auth()->user()->role === 'viewer')
    <form action="{{ route('questions.store', $meeting->id) }}" method="POST">
        @csrf
        <textarea name="isi" placeholder="Tulis pertanyaan..." required></textarea>
        <button type="submit">Kirim Pertanyaan</button>
    </form>
@endif

<hr>

{{-- List Pertanyaan --}}
@forelse($meeting->questions as $question)

    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <strong>{{ $question->user->name }}</strong>
        <p>{{ $question->isi }}</p>

        {{-- Jawaban --}}
        @foreach($question->replies as $reply)
            <div style="margin-left:30px; border-left:3px solid #999; padding-left:10px;">
                <strong>{{ $reply->user->name }}</strong>
                <p>{{ $reply->isi }}</p>
            </div>
        @endforeach

        {{-- Form Jawaban (Hanya Notulis) --}}
        @if(auth()->user()->role === 'notulis' 
            && auth()->id() === $meeting->created_by)

            <form action="{{ route('questions.reply', $question->id) }}" method="POST">
                @csrf
                <textarea name="isi" placeholder="Tulis jawaban..." required></textarea>
                <button type="submit">Balas</button>
            </form>

        @endif

    </div>

@empty
    <p>Belum ada pertanyaan.</p>
@endforelse
</x-app-layout>
