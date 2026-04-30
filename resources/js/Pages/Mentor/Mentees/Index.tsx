import { Head, Link } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { PageProps, Mentee, PaginatedData } from '@/types';

interface MenteesIndexProps extends PageProps {
    mentees: PaginatedData<Mentee>;
}

export default function MenteesIndex({ mentees }: MenteesIndexProps) {
    return (
        <AppLayout searchPlaceholder="Cari warga bimbingan...">
            <Head title="Warga Bimbingan" />

            <PageHeader
                title="Warga Bimbingan"
                subtitle="Daftar santri yang berada dalam bimbinganmu."
                breadcrumbs={[
                    { label: 'Beranda', href: '/mentor' },
                    { label: 'Warga Bimbingan' },
                ]}
            />

            <div className="glass-panel rounded-2xl overflow-hidden">
                {mentees.data.length === 0 ? (
                    <EmptyState icon="people" title="Belum ada warga bimbingan" className="py-16" />
                ) : (
                    <>
                        {/* Table header */}
                        <div className="hidden md:grid grid-cols-5 px-6 py-4 border-b border-white/40 bg-blue-50/20">
                            {['Santri', 'NIM', 'Asrama', 'Kelas', 'Score', ''].map((h) => (
                                <div key={h} className="text-label-caps text-on-surface-variant">{h}</div>
                            ))}
                        </div>

                        <div className="divide-y divide-white/20">
                            {mentees.data.map((mentee) => (
                                <div
                                    key={mentee.id}
                                    className="flex flex-col md:grid md:grid-cols-5 gap-4 md:gap-0 items-start md:items-center p-5 hover:bg-white/40 transition-colors"
                                >
                                    {/* Santri */}
                                    <div className="flex items-center gap-3">
                                        {mentee.avatar ? (
                                            <img src={mentee.avatar} alt={mentee.name} className="w-10 h-10 rounded-full object-cover border border-white" />
                                        ) : (
                                            <div className="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container text-sm">
                                                {mentee.name.split(' ').map((n) => n[0]).slice(0, 2).join('')}
                                            </div>
                                        )}
                                        <span className="font-body font-semibold text-on-surface">{mentee.name}</span>
                                    </div>
                                    <span className="text-body-sm text-on-surface-variant">{mentee.nim}</span>
                                    <span className="text-body-sm text-secondary">{mentee.asrama}</span>
                                    <span className="text-body-sm text-secondary">{mentee.kelas}</span>
                                    <span className={`font-bold ${mentee.score >= 85 ? 'text-success' : mentee.score >= 70 ? 'text-primary-container' : 'text-error'}`}>
                                        {mentee.score}
                                    </span>
                                    <Link
                                        href={`/mentor/mentees/${mentee.id}`}
                                        className="flex items-center gap-1 text-primary-container text-sm font-semibold hover:underline"
                                    >
                                        Detail <Icon name="chevron_right" className="text-base" />
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
                                                link.active ? 'bg-primary-container text-on-primary' : link.url ? 'text-on-surface hover:bg-white/50' : 'text-outline-variant cursor-default'
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
