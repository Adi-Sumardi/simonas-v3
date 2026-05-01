import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { QuranReader } from '@/Components/QuranReader';

// ─── Types ────────────────────────────────────────────────────────────────────
interface HafalanProgress {
    target_juz: number;
    current_juz: number;
    total_ayah_completed: number;
    streak_days: number;
    progress_percent: number;
    last_tasmi_at: string | null;
}

interface MenteeDetail {
    id: number;
    name: string;
    nim: string;
    avatar: string | null;
    asrama: string;
    kelas: string;
    angkatan: string;
    no_telp: string;
    asal_sekolah: string;
    score: number;
    hafalan_progress: HafalanProgress | null;
    pending_count: number;
    total_logs: number;
}

interface HafalanLog {
    id: number;
    surah: string;
    ayat_start: number;
    ayat_end: number;
    score: 'memtas' | 'layak_ulang' | 'perlu_perbaikan' | 'pending';
    notes: string | null;
    mentor_notes: string | null;
    tested_at: string | null;
    reviewed_at: string | null;
    created_at: string;
}

interface TrendPoint { label: string; count: number }

interface Props {
    mentee: MenteeDetail;
    hafalan_logs: HafalanLog[];
    trend: TrendPoint[];
}

type ShowTab = 'logs' | 'quran';

// ─── Constants ────────────────────────────────────────────────────────────────
const SCORE_META = {
    memtas:          { label: 'Memtas',          icon: 'check_circle',  bg: 'bg-emerald-100', text: 'text-emerald-700',  border: 'border-emerald-200',  dot: '#10b981' },
    layak_ulang:     { label: 'Layak Ulang',     icon: 'refresh',       bg: 'bg-amber-100',   text: 'text-amber-700',    border: 'border-amber-200',    dot: '#f59e0b' },
    perlu_perbaikan: { label: 'Perlu Perbaikan', icon: 'warning',       bg: 'bg-rose-100',    text: 'text-rose-700',     border: 'border-rose-200',     dot: '#f43f5e' },
    pending:         { label: 'Menunggu',        icon: 'schedule',      bg: 'bg-blue-100',    text: 'text-blue-700',     border: 'border-blue-200',     dot: '#3b82f6' },
};

