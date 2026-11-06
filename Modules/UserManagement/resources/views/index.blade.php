@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<main class="container">
    <header class="page-head"><div><p class="eyebrow">Modul UserManagement</p><h1>Pengguna</h1><p class="subtitle">Kelola akun, akses, dan peran SiPustaka.</p></div></header>
    @if (session('success')) <div class="notice">{{ session('success') }}</div> @endif
    @if ($errors->any()) <div class="notice error">{{ $errors->first() }}</div> @endif
    <details class="panel"><summary>Tambah pengguna</summary><form class="inline-edit" method="POST" action="{{ route('users.store') }}">@csrf
        <div class="form-grid"><div class="field"><label>Nama</label><input name="name" value="{{ old('name') }}" required>@error('name')<span class="field-error">{{ $message }}</span>@enderror</div><div class="field"><label>Email</label><input name="email" type="email" value="{{ old('email') }}" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror</div><div class="field"><label>Peran</label><select name="role"><option value="user">Pengguna</option><option value="admin">Admin</option></select>@error('role')<span class="field-error">{{ $message }}</span>@enderror</div><div class="field"><label>Kata sandi awal</label><input name="password" type="password" minlength="8" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror</div></div><div class="form-actions"><button type="submit">Buat akun</button></div>
    </form></details>
    <section class="panel"><div class="panel-head"><div><h2>Daftar akun</h2><p>{{ $users->total() }} pengguna terdaftar.</p></div></div>
        <div class="table-wrap"><table><thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Peminjaman</th><th>Aksi</th></tr></thead><tbody>
        @foreach($users as $user)
            <tr><td><strong>{{ $user->name }}</strong>@if($user->is(auth()->user()))<br><span class="muted">Akun Anda</span>@endif</td><td>{{ $user->email }}</td><td><span class="tag">{{ $user->isAdmin() ? 'Admin' : 'Pengguna' }}</span></td><td>{{ $user->loans_count }}</td><td>
                <details><summary>Edit</summary><form class="inline-edit" method="POST" action="{{ route('users.update', $user) }}">@csrf @method('PUT')
                    <div class="form-grid"><div class="field"><label>Nama</label><input name="name" value="{{ $user->name }}" required>@error('name')<span class="field-error">{{ $message }}</span>@enderror</div><div class="field"><label>Email</label><input name="email" type="email" value="{{ $user->email }}" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror</div><div class="field"><label>Peran</label><select name="role"><option value="user" @selected(!$user->isAdmin())>Pengguna</option><option value="admin" @selected($user->isAdmin())>Admin</option></select>@error('role')<span class="field-error">{{ $message }}</span>@enderror</div><div class="field"><label>Kata sandi baru</label><input name="password" type="password" minlength="8"><span class="hint">Kosongkan jika tidak diganti.</span>@error('password')<span class="field-error">{{ $message }}</span>@enderror</div></div><div class="form-actions"><button class="small-btn" type="submit">Simpan perubahan</button></div>
                </form></details>
                @if(!$user->is(auth()->user()))<form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus akun {{ $user->name }}?')" style="margin-top:8px">@csrf @method('DELETE')<button class="danger small-btn" type="submit">Hapus</button></form>@endif
            </td></tr>
        @endforeach
        </tbody></table></div>
        @if($users->hasPages())<div class="pagination"><span>Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>{{ $users->links() }}</div>@endif
    </section>
</main>
@endsection