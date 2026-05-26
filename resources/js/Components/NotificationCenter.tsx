import { useState, useEffect } from 'react';
import { router, Link } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';

interface Notification {
    id: number;
    title: string;
    message: string;
    type: 'info' | 'success' | 'warning' | 'error';
    link: string | null;
    is_read: boolean;
    created_at: string;
}

export default function NotificationCenter() {
    const [isOpen, setIsOpen] = useState(false);
    const [notifications, setNotifications] = useState<Notification[]>([]);
    const [loading, setLoading] = useState(false);

    const unreadCount = notifications.filter(n => !n.is_read).length;

    const fetchNotifications = async () => {
        setLoading(true);
        try {
            const response = await fetch('/notifications');
            const data = await response.json();
            setNotifications(data);
        } catch (error) {
            console.error('Failed to fetch notifications', error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (isOpen) {
            fetchNotifications();
        }
    }, [isOpen]);

    const markAsRead = (id: number) => {
        router.patch(`/notifications/${id}/read`, {}, {
            preserveScroll: true,
            onSuccess: () => {
                setNotifications(prev => prev.map(n => n.id === id ? { ...n, is_read: true } : n));
            }
        });
    };

    const markAllAsRead = () => {
        router.patch('/notifications/read-all', {}, {
            preserveScroll: true,
            onSuccess: () => {
                setNotifications(prev => prev.map(n => ({ ...n, is_read: true })));
            }
        });
    };

    const deleteNotification = (id: number) => {
        router.delete(`/notifications/${id}`, {
            preserveScroll: true,
            onSuccess: () => {
                setNotifications(prev => prev.filter(n => n.id !== id));
            }
        });
    };

    const getIcon = (type: string) => {
        switch (type) {
            case 'success': return { name: 'check_circle', color: 'text-emerald-500' };
            case 'warning': return { name: 'warning', color: 'text-amber-500' };
            case 'error':   return { name: 'error', color: 'text-rose-500' };
            default:        return { name: 'info', color: 'text-blue-500' };
        }
    };

    return (
        <div className="relative">
            {/* Bell Icon */}
            <button 
                onClick={() => setIsOpen(!isOpen)}
                className="w-10 h-10 rounded-xl hover:bg-surface-container transition-colors flex items-center justify-center relative group"
            >
                <Icon name="notifications" className="text-xl text-on-surface-variant group-hover:text-primary transition-colors" />
                {unreadCount > 0 && (
                    <span className="absolute top-2 right-2 w-4 h-4 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center ring-2 ring-white">
                        {unreadCount > 9 ? '9+' : unreadCount}
                    </span>
                )}
            </button>

            {/* Dropdown */}
            {isOpen && (
                <>
                    <div className="fixed inset-0 z-40" onClick={() => setIsOpen(false)} />
                    <div className="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-zinc-100 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2 duration-200">
                        
                        {/* Header */}
                        <div className="p-5 border-b border-zinc-100 flex items-center justify-between bg-zinc-50/50">
                            <h3 className="font-display font-black text-on-surface">Notifikasi</h3>
                            {unreadCount > 0 && (
                                <button 
                                    onClick={markAllAsRead}
                                    className="text-[10px] font-black text-primary-container uppercase tracking-widest hover:underline"
                                >
                                    Tandai semua dibaca
                                </button>
                            )}
                        </div>

                        {/* List */}
                        <div className="max-h-[400px] overflow-y-auto custom-scrollbar">
                            {loading && notifications.length === 0 ? (
                                <div className="p-10 flex flex-col items-center justify-center gap-3 opacity-40">
                                    <div className="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin" />
                                    <p className="text-xs font-bold">Memuat...</p>
                                </div>
                            ) : notifications.length === 0 ? (
                                <div className="p-12 flex flex-col items-center justify-center text-center gap-4">
                                    <div className="w-16 h-16 rounded-full bg-zinc-50 flex items-center justify-center text-zinc-300">
                                        <Icon name="notifications_off" className="text-3xl" />
                                    </div>
                                    <div>
                                        <p className="font-bold text-on-surface">Belum ada notifikasi</p>
                                        <p className="text-xs text-on-surface-variant">Kabar terbaru akan muncul di sini.</p>
                                    </div>
                                </div>
                            ) : (
                                <div className="divide-y divide-zinc-50">
                                    {notifications.map((n) => {
                                        const icon = getIcon(n.type);
                                        return (
                                            <div 
                                                key={n.id} 
                                                className={`p-4 hover:bg-zinc-50 transition-colors relative group ${!n.is_read ? 'bg-blue-50/30' : ''}`}
                                            >
                                                <div className="flex gap-4">
                                                    <div className={`w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center flex-shrink-0 border border-zinc-100`}>
                                                        <Icon name={icon.name} className={icon.color} filled />
                                                    </div>
                                                    <div className="flex-1 min-w-0">
                                                        <div className="flex justify-between items-start mb-1">
                                                            <p className={`text-sm font-bold truncate ${!n.is_read ? 'text-on-surface' : 'text-on-surface-variant'}`}>
                                                                {n.title}
                                                            </p>
                                                            <span className="text-[10px] text-outline flex-shrink-0 ml-2">
                                                                {new Date(n.created_at).toLocaleDateString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                                            </span>
                                                        </div>
                                                        <p className="text-xs text-on-surface-variant leading-relaxed line-clamp-2">
                                                            {n.message}
                                                        </p>
                                                        {n.link && (
                                                            <Link 
                                                                href={n.link}
                                                                onClick={() => { setIsOpen(false); markAsRead(n.id); }}
                                                                className="text-[10px] font-black text-primary-container uppercase tracking-widest mt-2 block hover:underline"
                                                            >
                                                                Lihat Detail →
                                                            </Link>
                                                        )}
                                                    </div>
                                                </div>

                                                {/* Actions Overlay */}
                                                <div className="absolute top-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    {!n.is_read && (
                                                        <button 
                                                            onClick={() => markAsRead(n.id)}
                                                            className="w-6 h-6 rounded-lg bg-white shadow-sm border border-zinc-100 flex items-center justify-center text-zinc-400 hover:text-primary"
                                                            title="Tandai dibaca"
                                                        >
                                                            <Icon name="done" className="text-sm" />
                                                        </button>
                                                    )}
                                                    <button 
                                                        onClick={() => deleteNotification(n.id)}
                                                        className="w-6 h-6 rounded-lg bg-white shadow-sm border border-zinc-100 flex items-center justify-center text-zinc-400 hover:text-rose-500"
                                                        title="Hapus"
                                                    >
                                                        <Icon name="delete" className="text-sm" />
                                                    </button>
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            )}
                        </div>

                        {/* Footer */}
                        {notifications.length > 0 && (
                            <div className="p-3 bg-zinc-50/50 border-t border-zinc-100 text-center">
                                <p className="text-[9px] font-black text-outline uppercase tracking-widest">Pusat Notifikasi SIMONAS</p>
                            </div>
                        )}
                    </div>
                </>
            )}
        </div>
    );
}
