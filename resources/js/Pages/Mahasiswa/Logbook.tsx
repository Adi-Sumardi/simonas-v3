import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';

interface LogItem {
    id: number;
    tanggal: string;
    topik: string;
    tujuan: string | null;
    hasil_diskusi: string | null;
    kendala: string | null;
    solusi: string | null;
    tindak_lanjut: string | null;
    mentor: { id: number; name: string; avatar: string | null };
}
interface Props {
    logs: { data: LogItem[]; current_page: number; last_page: number; total: number; per_page: number };
}

export default function MahasiswaLogbook({ logs }: Props) {
    const [expanded, setExpanded] = useState<number | null>(null);

    return (
        <AppLayout>
            <Head title="Log Book Mentoring" />
            <PageHeader
                title="Log Book Mentoring"
                subtitle="Riwayat catatan sesi mentoring kamu dengan mentor"
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Log Book' }]}
            />

            {logs.data.length === 0 ? (
                <div className="glass-card rounded-2xl py-16 flex flex-col items-center gap-2 text-on-surface-variant">
                    <Icon name="menu_book" className="text-4xl opacity-20" />
                    <p className="text-sm font-semibold">Belum ada catatan mentoring</p>
                </div>
            ) : (
                <div className="space-y-3 mb-6">
                    {logs.data.map(log => (
                        <div key={log.id} className="glass-card rounded-2xl p-4">
                            <div className="flex items-start justify-between gap-3">
                                <div className="flex items-start gap-3">
                                    <div className="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-xs font-black text-white flex-shrink-0">
                                        {log.mentor.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                    </div>
                                    <div>
                                        <p className="font-bold text-on-surface text-sm">{log.topik}</p>
                                        <p className="text-xs text-on-surface-variant">
                                            Mentor: {log.mentor.name} &middot; {new Date(log.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                                        </p>
                                    </div>
                                </div>
                                <button onClick={() => setExpanded(expanded === log.id ? null : log.id)} className="w-8 h-8 rounded-lg bg-surface-container text-on-surface-variant hover:bg-white/60 flex items-center justify-center transition-colors flex-shrink-0">
                                    <Icon name={expanded === log.id ? 'expand_less' : 'expand_more'} className="text-lg" />
                                </button>
                            </div>

                            {expanded === log.id && (
                                <div className="mt-4 pt-4 border-t border-white/40 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    <Field label="Tujuan" value={log.tujuan} />
                                    <Field label="Hasil Diskusi" value={log.hasil_diskusi} />
                                    <Field label="Kendala" value={log.kendala} />
                                    <Field label="Solusi" value={log.solusi} />
                                    <Field label="Tindak Lanjut" value={log.tindak_lanjut} className="sm:col-span-2" />
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            )}

            <Pagination
                currentPage={logs.current_page}
                totalPages={logs.last_page}
                totalItems={logs.total}
                perPage={logs.per_page}
                onPageChange={(page) => router.get('/mahasiswa/logbook', { page, per_page: logs.per_page }, { preserveState: true })}
                onPerPageChange={(perPage) => router.get('/mahasiswa/logbook', { per_page: perPage }, { preserveState: true })}
            />
        </AppLayout>
    );
}

function Field({ label, value, className }: { label: string; value: string | null; className?: string }) {
    return (
        <div className={className}>
            <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">{label}</p>
            <p className="text-on-surface whitespace-pre-wrap">{value || '-'}</p>
        </div>
    );
}
