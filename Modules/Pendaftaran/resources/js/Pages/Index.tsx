import { Link, router, usePage } from '@inertiajs/react';
import { useState, type FormEvent } from 'react';
import AppLayout from '../../../../../resources/js/Layouts/AppLayout';
import type { AppUser, SharedProps } from '../../../../../resources/js/types';

type Book = { id: number; title: string; author: string; publisher: string | null; published_year: number | null; stock: number };
type Loan = {
    id: number;
    borrower_name: string;
    school: string;
    study_program: string;
    email: string;
    phone: string;
    loan_date: string;
    return_date: string;
    status: 'Dipinjam' | 'Dikembalikan';
    document_path: string | null;
    book: Book;
    user?: AppUser;
};
type PageLink = { url: string | null; label: string; active: boolean };
type Paginator<T> = { data: T[]; links: PageLink[]; total: number; from: number | null; to: number | null };
type Props = { books: Paginator<Book>; loans: Paginator<Loan>; allBooks: Book[]; search: string };
type FormValues = Record<string, string | boolean | File>;

const emptyBook = { title: '', author: '', publisher: '', published_year: '', stock: '0' };
const emptyLoan = { book_id: '', borrower_name: '', school: '', study_program: '', email: '', phone: '', loan_date: '', return_date: '' };

function PagePagination({ links }: { links: PageLink[] }) {
    if (links.length < 4) return null;
    return <nav className="pagination" aria-label="Paginasi">{links.map((link, index) => link.url ? <Link key={`${link.label}-${index}`} href={link.url} preserveScroll className={link.active ? 'current' : ''}>{link.label.replace('&laquo;', '‹').replace('&raquo;', '›')}</Link> : <span key={`${link.label}-${index}`}>{link.label.replace('&laquo;', '‹').replace('&raquo;', '›')}</span>)}</nav>;
}

function Field({ label, name, value, onChange, error, type = 'text', required = false, ...props }: {
    label: string; name: string; value: string; onChange?: (value: string) => void; error?: string; type?: string; required?: boolean; [key: string]: unknown;
}) {
    return <div className="field"><label htmlFor={name}>{label}</label><input id={name} name={name} type={type} {...(onChange ? { value, onChange: (event) => onChange(event.target.value) } : { defaultValue: value })} aria-invalid={Boolean(error)} required={required} {...props} />{error && <span className="field-error">{error}</span>}</div>;
}

