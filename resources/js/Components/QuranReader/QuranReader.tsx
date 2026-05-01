import { useState, useEffect, useRef } from 'react';
import { router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { SurahNavigator } from './SurahNavigator';
import { AyatCard } from './AyatCard';
import { ProgressPanel } from './ProgressPanel';
import { useSurahList, useSurahDetail } from './useQuranData';
import { getJuzNumber, getJuzFirstSurah } from '@/lib/quranJuzMap';

// ─── Props ────────────────────────────────────────────────────────────────────
interface QuranReaderProps {
    initialSurahNomor?: number;     // from DB bookmark
    initialSurahNama?: string;
    initialAyat?: number;
    initialJuz?: number;
    readOnly?: boolean;             // true for mentor view
    bookmarkRoute?: string;         // default: /mahasiswa/hafalan/bookmark
}

// ─── Loading Skeleton ─────────────────────────────────────────────────────────
function AyatSkeleton() {
    return (
        <div className="space-y-3">
            {[1,2,3].map(i => (
                <div key={i} className="rounded-2xl p-5 bg-white/30 animate-pulse">
                    <div className="flex justify-between mb-4">
                        <div className="w-8 h-8 rounded-full bg-surface-container" />
                        <div className="flex gap-2">
                            <div className="w-8 h-8 rounded-lg bg-surface-container" />
                            <div className="w-8 h-8 rounded-lg bg-surface-container" />
                        </div>
                    </div>
                    <div className="h-12 bg-surface-container rounded-xl mb-3" />
                    <div className="h-4 bg-surface-container rounded-lg w-3/4 mb-2" />
                    <div className="h-4 bg-surface-container rounded-lg w-full" />
                </div>
            ))}
        </div>
    );
}

// ─── QuranReader ──────────────────────────────────────────────────────────────
export function QuranReader({
    initialSurahNomor = 1,
    initialSurahNama  = 'Al-Fatihah',
    initialAyat       = 1,
    initialJuz        = 1,
    readOnly          = false,
    bookmarkRoute     = '/mahasiswa/hafalan/bookmark',
}: QuranReaderProps) {
    // Navigation state
    const [activeSurah, setActiveSurah] = useState(initialSurahNomor);
    const [activeJuz,   setActiveJuz]   = useState(initialJuz);  // tracked separately from surah
    const [qari, setQari]               = useState('05'); // Misyari default

    // Bookmark state (from DB, then updated locally after save)
    const [markedSurah,  setMarkedSurah]  = useState(initialSurahNomor);
    const [markedNama,   setMarkedNama]   = useState(initialSurahNama);
    const [markedAyat,   setMarkedAyat]   = useState(initialAyat);
    const [markedJuz,    setMarkedJuz]    = useState(initialJuz);

    // UI state
    const [showLatin,  setShowLatin]  = useState(true);
    const [showTafsir, setShowTafsir] = useState(true);
    const [playingAyat, setPlayingAyat] = useState<number | null>(null);

    // Selection range for log submission (nomorAyat)
    const [selRange, setSelRange] = useState<{ dari: number; sampai: number } | null>(null);

    // Data hooks
    const { list: surahList, loading: listLoading } = useSurahList();
    const { detail, loading: detailLoading, error } = useSurahDetail(activeSurah);

    // Scroll to marked ayat when surah first loads
    const containerRef = useRef<HTMLDivElement>(null);
    useEffect(() => {
        if (!detail || activeSurah !== markedSurah) return;
        setTimeout(() => {
            const el = document.getElementById(`ayat-${markedAyat}`);
            el?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }, [detail, activeSurah, markedSurah, markedAyat]);

    // Auto-scroll to currently playing ayat
    useEffect(() => {
        if (playingAyat === null) return;
        // Small delay so the highlight renders first
        const timer = setTimeout(() => {
            const el = document.getElementById(`ayat-${playingAyat}`);
            el?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
        return () => clearTimeout(timer);
    }, [playingAyat]);

    // ── Handlers ──────────────────────────────────────────────────

    function handleSurahChange(nomor: number) {
        setActiveSurah(nomor);
        setActiveJuz(getJuzNumber(nomor, 1));  // update juz badge when surah changes
        setPlayingAyat(null);                  // stop audio when surah changes
    }

    function handleJuzChange(juz: number) {
        const { surah } = getJuzFirstSurah(juz);
        setActiveJuz(juz);      // ← track juz explicitly so badge shows correct value
        setActiveSurah(surah);
        setPlayingAyat(null);   // stop audio when navigating juz
    }

    function handleMark(ayat: number) {
        if (readOnly) return;
        const juz = getJuzNumber(activeSurah, ayat);
        const nama = detail?.namaLatin ?? markedNama;

        setMarkedSurah(activeSurah);
        setMarkedNama(nama);
        setMarkedAyat(ayat);
        setMarkedJuz(juz);

        router.post(bookmarkRoute, {
            surah_nomor: activeSurah,
            surah_nama:  nama,
            ayat,
            juz,
        }, { preserveScroll: true });
    }

    function handleSelect(nomorAyat: number) {
        if (selRange === null || selRange.sampai !== 0) {
            // Start new selection
            setSelRange({ dari: nomorAyat, sampai: nomorAyat });
        } else if (selRange.dari === nomorAyat) {
            // Deselect
            setSelRange(null);
        } else {
            // Expand selection
            setSelRange({ dari: selRange.dari, sampai: nomorAyat });
        }
    }

    function handleAudioEnd() {
        // Auto-play next ayat
        if (!detail || playingAyat === null) return;
        const nextAyat = playingAyat + 1;
        if (nextAyat <= detail.jumlahAyat) {
            setPlayingAyat(nextAyat);
        } else {
            setPlayingAyat(null);
        }
    }

    // ── Render ────────────────────────────────────────────────────
    return (
        <div className="space-y-4">

            {/* Font loader — lazy via CSS */}
            <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&display=swap"
            />

            {/* ── Navigator ── */}
            {listLoading ? (
                <div className="h-14 glass-card rounded-2xl animate-pulse" />
            ) : (
                <SurahNavigator
                    key={activeJuz}
                    surahList={surahList}
                    activeSurah={activeSurah}
                    activeJuz={activeJuz}
                    qari={qari}
                    onSurahChange={handleSurahChange}
                    onJuzChange={handleJuzChange}
                    onQariChange={setQari}
                />
            )}

            {/* ── Toggle controls ── */}
            <div className="flex items-center gap-2 flex-wrap">
                <button
                    onClick={() => setShowLatin(!showLatin)}
                    className={`px-3 py-1.5 rounded-full text-xs font-bold transition-all ${
                        showLatin ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'glass-card text-on-surface-variant'
                    }`}
                >
                    <Icon name="translate" className="text-xs mr-1" />
                    Latin
                </button>
                <button
                    onClick={() => setShowTafsir(!showTafsir)}
                    className={`px-3 py-1.5 rounded-full text-xs font-bold transition-all ${
                        showTafsir ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'glass-card text-on-surface-variant'
                    }`}
                >
                    <Icon name="article" className="text-xs mr-1" />
                    Terjemahan
                </button>

                {detail && !readOnly && (
                    <button
                        onClick={() => setPlayingAyat(playingAyat !== null ? null : 1)}
                        className={`ml-auto px-3 py-1.5 rounded-full text-xs font-bold flex items-center gap-1.5 transition-all ${
                            playingAyat !== null
                                ? 'bg-blue-500 text-white'
                                : 'glass-card text-on-surface-variant hover:bg-blue-50 hover:text-blue-700'
                        }`}
                    >
                        <Icon name={playingAyat !== null ? 'stop' : 'play_arrow'} className="text-sm" filled />
                        {playingAyat !== null ? 'Stop' : 'Putar Surah'}
                    </button>
                )}
            </div>

            {/* ── Main layout: reader + panel ── */}
            <div className="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {/* Reader column */}
                <div className="xl:col-span-2 space-y-3" ref={containerRef}>

                    {/* Surah header */}
                    {detail && (
                        <div className="glass-card rounded-2xl p-5 text-center relative overflow-hidden">
                            <div className="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
                                <span style={{ fontSize: 140, fontFamily: "'Scheherazade New', serif" }}>
                                    {detail.nama}
                                </span>
                            </div>
                            <p className="font-display text-lg font-bold text-on-surface">{detail.namaLatin}</p>
                            <p className="text-sm text-on-surface-variant">{detail.arti} · {detail.jumlahAyat} Ayat · {detail.tempatTurun}</p>
                            <p
                                className="text-2xl mt-2 text-on-surface"
                                style={{ fontFamily: "'Scheherazade New', serif", direction: 'rtl' }}
                            >
                                {detail.nama}
                            </p>
                        </div>
                    )}

                    {/* Bismillah (except Al-Fatihah and At-Taubah) */}
                    {detail && activeSurah !== 1 && activeSurah !== 9 && (
                        <div className="text-center py-2 text-on-surface-variant">
                            <span
                                style={{ fontFamily: "'Scheherazade New', serif", fontSize: '22px', direction: 'rtl' }}
                            >
                                بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ
                            </span>
                        </div>
                    )}

                    {/* Error state */}
                    {error && (
                        <div className="glass-card rounded-2xl p-8 text-center text-rose-600">
                            <Icon name="wifi_off" className="text-4xl mb-2 opacity-50" />
                            <p className="font-bold">{error}</p>
                            <button
                                onClick={() => window.location.reload()}
                                className="mt-3 px-4 py-2 bg-rose-100 rounded-xl text-sm font-bold hover:bg-rose-200 transition-colors"
                            >
                                Coba Lagi
                            </button>
                        </div>
                    )}

                    {/* Loading */}
                    {detailLoading && <AyatSkeleton />}

                    {/* Ayat list */}
                    {detail && !detailLoading && (
                        <div className="space-y-3">
                            {detail.ayat.map(ayat => {
                                const isMarked  = activeSurah === markedSurah && ayat.nomorAyat === markedAyat;
                                const isPassed  = activeSurah === markedSurah && ayat.nomorAyat < markedAyat;
                                const isSelected = selRange !== null && (
                                    ayat.nomorAyat >= Math.min(selRange.dari, selRange.sampai) &&
                                    ayat.nomorAyat <= Math.max(selRange.dari, selRange.sampai)
                                );
                                return (
                                    <AyatCard
                                        key={ayat.nomorAyat}
                                        ayat={ayat}
                                        qari={qari}
                                        isPlaying={playingAyat === ayat.nomorAyat}
                                        isMarked={isMarked}
                                        isPassed={isPassed}
                                        isSelected={isSelected}
                                        showLatin={showLatin}
                                        showTafsir={showTafsir}
                                        readOnly={readOnly}
                                        onPlay={() => setPlayingAyat(playingAyat === ayat.nomorAyat ? null : ayat.nomorAyat)}
                                        onAudioEnd={handleAudioEnd}
                                        onMark={() => handleMark(ayat.nomorAyat)}
                                        onSelect={() => handleSelect(ayat.nomorAyat)}
                                    />
                                );
                            })}
                        </div>
                    )}

                    {/* Next/Prev surah nav */}
                    {detail && (
                        <div className="flex gap-3 pt-2">
                            {detail.suratSebelumnya && (
                                <button
                                    onClick={() => setActiveSurah(detail.suratSebelumnya ? (detail.suratSebelumnya as any).nomor : activeSurah)}
                                    className="flex-1 glass-card rounded-2xl p-4 text-left hover:bg-white/60 transition-colors"
                                >
                                    <p className="text-[10px] text-on-surface-variant">← Sebelumnya</p>
                                    <p className="font-bold text-sm text-on-surface">{(detail.suratSebelumnya as any).namaLatin}</p>
                                </button>
                            )}
                            {detail.suratSelanjutnya && (
                                <button
                                    onClick={() => setActiveSurah(detail.suratSelanjutnya ? (detail.suratSelanjutnya as any).nomor : activeSurah)}
                                    className="flex-1 glass-card rounded-2xl p-4 text-right hover:bg-white/60 transition-colors ml-auto"
                                >
                                    <p className="text-[10px] text-on-surface-variant">Selanjutnya →</p>
                                    <p className="font-bold text-sm text-on-surface">{(detail.suratSelanjutnya as any).namaLatin}</p>
                                </button>
                            )}
                        </div>
                    )}
                </div>

                {/* Right panel */}
                <div className="xl:col-span-1">
                    <div className="sticky top-20">
                        <ProgressPanel
                            surahDetail={detail}
                            markedSurahNomor={markedSurah}
                            markedSurahNama={markedNama}
                            markedAyat={markedAyat}
                            markedJuz={markedJuz}
                            selectedRange={selRange}
                            onClearSelection={() => setSelRange(null)}
                            readOnly={readOnly}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}

export default QuranReader;
