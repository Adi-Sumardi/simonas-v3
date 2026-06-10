import { Head } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';

interface HafalanData { id:number; name:string; asrama:string; juz:number; target_juz:number; last_setoran:string; mentor:string; status:string }
interface Props { hafalan:HafalanData[]; juzDist:{ juz:string; count:number }[]; stats:Record<string,number> }

export default function Hafalan({ hafalan, juzDist, stats }: Props) {
    const [search, setSearch]  = useState('');
    const [asrama, setAsrama]  = useState('');
    const [sortBy, setSortBy]  = useState<'juz'|'name'>('juz');
    const [page, setPage]      = useState(1);
    const [perPage, setPerPage]= useState(10);

    const asramas = [...new Set(hafalan.map(h => h.asrama))];

    const filtered = useMemo(() => {
        setPage(1);
        const list = hafalan.filter(h =>
            (asrama === '' || h.asrama === asrama) &&
            (search === '' || h.name.toLowerCase().includes(search.toLowerCase()))
        );
        return list.sort((a, b) => sortBy === 'juz' ? b.juz - a.juz : a.name.localeCompare(b.name));
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [hafalan, search, asrama, sortBy]);

    const totalPages = Math.ceil(filtered.length / perPage);
    const paginated  = filtered.slice((page - 1) * perPage, page * perPage);
    const globalIdx  = (i: number) => (page - 1) * perPage + i + 1;

    const STATUS_STYLE: Record<string, string> = {
        baik:            'bg-emerald-100 text-emerald-700',
        on_track:        'bg-blue-100 text-blue-700',
        perlu_perhatian: 'bg-rose-100 text-rose-700',
    };

    return (
        <AppLayout>
            <Head title="Monitoring Hafalan" />
            <PageHeader title="Monitoring Hafalan" subtitle="Perkembangan hafalan Al-Qur'an seluruh warga"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Hafalan' }]} />

            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <StatCard icon="people"            label="TOTAL WARGA"     value={stats.total}            badgeColor="blue" />
                <StatCard icon="auto_stories"      label="RATA-RATA JUZ"    value={`${stats.avg_juz} Juz`} badgeColor="emerald" />
                <StatCard icon="workspace_premium" label="HAFIDZ 30 JUZ"    value={stats.hafidz}           badge="🏆" badgeColor="amber" />
                <StatCard icon="warning"           label="PERLU PERHATIAN"  value={stats.perlu_perhatian}  badgeColor="rose" />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div className="lg:col-span-2 glass-card rounded-2xl p-5">
                    <h3 className="font-bold text-on-surface mb-4">Distribusi Pencapaian Juz</h3>
                    <ResponsiveContainer width="100%" height={180}>
                        <BarChart data={juzDist} margin={{ top:0, right:0, bottom:0, left:-20 }}>
                            <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" vertical={false} />
                            <XAxis dataKey="juz" tick={{ fontSize:9, fill:'#94a3b8' }} />
                            <YAxis tick={{ fontSize:9, fill:'#94a3b8' }} />
                            <Tooltip contentStyle={{ borderRadius:10, fontSize:11 }} />
                            <Bar dataKey="count" fill="#10b981" radius={[4,4,0,0]} name="Warga" />
                        </BarChart>
                    </ResponsiveContainer>
                </div>
                <div className="glass-card rounded-2xl p-5 space-y-3">
                    <h3 className="font-bold text-on-surface">Rata-rata per Asrama</h3>
                    {asramas.map(a => {
                        const d = hafalan.filter(h => h.asrama === a);
                        const avg = Math.round(d.reduce((s,h) => s+h.juz, 0) / d.length);
                        return (
                            <div key={a}>
                                <div className="flex justify-between text-xs mb-1">
                                    <span className="font-semibold text-on-surface">{a}</span>
                                    <span className="font-bold text-emerald-600">~Juz {avg}</span>
                                </div>
                                <div className="h-2 bg-surface-container rounded-full overflow-hidden">
                                    <div className="h-full bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full" style={{ width:`${(avg/30)*100}%` }} />
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>

            {/* Filters */}
            <div className="flex flex-col sm:flex-row gap-3 mb-4">
                <div className="flex-1 flex items-center gap-2 glass-card rounded-xl px-3 py-2">
                    <Icon name="search" className="text-on-surface-variant text-lg" />
                    <input value={search} onChange={e => setSearch(e.target.value)} placeholder="Cari nama..." className="bg-transparent outline-none text-sm flex-1" />
                    {search && <button onClick={() => setSearch('')}><Icon name="close" className="text-sm text-outline" /></button>}
                </div>
                <select value={asrama} onChange={e => setAsrama(e.target.value)} className="glass-input text-sm py-2 min-w-[140px]">
                    <option value="">Semua Asrama</option>
                    {asramas.map(a => <option key={a} value={a}>{a}</option>)}
                </select>
                <select value={sortBy} onChange={e => setSortBy(e.target.value as 'juz'|'name')} className="glass-input text-sm py-2 min-w-[120px]">
                    <option value="juz">Sort: Juz ↓</option>
                    <option value="name">Sort: Nama A-Z</option>
                </select>
            </div>

            <div className="glass-card rounded-2xl overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="border-b border-white/40 bg-surface-container/30">
                            <tr>{['#','Nama','Asrama','Hafalan','Progress','Terakhir Setoran','Mentor','Status'].map(h=>(
                                <th key={h} className="text-left py-3 px-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant whitespace-nowrap">{h}</th>
                            ))}</tr>
                        </thead>
                        <tbody>
                            {paginated.map((h, i) => (
                                <tr key={h.id} className="border-b border-white/20 hover:bg-white/30 transition-colors">
                                    <td className="py-3 px-4 text-xs font-black text-on-surface-variant tabular-nums">{globalIdx(i)}</td>
                                    <td className="py-3 px-4 font-semibold text-on-surface">{h.name}</td>
                                    <td className="py-3 px-4"><span className="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-600 font-bold">{h.asrama}</span></td>
                                    <td className="py-3 px-4 font-black text-emerald-600">Juz {h.juz}</td>
                                    <td className="py-3 px-4 min-w-[120px]">
                                        <div className="flex items-center gap-2">
                                            <div className="flex-1 h-2 bg-surface-container rounded-full overflow-hidden">
                                                <div className="h-full bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full" style={{ width:`${(h.juz/30)*100}%` }} />
                                            </div>
                                            <span className="text-xs font-bold text-on-surface-variant">{Math.round((h.juz/30)*100)}%</span>
                                        </div>
                                    </td>
                                    <td className="py-3 px-4 text-xs text-on-surface-variant">{h.last_setoran}</td>
                                    <td className="py-3 px-4 text-xs text-on-surface-variant">{h.mentor}</td>
                                    <td className="py-3 px-4">
                                        <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${STATUS_STYLE[h.status] ?? 'bg-slate-100 text-slate-600'}`}>
                                            {h.status === 'baik' ? '✓ Baik' : h.status === 'on_track' ? '→ On Track' : '⚠ Perhatian'}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
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
