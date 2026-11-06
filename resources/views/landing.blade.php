@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<main class="auth-wrap">
    <section class="auth-copy">
        <div class="auth-mark" aria-hidden="true">S.</div>
        <p class="eyebrow">Perpustakaan · Peminjaman</p>
        <h1>Ruang baca, dalam jangkauan.</h1>
        <p>Temukan koleksi, ajukan peminjaman, dan pantau pengembaliannya dalam satu tempat.</p>
        <div class="welcome-actions">
            @auth
                <a class="button" href="{{ route('dashboard') }}">Buka dashboard</a>
            @else
                <a class="button" href="{{ route('login') }}">Masuk ke SiPustaka</a>
                <a class="button secondary" href="{{ route('register') }}">Buat akun</a>
            @endauth
        </div>
    </section>
    <aside class="panel" style="border-top:4px solid var(--blue);max-width:390px">
        <p class="eyebrow">SiPustaka</p>
        <h2>Koleksi untuk ide berikutnya.</h2>
        <p class="subtitle">Jelajahi katalog buku yang tersedia dan kirim permohonan pinjam secara daring.</p>
        <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--line);font:13px/1.6 Arial,sans-serif;color:var(--muted)">
            Masuk sebagai peminjam untuk mengajukan peminjaman atau sebagai admin untuk mengelola katalog dan pengguna.
        </div>
    </aside>
</main>
@endsection