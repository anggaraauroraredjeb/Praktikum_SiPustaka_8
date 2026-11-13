import { Link, usePage } from '@inertiajs/react';
import type { PropsWithChildren, ReactNode } from 'react';
import type { SharedProps } from '../types';

type Props = PropsWithChildren<{
    title: string;
    description?: string;
    actions?: ReactNode;
}>;

export default function AppLayout({ title, description, actions, children }: Props) {
    const { auth, navigation, flash } = usePage<SharedProps>().props;
    const path = window.location.pathname;

    return (
        <div className="app-shell">
            <aside className="sidebar">
                <Link href={auth.user ? '/dashboard' : '/'} className="wordmark">
                    <span className="wordmark-icon">S</span>
                    <span>si<span className="wordmark-accent">pustaka</span></span>
                </Link>
                <div className="sidebar-label">Perpustakaan</div>
                <nav className="side-nav" aria-label="Navigasi utama">
                    {navigation.map((item, index) => {
                        const active = path === item.href || (item.href !== '/dashboard' && path.startsWith(item.href));
                        return (
                            <Link key={item.href} href={item.href} className={active ? 'nav-item active' : 'nav-item'}>
                                <span className="nav-index">0{index + 1}</span>{item.label}
                            </Link>
                        );
                    })}
                </nav>
                <div className="sidebar-bottom">
                    <div className="profile-chip">
                        <div className="avatar">{auth.user?.name.slice(0, 1).toUpperCase()}</div>
                        <div className="profile-copy">
                            <strong>{auth.user?.name}</strong>
                            <span>{auth.user?.role === 'admin' ? 'Administrator' : 'Anggota'}</span>
                        </div>
                    </div>
                    <Link href="/logout" method="post" as="button" className="logout-link">Keluar <span aria-hidden="true">↗</span></Link>
                </div>
            </aside>
            <main className="main-area">
                <header className="page-topline">
                    <span className="topline-mark">SIPUSTAKA <i>/</i> {auth.user?.role === 'admin' ? 'ADMIN' : 'ANGGOTA'}</span>
                    <span className="topline-status"><i /> Layanan aktif</span>
                </header>
                {flash.success && <div className="flash-message" role="status">{flash.success}</div>}
                <div className="page-heading">
                    <div>
                        <p className="eyebrow">Ruang koleksi digital</p>
                        <h1>{title}</h1>
                        {description && <p className="page-description">{description}</p>}
                    </div>
                    {actions && <div className="heading-actions">{actions}</div>}
                </div>
                {children}
            </main>
        </div>
    );
}