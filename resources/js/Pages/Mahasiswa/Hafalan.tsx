import { Head, router } from '@inertiajs/react';
import { useState, useEffect, useMemo } from 'react';
import axios from 'axios';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';
import { PageProps } from '@/types';
import { QuranReader } from '@/Components/QuranReader';
import { getJuzNumber } from '@/lib/quranJuzMap';

// ─── Types ────────────────────────────────────────────────────────────────────
type Score = 'memtas' | 'layak_ulang' | 'perlu_perbaikan' | 'pending';
type ActiveTab = 'baca' | 'log' | 'riwayat';

interface HafalanLog {
    id: number; surah: string; ayat_start: number; ayat_end: number;
    halaman_start?: number; halaman_end?: number;
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
    current_page?: number;
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

const SURAH_START_PAGES: Record<number, number> = {
    1: 1, 2: 2, 3: 50, 4: 77, 5: 106, 6: 128, 7: 151, 8: 177, 9: 187, 10: 208,
    11: 221, 12: 235, 13: 249, 14: 255, 15: 262, 16: 267, 17: 282, 18: 293, 19: 305, 20: 312,
    21: 322, 22: 332, 23: 342, 24: 350, 25: 359, 26: 367, 27: 377, 28: 385, 29: 396, 30: 404,
    31: 411, 32: 415, 33: 418, 34: 428, 35: 434, 36: 440, 37: 446, 38: 453, 39: 458, 40: 467,
    41: 477, 42: 483, 43: 489, 44: 496, 45: 499, 46: 502, 47: 507, 48: 511, 49: 515, 50: 518,
    51: 520, 52: 526, 53: 528, 54: 531, 55: 534, 56: 537, 57: 542, 58: 545, 59: 549, 60: 551,
    61: 553, 62: 554, 63: 556, 64: 558, 65: 560, 66: 562, 67: 564, 68: 566, 69: 568, 70: 570,
    71: 572, 72: 574, 73: 575, 74: 577, 75: 578, 76: 580, 77: 582, 78: 583, 79: 585, 80: 586,
    81: 587, 82: 589, 83: 590, 84: 591, 85: 592, 86: 593, 87: 594, 88: 595, 89: 596, 90: 597,
    91: 597, 92: 598, 93: 599, 94: 600, 95: 601, 96: 601, 97: 602, 98: 602, 99: 603, 100: 603,
    101: 604, 102: 604, 103: 604, 104: 604, 105: 604, 106: 604, 107: 604, 108: 604, 109: 604, 110: 604,
    111: 604, 112: 604, 113: 604, 114: 604
};

// ─── Log Form Modal ───────────────────────────────────────────────────────────
function LogModal({ editItem, onClose }: { editItem: HafalanLog | null; onClose: () => void }) {
    const [form, setForm] = useState({
        surah:         editItem?.surah         ?? '',
        ayat_start:    editItem?.ayat_start    ?? 1,
        ayat_end:      editItem?.ayat_end      ?? 1,
        halaman_start: editItem?.halaman_start ?? '',
        halaman_end:   editItem?.halaman_end   ?? '',
        notes:         editItem?.notes         ?? '',
        tested_at:     editItem?.tested_at     ?? new Date().toISOString().slice(0, 10),
    });
    const [saving, setSaving] = useState(false);

    function handleSurahChange(val: string) {
        set('surah', val);
        const idx = SURAHS.indexOf(val);
        if (idx !== -1) {
            const page = SURAH_START_PAGES[idx + 1];
            if (page) {
                setForm(p => ({
                    ...p,
                    surah: val,
                    halaman_start: page,
                    halaman_end: page,
                }));
            }
        }
    }

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
        <Modal open={true} onClose={onClose} title={editItem ? 'Edit Setoran' : 'Setoran Hafalan'} icon="auto_stories" size="md"
            footer={
                <>
                    <button type="button" onClick={onClose}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-black/10 transition-colors">
                        Batal
                    </button>
                    <button form="hafalan-form" type="submit" disabled={saving}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-emerald-500 text-white disabled:opacity-50 hover:bg-emerald-600 transition-colors flex items-center gap-2">
                        <Icon name="send" className="text-base" />
                        {saving ? 'Mengirim...' : editItem ? 'Simpan' : 'Kirim ke Mentor'}
                    </button>
                </>
            }>
            <form id="hafalan-form" onSubmit={submit} className="space-y-4">
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Surah *</label>
                    <select value={form.surah} onChange={e => handleSurahChange(e.target.value)} className="glass-input w-full text-sm" required>
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
                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Halaman Mulai (opsional)</label>
                        <input type="number" min={1} max={604} value={form.halaman_start}
                            onChange={e => set('halaman_start', e.target.value ? parseInt(e.target.value) : '')}
                            className="glass-input w-full text-sm" placeholder="Contoh: 152" />
                    </div>
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Halaman Selesai (opsional)</label>
                        <input type="number" min={form.halaman_start || 1} max={604} value={form.halaman_end}
                            onChange={e => set('halaman_end', e.target.value ? parseInt(e.target.value) : '')}
                            className="glass-input w-full text-sm" placeholder="Contoh: 153" />
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
            </form>
        </Modal>
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
                        <p className="text-xs text-on-surface-variant">
                            Ayat {log.ayat_start}–{log.ayat_end}
                            {log.halaman_start ? ` (Hal. ${log.halaman_start}${log.halaman_end && log.halaman_end !== log.halaman_start ? `–${log.halaman_end}` : ''})` : ''}
                            · {log.tested_at}
                        </p>
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
    const [liveMeetActive, setLiveMeetActive] = useState(false);
    const [liveMentorName, setLiveMentorName] = useState('');

    const [readerCoords, setReaderCoords] = useState({
        surahNomor: hafalan.current_surah_nomor || 1,
        surahNama:  hafalan.current_surah_nama  || 'Al-Fatihah',
        ayat:       hafalan.current_ayat        || 1,
        juz:        hafalan.current_juz         || 1,
    });

    const pageLogs = useMemo(() => {
        const map: Record<number, HafalanLog[]> = {};
        logs.forEach(log => {
            if (log.halaman_start && log.halaman_end) {
                for (let p = log.halaman_start; p <= log.halaman_end; p++) {
                    if (p >= 1 && p <= 604) {
                        if (!map[p]) map[p] = [];
                        map[p].push(log);
                    }
                }
            }
        });
        return map;
    }, [logs]);

    function handlePageClick(pageNum: number) {
        const pLogs = pageLogs[pageNum];
        let surahNomor = 1;
        let surahNama = 'Al-Fatihah';
        let ayat = 1;

        if (pLogs && pLogs.length > 0) {
            const latestLog = pLogs[0];
            const idx = SURAHS.indexOf(latestLog.surah);
            if (idx !== -1) {
                surahNomor = idx + 1;
                surahNama = latestLog.surah;
                ayat = latestLog.ayat_start;
            }
        } else {
            let bestSurahIdx = 0;
            let maxStartPage = -1;
            for (let idx = 0; idx < SURAHS.length; idx++) {
                const startPage = SURAH_START_PAGES[idx + 1];
                if (startPage !== undefined && startPage <= pageNum && startPage > maxStartPage) {
                    maxStartPage = startPage;
                    bestSurahIdx = idx;
                }
            }
            surahNomor = bestSurahIdx + 1;
            surahNama = SURAHS[bestSurahIdx];
            ayat = 1;
        }

        setReaderCoords({
            surahNomor,
            surahNama,
            ayat,
            juz: getJuzNumber(surahNomor, ayat)
        });
        setActiveTab('baca');
    }

    useEffect(() => {
        function checkLiveStatus() {
            axios.get('/mahasiswa/live-meet/status')
                .then(res => {
                    if (res.data && res.data.active) {
                        setLiveMeetActive(true);
                        setLiveMentorName(res.data.mentor_name || '');
                    } else {
                        setLiveMeetActive(false);
                    }
                })
                .catch(err => console.error('Gagal mengecek status Live Meet:', err));
        }

        checkLiveStatus();
        const interval = setInterval(checkLiveStatus, 10000);
        return () => clearInterval(interval);
    }, []);

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

            {/* ── Banner Live Meet Mentor ── */}
            {liveMeetActive && (
                <div className="mb-6 px-5 py-4 bg-emerald-500/10 border border-emerald-500/30 backdrop-blur-md rounded-2xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-[0_8px_30px_rgba(16,185,129,0.05)] animate-pulse">
                    <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 animate-bounce">
                            <Icon name="videocam" className="text-xl" filled />
                        </div>
                        <div>
                            <p className="text-sm font-bold text-emerald-800">Ustadz/Mentor {liveMentorName} sedang aktif di Live Meet!</p>
                            <p className="text-xs text-emerald-700">Silakan bergabung sekarang untuk menyetorkan hafalan Anda secara tatap muka.</p>
                        </div>
                    </div>
                    <button
                        onClick={() => router.visit('/mahasiswa/live-meet/join')}
                        className="bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs px-5 py-3 rounded-xl transition-all shadow-md shadow-emerald-200 hover:scale-[1.02] flex items-center gap-1.5 flex-shrink-0 justify-center"
                    >
                        <Icon name="video_call" className="text-lg" />
                        Gabung Live Meet
                    </button>
                </div>
            )}

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
                    key={`${readerCoords.surahNomor}-${readerCoords.ayat}`}
                    initialSurahNomor={readerCoords.surahNomor}
                    initialSurahNama={readerCoords.surahNama}
                    initialAyat={readerCoords.ayat}
                    initialJuz={readerCoords.juz}
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
                                {hafalan.current_page && (
                                    <p className="text-[11px] text-emerald-600 font-semibold mt-1">Halaman Saat Ini: {hafalan.current_page}</p>
                                )}
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

                    {/* Right: Al-Quran Page Progress Map */}
                    <div className="lg:col-span-2">
                        <PageProgressMap logs={logs} onNavigatePage={handlePageClick} />
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

// ─── PETA PROGRES HALAMAN AL-QUR'AN ──────────────────────────────────────────
function PageProgressMap({ logs, onNavigatePage }: { logs: HafalanLog[]; onNavigatePage: (p: number) => void }) {
    const [selectedJuz, setSelectedJuz] = useState<number>(1);
    const [hoveredPage, setHoveredPage] = useState<number | null>(null);

    const JUZ_PAGE_RANGES: Record<number, { start: number; end: number }> = {
        1:  { start: 1,   end: 21  },
        2:  { start: 22,  end: 41  },
        3:  { start: 42,  end: 61  },
        4:  { start: 62,  end: 81  },
        5:  { start: 82,  end: 101 },
        6:  { start: 102, end: 121 },
        7:  { start: 122, end: 141 },
        8:  { start: 142, end: 161 },
        9:  { start: 162, end: 181 },
        10: { start: 182, end: 201 },
        11: { start: 202, end: 221 },
        12: { start: 222, end: 241 },
        13: { start: 242, end: 261 },
        14: { start: 262, end: 281 },
        15: { start: 282, end: 301 },
        16: { start: 302, end: 321 },
        17: { start: 322, end: 341 },
        18: { start: 342, end: 361 },
        19: { start: 362, end: 381 },
        20: { start: 382, end: 401 },
        21: { start: 402, end: 421 },
        22: { start: 422, end: 441 },
        23: { start: 442, end: 461 },
        24: { start: 462, end: 481 },
        25: { start: 482, end: 501 },
        26: { start: 502, end: 521 },
        27: { start: 522, end: 541 },
        28: { start: 542, end: 561 },
        29: { start: 562, end: 581 },
        30: { start: 582, end: 604 },
    };

    const pageStatus = useMemo(() => {
        const status: Record<number, 'memtas' | 'layak_ulang' | 'perlu_perbaikan' | 'pending' | 'none'> = {};
        for (let i = 1; i <= 604; i++) status[i] = 'none';

        const statusWeight: Record<'memtas' | 'layak_ulang' | 'perlu_perbaikan' | 'pending' | 'none', number> = {
            memtas: 4,
            layak_ulang: 3,
            perlu_perbaikan: 2,
            pending: 1,
            none: 0
        };

        logs.forEach(log => {
            if (log.halaman_start && log.halaman_end) {
                for (let p = log.halaman_start; p <= log.halaman_end; p++) {
                    if (p >= 1 && p <= 604) {
                        const currentStatus = status[p];
                        const currentWeight = statusWeight[currentStatus] ?? 0;
                        const logWeight = statusWeight[log.score] ?? 0;
                        if (logWeight > currentWeight) {
                            status[p] = log.score;
                        }
                    }
                }
            }
        });
        return status;
    }, [logs]);

    const pageLogs = useMemo(() => {
        const map: Record<number, HafalanLog[]> = {};
        logs.forEach(log => {
            if (log.halaman_start && log.halaman_end) {
                for (let p = log.halaman_start; p <= log.halaman_end; p++) {
                    if (p >= 1 && p <= 604) {
                        if (!map[p]) map[p] = [];
                        map[p].push(log);
                    }
                }
            }
        });
        return map;
    }, [logs]);

    // Count summary stats
    const stats = useMemo(() => {
        let memtas = 0;
        let layakUlang = 0;
        let pending = 0;
        let perluPerbaikan = 0;
        for (let i = 1; i <= 604; i++) {
            if (pageStatus[i] === 'memtas') memtas++;
            else if (pageStatus[i] === 'layak_ulang') layakUlang++;
            else if (pageStatus[i] === 'pending') pending++;
            else if (pageStatus[i] === 'perlu_perbaikan') perluPerbaikan++;
        }
        return { memtas, layakUlang, pending, perluPerbaikan };
    }, [pageStatus]);

    const currentRange = JUZ_PAGE_RANGES[selectedJuz] ?? JUZ_PAGE_RANGES[1];
    const pagesToShow: number[] = [];
    for (let p = currentRange.start; p <= currentRange.end; p++) {
        pagesToShow.push(p);
    }

    const STATUS_STYLE: Record<'memtas' | 'layak_ulang' | 'perlu_perbaikan' | 'pending' | 'none', { bg: string; label: string; colorText: string }> = {
        memtas:          { bg: 'bg-emerald-500 text-white shadow-emerald-200/50 hover:bg-emerald-600', label: 'Memtas (Lancar)', colorText: 'text-emerald-700' },
        layak_ulang:     { bg: 'bg-blue-500 text-white shadow-blue-200/50 hover:bg-blue-600',          label: 'Layak Ulang',     colorText: 'text-blue-700' },
        perlu_perbaikan: { bg: 'bg-rose-500 text-white shadow-rose-200/50 hover:bg-rose-600',          label: 'Perlu Perbaikan', colorText: 'text-rose-700' },
        pending:         { bg: 'bg-amber-500 text-white shadow-amber-200/50 hover:bg-amber-600',        label: 'Menunggu Review', colorText: 'text-amber-700' },
        none:            { bg: 'bg-white/40 border border-dashed border-outline-variant/30 text-on-surface-variant hover:bg-white/80 hover:border-emerald-300', label: 'Belum Dihafal', colorText: 'text-on-surface-variant' }
    };

    return (
        <div className="glass-card rounded-3xl p-6 space-y-6 flex flex-col h-full">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/20 pb-4">
                <div>
                    <h3 className="font-display text-lg font-bold text-on-surface flex items-center gap-2">
                        <Icon name="map" className="text-emerald-600" filled />
                        Peta Progres Halaman Al-Qur'an
                    </h3>
                    <p className="text-xs text-on-surface-variant mt-0.5">Pantau status hafalan berdasarkan nomor halaman (Mushaf Madinah)</p>
                </div>
                
                {/* Juz Selector */}
                <div className="flex items-center gap-2">
                    <span className="text-xs font-bold text-on-surface-variant">Juz:</span>
                    <select
                        value={selectedJuz}
                        onChange={e => setSelectedJuz(parseInt(e.target.value))}
                        className="glass-input text-xs py-1.5 px-3 min-w-[100px]"
                    >
                        {Array.from({ length: 30 }, (_, i) => i + 1).map(j => (
                            <option key={j} value={j}>Juz {j}</option>
                        ))}
                    </select>
                </div>
            </div>

            {/* Summary statistics */}
            <div className="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <div className="p-3 bg-emerald-50 border border-emerald-100 rounded-xl text-center">
                    <p className="text-xl font-black text-emerald-600">{stats.memtas}</p>
                    <p className="text-[9px] font-bold text-emerald-700 uppercase tracking-wide">Lancar</p>
                </div>
                <div className="p-3 bg-blue-50 border border-blue-100 rounded-xl text-center">
                    <p className="text-xl font-black text-blue-600">{stats.layakUlang}</p>
                    <p className="text-[9px] font-bold text-blue-700 uppercase tracking-wide">Layak Ulang</p>
                </div>
                <div className="p-3 bg-rose-50 border border-rose-100 rounded-xl text-center">
                    <p className="text-xl font-black text-rose-600">{stats.perluPerbaikan}</p>
                    <p className="text-[9px] font-bold text-rose-700 uppercase tracking-wide">Perbaikan</p>
                </div>
                <div className="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center">
                    <p className="text-xl font-black text-amber-600">{stats.pending}</p>
                    <p className="text-[9px] font-bold text-amber-700 uppercase tracking-wide">Pending</p>
                </div>
                <div className="p-3 bg-surface-container/30 border border-white/20 rounded-xl text-center col-span-2 sm:col-span-1">
                    <p className="text-xl font-black text-on-surface">{604 - stats.memtas - stats.layakUlang - stats.perluPerbaikan - stats.pending}</p>
                    <p className="text-[9px] font-bold text-on-surface-variant uppercase tracking-wide">Belum Dihafal</p>
                </div>
            </div>

            {/* Visual Grid for pages in selected Juz */}
            <div className="flex-1">
                <p className="text-xs font-bold text-on-surface-variant mb-3">Halaman di Juz {selectedJuz} ({currentRange.start} - {currentRange.end}):</p>
                <div className="grid grid-cols-4 xs:grid-cols-6 sm:grid-cols-8 md:grid-cols-7 lg:grid-cols-9 gap-2">
                    {pagesToShow.map(p => {
                        const status = pageStatus[p] || 'none';
                        const meta = STATUS_STYLE[status];
                        const countLogs = pageLogs[p]?.length || 0;
                        return (
                            <button
                                key={p}
                                onClick={() => onNavigatePage(p)}
                                onMouseEnter={() => setHoveredPage(p)}
                                onMouseLeave={() => setHoveredPage(null)}
                                className={`h-11 rounded-xl font-bold text-xs flex flex-col items-center justify-center transition-all duration-300 relative shadow-sm ${meta.bg}`}
                                title={`Halaman ${p} (${meta.label})`}
                            >
                                <span className="text-[9px] opacity-75 font-normal">Hal</span>
                                <span className="leading-none text-sm tabular-nums">{p}</span>
                                {countLogs > 0 && (
                                    <span className="absolute -top-1 -right-1 w-3.5 h-3.5 bg-rose-500 text-white rounded-full text-[8px] flex items-center justify-center font-black border border-white">
                                        {countLogs}
                                    </span>
                                )}
                            </button>
                        );
                    })}
                </div>
            </div>

            {/* Hover details tooltip panel */}
            <div className="bg-surface-container/30 border border-white/10 rounded-2xl p-4 min-h-[90px] flex flex-col justify-center">
                {hoveredPage !== null ? (
                    <div>
                        <div className="flex items-center gap-2">
                            <span className="text-xs font-bold text-on-surface">Detail Halaman {hoveredPage}:</span>
                            <span className={`text-[10px] font-black uppercase tracking-wide px-2 py-0.5 rounded-full ${STATUS_STYLE[pageStatus[hoveredPage]].bg}`}>
                                {pageStatus[hoveredPage] === 'none' ? 'Belum Dihafal' : pageStatus[hoveredPage].toUpperCase()}
                            </span>
                        </div>
                        {pageLogs[hoveredPage] && pageLogs[hoveredPage].length > 0 ? (
                            <div className="mt-1.5 space-y-1">
                                {pageLogs[hoveredPage].slice(0, 1).map((log, index) => (
                                    <p key={index} className="text-xs text-on-surface-variant line-clamp-2">
                                        Terakhir disetorkan: <strong>{log.surah} (Ayat {log.ayat_start}-{log.ayat_end})</strong> pada tanggal {log.tested_at}.
                                    </p>
                                ))}
                            </div>
                        ) : (
                            <p className="text-xs text-on-surface-variant mt-1">Belum ada riwayat setoran untuk halaman ini.</p>
                        )}
                        <p className="text-[10px] text-emerald-600 font-bold mt-1.5 flex items-center gap-1">
                            <Icon name="ads_click" className="text-xs" /> Klik untuk membuka halaman ini di Al-Qur'an
                        </p>
                    </div>
                ) : (
                    <div className="text-center text-xs text-on-surface-variant py-2 flex flex-col items-center gap-1.5">
                        <Icon name="mouse" className="text-lg opacity-40" />
                        <p>Arahkan kursor ke sel halaman untuk melihat detail, atau klik untuk membaca.</p>
                    </div>
                )}
            </div>

            {/* Legend */}
            <div className="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 pt-4 border-t border-white/40 text-[10px] font-bold text-on-surface-variant">
                {Object.entries(STATUS_STYLE).map(([status, m]) => (
                    <div key={status} className="flex items-center gap-1.5">
                        <span className={`w-2.5 h-2.5 rounded-full ${status === 'none' ? 'bg-slate-200 border border-dashed border-slate-400' : m.bg.split(' ')[0]}`} />
                        <span>{m.label}</span>
                    </div>
                ))}
            </div>
        </div>
    );
}
