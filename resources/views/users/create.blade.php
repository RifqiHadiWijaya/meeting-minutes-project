<form method="POST" action="{{ route('users.store') }}">
@csrf

<input type="text" name="name" placeholder="Nama">

<input type="email" name="email" placeholder="Email">

<input type="password" name="password" placeholder="Password">

<select name="role">
    <option value="viewer">Viewer</option>
    <option value="notulis">Notulis</option>
    <option value="admin">Admin</option>
</select>

<button type="submit">Simpan</button>
</form>
