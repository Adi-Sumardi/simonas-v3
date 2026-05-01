import { Icon } from '@/Components/ui/Icon';
import { AudioPlayer } from './AudioPlayer';
import type { AyatItem } from './useQuranData';

interface AyatCardProps {
    ayat: AyatItem;
    qari: string;
    isPlaying: boolean;
    isMarked: boolean;        // posisi terakhir user
    isPassed: boolean;        // sudah melewati ayat ini
    isSelected: boolean;      // dipilih untuk range log
    showLatin: boolean;
    showTafsir: boolean;
    readOnly: boolean;
    onPlay: () => void;
    onAudioEnd: () => void;
    onMark: () => void;
    onSelect: () => void;
}

export function AyatCard({
    ayat, qari, isPlaying, isMarked, isPassed, isSelected,
    showLatin, showTafsir, readOnly, onPlay, onAudioEnd, onMark, onSelect,
}: AyatCardProps) {

    // Determine card style based on state
    let cardClass = 'border border-white/30 bg-white/40';
    if (isMarked)    cardClass = 'border-2 border-emerald-400 bg-emerald-100/60 ring-2 ring-emerald-300/30';
    else if (isPlaying) cardClass = 'border-2 border-blue-400 bg-blue-50/80 ring-4 ring-blue-300/30 shadow-lg shadow-blue-100';
    else if (isPassed)  cardClass = 'border border-emerald-200 bg-emerald-50/60';
    else if (isSelected) cardClass = 'border-2 border-amber-300 bg-amber-50/60';

    return (
        <div
            id={`ayat-${ayat.nomorAyat}`}
            className={`rounded-2xl p-5 transition-all duration-200 ${cardClass}`}
        >
            {/* Header: nomor ayat + actions */}
            <div className="flex items-center justify-between mb-4">
                <div className="flex items-center gap-2">
                    {/* Nomor ayat badge */}
                    <div className={`w-8 h-8 rounded-full flex items-center justify-center text-xs font-black border transition-all ${
                        isPlaying ? 'bg-blue-500 text-white border-blue-600 scale-110 shadow-md shadow-blue-200'
                        : isMarked  ? 'bg-emerald-500 text-white border-emerald-600'
                        : isPassed  ? 'bg-emerald-100 text-emerald-700 border-emerald-300'
                        : 'bg-surface-container text-on-surface-variant border-white/30'
                    }`}>
                        {ayat.nomorAyat}
                    </div>
                    {isPlaying && (
                        <span className="text-[10px] font-black text-blue-700 bg-blue-100 border border-blue-300 px-2 py-0.5 rounded-full flex items-center gap-1 animate-pulse">
                            <Icon name="graphic_eq" className="text-xs" filled />
                            Sedang diputar
                        </span>
                    )}
                    {!isPlaying && isMarked && (
                        <span className="text-[10px] font-black text-emerald-700 bg-emerald-100 border border-emerald-300 px-2 py-0.5 rounded-full flex items-center gap-1">
                            <Icon name="bookmark" className="text-xs" filled />
                            Posisi Terakhir
                        </span>
                    )}
                </div>

                <div className="flex items-center gap-1.5">
                    {/* Audio player */}
                    <AudioPlayer
                        ayat={ayat}
                        qari={qari}
                        isPlaying={isPlaying}
                        onPlay={onPlay}
                        onEnd={onAudioEnd}
                    />

                    {/* Tandai button (only for non-readOnly) */}
                    {!readOnly && (
                        <button
                            onClick={onMark}
                            title="Tandai sampai sini"
                            className={`w-8 h-8 rounded-lg flex items-center justify-center transition-all ${
                                isMarked
                                    ? 'bg-emerald-500 text-white'
                                    : 'bg-surface-container text-on-surface-variant hover:bg-emerald-100 hover:text-emerald-600'
                            }`}
                        >
                            <Icon name="bookmark" className="text-sm" filled={isMarked} />
                        </button>
                    )}

                    {/* Select for log range */}
                    {!readOnly && (
                        <button
                            onClick={onSelect}
                            title="Pilih untuk log setoran"
                            className={`w-8 h-8 rounded-lg flex items-center justify-center transition-all ${
                                isSelected
                                    ? 'bg-amber-400 text-white'
                                    : 'bg-surface-container text-on-surface-variant hover:bg-amber-100 hover:text-amber-600'
                            }`}
                        >
                            <Icon name="check_box" className="text-sm" filled={isSelected} />
                        </button>
                    )}
                </div>
            </div>

            {/* Teks Arab */}
            <p
                className="text-right leading-loose mb-3 text-on-surface select-text"
                style={{
                    fontFamily: "'Scheherazade New', 'KFGQPC HAFS Uthmanic Script', serif",
                    fontSize: '26px',
                    lineHeight: '2.2',
                    direction: 'rtl',
                }}
            >
                {ayat.teksArab}
            </p>

            {/* Latin transliteration */}
            {showLatin && (
                <p className="text-sm text-on-surface-variant italic mb-2 leading-relaxed">
                    {ayat.teksLatin}
                </p>
            )}

            {/* Terjemahan Indonesia */}
            {showTafsir && (
                <p className="text-sm text-on-surface leading-relaxed border-t border-white/40 pt-2 mt-2">
                    {ayat.teksIndonesia}
                </p>
            )}
        </div>
    );
}
