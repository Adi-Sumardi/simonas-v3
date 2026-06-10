import { Head, router } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { LiveKitRoom, VideoConference } from '@livekit/components-react';
import '@livekit/components-styles';
import { PageProps } from '@/types';
import { useState, useEffect, useRef } from 'react';

// Force LiveKit ControlBar to use the 'verbose' mode (always showing text labels)
if (typeof window !== 'undefined') {
    const originalMatchMedia = window.matchMedia;
    window.matchMedia = function(query) {
        if (query.includes('max-width: 760px') || query.includes('max-width: 1000px') || query.includes('max-width: 800px')) {
            return {
                matches: false,
                media: query,
                onchange: null,
                addListener: () => {},
                removeListener: () => {},
                addEventListener: () => {},
                removeEventListener: () => {},
                dispatchEvent: () => false,
            } as MediaQueryList;
        }
        return originalMatchMedia ? originalMatchMedia(query) : {
            matches: false,
            media: query,
            onchange: null,
            addListener: () => {},
            removeListener: () => {},
            addEventListener: () => {},
            removeEventListener: () => {},
            dispatchEvent: () => false,
        } as MediaQueryList;
    };
}

interface HafalanLiveMeetProps extends PageProps {
    token: string;
    wsUrl: string;
    roomName: string;
    mentorName: string;
}

export default function HafalanLiveMeet({ token, wsUrl, roomName, mentorName }: HafalanLiveMeetProps) {
    const videoContainerRef = useRef<HTMLDivElement>(null);
    const [isFullscreen, setIsFullscreen] = useState(false);

    function handleLeaveMeet() {
        if (confirm('Keluar dari pertemuan Live Meet ini?')) {
            router.visit('/mahasiswa/hafalan');
        }
    }

    function toggleFullscreen() {
        if (!videoContainerRef.current) return;
        if (!document.fullscreenElement) {
            videoContainerRef.current.requestFullscreen().then(() => {
                setIsFullscreen(true);
            }).catch(err => {
                console.error("Gagal fullscreen:", err);
            });
        } else {
            document.exitFullscreen().then(() => {
                setIsFullscreen(false);
            });
        }
    }

    useEffect(() => {
        function handleFullscreenChange() {
            setIsFullscreen(!!document.fullscreenElement);
        }
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        return () => document.removeEventListener('fullscreenchange', handleFullscreenChange);
    }, []);

    return (
        <AppLayout searchPlaceholder="Cari di Live Meet...">
            <Head title="Live Meet Setoran Hafalan" />

            <PageHeader
                title="Live Meet Setoran"
                subtitle={`Menghubungkan Anda dengan Mentor: ${mentorName} (Ruang: ${roomName})`}
                breadcrumbs={[
                    { label: 'Beranda', href: '/dashboard' },
                    { label: 'Hafalan', href: '/mahasiswa/hafalan' },
                    { label: 'Live Meet' },
                ]}
                actions={
                    <button
                        onClick={handleLeaveMeet}
                        className="bg-slate-600 hover:bg-slate-700 text-white flex items-center gap-2 text-sm px-4 py-2.5 rounded-xl font-bold transition-colors shadow-md"
                    >
                        <Icon name="logout" className="text-xl" />
                        Keluar Pertemuan
                    </button>
                }
            />

            <div className="max-w-4xl mx-auto w-full flex flex-col gap-4">
                <div 
                    ref={videoContainerRef}
                    className="glass-panel rounded-3xl overflow-hidden p-2 bg-black/90 h-[500px] lg:h-[calc(100vh-250px)] relative flex flex-col shadow-2xl"
                >
                    {/* Custom Fullscreen Button */}
                    <button
                        type="button"
                        onClick={toggleFullscreen}
                        className="absolute top-4 right-4 z-50 w-9 h-9 rounded-xl bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition-all border border-white/10 shadow-lg hover:scale-105"
                        title={isFullscreen ? "Keluar Fullscreen" : "Fullscreen Layar Video"}
                    >
                        <Icon name={isFullscreen ? "fullscreen_exit" : "fullscreen"} className="text-lg" />
                    </button>

                    {token ? (
                        <LiveKitRoom
                            video={true}
                            audio={true}
                            token={token}
                            serverUrl={wsUrl}
                            data-lk-theme="default"
                            onDisconnected={() => router.visit('/mahasiswa/hafalan')}
                            className="flex-1 flex flex-col"
                        >
                            <VideoConference />
                        </LiveKitRoom>
                    ) : (
                        <div className="flex-1 flex flex-col items-center justify-center text-white/60">
                            <Icon name="videocam_off" className="text-5xl mb-2" />
                            <p className="text-sm">Menghubungkan ke Live Meet mentor...</p>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
