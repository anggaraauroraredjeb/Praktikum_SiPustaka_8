import { Link } from '@inertiajs/react';
import AppLayout from '../Layouts/AppLayout';
import type { AppUser } from '../types';

type RecentLoan = {
    id: number;
    borrower_name: string;
    loan_date: string;
    return_date: string;
    status: string;
    book: { title: string };
};

type Props = { bookCount: number; loanCount: number; recentLoans: RecentLoan[]; user: AppUser };

export default function Dashboard({ bookCount, loanCount, recentLoans, user }: Props) {
    return (
        <AppLayout title={`Selamat datang, ${user.name.split(' ')[0]}`} description="Ringkasan koleksi dan aktivitas peminjaman.">
            <section className="metrics">
                <article className="metric"><span className="metric-label">Judul buku</span><strong className="metric-value">{bookCount.toLocaleString('id-ID')}</strong></article>
                <article className="metric"><span className="metric-label">{user.role === 'admin' ? 'Seluruh peminjaman' : 'Peminjaman saya'}</span><strong className="metric-value">{loanCount.toLocaleString('id-ID')}</strong></article>
                <article className="metric"><span className="metric-label">Status akun</span><strong className="metric-value" style={{ fontSize: 22 }}>Aktif<span className="metric-note">●</span></strong></article>
            </section>
            <section className="grid-two">
                <div className="panel">
                    <div className="panel-heading"><div><h2>Aktivitas terbaru</h2><p>{user.role === 'admin' ? 'Peminjaman yang baru tercatat' : 'Riwayat peminjaman Anda'}</p></div><span className="panel-count">{recentLoans.length} terbaru</span></div>
                    {recentLoans.length ? <div className="table-wrap"><table><thead><tr><th>Buku</th>{user.role === 'admin' && <th>Peminjam</th>}<th>Tanggal</th><th>Status</th></tr></thead><tbody>{recentLoans.map((loan) => <tr key={loan.id}><td><strong>{loan.book.title}</strong></td>{user.role === 'admin' && <td>{loan.borrower_name}</td>}<td>{loan.loan_date.slice(0, 10)}</td><td><span className={`status ${loan.status === 'Dipinjam' ? 'active' : 'returned'}`}>{loan.status}</span></td></tr>)}</tbody></table></div> : <div className="empty-state">Belum ada aktivitas peminjaman.</div>}
                </div>
                <div className="panel dashboard-action">
                    <div className="panel-heading"><div><h2>Mulai dari koleksi</h2><p>Temukan buku berikutnya untuk dibaca.</p></div></div>
                    <p className="dashboard-note">Katalog memuat buku yang tersedia beserta informasi penulis, penerbit, dan stok. Anggota dapat mengajukan peminjaman langsung dari halaman katalog.</p>
                    <Link href="/pendaftaran" className="btn">Buka katalog buku <span aria-hidden="true">→</span></Link>
                </div>
            </section>
        </AppLayout>
    );
}