import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { PageProps, Mentee, PaginatedData } from '@/types';

interface MenteesIndexProps extends PageProps {
    mentees: PaginatedData<Mentee>;
}

export default function MenteesIndex({ mentees }: MenteesIndexProps) {
    const [search, setSearch] = useState('');

    function handleSearch(e: React.FormEvent) {
        e.preventDefault();
        router.get('/mentor/mentees', { search }, { preserveState: true, replace: true });
    }

    return (
        <AppLayout searchPlaceholder="Cari warga bimbingan...">
            <Head title="Warga Bimbingan" />

            <PageHeader
                title="Warga Bimbingan"
                subtitle="Daftar santri yang berada dalam bimbinganmu."
                breadcrumbs={[
                    { label: 'Beranda', href: '/dashboard' },
                    { label: 'Warga Bimbingan' },
                ]}
            />

            {/* Search bar */}
            <form onSubmit={handleSearch} className="mb-4">
                <div className="relative w-full max-w-xs">
                    <Icon name="search" className="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg" />
                    <input
                        type="text"
                        value={search}
                        onChange={e => setSearch(e.target.value)}
                        placeholder="Nama atau NIM..."
                        className="glass-input pl-9 pr-4 py-2 text-sm w-full"
                    />
                </div>
            </form>

            <div className="glass-panel rounded-2xl overflow-hidden">
                {mentees.data.length === 0 ? (
                    <EmptyState
                        icon="people"
                        title="Belum ada warga bimbingan"
                        description="Santri akan muncul di sini setelah ditugaskan ke kamu."
                        className="py-16"
                    />
                ) : (
                    <>
                        {/* Table header */}
                        <div className="hidden md:grid grid-cols-6 px-6 py-4 border-b border-white/40 bg-blue-50/20 gap-4">
                            {['Santri', 'NIM', 'Asrama', 'Prodi', 'Skor', ''].map((h) => (
                                <div key={h} className="text-label-caps text-on-surface-variant">{h}</div>
                            ))}
                        </div>

                        <div className="divide-y divide-white/20">
                            {mentees.data.map((mentee) => (
                                <div
                                    key={mentee.id}
                                    className="flex flex-col md:grid md:grid-cols-6 gap-4 md:gap-0 items-start md:items-center p-5 hover:bg-white/40 transition-colors group"
                                >
                                    {/* Santri */}
                                    <div className="flex items-center gap-3">
                                        {mentee.avatar ? (
                                            <img src={mentee.avatar} alt={mentee.name} className="w-11 h-11 rounded-full object-cover border-2 border-white shadow-sm" />
                                        ) : (
                                            <div className="w-11 h-11 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container text-sm shadow-sm">
                                                {mentee.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                            </div>
                                        )}
                                        <div>
                                            <span className="font-body font-semibold text-on-surface text-sm block">{mentee.name}</span>
                                            {mentee.status === 'review_needed' && (
                                                <span className="inline-flex items-center gap-1 text-[9px] font-black text-amber-600 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded-full mt-0.5">
                                                    <Icon name="pending_actions" className="text-xs" />
                                                    Review Needed
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    <span className="text-body-sm text-on-surface-variant font-mono">{mentee.nim}</span>
                                    <span className="text-body-sm text-secondary">{mentee.asrama}</span>
                                    <span className="text-body-sm text-secondary truncate">{mentee.kelas}</span>

                                    {/* Score */}
                                    <div className="flex items-center gap-2">
                                        <span className={`font-black text-base ${
                                            mentee.score >= 85 ? 'text-emerald-600' :
                                            mentee.score >= 70 ? 'text-primary-container' :
                                            mentee.score > 0   ? 'text-amber-600' :
                                            'text-outline-variant'
                                        }`}>
                                            {mentee.score > 0 ? mentee.score : '—'}
                                        </span>
                                        {mentee.score > 0 && <span className="text-[10px] text-on-surface-variant">/100</span>}
                                    </div>

                                    <Link
                                        href={`/mentor/mentees/${mentee.id}`}
                                        className="flex items-center gap-1.5 text-primary-container text-sm font-semibold hover:underline group-hover:gap-2 transition-all"
                                    >
                                        Detail
                                        <Icon name="chevron_right" className="text-base" />
                                    </Link>
                                </div>
                            ))}
                        </div>

                        {/* Pagination */}
                        {mentees.last_page > 1 && (
                            <div className="p-5 border-t border-white/40 flex justify-between items-center">
                                <p className="text-body-sm text-on-surface-variant">
                                    {mentees.from}–{mentees.to} dari {mentees.total}
                                </p>
                                <div className="flex gap-2">
                                    {mentees.links.map((link, i) => (
                                        <Link
                                            key={i}
                                            href={link.url ?? '#'}
                                            preserveScroll
                                            className={`px-3 py-1.5 rounded-lg text-body-sm font-medium transition-colors ${
                                                link.active
                                                    ? 'bg-primary-container text-on-primary'
                                                    : link.url
                                                        ? 'text-on-surface hover:bg-white/50'
                                                        : 'text-outline-variant cursor-default'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </div>
                            </div>
                        )}
                    </>
                )}
            </div>
        </AppLayout>
    );
}
