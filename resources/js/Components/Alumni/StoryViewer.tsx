import { useEffect, useRef, useState } from 'react';
import { createPortal } from 'react-dom';
import { router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { ConfirmDialog } from '@/Components/ui/ConfirmDialog';
import type { StoryGroup, StoryItem } from './StoriesBar';

interface Props {
    groups: StoryGroup[];
    initialGroupIdx: number;
    currentUserId: number;
    onClose: () => void;
}

const STORY_DURATION_MS = 5000; // 5 seconds per story

function initials(name: string) {
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
}


export function StoryViewer({ groups, initialGroupIdx, currentUserId, onClose }: Props) {
    const [groupIdx, setGroupIdx] = useState(initialGroupIdx);
    const [itemIdx, setItemIdx] = useState(0);
    const [paused, setPaused] = useState(false);
    const [progress, setProgress] = useState(0);
    const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);
    const [deleting, setDeleting] = useState(false);
    const intervalRef = useRef<ReturnType<typeof setInterval> | null>(null);
    const viewedRef = useRef<Set<number>>(new Set());

    const group = groups[groupIdx];
    const item: StoryItem | undefined = group?.items[itemIdx];

    // ── Progress timer ───────────────────────────────────────────────────────
    useEffect(() => {
        setProgress(0);
        if (!item) return;

        // Mark as viewed (silently with fetch)
        if (!viewedRef.current.has(item.id)) {
            viewedRef.current.add(item.id);
            fetch(`/alumni/hub/stories/${item.id}/view`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '',
                    'Accept': 'application/json',
                },
            }).catch(() => {});
        }

        const step = 100 / (STORY_DURATION_MS / 50); // tick every 50ms
        intervalRef.current = setInterval(() => {
            if (!paused) {
                setProgress(p => {
                    if (p >= 100) {
                        goNext();
                        return 0;
                    }
                    return p + step;
                });
            }
        }, 50);

        return () => {
            if (intervalRef.current) clearInterval(intervalRef.current);
        };
    }, [groupIdx, itemIdx, paused]);

    function goNext() {
        if (itemIdx < group.items.length - 1) {
            setItemIdx(i => i + 1);
        } else if (groupIdx < groups.length - 1) {
            setGroupIdx(g => g + 1);
            setItemIdx(0);
        } else {
            onClose();
        }
    }

    function goPrev() {
        if (itemIdx > 0) {
            setItemIdx(i => i - 1);
        } else if (groupIdx > 0) {
            setGroupIdx(g => g - 1);
            setItemIdx(0);
        }
    }

    function handleDelete() {
        if (!item) return;
        setDeleting(true);
        router.delete(`/alumni/hub/stories/${item.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                setShowDeleteConfirm(false);
                onClose();
            },
            onFinish: () => setDeleting(false),
        });
    }

    if (!group || !item) return null;

    return createPortal(
        <div
            className="fixed inset-0 z-[99999] flex items-center justify-center bg-black/95 backdrop-blur-md"
            onClick={onClose}
        >
            {/* Story card */}
            <div
                className="relative w-full max-w-sm h-[85vh] max-h-[700px] rounded-3xl overflow-hidden shadow-2xl bg-slate-900"
                onClick={e => e.stopPropagation()}
            >
                {/* ── Progress bars ── */}
                <div className="absolute top-0 left-0 right-0 z-10 flex gap-1 p-3">
                    {group.items.map((it, i) => (
                        <div key={it.id} className="flex-1 h-[3px] bg-white/20 rounded-full overflow-hidden">
                            <div
                                className="h-full bg-white rounded-full transition-none"
                                style={{
                                    width: i < itemIdx ? '100%' : i === itemIdx ? `${progress}%` : '0%',
                                }}
                            />
                        </div>
                    ))}
                </div>

                {/* ── Header ── */}
                <div className="absolute top-0 left-0 right-0 z-10 pt-8 px-4 pb-3 flex items-center gap-3"
                    style={{ background: 'linear-gradient(to bottom, rgba(0,0,0,0.8) 0%, transparent 100%)' }}>
                    <div className="w-9 h-9 rounded-full overflow-hidden flex items-center justify-center bg-gradient-to-br from-emerald-400 to-teal-600 flex-shrink-0">
                        {group.avatar
                            ? <img src={group.avatar} alt={group.name} className="w-full h-full object-cover" />
                            : <span className="text-white text-xs font-bold">{initials(group.name)}</span>
                        }
                    </div>
                    <div className="flex-1 min-w-0">
                        <p className="text-white text-sm font-bold truncate">{group.name}</p>
                        <p className="text-white/60 text-xs">{item.created_at}</p>
                    </div>

                    {/* Delete button (own stories) */}
                    {group.user_id === currentUserId && (
                        <button
                            onClick={() => setShowDeleteConfirm(true)}
                            className="w-8 h-8 rounded-full bg-white/10 hover:bg-red-500/80 flex items-center justify-center transition-colors mr-1"
                        >
                            <Icon name="delete" className="text-white text-sm" />
                        </button>
                    )}

                    {/* Close */}
                    <button
                        onClick={onClose}
                        className="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                    >
                        <Icon name="close" className="text-white text-sm" />
                    </button>
                </div>

                {/* ── Story image ── */}
                <img
                    key={item.id}
                    src={item.image_url}
                    alt="story"
                    className="w-full h-full object-contain bg-black"
                    onLoad={() => setPaused(false)}
                />

                {/* ── Caption ── */}
                {item.caption && (
                    <div className="absolute bottom-0 left-0 right-0 p-6"
                        style={{ background: 'linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%)' }}>
                        <p className="text-white text-sm leading-relaxed drop-shadow-md">{item.caption}</p>
                    </div>
                )}

                {/* ── Tap zones ── */}
                <button
                    className="absolute left-0 top-0 w-1/4 h-full z-20 outline-none"
                    onClick={goPrev}
                />
                <button
                    className="absolute right-0 top-0 w-1/4 h-full z-20 outline-none"
                    onClick={goNext}
                />
                {/* Middle: hold to pause */}
                <button
                    className="absolute left-1/4 top-0 w-1/2 h-full z-20 cursor-default outline-none"
                    onPointerDown={() => setPaused(true)}
                    onPointerUp={() => setPaused(false)}
                />
            </div>

            {/* ── Prev / Next group arrows ── */}
            {groupIdx > 0 && (
                <button
                    onClick={() => { setGroupIdx(g => g - 1); setItemIdx(0); }}
                    className="absolute left-8 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10 hidden md:flex"
                >
                    <Icon name="chevron_left" className="text-2xl" />
                </button>
            )}
            {groupIdx < groups.length - 1 && (
                <button
                    onClick={() => { setGroupIdx(g => g + 1); setItemIdx(0); }}
                    className="absolute right-8 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors z-10 hidden md:flex"
                >
                    <Icon name="chevron_right" className="text-2xl" />
                </button>
            )}
            {/* Delete Confirmation */}
            <ConfirmDialog
                open={showDeleteConfirm}
                onClose={() => setShowDeleteConfirm(false)}
                onConfirm={handleDelete}
                loading={deleting}
                title="Hapus Story?"
                message="Story ini akan dihapus permanen dan tidak dapat dikembalikan."
                confirmText="Ya, Hapus"
                type="danger"
            />
        </div>,
        document.body
    );
}
