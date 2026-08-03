import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';

interface Item {
    id: number;
    warga: { id: number; name: string; avatar: string | null; asrama: string | null };
    nama_beasiswa: string;
    nominal: number;
    tanggal_diajukan: string;
}
interface Props { items: Item[] }

export default function BeasiswaPending({ items }: Props) {
    const [processingId, setProcessingId] = useState<number | null>(null);

    function approve(id: number) {
        setProcessingId(id);
        router.patch(`/mentor/beasiswa/${id}/approve`, {}, {
            preserveScroll: true,
            onFinish: () => setProcessingId(null),
        });
    }

    function reject(id: number) {
        const catatan = prompt('Alasan menolak pengajuan ini (opsional):') ?? '';
        setProcessingId(id);
        router.patch(`/mentor/beasiswa/${id}/reject`, { catatan }, {
            preserveScroll: true,
            onFinish: () => setProcessingId(null),
        });
    }

    return (
        <AppLayout>
            <Head title="Persetujuan Beasiswa Yayasan" />
            <PageHeader
                title="Persetujuan Beasiswa Yayasan"
                subtitle="Warga bimbingan kamu yang diajukan untuk menerima Beasiswa Yayasan bulan ini"
                breadcrumbs={[{ label: 'Dashboard', href: '/mentor' }, { label: 'Beasiswa Yayasan' }]}
            />

            {items.length === 0 ? (
                <div className="glass-card rounded-2xl py-16 flex flex-col items-center gap-2 text-on-surface-variant">
                    <Icon name="volunteer_activism" className="text-4xl opacity-20" />
                    <p className="text-sm font-semibold">Tidak ada pengajuan yang menunggu persetujuan</p>
                </div>
            ) : (
                <div className="space-y-3">
                    {items.map(item => (
                        <div key={item.id} className="glass-card rounded-2xl p-4 flex items-center gap-4">
                            <div className="w-11 h-11 rounded-full bg-primary-container flex items-center justify-center text-sm font-black text-white flex-shrink-0">
                                {item.warga.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                            </div>
                            <div className="flex-1 min-w-0">
                                <p className="font-bold text-on-surface text-sm">{item.warga.name}</p>
                                <p className="text-xs text-on-surface-variant">
                                    {item.warga.asrama} &middot; {item.nama_beasiswa}
                                    {item.nominal ? ` · Rp${item.nominal.toLocaleString('id-ID')}` : ''}
                                </p>
                                <p className="text-[10px] text-on-surface-variant mt-0.5">Diajukan {item.tanggal_diajukan}</p>
                            </div>
                            <div className="flex items-center gap-2 flex-shrink-0">
                                <button
                                    onClick={() => reject(item.id)}
                                    disabled={processingId === item.id}
                                    className="px-3 py-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold transition-colors disabled:opacity-50"
                                >
                                    Tolak
                                </button>
                                <button
                                    onClick={() => approve(item.id)}
                                    disabled={processingId === item.id}
                                    className="px-3 py-2 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 text-xs font-bold transition-colors disabled:opacity-50 flex items-center gap-1"
                                >
                                    <Icon name="check" className="text-sm" /> Setujui
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </AppLayout>
    );
}
