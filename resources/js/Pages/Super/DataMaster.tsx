import { Head, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { useDebounce } from '@/hooks/useDebounce';

interface DataItem {
    id: number;
    name: string;
    role: 'mahasiswa' | 'alumni';
    no_induk: string | null;
    asrama: string | null;
    angkatan: string | null;
    provinsi: string | null;
    kota: string | null;
    universitas: string | null;
    fakultas: string | null;
    prodi: string | null;
    avatar: string | null;
}
interface Props {
    data: { data: DataItem[]; current_page: number; last_page: number; total: number; per_page: number };
    filters: Record<string, string>;
    options: { provinsi: string[]; kota: string[]; universitas: string[]; prodi: string[] };
    stats: { total: number; mahasiswa: number; alumni: number };
}

export default function DataMaster({ data, filters, options, stats }: Props) {
    const [search, setSearch] = useState(filters.search ?? '');
    const [role, setRole] = useState(filters.role ?? '');
    const [provinsi, setProvinsi] = useState(filters.provinsi ?? '');
    const [kota, setKota] = useState(filters.kota ?? '');
    const [universitas, setUniversitas] = useState(filters.universitas ?? '');
    const [prodi, setProdi] = useState(filters.prodi ?? '');

    const debouncedSearch = useDebounce(search, 500);

    useEffect(() => {
        router.get('/super/data-master', {
            search: debouncedSearch, role, provinsi, kota, universitas, prodi, per_page: data.per_page,
        }, { preserveState: true, replace: true });
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [debouncedSearch, role, provinsi, kota, universitas, prodi]);

    function resetFilters() {
        setSearch(''); setRole(''); setProvinsi(''); setKota(''); setUniversitas(''); setProdi('');
    }

    return (
        <AppLayout>
            <Head title="Data Master" />
            <PageHeader
                title="Data Master"
                subtitle="Gabungan data mahasiswa & alumni, bisa difilter berdasarkan wilayah, kampus, dan jurusan"
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Data Master' }]}
            />

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <StatCard icon="storage" label="TOTAL DATA" value={stats.total} badgeColor="blue" />
                <StatCard icon="school" label="MAHASISWA" value={stats.mahasiswa} badgeColor="emerald" />
                <StatCard icon="workspace_premium" label="ALUMNI" value={stats.alumni} badgeColor="amber" />
            </div>

            <div className="glass-card rounded-2xl p-4 mb-6 space-y-3">
                <div className="flex items-center gap-3 bg-white/60 rounded-xl px-4 py-2.5 shadow-inner-soft">
                    <Icon name="search" className="text-on-surface-variant text-lg flex-shrink-0" />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="Cari nama atau NIM/NIA..." className="bg-transparent outline-none text-sm flex-1 w-full" />
                    {search && <button onClick={() => setSearch('')}><Icon name="close" className="text-sm text-outline" /></button>}
                </div>
                <div className="flex flex-wrap gap-2">
                    <select value={role} onChange={e => setRole(e.target.value)} className="glass-input text-sm py-2 min-w-[130px] flex-1">
                        <option value="">Semua Role</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="alumni">Alumni</option>
                    </select>
                    <select value={provinsi} onChange={e => setProvinsi(e.target.value)} className="glass-input text-sm py-2 min-w-[150px] flex-1">
                        <option value="">Semua Wilayah/Provinsi</option>
                        {options.provinsi.map(p => <option key={p} value={p}>{p}</option>)}
                    </select>
                    <select value={kota} onChange={e => setKota(e.target.value)} className="glass-input text-sm py-2 min-w-[150px] flex-1">
                        <option value="">Semua Kota/Kabupaten</option>
                        {options.kota.map(k => <option key={k} value={k}>{k}</option>)}
                    </select>
                    <select value={universitas} onChange={e => setUniversitas(e.target.value)} className="glass-input text-sm py-2 min-w-[170px] flex-1">
                        <option value="">Semua Asal Kampus</option>
                        {options.universitas.map(u => <option key={u} value={u}>{u}</option>)}
                    </select>
                    <select value={prodi} onChange={e => setProdi(e.target.value)} className="glass-input text-sm py-2 min-w-[170px] flex-1">
                        <option value="">Semua Jurusan/Prodi</option>
                        {options.prodi.map(p => <option key={p} value={p}>{p}</option>)}
                    </select>
                    <button onClick={resetFilters} className="text-xs font-bold px-3 py-2 rounded-lg bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors whitespace-nowrap">
                        Reset
                    </button>
                </div>
            </div>

            <div className="glass-card rounded-2xl overflow-hidden mb-6">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="border-b border-white/40 bg-surface-container/30">
                            <tr>
                                {['#', 'Nama', 'Role', 'No. Induk', 'Asrama', 'Wilayah', 'Kampus', 'Jurusan'].map(h => (
                                    <th key={h} className="text-left py-3 px-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant whitespace-nowrap">{h}</th>
                                ))}
                            </tr>
                        </thead>
                        <tbody>
                            {data.data.map((d, i) => (
                                <tr key={d.id} className="border-b border-white/20 hover:bg-white/30 transition-colors">
                                    <td className="py-3 px-4 text-xs font-black text-on-surface-variant tabular-nums">
                                        {(data.current_page - 1) * data.per_page + i + 1}
                                    </td>
                                    <td className="py-3 px-4">
                                        <div className="flex items-center gap-2">
                                            <div className="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-xs font-black text-white flex-shrink-0">
                                                {d.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                            </div>
                                            <span className="font-semibold text-on-surface">{d.name}</span>
                                        </div>
                                    </td>
                                    <td className="py-3 px-4">
                                        <span className={`text-xs px-2 py-1 rounded-full font-bold ${d.role === 'alumni' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600'}`}>
                                            {d.role === 'alumni' ? 'Alumni' : 'Mahasiswa'}
                                        </span>
                                    </td>
                                    <td className="py-3 px-4 font-mono text-xs text-on-surface-variant">{d.no_induk ?? '-'}</td>
                                    <td className="py-3 px-4 text-on-surface-variant">{d.asrama ?? '-'}</td>
                                    <td className="py-3 px-4 text-on-surface-variant">{[d.kota, d.provinsi].filter(Boolean).join(', ') || '-'}</td>
                                    <td className="py-3 px-4 text-on-surface-variant">{d.universitas ?? '-'}</td>
                                    <td className="py-3 px-4 text-on-surface-variant">{d.prodi ?? '-'}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {data.data.length === 0 && (
                <div className="glass-card rounded-2xl py-12 flex flex-col items-center gap-2 text-on-surface-variant mb-6">
                    <Icon name="search_off" className="text-4xl opacity-20" />
                    <p className="text-sm font-semibold">Tidak ada data yang sesuai filter</p>
                </div>
            )}

            <Pagination
                currentPage={data.current_page}
                totalPages={data.last_page}
                totalItems={data.total}
                perPage={data.per_page}
                onPageChange={(page) => router.get('/super/data-master', { ...filters, page }, { preserveState: true })}
                onPerPageChange={(perPage) => router.get('/super/data-master', { ...filters, per_page: perPage }, { preserveState: true })}
            />
        </AppLayout>
    );
}
