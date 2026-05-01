import { ReactNode } from 'react';
import { usePage, router } from '@inertiajs/react';
import { Sidebar } from '@/Components/Sidebar';
import { Topbar } from '@/Components/Topbar';
import { PWAInstallBanner } from '@/Components/PWAInstallBanner';
import { PageProps } from '@/types';

interface AppLayoutProps {
    children: ReactNode;
    searchPlaceholder?: string;
}

export function AppLayout({ children, searchPlaceholder }: AppLayoutProps) {
    const { auth } = usePage<PageProps>().props;
    const user = auth.user;

    const role = user.role ?? 'mahasiswa';
    const profileHref = role === 'mahasiswa' ? '/mahasiswa/profil'
        : role === 'mentor'    ? '/mentor'
        : role === 'super'     ? '/super/role-permission'
        : '/dashboard';

    const dropdownItems = [
        { label: 'Profil Saya', icon: 'person',   href: profileHref },
        { label: 'Keluar',      icon: 'logout',    onClick: () => router.post('/logout'), danger: true },
    ];

    return (
        <div className="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-white">
            {/* Topbar */}
            <Topbar
                user={user}
                searchPlaceholder={searchPlaceholder}
                dropdownItems={dropdownItems}
            />

            {/* Sidebar (desktop) + Bottom nav (mobile) */}
            <Sidebar user={user} />

            {/* Main content */}
            {/*
                lg:ml-64  → push right of desktop sidebar (w-64)
                pt-16     → below topbar (h-16)
                pb-24     → above mobile bottom nav on small screens
                lg:pb-8   → normal padding on desktop
            */}
            <main className="lg:ml-64 pt-16 min-h-screen">
                <div className="w-full max-w-7xl mx-auto px-4 py-6 sm:px-6 md:px-8 pb-24 lg:pb-10">
                    {children}
                </div>
            </main>

            {/* PWA Install prompt */}
            <PWAInstallBanner />
        </div>
    );
}
