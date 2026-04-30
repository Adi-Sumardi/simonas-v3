import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Icon } from '@/Components/ui/Icon';

interface Submission {
    id: number; santri_id: number; santri_name: string; asrama: string;
    surah: string; ayat_dari: number; ayat_sampai: number; juz: number;
    tanggal: string; status: 'pending' | 'graded';
    nilai?: number; grade?: string; catatan?: string;
}
interface Props {
    submissions: Submission[];
    stats: { pending: number; graded: number; total: number; avg_nilai: number };
}

const GRADE_OPTIONS = [
    { grade: 'A', min: 90, label: 'A — Mumtaz (90-100)',     color: 'text-emerald-600 bg-emerald-50 border-emerald-200' },
    { grade: 'B', min: 80, label: 'B — Jayyid Jiddan (80-89)', color: 'text-blue-600 bg-blue-50 border-blue-100' },
    { grade: 'C', min: 70, label: 'C — Jayyid (70-79)',       color: 'text-amber-600 bg-amber-50 border-amber-200' },
    { grade: 'D', min: 60, label: 'D — Maqbul (60-69)',       color: 'text-orange-600 bg-orange-50 border-orange-200' },
    { grade: 'E', min: 0,  label: 'E — Rasib (< 60)',         color: 'text-rose-600 bg-rose-50 border-rose-200' },
];

