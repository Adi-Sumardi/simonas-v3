import { useRegisterSW } from 'virtual:pwa-register/react';
import { Icon } from '@/Components/ui/Icon';

/**
 * Muncul saat service worker mendeteksi versi baru sudah ter-deploy
 * (build assets berubah). Klik "Muat Ulang" langsung aktifkan versi baru.
 */
export function UpdateBanner() {
    const {
        needRefresh: [needRefresh, setNeedRefresh],
        updateServiceWorker,
    } = useRegisterSW();

    if (!needRefresh) return null;

    return (
        <div className="fixed top-4 left-1/2 -translate-x-1/2 z-[110] w-[calc(100%-2rem)] max-w-sm animate-slide-up">
            <div className="glass-panel p-4 rounded-2xl shadow-2xl border border-primary/10 flex items-center gap-4">
                <div className="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 shadow-md">
                    <Icon name="system_update" className="text-2xl text-emerald-600" filled />
                </div>
                <div className="flex-1 min-w-0">
                    <p className="font-bold text-on-surface text-sm">Update tersedia</p>
                    <p className="text-xs text-on-surface-variant truncate">Ada versi baru SIMONAS, muat ulang untuk memperbarui</p>
                </div>
                <div className="flex gap-2 flex-shrink-0">
                    <button
                        onClick={() => setNeedRefresh(false)}
                        className="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors"
                        aria-label="Nanti saja"
                    >
                        <Icon name="close" className="text-lg" />
                    </button>
                    <button
                        onClick={() => updateServiceWorker(true)}
                        className="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-md shadow-emerald-600/20 hover:opacity-90 transition-opacity"
                    >
                        Muat Ulang
                    </button>
                </div>
            </div>
        </div>
    );
}
