import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';

// ─── Types ────────────────────────────────────────────────────────────────────
interface Submission {
    id: number;
    warga_id: number;
    warga_name: string;
    asrama: string;
    surah: string;
    ayat_dari: number;
    ayat_sampai: number;
    juz: number | null;
    tanggal: string;
    status: 'pending' | 'graded';
    score_raw?: 'memtas' | 'layak_ulang' | 'perlu_perbaikan';
    catatan?: string;
}

interface Props {
    submissions: Submission[];
    stats: { pending: number; graded: number; total: number; avg_nilai: number };
}

// ─── Score meta ───────────────────────────────────────────────────────────────
const SCORE_META = {
    memtas:          { label: 'Memtas',          icon: 'check_circle',  cls: 'text-emerald-700 bg-emerald-50 border-emerald-200' },
    layak_ulang:     { label: 'Layak Ulang',     icon: 'refresh',       cls: 'text-amber-700 bg-amber-50 border-amber-200' },
    perlu_perbaikan: { label: 'Perlu Perbaikan', icon: 'warning',       cls: 'text-rose-700 bg-rose-50 border-rose-200' },
};

const SCORE_OPTIONS = [
    { value: 'memtas',          label: 'Memtas',          desc: 'Hafalan lancar dan baik',           icon: 'check_circle',  cls: 'text-emerald-700 bg-emerald-50 border-emerald-300' },
    { value: 'layak_ulang',     label: 'Layak Ulang',     desc: 'Perlu diulang, sudah lumayan',      icon: 'refresh',       cls: 'text-amber-700 bg-amber-50 border-amber-300' },
    { value: 'perlu_perbaikan', label: 'Perlu Perbaikan', desc: 'Banyak kesalahan, perbaiki dulu',   icon: 'warning',       cls: 'text-rose-700 bg-rose-50 border-rose-300' },
];

