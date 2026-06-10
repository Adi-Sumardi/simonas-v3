import { Head, useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { PageProps, HafalanLog } from '@/types';

interface PendingLog extends HafalanLog {
    mahasiswa_name: string;
    mahasiswa_nim: string;
    mahasiswa_avatar?: string;
    submitted_at: string;
}

interface HafalanPendingProps extends PageProps {
    pending_logs: PendingLog[];
}

// ─── Scoring Modal ─────────────────────────────────────────────────────────────
function ScoringModal({ log, onClose }: { log: PendingLog; onClose: () => void }) {
    const { data, setData, patch, processing } = useForm({
        score: 'memtas' as string,
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
            cls: 'text-amber-700 bg-amber-50 border-amber-300',
        },
        {
            value: 'perlu_perbaikan',
            label: 'Perlu Perbaikan',
            desc: 'Banyak kesalahan, perlu latihan lagi',
            icon: 'warning',
            cls: 'text-rose-700 bg-rose-50 border-rose-300',
        },
    ];

    const initials = log.mahasiswa_name.split(' ').map(n => n[0]).slice(0, 2).join('');

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div className="glass-card rounded-2xl w-full max-w-lg p-6 shadow-2xl animate-slide-up">
                {/* Header */}
                <div className="flex items-center justify-between mb-5">
                    <div>
                        <h3 className="font-display text-lg font-bold text-on-surface">Nilai Hafalan</h3>
                        <p className="text-xs text-on-surface-variant mt-0.5">Setoran dari {log.mahasiswa_name}</p>
                    </div>
                    <button onClick={onClose} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center transition-colors">
                        <Icon name="close" className="text-on-surface-variant" />
                    </button>
                </div>

                {/* Submission info */}
                <div className="bg-surface-container/40 rounded-xl p-4 mb-5">
                    <div className="flex items-center gap-3 mb-3">
                        {log.mahasiswa_avatar ? (
                            <img src={log.mahasiswa_avatar} alt={log.mahasiswa_name} className="w-10 h-10 rounded-full object-cover border border-white" />
                        ) : (
                            <div className="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container text-sm">
                                {initials}
                            </div>
                        )}
                        <div>
                            <p className="font-bold text-on-surface text-sm">{log.mahasiswa_name}</p>
                            <p className="text-[10px] text-on-surface-variant">{log.mahasiswa_nim}</p>
                        </div>
                    </div>
                    <div className="flex flex-wrap gap-3 text-xs text-on-surface-variant">
                        <span className="flex items-center gap-1">
                            <Icon name="auto_stories" className="text-emerald-600 text-sm" filled />
                            {log.surah}
                        </span>
                        <span className="flex items-center gap-1">
                            <Icon name="format_list_numbered" className="text-sm" />
                            Ayat {log.ayat_start}–{log.ayat_end}
                        </span>
                        {log.halaman_start && (
                            <span className="flex items-center gap-1">
                                <Icon name="menu_book" className="text-sm text-blue-600" />
                                Hal. {log.halaman_start}{log.halaman_end && log.halaman_end !== log.halaman_start ? `–${log.halaman_end}` : ''}
                            </span>
                        )}
                        <span className="flex items-center gap-1">
                            <Icon name="schedule" className="text-sm" />
                            {log.submitted_at}
                        </span>
                    </div>
                    {log.notes && (
                        <p className="mt-2 text-xs text-on-surface-variant italic border-l-2 border-primary-container/30 pl-2">
                            "{log.notes}"
                        </p>
                    )}
                </div>

                {/* Score options */}
                <div className="space-y-2 mb-5">
                    {scoreOptions.map(opt => (
                        <button
                            key={opt.value}
                            onClick={() => setData('score', opt.value)}
                            className={`w-full flex items-center gap-3 p-4 rounded-xl border-2 transition-all text-left ${
                                data.score === opt.value
                                    ? opt.cls + ' scale-[1.01] shadow-sm'
                                    : 'border-surface-container text-on-surface-variant hover:bg-white/60'
                            }`}
                        >
                            <Icon name={opt.icon} className="text-2xl flex-shrink-0" filled={data.score === opt.value} />
                            <div className="flex-1">
                                <p className="font-bold text-sm">{opt.label}</p>
                                <p className="text-[10px] opacity-70">{opt.desc}</p>
                            </div>
                            {data.score === opt.value && (
                                <Icon name="radio_button_checked" className="text-lg flex-shrink-0" filled />
                            )}
                        </button>
                    ))}
                </div>

                {/* Mentor notes */}
                <div className="mb-5">
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-2">
                        Catatan Mentor <span className="normal-case font-normal">(opsional)</span>
                    </label>
                    <textarea
                        rows={3}
                        value={data.mentor_notes}
                        onChange={e => setData('mentor_notes', e.target.value)}
                        placeholder="Tajwid, makhorijul huruf, saran perbaikan..."
                        className="glass-input w-full text-sm resize-none"
                    />
                </div>

                <div className="flex gap-3">
                    <button
                        onClick={onClose}
                        className="flex-1 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        disabled={processing}
                        onClick={() => patch(`/mentor/hafalan/log/${log.id}`, { onSuccess: onClose })}
                        className="flex-1 py-3 rounded-xl font-bold text-sm bg-primary-container text-white hover:opacity-90 transition-opacity disabled:opacity-50"
                    >
                        {processing ? 'Menyimpan...' : 'Simpan Penilaian'}
                    </button>
                </div>
            </div>
        </div>
    );
}

