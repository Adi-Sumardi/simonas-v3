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

    const src = ayat.audio[qari] ?? ayat.audio['05'] ?? '';

    useEffect(() => {
        const el = audioRef.current;
        if (!el) return;
        if (isPlaying) {
            el.src = src;
            el.play().catch(() => {});
        } else {
            el.pause();
            el.currentTime = 0;
            setProgress(0);
        }
    }, [isPlaying, src]);

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
                        ? 'bg-blue-500 text-white shadow-md shadow-blue-200'
                        : 'bg-surface-container text-on-surface-variant hover:bg-blue-100 hover:text-blue-600'
                }`}
                title={isPlaying ? 'Pause' : 'Putar ayat ini'}
            >
                <Icon name={isPlaying ? 'pause' : 'volume_up'} className="text-sm" filled={isPlaying} />
            </button>

            {isPlaying && progress > 0 && (
                <div className="w-16 h-1 bg-surface-container rounded-full overflow-hidden">
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
            />
        </div>
    );
}
