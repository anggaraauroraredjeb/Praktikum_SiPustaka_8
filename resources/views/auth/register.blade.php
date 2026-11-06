@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<main class="auth-wrap">
    <section class="auth-copy"><div class="auth-mark" aria-hidden="true">S.</div><p class="eyebrow">Akun baru</p><h1>Mulai dari satu halaman.</h1><p>Daftar sebagai pengguna untuk mengakses katalog dan mengajukan peminjaman.</p></section>
    <section class="auth-card">
        <h2>Buat akun</h2><p class="subtitle" style="margin-bottom:20px">Semua kolom wajib diisi.</p>
        @if ($errors->any()) <div class="notice error">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('register.store') }}">@csrf
            <div class="field"><label for="name">Nama lengkap</label><input id="name" name="name" value="{{ old('name') }}" autocomplete="name" required></div>
            <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required></div>
            <div class="field"><label for="password">Kata sandi</label><input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required><span class="hint">Minimal 8 karakter.</span></div>
            <div class="field"><label for="password_confirmation">Ulangi kata sandi</label><input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" required></div>
            <button type="submit" style="width:100%;margin-top:8px">Daftar</button>
        </form>
        <p class="auth-foot">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
    </section>
</main>
@endsection