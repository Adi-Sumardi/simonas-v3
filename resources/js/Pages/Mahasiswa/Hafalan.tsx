import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';
import { QuranReader } from '@/Components/QuranReader';

// ─── Types ────────────────────────────────────────────────────────────────────
type Score = 'memtas' | 'layak_ulang' | 'perlu_perbaikan' | 'pending';
type ActiveTab = 'baca' | 'log' | 'riwayat';

interface HafalanLog {
    id: number; surah: string; ayat_start: number; ayat_end: number;
    score: Score; notes?: string; mentor_notes?: string;
    tested_at?: string; reviewed_at?: string;
    mentor?: { name: string; avatar?: string };
}

interface HafalanData {
    current_juz: number; current_ayah: number; target_juz: number;
    streak_days: number; total_ayah: number;
    // Bookmark fields (new)
    current_surah_nomor: number;
    current_surah_nama: string;
    current_ayat: number;
}

interface HafalanPageProps extends PageProps {
    hafalan: HafalanData;
    logs: HafalanLog[];
    weekly: { completed_pages: number; target_pages: number; percent: number };
    quality: { mutqin_percent: number; murajaah_percent: number };
}

// ─── Score config ─────────────────────────────────────────────────────────────
const SCORE_META: Record<Score, { label: string; color: string; bg: string; icon: string }> = {
    memtas:          { label: 'Memtaskan',       color: 'text-emerald-700', bg: 'bg-emerald-100', icon: 'check_circle' },
    layak_ulang:     { label: 'Layak Ulang',     color: 'text-blue-700',    bg: 'bg-blue-100',    icon: 'replay' },
    perlu_perbaikan: { label: 'Perlu Perbaikan', color: 'text-amber-700',   bg: 'bg-amber-100',   icon: 'warning' },
    pending:         { label: 'Menunggu Mentor', color: 'text-slate-600',   bg: 'bg-slate-100',   icon: 'schedule' },
};

const SURAHS = [
    'Al-Fatihah','Al-Baqarah','Ali Imran','An-Nisa','Al-Maidah','Al-Anam','Al-Araf','Al-Anfal',
    'At-Taubah','Yunus','Hud','Yusuf','Ar-Ra\'d','Ibrahim','Al-Hijr','An-Nahl','Al-Isra',
    'Al-Kahf','Maryam','Ta Ha','Al-Anbiya','Al-Hajj','Al-Mu\'minun','An-Nur','Al-Furqan',
    'Ash-Shu\'ara','An-Naml','Al-Qasas','Al-Ankabut','Ar-Rum','Luqman','As-Sajdah',
    'Al-Ahzab','Saba','Fatir','Ya-Sin','As-Saffat','Sad','Az-Zumar','Ghafir','Fussilat',
    'Ash-Shura','Az-Zukhruf','Ad-Dukhan','Al-Jathiyah','Al-Ahqaf','Muhammad','Al-Fath',
    'Al-Hujurat','Qaf','Adh-Dhariyat','At-Tur','An-Najm','Al-Qamar','Ar-Rahman','Al-Waqia',
    'Al-Hadid','Al-Mujadila','Al-Hashr','Al-Mumtahanah','As-Saf','Al-Jumuah','Al-Munafiqun',
    'At-Taghabun','At-Talaq','At-Tahrim','Al-Mulk','Al-Qalam','Al-Haqqah','Al-Maarij',
    'Nuh','Al-Jinn','Al-Muzzammil','Al-Muddaththir','Al-Qiyamah','Al-Insan','Al-Mursalat',
    'An-Naba','An-Naziat','Abasa','At-Takwir','Al-Infitar','Al-Mutaffifin','Al-Inshiqaq',
    'Al-Buruj','At-Tariq','Al-Ala','Al-Ghashiyah','Al-Fajr','Al-Balad','Ash-Shams',
    'Al-Layl','Ad-Duhaa','Ash-Sharh','At-Tin','Al-Alaq','Al-Qadr','Al-Bayyinah',
    'Az-Zalzalah','Al-Adiyat','Al-Qariah','At-Takathur','Al-Asr','Al-Humazah','Al-Fil',
    'Quraish','Al-Maun','Al-Kawthar','Al-Kafirun','An-Nasr','Al-Masad','Al-Ikhlas',
    'Al-Falaq','An-Nas',
];

