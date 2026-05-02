import { useState, useRef, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { User } from '@/types';

interface DropdownItem {
    label: string;
    href?: string;
    onClick?: () => void;
    icon?: string;
    danger?: boolean;
}

interface TopbarProps {
    user: User;
    searchPlaceholder?: string;
    dropdownItems?: DropdownItem[];
    notificationCount?: number;
    onMenuClick?: () => void;
}

export function Topbar({
    user,
    searchPlaceholder = 'Search...',
    dropdownItems = [],
    notificationCount = 0,
    onMenuClick,
}: TopbarProps) {
    const [profileOpen, setProfileOpen] = useState(false);
    const [notificationsOpen, setNotificationsOpen] = useState(false);

    const dropdownRef = useRef<HTMLDivElement>(null);
    const notifRef = useRef<HTMLDivElement>(null);

    // Close on outside click
    useEffect(() => {
        function handleClick(e: MouseEvent) {
            if (dropdownRef.current && !dropdownRef.current.contains(e.target as Node)) {
                setProfileOpen(false);
            }
            if (notifRef.current && !notifRef.current.contains(e.target as Node)) {
                setNotificationsOpen(false);
            }
        }
        document.addEventListener('mousedown', handleClick);
        return () => document.removeEventListener('mousedown', handleClick);
    }, []);

    const initials = user.name
        .split(' ')
        .map((n) => n[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();

    return (
        <header className="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-md border-b border-white/40 shadow-lg shadow-blue-500/5 px-6 py-3 flex justify-between items-center">
            {/* Brand */}
            <div className="flex items-center gap-3">
                <button 
                    onClick={onMenuClick}
                    className="p-2 rounded-xl bg-primary/5 text-primary hover:bg-primary/10 transition-all lg:hidden"
                >
                    <Icon name="menu" className="text-xl" />
                </button>
                <div className="w-9 h-9 bg-primary-container rounded-xl flex items-center justify-center text-white shadow-md lg:hidden">
                    <Icon name="school" className="text-xl" filled />
                </div>
                <span className="font-display text-xl font-bold tracking-tight text-primary-container lg:hidden">
                    SIMONAS
                </span>
            </div>

            {/* Search */}
            <div className="hidden md:flex items-center bg-surface-container-low rounded-full px-4 py-2 gap-2 border border-outline-variant/30 flex-1 max-w-md mx-8">
                <Icon name="search" className="text-xl text-on-surface-variant" />
                <input
                    type="text"
                    placeholder={searchPlaceholder}
                    className="bg-transparent border-none focus:ring-0 outline-none text-body-sm w-full text-on-surface placeholder:text-on-surface-variant"
                />
            </div>

            {/* Actions */}
            <div className="flex items-center gap-3">
                {/* Notification */}
                <div className="relative" ref={notifRef}>
                    <button 
                        onClick={() => { setNotificationsOpen(!notificationsOpen); setProfileOpen(false); }}
                        className={`relative p-2 rounded-full transition-colors ${notificationsOpen ? 'bg-primary/10 text-primary' : 'text-secondary hover:bg-primary-fixed/50'}`}
                    >
                        <Icon name="notifications" className="text-xl" />
                        {notificationCount > 0 && (
                            <span className="absolute top-1.5 right-1.5 w-4 h-4 bg-error text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                                {notificationCount}
                            </span>
                        )}
                    </button>

                    {notificationsOpen && (
                        <div className="absolute right-0 mt-2 w-72 bg-white/95 backdrop-blur-2xl border border-white/50 shadow-2xl rounded-2xl py-0 z-50 animate-in fade-in slide-in-from-top-2 duration-150 overflow-hidden">
                            <div className="px-4 py-3 border-b border-outline-variant/30 bg-primary/5 flex items-center justify-between">
                                <span className="text-sm font-bold text-on-surface">Notifikasi</span>
                                <span className="text-[10px] bg-primary text-white px-2 py-0.5 rounded-full">{notificationCount || 2} Baru</span>
                            </div>
                            <div className="max-h-64 overflow-y-auto bg-white/80">
                                <div className="divide-y divide-outline-variant/20">
                                    <div className="px-4 py-3 hover:bg-black/5 transition-colors cursor-pointer">
                                        <p className="text-xs font-bold text-on-surface">Waktunya Sholat Dzuhur</p>
                                        <p className="text-[10px] text-on-surface-variant mt-0.5 leading-relaxed">Jangan lupa ceklist sholat tepat waktu agar poinmu tetap maksimal!</p>
                                        <p className="text-[9px] text-primary mt-1 font-bold">2 menit yang lalu</p>
                                    </div>
                                    <div className="px-4 py-3 hover:bg-black/5 transition-colors cursor-pointer">
                                        <p className="text-xs font-bold text-on-surface">Target Log Aktivitas</p>
                                        <p className="text-[10px] text-on-surface-variant mt-0.5 leading-relaxed">Kamu sudah mencapai 15/23 log bulan ini. Terus semangat!</p>
                                        <p className="text-[9px] text-primary mt-1 font-bold">1 jam yang lalu</p>
                                    </div>
                                </div>
                            </div>
                            <div className="px-4 py-2 bg-surface-container-low text-center border-t border-outline-variant/30">
                                <button className="text-[10px] font-bold text-primary hover:underline uppercase tracking-widest">Lihat Semua Notifikasi</button>
                            </div>
                        </div>
                    )}
                </div>


                {/* Profile Dropdown */}
                <div className="relative" ref={dropdownRef}>
                    <button
                        onClick={() => setProfileOpen((v) => !v)}
                        className="w-10 h-10 rounded-full border-2 border-primary/20 overflow-hidden bg-primary-fixed flex items-center justify-center text-primary font-bold text-sm"
                        aria-label="Open profile menu"
                    >
                        {user.avatar ? (
                            <img src={user.avatar} alt={user.name} className="w-full h-full object-cover" />
                        ) : (
                            <span>{initials}</span>
                        )}
                    </button>

                    {profileOpen && (
                        <div className="absolute right-0 mt-2 w-56 bg-white/95 backdrop-blur-2xl border border-white/50 shadow-2xl rounded-2xl py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
                            <div className="px-4 py-2 border-b border-outline-variant/30 mb-1">
                                <p className="text-body-sm font-semibold text-on-surface">{user.name}</p>
                                <p className="text-label-caps text-on-surface-variant uppercase">{user.role}</p>
                            </div>
                            {dropdownItems.map((item, i) => (
                                item.href ? (
                                    <Link
                                        key={i}
                                        href={item.href}
                                        className={`flex items-center gap-3 px-4 py-2 text-body-sm transition-colors hover:bg-white/40 ${item.danger ? 'text-error' : 'text-on-surface'}`}
                                        onClick={() => setProfileOpen(false)}
                                    >
                                        {item.icon && <Icon name={item.icon} className="text-base" />}
                                        {item.label}
                                    </Link>
                                ) : (
                                    <button
                                        key={i}
                                        onClick={() => { item.onClick?.(); setProfileOpen(false); }}
                                        className={`flex w-full items-center gap-3 px-4 py-2 text-body-sm transition-colors hover:bg-white/40 text-left ${item.danger ? 'text-error' : 'text-on-surface'}`}
                                    >
                                        {item.icon && <Icon name={item.icon} className="text-base" />}
                                        {item.label}
                                    </button>
                                )
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </header>
    );
}