// ─── Score Badge ──────────────────────────────────────────────────────────────
function ScoreBadge({ score_raw }: { score_raw?: string }) {
    if (!score_raw || score_raw === 'pending') {
        return (
            <span className="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black border text-amber-600 bg-amber-50 border-amber-200">
                <Icon name="schedule" className="text-xs" />
                Pending
            </span>
        );
    }
    const m = SCORE_META[score_raw as keyof typeof SCORE_META];
    if (!m) return null;
    return (
        <span className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black border ${m.cls}`}>
            <Icon name={m.icon} className="text-xs" filled />
            {m.label}
        </span>
    );
}

// ─── Scoring Modal ─────────────────────────────────────────────────────────────
function ScoringModal({ sub, onClose }: { sub: Submission; onClose: () => void }) {
    const { data, setData, post, processing } = useForm({
        score:        (sub.score_raw ?? 'memtas') as string,
        mentor_notes: sub.catatan ?? '',
    });

    return (
        <Modal open={true} onClose={onClose} size="md" disableBackdropClose
            title={
                <div>
                    <h3 className="font-display text-lg font-bold text-on-surface">
                        {sub.status === 'pending' ? 'Beri Penilaian' : 'Edit Penilaian'}
                    </h3>
                    <p className="text-xs text-on-surface-variant mt-0.5">{sub.warga_name} · {sub.asrama}</p>
                </div>
            }
            footer={
                <>
                    <button onClick={onClose} className="px-5 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-black/10 transition-colors">
                        Batal
                    </button>
                    <button
                        disabled={processing}
                        onClick={() => post(`/mentor/penilaian/${sub.id}`, { onSuccess: onClose })}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-primary-container text-white hover:opacity-90 transition-opacity disabled:opacity-50"
                    >
                        {processing ? 'Menyimpan...' : 'Simpan Penilaian'}
                    </button>
                </>
            }>

            {/* Submission detail */}
            <div className="bg-surface-container/40 rounded-xl p-4 mb-5 space-y-1.5">
                <div className="flex items-center gap-2 text-sm font-bold text-on-surface">
                    <Icon name="auto_stories" className="text-emerald-600 text-base" filled />
                    {sub.surah}
                    {sub.juz && <span className="text-xs font-normal text-on-surface-variant">(Juz {sub.juz})</span>}
                </div>
                <div className="flex flex-wrap gap-3 text-xs text-on-surface-variant">
                    <span>Ayat {sub.ayat_dari}–{sub.ayat_sampai}</span>
                    <span>·</span>
                    <span>{sub.tanggal}</span>
                </div>
            </div>

            {/* Score options */}
            <div className="space-y-2 mb-5">
                {SCORE_OPTIONS.map(opt => (
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

            {/* Notes */}
            <div>
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
        </Modal>
    );
}

// ─── Main Page ────────────────────────────────────────────────────────────────
export default function Penilaian({ submissions, stats }: Props) {
    const [active, setActive] = useState<Submission | null>(null);
    const [filterStatus, setFilter] = useState<'all' | 'pending' | 'graded'>('all');

    const filtered = submissions.filter(s => filterStatus === 'all' || s.status === filterStatus);

    return (
        <AppLayout>
            <Head title="Penilaian Hafalan" />
            <PageHeader
                title="Penilaian Hafalan"
                subtitle="Kelola dan nilai setoran hafalan warga bimbingan kamu"
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Penilaian' }]}
            />

            {/* Stats */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <StatCard icon="inbox"          label="TOTAL SETORAN"   value={stats.total}     badgeColor="blue" />
                <StatCard icon="pending_actions" label="PENDING"         value={stats.pending}   badge="⏳" badgeColor="amber" />
                <StatCard icon="task_alt"        label="SUDAH DINILAI"  value={stats.graded}    badge="✓"  badgeColor="emerald" />
                <StatCard icon="analytics"       label="RATA-RATA"      value={`${stats.avg_nilai}/100`} badgeColor="purple" />
            </div>

            {/* Filter tabs */}
            <div className="flex gap-2 mb-4 flex-wrap">
                {([['all', 'Semua'], ['pending', '⏳ Pending'], ['graded', '✅ Sudah Dinilai']] as const).map(([val, label]) => (
                    <button
                        key={val}
                        onClick={() => setFilter(val)}
                        className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${
                            filterStatus === val
                                ? 'bg-primary-container text-white shadow-md'
                                : 'glass-card text-on-surface-variant hover:bg-white/60'
                        }`}
                    >
                        {label}
                    </button>
                ))}
            </div>

            {/* Submission cards */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {filtered.map(s => (
                    <div
                        key={s.id}
                        className={`glass-card rounded-2xl p-5 flex flex-col gap-4 transition-all hover:shadow-lg ${
                            s.status === 'pending' ? 'ring-2 ring-amber-300/50' : ''
                        }`}
                    >
                        {/* Header */}
                        <div className="flex items-start justify-between">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center font-black text-primary-container text-sm">
                                    {s.warga_name.split(' ').map((n: string) => n[0]).slice(0, 2).join('')}
                                </div>
                                <div>
                                    <p className="font-bold text-on-surface text-sm">{s.warga_name}</p>
                                    <p className="text-[10px] text-on-surface-variant">{s.asrama}</p>
                                </div>
                            </div>
                            <ScoreBadge score_raw={s.status === 'pending' ? 'pending' : s.score_raw} />
                        </div>

                        {/* Surah info */}
                        <div className="bg-surface-container/40 rounded-xl p-3 space-y-1">
                            <div className="flex items-center gap-2 text-sm font-bold text-on-surface">
                                <Icon name="auto_stories" className="text-emerald-600 text-base" filled />
                                {s.surah}
                            </div>
                            <div className="flex gap-3 text-xs text-on-surface-variant">
                                {s.juz && <><span>Juz {s.juz}</span><span>·</span></>}
                                <span>Ayat {s.ayat_dari}–{s.ayat_sampai}</span>
                                <span>·</span>
                                <span>{s.tanggal}</span>
                            </div>
                        </div>

                        {/* Catatan */}
                        {s.status === 'graded' && s.catatan && (
                            <p className="text-xs text-on-surface-variant italic border-l-2 border-primary-container/30 pl-3">
                                "{s.catatan}"
                            </p>
                        )}

                        {/* Action */}
                        {s.status === 'pending' ? (
                            <button
                                onClick={() => setActive(s)}
                                className="w-full py-2.5 bg-primary-container text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2"
                            >
                                <Icon name="rate_review" className="text-base" />
                                Beri Nilai
                            </button>
                        ) : (
                            <button
                                onClick={() => setActive(s)}
                                className="w-full py-2.5 bg-surface-container text-on-surface-variant rounded-xl text-sm font-bold hover:bg-white/60 transition-colors flex items-center justify-center gap-2"
                            >
                                <Icon name="edit" className="text-base" />
                                Edit Penilaian
                            </button>
                        )}
                    </div>
                ))}
            </div>

            {filtered.length === 0 && (
                <div className="glass-card rounded-2xl flex flex-col items-center py-16 gap-3 text-on-surface-variant">
                    <Icon name="task_alt" className="text-5xl opacity-20" />
                    <p className="text-sm">
                        Tidak ada setoran {filterStatus === 'pending' ? 'yang menunggu' : ''}
                    </p>
                </div>
            )}

            {/* Modal */}
            {active && <ScoringModal sub={active} onClose={() => setActive(null)} />}
        </AppLayout>
    );
}
