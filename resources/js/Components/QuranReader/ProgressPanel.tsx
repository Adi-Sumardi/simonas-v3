import { useState } from 'react';
import { router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import type { SurahDetail } from './useQuranData';

interface ProgressPanelProps {
    surahDetail: SurahDetail | null;
    markedSurahNomor: number;
    markedSurahNama: string;
    markedAyat: number;
    markedJuz: number;
    selectedRange: { dari: number; sampai: number } | null;
    onClearSelection: () => void;
    readOnly?: boolean;
}

export function ProgressPanel({
    surahDetail,
    markedSurahNomor, markedSurahNama, markedAyat, markedJuz,
    selectedRange, onClearSelection, readOnly = false,
}: ProgressPanelProps) {
    const [submitting, setSubmitting] = useState(false);
    const [notes, setNotes] = useState('');
    const [halamanStart, setHalamanStart] = useState<number | ''>('');
    const [halamanEnd, setHalamanEnd] = useState<number | ''>('');

    const hasSelection = selectedRange && selectedRange.dari > 0 && selectedRange.sampai > 0;

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

    // Auto-fill page numbers when selection changes
    useState(() => {
        if (selectedRange && surahDetail) {
            const defaultPage = SURAH_START_PAGES[surahDetail.nomor] ?? '';
            setHalamanStart(defaultPage);
            setHalamanEnd(defaultPage);
        }
    });

    // We can also trigger on change explicitly using standard react update pattern
    const prevSelectionRef = useState(selectedRange);
    if (selectedRange !== prevSelectionRef[0]) {
        prevSelectionRef[1](selectedRange);
        if (selectedRange && surahDetail) {
            const defaultPage = SURAH_START_PAGES[surahDetail.nomor] ?? '';
            setHalamanStart(defaultPage);
            setHalamanEnd(defaultPage);
        } else {
            setHalamanStart('');
            setHalamanEnd('');
        }
    }

    function submitLog() {
        if (!hasSelection || !surahDetail) return;
        setSubmitting(true);
        router.post('/mahasiswa/hafalan/log', {
            surah:         surahDetail.namaLatin,
            ayat_start:    Math.min(selectedRange.dari, selectedRange.sampai),
            ayat_end:      Math.max(selectedRange.dari, selectedRange.sampai),
            halaman_start: halamanStart || null,
            halaman_end:   halamanEnd   || null,
            notes,
            tested_at:     new Date().toISOString().slice(0, 10),
        }, {
            preserveScroll: true,
            onSuccess: () => { setNotes(''); onClearSelection(); },
            onFinish: () => setSubmitting(false),
        });
    }

    return (
        <div className="space-y-4">

            {/* ── Posisi Terakhir ── */}
            <div className="glass-card rounded-2xl p-5">
                <h4 className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-4 flex items-center gap-2">
                    <Icon name="bookmark" className="text-emerald-600 text-sm" filled />
                    Posisi Hafalan
                </h4>

                {markedSurahNomor > 0 ? (
                    <div className="space-y-3">
                        <div className="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                            <p className="text-xs text-emerald-700 font-bold mb-0.5">Terakhir ditandai</p>
                            <p className="text-on-surface font-black">{markedSurahNama}</p>
                            <p className="text-sm text-emerald-700">Ayat {markedAyat} · Juz {markedJuz}</p>
                        </div>

                        {/* Progress bar: juz */}
                        <div>
                            <div className="flex justify-between text-xs text-on-surface-variant mb-1">
                                <span>Progress Juz</span>
                                <span className="font-bold text-emerald-700">{markedJuz}/30</span>
                            </div>
                            <div className="h-2 bg-surface-container rounded-full overflow-hidden">
                                <div
                                    className="h-full bg-emerald-500 rounded-full transition-all"
                                    style={{ width: `${(markedJuz / 30) * 100}%` }}
                                />
                            </div>
                        </div>
                    </div>
                ) : (
                    <div className="text-center py-4">
                        <Icon name="bookmark_border" className="text-3xl text-on-surface-variant opacity-30 mb-2" />
                        <p className="text-xs text-on-surface-variant">Tandai ayat untuk menyimpan posisi hafalan</p>
                    </div>
                )}
            </div>

            {/* ── Log Cepat ── */}
            {!readOnly && (
                <div className="glass-card rounded-2xl p-5">
                    <h4 className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-4 flex items-center gap-2">
                        <Icon name="send" className="text-primary-container text-sm" />
                        Log Setoran Cepat
                    </h4>

                    {hasSelection ? (
                        <div className="space-y-3">
                            <div className="bg-amber-50 border border-amber-200 rounded-xl p-3 space-y-1">
                                <p className="text-xs font-bold text-amber-700">Range Dipilih:</p>
                                <p className="text-sm text-on-surface font-black">
                                    {surahDetail?.namaLatin ?? '...'}
                                </p>
                                <p className="text-xs text-amber-700">
                                    Ayat {Math.min(selectedRange.dari, selectedRange.sampai)}
                                    {' – '}
                                    {Math.max(selectedRange.dari, selectedRange.sampai)}
                                </p>
                            </div>

                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1">
                                        Halaman Mulai
                                    </label>
                                    <input
                                        type="number"
                                        min={1}
                                        max={604}
                                        value={halamanStart}
                                        onChange={e => setHalamanStart(e.target.value ? parseInt(e.target.value) : '')}
                                        placeholder="Contoh: 152"
                                        className="glass-input w-full text-xs"
                                    />
                                </div>
                                <div>
                                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1">
                                        Halaman Selesai
                                    </label>
                                    <input
                                        type="number"
                                        min={halamanStart || 1}
                                        max={604}
                                        value={halamanEnd}
                                        onChange={e => setHalamanEnd(e.target.value ? parseInt(e.target.value) : '')}
                                        placeholder="Contoh: 153"
                                        className="glass-input w-full text-xs"
                                    />
                                </div>
                            </div>

                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">
                                    Catatan untuk Mentor
                                </label>
                                <textarea
                                    rows={2}
                                    value={notes}
                                    onChange={e => setNotes(e.target.value)}
                                    placeholder="Ada yang ingin disampaikan?"
                                    className="glass-input w-full text-xs resize-none"
                                />
                            </div>

                            <div className="flex gap-2">
                                <button
                                    onClick={onClearSelection}
                                    className="flex-1 py-2 rounded-xl text-xs font-bold bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    onClick={submitLog}
                                    disabled={submitting}
                                    className="flex-1 py-2 rounded-xl text-xs font-bold bg-emerald-500 text-white hover:bg-emerald-600 transition-colors disabled:opacity-50 flex items-center justify-center gap-1"
                                >
                                    <Icon name="send" className="text-sm" />
                                    {submitting ? 'Mengirim...' : 'Kirim ke Mentor'}
                                </button>
                            </div>
                        </div>
                    ) : (
                        <div className="text-center py-4">
                            <Icon name="touch_app" className="text-3xl text-on-surface-variant opacity-30 mb-2" />
                            <p className="text-xs text-on-surface-variant leading-relaxed">
                                Klik ikon <span className="font-bold">☑</span> pada ayat untuk<br />
                                memilih range setoran
                            </p>
                        </div>
                    )}
                </div>
            )}

            {/* ── Cara Penggunaan ── */}
            {!readOnly && (
                <div className="glass-card rounded-2xl p-4 space-y-2">
                    <h4 className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">
                        Panduan Cepat
                    </h4>
                    {[
                        { icon: 'volume_up',    desc: 'Tap 🔊 untuk dengarkan ayat' },
                        { icon: 'bookmark',     desc: 'Tap 🔖 untuk tandai posisi' },
                        { icon: 'check_box',    desc: 'Tap ☑ untuk pilih range log' },
                    ].map(g => (
                        <div key={g.desc} className="flex items-center gap-2 text-xs text-on-surface-variant">
                            <Icon name={g.icon} className="text-sm text-emerald-600 flex-shrink-0" />
                            <span>{g.desc}</span>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}