export default function PendaftaranIndex({ books, loans, allBooks, search }: Props) {
    const { auth } = usePage<SharedProps>().props;
    const user = auth.user!;
    const isAdmin = user.role === 'admin';
    const [bookFormOpen, setBookFormOpen] = useState(false);
    const [bookEditing, setBookEditing] = useState<Book | null>(null);
    const [bookValues, setBookValues] = useState<Record<string, string>>(emptyBook);
    const [loanEditing, setLoanEditing] = useState<Loan | null>(null);
    const [notice, setNotice] = useState('');
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [busy, setBusy] = useState(false);

    async function send(event: FormEvent<HTMLFormElement>, url: string, method: 'POST' | 'PUT' | 'DELETE', onSuccess: () => void) {
        event.preventDefault();
        setBusy(true);
        setErrors({});
        const formData = new FormData(event.currentTarget);
        if (method !== 'POST') formData.set('_method', method);
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '',
                },
                body: formData,
            });
            const result = await response.json();
            if (!response.ok) {
                if (result.errors) setErrors(Object.fromEntries(Object.entries(result.errors as Record<string, string[]>).map(([field, messages]) => [field, messages[0]])));
                else setNotice(result.message || 'Perubahan tidak dapat disimpan.');
                return;
            }
            setNotice(result.message || 'Perubahan berhasil disimpan.');
            onSuccess();
            router.reload({ only: ['books', 'loans', 'allBooks'] });
        } catch {
            setNotice('Koneksi gagal. Silakan coba lagi.');
        } finally {
            setBusy(false);
        }
    }

    function startBookEdit(book: Book) {
        setBookEditing(book);
        setBookValues({ title: book.title, author: book.author, publisher: book.publisher ?? '', published_year: book.published_year?.toString() ?? '', stock: String(book.stock) });
        setBookFormOpen(true);
        setErrors({});
    }

    function startLoanEdit(loan: Loan) {
        setLoanEditing(loan);
        setErrors({});
    }

    function handleSearch(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        router.get('/pendaftaran', { search: data.get('search')?.toString() ?? '' }, { preserveState: true, replace: true });
    }

    return (
        <AppLayout title="Buku & peminjaman" description="Kelola katalog, ajukan peminjaman, dan pantau pengembalian.">
            {notice && <div className="flash-message" role="status">{notice}</div>}
            <section className="panel">
                <div className="panel-heading"><div><h2>Katalog buku</h2><p>Telusuri koleksi dan ketersediaan buku.</p></div><span className="panel-count">{books.total} judul</span></div>
                <div className="toolbar">
                    <form className="search-form" onSubmit={handleSearch}><input name="search" type="search" defaultValue={search} placeholder="Cari judul, penulis, penerbit" aria-label="Cari katalog" /><button className="btn secondary" type="submit">Cari</button></form>
                    {isAdmin && <button className="btn" type="button" onClick={() => { setBookEditing(null); setBookValues(emptyBook); setBookFormOpen(!bookFormOpen); setErrors({}); }}>{bookFormOpen ? 'Tutup form' : '+ Tambah buku'}</button>}
                </div>
                {isAdmin && bookFormOpen && <form className="panel form-panel" onSubmit={(event) => send(event, bookEditing ? `/pendaftaran/books/${bookEditing.id}` : '/pendaftaran/books', bookEditing ? 'PUT' : 'POST', () => { setBookFormOpen(false); setBookEditing(null); })}>
                    <div className="panel-heading"><div><h2>{bookEditing ? 'Ubah data buku' : 'Buku baru'}</h2><p>Lengkapi informasi katalog.</p></div></div>
                    <div className="form-grid">
                        <Field label="Judul buku" name="title" value={bookValues.title} onChange={(value) => setBookValues({ ...bookValues, title: value })} error={errors.title} required />
                        <Field label="Penulis" name="author" value={bookValues.author} onChange={(value) => setBookValues({ ...bookValues, author: value })} error={errors.author} required />
                        <Field label="Penerbit" name="publisher" value={bookValues.publisher} onChange={(value) => setBookValues({ ...bookValues, publisher: value })} error={errors.publisher} />
                        <Field label="Tahun terbit" name="published_year" type="number" value={bookValues.published_year} onChange={(value) => setBookValues({ ...bookValues, published_year: value })} error={errors.published_year} />
                        <Field label="Stok" name="stock" type="number" value={bookValues.stock} onChange={(value) => setBookValues({ ...bookValues, stock: value })} error={errors.stock} required min="0" />
                    </div>
                    <div className="form-actions"><button className="btn" disabled={busy}>{busy ? 'Menyimpan...' : bookEditing ? 'Simpan perubahan' : 'Tambahkan buku'}</button><button type="button" className="btn secondary" onClick={() => setBookFormOpen(false)}>Batal</button></div>
                </form>}
                <div className="table-wrap"><table><thead><tr><th>Judul</th><th>Penulis</th><th>Penerbit</th><th>Tahun</th><th>Stok</th>{isAdmin && <th>Aksi</th>}</tr></thead><tbody>
                    {books.data.map((book) => <tr key={book.id}><td><strong>{book.title}</strong></td><td>{book.author}</td><td>{book.publisher || '—'}</td><td>{book.published_year || '—'}</td><td><span className={`status ${book.stock > 0 ? 'active' : ''}`}>{book.stock} tersedia</span></td>{isAdmin && <td><div className="action-group"><button className="btn secondary small" onClick={() => startBookEdit(book)}>Ubah</button><button className="btn danger small" onClick={() => { if (window.confirm(`Hapus buku "${book.title}"?`)) formDataSubmit(`/pendaftaran/books/${book.id}`, 'DELETE'); }}>Hapus</button></div></td>}</tr>)}
                    {!books.data.length && <tr><td colSpan={isAdmin ? 6 : 5} className="empty-state">Buku tidak ditemukan.</td></tr>}
                </tbody></table></div>
                <PagePagination links={books.links} />
            </section>

            <section className="panel">
                <div className="panel-heading"><div><h2>{isAdmin ? 'Daftar peminjaman' : 'Peminjaman saya'}</h2><p>{isAdmin ? 'Kelola pengajuan dan status pengembalian.' : 'Ajukan buku dan lihat status pengembalian.'}</p></div><span className="panel-count">{loans.total} data</span></div>
                <div className="table-wrap"><table><thead><tr><th>Peminjam</th><th>Buku</th>{isAdmin && <th>Kontak</th>}<th>Tanggal</th><th>Status</th><th>Bukti</th>{isAdmin && <th>Aksi</th>}</tr></thead><tbody>
                    {loans.data.map((loan) => <tr key={loan.id}><td><strong>{loan.borrower_name}</strong><span className="subline">{loan.school}</span></td><td>{loan.book.title}</td>{isAdmin && <td>{loan.email}<span className="subline">{loan.phone}</span></td>}<td>{dateValue(loan.loan_date)}<span className="subline">Kembali {dateValue(loan.return_date)}</span></td><td><span className={`status ${loan.status === 'Dipinjam' ? 'active' : 'returned'}`}>{loan.status}</span></td><td>{loan.document_path ? <a className="text-link" href={`/pendaftaran/loans/${loan.id}/document`} target="_blank" rel="noreferrer">Buka berkas ↗</a> : <span className="subline">—</span>}</td>{isAdmin && <td><div className="action-group"><button className="btn secondary small" onClick={() => startLoanEdit(loan)}>Ubah</button><button className="btn danger small" onClick={() => { if (window.confirm('Hapus data peminjaman ini?')) formDataSubmit(`/pendaftaran/loans/${loan.id}`, 'DELETE'); }}>Hapus</button></div></td>}</tr>)}
                    {!loans.data.length && <tr><td colSpan={isAdmin ? 7 : 5} className="empty-state">Belum ada data peminjaman.</td></tr>}
                </tbody></table></div>
                <PagePagination links={loans.links} />
            </section>

            <section className="grid-two loan-workspace">
                <form key={loanEditing?.id ?? 'new-loan'} className="panel" encType="multipart/form-data" onSubmit={(event) => send(event, loanEditing ? `/pendaftaran/loans/${loanEditing.id}` : '/pendaftaran/loans', loanEditing ? 'PUT' : 'POST', () => setLoanEditing(null))}>
                    <div className="panel-heading"><div><h2>{loanEditing ? 'Ubah peminjaman' : 'Ajukan peminjaman'}</h2><p>{loanEditing ? 'Perbarui data peminjaman.' : 'Isi data peminjam dan pilih buku yang tersedia.'}</p></div>{loanEditing && <button type="button" className="text-link" onClick={() => setLoanEditing(null)}>Batal ubah</button>}</div>
                    {errors.message && <div className="error-banner">{errors.message}</div>}
                    <div className="form-grid">
                        <div className="field full"><label htmlFor="book_id">Buku</label><select id="book_id" name="book_id" defaultValue={loanEditing?.book.id ?? ''} aria-invalid={Boolean(errors.book_id)} required><option value="" disabled>Pilih buku</option>{allBooks.map((book) => <option key={book.id} value={book.id} disabled={!isAdmin && book.stock < 1}>{book.title} · stok {book.stock}</option>)}</select>{errors.book_id && <span className="field-error">{errors.book_id}</span>}</div>
                        <Field label="Nama peminjam" name="borrower_name" value={loanEditing?.borrower_name ?? user.name} onChange={() => {}} error={errors.borrower_name} required readOnly={!isAdmin} />
                        <Field label="Asal sekolah" name="school" value={loanEditing?.school ?? ''} onChange={() => {}} error={errors.school} required />
                        <Field label="Program studi" name="study_program" value={loanEditing?.study_program ?? ''} onChange={() => {}} error={errors.study_program} required />
                        <Field label="Email" name="email" type="email" value={loanEditing?.email ?? user.email} onChange={() => {}} error={errors.email} required readOnly={!isAdmin} />
                        <Field label="Nomor HP" name="phone" type="tel" value={loanEditing?.phone ?? ''} onChange={() => {}} error={errors.phone} required />
                        <Field label="Tanggal pinjam" name="loan_date" type="date" value={dateValue(loanEditing?.loan_date) ?? ''} onChange={() => {}} error={errors.loan_date} required />
                        <Field label="Tanggal kembali" name="return_date" type="date" value={dateValue(loanEditing?.return_date) ?? ''} onChange={() => {}} error={errors.return_date} required />
                        {loanEditing && <div className="field"><label htmlFor="status">Status</label><select name="status" id="status" defaultValue={loanEditing.status}><option>Dipinjam</option><option>Dikembalikan</option></select>{errors.status && <span className="field-error">{errors.status}</span>}</div>}
                        <div className="field full"><label htmlFor="document">Bukti peminjaman (JPG, PNG, PDF, maks. 2 MB)</label><input id="document" name="document" type="file" accept=".jpg,.jpeg,.png,.pdf" aria-invalid={Boolean(errors.document)} />{errors.document && <span className="field-error">{errors.document}</span>}{loanEditing?.document_path && <label className="file-remove"><input type="checkbox" name="remove_document" value="1" /> Hapus bukti saat ini</label>}</div>
                    </div>
                    <div className="form-actions"><button className="btn" disabled={busy}>{busy ? 'Menyimpan...' : loanEditing ? 'Simpan peminjaman' : 'Kirim pengajuan'}</button></div>
                </form>
                {loanEditing && <div className="panel loan-edit-note"><div className="panel-heading"><div><h2>Informasi pengajuan</h2><p>Data peminjam #{loanEditing.id}</p></div></div><p className="dashboard-note">Perubahan status akan memperbarui stok buku secara otomatis. Berkas lama dihapus dari penyimpanan saat diganti atau dihapus.</p></div>}
            </section>
        </AppLayout>
    );
}

function dateValue(value?: string) {
    return value?.slice(0, 10);
}

function formDataSubmit(url: string, method: 'DELETE') {
    const token = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content ?? '';
    const formData = new FormData();
    formData.set('_method', method);
    fetch(url, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' }, body: formData })
        .then(async (response) => {
            const result = await response.json();
            if (!response.ok) window.alert(result.message || 'Data tidak dapat dihapus.');
            else router.reload({ only: ['books', 'loans', 'allBooks'] });
        })
        .catch(() => window.alert('Koneksi gagal. Silakan coba lagi.'));
}