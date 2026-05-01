import { useState } from 'react';
import { Icon } from '@/Components/ui/Icon';
import type { SurahItem } from './useQuranData';

interface SurahNavigatorProps {
    surahList: SurahItem[];
    activeSurah: number;
    activeJuz: number;
    qari: string;
    onSurahChange: (nomor: number) => void;
    onJuzChange: (juz: number) => void;
    onQariChange: (qari: string) => void;
}

const QARI_OPTIONS = [
    { value: '01', label: 'Abdullah Al-Juhany' },
    { value: '02', label: 'Abdul-Muhsin Al-Qasim' },
    { value: '03', label: 'Abdurrahman As-Sudais' },
    { value: '04', label: 'Ibrahim Al-Dossari' },
    { value: '05', label: 'Misyari Rasyid Al-Afasi' },
    { value: '06', label: 'Yasser Al-Dosari' },
];

export function SurahNavigator({
    surahList, activeSurah, activeJuz, qari,
    onSurahChange, onJuzChange, onQariChange,
}: SurahNavigatorProps) {
    const [surahSearch, setSurahSearch] = useState('');
    const [showSurahDrop, setShowSurahDrop] = useState(false);
    const [showQariDrop, setShowQariDrop] = useState(false);
    const [showJuzDrop, setShowJuzDrop] = useState(false);

    const activeSurahData = surahList.find(s => s.nomor === activeSurah);

    const filteredSurahs = surahList.filter(s =>
        s.namaLatin.toLowerCase().includes(surahSearch.toLowerCase()) ||
        s.nomor.toString() === surahSearch
    );

    function prevSurah() { if (activeSurah > 1) onSurahChange(activeSurah - 1); }
    function nextSurah() { if (activeSurah < 114) onSurahChange(activeSurah + 1); }

    return (
        <div className="glass-card rounded-2xl p-3 flex items-center gap-2 flex-wrap">

            {/* ── Juz selector ── */}
            <div className="relative">
                <button
                    onClick={() => { setShowJuzDrop(!showJuzDrop); setShowSurahDrop(false); setShowQariDrop(false); }}
                    className="flex items-center gap-1.5 px-3 py-2 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-700 rounded-xl text-sm font-bold transition-colors"
                >
                    <Icon name="menu_book" className="text-base" />
                    Juz {activeJuz}
                    <Icon name="expand_more" className="text-base" />
                </button>

                {showJuzDrop && (
                    <div className="absolute top-full left-0 mt-1 z-50 bg-white/95 backdrop-blur-xl shadow-xl rounded-2xl p-2 w-52">
                        <div className="grid grid-cols-5 gap-1">
                            {Array.from({ length: 30 }, (_, i) => i + 1).map(j => (
                                <button
                                    key={j}
                                    onClick={() => { onJuzChange(j); setShowJuzDrop(false); }}
                                    className={`py-1.5 rounded-lg text-xs font-bold transition-colors ${
                                        j === activeJuz
                                            ? 'bg-emerald-500 text-white'
                                            : 'text-on-surface-variant hover:bg-emerald-50 hover:text-emerald-700'
                                    }`}
                                >
                                    {j}
                                </button>
                            ))}
                        </div>
                    </div>
                )}
            </div>

            {/* ── Surah prev/next + dropdown ── */}
            <button onClick={prevSurah} disabled={activeSurah <= 1}
                className="w-8 h-8 rounded-lg bg-surface-container text-on-surface-variant hover:bg-white/60 disabled:opacity-30 flex items-center justify-center transition-colors">
                <Icon name="chevron_left" />
            </button>

            <div className="relative flex-1 min-w-[160px]">
                <button
                    onClick={() => { setShowSurahDrop(!showSurahDrop); setShowJuzDrop(false); setShowQariDrop(false); }}
                    className="w-full flex items-center justify-between gap-2 px-4 py-2 bg-surface-container hover:bg-white/60 rounded-xl text-sm font-bold transition-colors"
                >
                    <div className="text-left min-w-0">
                        <span className="text-on-surface truncate block">{activeSurahData?.namaLatin ?? '...'}</span>
                        {activeSurahData && (
                            <span className="text-[10px] text-on-surface-variant font-normal">{activeSurahData.jumlahAyat} ayat · {activeSurahData.tempatTurun}</span>
                        )}
                    </div>
                    <Icon name="expand_more" className="text-on-surface-variant flex-shrink-0" />
                </button>

                {showSurahDrop && (
                    <div className="absolute top-full left-0 mt-1 z-50 bg-white/95 backdrop-blur-xl shadow-2xl rounded-2xl p-3 w-72">
                        <input
                            autoFocus
                            type="text"
                            placeholder="Cari surah..."
                            value={surahSearch}
                            onChange={e => setSurahSearch(e.target.value)}
                            className="w-full px-3 py-2 bg-surface-container rounded-xl text-sm mb-2 outline-none border border-white/40 focus:border-emerald-300"
                        />
                        <div className="overflow-y-auto max-h-60 space-y-0.5">
                            {filteredSurahs.map(s => (
                                <button
                                    key={s.nomor}
                                    onClick={() => { onSurahChange(s.nomor); setShowSurahDrop(false); setSurahSearch(''); }}
                                    className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left text-sm transition-colors ${
                                        s.nomor === activeSurah
                                            ? 'bg-emerald-100 text-emerald-700 font-bold'
                                            : 'text-on-surface hover:bg-surface-container'
                                    }`}
                                >
                                    <span className="w-7 h-7 rounded-lg bg-surface-container flex items-center justify-center text-xs font-black text-on-surface-variant flex-shrink-0">
                                        {s.nomor}
                                    </span>
                                    <div className="flex-1 min-w-0">
                                        <p className="font-semibold truncate">{s.namaLatin}</p>
                                        <p className="text-[10px] text-on-surface-variant">{s.jumlahAyat} ayat · {s.arti}</p>
                                    </div>
                                    <span className="text-base font-medium text-on-surface-variant" style={{ fontFamily: "'Scheherazade New', serif", direction: 'rtl' }}>
                                        {s.nama}
                                    </span>
                                </button>
                            ))}
                        </div>
                    </div>
                )}
            </div>

            <button onClick={nextSurah} disabled={activeSurah >= 114}
                className="w-8 h-8 rounded-lg bg-surface-container text-on-surface-variant hover:bg-white/60 disabled:opacity-30 flex items-center justify-center transition-colors">
                <Icon name="chevron_right" />
            </button>

            {/* ── Qari selector ── */}
            <div className="relative ml-auto">
                <button
                    onClick={() => { setShowQariDrop(!showQariDrop); setShowSurahDrop(false); setShowJuzDrop(false); }}
                    className="flex items-center gap-1.5 px-3 py-2 bg-surface-container hover:bg-white/60 text-on-surface-variant rounded-xl text-xs font-bold transition-colors"
                    title="Pilih Qari"
                >
                    <Icon name="record_voice_over" className="text-base" />
                    <span className="hidden sm:inline">{QARI_OPTIONS.find(q => q.value === qari)?.label.split(' ')[0]}</span>
                    <Icon name="expand_more" className="text-base" />
                </button>

                {showQariDrop && (
                    <div className="absolute top-full right-0 mt-1 z-50 bg-white/95 backdrop-blur-xl shadow-xl rounded-2xl p-2 w-64">
                        {QARI_OPTIONS.map(opt => (
                            <button
                                key={opt.value}
                                onClick={() => { onQariChange(opt.value); setShowQariDrop(false); }}
                                className={`w-full text-left px-3 py-2.5 rounded-xl text-sm transition-colors flex items-center gap-2 ${
                                    opt.value === qari
                                        ? 'bg-emerald-100 text-emerald-700 font-bold'
                                        : 'text-on-surface hover:bg-surface-container'
                                }`}
                            >
                                <Icon name="mic" className="text-base flex-shrink-0" />
                                {opt.label}
                            </button>
                        ))}
                    </div>
                )}
            </div>

            {/* Click outside to close dropdowns */}
            {(showSurahDrop || showJuzDrop || showQariDrop) && (
                <div
                    className="fixed inset-0 z-40"
                    onClick={() => { setShowSurahDrop(false); setShowJuzDrop(false); setShowQariDrop(false); }}
                />
            )}
        </div>
    );
}
