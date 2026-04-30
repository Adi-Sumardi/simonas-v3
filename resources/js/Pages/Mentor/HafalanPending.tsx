import { Head, Link } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { StatusPill } from '@/Components/ui/StatusPill';
import { PageProps, HafalanLog } from '@/types';

interface HafalanPendingProps extends PageProps {
    pending_logs: (HafalanLog & {
        mahasiswa_name: string;
        mahasiswa_nim: string;
        mahasiswa_avatar?: string;
        submitted_at: string;
    })[];
}

export default function HafalanPending({ pending_logs }: HafalanPendingProps) {
    return (
        <AppLayout searchPlaceholder="Cari hafalan pending...">
            <Head title="Hafalan Pending" />

            <PageHeader
                title="Hafalan Pending"
                subtitle="Antrian setoran hafalan yang menunggu penilaian dari kamu."
                breadcrumbs={[
                    { label: 'Beranda', href: '/mentor' },
                    { label: 'Hafalan Pending' },
                ]}
            />

            <div className="glass-panel rounded-2xl overflow-hidden">
                {pending_logs.length === 0 ? (
                    <EmptyState
                        icon="pending"
                        title="Tidak ada hafalan pending"
                        description="Semua setoran sudah dinilai. Bagus!"
                        className="py-16"
                    />
                ) : (
                    <div className="divide-y divide-white/20">
                        {pending_logs.map((log) => (
                            <div key={log.id} className="flex items-center justify-between p-5 hover:bg-white/40 transition-colors">
                                <div className="flex items-center gap-4">
                                    {log.mahasiswa_avatar ? (
                                        <img
                                            src={log.mahasiswa_avatar}
                                            alt={log.mahasiswa_name}
                                            className="w-12 h-12 rounded-full border-2 border-primary/10 object-cover"
                                        />
                                    ) : (
                                        <div className="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container">
                                            {log.mahasiswa_name.split(' ').map((n) => n[0]).slice(0, 2).join('')}
                                        </div>
                                    )}
                                    <div>
                                        <h4 className="font-body text-title-sm font-semibold text-on-surface">{log.mahasiswa_name}</h4>
                                        <p className="text-xs text-on-surface-variant">{log.mahasiswa_nim}</p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-8">
                                    <div className="text-center">
                                        <p className="font-bold text-on-surface">{log.surah}</p>
                                        <p className="text-xs text-on-surface-variant">Ayah {log.ayat_start}–{log.ayat_end}</p>
                                    </div>
                                    <p className="text-xs text-outline">{log.submitted_at}</p>
                                    <StatusPill variant="warning">PENDING</StatusPill>
                                    <Link
                                        href={`/mentor/hafalan/log/${log.id}`}
                                        className="bg-primary-container/10 text-primary-container px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-container hover:text-on-primary transition-colors flex items-center gap-2"
                                    >
                                        <Icon name="rate_review" className="text-base" /> Nilai
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
