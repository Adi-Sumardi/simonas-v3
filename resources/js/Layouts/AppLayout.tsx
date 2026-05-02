import { ReactNode, useState } from 'react';
import { usePage, router } from '@inertiajs/react';
import { Sidebar } from '@/Components/Sidebar';
import { Topbar } from '@/Components/Topbar';
import { PWAInstallBanner } from '@/Components/PWAInstallBanner';
import { ReminderManager } from '@/Components/ReminderManager';
import { ToastProvider } from '@/Components/ui/Toast';
import { PageProps } from '@/types';

interface AppLayoutProps {
    children: ReactNode;
    searchPlaceholder?: string;
}

export function AppLayout({ children, searchPlaceholder }: AppLayoutProps) {
    const { auth } = usePage<PageProps>().props;
    const user = auth.user;
    const [drawerOpen, setDrawerOpen] = useState(false);

    const role = user.role ?? 'mahasiswa';
    const profileHref = role === 'mahasiswa' ? '/mahasiswa/profil'
        : role === 'mentor'    ? '/mentor'
        : role === 'super'     ? '/super/role-permission'
        : role === 'alumni'    ? '/alumni/profil'
        : '/dashboard';

    const dropdownItems = [
        { label: 'Profil Saya', icon: 'person',   href: profileHref },
        { label: 'Keluar',      icon: 'logout',    onClick: () => router.post('/logout'), danger: true },
    ];

    return (
        <ToastProvider>
            <ReminderManager />
            <div className="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-white">
                {/* Topbar */}
                <Topbar
                    user={user}
                    searchPlaceholder={searchPlaceholder}
                    dropdownItems={dropdownItems}
                    onMenuClick={() => setDrawerOpen(true)}
                />

                {/* Sidebar (desktop) + Bottom nav (mobile) */}
                <Sidebar 
                    user={user} 
                    drawerOpen={drawerOpen} 
                    onClose={() => setDrawerOpen(false)} 
                    onOpen={() => setDrawerOpen(true)}
                />

                {/* Main content */}
                <main className="lg:ml-64 pt-16 min-h-screen">
                    <div className="w-full max-w-7xl mx-auto px-4 py-6 sm:px-6 md:px-8 pb-24 lg:pb-10">
                        {children}
                    </div>
                </main>

                {/* PWA Install prompt */}
                <PWAInstallBanner />
            </div>
        </ToastProvider>
    );
}
