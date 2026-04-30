import { useState, useEffect } from 'react';
import { Icon } from '@/Components/ui/Icon';

interface BeforeInstallPromptEvent extends Event {
    prompt: () => Promise<void>;
    userChoice: Promise<{ outcome: 'accepted' | 'dismissed' }>;
}

let deferredPrompt: BeforeInstallPromptEvent | null = null;

export function PWAInstallBanner() {
    const [showBanner, setShowBanner] = useState(false);
    const [installed, setInstalled]   = useState(false);

    useEffect(() => {
        // Check if already installed
        if (window.matchMedia('(display-mode: standalone)').matches) {
            setInstalled(true);
            return;
        }

        const handler = (e: Event) => {
            e.preventDefault();
            deferredPrompt = e as BeforeInstallPromptEvent;
            // Show banner after 3s delay (don't be annoying immediately)
            setTimeout(() => setShowBanner(true), 3000);
        };

        window.addEventListener('beforeinstallprompt', handler);
        window.addEventListener('appinstalled', () => {
            setInstalled(true);
            setShowBanner(false);
        });

        return () => window.removeEventListener('beforeinstallprompt', handler);
    }, []);

    async function handleInstall() {
        if (!deferredPrompt) return;
        await deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        if (outcome === 'accepted') setInstalled(true);
        setShowBanner(false);
        deferredPrompt = null;
    }

    if (!showBanner || installed) return null;

    return (
        <div className="fixed bottom-24 lg:bottom-6 left-1/2 -translate-x-1/2 z-[100] w-[calc(100%-2rem)] max-w-sm animate-slide-up">
            <div className="glass-panel p-4 rounded-2xl shadow-2xl border border-primary/10 flex items-center gap-4">
                <div className="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center flex-shrink-0 shadow-md">
                    <Icon name="install_mobile" className="text-2xl text-on-primary" filled />
                </div>
                <div className="flex-1 min-w-0">
                    <p className="font-bold text-on-surface text-sm">Install SIMONAS</p>
                    <p className="text-xs text-on-surface-variant truncate">Akses lebih cepat dari layar utama</p>
                </div>
                <div className="flex gap-2 flex-shrink-0">
                    <button
                        onClick={() => setShowBanner(false)}
                        className="p-2 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors"
                        aria-label="Tutup"
                    >
                        <Icon name="close" className="text-lg" />
                    </button>
                    <button
                        onClick={handleInstall}
                        className="px-4 py-2 bg-primary-container text-on-primary rounded-xl text-sm font-bold shadow-md shadow-blue-500/20 hover:opacity-90 transition-opacity"
                    >
                        Install
                    </button>
                </div>
            </div>
        </div>
    );
}