// ─── Score Badge ──────────────────────────────────────────────────────────────
function ScoreBadge({ score }: { score: HafalanLog['score'] }) {
    const m = SCORE_META[score] ?? SCORE_META.pending;
    return (
        <span className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black border ${m.bg} ${m.text} ${m.border}`}>
            <Icon name={m.icon} className="text-xs" filled />
            {m.label}
        </span>
    );
}

// ─── Scoring Modal ─────────────────────────────────────────────────────────────
function ScoringModal({ log, onClose }: { log: HafalanLog; onClose: () => void }) {
    const { data, setData, patch, processing } = useForm({
        score:        log.score !== 'pending' ? log.score : 'memtas' as string,
        mentor_notes: log.mentor_notes ?? '',
    });

    const scoreOptions = [
        { value: 'memtas',          label: 'Memtas',          desc: 'Hafalan lancar dan baik',          icon: 'check_circle',  cls: 'text-emerald-700 bg-emerald-50 border-emerald-300' },
        { value: 'layak_ulang',     label: 'Layak Ulang',     desc: 'Perlu diulang tapi sudah lumayan', icon: 'refresh',       cls: 'text-amber-700 bg-amber-50 border-amber-300' },
        { value: 'perlu_perbaikan', label: 'Perlu Perbaikan', desc: 'Banyak kesalahan, perbaiki dulu',  icon: 'warning',       cls: 'text-rose-700 bg-rose-50 border-rose-300' },
    ];

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div className="glass-card rounded-2xl w-full max-w-lg p-6 shadow-2xl animate-slide-up">
                <div className="flex items-center justify-between mb-5">
                    <div>
                        <h3 className="font-display text-lg font-bold text-on-surface">Beri Penilaian</h3>
                        <p className="text-xs text-on-surface-variant mt-0.5">{log.surah} — Ayat {log.ayat_start}–{log.ayat_end}</p>
                    </div>
                    <button onClick={onClose} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center transition-colors">
                        <Icon name="close" className="text-on-surface-variant" />
                    </button>
                </div>

                <div className="space-y-2 mb-5">
                    {scoreOptions.map(opt => (
                        <button key={opt.value} onClick={() => setData('score', opt.value)}
                            className={`w-full flex items-center gap-3 p-4 rounded-xl border-2 transition-all text-left ${
                                data.score === opt.value ? opt.cls + ' scale-[1.01] shadow-sm' : 'border-surface-container text-on-surface-variant hover:bg-white/60'
                            }`}>
                            <Icon name={opt.icon} className="text-2xl flex-shrink-0" filled={data.score === opt.value} />
                            <div>
                                <p className="font-bold text-sm">{opt.label}</p>
                                <p className="text-[10px] opacity-70">{opt.desc}</p>
                            </div>
                            {data.score === opt.value && <Icon name="check_circle" className="ml-auto text-lg" filled />}
                        </button>
                    ))}
                </div>

                <div className="mb-5">
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-2">Catatan Mentor</label>
                    <textarea rows={3} value={data.mentor_notes}
                        onChange={e => setData('mentor_notes', e.target.value)}
                        placeholder="Tajwid, makhorijul huruf, saran perbaikan..."
                        className="glass-input w-full text-sm resize-none" />
                </div>

                <div className="flex gap-3">
                    <button onClick={onClose} className="flex-1 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">
                        Batal
                    </button>
                    <button disabled={processing}
                        onClick={() => patch(`/mentor/hafalan/log/${log.id}`, { onSuccess: onClose })}
                        className="flex-1 py-3 rounded-xl font-bold text-sm bg-primary-container text-white hover:opacity-90 transition-opacity disabled:opacity-50">
                        {processing ? 'Menyimpan...' : 'Simpan Penilaian'}
                    </button>
                </div>
            </div>
        </div>
    );
}

// ─── Main Page ────────────────────────────────────────────────────────────────
export default function MenteeShow({ mentee, hafalan_logs, trend }: Props) {
    const [scoringLog,  setScoringLog]  = useState<HafalanLog | null>(null);
    const [filterScore, setFilterScore] = useState<string>('');
    const [activeTab,   setActiveTab]   = useState<ShowTab>('logs');

    const initials  = mentee.name.split(' ').map(n => n[0]).slice(0, 2).join('');
    const maxTrend  = Math.max(...trend.map(t => t.count), 1);
    const filtered  = filterScore ? hafalan_logs.filter(l => l.score === filterScore) : hafalan_logs;
    const scoreStats = {
        memtas:          hafalan_logs.filter(l => l.score === 'memtas').length,
        layak_ulang:     hafalan_logs.filter(l => l.score === 'layak_ulang').length,
        perlu_perbaikan: hafalan_logs.filter(l => l.score === 'perlu_perbaikan').length,
        pending:         hafalan_logs.filter(l => l.score === 'pending').length,
    };

    return (
        <AppLayout searchPlaceholder="Cari riwayat hafalan...">
            <Head title={`Detail: ${mentee.name}`} />

            <PageHeader
                title={mentee.name}
                subtitle={`${mentee.nim} · ${mentee.asrama}`}
                breadcrumbs={[
                    { label: 'Beranda', href: '/dashboard' },
                    { label: 'Warga Bimbingan', href: '/mentor/mentees' },
                    { label: mentee.name },
                ]}
            />

            {/* ── Tab bar ── */}
            <div className="glass-card rounded-2xl p-1.5 flex gap-1 mb-6">
                {([
                    ['logs',  '📋 Hafalan Logs',    'history'],
                    ['quran', '🕌 Quran Progress',  'auto_stories'],
                ] as const).map(([key, label, icon]) => (
                    <button key={key} onClick={() => setActiveTab(key)}
                        className={`flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-bold transition-all ${
                            activeTab === key
                                ? 'bg-emerald-500 text-white shadow-md'
                                : 'text-on-surface-variant hover:bg-surface-container'
                        }`}>
                        <Icon name={icon} className="text-base" filled={activeTab === key} />
                        <span className="hidden sm:inline">{label}</span>
                    </button>
                ))}
            </div>

            {/* ── TAB: Hafalan Logs ── */}
            {activeTab === 'logs' && (
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {/* ── Left sidebar ── */}
                    <div className="space-y-6">

                        {/* Profile card */}
                        <section className="glass-card rounded-2xl p-6">
                            <div className="flex flex-col items-center text-center mb-6">
                                {mentee.avatar ? (
                                    <img src={mentee.avatar} alt={mentee.name} className="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover mb-3" />
                                ) : (
                                    <div className="w-24 h-24 rounded-full bg-primary-fixed border-4 border-white shadow-lg flex items-center justify-center font-bold text-3xl text-primary-container mb-3">
                                        {initials}
                                    </div>
                                )}
                                <h2 className="font-display text-lg font-bold text-on-surface">{mentee.name}</h2>
                                <p className="text-sm text-on-surface-variant mt-1">{mentee.nim}</p>
                                <div className={`mt-3 px-4 py-1.5 rounded-full text-sm font-black ${
                                    mentee.score >= 85 ? 'bg-emerald-100 text-emerald-700' :
                                    mentee.score >= 70 ? 'bg-blue-100 text-blue-700' :
                                    mentee.score > 0   ? 'bg-amber-100 text-amber-700' :
                                    'bg-surface-container text-on-surface-variant'
                                }`}>
                                    {mentee.score > 0 ? `Skor: ${mentee.score}/100` : 'Belum ada penilaian'}
                                </div>
                            </div>

                            <div className="space-y-3">
                                {[
                                    { icon: 'home',          label: 'Asrama',       value: mentee.asrama },
                                    { icon: 'school',        label: 'Prodi',        value: mentee.kelas },
                                    { icon: 'calendar_today',label: 'Angkatan',     value: mentee.angkatan },
                                    { icon: 'phone',         label: 'No. HP',       value: mentee.no_telp },
                                    { icon: 'location_city', label: 'Asal Sekolah', value: mentee.asal_sekolah },
                                ].map(row => (
                                    <div key={row.label} className="flex items-center gap-3">
                                        <div className="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center flex-shrink-0">
                                            <Icon name={row.icon} className="text-sm text-on-surface-variant" />
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant">{row.label}</p>
                                            <p className="text-sm text-on-surface font-medium truncate">{row.value}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <Link href="/mentor/penilaian"
                                className="mt-5 w-full flex items-center justify-center gap-2 py-3 bg-primary-container text-white rounded-xl font-bold text-sm hover:opacity-90 transition-opacity">
                                <Icon name="rate_review" className="text-base" />
                                Lihat Semua Penilaian
                            </Link>
                        </section>

                        {/* Hafalan progress */}
                        {mentee.hafalan_progress && (
                            <section className="glass-card rounded-2xl p-6">
                                <h3 className="font-bold text-on-surface mb-4 flex items-center gap-2 text-sm">
                                    <Icon name="auto_stories" className="text-emerald-600" filled />
                                    Progress Hafalan
                                </h3>
                                <div className="mb-4">
                                    <div className="flex justify-between text-xs mb-1.5">
                                        <span className="font-bold text-emerald-700">Juz {mentee.hafalan_progress.current_juz}</span>
                                        <span className="text-on-surface-variant">Target: {mentee.hafalan_progress.target_juz} Juz</span>
                                    </div>
                                    <div className="h-3 bg-surface-container rounded-full overflow-hidden">
                                        <div className="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full transition-all"
                                            style={{ width: `${Math.min(mentee.hafalan_progress.progress_percent, 100)}%` }} />
                                    </div>
                                    <p className="text-[10px] text-on-surface-variant mt-1">{mentee.hafalan_progress.progress_percent}% dari target</p>
                                </div>
                                <div className="grid grid-cols-3 gap-2">
                                    {[
                                        { label: 'Total Ayah', value: mentee.hafalan_progress.total_ayah_completed, icon: 'format_list_numbered', color: 'text-blue-600' },
                                        { label: 'Streak',     value: `${mentee.hafalan_progress.streak_days}h`,    icon: 'local_fire_department',  color: 'text-orange-500' },
                                        { label: 'Tasmi',      value: mentee.hafalan_progress.last_tasmi_at ?? '-', icon: 'timer',                  color: 'text-purple-600' },
                                    ].map(stat => (
                                        <div key={stat.label} className="bg-surface-container/40 rounded-xl p-3 text-center">
                                            <Icon name={stat.icon} className={`text-xl ${stat.color} mb-1`} filled />
                                            <p className="font-bold text-on-surface text-sm">{stat.value}</p>
                                            <p className="text-[9px] text-on-surface-variant">{stat.label}</p>
                                        </div>
                                    ))}
                                </div>
                            </section>
                        )}

                        {/* Trend chart */}
                        <section className="glass-card rounded-2xl p-6">
                            <h3 className="font-bold text-on-surface mb-4 flex items-center gap-2 text-sm">
                                <Icon name="bar_chart" className="text-primary-container" />
                                Tren Setoran (6 Minggu)
                            </h3>
                            <div className="flex items-end gap-2 h-24">
                                {trend.map((t, i) => {
                                    const heightPct = t.count > 0 ? (t.count / maxTrend) * 100 : 4;
                                    return (
                                        <div key={t.label} className="flex-1 flex flex-col items-center gap-1">
                                            <span className="text-[9px] text-on-surface-variant font-bold">{t.count}</span>
                                            <div className="w-full rounded-t-lg bg-primary-container/20 relative overflow-hidden"
                                                style={{ height: `${Math.max(4, heightPct)}%` }}>
                                                <div className="absolute inset-0 bg-primary-container rounded-t-lg"
                                                    style={{ opacity: 0.3 + (i / trend.length) * 0.7 }} />
                                            </div>
                                            <span className="text-[9px] text-on-surface-variant">{t.label}</span>
                                        </div>
                                    );
                                })}
                            </div>
                        </section>
                    </div>

                    {/* ── Right: Hafalan Logs ── */}
                    <div className="lg:col-span-2 space-y-6">

                        {/* Stats row */}
                        <div className="grid grid-cols-4 gap-3">
                            {[
                                { key: 'pending',         label: 'Pending',     color: 'bg-blue-50 border-blue-200 text-blue-700' },
                                { key: 'memtas',          label: 'Memtas',      color: 'bg-emerald-50 border-emerald-200 text-emerald-700' },
                                { key: 'layak_ulang',     label: 'Layak Ulang', color: 'bg-amber-50 border-amber-200 text-amber-700' },
                                { key: 'perlu_perbaikan', label: 'Perbaikan',   color: 'bg-rose-50 border-rose-200 text-rose-700' },
                            ].map(s => (
                                <button key={s.key}
                                    onClick={() => setFilterScore(filterScore === s.key ? '' : s.key)}
                                    className={`p-3 rounded-xl border-2 text-center transition-all ${
                                        filterScore === s.key
                                            ? s.color + ' scale-[1.02] shadow-sm'
                                            : 'bg-white/40 border-white/40 text-on-surface-variant hover:bg-white/60'
                                    }`}>
                                    <p className="font-black text-xl">{scoreStats[s.key as keyof typeof scoreStats]}</p>
                                    <p className="text-[9px] font-bold uppercase tracking-wide mt-0.5">{s.label}</p>
                                </button>
                            ))}
                        </div>

                        {/* Logs list */}
                        <section className="glass-card rounded-2xl overflow-hidden">
                            <div className="px-6 py-4 border-b border-white/40 flex items-center justify-between">
                                <h3 className="font-bold text-on-surface">
                                    Riwayat Setoran Hafalan
                                    {filterScore && (
                                        <span className="ml-2 text-xs font-normal text-on-surface-variant">
                                            · filter: {SCORE_META[filterScore as keyof typeof SCORE_META]?.label}
                                        </span>
                                    )}
                                </h3>
                                <span className="text-xs text-on-surface-variant">{filtered.length} setoran</span>
                            </div>

                            {filtered.length === 0 ? (
                                <div className="flex flex-col items-center py-16 gap-3 text-on-surface-variant">
                                    <Icon name="auto_stories" className="text-5xl opacity-20" />
                                    <p className="text-sm">Belum ada riwayat setoran</p>
                                </div>
                            ) : (
                                <div className="divide-y divide-white/20">
                                    {filtered.map(log => {
                                        const m = SCORE_META[log.score] ?? SCORE_META.pending;
                                        return (
                                            <div key={log.id} className={`p-5 hover:bg-white/40 transition-colors ${log.score === 'pending' ? 'ring-inset ring-1 ring-blue-200/50' : ''}`}>
                                                <div className="flex items-start justify-between gap-4">
                                                    <div className="flex items-start gap-3 flex-1 min-w-0">
                                                        <div className={`w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 ${m.bg}`}>
                                                            <Icon name="auto_stories" className={`text-base ${m.text}`} filled />
                                                        </div>
                                                        <div className="flex-1 min-w-0">
                                                            <div className="flex items-center gap-2 flex-wrap">
                                                                <p className="font-bold text-on-surface text-sm">{log.surah}</p>
                                                                <ScoreBadge score={log.score} />
                                                            </div>
                                                            <p className="text-xs text-on-surface-variant mt-0.5">
                                                                Ayat {log.ayat_start}–{log.ayat_end}
                                                                {log.tested_at && ` · ${log.tested_at}`}
                                                            </p>
                                                            {log.notes && (
                                                                <p className="text-xs text-on-surface-variant italic mt-1.5 border-l-2 border-surface-container pl-2">
                                                                    "{log.notes}"
                                                                </p>
                                                            )}
                                                            {log.mentor_notes && (
                                                                <div className="mt-2 px-3 py-2 bg-emerald-50 border border-emerald-100 rounded-lg">
                                                                    <p className="text-[10px] font-bold text-emerald-700 uppercase mb-0.5">Catatan Mentor</p>
                                                                    <p className="text-xs text-emerald-800">{log.mentor_notes}</p>
                                                                </div>
                                                            )}
                                                            <p className="text-[10px] text-outline mt-2">{log.created_at}</p>
                                                        </div>
                                                    </div>

                                                    {log.score === 'pending' && (
                                                        <button onClick={() => setScoringLog(log)}
                                                            className="flex-shrink-0 flex items-center gap-1.5 px-3 py-2 bg-primary-container text-white rounded-xl text-xs font-bold hover:opacity-90 transition-opacity">
                                                            <Icon name="rate_review" className="text-sm" />
                                                            Nilai
                                                        </button>
                                                    )}
                                                    {log.score !== 'pending' && (
                                                        <button onClick={() => setScoringLog(log)}
                                                            className="flex-shrink-0 px-3 py-2 bg-surface-container text-on-surface-variant rounded-xl text-xs font-bold hover:bg-white/60 transition-colors">
                                                            Edit
                                                        </button>
                                                    )}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            )}
                        </section>
                    </div>
                </div>
            )}

            {/* ── TAB: Quran Progress (read-only untuk mentor) ── */}
            {activeTab === 'quran' && (
                <div>
                    {mentee.hafalan_progress && (
                        <div className="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                            <Icon name="bookmark" className="text-emerald-600 text-2xl" filled />
                            <div>
                                <p className="text-sm font-bold text-emerald-700">Posisi hafalan {mentee.name}</p>
                                <p className="text-xs text-emerald-600">
                                    Juz {mentee.hafalan_progress.current_juz} · Read-only — mentor tidak bisa ubah posisi santri
                                </p>
                            </div>
                        </div>
                    )}
                    <QuranReader
                        initialSurahNomor={1}
                        initialSurahNama="Al-Fatihah"
                        initialAyat={1}
                        initialJuz={mentee.hafalan_progress?.current_juz ?? 1}
                        readOnly={true}
                    />
                </div>
            )}

            {scoringLog && <ScoringModal log={scoringLog} onClose={() => setScoringLog(null)} />}
        </AppLayout>
    );
}
