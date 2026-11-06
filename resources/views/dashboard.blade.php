@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<main class="container">
    <header class="page-head"><div><p class="eyebrow">{{ auth()->user()->isAdmin() ? 'Administrasi' : 'Akun peminjam' }}</p><h1>Halo, {{ auth()->user()->name }}.</h1><p class="subtitle">Ringkasan aktivitas perpustakaan Anda.</p></div><a class="button" href="{{ route('pendaftaran.index') }}">{{ auth()->user()->isAdmin() ? 'Kelola katalog' : 'Ajukan peminjaman' }}</a></header>
    <section class="metrics"><div class="metric"><small>Koleksi buku</small><strong>{{ number_format($bookCount) }}</strong></div><div class="metric"><small>{{ auth()->user()->isAdmin() ? 'Total peminjaman' : 'Peminjaman Anda' }}</small><strong>{{ number_format($loanCount) }}</strong></div><div class="metric"><small>Peran akun</small><strong style="font-size:23px;margin-top:12px">{{ auth()->user()->isAdmin() ? 'Admin' : 'Pengguna' }}</strong></div></section>
    <section class="panel"><div class="panel-head"><div><h2>{{ auth()->user()->isAdmin() ? 'Peminjaman terbaru' : 'Aktivitas Anda' }}</h2><p>Catatan terbaru di SiPustaka.</p></div><a href="{{ route('pendaftaran.index') }}">Lihat semua</a></div>
        <div class="table-wrap"><table><thead><tr><th>Peminjam</th><th>Buku</th><th>Tanggal pinjam</th><th>Tanggal kembali</th><th>Status</th></tr></thead><tbody>
        @forelse ($recentLoans as $loan)<tr><td>{{ $loan->borrower_name }}</td><td>{{ $loan->book?->title ?? 'Buku dihapus' }}</td><td>{{ $loan->loan_date->format('d M Y') }}</td><td>{{ $loan->return_date->format('d M Y') }}</td><td><span class="tag {{ $loan->status === 'Dikembalikan' ? 'returned' : '' }}">{{ $loan->status }}</span></td></tr>
        @empty<tr><td colspan="5" class="muted">Belum ada peminjaman.</td></tr>@endforelse
        </tbody></table></div>
    </section>
</main>
@endsection