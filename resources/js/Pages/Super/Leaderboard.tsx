import { Head } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';

interface Entry { rank:number; id:number; name:string; asrama:string; points:number; shalat:number; hafalan:number; akademik:number; badge:string|null }
interface Props { entries:Entry[]; asramas:string[]; stats:{ top_asrama:string; avg_points:number; total:number } }

export default function Leaderboard({ entries, asramas, stats }: Props) {
    const [filterAsrama, setFilter] = useState('');
    const [page, setPage]           = useState(1);
    const [perPage, setPerPage]     = useState(10);

    const filtered = useMemo(() => {
        setPage(1);
        return entries
            .filter(e => filterAsrama === '' || e.asrama === filterAsrama)
            .map((e, i) => ({ ...e, displayRank: i + 1 }));
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [entries, filterAsrama]);

    const top3       = entries.slice(0, 3);
    const totalPages = Math.ceil(filtered.length / perPage);
    const paginated  = filtered.slice((page - 1) * perPage, page * perPage);

    const PODIUM_STYLE: Record<number, string> = {
        1: 'bg-gradient-to-b from-amber-400 to-yellow-500 text-white shadow-amber-300',
        2: 'bg-gradient-to-b from-slate-300 to-slate-400 text-white shadow-slate-200',
        3: 'bg-gradient-to-b from-amber-600 to-amber-700 text-white shadow-amber-400',
    };

    return (
        <AppLayout>
            <Head title="Leaderboard" />
            <PageHeader title="Leaderboard Santri" subtitle="Peringkat berdasarkan akumulasi poin dari semua dimensi"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Leaderboard' }]} />

            {/* Podium top 3 */}
            {top3.length >= 3 && (
                <div className="flex items-end justify-center gap-4 mb-8 px-4">
                    {[top3[1], top3[0], top3[2]].map((e, i) => {
                        const pos = i === 1 ? 1 : i === 0 ? 2 : 3;
                        const height = pos === 1 ? 'h-32' : pos === 2 ? 'h-24' : 'h-20';
                        return (
                            <div key={e.id} className="flex flex-col items-center gap-2 flex-1 max-w-[140px]">
                                <div className="text-2xl">{e.badge}</div>
                                <div className="w-12 h-12 rounded-full bg-white border-2 border-white/60 shadow-lg flex items-center justify-center font-black text-sm text-primary-container">
                                    {e.name.split(' ').map((n:string)=>n[0]).slice(0,2).join('')}
                                </div>
                                <p className="text-xs font-bold text-on-surface text-center line-clamp-1">{e.name}</p>
                                <div className={`w-full ${height} ${PODIUM_STYLE[pos]} rounded-t-2xl flex items-center justify-center shadow-lg`}>
                                    <span className="font-black text-lg">{pos}</span>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}

            {/* Stats */}
            <div className="grid grid-cols-3 gap-4 mb-6">
                <div className="glass-card rounded-2xl p-4 text-center">
                    <p className="text-xl font-black text-primary-container">{stats.total}</p>
                    <p className="text-[10px] font-black uppercase tracking-wider text-on-surface-variant mt-1">Total Peserta</p>
                </div>
                <div className="glass-card rounded-2xl p-4 text-center">
                    <p className="text-xl font-black text-amber-600">{stats.avg_points}</p>
                    <p className="text-[10px] font-black uppercase tracking-wider text-on-surface-variant mt-1">Rata-rata Poin</p>
                </div>
                <div className="glass-card rounded-2xl p-4 text-center">
                    <p className="text-xl font-black text-emerald-600">{stats.top_asrama}</p>
                    <p className="text-[10px] font-black uppercase tracking-wider text-on-surface-variant mt-1">Asrama Terbaik</p>
                </div>
            </div>

            {/* Filter */}
            <div className="flex gap-2 mb-4 flex-wrap">
                <button onClick={() => setFilter('')} className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${filterAsrama==='' ? 'bg-on-surface text-white' : 'glass-card text-on-surface-variant hover:bg-white/60'}`}>Semua</button>
                {asramas.map(a => (
                    <button key={a} onClick={() => setFilter(a)} className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${filterAsrama===a ? 'bg-primary-container text-white' : 'glass-card text-on-surface-variant hover:bg-white/60'}`}>{a}</button>
                ))}
            </div>

            {/* Table */}
            <div className="glass-card rounded-2xl overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="border-b border-white/40 bg-surface-container/30">
                            <tr>{['#','Rank','Santri','Asrama','Poin','Shalat','Hafalan','Akademik'].map(h=>(
                                <th key={h} className="text-left py-3 px-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">{h}</th>
                            ))}</tr>
                        </thead>
                        <tbody>
                            {paginated.map((e, i) => {
                                const absIdx = (page - 1) * perPage + i + 1;
                                return (
                                    <tr key={e.id} className={`border-b border-white/20 hover:bg-white/30 transition-colors ${e.displayRank <= 3 ? 'bg-amber-50/30' : ''}`}>
                                        <td className="py-3 px-4 text-xs font-black text-on-surface-variant tabular-nums">{absIdx}</td>
                                        <td className="py-3 px-4 font-black text-lg">
                                            {e.badge ?? <span className="text-sm text-on-surface-variant">#{e.displayRank}</span>}
                                        </td>
                                        <td className="py-3 px-4">
                                            <div className="flex items-center gap-2">
                                                <div className="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-xs font-black text-white">
                                                    {e.name.split(' ').map((n:string)=>n[0]).slice(0,2).join('')}
                                                </div>
                                                <span className="font-semibold text-on-surface">{e.name}</span>
                                            </div>
                                        </td>
                                        <td className="py-3 px-4"><span className="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-600 font-bold">{e.asrama}</span></td>
                                        <td className="py-3 px-4 font-black text-primary-container">{e.points.toLocaleString()}</td>
                                        {[e.shalat, e.hafalan, e.akademik].map((v, vi) => (
                                            <td key={vi} className="py-3 px-4">
                                                <span className={`text-xs font-bold px-2 py-1 rounded-full ${v>=85?'bg-emerald-50 text-emerald-600':v>=70?'bg-amber-50 text-amber-600':'bg-rose-50 text-rose-600'}`}>{v}</span>
                                            </td>
                                        ))}
                                    </tr>
                                );
                            })}
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