function GradeBadge({ grade, nilai }: { grade?: string; nilai?: number }) {
    const style = grade ? GRADE_OPTIONS.find(g => g.grade === grade)?.color ?? '' : 'text-amber-600 bg-amber-50 border-amber-200';
    return (
        <span className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-black border ${style}`}>
            {grade ? `${grade} · ${nilai}` : 'Pending'}
        </span>
    );
}

function ScoringModal({ sub, onClose }: { sub: Submission; onClose: () => void }) {
    const { data, setData, post, processing } = useForm({
        nilai: sub.nilai ?? 80,
        grade: sub.grade ?? 'B',
        catatan: sub.catatan ?? '',
    });

    function autoGrade(nilai: number) {
        const g = GRADE_OPTIONS.find(g => nilai >= g.min)?.grade ?? 'E';
        setData('grade', g);
    }

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div className="glass-card rounded-2xl w-full max-w-lg p-6 shadow-2xl animate-slide-up">
                <div className="flex items-center justify-between mb-5">
                    <h3 className="font-display text-lg font-bold text-on-surface">Beri Penilaian</h3>
                    <button onClick={onClose} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center">
                        <Icon name="close" className="text-on-surface-variant" />
                    </button>
                </div>

                {/* Submission info */}
                <div className="bg-surface-container/40 rounded-xl p-4 mb-5 space-y-1.5">
                    <div className="flex items-center gap-2">
                        <div className="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center font-black text-emerald-600 text-sm">
                            {sub.santri_name.split(' ').map(n => n[0]).slice(0,2).join('')}
                        </div>
                        <div>
                            <p className="font-bold text-on-surface text-sm">{sub.santri_name}</p>
                            <p className="text-xs text-on-surface-variant">{sub.asrama}</p>
                        </div>
                    </div>
                    <div className="flex flex-wrap gap-3 pt-1 text-xs text-on-surface-variant">
                        <span className="flex items-center gap-1"><Icon name="auto_stories" className="text-emerald-600 text-sm" filled /> {sub.surah} (Juz {sub.juz})</span>
                        <span className="flex items-center gap-1"><Icon name="format_list_numbered" className="text-sm" /> Ayat {sub.ayat_dari}–{sub.ayat_sampai}</span>
                        <span className="flex items-center gap-1"><Icon name="calendar_today" className="text-sm" /> {sub.tanggal}</span>
                    </div>
                </div>

                {/* Grade selection */}
                <div className="grid grid-cols-5 gap-2 mb-4">
                    {GRADE_OPTIONS.map(g => (
                        <button key={g.grade} onClick={() => { setData('grade', g.grade); setData('nilai', g.min === 0 ? 55 : g.min); }}
                            className={`py-3 rounded-xl text-sm font-black border-2 transition-all ${data.grade === g.grade ? g.color + ' border-current scale-105 shadow-md' : 'border-surface-container text-on-surface-variant hover:bg-white/60'}`}>
                            {g.grade}
                        </button>
                    ))}
                </div>

                {/* Nilai input */}
                <div className="mb-4">
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-2">Nilai (0–100)</label>
                    <div className="flex items-center gap-4">
                        <input type="range" min={0} max={100} value={data.nilai}
                            onChange={e => { const v = Number(e.target.value); setData('nilai', v); autoGrade(v); }}
                            className="flex-1 accent-primary-container" />
                        <input type="number" min={0} max={100} value={data.nilai}
                            onChange={e => { const v = Number(e.target.value); setData('nilai', v); autoGrade(v); }}
                            className="glass-input w-16 text-center text-lg font-black" />
                    </div>
                </div>

                {/* Catatan */}
                <div className="mb-5">
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-2">Catatan Mentor</label>
                    <textarea rows={3} value={data.catatan} onChange={e => setData('catatan', e.target.value)}
                        placeholder="Masukan tajwid, makhorijul huruf, saran perbaikan..."
                        className="glass-input w-full text-sm resize-none" />
                </div>

                <div className="flex gap-3">
                    <button onClick={onClose} className="flex-1 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">Batal</button>
                    <button
                        disabled={processing}
                        onClick={() => post(`/mentor/penilaian/${sub.id}`, { onSuccess: onClose })}
                        className="flex-1 py-3 rounded-xl font-bold text-sm bg-primary-container text-white hover:opacity-90 transition-opacity disabled:opacity-50"
                    >
                        {processing ? 'Menyimpan...' : 'Simpan Penilaian'}
                    </button>
                </div>
            </div>
        </div>
    );
}

export default function Penilaian({ submissions, stats }: Props) {
    const [active, setActive] = useState<Submission | null>(null);
    const [filterStatus, setFilter] = useState<'all'|'pending'|'graded'>('all');

    const filtered = submissions.filter(s => filterStatus === 'all' || s.status === filterStatus);

    return (
        <AppLayout>
            <Head title="Penilaian Hafalan" />
            <PageHeader
                title="Penilaian Hafalan"
                subtitle="Kelola dan nilai setoran hafalan santri bimbingan kamu"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Penilaian' }]}
            />

            {/* Stats */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <StatCard icon="inbox"          label="TOTAL SETORAN"  value={stats.total}     badgeColor="blue" />
                <StatCard icon="pending_actions" label="PENDING"        value={stats.pending}   badge="⏳" badgeColor="amber" />
                <StatCard icon="task_alt"        label="SUDAH DINILAI"  value={stats.graded}    badge="✓"  badgeColor="emerald" />
                <StatCard icon="analytics"       label="RATA-RATA NILAI" value={stats.avg_nilai} badge="/100" badgeColor="purple" />
            </div>

            {/* Filter tabs */}
            <div className="flex gap-2 mb-4 flex-wrap">
                {([['all','Semua'], ['pending','⏳ Pending'], ['graded','✅ Sudah Dinilai']] as const).map(([val, label]) => (
                    <button key={val} onClick={() => setFilter(val as typeof filterStatus)}
                        className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${filterStatus === val ? 'bg-primary-container text-white shadow-md' : 'glass-card text-on-surface-variant hover:bg-white/60'}`}>
                        {label}
                    </button>
                ))}
            </div>

            {/* Submission cards */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {filtered.map(s => (
                    <div key={s.id} className={`glass-card rounded-2xl p-5 flex flex-col gap-4 transition-all hover:shadow-lg ${s.status === 'pending' ? 'ring-2 ring-amber-300/50' : ''}`}>
                        {/* Header */}
                        <div className="flex items-start justify-between">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center font-black text-emerald-600 text-sm">
                                    {s.santri_name.split(' ').map((n: string) => n[0]).slice(0,2).join('')}
                                </div>
                                <div>
                                    <p className="font-bold text-on-surface text-sm">{s.santri_name}</p>
                                    <p className="text-[10px] text-on-surface-variant">{s.asrama}</p>
                                </div>
                            </div>
                            <GradeBadge grade={s.grade} nilai={s.nilai} />
                        </div>

                        {/* Surah info */}
                        <div className="bg-surface-container/40 rounded-xl p-3 space-y-1">
                            <div className="flex items-center gap-2 text-sm font-bold text-on-surface">
                                <Icon name="auto_stories" className="text-emerald-600 text-base" filled />
                                {s.surah}
                            </div>
                            <div className="flex gap-3 text-xs text-on-surface-variant">
                                <span>Juz {s.juz}</span>
                                <span>·</span>
                                <span>Ayat {s.ayat_dari}–{s.ayat_sampai}</span>
                                <span>·</span>
                                <span>{s.tanggal}</span>
                            </div>
                        </div>

                        {/* Catatan if graded */}
                        {s.status === 'graded' && s.catatan && (
                            <p className="text-xs text-on-surface-variant italic border-l-2 border-primary-container/30 pl-3">
                                "{s.catatan}"
                            </p>
                        )}

                        {/* Action */}
                        {s.status === 'pending' ? (
                            <button onClick={() => setActive(s)}
                                className="w-full py-2.5 bg-primary-container text-white rounded-xl text-sm font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                                <Icon name="rate_review" className="text-base" />
                                Beri Nilai
                            </button>
                        ) : (
                            <button onClick={() => setActive(s)}
                                className="w-full py-2.5 bg-surface-container text-on-surface-variant rounded-xl text-sm font-bold hover:bg-white/60 transition-colors flex items-center justify-center gap-2">
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
                    <p className="text-sm">Tidak ada setoran {filterStatus === 'pending' ? 'yang menunggu' : ''}</p>
                </div>
            )}

            {/* Modal */}
            {active && <ScoringModal sub={active} onClose={() => setActive(null)} />}
        </AppLayout>
    );
}
