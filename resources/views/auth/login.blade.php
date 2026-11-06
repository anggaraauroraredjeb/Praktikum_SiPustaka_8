@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<main class="auth-wrap">
    <section class="auth-copy"><div class="auth-mark" aria-hidden="true">S.</div><p class="eyebrow">Selamat datang kembali</p><h1>Lanjutkan cerita.</h1><p>Masuk untuk melihat koleksi dan aktivitas peminjaman SiPustaka.</p></section>
    <section class="auth-card">
        <h2>Masuk</h2><p class="subtitle" style="margin-bottom:20px">Gunakan email dan kata sandi akun Anda.</p>
        @if ($errors->any()) <div class="notice error">{{ $errors->first() }}</div> @endif
        <form method="POST" action="{{ route('login.store') }}">@csrf
            <div class="field"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus></div>
            <div class="field"><label for="password">Kata sandi</label><input id="password" name="password" type="password" autocomplete="current-password" required></div>
            <label style="display:flex;align-items:center;gap:8px;font:12px Arial,sans-serif;margin:12px 0 18px"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
            <button type="submit" style="width:100%">Masuk</button>
        </form>
        <p class="auth-foot">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    </section>
</main>
@endsection