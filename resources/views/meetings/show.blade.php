<x-app-layout>
    <h1>{{ $meeting->judul }}</h1>

    <p>Tanggal: {{ $meeting->tanggal }}</p>
    <p>Lokasi: {{ $meeting->lokasi }}</p>

    <hr>

    <h3>Notulensi</h3>

    {!! $meeting->notulensi !!}

    {{-- form pertanyaan (hanya viewer) --}}
    <hr>
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
