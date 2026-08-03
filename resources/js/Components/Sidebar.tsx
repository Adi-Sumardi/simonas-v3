import { Link, usePage, router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { PageProps, User } from '@/types';

type Role = 'super' | 'admin' | 'mentor' | 'mahasiswa' | 'alumni' | 'pengurus_asrama';

interface MenuItem {
    label: string;
    icon: string;
    href: string;
    permission?: string;
    soon?: boolean;
}

interface MenuGroup {
    section: string;
    forRoles: Role[];
    items: MenuItem[];
}

// ─── Grouped Menu Definitions ────────────────────────────────────
// Super Admin melihat SEMUA grup, dipisah dengan section header
// Roles lain hanya melihat grup yang sesuai role mereka
const MENU_GROUPS: MenuGroup[] = [
    {
        section: '',  // no section label for the shared entry
        forRoles: ['super','admin','mentor','mahasiswa','alumni','pengurus_asrama'],
        items: [
            { label: 'Dashboard', icon: 'dashboard', href: '/dashboard' },
        ],
    },
    {
        section: 'Mahasiswa',
        forRoles: ['mahasiswa'],
        items: [
            { label: 'Profil',      icon: 'person',          href: '/mahasiswa/profil' },
            { label: 'Aktivitas',   icon: 'event_available', href: '/mahasiswa/aktivitas',   permission: 'log-aktivitas' },
            { label: 'Hafalan',     icon: 'auto_stories',    href: '/mahasiswa/hafalan',     permission: 'log-hafalan' },
            { label: 'Leaderboard', icon: 'military_tech',   href: '/mahasiswa/leaderboard', permission: 'view-leaderboard' },
            { label: 'Kalender',    icon: 'calendar_month',  href: '/mahasiswa/kalender' },
            { label: 'Kegiatan',    icon: 'event',           href: '/mahasiswa/kegiatan' },
            { label: 'Log Book Mentoring', icon: 'menu_book', href: '/mahasiswa/logbook' },
        ],
    },
    {
        section: 'Mentor',
        forRoles: ['mentor'],
        items: [
            { label: 'Warga Bimbingan', icon: 'people',          href: '/mentor/mentees' },
            { label: 'Hafalan Pending', icon: 'pending_actions', href: '/mentor/hafalan/pending', permission: 'nilai-santri' },
            { label: 'Penilaian',       icon: 'rate_review',     href: '/mentor/penilaian' },
            { label: 'Kalender',        icon: 'calendar_month',  href: '/mentor/kalender' },
            { label: 'Log Book Mentoring', icon: 'menu_book', href: '/mentor/logbook' },
            { label: 'Beasiswa Yayasan', icon: 'volunteer_activism', href: '/mentor/beasiswa/pending' },
        ],
    },
    {
        section: '',
        forRoles: ['super'],
        items: [
            { label: 'Role & Permission', icon: 'admin_panel_settings', href: '/super/role-permission' },
            { label: 'Warga',             icon: 'people_alt',           href: '/super/warga' },
            { label: 'Mentor',            icon: 'supervisor_account',   href: '/super/mentor' },
            { label: 'Alumni',            icon: 'school',               href: '/super/alumni' },
            { label: 'Kegiatan',          icon: 'event',                href: '/super/kegiatan' },
            { label: 'Hafalan',           icon: 'menu_book',            href: '/super/hafalan' },
            { label: 'Leaderboard',       icon: 'leaderboard',          href: '/super/leaderboard' },
            { label: 'Laporan',           icon: 'bar_chart',            href: '/super/laporan' },
            { label: 'Data Master',       icon: 'storage',              href: '/super/data-master' },
            { label: 'Pengaturan',        icon: 'settings',             href: '/super/pengaturan' },
        ],
    },
    {
        section: 'Admin',
        forRoles: ['admin'],
        items: [
            { label: 'Role & Permission', icon: 'admin_panel_settings', href: '/super/role-permission' },
            { label: 'Warga Asrama',      icon: 'people',               href: '/admin/warga',      soon: true },
            { label: 'Kegiatan Asrama',   icon: 'event',                href: '/admin/kegiatan',   soon: true },
            { label: 'Hafalan',           icon: 'auto_stories',         href: '/admin/hafalan',    soon: true },
            { label: 'Laporan',           icon: 'bar_chart',            href: '/admin/laporan',    soon: true },
        ],
    },
    {
        section: 'Alumni',
        forRoles: ['alumni'],
        items: [
            { label: 'Profil',          icon: 'person',      href: '/alumni/profil' },
            { label: 'Database',        icon: 'groups',      href: '/alumni/database' },
            { label: 'Postingan',       icon: 'forum',       href: '/alumni/hub' },
            { label: 'Bisnis',          icon: 'storefront',  href: '/alumni/bisnis' },
            { label: 'Lowongan',        icon: 'work',        href: '/alumni/jobs' },
        ],
    },
    {
        section: 'Pengurus Asrama',
        forRoles: ['pengurus_asrama'],
        items: [
            { label: 'Kegiatan Asrama', icon: 'event',           href: '/pengurus-asrama/kegiatan',     permission: 'manage-kegiatan-asrama' },
            { label: 'Program Kerja',   icon: 'assignment_turned_in', href: '/pengurus-asrama/program-kerja', permission: 'manage-program-kerja' },
        ],
    },
];

// Mobile bottom nav hrefs per role
const BOTTOM_NAV: Record<string, string[]> = {
    mahasiswa:        ['/dashboard', '/mahasiswa/aktivitas', '/mahasiswa/hafalan', '/mahasiswa/leaderboard', '/mahasiswa/profil'],
    mentor:           ['/dashboard', '/mentor/penilaian', '/mentor/kalender', 'drawer-toggle'],
    super:            ['/dashboard', '/super/warga', '/super/alumni', 'drawer-toggle'],
    admin:            ['/dashboard', '/super/role-permission'],
    alumni:           ['/dashboard', '/alumni/hub', '/alumni/jobs', '/alumni/bisnis', '/alumni/profil'],
    pengurus_asrama:  ['/pengurus-asrama/kegiatan', '/pengurus-asrama/program-kerja'],
};

const FAB_BY_ROLE: Record<string, { label: string; href: string; icon: string } | null> = {
    mahasiswa:       { label: 'Log Aktivitas', href: '/mahasiswa/aktivitas?action=create', icon: 'add_circle' },
    mentor:          null,
    super:           null,
    admin:           null,
    alumni:          null,
    pengurus_asrama: null,
};

interface SidebarProps { 
    user: User; 
    drawerOpen?: boolean;
    onClose?: () => void;
    onOpen?: () => void;
}

export function Sidebar({ user, drawerOpen = false, onClose, onOpen }: SidebarProps) {
    const { url, props } = usePage<PageProps>();

    // Multi-role: use Spatie roles array, fall back to single role column
    const userRoles   = (user.roles?.length ? user.roles : [user.role]) as Role[];
    const isMultiRole = userRoles.length > 1;
    const permissions = (props.permissions as string[]) ?? [];

    // Show groups for ALL roles the user has — merged with section headers when multi-role
    const visibleGroups = MENU_GROUPS
        .filter(g => g.forRoles.some(r => userRoles.includes(r)))
        .map(g => ({
            ...g,
            // Only show section header when user has multiple roles (so they can tell them apart)
            showHeader: isMultiRole && g.section !== '',
            items: g.items.filter(item =>
                !item.permission || permissions.includes(item.permission)
            ),
        }))
        .filter(g => g.items.length > 0);

    // Flat list for bottom nav
    const allItems    = visibleGroups.flatMap(g => g.items);
    // FAB: prefer mahasiswa, then first role that has one
    const fab         = userRoles.map(r => FAB_BY_ROLE[r]).find(f => f != null) ?? null;
    // Bottom nav: aggregate from all roles
    const bottomHrefs = [...new Set(userRoles.flatMap(r => BOTTOM_NAV[r] ?? []))];
    const bottomNav   = allItems.filter(m => bottomHrefs.includes(m.href) && !m.soon).slice(0, 5);

    // Add drawer toggle to bottom nav if requested
    if (bottomHrefs.includes('drawer-toggle')) {
        bottomNav.push({ label: 'Menu', icon: 'menu', href: 'drawer-toggle' });
    }

    function isActive(href: string) {
        if (href === 'drawer-toggle') return drawerOpen;
        if (href === '/dashboard') return url === '/dashboard' || url === '/dashboard/';
        // Exact-match routes that should not prefix-match sub-paths
        if (href === '/mentor') return url === '/mentor' || url === '/mentor/';
        return url.startsWith(href);
    }

    function handleLogout() { router.post('/logout'); }

    const isLiveMeet = url.includes('/live-meet');

    function NavItem({ item, mobile = false }: { item: MenuItem; mobile?: boolean }) {
        const active = isActive(item.href);

        if (item.soon) {
            if (mobile) return null;
            return (
                <div
                    title="Segera hadir"
                    className="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-on-surface-variant/40 cursor-not-allowed select-none"
                >
                    <Icon name={item.icon} className="text-xl" />
                    <span className="flex-1">{item.label}</span>
                    <span className="text-[9px] font-black uppercase tracking-wider bg-surface-container text-on-surface-variant/50 px-1.5 py-0.5 rounded-full">
                        Soon
                    </span>
                </div>
            );
        }

        if (item.href === 'drawer-toggle') {
            return (
                <button
                    onClick={isLiveMeet ? undefined : (onOpen || (() => {}))}
                    disabled={isLiveMeet}
                    className={`flex flex-col items-center justify-center gap-0.5 transition-all ${
                        active ? 'text-primary-container scale-110' : 'text-secondary/70'
                    } ${isLiveMeet ? 'pointer-events-none opacity-40 cursor-not-allowed' : ''}`}
                >
                    <Icon name={item.icon} className="text-2xl" filled={active} />
                    <span className="font-display text-[9px] uppercase tracking-wider leading-tight text-center">{item.label}</span>
                </button>
            );
        }

        return mobile ? (
            <Link
                href={isLiveMeet ? '#' : item.href}
                onClick={isLiveMeet ? (e) => e.preventDefault() : undefined}
                className={`flex flex-col items-center justify-center gap-0.5 transition-all ${
                    active ? 'text-primary-container scale-110' : 'text-secondary/70'
                } ${isLiveMeet ? 'pointer-events-none opacity-40 cursor-not-allowed' : ''}`}
            >
                <Icon name={item.icon} className="text-2xl" filled={active} />
                <span className="font-display text-[9px] uppercase tracking-wider leading-tight text-center">{item.label}</span>
            </Link>
        ) : (
            <Link
                href={isLiveMeet ? '#' : item.href}
                onClick={isLiveMeet ? (e) => e.preventDefault() : undefined}
                className={`flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all ${
                    active
                        ? 'bg-primary-container text-on-primary shadow-md shadow-blue-500/20'
                        : 'text-secondary hover:bg-white/40 hover:translate-x-0.5'
                } ${isLiveMeet ? 'pointer-events-none opacity-40 cursor-not-allowed' : ''}`}
            >
                <Icon name={item.icon} className="text-xl" filled={active} />
                {item.label}
            </Link>
        );
    }

    return (
        <>
            {/* ── Overlay Backdrop (Mobile & Super Admin Drawer) ── */}
            {(drawerOpen) && (
                <div 
                    className="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 transition-opacity"
                    onClick={onClose}
                />
            )}

            {/* ── Sidebar / Drawer ── */}
            <aside className={`flex flex-col h-[calc(100vh-64px)] w-64 fixed left-0 top-16 bg-white/60 backdrop-blur-2xl border-r border-white/30 shadow-[20px_0_30px_rgba(37,99,235,0.05)] p-4 z-40 transition-transform duration-300 print:hidden ${
                drawerOpen ? 'translate-x-0 !flex' : '-translate-x-full lg:translate-x-0 lg:flex'
            }`}>
                {/* Brand */}
                <div className="mb-4 px-2 flex items-center justify-between">
                    <div>
                        <div className="w-10 h-10 rounded-xl overflow-hidden shadow-md mb-3">
                            <img src="/images/simonas_logo.png" alt="SIMONAS" className="w-full h-full object-cover" />
                        </div>
                        <p className="font-display text-sm font-black text-primary-container">SIMONAS</p>
                    </div>
                    {drawerOpen && (
                        <button onClick={onClose} className="lg:hidden p-2 text-on-surface-variant hover:bg-surface-container rounded-lg">
                            <Icon name="close" />
                        </button>
                    )}
                </div>
                
                <p className="px-2 mb-4 text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">
                    {userRoles.length > 1
                        ? userRoles.map(r => r.charAt(0).toUpperCase() + r.slice(1)).join(' · ')
                        : userRoles[0] === 'super' ? 'Super Admin' : userRoles[0].charAt(0).toUpperCase() + userRoles[0].slice(1)
                    } Portal
                </p>

                {/* Grouped nav */}
                <nav className="flex-1 overflow-y-auto space-y-0.5 pr-1">
                    {visibleGroups.map((group, gi) => (
                        <div key={gi}>
                            {/* Section header — only shown when user has multiple roles */}
                            {group.showHeader && (
                                <p className={`text-[9px] font-black uppercase tracking-widest px-2 mb-1 ${gi > 0 ? 'mt-4' : 'mt-2'} ${
                                    group.section === 'Mahasiswa'        ? 'text-blue-500' :
                                    group.section === 'Mentor'          ? 'text-emerald-600' :
                                    group.section === 'Alumni'          ? 'text-violet-600' :
                                    group.section === 'Admin'           ? 'text-amber-600' :
                                    group.section === 'Pengurus Asrama' ? 'text-orange-600' :
                                    'text-on-surface-variant'
                                }`}>
                                    {group.section}
                                </p>
                            )}
                            {group.items.map(item => (
                                <NavItem key={`${item.href}-${item.label}`} item={item} />
                            ))}
                        </div>
                    ))}
                </nav>

                {/* FAB */}
                {fab && (
                    <div className="pt-2">
                        <Link
                            href={isLiveMeet ? '#' : fab.href}
                            onClick={isLiveMeet ? (e) => e.preventDefault() : undefined}
                            className={`w-full py-3.5 bg-primary text-on-primary rounded-xl shadow-lg shadow-blue-500/20 font-bold text-sm flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-95 transition-all ${
                                isLiveMeet ? 'pointer-events-none opacity-40 cursor-not-allowed' : ''
                            }`}
                        >
                            <Icon name={fab.icon} className="text-xl" filled />
                            {fab.label}
                        </Link>
                    </div>
                )}

                {/* Logout */}
                <button
                    onClick={handleLogout}
                    disabled={isLiveMeet}
                    className={`flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-error hover:bg-error/10 transition-colors mt-2 ${
                        isLiveMeet ? 'pointer-events-none opacity-40 cursor-not-allowed' : ''
                    }`}
                >
                    <Icon name="logout" className="text-xl" />
                    Keluar
                </button>
            </aside>

            {/* ── Mobile Bottom Nav ── */}
            <nav className="lg:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 pt-3 pb-safe pb-3 bg-white/80 backdrop-blur-lg border-t border-white/40 shadow-[0_-10px_25px_rgba(0,0,0,0.05)] print:hidden">
                {bottomNav.map(item => <NavItem key={`${item.href}-mob`} item={item} mobile />)}
            </nav>

            {/* Mobile FAB */}
            {fab && (
                <Link
                    href={isLiveMeet ? '#' : fab.href}
                    onClick={isLiveMeet ? (e) => e.preventDefault() : undefined}
                    className={`lg:hidden fixed bottom-20 right-5 w-14 h-14 bg-primary text-on-primary rounded-full shadow-[0_12px_24px_rgba(37,99,235,0.3)] flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-40 ${
                        isLiveMeet ? 'pointer-events-none opacity-40 cursor-not-allowed' : ''
                    }`}
                    aria-label={fab.label}
                >
                    <Icon name="add" className="text-3xl" />
                </Link>
            )}
        </>
    );
}
