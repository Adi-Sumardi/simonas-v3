import { Link, usePage, router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { PageProps, User } from '@/types';

type Role = 'super' | 'admin' | 'mentor' | 'mahasiswa' | 'alumni';

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
        forRoles: ['super','admin','mentor','mahasiswa','alumni'],
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
        ],
    },
    {
        section: 'Mentor',
        forRoles: ['mentor'],
        items: [
            { label: 'Beranda',        icon: 'home',            href: '/mentor' },
            { label: 'Warga Bimbingan', icon: 'people',          href: '/mentor/mentees' },
            { label: 'Hafalan Pending', icon: 'pending_actions', href: '/mentor/hafalan/pending', permission: 'nilai-santri' },
            { label: 'Penilaian',       icon: 'rate_review',     href: '/mentor/penilaian' },
            { label: 'Kalender',        icon: 'calendar_month',  href: '/mentor/kalender' },
        ],
    },
    {
        section: '',
        forRoles: ['super'],
        items: [
            { label: 'Role & Permission', icon: 'admin_panel_settings', href: '/super/role-permission' },
            { label: 'Warga',             icon: 'people_alt',           href: '/super/warga' },
            { label: 'Alumni',            icon: 'school',               href: '/super/alumni' },
            { label: 'Kegiatan',          icon: 'event',                href: '/super/kegiatan' },
            { label: 'Hafalan',           icon: 'menu_book',            href: '/super/hafalan' },
            { label: 'Leaderboard',       icon: 'leaderboard',          href: '/super/leaderboard' },
            { label: 'Laporan',           icon: 'bar_chart',            href: '/super/laporan' },
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
            { label: 'Postingan',       icon: 'people',      href: '/alumni/hub' },
            { label: 'Bisnis',          icon: 'storefront',  href: '/alumni/bisnis' },
            { label: 'Lowongan',        icon: 'work',        href: '/alumni/jobs' },
        ],
    },
];

// Mobile bottom nav hrefs per role
const BOTTOM_NAV: Record<string, string[]> = {
    mahasiswa: ['/dashboard', '/mahasiswa/aktivitas', '/mahasiswa/hafalan', '/mahasiswa/leaderboard', '/mahasiswa/profil'],
    mentor:    ['/mentor', '/mentor/mentees', '/mentor/hafalan/pending', '/mentor/penilaian', '/mentor/kalender'],
    super:     ['/dashboard', '/super/role-permission', '/mahasiswa/profil', '/mentor/mentees'],
    admin:     ['/dashboard', '/super/role-permission'],
    alumni:    ['/dashboard', '/alumni/hub', '/alumni/jobs', '/alumni/bisnis', '/alumni/profil'],
};

const FAB_BY_ROLE: Record<string, { label: string; href: string; icon: string } | null> = {
    mahasiswa: { label: 'Log Aktivitas', href: '/mahasiswa/aktivitas/create', icon: 'add_circle' },
    mentor:    null,
    super:     null,
    admin:     null,
    alumni:    null,
};

interface SidebarProps { user: User }

export function Sidebar({ user }: SidebarProps) {
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

    function isActive(href: string) {
        if (href === '/dashboard') return url === '/dashboard' || url === '/dashboard/';
        // Exact-match routes that should not prefix-match sub-paths
        if (href === '/mentor') return url === '/mentor' || url === '/mentor/';
        return url.startsWith(href);
    }

    function handleLogout() { router.post('/logout'); }

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

        return mobile ? (
            <Link
                href={item.href}
                className={`flex flex-col items-center justify-center gap-0.5 transition-all ${
                    active ? 'text-primary-container scale-110' : 'text-secondary/70'
                }`}
            >
                <Icon name={item.icon} className="text-2xl" filled={active} />
                <span className="font-display text-[9px] uppercase tracking-wider leading-tight text-center">{item.label}</span>
            </Link>
        ) : (
            <Link
                href={item.href}
                className={`flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all ${
                    active
                        ? 'bg-primary-container text-on-primary shadow-md shadow-blue-500/20'
                        : 'text-secondary hover:bg-white/40 hover:translate-x-0.5'
                }`}
            >
                <Icon name={item.icon} className="text-xl" filled={active} />
                {item.label}
            </Link>
        );
    }

    return (
        <>
            {/* ── Desktop Sidebar ── */}
            <aside className="hidden lg:flex flex-col h-[calc(100vh-64px)] w-64 fixed left-0 top-16 bg-white/60 backdrop-blur-2xl border-r border-white/30 shadow-[20px_0_30px_rgba(37,99,235,0.05)] p-4 z-40">
                {/* Brand */}
                <div className="mb-4 px-2">
                    <div className="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center text-white shadow-md mb-3">
                        <Icon name="school" className="text-xl" filled />
                    </div>
                    <p className="font-display text-sm font-black text-primary-container">SIMONAS</p>
                    <p className="text-[10px] uppercase tracking-widest text-on-surface-variant font-bold">
                        {userRoles.length > 1
                            ? userRoles.map(r => r.charAt(0).toUpperCase() + r.slice(1)).join(' · ')
                            : userRoles[0] === 'super' ? 'Super Admin' : userRoles[0].charAt(0).toUpperCase() + userRoles[0].slice(1)
                        } Portal
                    </p>
                </div>

                {/* Grouped nav */}
                <nav className="flex-1 overflow-y-auto space-y-0.5 pr-1">
                    {visibleGroups.map((group, gi) => (
                        <div key={gi}>
                            {/* Section header — only shown when user has multiple roles */}
                            {group.showHeader && (
                                <p className={`text-[9px] font-black uppercase tracking-widest px-2 mb-1 ${gi > 0 ? 'mt-4' : 'mt-2'} ${
                                    group.section === 'Mahasiswa' ? 'text-blue-500' :
                                    group.section === 'Mentor'    ? 'text-emerald-600' :
                                    group.section === 'Alumni'    ? 'text-violet-600' :
                                    group.section === 'Admin'     ? 'text-amber-600' :
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
                            href={fab.href}
                            className="w-full py-3.5 bg-primary text-on-primary rounded-xl shadow-lg shadow-blue-500/20 font-bold text-sm flex items-center justify-center gap-2 hover:scale-[1.02] active:scale-95 transition-all"
                        >
                            <Icon name={fab.icon} className="text-xl" filled />
                            {fab.label}
                        </Link>
                    </div>
                )}

                {/* Logout */}
                <button
                    onClick={handleLogout}
                    className="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-error hover:bg-error/10 transition-colors mt-2"
                >
                    <Icon name="logout" className="text-xl" />
                    Keluar
                </button>
            </aside>

            {/* ── Mobile Bottom Nav ── */}
            <nav className="lg:hidden fixed bottom-0 left-0 w-full z-50 flex justify-around items-center px-4 pt-3 pb-safe pb-3 bg-white/80 backdrop-blur-lg border-t border-white/40 shadow-[0_-10px_25px_rgba(0,0,0,0.05)]">
                {bottomNav.map(item => <NavItem key={`${item.href}-mob`} item={item} mobile />)}
            </nav>

            {/* Mobile FAB */}
            {fab && (
                <Link
                    href={fab.href}
                    className="lg:hidden fixed bottom-20 right-5 w-14 h-14 bg-primary text-on-primary rounded-full shadow-[0_12px_24px_rgba(37,99,235,0.3)] flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-40"
                    aria-label={fab.label}
                >
                    <Icon name="add" className="text-3xl" />
                </Link>
            )}
        </>
    );
}
