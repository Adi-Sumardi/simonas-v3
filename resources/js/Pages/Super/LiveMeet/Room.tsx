import { Head, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { LiveKitRoom, VideoConference } from '@livekit/components-react';
import '@livekit/components-styles';
import { PageProps } from '@/types';

interface RoomData {
    id: number;
    title: string;
    room_name: string;
    passcode: string | null;
    status: string;
    waiting_room_enabled: boolean;
}
interface ParticipantItem { id: number; user_id: number; name: string; avatar?: string | null; role: string }
interface WaitingItem { id: number; user_id: number; name: string; avatar?: string | null }

interface Props extends PageProps {
    room: RoomData;
    myRole: 'host' | 'co-host' | 'participant';
    token?: string | null;
    wsUrl?: string | null;
    hasActiveRecording: boolean;
    participants: ParticipantItem[];
    waitingQueue: WaitingItem[];
}

export default function LiveMeetRoom({ room, myRole, token, wsUrl, hasActiveRecording, participants, waitingQueue }: Props) {
    const isHostOrCoHost = myRole === 'host' || myRole === 'co-host';
    const isHost = myRole === 'host';
    const [copied, setCopied] = useState(false);
    const [recording, setRecording] = useState(hasActiveRecording);

    // Poll participant/waiting-room state every 5s for host/co-host (no websockets in this app).
    useEffect(() => {
        if (!isHostOrCoHost) return;
        const interval = setInterval(() => {
            router.reload({ only: ['participants', 'waitingQueue'] });
        }, 5000);
        return () => clearInterval(interval);
    }, [isHostOrCoHost]);

    function copyPasscode() {
        if (!room.passcode) return;
        navigator.clipboard.writeText(room.passcode);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    }

    function post(url: string) {
        router.post(url, {}, { preserveScroll: true, preserveState: true });
    }

    function toggleRecording() {
        if (recording) {
            post(`/super/live-meet/${room.id}/recording/stop`);
            setRecording(false);
        } else {
            post(`/super/live-meet/${room.id}/recording/start`);
            setRecording(true);
        }
    }

    function endMeeting() {
        if (!confirm('Akhiri meeting untuk semua peserta?')) return;
        router.post(`/super/live-meet/${room.id}/end`);
    }

    return (
        <AppLayout>
            <Head title={`Live Meet — ${room.title}`} />

            <PageHeader
                title={room.title}
                subtitle={isHostOrCoHost && room.passcode ? `Passcode: ${room.passcode}` : 'Live Meet'}
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Live Meet', href: '/super/live-meet' }, { label: room.title }]}
                actions={
                    isHost ? (
                        <button onClick={endMeeting} className="bg-rose-600 hover:bg-rose-700 text-white flex items-center gap-2 text-sm px-4 py-2.5 rounded-xl font-bold transition-colors shadow-md shadow-rose-200">
                            <Icon name="call_end" className="text-xl" /> Akhiri untuk Semua
                        </button>
                    ) : undefined
                }
            />

            <div className="grid grid-cols-1 xl:grid-cols-3 gap-6">
                {/* Video panel */}
                <div className="xl:col-span-2 flex flex-col gap-4">
                    <div className="glass-panel rounded-3xl overflow-hidden p-2 bg-black/90 h-[500px] xl:h-[600px] relative flex flex-col shadow-2xl">
                        {recording && (
                            <span className="absolute top-4 left-4 z-50 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-600/90 text-white text-xs font-bold">
                                <span className="w-2 h-2 rounded-full bg-white animate-pulse" /> REC
                            </span>
                        )}
                        {token && wsUrl ? (
                            <LiveKitRoom
                                video audio
                                token={token}
                                serverUrl={wsUrl}
                                data-lk-theme="default"
                                onDisconnected={() => router.visit('/super/live-meet')}
                                className="flex-1 flex flex-col"
                            >
                                <VideoConference />
                            </LiveKitRoom>
                        ) : (
                            <div className="flex-1 flex flex-col items-center justify-center p-6 text-center text-white/80">
                                <Icon name="cloud_off" className="text-5xl text-amber-400 mb-3" />
                                <h4 className="font-bold text-lg text-white mb-1">Server LiveKit tidak tersedia</h4>
                            </div>
                        )}
                    </div>

                    {isHostOrCoHost && (
                        <div className="glass-card rounded-2xl p-4 flex flex-wrap items-center gap-2">
                            {room.passcode && (
                                <button onClick={copyPasscode} className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">
                                    <Icon name={copied ? 'check' : 'content_copy'} className="text-base" />
                                    {copied ? 'Tersalin' : `Passcode: ${room.passcode}`}
                                </button>
                            )}
                            <button onClick={() => post(`/super/live-meet/${room.id}/mute-all`)} className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors">
                                <Icon name="mic_off" className="text-base" /> Mute All
                            </button>
                            <button onClick={() => post(`/super/live-meet/${room.id}/unmute-all`)} className="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                                <Icon name="mic" className="text-base" /> Unmute All
                            </button>
                            <button onClick={toggleRecording} className={`flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-colors ${recording ? 'bg-rose-100 text-rose-700 hover:bg-rose-200' : 'bg-rose-50 text-rose-600 hover:bg-rose-100'}`}>
                                <Icon name={recording ? 'stop_circle' : 'fiber_manual_record'} className="text-base" />
                                {recording ? 'Hentikan Rekam' : 'Mulai Rekam'}
                            </button>
                        </div>
                    )}
                </div>

                {/* Side panel: participants + waiting room */}
                <div className="flex flex-col gap-4">
                    {isHostOrCoHost && room.waiting_room_enabled && (
                        <div className="glass-card rounded-2xl p-5">
                            <h3 className="font-display font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
                                <Icon name="hourglass_top" className="text-amber-500" />
                                Menunggu Izin ({waitingQueue.length})
                            </h3>
                            {waitingQueue.length === 0 ? (
                                <p className="text-xs text-on-surface-variant/60">Tidak ada peserta menunggu.</p>
                            ) : (
                                <div className="space-y-2">
                                    {waitingQueue.map(w => (
                                        <div key={w.id} className="flex items-center justify-between gap-2 p-2 rounded-xl bg-white/40">
                                            <span className="text-sm font-semibold text-on-surface truncate">{w.name}</span>
                                            <div className="flex items-center gap-1.5 flex-shrink-0">
                                                <button onClick={() => post(`/super/live-meet/${room.id}/participants/${w.id}/admit`)} className="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 inline-flex items-center justify-center">
                                                    <Icon name="check" className="text-sm" />
                                                </button>
                                                <button onClick={() => post(`/super/live-meet/${room.id}/participants/${w.id}/deny`)} className="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 inline-flex items-center justify-center">
                                                    <Icon name="close" className="text-sm" />
                                                </button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    )}

                    {isHostOrCoHost && (
                        <div className="glass-card rounded-2xl p-5 flex-1">
                            <h3 className="font-display font-bold text-sm text-on-surface mb-3 flex items-center gap-2">
                                <Icon name="group" className="text-primary-container" />
                                Peserta ({participants.length})
                            </h3>
                            <div className="space-y-2">
                                {participants.map(p => (
                                    <div key={p.id} className="flex items-center justify-between gap-2 p-2 rounded-xl hover:bg-white/40">
                                        <div className="min-w-0">
                                            <p className="text-sm font-semibold text-on-surface truncate">{p.name}</p>
                                            <span className={`text-[10px] font-bold uppercase tracking-wider ${p.role === 'host' ? 'text-violet-600' : p.role === 'co-host' ? 'text-blue-600' : 'text-on-surface-variant'}`}>
                                                {p.role === 'host' ? 'Host' : p.role === 'co-host' ? 'Co-Host' : 'Peserta'}
                                            </span>
                                        </div>
                                        {isHost && p.role !== 'host' && (
                                            <div className="flex items-center gap-1.5 flex-shrink-0">
                                                {p.role === 'co-host' ? (
                                                    <button onClick={() => post(`/super/live-meet/${room.id}/participants/${p.id}/demote`)} title="Cabut Co-Host" className="text-[10px] font-bold px-2 py-1 rounded-lg bg-surface-container text-on-surface-variant hover:bg-white/60">
                                                        Cabut Co-Host
                                                    </button>
                                                ) : (
                                                    <button onClick={() => post(`/super/live-meet/${room.id}/participants/${p.id}/promote`)} title="Jadikan Co-Host" className="text-[10px] font-bold px-2 py-1 rounded-lg bg-primary-container/10 text-primary-container hover:bg-primary-container/20">
                                                        Jadikan Co-Host
                                                    </button>
                                                )}
                                                <button onClick={() => { if (confirm(`Keluarkan ${p.name}?`)) post(`/super/live-meet/${room.id}/participants/${p.id}/kick`); }} className="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 inline-flex items-center justify-center">
                                                    <Icon name="person_remove" className="text-sm" />
                                                </button>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
