import { useRef, useState } from 'react';
import { router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';
import { StoryViewer } from '@/Components/Alumni/StoryViewer';

export interface StoryItem {
    id: number;
    image_url: string;
    caption: string | null;
    created_at: string;
    expires_at: string;
}

export interface StoryGroup {
    user_id: number;
    name: string;
    avatar: string | null;
    is_self: boolean;
    all_seen: boolean;
    items: StoryItem[];
}

interface Props {
    groups: StoryGroup[];
    currentUserId: number;
    currentUserName: string;
    currentUserAvatar: string | null;
}

function initials(name: string) {
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
}

function StoryCircle({
    name, avatar, all_seen, isSelf, hasStory, onClick,
}: {
    name: string; avatar: string | null; all_seen: boolean;
    isSelf: boolean; hasStory: boolean; onClick: () => void;
}) {
    // ring color: gradient (uncircled) for unseen, gray for seen, dashed for self+empty
    const ringClass = hasStory
        ? all_seen
            ? 'bg-on-surface-variant/30'
            : 'bg-gradient-to-tr from-emerald-400 via-teal-500 to-blue-500'
        : 'bg-transparent border-2 border-dashed border-outline-variant';

    return (
        <button onClick={onClick}
            className="flex flex-col items-center gap-1.5 flex-shrink-0 group">
            <div className={`relative w-16 h-16 rounded-full p-[3px] ${ringClass} transition-transform group-active:scale-95`}>
                <div className="w-full h-full rounded-full bg-surface-container-lowest p-[2px]">
                    <div className="w-full h-full rounded-full overflow-hidden bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center">
                        {avatar
                            ? <img src={avatar} alt={name} className="w-full h-full object-cover" />
                            : <span className="text-white font-bold text-sm">{initials(name)}</span>
                        }
                    </div>
                </div>
                {/* Self + no story = "+" badge */}
                {isSelf && !hasStory && (
                    <div className="absolute -bottom-0.5 -right-0.5 w-6 h-6 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center text-white shadow">
                        <Icon name="add" className="text-base" />
                    </div>
                )}
            </div>
            <span className="text-[11px] font-medium text-on-surface truncate max-w-[68px]">
                {isSelf ? (hasStory ? 'Kamu' : 'Story-mu') : name.split(' ')[0]}
            </span>
        </button>
    );
}

export function StoriesBar({ groups, currentUserId, currentUserName, currentUserAvatar }: Props) {
    const [activeGroupIdx, setActiveGroupIdx] = useState<number | null>(null);
    const [uploading, setUploading] = useState(false);
    const [errMsg, setErrMsg] = useState<string | null>(null);
    const fileInput = useRef<HTMLInputElement>(null);
    const captionInput = useRef<HTMLInputElement>(null);
    const [pendingFile, setPendingFile] = useState<File | null>(null);
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);

    const selfGroup = groups.find(g => g.is_self);
    const otherGroups = groups.filter(g => !g.is_self);

    function handleSelfClick() {
        // Kalau sudah punya story, buka viewer. Kalau belum, buka file picker.
        if (selfGroup) {
            const idx = groups.findIndex(g => g.is_self);
            setActiveGroupIdx(idx);
        } else {
            fileInput.current?.click();
        }
    }

    function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (!file) return;
        if (file.size > 5 * 1024 * 1024) {
            setErrMsg('Ukuran maksimal 5MB');
            return;
        }
        setPendingFile(file);
        setPreviewUrl(URL.createObjectURL(file));
        setErrMsg(null);
    }

    function uploadStory() {
        if (!pendingFile) return;
        const formData = new FormData();
        formData.append('image', pendingFile);
        const cap = captionInput.current?.value ?? '';
        if (cap) formData.append('caption', cap);

        setUploading(true);
        router.post('/alumni/hub/stories', formData, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                setPendingFile(null);
                setPreviewUrl(null);
                if (fileInput.current) fileInput.current.value = '';
            },
            onError: (errors) => {
                setErrMsg(errors.image || 'Upload gagal, coba lagi.');
            },
            onFinish: () => setUploading(false),
        });
    }

    function cancelUpload() {
        setPendingFile(null);
        setPreviewUrl(null);
        setErrMsg(null);
        if (fileInput.current) fileInput.current.value = '';
    }

    return (
        <>
            <div className="glass-card rounded-2xl p-4 mb-6">
                <div className="flex gap-4 overflow-x-auto pb-1 -mx-1 px-1 scrollbar-hide">
                    {/* Self circle (always first) */}
                    <StoryCircle
                        name={currentUserName}
                        avatar={currentUserAvatar}
                        all_seen={selfGroup?.all_seen ?? true}
                        isSelf
                        hasStory={!!selfGroup}
                        onClick={handleSelfClick}
                    />

                    {/* Divider */}
                    {otherGroups.length > 0 && (
                        <div className="w-px bg-outline-variant/40 mx-1 self-stretch flex-shrink-0" />
                    )}

                    {/* Other users' stories */}
                    {otherGroups.map((g) => (
                        <StoryCircle
                            key={g.user_id}
                            name={g.name}
                            avatar={g.avatar}
                            all_seen={g.all_seen}
                            isSelf={false}
                            hasStory
                            onClick={() => {
                                const idx = groups.findIndex(grp => grp.user_id === g.user_id);
                                setActiveGroupIdx(idx);
                            }}
                        />
                    ))}
                </div>

                <input
                    ref={fileInput}
                    type="file"
                    accept="image/*"
                    onChange={handleFileChange}
                    className="hidden"
                />
            </div>

            {/* Upload preview modal */}
            {previewUrl && activeGroupIdx === null && (
                <Modal open={true} onClose={cancelUpload} title="Bagikan Story" icon="auto_stories" size="md"
                    footer={
                        <>
                            <button onClick={cancelUpload} disabled={uploading}
                                className="px-5 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-black/10 transition-colors disabled:opacity-50">
                                Batal
                            </button>
                            <button onClick={uploadStory} disabled={uploading}
                                className="px-5 py-2.5 rounded-xl font-bold text-sm bg-emerald-500 text-white hover:bg-emerald-600 transition-colors disabled:opacity-50 flex items-center gap-2">
                                <Icon name="send" className="text-base" filled />
                                {uploading ? 'Mengunggah...' : 'Bagikan'}
                            </button>
                        </>
                    }>
                    <div className="space-y-4">
                        <div className="aspect-[9/16] max-h-[60vh] rounded-2xl overflow-hidden bg-black">
                            <img src={previewUrl} alt="Preview" className="w-full h-full object-contain" />
                        </div>

                        <input
                            ref={captionInput}
                            type="text"
                            placeholder="Tulis caption (opsional)..."
                            maxLength={280}
                            className="glass-input w-full text-sm"
                        />

                        {errMsg && (
                            <p className="text-sm text-error bg-error-container/40 px-3 py-2 rounded-lg flex items-center gap-2">
                                <Icon name="error" className="text-base" filled /> {errMsg}
                            </p>
                        )}

                        <div className="flex items-center gap-2 text-xs text-on-surface-variant">
                            <Icon name="schedule" className="text-base" />
                            Story akan otomatis hilang dalam 24 jam
                        </div>
                    </div>
                </Modal>
            )}

            {/* Story viewer modal */}
            {activeGroupIdx !== null && (
                <StoryViewer
                    groups={groups}
                    initialGroupIdx={activeGroupIdx}
                    currentUserId={currentUserId}
                    onClose={() => setActiveGroupIdx(null)}
                />
            )}
        </>
    );
}
