import { Link, router, useForm, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '../../../../../resources/js/Layouts/AppLayout';
import type { AppUser, SharedProps } from '../../../../../resources/js/types';

type ManagedUser = AppUser & { loans_count: number; created_at: string };
type PageLink = { url: string | null; label: string; active: boolean };
type Props = { users: { data: ManagedUser[]; links: PageLink[]; total: number } };

export default function UserManagementIndex({ users }: Props) {
    const { auth } = usePage<SharedProps>().props;
    const [createOpen, setCreateOpen] = useState(false);
    const [editing, setEditing] = useState<ManagedUser | null>(null);
    const form = useForm({ name: '', email: '', role: 'user' as 'user' | 'admin', password: '' });

    function startCreate() {
        setEditing(null);
        form.reset();
        form.setData({ name: '', email: '', role: 'user', password: '' });
        setCreateOpen(true);
    }

    function startEdit(user: ManagedUser) {
        setCreateOpen(false);
        setEditing(user);
        form.clearErrors();
        form.setData({ name: user.name, email: user.email, role: user.role, password: '' });
    }

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        if (editing) {
            form.put(`/users/${editing.id}`, { preserveScroll: true, onSuccess: () => setEditing(null) });
        } else {
            form.post('/users', { preserveScroll: true, onSuccess: () => { form.reset(); setCreateOpen(false); } });
        }
    }

    return (
        <AppLayout title="Manajemen pengguna" description="Atur akun anggota, akses admin, dan aktivitas peminjaman.">
            <section className="panel">
                <div className="panel-heading"><div><h2>Daftar pengguna</h2><p>Perubahan role berlaku setelah data disimpan.</p></div><span className="panel-count">{users.total} akun</span></div>
                <div className="toolbar"><span className="panel-count">Akun Anda: {auth.user?.email}</span><button className="btn" onClick={startCreate}>+ Tambah pengguna</button></div>
                {createOpen && <UserForm title="Tambah pengguna" form={form} editing={false} onSubmit={submit} onCancel={() => setCreateOpen(false)} />}
                {editing && <UserForm title={`Ubah ${editing.name}`} form={form} editing onSubmit={submit} onCancel={() => setEditing(null)} isCurrentUser={editing.id === auth.user?.id} />}
                <div className="table-wrap"><table><thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Peminjaman</th><th>Bergabung</th><th>Aksi</th></tr></thead><tbody>
                    {users.data.map((user) => <tr key={user.id}><td><strong>{user.name}</strong>{user.id === auth.user?.id && <span className="subline">Akun Anda</span>}</td><td>{user.email}</td><td><span className={`status ${user.role === 'admin' ? 'active' : ''}`}>{user.role === 'admin' ? 'Admin' : 'Anggota'}</span></td><td>{user.loans_count}</td><td>{new Date(user.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td><td><div className="action-group"><button className="btn secondary small" onClick={() => startEdit(user)}>Ubah</button><button className="btn danger small" disabled={user.id === auth.user?.id} onClick={() => { if (window.confirm(`Hapus akun ${user.name}?`)) router.delete(`/users/${user.id}`, { preserveScroll: true }); }}>Hapus</button></div></td></tr>)}
                    {!users.data.length && <tr><td colSpan={6} className="empty-state">Belum ada pengguna.</td></tr>}
                </tbody></table></div>
                <nav className="pagination" aria-label="Paginasi pengguna">{users.links.map((link, index) => link.url ? <Link key={`${link.label}-${index}`} href={link.url} preserveScroll className={link.active ? 'current' : ''}>{link.label.replace('&laquo;', '‹').replace('&raquo;', '›')}</Link> : <span key={`${link.label}-${index}`}>{link.label.replace('&laquo;', '‹').replace('&raquo;', '›')}</span>)}</nav>
            </section>
        </AppLayout>
    );
}

function UserForm({ title, form, editing, onSubmit, onCancel, isCurrentUser = false }: {
    title: string;
    form: ReturnType<typeof useForm<{ name: string; email: string; role: 'user' | 'admin'; password: string }>>;
    editing: boolean;
    onSubmit: (event: FormEvent<HTMLFormElement>) => void;
    onCancel: () => void;
    isCurrentUser?: boolean;
}) {
    return <form className="panel form-panel" onSubmit={onSubmit}>
        <div className="panel-heading"><div><h2>{title}</h2><p>{editing ? 'Kosongkan kata sandi bila tidak ingin menggantinya.' : 'Akun akan langsung dapat digunakan.'}</p></div></div>
        <div className="form-grid">
            <div className="field"><label htmlFor="user-name">Nama lengkap</label><input id="user-name" value={form.data.name} onChange={(event) => form.setData('name', event.target.value)} aria-invalid={Boolean(form.errors.name)} required />{form.errors.name && <span className="field-error">{form.errors.name}</span>}</div>
            <div className="field"><label htmlFor="user-email">Email</label><input id="user-email" type="email" value={form.data.email} onChange={(event) => form.setData('email', event.target.value)} aria-invalid={Boolean(form.errors.email)} required />{form.errors.email && <span className="field-error">{form.errors.email}</span>}</div>
            <div className="field"><label htmlFor="user-role">Peran</label><select id="user-role" value={form.data.role} onChange={(event) => form.setData('role', event.target.value as 'user' | 'admin')} disabled={isCurrentUser} aria-invalid={Boolean(form.errors.role)}><option value="user">Anggota</option><option value="admin">Admin</option></select>{isCurrentUser && <span className="subline">Peran akun Anda tidak dapat diturunkan.</span>}{form.errors.role && <span className="field-error">{form.errors.role}</span>}</div>
            <div className="field"><label htmlFor="user-password">{editing ? 'Kata sandi baru' : 'Kata sandi'}</label><input id="user-password" type="password" autoComplete="new-password" value={form.data.password} onChange={(event) => form.setData('password', event.target.value)} aria-invalid={Boolean(form.errors.password)} required={!editing} />{form.errors.password && <span className="field-error">{form.errors.password}</span>}</div>
        </div>
        {(form.errors as Record<string, string>).user && <div className="error-banner">{(form.errors as Record<string, string>).user}</div>}
        <div className="form-actions"><button className="btn" disabled={form.processing}>{form.processing ? 'Menyimpan...' : 'Simpan pengguna'}</button><button type="button" className="btn secondary" onClick={onCancel}>Batal</button></div>
    </form>;
}