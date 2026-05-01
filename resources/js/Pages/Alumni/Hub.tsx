import { Head, Link, useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { AlumniSidebar } from '@/Components/Alumni/AlumniSidebar';
import { StoriesBar, type StoryGroup } from '@/Components/Alumni/StoriesBar';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';

// ─── Types ────────────────────────────────────────────────────────────────────
interface Author { id: number; name: string; avatar: string | null; angkatan: string }
interface Comment { id: number; content: string; created_at: string; author: Author }
interface Post {
    id: number;
    type: 'story' | 'achievement' | 'event' | 'question';
    title: string | null;
    content: string;
    image_url: string | null;
    likes_count: number;
    comments_count: number;
    is_pinned: boolean;
    is_liked: boolean;
    author: Author;
    comments: Comment[];
    created_at: string;
}
interface Paginated<T> { data: T[]; current_page: number; last_page: number; next_page_url: string | null }
interface Props { posts: Paginated<Post>; stories: StoryGroup[] }

// ─── Constants ────────────────────────────────────────────────────────────────
const POST_TYPE_META = {
    story:       { label: 'Cerita',       icon: 'auto_stories',   color: 'bg-blue-100 text-blue-700 border-blue-200' },
    achievement: { label: 'Pencapaian',   icon: 'emoji_events',   color: 'bg-amber-100 text-amber-700 border-amber-200' },
    event:       { label: 'Event',        icon: 'event',          color: 'bg-purple-100 text-purple-700 border-purple-200' },
    question:    { label: 'Tanya Jawab',  icon: 'help',           color: 'bg-emerald-100 text-emerald-700 border-emerald-200' },
};

// ─── Avatar Component ─────────────────────────────────────────────────────────
function Avatar({ author, size = 10 }: { author: Author; size?: number }) {
    const initials = author.name.split(' ').map(n => n[0]).slice(0, 2).join('');
    const cls = `w-${size} h-${size} rounded-full flex-shrink-0 flex items-center justify-center font-bold text-sm`;
    return author.avatar
        ? <img src={author.avatar} alt={author.name} className={`${cls} object-cover`} />
        : <div className={`${cls} bg-gradient-to-br from-emerald-400 to-teal-600 text-white`}>{initials}</div>;
}

// ─── Compose Post Form ────────────────────────────────────────────────────────
function ComposePost({ currentUser }: { currentUser: Author }) {
    const [open, setOpen] = useState(false);
    const { data, setData, post, processing, reset, errors } = useForm({
        type: 'story' as string,
        title: '',
        content: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/alumni/hub/posts', { onSuccess: () => { reset(); setOpen(false); } });
    }

    return (
        <div className="glass-card rounded-2xl p-4 mb-6">
            <div className="flex items-center gap-3">
                <Avatar author={currentUser} size={10} />
                <button onClick={() => setOpen(true)}
                    className="flex-1 text-left px-4 py-3 bg-surface-container hover:bg-white/60 rounded-xl text-sm text-on-surface-variant transition-colors">
                    Bagikan cerita, pencapaian, atau pertanyaan...
                </button>
            </div>

            {/* Post type shortcuts */}
            <div className="flex gap-2 mt-3 ml-[3.25rem]">
                {Object.entries(POST_TYPE_META).map(([key, m]) => (
                    <button key={key} onClick={() => { setData('type', key); setOpen(true); }}
                        className={`flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition-colors ${m.color}`}>
                        <Icon name={m.icon} className="text-xs" filled />
                        {m.label}
                    </button>
                ))}
            </div>

            {/* Modal */}
            {open && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
                    <div className="glass-card rounded-2xl w-full max-w-lg p-6 shadow-2xl animate-slide-up">
                        <div className="flex items-center justify-between mb-5">
                            <div className="flex items-center gap-3">
                                <Avatar author={currentUser} size={10} />
                                <div>
                                    <p className="font-bold text-on-surface text-sm">{currentUser.name}</p>
                                    <p className="text-xs text-on-surface-variant">Alumni · {currentUser.angkatan}</p>
                                </div>
                            </div>
                            <button onClick={() => setOpen(false)} className="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center">
                                <Icon name="close" className="text-on-surface-variant" />
                            </button>
                        </div>

                        <form onSubmit={submit} className="space-y-4">
                            {/* Type selector */}
                            <div className="flex gap-2 flex-wrap">
                                {Object.entries(POST_TYPE_META).map(([key, m]) => (
                                    <button type="button" key={key}
                                        onClick={() => setData('type', key)}
                                        className={`flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border transition-all ${
                                            data.type === key ? m.color + ' scale-105' : 'bg-surface-container border-white/30 text-on-surface-variant'
                                        }`}>
                                        <Icon name={m.icon} className="text-xs" filled={data.type === key} />
                                        {m.label}
                                    </button>
                                ))}
                            </div>

                            <input type="text" value={data.title} onChange={e => setData('title', e.target.value)}
                                placeholder="Judul (opsional)..."
                                className="glass-input w-full text-sm" />

                            <textarea rows={5} value={data.content} onChange={e => setData('content', e.target.value)}
                                placeholder="Apa yang ingin kamu bagikan kepada sesama alumni...?"
                                className="glass-input w-full text-sm resize-none" required />
                            {errors.content && <p className="text-xs text-rose-600">{errors.content}</p>}

                            <div className="flex gap-3">
                                <button type="button" onClick={() => setOpen(false)}
                                    className="flex-1 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant">
                                    Batal
                                </button>
                                <button type="submit" disabled={processing || !data.content.trim()}
                                    className="flex-1 py-3 rounded-xl font-bold text-sm bg-emerald-500 text-white disabled:opacity-50 hover:bg-emerald-600 transition-colors">
                                    {processing ? 'Memposting...' : 'Bagikan'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}

// ─── Comment Box ─────────────────────────────────────────────────────────────
function CommentBox({ postId, currentUser }: { postId: number; currentUser: Author }) {
    const { data, setData, post, processing, reset } = useForm({ content: '' });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(`/alumni/hub/posts/${postId}/comments`, { onSuccess: () => reset() });
    }

    return (
        <form onSubmit={submit} className="flex items-center gap-2 mt-3">
            <Avatar author={currentUser} size={8} />
            <input value={data.content} onChange={e => setData('content', e.target.value)}
                placeholder="Tulis komentar..."
                className="flex-1 px-3 py-2 bg-surface-container rounded-xl text-sm outline-none border border-white/30 focus:border-emerald-300 transition-colors" />
            <button type="submit" disabled={processing || !data.content.trim()}
                className="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center disabled:opacity-40 hover:bg-emerald-600 transition-colors">
                <Icon name="send" className="text-sm" filled />
            </button>
        </form>
    );
}

// ─── Post Card ────────────────────────────────────────────────────────────────
function PostCard({ post, currentUser }: { post: Post; currentUser: Author }) {
    const [showComments, setShowComments] = useState(false);
    const [liked, setLiked] = useState(post.is_liked);
    const [likesCount, setLikesCount] = useState(post.likes_count);
    const meta = POST_TYPE_META[post.type];

    function handleLike() {
        const next = !liked;
        setLiked(next);
        setLikesCount(c => next ? c + 1 : c - 1);
        router.post(`/alumni/hub/posts/${post.id}/like`, {}, { preserveScroll: true });
    }

    function handleDelete() {
        if (!confirm('Hapus post ini?')) return;
        router.delete(`/alumni/hub/posts/${post.id}`, { preserveScroll: true });
    }

    return (
        <div className={`glass-card rounded-2xl overflow-hidden ${post.is_pinned ? 'ring-2 ring-emerald-300/50' : ''}`}>
            {post.is_pinned && (
                <div className="bg-emerald-500/10 border-b border-emerald-200/50 px-5 py-2 flex items-center gap-2">
                    <Icon name="push_pin" className="text-emerald-600 text-sm" filled />
                    <span className="text-xs font-bold text-emerald-700">Disematkan</span>
                </div>
            )}

            <div className="p-5">
                {/* Header */}
                <div className="flex items-start justify-between mb-4">
                    <div className="flex items-center gap-3">
                        <Avatar author={post.author} size={11} />
                        <div>
                            <p className="font-bold text-on-surface text-sm">{post.author.name}</p>
                            <div className="flex items-center gap-2 mt-0.5">
                                <span className="text-xs text-on-surface-variant">Alumni {post.author.angkatan}</span>
                                <span className="text-on-surface-variant opacity-30">·</span>
                                <span className="text-xs text-on-surface-variant">{post.created_at}</span>
                            </div>
                        </div>
                    </div>
                    <div className="flex items-center gap-2">
                        <span className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black border ${meta.color}`}>
                            <Icon name={meta.icon} className="text-xs" filled />
                            {meta.label}
                        </span>
                        {currentUser.id === post.author.id && (
                            <button onClick={handleDelete} className="w-7 h-7 rounded-lg bg-surface-container/50 flex items-center justify-center text-on-surface-variant hover:bg-rose-100 hover:text-rose-600 transition-colors">
                                <Icon name="delete" className="text-xs" />
                            </button>
                        )}
                    </div>
                </div>

                {/* Content */}
                {post.title && <h3 className="font-bold text-on-surface mb-2">{post.title}</h3>}
                <p className="text-sm text-on-surface leading-relaxed whitespace-pre-wrap">{post.content}</p>
                {post.image_url && (
                    <img src={post.image_url} alt="post image" className="mt-3 rounded-xl w-full object-cover max-h-80" />
                )}

                {/* Actions */}
                <div className="flex items-center gap-4 mt-4 pt-3 border-t border-white/30">
                    <button onClick={handleLike}
                        className={`flex items-center gap-1.5 text-sm font-bold transition-all ${
                            liked ? 'text-rose-500' : 'text-on-surface-variant hover:text-rose-400'
                        }`}>
                        <Icon name={liked ? 'favorite' : 'favorite_border'} className="text-base" filled={liked} />
                        {likesCount > 0 && <span>{likesCount}</span>}
                    </button>

                    <button onClick={() => setShowComments(!showComments)}
                        className="flex items-center gap-1.5 text-sm font-bold text-on-surface-variant hover:text-primary-container transition-colors">
                        <Icon name="chat_bubble_outline" className="text-base" />
                        {post.comments_count > 0 && <span>{post.comments_count}</span>}
                    </button>

                    <button className="flex items-center gap-1.5 text-sm font-bold text-on-surface-variant hover:text-blue-500 transition-colors ml-auto">
                        <Icon name="share" className="text-base" />
                    </button>
                </div>

                {/* Comments */}
                {showComments && (
                    <div className="mt-4 space-y-3 border-t border-white/30 pt-4">
                        {post.comments.map(c => (
                            <div key={c.id} className="flex items-start gap-2.5">
                                <Avatar author={c.author} size={8} />
                                <div className="flex-1 bg-surface-container/60 rounded-xl px-3 py-2">
                                    <div className="flex items-center gap-2 mb-1">
                                        <span className="text-xs font-bold text-on-surface">{c.author.name}</span>
                                        <span className="text-[10px] text-on-surface-variant">{c.created_at}</span>
                                    </div>
                                    <p className="text-xs text-on-surface leading-relaxed">{c.content}</p>
                                </div>
                            </div>
                        ))}
                        <CommentBox postId={post.id} currentUser={currentUser} />
                    </div>
                )}
            </div>
        </div>
    );
}

// ─── Main Page ────────────────────────────────────────────────────────────────
export default function Hub({ posts, stories }: Props) {
    const { auth } = usePage<PageProps>().props;
    const currentUser: Author = {
        id: auth.user.id,
        name: auth.user.name,
        avatar: (auth.user as any).avatar ?? null,
        angkatan: (auth.user as any).angkatan ?? '-',
    };

    return (
        <AppLayout searchPlaceholder="Cari di Alumni Hub...">
            <Head title="Alumni Hub — SIMONAS" />

            <PageHeader
                title="Alumni Hub 🌐"
                subtitle="Terhubung, berbagi cerita, dan saling support sesama alumni"
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Alumni Hub' },
                ]}
            />

            <div className="grid grid-cols-1 xl:grid-cols-4 gap-6">

                {/* ── Feed column ── */}
                <div className="xl:col-span-3 space-y-4">
                    <StoriesBar
                        groups={stories}
                        currentUserId={auth.user.id}
                        currentUserName={auth.user.name}
                        currentUserAvatar={(auth.user as any).avatar ?? null}
                    />
                    <ComposePost currentUser={currentUser} />

                    {posts.data.length === 0 ? (
                        <div className="glass-card rounded-2xl p-16 flex flex-col items-center gap-4 text-center">
                            <Icon name="people" className="text-6xl opacity-20 text-on-surface-variant" />
                            <h3 className="font-display text-lg font-bold text-on-surface">Belum ada postingan</h3>
                            <p className="text-sm text-on-surface-variant">Jadilah yang pertama berbagi cerita!</p>
                        </div>
                    ) : (
                        posts.data.map(p => <PostCard key={p.id} post={p} currentUser={currentUser} />)
                    )}

                    {/* Pagination */}
                    {posts.last_page > 1 && (
                        <div className="flex justify-center gap-2 pt-4">
                            {Array.from({ length: posts.last_page }, (_, i) => i + 1).map(page => (
                                <Link key={page} href={`/alumni/hub?page=${page}`}
                                    className={`w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold transition-colors ${
                                        page === posts.current_page
                                            ? 'bg-emerald-500 text-white'
                                            : 'glass-card text-on-surface-variant hover:bg-white/60'
                                    }`}>
                                    {page}
                                </Link>
                            ))}
                        </div>
                    )}
                </div>

                {/* ── Sidebar ── */}
                <div>
                    <AlumniSidebar active="/alumni/hub" />
                </div>
            </div>
        </AppLayout>
    );
}
