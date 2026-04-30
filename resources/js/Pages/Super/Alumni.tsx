import { Head } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';

interface Alumni {
    id: number; name: string; angkatan: number; asrama: string;
    karir: string; kota: string; avatar: string | null;
    hafalan_juz: number; linkedin: string;
}
interface Props {
    alumni: Alumni[];
    asrama_list: string[];
    stats: { total: number; hafidz: number; angkatan_list: number[] };
}

const ASRAMA_COLOR: Record<string, { gradient: string; badge: string; text: string }> = {
    'Al-Farabi':  { gradient: 'from-blue-500 to-indigo-600',    badge: 'bg-blue-100 text-blue-700',       text: 'text-blue-600' },
    'Al-Ghazali': { gradient: 'from-emerald-500 to-teal-600',   badge: 'bg-emerald-100 text-emerald-700', text: 'text-emerald-600' },
    'Ibnu Sina':  { gradient: 'from-violet-500 to-purple-600',  badge: 'bg-violet-100 text-violet-700',   text: 'text-violet-600' },
    'Al-Kindi':   { gradient: 'from-amber-500 to-orange-500',   badge: 'bg-amber-100 text-amber-700',     text: 'text-amber-600' },
};

function initials(name: string) {
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
}

export default function Alumni({ alumni, asrama_list, stats }: Props) {
    const [search, setSearch]    = useState('');
    const [angkatan, setAngkatan]= useState('');
    const [asrama, setAsrama]    = useState('');
    const [page, setPage]        = useState(1);
    const [perPage, setPerPage]  = useState(12); // multiples of 4 for grid

    const filtered = useMemo(() => {
        setPage(1);
        return alumni.filter(a =>
            (angkatan === '' || String(a.angkatan) === angkatan) &&
            (asrama   === '' || a.asrama === asrama) &&
            (search   === '' || a.name.toLowerCase().includes(search.toLowerCase()) ||
                                a.karir.toLowerCase().includes(search.toLowerCase()))
        );
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [alumni, search, angkatan, asrama]);

    const totalPages = Math.ceil(filtered.length / perPage);
    const paginated  = filtered.slice((page - 1) * perPage, page * perPage);
    const globalIdx  = (i: number) => (page - 1) * perPage + i + 1;

    return (
        <AppLayout>
            <Head title="Data Alumni" />
            <PageHeader
                title="Data Alumni"
                subtitle="Direktori alumni, jejak karir, dan komunitas pesantren"
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Alumni' }]}
                actions={
                    <button className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="person_add" className="text-lg" />
                        Tambah Alumni
                    </button>
                }
            />

            {/* Stats */}
            <div className="grid grid-cols-3 gap-4 mb-6">
                {[
                    { label:'Total Alumni',  value: stats.total,                icon:'school',        bg:'bg-blue-100',    text:'text-blue-600' },
                    { label:'Hafidz 30 Juz', value: stats.hafidz,               icon:'auto_stories',  bg:'bg-emerald-100', text:'text-emerald-600' },
                    { label:'Angkatan',      value: stats.angkatan_list.length,  icon:'calendar_today',bg:'bg-violet-100',  text:'text-violet-600' },
                ].map(s => (
                    <div key={s.label} className="glass-card rounded-2xl p-5 flex items-center gap-4">
                        <div className={`w-12 h-12 ${s.bg} rounded-2xl flex items-center justify-center flex-shrink-0`}>
                            <Icon name={s.icon} className={`text-2xl ${s.text}`} filled />
                        </div>
                        <div>
                            <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">{s.label}</p>
                            <p className="font-display text-2xl font-bold text-on-surface">{s.value}</p>
                        </div>
                    </div>
                ))}
            </div>

            {/* Filters */}
            <div className="flex flex-col sm:flex-row gap-3 mb-4">
                <div className="flex-1 flex items-center gap-2 glass-card rounded-xl px-3 py-2.5">
                    <Icon name="search" className="text-on-surface-variant text-lg flex-shrink-0" />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="Cari nama atau profesi..." className="bg-transparent outline-none text-sm flex-1" />
                    {search && <button onClick={() => setSearch('')} className="flex-shrink-0"><Icon name="close" className="text-sm text-outline" /></button>}
                </div>
                <select value={asrama} onChange={e => setAsrama(e.target.value)} className="glass-input text-sm py-2.5 min-w-[150px]">
                    <option value="">Semua Asrama</option>
                    {asrama_list.map(a => <option key={a} value={a}>{a}</option>)}
                </select>
                <select value={angkatan} onChange={e => setAngkatan(e.target.value)} className="glass-input text-sm py-2.5 min-w-[145px]">
                    <option value="">Semua Angkatan</option>
                    {stats.angkatan_list.map(a => <option key={a} value={String(a)}>{a}</option>)}
                </select>
            </div>

            <p className="text-xs text-on-surface-variant mb-4 font-semibold">
                Menampilkan <span className="text-on-surface font-black">{filtered.length}</span> dari {alumni.length} alumni
            </p>

            {/* Card Grid */}
            <div className="glass-card rounded-2xl overflow-hidden">
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 p-5">
                    {paginated.map((a, i) => {
                        const color  = ASRAMA_COLOR[a.asrama] ?? ASRAMA_COLOR['Al-Farabi'];
                        const juzPct = Math.round((a.hafalan_juz / 30) * 100);
                        const idx    = globalIdx(i);

                        return (
                            <div key={a.id} className="bg-white/50 rounded-2xl overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-200 group border border-white/60">
                                <div className={`h-1.5 bg-gradient-to-r ${color.gradient}`} />
                                <div className="p-4 flex flex-col gap-3 flex-1">
                                    {/* Index + Avatar + Name */}
                                    <div className="flex items-center gap-3">
                                        <span className="text-[10px] font-black text-on-surface-variant bg-surface-container w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">{idx}</span>
                                        <div className={`w-10 h-10 rounded-xl bg-gradient-to-br ${color.gradient} flex items-center justify-center text-xs font-black text-white shadow flex-shrink-0`}>
                                            {initials(a.name)}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <h3 className="font-bold text-on-surface text-sm leading-tight truncate">{a.name}</h3>
                                            <span className={`inline-block text-[10px] font-black px-2 py-0.5 rounded-full ${color.badge}`}>{a.asrama}</span>
                                        </div>
                                        <span className="text-[10px] font-black text-on-surface-variant bg-surface-container px-1.5 py-1 rounded-lg flex-shrink-0">{a.angkatan}</span>
                                    </div>
                                    {/* Career */}
                                    <div className="space-y-1">
                                        <div className="flex items-start gap-1.5">
                                            <Icon name="work" className="text-xs text-on-surface-variant flex-shrink-0 mt-0.5" />
                                            <p className="text-xs font-semibold text-on-surface leading-snug">{a.karir}</p>
                                        </div>
                                        <div className="flex items-center gap-1.5">
                                            <Icon name="location_on" className="text-xs text-on-surface-variant flex-shrink-0" />
                                            <p className="text-xs text-on-surface-variant">{a.kota}</p>
                                        </div>
                                    </div>
                                    {/* Hafalan */}
                                    <div className="mt-auto space-y-1">
                                        <div className="flex items-center justify-between text-xs">
                                            <div className="flex items-center gap-1">
                                                <Icon name="auto_stories" className={`text-xs ${color.text}`} filled />
                                                <span className={`font-black ${color.text}`}>{a.hafalan_juz === 30 ? '🏆 Hafidz' : `Juz ${a.hafalan_juz}`}</span>
                                            </div>
                                            <span className="text-on-surface-variant font-bold">{juzPct}%</span>
                                        </div>
                                        <div className="h-1.5 bg-surface-container rounded-full overflow-hidden">
                                            <div className={`h-full rounded-full bg-gradient-to-r ${color.gradient}`} style={{ width: `${juzPct}%` }} />
                                        </div>
                                    </div>
                                    {/* Hover actions */}
                                    <div className="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button className="flex-1 py-1.5 rounded-xl text-[11px] font-bold bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors flex items-center justify-center gap-1">
                                            <Icon name="visibility" className="text-xs" /> Detail
                                        </button>
                                        <button className="w-8 h-8 rounded-xl bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors flex items-center justify-center flex-shrink-0">
                                            <Icon name="edit" className="text-xs" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </div>

                {filtered.length === 0 && (
                    <div className="flex flex-col items-center py-16 gap-3 text-on-surface-variant">
                        <Icon name="search_off" className="text-5xl opacity-20" />
                        <p className="text-sm font-semibold">Tidak ada alumni yang sesuai filter</p>
                        <button onClick={() => { setSearch(''); setAsrama(''); setAngkatan(''); }}
                            className="text-xs text-primary-container font-bold hover:underline">
                            Reset filter
                        </button>
                    </div>
                )}

                <Pagination
                    currentPage={page} totalPages={totalPages}
                    totalItems={filtered.length} perPage={perPage}
                    onPageChange={setPage} onPerPageChange={setPerPage}
                    perPageOptions={[8, 12, 24, 48]}
                />
            </div>
        </AppLayout>
    );
}
