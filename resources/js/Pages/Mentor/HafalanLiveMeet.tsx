import { Head, router, useForm } from '@inertiajs/react';
import { useState, useEffect, useRef } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { LiveKitRoom, VideoConference } from '@livekit/components-react';
import '@livekit/components-styles';
import { PageProps, HafalanLog } from '@/types';

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

interface PendingLog extends HafalanLog {
    mahasiswa_name: string;
    mahasiswa_nim: string;
    mahasiswa_avatar?: string;
    submitted_at: string;
}

interface HafalanLiveMeetProps extends PageProps {
    token: string;
    wsUrl: string;
    roomName: string;
    pending_logs: PendingLog[];
}

export default function HafalanLiveMeet({ token, wsUrl, roomName, pending_logs }: HafalanLiveMeetProps) {
    const videoContainerRef = useRef<HTMLDivElement>(null);
    const [isFullscreen, setIsFullscreen] = useState(false);
    const [selectedLog, setSelectedLog] = useState<PendingLog | null>(null);

    const { data, setData, patch, processing, reset } = useForm({
        score: 'memtas',
        mentor_notes: '',
    });

    const scoreOptions = [
        {
            value: 'memtas',
            label: 'Memtas',
            desc: 'Hafalan lancar dan baik',
            icon: 'check_circle',
            cls: 'text-emerald-700 bg-emerald-50 border-emerald-300',
        },
        {
            value: 'layak_ulang',
            label: 'Layak Ulang',
            desc: 'Perlu diulang, tapi sudah lumayan',
            icon: 'refresh',
            cls: 'text-blue-700 bg-blue-50 border-blue-300',
        },
        {
            value: 'perlu_perbaikan',
            label: 'Perlu Perbaikan',
            desc: 'Banyak kesalahan, perlu latihan lagi',
            icon: 'warning',
            cls: 'text-rose-700 bg-rose-50 border-rose-300',
        },
    ];

    function handleSelectLog(log: PendingLog) {
        setSelectedLog(log);
        reset();
    }

    function handleSaveScore(e: React.FormEvent) {
        e.preventDefault();
        if (!selectedLog) return;

        patch(`/mentor/hafalan/log/${selectedLog.id}`, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                setSelectedLog(null);
                reset();
            },
        });
    }

    function handleStopMeet() {
        if (confirm('Apakah Anda yakin ingin mengakhiri sesi Live Meet ini? Semua mahasiswa yang terhubung akan diputuskan.')) {
            router.post('/mentor/live-meet/stop');
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
                subtitle={`Ruang Video Call: ${roomName}`}
                breadcrumbs={[
                    { label: 'Beranda', href: '/dashboard' },
                    { label: 'Hafalan Pending', href: '/mentor/hafalan/pending' },
                    { label: 'Live Meet' },
                ]}
                actions={
                    <button
                        onClick={handleStopMeet}
                        className="bg-rose-600 hover:bg-rose-700 text-white flex items-center gap-2 text-sm px-4 py-2.5 rounded-xl font-bold transition-colors shadow-md shadow-rose-200"
                    >
                        <Icon name="call_end" className="text-xl" />
                        Akhiri Pertemuan
                    </button>
                }
            />

            <div className="grid grid-cols-1 xl:grid-cols-3 gap-6">
                {/* ── VIDEO CONFERENCE PANEL (Left - 2 Cols) ── */}
                <div className="xl:col-span-2 flex flex-col gap-4">
                    <div 
                        ref={videoContainerRef}
                        className="glass-panel rounded-3xl overflow-hidden p-2 bg-black/90 h-[500px] xl:h-[600px] relative flex flex-col shadow-2xl"
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
                                onDisconnected={() => router.visit('/mentor/hafalan/pending')}
                                className="flex-1 flex flex-col"
                            >
                                <VideoConference />
                            </LiveKitRoom>
                        ) : (
                            <div className="flex-1 flex flex-col items-center justify-center text-white/60">
                                <Icon name="videocam_off" className="text-5xl mb-2" />
                                <p className="text-sm">Menghubungkan ke server LiveKit...</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* ── QUEUE & SCORING PANEL (Right - 1 Col) ── */}
                <div className="flex flex-col gap-4">
                    {/* Live Student Queue */}
                    <div className="glass-card rounded-2xl p-5 flex flex-col max-h-[350px]">
                        <h3 className="font-display font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
                            <Icon name="queue" className="text-primary-container" />
                            Antrian Setoran Mentees ({pending_logs.length})
                        </h3>
                        
                        <div className="flex-1 overflow-y-auto space-y-2 pr-1">
                            {pending_logs.length === 0 ? (
                                <div className="text-center py-8 text-xs text-on-surface-variant/60">
                                    <Icon name="hourglass_empty" className="text-2xl mb-1 opacity-45" />
                                    <p>Belum ada mahasiswa yang mengajukan setoran pending.</p>
                                </div>
                            ) : (
                                pending_logs.map((log) => {
                                    const isSelected = selectedLog?.id === log.id;
                                    const initials = (log.mahasiswa_name || '').split(' ').filter(Boolean).map(n => n[0]).slice(0, 2).join('') || 'M';
                                    return (
                                        <button
                                            key={log.id}
                                            onClick={() => handleSelectLog(log)}
                                            className={`w-full flex items-center justify-between p-3 rounded-xl border text-left transition-all ${
                                                isSelected
                                                    ? 'bg-primary-container text-white border-primary-container shadow-sm'
                                                    : 'bg-white/40 border-surface-container hover:bg-white/70 text-on-surface-variant'
                                            }`}
                                        >
                                            <div className="flex items-center gap-2.5 min-w-0">
                                                {log.mahasiswa_avatar ? (
                                                    <img src={log.mahasiswa_avatar} alt="" className="w-8 h-8 rounded-full object-cover border border-white" />
                                                ) : (
                                                    <div className={`w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs ${
                                                        isSelected ? 'bg-white text-primary-container' : 'bg-primary-fixed text-primary-container'
                                                    }`}>
                                                        {initials}
                                                    </div>
                                                )}
                                                <div className="min-w-0">
                                                    <p className={`font-semibold text-xs truncate ${isSelected ? 'text-white' : 'text-on-surface'}`}>{log.mahasiswa_name}</p>
                                                    <p className="text-[10px] opacity-75">
                                                        {log.surah} · Ayat {log.ayat_start}-{log.ayat_end}
                                                        {log.halaman_start ? ` (Hal. ${log.halaman_start}${log.halaman_end && log.halaman_end !== log.halaman_start ? `–${log.halaman_end}` : ''})` : ''}
                                                    </p>
                                                </div>
                                            </div>
                                            <Icon name="chevron_right" className={`text-sm ${isSelected ? 'text-white' : 'text-outline'}`} />
                                        </button>
                                    );
                                })
                            )}
                        </div>
                    </div>

                    {/* Real-time Grading Form */}
                    <div className="glass-card rounded-2xl p-5 flex-1 flex flex-col">
                        <h3 className="font-display font-bold text-sm text-on-surface mb-3 flex items-center gap-2 border-b border-white/20 pb-2">
                            <Icon name="rate_review" className="text-emerald-500" />
                            Penilaian Real-time
                        </h3>

                        {selectedLog ? (
                            <form onSubmit={handleSaveScore} className="space-y-4 flex-1 flex flex-col justify-between">
                                <div className="space-y-3">
                                    {/* Student Header */}
                                    <div className="bg-surface-container/30 rounded-xl p-3 text-xs">
                                        <p className="font-bold text-on-surface">{selectedLog.mahasiswa_name}</p>
                                        <p className="text-on-surface-variant text-[10px] mt-0.5">
                                            Surah: <strong>{selectedLog.surah}</strong> (Ayat {selectedLog.ayat_start}-{selectedLog.ayat_end})
                                            {selectedLog.halaman_start && (
                                                <span> · Halaman: <strong>{selectedLog.halaman_start}{selectedLog.halaman_end && selectedLog.halaman_end !== selectedLog.halaman_start ? `–${selectedLog.halaman_end}` : ''}</strong></span>
                                            )}
                                        </p>
                                        {selectedLog.notes && (
                                            <p className="mt-1.5 italic text-[11px] text-on-surface-variant border-l-2 border-primary-container/30 pl-2">
                                                "{selectedLog.notes}"
                                            </p>
                                        )}
                                    </div>

                                    {/* Score Buttons */}
                                    <div className="space-y-2">
                                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block">Hasil Setoran</label>
                                        <div className="grid grid-cols-1 gap-1.5">
                                            {scoreOptions.map((opt) => (
                                                <button
                                                    key={opt.value}
                                                    type="button"
                                                    onClick={() => setData('score', opt.value)}
                                                    className={`flex items-center gap-2.5 p-2.5 rounded-lg border text-left transition-all ${
                                                        data.score === opt.value
                                                            ? opt.cls + ' font-bold shadow-sm'
                                                            : 'bg-white/40 border-surface-container text-on-surface-variant hover:bg-white/80'
                                                    }`}
                                                >
                                                    <Icon name={opt.icon} className="text-lg flex-shrink-0" filled={data.score === opt.value} />
                                                    <div className="min-w-0">
                                                        <p className="text-xs leading-none">{opt.label}</p>
                                                        <p className="text-[9px] opacity-75 truncate mt-0.5">{opt.desc}</p>
                                                    </div>
                                                </button>
                                            ))}
                                        </div>
                                    </div>

                                    {/* Notes */}
                                    <div>
                                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1">Catatan Mentor</label>
                                        <textarea
                                            value={data.mentor_notes}
                                            onChange={e => setData('mentor_notes', e.target.value)}
                                            rows={3}
                                            placeholder="Catatan tajwid, makhorijul huruf, dll..."
                                            className="glass-input w-full text-xs resize-none"
                                        />
                                    </div>
                                </div>

                                <div className="flex gap-2 pt-2">
                                    <button
                                        type="button"
                                        onClick={() => setSelectedLog(null)}
                                        className="flex-1 py-2 rounded-xl font-bold text-xs bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors"
                                    >
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="flex-1 py-2 rounded-xl font-bold text-xs bg-primary-container text-white hover:opacity-90 transition-opacity disabled:opacity-50"
                                    >
                                        {processing ? 'Menyimpan...' : 'Simpan Nilai'}
                                    </button>
                                </div>
                            </form>
                        ) : (
                            <div className="flex-1 flex flex-col items-center justify-center text-center text-xs text-on-surface-variant/50 py-12">
                                <Icon name="rate_review" className="text-4xl mb-2 opacity-35" />
                                <p className="font-bold">Form Penilaian Kosong</p>
                                <p className="mt-1 px-4">Pilih salah satu mahasiswa dari daftar antrian di atas untuk melakukan penilaian langsung.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
