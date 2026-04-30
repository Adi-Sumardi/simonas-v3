import { Head, Link } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { PageProps, AktivitasItem, PaginatedData } from '@/types';

interface AktivitasIndexProps extends PageProps {
    activities: PaginatedData<AktivitasItem>;
}

export default function AktivitasIndex({ activities }: AktivitasIndexProps) {
    return (
        <AppLayout searchPlaceholder="Cari aktivitas...">
            <Head title="Aktivitas Saya" />

            <PageHeader
                title="Aktivitas Saya"
                subtitle="Semua log aktivitas harian kamu."
                breadcrumbs={[
                    { label: 'Beranda', href: '/mahasiswa' },
                    { label: 'Aktivitas' },
                ]}
                actions={
                    <Link
                        href="/mahasiswa/aktivitas/create"
                        className="btn-primary flex items-center gap-2 text-sm"
                    >
                        <Icon name="add" className="text-xl" />
                        Log Aktivitas
                    </Link>
                }
            />

            <div className="glass-panel rounded-2xl overflow-hidden">
                {activities.data.length === 0 ? (
                    <EmptyState
                        icon="event_note"
                        title="Belum ada aktivitas"
                        description="Mulai catat aktivitas harianmu untuk melacak progress."
                        action={
                            <Link href="/mahasiswa/aktivitas/create" className="btn-primary text-sm px-6 py-2">
                                Log Aktivitas Pertama
                            </Link>
                        }
                        className="py-16"
                    />
                ) : (
                    <div className="divide-y divide-white/20">
                        {activities.data.map((act) => (
                            <div
                                key={act.id}
                                className="flex items-center gap-4 p-5 hover:bg-white/40 transition-colors"
                            >
                                <div className="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-primary-fixed flex items-center justify-center">
                                    {act.image_url ? (
                                        <img src={act.image_url} alt={act.jenis} className="w-full h-full object-cover" />
                                    ) : (
                                        <Icon name={act.icon ?? 'event_note'} className="text-xl text-primary-container" />
                                    )}
                                </div>
                                <div className="flex-1">
                                    <h4 className="font-body font-semibold text-on-surface">{act.jenis}</h4>
                                    <p className="text-body-sm text-on-surface-variant">{act.deskripsi}</p>
                                </div>
                                <span className="text-label-caps text-outline whitespace-nowrap">{act.created_at}</span>
                            </div>
                        ))}
                    </div>
                )}

                {/* Pagination */}
                {activities.last_page > 1 && (
                    <div className="p-5 border-t border-white/40 flex justify-between items-center">
                        <p className="text-body-sm text-on-surface-variant">
                            Menampilkan {activities.from}–{activities.to} dari {activities.total}
                        </p>
                        <div className="flex gap-2">
                            {activities.links.map((link, i) => (
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
            </div>
        </AppLayout>
    );
}