// ─── Log Form Modal ───────────────────────────────────────────────────────────
function LogModal({ editItem, onClose }: { editItem: HafalanLog | null; onClose: () => void }) {
    const [form, setForm] = useState({
        surah:      editItem?.surah      ?? '',
        ayat_start: editItem?.ayat_start ?? 1,
        ayat_end:   editItem?.ayat_end   ?? 1,
        notes:      editItem?.notes      ?? '',
        tested_at:  editItem?.tested_at  ?? new Date().toISOString().slice(0, 10),
    });
    const [saving, setSaving] = useState(false);

    function set(f: string, v: string | number) { setForm(p => ({ ...p, [f]: v })); }

    function submit(e: React.FormEvent) {
        e.preventDefault();
        setSaving(true);
        const url    = editItem ? `/mahasiswa/hafalan/log/${editItem.id}` : '/mahasiswa/hafalan/log';
        const method = editItem ? router.put : router.post;
        method(url, form, {
            preserveState: true, preserveScroll: true,
            onSuccess: () => onClose(),
            onFinish:  () => setSaving(false),
        });
    }

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div className="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl w-full max-w-md">
                <div className="flex items-center justify-between px-6 py-5 border-b border-white/40">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <Icon name="auto_stories" className="text-emerald-600 text-xl" filled />
                        </div>
                        <h3 className="font-bold text-on-surface">
                            {editItem ? 'Edit Setoran' : 'Setoran Hafalan'}
                        </h3>
                    </div>
                    <button onClick={onClose} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center">
                        <Icon name="close" className="text-on-surface-variant" />
                    </button>
                </div>

                <form onSubmit={submit} className="px-6 py-5 space-y-4">
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Surah *</label>
                        <select value={form.surah} onChange={e => set('surah', e.target.value)} className="glass-input w-full text-sm" required>
                            <option value="">— Pilih Surah —</option>
                            {SURAHS.map(s => <option key={s} value={s}>{s}</option>)}
                        </select>
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Ayat Mulai *</label>
                            <input type="number" min={1} value={form.ayat_start}
                                onChange={e => set('ayat_start', parseInt(e.target.value))}
                                className="glass-input w-full text-sm" required />
                        </div>
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Ayat Selesai *</label>
                            <input type="number" min={form.ayat_start} value={form.ayat_end}
                                onChange={e => set('ayat_end', parseInt(e.target.value))}
                                className="glass-input w-full text-sm" required />
                        </div>
                    </div>
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Tanggal Setoran</label>
                        <input type="date" value={form.tested_at}
                            onChange={e => set('tested_at', e.target.value)}
                            className="glass-input w-full text-sm" />
                    </div>
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Catatan untuk Mentor</label>
                        <textarea value={form.notes} onChange={e => set('notes', e.target.value)}
                            rows={3} className="glass-input w-full text-sm resize-none"
                            placeholder="Ada bagian yang terasa sulit? Ceritakan di sini..." />
                    </div>
                    <p className="text-xs text-on-surface-variant bg-amber-50 px-3 py-2 rounded-xl flex items-start gap-2">
                        <Icon name="info" className="text-amber-600 text-sm flex-shrink-0 mt-0.5" />
                        Setoran akan dikirim ke mentor untuk dinilai. Status awal: <strong>Menunggu Mentor</strong>.
                    </p>
                    <div className="flex gap-3 pt-1">
                        <button type="button" onClick={onClose}
                            className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">
                            Batal
                        </button>
                        <button type="submit" disabled={saving}
                            className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-emerald-500 text-white hover:bg-emerald-600 transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
                            <Icon name="send" className="text-base" />
                            {saving ? 'Mengirim...' : editItem ? 'Simpan' : 'Kirim ke Mentor'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

// ─── Log Card ─────────────────────────────────────────────────────────────────
function LogCard({ log, onEdit, onDelete }: { log: HafalanLog; onEdit: () => void; onDelete: () => void }) {
    const meta = SCORE_META[log.score];
    const canEdit = log.score === 'pending';
    return (
        <div className="glass-card rounded-2xl p-4 space-y-3">
            <div className="flex items-start justify-between gap-3">
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <Icon name="auto_stories" className="text-emerald-600 text-lg" filled />
                    </div>
                    <div>
                        <p className="font-bold text-on-surface text-sm">{log.surah}</p>
                        <p className="text-xs text-on-surface-variant">Ayat {log.ayat_start}–{log.ayat_end} · {log.tested_at}</p>
                    </div>
                </div>
                <div className="flex items-center gap-2">
                    <span className={`px-2.5 py-1 rounded-full text-[10px] font-black flex items-center gap-1 ${meta.bg} ${meta.color}`}>
                        <Icon name={meta.icon} className="text-xs" />
                        {meta.label}
                    </span>
                    {canEdit && (
                        <div className="flex gap-1">
                            <button onClick={onEdit} className="w-7 h-7 rounded-lg bg-surface-container hover:bg-blue-100 hover:text-blue-600 text-on-surface-variant flex items-center justify-center transition-colors">
                                <Icon name="edit" className="text-xs" />
                            </button>
                            <button onClick={onDelete} className="w-7 h-7 rounded-lg bg-surface-container hover:bg-rose-100 hover:text-rose-600 text-on-surface-variant flex items-center justify-center transition-colors">
                                <Icon name="delete" className="text-xs" />
                            </button>
                        </div>
                    )}
                </div>
            </div>
            {log.notes && (
                <p className="text-xs text-on-surface-variant bg-surface-container/50 px-3 py-2 rounded-xl">
                    <span className="font-bold">Catatan kamu:</span> {log.notes}
                </p>
            )}
            {log.mentor_notes && (
                <div className="flex items-start gap-2 bg-blue-50 px-3 py-2 rounded-xl">
                    {log.mentor?.avatar
                        ? <img src={log.mentor.avatar} alt="" className="w-5 h-5 rounded-full flex-shrink-0 mt-0.5" />
                        : <Icon name="supervisor_account" className="text-blue-600 text-base flex-shrink-0 mt-0.5" filled />
                    }
                    <div>
                        <p className="text-[10px] font-black text-blue-700">{log.mentor?.name ?? 'Mentor'} · {log.reviewed_at}</p>
                        <p className="text-xs text-blue-800 mt-0.5">{log.mentor_notes}</p>
                    </div>
                </div>
            )}
        </div>
    );
}

// ─── Main ─────────────────────────────────────────────────────────────────────
export default function Hafalan({ hafalan, logs, weekly, quality }: HafalanPageProps) {
    const [activeTab, setActiveTab] = useState<ActiveTab>('baca');
    const [showModal, setShowModal] = useState(false);
    const [editItem,  setEditItem]  = useState<HafalanLog | null>(null);
    const [activeFilter, setFilter] = useState<Score | 'all'>('all');

    function openAdd()  { setEditItem(null); setShowModal(true); }
    function openEdit(log: HafalanLog) { setEditItem(log); setShowModal(true); }
    function closeModal() { setShowModal(false); setEditItem(null); }

    function deleteLog(log: HafalanLog) {
        if (!confirm(`Hapus setoran ${log.surah} ayat ${log.ayat_start}–${log.ayat_end}?`)) return;
        router.delete(`/mahasiswa/hafalan/log/${log.id}`, { preserveState: true, preserveScroll: true });
    }

    const percent  = Math.round(((hafalan.current_juz * 20 + hafalan.current_ayah) / 6236) * 100);
    const filtered = activeFilter === 'all' ? logs : logs.filter(l => l.score === activeFilter);

    const tabs: { key: ActiveTab; label: string; icon: string }[] = [
        { key: 'baca',     label: 'Baca Al-Quran', icon: 'auto_stories' },
        { key: 'log',      label: 'Log Setoran',   icon: 'edit_note' },
        { key: 'riwayat',  label: 'Riwayat',       icon: 'history' },
    ];

    return (
        <AppLayout searchPlaceholder="Cari hafalan...">
            <Head title="Hafalan Qur'an" />

            <PageHeader
                title="Hafalan Qur'an"
                subtitle="Baca, hafal, dan catat setoran harianmu."
                breadcrumbs={[{ label: 'Beranda', href: '/mahasiswa' }, { label: 'Hafalan' }]}
                actions={
                    activeTab !== 'baca' ? (
                        <button onClick={openAdd}
                            className="btn-primary flex items-center gap-2 text-sm px-4 py-2.5 rounded-xl font-bold">
                            <Icon name="add" className="text-xl" />
                            Setoran Baru
                        </button>
                    ) : undefined
                }
            />

            {/* ── Tab bar ── */}
            <div className="glass-card rounded-2xl p-1.5 flex gap-1 mb-6">
                {tabs.map(tab => (
                    <button
                        key={tab.key}
                        onClick={() => setActiveTab(tab.key)}
                        className={`flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-bold transition-all ${
                            activeTab === tab.key
                                ? 'bg-emerald-500 text-white shadow-md shadow-emerald-200'
                                : 'text-on-surface-variant hover:bg-surface-container'
                        }`}
                    >
                        <Icon name={tab.icon} className="text-base" filled={activeTab === tab.key} />
                        <span className="hidden sm:inline">{tab.label}</span>
                    </button>
                ))}
            </div>

            {/* ── TAB: Baca Al-Quran ── */}
            {activeTab === 'baca' && (
                <QuranReader
                    initialSurahNomor={hafalan.current_surah_nomor || 1}
                    initialSurahNama={hafalan.current_surah_nama || 'Al-Fatihah'}
                    initialAyat={hafalan.current_ayat || 1}
                    initialJuz={hafalan.current_juz || 1}
                    readOnly={false}
                />
            )}

            {/* ── TAB: Log Setoran ── */}
            {activeTab === 'log' && (
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {/* Left: Stats */}
                    <div className="space-y-4">
                        {/* Progress donut */}
                        <div className="glass-card rounded-3xl p-6 text-center relative overflow-hidden">
                            <div className="absolute top-0 right-0 p-6 opacity-5">
                                <Icon name="auto_stories" className="text-[100px] text-emerald-600" filled />
                            </div>
                            <div className="relative">
                                <div className="relative inline-flex items-center justify-center w-32 h-32 mx-auto">
                                    <svg className="w-full h-full -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="42" fill="none" stroke="#e2e8f0" strokeWidth="8"/>
                                        <circle cx="50" cy="50" r="42" fill="none" stroke="#10b981" strokeWidth="8"
                                            strokeLinecap="round"
                                            strokeDasharray={`${2.64 * percent} ${264 - 2.64 * percent}`}
                                            className="transition-all duration-1000"/>
                                    </svg>
                                    <div className="absolute text-center">
                                        <p className="text-2xl font-bold text-on-surface">{hafalan.current_juz}</p>
                                        <p className="text-xs text-on-surface-variant">Juz</p>
                                    </div>
                                </div>
                                <p className="font-bold text-on-surface mt-2">Progress Hafalan</p>
                                <p className="text-xs text-on-surface-variant">{percent}% dari 30 Juz</p>
                            </div>
                        </div>

                        {/* Weekly target */}
                        <div className="glass-card rounded-2xl p-5">
                            <div className="flex items-center justify-between mb-3">
                                <h3 className="font-bold text-on-surface text-sm">Target Minggu Ini</h3>
                                <span className="text-xs font-black text-emerald-600">{weekly.percent}%</span>
                            </div>
                            <div className="h-2.5 bg-surface-container rounded-full overflow-hidden">
                                <div className="h-full bg-emerald-500 rounded-full transition-all duration-700"
                                    style={{ width: `${weekly.percent}%` }} />
                            </div>
                            <p className="text-xs text-on-surface-variant mt-2">
                                {weekly.completed_pages} / {weekly.target_pages} ayat
                            </p>
                        </div>

                        {/* Quality stats */}
                        <div className="glass-card rounded-2xl p-5 space-y-3">
                            <h3 className="font-bold text-on-surface text-sm">Kualitas Setoran</h3>
                            {[
                                { label: 'Memtaskan', val: quality.mutqin_percent,   color: 'bg-emerald-500' },
                                { label: 'Layak Ulang', val: quality.murajaah_percent, color: 'bg-blue-500' },
                            ].map(q => (
                                <div key={q.label}>
                                    <div className="flex justify-between text-xs mb-1">
                                        <span className="text-on-surface-variant">{q.label}</span>
                                        <span className="font-bold text-on-surface">{q.val}%</span>
                                    </div>
                                    <div className="h-2 bg-surface-container rounded-full overflow-hidden">
                                        <div className={`h-full ${q.color} rounded-full transition-all duration-700`}
                                            style={{ width: `${q.val}%` }} />
                                    </div>
                                </div>
                            ))}
                        </div>

                        {/* Streak */}
                        <div className="glass-card rounded-2xl p-4 flex items-center gap-3">
                            <div className="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                                <Icon name="local_fire_department" className="text-orange-600 text-xl" filled />
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase text-on-surface-variant">Streak</p>
                                <p className="font-bold text-on-surface">{hafalan.streak_days} hari berturut-turut</p>
                            </div>
                        </div>

                        <button onClick={openAdd}
                            className="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold flex items-center justify-center gap-2 transition-colors">
                            <Icon name="add" className="text-xl" />
                            Setoran Baru
                        </button>
                    </div>

                    {/* Right: empty state or recent logs preview */}
                    <div className="lg:col-span-2">
                        <div className="glass-card rounded-3xl p-8 text-center text-on-surface-variant">
                            <Icon name="edit_note" className="text-5xl text-emerald-400 opacity-30 mb-3" filled />
                            <p className="font-bold mb-1">Log Setoran Hafalan</p>
                            <p className="text-sm mb-4">Klik tombol "Setoran Baru" untuk mencatat setoran ke mentor.</p>
                            <p className="text-xs">Atau gunakan fitur <strong>Log Cepat</strong> saat membaca di tab Al-Quran.</p>
                        </div>
                    </div>
                </div>
            )}

            {/* ── TAB: Riwayat ── */}
            {activeTab === 'riwayat' && (
                <div className="space-y-4">
                    {/* Filter tabs */}
                    <div className="glass-card rounded-2xl p-1.5 flex gap-1 overflow-x-auto">
                        {(['all', 'pending', 'memtas', 'layak_ulang', 'perlu_perbaikan'] as const).map(f => (
                            <button key={f} onClick={() => setFilter(f)}
                                className={`flex-shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all ${
                                    activeFilter === f
                                        ? f === 'all' ? 'bg-on-surface text-white' : `${SCORE_META[f as Score]?.bg} ${SCORE_META[f as Score]?.color}`
                                        : 'text-on-surface-variant hover:bg-surface-container'
                                }`}>
                                {f === 'all' ? `Semua (${logs.length})` : SCORE_META[f as Score].label}
                            </button>
                        ))}
                    </div>

                    {filtered.length === 0 ? (
                        <div className="glass-card rounded-3xl p-12 text-center text-on-surface-variant">
                            <Icon name="auto_stories" className="text-5xl text-emerald-400 opacity-30 mb-3" filled />
                            <p className="font-bold mb-1">Belum ada setoran</p>
                            <p className="text-sm mb-4">Mulai kirim setoran hafalan ke mentor sekarang.</p>
                            <button onClick={openAdd}
                                className="px-5 py-2 rounded-xl bg-emerald-500 text-white text-sm font-bold inline-flex items-center gap-2">
                                <Icon name="add" className="text-base" /> Setoran Pertama
                            </button>
                        </div>
                    ) : (
                        <div className="space-y-3">
                            {filtered.map(log => (
                                <LogCard key={log.id} log={log}
                                    onEdit={() => openEdit(log)}
                                    onDelete={() => deleteLog(log)} />
                            ))}
                        </div>
                    )}
                </div>
            )}

            {showModal && <LogModal editItem={editItem} onClose={closeModal} />}
        </AppLayout>
    );
}
