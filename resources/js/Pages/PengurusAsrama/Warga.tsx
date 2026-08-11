import { Head, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { useDebounce } from '@/hooks/useDebounce';

interface WargaData {
    id: number;
    name: string;
    email: string;
    no_induk: string | null;
    asrama: string;
    status_warga: string;
    avatar: string | null;
    angkatan: string | null;
}

interface Props {
    warga: {
        data: WargaData[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    asrama: string;
    stats: { total: number; aktif: number; nonaktif: number };
    filters: { search?: string; status?: string; per_page?: number };
}

export default function PengurusWarga({ warga, asrama, stats, filters }: Props) {
    const [search, setSearch] = useState(filters.search || '');
    const [status, setStatus] = useState(filters.status || '');
    const debouncedSearch = useDebounce(search, 400);
    const [updatingId, setUpdatingId] = useState<number | null>(null);

    useEffect(() => {
        if (debouncedSearch !== (filters.search || '') || status !== (filters.status || '')) {
            router.get('/pengurus-asrama/warga', { search: debouncedSearch, status, page: 1 }, {
                preserveState: true, preserveScroll: true, replace: true,
            });
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [debouncedSearch, status]);

    function changeStatus(w: WargaData, newStatus: string) {
        if (newStatus === w.status_warga) return;
        setUpdatingId(w.id);
        router.patch(`/pengurus-asrama/warga/${w.id}/status`, { status_warga: newStatus }, {
            preserveState: true, preserveScroll: true,
            onFinish: () => setUpdatingId(null),
        });
    }

    return (
        <AppLayout searchPlaceholder="Cari warga...">
            <Head title="Warga Asrama" />

            <PageHeader
                title="Warga Asrama"
                subtitle={`Kelola status warga di ${asrama || 'asrama kamu'}.`}
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Warga Asrama' }]}
            />

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <StatCard icon="people"       label="TOTAL WARGA" value={stats.total}    badgeColor="blue" />
                <StatCard icon="check_circle" label="AKTIF"       value={stats.aktif}    badge="Aktif" badgeColor="emerald" />
                <StatCard icon="cancel"       label="NONAKTIF"    value={stats.nonaktif} badge="Off"   badgeColor="rose" />
            </div>

            <div className="glass-card rounded-2xl p-4 mb-6 flex flex-col md:flex-row gap-4">
                <div className="flex-1 relative">
                    <Icon name="search" className="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg" />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="Cari nama atau NIS..." className="glass-input w-full pl-10 text-sm" />
                </div>
                <select value={status} onChange={e => setStatus(e.target.value)} className="glass-input text-sm py-2 md:w-48">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <div className="glass-card rounded-2xl overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-white/40 bg-surface-container/10">
                                <th className="text-left px-5 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Warga</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">NIS</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Angkatan</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {warga.data.length === 0 ? (
                                <tr><td colSpan={4} className="px-5 py-10 text-center text-on-surface-variant">Belum ada warga.</td></tr>
                            ) : warga.data.map(w => (
                                <tr key={w.id} className="border-b border-white/20 hover:bg-white/20 transition-colors">
                                    <td className="px-5 py-3">
                                        <div className="flex items-center gap-3">
                                            <div className="w-9 h-9 rounded-full overflow-hidden bg-primary-fixed flex-shrink-0 flex items-center justify-center">
                                                {w.avatar
                                                    ? <img src={w.avatar} alt={w.name} className="w-full h-full object-cover" />
                                                    : <Icon name="person" className="text-primary-container text-lg" filled />
                                                }
                                            </div>
                                            <div>
                                                <p className="font-semibold text-on-surface">{w.name}</p>
                                                <p className="text-xs text-on-surface-variant">{w.email}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-4 py-3 text-on-surface-variant">{w.no_induk || '-'}</td>
                                    <td className="px-4 py-3 text-on-surface-variant">{w.angkatan || '-'}</td>
                                    <td className="px-4 py-3">
                                        <select
                                            value={w.status_warga}
                                            disabled={updatingId === w.id}
                                            onChange={e => changeStatus(w, e.target.value)}
                                            className={`text-xs font-bold px-2.5 py-1.5 rounded-full border-0 cursor-pointer disabled:opacity-50 ${
                                                w.status_warga === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'
                                            }`}
                                        >
                                            <option value="aktif">● Aktif</option>
                                            <option value="nonaktif">○ Nonaktif</option>
                                        </select>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-4">
                <Pagination
                    currentPage={warga.current_page}
                    totalPages={warga.last_page}
                    totalItems={warga.total}
                    perPage={warga.per_page}
                    onPageChange={(page) => router.get('/pengurus-asrama/warga', { search, status, page }, { preserveScroll: true })}
                    onPerPageChange={(perPage) => router.get('/pengurus-asrama/warga', { search, status, per_page: perPage, page: 1 }, { preserveScroll: true })}
                />
            </div>
        </AppLayout>
    );
}
