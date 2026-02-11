<x-app-layout>
    <h1>Data User</h1>

    <a href="{{ route('users.create') }}">Tambah User</a>

    @foreach($users as $user)
        <div>
            {{ $user->name }} - {{ $user->role }}

            <a href="{{ route('users.edit', $user->id) }}">Edit</a>

            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </div>
    @endforeach
</x-app-layout>