// ─── Main Page ────────────────────────────────────────────────────────────────
export default function HafalanPending({ pending_logs }: HafalanPendingProps) {
    const [active, setActive] = useState<PendingLog | null>(null);

    return (
        <AppLayout searchPlaceholder="Cari hafalan pending...">
            <Head title="Hafalan Pending" />

            <PageHeader
                title="Hafalan Pending"
                subtitle="Antrian setoran hafalan yang menunggu penilaian dari kamu."
                breadcrumbs={[
                    { label: 'Beranda', href: '/dashboard' },
                    { label: 'Hafalan Pending' },
                ]}
                actions={
                    <button
                        onClick={() => router.post('/mentor/live-meet/start')}
                        className="bg-emerald-600 hover:bg-emerald-700 text-white flex items-center gap-2 text-sm px-4 py-2.5 rounded-xl font-bold transition-all shadow-md shadow-emerald-200 hover:scale-[1.02] active:scale-95"
                    >
                        <Icon name="video_call" className="text-xl" />
                        Mulai Live Meet
                    </button>
                }
            />

            {pending_logs.length > 0 && (
                <div className="mb-4 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-3">
                    <Icon name="pending_actions" className="text-amber-600 text-2xl" />
                    <div>
                        <p className="text-sm font-bold text-amber-700">{pending_logs.length} setoran menunggu penilaianmu</p>
                        <p className="text-xs text-amber-600">Klik tombol "Nilai" untuk memberi penilaian</p>
                    </div>
                </div>
            )}

            <div className="glass-panel rounded-2xl overflow-hidden">
                {pending_logs.length === 0 ? (
                    <EmptyState
                        icon="task_alt"
                        title="Tidak ada hafalan pending"
                        description="Semua setoran sudah dinilai. Bagus sekali! 🎉"
                        className="py-16"
                    />
                ) : (
                    <div className="divide-y divide-white/20">
                        {pending_logs.map((log) => {
                            const initials = log.mahasiswa_name.split(' ').map(n => n[0]).slice(0, 2).join('');
                            return (
                                <div key={log.id} className="p-5 hover:bg-white/40 transition-colors">
                                    <div className="flex items-center justify-between gap-4">
                                        {/* Warga info */}
                                        <div className="flex items-center gap-4 min-w-0">
                                            {log.mahasiswa_avatar ? (
                                                <img
                                                    src={log.mahasiswa_avatar}
                                                    alt={log.mahasiswa_name}
                                                    className="w-12 h-12 rounded-full border-2 border-primary/10 object-cover flex-shrink-0"
                                                />
                                            ) : (
                                                <div className="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container flex-shrink-0">
                                                    {initials}
                                                </div>
                                            )}
                                            <div className="min-w-0">
                                                <h4 className="font-body text-sm font-semibold text-on-surface">{log.mahasiswa_name}</h4>
                                                <p className="text-xs text-on-surface-variant">{log.mahasiswa_nim}</p>
                                            </div>
                                        </div>

                                        {/* Hafalan info */}
                                        <div className="hidden sm:flex items-center gap-6 flex-shrink-0">
                                            <div className="text-center">
                                                <p className="font-bold text-on-surface text-sm">{log.surah}</p>
                                                <p className="text-[10px] text-on-surface-variant">
                                                    Ayat {log.ayat_start}–{log.ayat_end}
                                                    {log.halaman_start ? ` (Hal. ${log.halaman_start}${log.halaman_end && log.halaman_end !== log.halaman_start ? `–${log.halaman_end}` : ''})` : ''}
                                                </p>
                                            </div>
                                            <p className="text-xs text-outline">{log.submitted_at}</p>
                                            <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 border border-amber-200">
                                                <Icon name="schedule" className="text-xs" />
                                                Pending
                                            </span>
                                        </div>

                                        {/* Action */}
                                        <button
                                            onClick={() => setActive(log)}
                                            className="flex-shrink-0 flex items-center gap-2 bg-primary-container/10 text-primary-container px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-primary-container hover:text-white transition-colors"
                                        >
                                            <Icon name="rate_review" className="text-base" />
                                            Nilai
                                        </button>
                                    </div>

                                    {/* Mobile hafalan info */}
                                    <div className="sm:hidden mt-3 flex items-center gap-4 pl-16">
                                        <span className="text-xs text-on-surface font-bold">{log.surah}</span>
                                        <span className="text-[10px] text-on-surface-variant font-medium">
                                            Ayat {log.ayat_start}–{log.ayat_end}
                                            {log.halaman_start ? ` (Hal. ${log.halaman_start}${log.halaman_end && log.halaman_end !== log.halaman_start ? `–${log.halaman_end}` : ''})` : ''}
                                        </span>
                                        <span className="text-[10px] text-outline">{log.submitted_at}</span>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>

            {/* Scoring modal */}
            {active && <ScoringModal log={active} onClose={() => setActive(null)} />}
        </AppLayout>
    );
}
