import { Link, usePage } from '@inertiajs/react';
import type { SharedProps } from '../types';

export default function Home() {
    const { auth } = usePage<SharedProps>().props;

    return (
        <main className="main-area home-main">
            <header className="page-topline">
                <Link href="/" className="wordmark">
                    <span className="wordmark-icon">S</span><span>si<span className="wordmark-accent">pustaka</span></span>
                </Link>
                <div className="heading-actions">
                    {auth.user ? <Link className="btn" href="/dashboard">Buka dashboard <span aria-hidden="true">↗</span></Link> : <>
                        <Link className="btn secondary" href="/login">Masuk</Link>
                        <Link className="btn" href="/register">Daftar anggota <span aria-hidden="true">↗</span></Link>
                    </>}
                </div>
            </header>
            <section className="home-cover">
                <div className="home-copy">
                    <p className="eyebrow">Katalog & peminjaman perpustakaan</p>
                    <h1>Temukan bacaan yang membuka dunia.</h1>
                    <p>Jelajahi koleksi perpustakaan, ajukan peminjaman, dan pantau tanggal pengembalian dari satu ruang digital.</p>
                    <div className="heading-actions">
                        <Link className="btn" href={auth.user ? '/pendaftaran' : '/register'}>{auth.user ? 'Jelajahi koleksi' : 'Mulai sebagai anggota'} <span aria-hidden="true">→</span></Link>
                        {!auth.user && <Link className="text-link" href="/login">Sudah punya akun?</Link>}
                    </div>
                </div>
                <div className="home-art" aria-label="Ilustrasi tumpukan buku">
                    <div className="book-stack"><div className="book"><span>Ruang<br />untuk<br />bertumbuh</span></div><div className="book"><span>CATATAN<br />PERJALANAN</span></div><div className="book"><span>Kumpulan<br />cerita</span></div></div>
                </div>
            </section>
        </main>
    );
}