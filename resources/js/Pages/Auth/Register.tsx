import { Link, useForm } from '@inertiajs/react';

export default function Register() {
    const form = useForm({ name: '', email: '', password: '', password_confirmation: '' });

    function submit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        form.post('/register');
    }

    return (
        <main className="auth-shell">
            <aside className="auth-aside">
                <Link href="/" className="wordmark"><span className="wordmark-icon">S</span><span>si<span className="wordmark-accent">pustaka</span></span></Link>
                <div className="auth-copy"><p className="eyebrow">Bergabung dengan perpustakaan</p><h1>Satu akun, banyak cerita.</h1><p>Buat akun anggota untuk melihat koleksi dan mengajukan peminjaman buku.</p></div>
                <span className="auth-footnote">AKUN BARU OTOMATIS MENJADI ANGGOTA</span>
            </aside>
            <section className="auth-main">
                <form className="auth-form" onSubmit={submit}>
                    <h2>Daftar anggota</h2><p>Lengkapi data di bawah untuk membuat akun.</p>
                    {Object.keys(form.errors).length > 0 && <div className="auth-error">Periksa kembali data yang ditandai.</div>}
                    <div className="field"><label htmlFor="name">Nama lengkap</label><input id="name" autoComplete="name" value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} aria-invalid={Boolean(form.errors.name)} required />{form.errors.name && <span className="field-error">{form.errors.name}</span>}</div>
                    <div className="field"><label htmlFor="email">Alamat email</label><input id="email" type="email" autoComplete="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} aria-invalid={Boolean(form.errors.email)} required />{form.errors.email && <span className="field-error">{form.errors.email}</span>}</div>
                    <div className="field"><label htmlFor="password">Kata sandi</label><input id="password" type="password" autoComplete="new-password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} aria-invalid={Boolean(form.errors.password)} required />{form.errors.password && <span className="field-error">{form.errors.password}</span>}</div>
                    <div className="field"><label htmlFor="password_confirmation">Ulangi kata sandi</label><input id="password_confirmation" type="password" autoComplete="new-password" value={form.data.password_confirmation} onChange={(event) => form.setData('password_confirmation', event.target.value)} required /></div>
                    <button className="btn" type="submit" disabled={form.processing}>{form.processing ? 'Membuat akun...' : 'Buat akun anggota'}</button>
                    <div className="auth-switch">Sudah terdaftar? <Link href="/login">Masuk</Link></div>
                </form>
            </section>
        </main>
    );
}