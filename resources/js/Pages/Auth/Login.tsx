import { Link, useForm } from '@inertiajs/react';

export default function Login() {
    const form = useForm({ email: '', password: '', remember: false });

    function submit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        form.post('/login');
    }

    return (
        <main className="auth-shell">
            <aside className="auth-aside">
                <Link href="/" className="wordmark"><span className="wordmark-icon">S</span><span>si<span className="wordmark-accent">pustaka</span></span></Link>
                <div className="auth-copy"><p className="eyebrow">Ruang baca digital</p><h1>Selamat datang kembali.</h1><p>Masuk untuk mengakses koleksi dan aktivitas peminjaman SiPustaka.</p></div>
                <span className="auth-footnote">KOLEKSI TERJAGA · PENGETAHUAN TERBUKA</span>
            </aside>
            <section className="auth-main">
                <form className="auth-form" onSubmit={submit}>
                    <h2>Masuk ke akun</h2><p>Gunakan email dan kata sandi yang terdaftar.</p>
                    {Object.keys(form.errors).length > 0 && <div className="auth-error">Periksa kembali email dan kata sandi Anda.</div>}
                    <div className="field"><label htmlFor="email">Alamat email</label><input id="email" type="email" autoComplete="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} aria-invalid={Boolean(form.errors.email)} required />{form.errors.email && <span className="field-error">{form.errors.email}</span>}</div>
                    <div className="field"><label htmlFor="password">Kata sandi</label><input id="password" type="password" autoComplete="current-password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} aria-invalid={Boolean(form.errors.password)} required />{form.errors.password && <span className="field-error">{form.errors.password}</span>}</div>
                    <label className="remember-row"><input type="checkbox" checked={form.data.remember} onChange={(event) => form.setData('remember', event.target.checked)} /> Ingat saya</label>
                    <button className="btn" type="submit" disabled={form.processing}>{form.processing ? 'Memeriksa...' : 'Masuk'}</button>
                    <div className="auth-switch">Belum terdaftar? <Link href="/register">Buat akun anggota</Link></div>
                </form>
            </section>
        </main>
    );
}