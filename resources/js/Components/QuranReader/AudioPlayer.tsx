import { useRef, useEffect, useState } from 'react';
import { Icon } from '@/Components/ui/Icon';
import type { AyatItem } from './useQuranData';

interface AudioPlayerProps {
    ayat: AyatItem;
    qari: string;
    isPlaying: boolean;
    onPlay: () => void;
    onEnd: () => void;
}

export function AudioPlayer({ ayat, qari, isPlaying, onPlay, onEnd }: AudioPlayerProps) {
    const audioRef  = useRef<HTMLAudioElement>(null);
    const [progress, setProgress] = useState(0);

    const src = ayat.audio?.[qari] ?? ayat.audio?.['05'] ?? '';

    // ── Effect 1: play / pause based on isPlaying ──────────────────────────────
    useEffect(() => {
        const el = audioRef.current;
        if (!el) return;

        if (isPlaying) {
            // Only set src when starting — avoid resetting on re-render
            if (el.src !== src) {
                el.src = src;
                el.load();
            }
            el.play().catch(() => {
                // Browser may block autoplay — user gesture needed
            });
        } else {
            el.pause();
            // Only reset time if we stopped (not just paused mid-play by qari change)
            if (!isPlaying) {
                el.currentTime = 0;
                setProgress(0);
            }
        }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [isPlaying]);

    // ── Effect 2: when qari changes while playing — reload and play new src ─────
    useEffect(() => {
        const el = audioRef.current;
        if (!el || !isPlaying) return;
        el.src  = src;
        el.load();
        el.play().catch(() => {});
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [src]);

    // ── Effect 3: cleanup on unmount ───────────────────────────────────────────
    useEffect(() => {
        return () => {
            const el = audioRef.current;
            if (el) { el.pause(); el.src = ''; }
        };
    }, []);

    function handleTimeUpdate() {
        const el = audioRef.current;
        if (!el || !el.duration) return;
        setProgress((el.currentTime / el.duration) * 100);
    }

    return (
        <div className="flex items-center gap-2">
            <button
                onClick={onPlay}
                className={`w-8 h-8 rounded-lg flex items-center justify-center transition-all ${
                    isPlaying
                        ? 'bg-blue-500 text-white shadow-md shadow-blue-200 animate-pulse'
                        : 'bg-surface-container text-on-surface-variant hover:bg-blue-100 hover:text-blue-600'
                }`}
                title={isPlaying ? 'Stop' : 'Putar ayat ini'}
            >
                <Icon name={isPlaying ? 'stop' : 'volume_up'} className="text-sm" filled={isPlaying} />
            </button>

            {isPlaying && progress > 0 && (
                <div className="w-16 h-1.5 bg-surface-container rounded-full overflow-hidden">
                    <div
                        className="h-full bg-blue-400 rounded-full transition-all"
                        style={{ width: `${progress}%` }}
                    />
                </div>
            )}

            <audio
                ref={audioRef}
                onTimeUpdate={handleTimeUpdate}
                onEnded={() => { setProgress(0); onEnd(); }}
                onError={() => setProgress(0)}
            />
        </div>
    );
}
