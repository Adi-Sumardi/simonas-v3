import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';
import { PageProps } from '@/types';

interface RecordingItem {
    id: number;
    status: string;
    expires_at: string | null;
    days_left: number | null;
    downloaded_at: string | null;
}

interface RoomItem {
    id: number;
    title: string;
    room_name: string;
    status: string;
    host: { id: number; name: string; avatar?: string | null } | null;
    started_at: string | null;
    ended_at: string | null;
    waiting_room_enabled: boolean;
    participants_count: number;
    recordings: RecordingItem[];
}

interface Props extends PageProps {
    active: RoomItem[];
    riwayat: RoomItem[];
}

const RECORDING_STATUS_META: Record<string, { label: string; color: string }> = {
    recording:  { label: 'Merekam...', color: 'bg-rose-50 text-rose-600' },
    processing: { label: 'Memproses...', color: 'bg-amber-50 text-amber-600' },
    ready:      { label: 'Siap diunduh', color: 'bg-emerald-50 text-emerald-600' },
    failed:     { label: 'Gagal', color: 'bg-zinc-100 text-zinc-500' },
};

export default function LiveMeetIndex({ active, riwayat }: Props) {
    const [showCreate, setShowCreate] = useState(false);
    const createForm = useForm({ title: '', waiting_room_enabled: false as boolean });

    function submitCreate(e: React.FormEvent) {
        e.preventDefault();
        createForm.post('/super/live-meet', {
            onSuccess: () => { createForm.reset(); setShowCreate(false); },
        });
    }

    function deleteRecording(id: number) {
        if (!confirm('Hapus rekaman ini sekarang?')) return;
        router.delete(`/super/live-meet/recordings/${id}`, { preserveScroll: true });
    }

    return (
        <AppLayout searchPlaceholder="Cari meeting...">
            <Head title="Live Meet" />

            <PageHeader
                title="Live Meet"
                subtitle="Video conference internal — host/co-host, passcode, recording, dan riwayat 7 hari."
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Live Meet' }]}
                actions={
                    <button onClick={() => setShowCreate(true)} className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="video_call" className="text-xl" /> Mulai Meeting Baru
                    </button>
                }
            />

            {/* Active meetings */}
            <div className="mb-8">
                <h3 className="font-bold text-on-surface text-sm uppercase tracking-wide mb-3">Meeting Aktif</h3>
                {active.length === 0 ? (
                    <div className="glass-card rounded-2xl py-10 flex flex-col items-center gap-2 text-on-surface-variant">
                        <Icon name="videocam_off" className="text-3xl opacity-30" />
                        <p className="text-sm">Tidak ada meeting yang sedang berlangsung.</p>
                    </div>
                ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {active.map(room => (
                            <Link key={room.id} href={`/super/live-meet/${room.id}`} className="glass-card rounded-2xl p-5 hover:shadow-md transition-all block">
                                <div className="flex items-center justify-between mb-2">
                                    <span className="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-rose-600">
                                        <span className="w-2 h-2 rounded-full bg-rose-500 animate-pulse" /> Live
                                    </span>
                                    {room.waiting_room_enabled && (
                                        <span className="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-600">Waiting Room</span>
                                    )}
                                </div>
                                <p className="font-bold text-on-surface">{room.title}</p>
                                <p className="text-xs text-on-surface-variant mt-1">Host: {room.host?.name ?? '-'} · {room.participants_count} peserta</p>
                            </Link>
                        ))}
                    </div>
                )}
            </div>

            {/* History */}
            <div>
                <h3 className="font-bold text-on-surface text-sm uppercase tracking-wide mb-3">Riwayat 7 Hari Terakhir</h3>
                {riwayat.length === 0 ? (
                    <div className="glass-card rounded-2xl py-10 flex flex-col items-center gap-2 text-on-surface-variant">
                        <Icon name="history" className="text-3xl opacity-30" />
                        <p className="text-sm">Belum ada riwayat meeting.</p>
                    </div>
                ) : (
                    <div className="space-y-3">
                        {riwayat.map(room => (
                            <div key={room.id} className="glass-card rounded-2xl p-5">
                                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <p className="font-bold text-on-surface">{room.title}</p>
                                        <p className="text-xs text-on-surface-variant mt-1">
                                            Host: {room.host?.name ?? '-'} · {room.started_at} &rarr; {room.ended_at}
                                        </p>
                                    </div>
                                </div>
                                {room.recordings.length > 0 && (
                                    <div className="mt-3 pt-3 border-t border-white/30 space-y-2">
                                        {room.recordings.map(rec => {
                                            const meta = RECORDING_STATUS_META[rec.status] ?? RECORDING_STATUS_META.failed;
                                            return (
                                                <div key={rec.id} className="flex flex-wrap items-center justify-between gap-2">
                                                    <div className="flex items-center gap-2">
                                                        <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${meta.color}`}>{meta.label}</span>
                                                        {rec.status === 'ready' && rec.days_left !== null && (
                                                            <span className="text-[11px] text-on-surface-variant">
                                                                Terhapus otomatis dalam {rec.days_left} hari
                                                            </span>
                                                        )}
                                                        {rec.downloaded_at && (
                                                            <span className="text-[11px] text-emerald-600">Sudah diunduh</span>
                                                        )}
                                                    </div>
                                                    {rec.status === 'ready' && (
                                                        <div className="flex items-center gap-1.5">
                                                            <a
                                                                href={`/super/live-meet/recordings/${rec.id}/download`}
                                                                className="text-xs font-bold px-3 py-1.5 rounded-lg bg-primary-container/10 text-primary-container hover:bg-primary-container/20 transition-colors"
                                                            >
                                                                Download
                                                            </a>
                                                            <button
                                                                onClick={() => deleteRecording(rec.id)}
                                                                className="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 inline-flex items-center justify-center transition-colors"
                                                            >
                                                                <Icon name="delete" className="text-sm" />
                                                            </button>
                                                        </div>
                                                    )}
                                                </div>
                                            );
                                        })}
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>

            <Modal open={showCreate} onClose={() => setShowCreate(false)} title="Mulai Meeting Baru" icon="video_call">
                <form onSubmit={submitCreate} className="space-y-4">
                    <div>
                        <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Judul Meeting</label>
                        <input
                            type="text"
                            value={createForm.data.title}
                            onChange={e => createForm.setData('title', e.target.value)}
                            placeholder="Contoh: Rapat Koordinasi Pengurus"
                            className="glass-input w-full text-sm py-2"
                            autoFocus
                        />
                        {createForm.errors.title && <p className="text-xs text-rose-500 mt-1">{createForm.errors.title}</p>}
                    </div>
                    <label className="flex items-center gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            checked={createForm.data.waiting_room_enabled}
                            onChange={e => createForm.setData('waiting_room_enabled', e.target.checked)}
                            className="w-4 h-4"
                        />
                        <span className="text-sm text-on-surface">Aktifkan Waiting Room (peserta perlu diizinkan host masuk)</span>
                    </label>
                    <div className="flex gap-3 pt-2">
                        <button type="submit" disabled={createForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {createForm.processing ? 'Memulai...' : 'Mulai Meeting'}
                        </button>
                        <button type="button" onClick={() => setShowCreate(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </form>
            </Modal>
        </AppLayout>
    );
}
