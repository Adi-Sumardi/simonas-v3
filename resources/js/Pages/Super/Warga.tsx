import { Head } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';

interface Warga {
    id: number; nim: string; name: string; asrama: string;
    angkatan: number; status: string; hafalan_juz: number;
    skor: number; mentor: string;
}
interface Props { warga: Warga[]; asramas: string[]; stats: Record<string, number> }

export default function Warga({ warga, asramas, stats }: Props) {
    const [search, setSearch]   = useState('');
    const [asrama, setAsrama]   = useState('');
    const [status, setStatus]   = useState('');
    const [page, setPage]       = useState(1);
    const [perPage, setPerPage] = useState(10);

    const filtered = useMemo(() => {
        setPage(1);
        return warga.filter(w =>
            (asrama === '' || w.asrama === asrama) &&
            (status === '' || w.status === status) &&
            (search === '' || w.name.toLowerCase().includes(search.toLowerCase()) || w.nim.includes(search))
        );
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [warga, search, asrama, status]);

    const totalPages = Math.ceil(filtered.length / perPage);
    const paginated  = filtered.slice((page - 1) * perPage, page * perPage);
    const globalIdx  = (i: number) => (page - 1) * perPage + i + 1;

    return (
        <AppLayout>
            <Head title="Manajemen Warga" />
            <PageHeader
                title="Manajemen Warga"
                subtitle="Data seluruh santri aktif dan nonaktif di pesantren"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Warga' }]}
                actions={
                    <button className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="person_add" className="text-lg" />
                        Tambah Warga
                    </button>
                }
            />

            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <StatCard icon="people"        label="TOTAL WARGA"  value={stats.total}    badgeColor="blue" />
                <StatCard icon="check_circle"  label="AKTIF"        value={stats.aktif}    badge="Aktif"    badgeColor="emerald" />
                <StatCard icon="cancel"        label="NONAKTIF"     value={stats.nonaktif} badge="Off"      badgeColor="rose" />
                <StatCard icon="analytics"     label="AVG. SKOR"    value={`${stats.avg_skor}/100`} badge="Semester ini" badgeColor="amber" />
            </div>

            <div className="glass-card rounded-2xl p-4 mb-4 flex flex-col sm:flex-row gap-3">
                <div className="flex-1 flex items-center gap-2 bg-white/60 rounded-xl px-3 py-2">
                    <Icon name="search" className="text-on-surface-variant text-lg" />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="Cari nama atau NIM..." className="bg-transparent outline-none text-sm flex-1" />
                    {search && <button onClick={() => setSearch('')}><Icon name="close" className="text-sm text-outline" /></button>}
                </div>
                <select value={asrama} onChange={e => setAsrama(e.target.value)} className="glass-input text-sm py-2 min-w-[140px]">
                    <option value="">Semua Asrama</option>
                    {asramas.map(a => <option key={a} value={a}>{a}</option>)}
                </select>
                <select value={status} onChange={e => setStatus(e.target.value)} className="glass-input text-sm py-2 min-w-[120px]">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <div className="glass-card rounded-2xl overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="border-b border-white/40 bg-surface-container/30">
                            <tr>
                                {['#','NIM','Nama','Asrama','Angkatan','Hafalan','Skor','Status','Mentor','Aksi'].map(h => (
                                    <th key={h} className="text-left py-3 px-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant whitespace-nowrap last:text-right">{h}</th>
                                ))}
                            </tr>
                        </thead>
                        <tbody>
                            {paginated.map((w, i) => (
                                <tr key={w.id} className="border-b border-white/20 hover:bg-white/30 transition-colors">
                                    <td className="py-3 px-4 text-xs font-black text-on-surface-variant tabular-nums">{globalIdx(i)}</td>
                                    <td className="py-3 px-4 font-mono text-xs text-on-surface-variant">{w.nim}</td>
                                    <td className="py-3 px-4">
                                        <div className="flex items-center gap-2">
                                            <div className="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-xs font-black text-white flex-shrink-0">
                                                {w.name.split(' ').map((n: string) => n[0]).slice(0,2).join('')}
                                            </div>
                                            <span className="font-semibold text-on-surface">{w.name}</span>
                                        </div>
                                    </td>
                                    <td className="py-3 px-4">
                                        <span className="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-600 font-bold">{w.asrama}</span>
                                    </td>
                                    <td className="py-3 px-4 text-on-surface-variant">{w.angkatan}</td>
                                    <td className="py-3 px-4">
                                        <div className="flex items-center gap-2">
                                            <div className="w-16 h-1.5 bg-surface-container rounded-full overflow-hidden">
                                                <div className="h-full bg-emerald-400 rounded-full" style={{ width: `${(w.hafalan_juz/30)*100}%` }} />
                                            </div>
                                            <span className="text-xs font-bold text-emerald-600">Juz {w.hafalan_juz}</span>
                                        </div>
                                    </td>
                                    <td className="py-3 px-4">
                                        <span className={`text-xs font-bold px-2 py-1 rounded-full ${w.skor >= 85 ? 'bg-emerald-50 text-emerald-600' : w.skor >= 70 ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600'}`}>
                                            {w.skor}
                                        </span>
                                    </td>
                                    <td className="py-3 px-4">
                                        <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${w.status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'}`}>
                                            {w.status === 'aktif' ? '● Aktif' : '○ Nonaktif'}
                                        </span>
                                    </td>
                                    <td className="py-3 px-4 text-xs text-on-surface-variant">{w.mentor}</td>
                                    <td className="py-3 px-4 text-right">
                                        <div className="flex justify-end gap-1">
                                            <button className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors">
                                                <Icon name="visibility" className="text-sm" />
                                            </button>
                                            <button className="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition-colors">
                                                <Icon name="edit" className="text-sm" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    {paginated.length === 0 && (
                        <div className="flex flex-col items-center py-12 gap-2 text-on-surface-variant">
                            <Icon name="search_off" className="text-4xl opacity-20" />
                            <p className="text-sm">Tidak ada data yang sesuai</p>
                        </div>
                    )}
                </div>
                <Pagination
                    currentPage={page} totalPages={totalPages}
                    totalItems={filtered.length} perPage={perPage}
                    onPageChange={setPage} onPerPageChange={setPerPage}
                />
            </div>
        </AppLayout>
    );
}
