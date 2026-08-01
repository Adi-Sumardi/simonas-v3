import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, BarChart, Bar } from 'recharts';

interface TrendData { bulan:string; shalat:number; hafalan:number; akademik:number; kegiatan:number }
interface AsramaPerf { asrama:string; avg:number; warga:number }
interface RekapItem {
    id: number; name: string; asrama: string;
    akademik: number; leadership: number; karakter: number; kreatif: number; total: number;
}
interface Rekap { items: RekapItem[]; total: number; page: number; per_page: number; last_page: number }
interface RekapFilters { asrama: string | null; from: string; to: string }
interface Props {
    trend: TrendData[];
    asramaPerf: AsramaPerf[];
    stats: Record<string, number>;
    rekap: Rekap;
    rekapFilters: RekapFilters;
    asramas: string[];
}

export default function Laporan({ trend, asramaPerf, stats, rekap, rekapFilters, asramas }: Props) {
    const [rekapAsrama, setRekapAsrama] = useState(rekapFilters.asrama ?? '');
    const [rekapFrom, setRekapFrom]     = useState(rekapFilters.from);
    const [rekapTo, setRekapTo]         = useState(rekapFilters.to);

    function applyRekapFilter(overrides: Partial<{ asrama: string; from: string; to: string; page: number }> = {}) {
        router.get('/super/laporan', {
            rekap_asrama: overrides.asrama ?? rekapAsrama,
            rekap_from:   overrides.from   ?? rekapFrom,
            rekap_to:     overrides.to     ?? rekapTo,
            rekap_page:   overrides.page   ?? 1,
            rekap_per_page: rekap.per_page,
        }, { preserveScroll: true, preserveState: true, only: ['rekap', 'rekapFilters'] });
    }

    function resetRekapFilter() {
        const from = new Date(new Date().getFullYear(), 0, 1).toISOString().slice(0, 10);
        const to   = new Date().toISOString().slice(0, 10);
        setRekapAsrama('');
        setRekapFrom(from);
        setRekapTo(to);
        applyRekapFilter({ asrama: '', from, to, page: 1 });
    }
    return (
        <AppLayout>
            <Head title="Laporan Eksekutif" />
            <PageHeader title="Laporan Eksekutif" subtitle="Analitik performa, tren perkembangan, dan ringkasan data Asrama"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Laporan' }]}
                actions={
                    <button className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="download" className="text-lg" />
                        Export PDF
                    </button>
                }
            />

            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <StatCard icon="people"           label="TOTAL WARGA"    value={stats.total_warga}             badgeColor="blue" />
                <StatCard icon="analytics"        label="AVG. SKOR"      value={`${stats.avg_skor}/100`}       badgeColor="emerald" />
                <StatCard icon="event"            label="KEGIATAN"       value={stats.total_kegiatan}          badgeColor="amber" />
                <StatCard icon="check_circle"     label="WARGA AKTIF"   value={`${stats.persen_aktif}%`}      badge="Aktif" badgeColor="purple" />
            </div>

            {/* Line chart — trend */}
            <div className="glass-card rounded-2xl p-5 mb-6">
                <h3 className="font-bold text-on-surface mb-1">Tren Performa Bulanan</h3>
                <p className="text-xs text-on-surface-variant mb-5">Rata-rata skor per dimensi sepanjang tahun</p>
                <ResponsiveContainer width="100%" height={280}>
                    <LineChart data={trend} margin={{ top:5, right:20, bottom:5, left:-15 }}>
                        <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
                        <XAxis dataKey="bulan" tick={{ fontSize:10, fill:'#94a3b8' }} />
                        <YAxis domain={[50,100]} tick={{ fontSize:10, fill:'#94a3b8' }} />
                        <Tooltip contentStyle={{ borderRadius:12, fontSize:11 }} />
                        <Legend wrapperStyle={{ fontSize:11, fontWeight:700 }} />
                        <Line type="monotone" dataKey="shalat"   stroke="#2563eb" strokeWidth={2.5} dot={false} name="Shalat" />
                        <Line type="monotone" dataKey="hafalan"  stroke="#10b981" strokeWidth={2.5} dot={false} name="Hafalan" />
                        <Line type="monotone" dataKey="akademik" stroke="#f59e0b" strokeWidth={2.5} dot={false} name="Akademik" />
                        <Line type="monotone" dataKey="kegiatan" stroke="#8b5cf6" strokeWidth={2.5} dot={false} name="Kegiatan" />
                    </LineChart>
                </ResponsiveContainer>
            </div>

            {/* Bar chart — asrama performance */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="glass-card rounded-2xl p-5">
                    <h3 className="font-bold text-on-surface mb-1">Performa per Asrama</h3>
                    <p className="text-xs text-on-surface-variant mb-4">Rata-rata skor keseluruhan</p>
                    <ResponsiveContainer width="100%" height={200}>
                        <BarChart data={asramaPerf} margin={{ top:0, right:0, bottom:0, left:-20 }}>
                            <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" vertical={false} />
                            <XAxis dataKey="asrama" tick={{ fontSize:10, fill:'#94a3b8' }} />
                            <YAxis domain={[60,100]} tick={{ fontSize:10, fill:'#94a3b8' }} />
                            <Tooltip contentStyle={{ borderRadius:10, fontSize:11 }} />
                            <Bar dataKey="avg" fill="#2563eb" radius={[6,6,0,0]} name="Avg Skor" />
                        </BarChart>
                    </ResponsiveContainer>
                </div>

                {/* Asrama cards */}
                <div className="glass-card rounded-2xl p-5">
                    <h3 className="font-bold text-on-surface mb-4">Ringkasan Asrama</h3>
                    <div className="space-y-3">
                        {asramaPerf.sort((a,b) => b.avg-a.avg).map((a, i) => (
                            <div key={a.asrama} className="flex items-center gap-3">
                                <div className={`w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black text-white flex-shrink-0 ${
                                    i===0?'bg-amber-400':i===1?'bg-slate-400':i===2?'bg-amber-700':'bg-blue-400'
                                }`}>{i+1}</div>
                                <div className="flex-1">
                                    <div className="flex justify-between text-xs mb-1">
                                        <span className="font-semibold text-on-surface">{a.asrama}</span>
                                        <span className="font-black text-primary-container">{a.avg}/100</span>
                                    </div>
                                    <div className="h-2 bg-surface-container rounded-full overflow-hidden">
                                        <div className="h-full bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full" style={{ width:`${a.avg}%` }} />
                                    </div>
                                    <p className="text-[10px] text-on-surface-variant mt-0.5">{a.warga} warga</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* Rekap Aktivitas Warga */}
            <div className="glass-card rounded-2xl p-5 mt-6">
                <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-5">
                    <div>
                        <h3 className="font-bold text-on-surface mb-1">Rekap Aktivitas Warga</h3>
                        <p className="text-xs text-on-surface-variant">Jumlah aktivitas per dimensi, bisa difilter per asrama & rentang tanggal</p>
                    </div>
                    <div className="flex flex-wrap items-center gap-2">
                        <select
                            value={rekapAsrama}
                            onChange={e => { setRekapAsrama(e.target.value); applyRekapFilter({ asrama: e.target.value, page: 1 }); }}
                            className="glass-input text-xs py-2"
                        >
                            <option value="">Semua Asrama</option>
                            {asramas.map(a => <option key={a} value={a}>{a}</option>)}
                        </select>
                        <input
                            type="date"
                            value={rekapFrom}
                            onChange={e => { setRekapFrom(e.target.value); applyRekapFilter({ from: e.target.value, page: 1 }); }}
                            className="glass-input text-xs py-2"
                        />
                        <span className="text-xs text-on-surface-variant">s/d</span>
                        <input
                            type="date"
                            value={rekapTo}
                            onChange={e => { setRekapTo(e.target.value); applyRekapFilter({ to: e.target.value, page: 1 }); }}
                            className="glass-input text-xs py-2"
                        />
                        <button onClick={resetRekapFilter} className="text-xs font-bold px-3 py-2 rounded-lg bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors whitespace-nowrap">
                            Reset
                        </button>
                    </div>
                </div>

                {rekap.items.length === 0 ? (
                    <div className="text-center py-10 text-on-surface-variant text-sm">Tidak ada data aktivitas untuk filter ini.</div>
                ) : (
                    <div className="overflow-x-auto -mx-5">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b border-white/40">
                                    <th className="text-left px-5 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">#</th>
                                    <th className="text-left px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Nama</th>
                                    <th className="text-left px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Asrama</th>
                                    <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Akademik</th>
                                    <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Leadership</th>
                                    <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Karakter Islami</th>
                                    <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Kreativitas</th>
                                    <th className="text-center px-5 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {rekap.items.map((r, i) => (
                                    <tr key={r.id} className="border-b border-white/20 hover:bg-white/20 transition-colors">
                                        <td className="px-5 py-3 text-xs font-black text-on-surface-variant tabular-nums">{(rekap.page - 1) * rekap.per_page + i + 1}</td>
                                        <td className="px-4 py-3 font-semibold text-on-surface">{r.name}</td>
                                        <td className="px-4 py-3 text-on-surface-variant">{r.asrama}</td>
                                        <td className="px-4 py-3 text-center">{r.akademik}</td>
                                        <td className="px-4 py-3 text-center">{r.leadership}</td>
                                        <td className="px-4 py-3 text-center">{r.karakter}</td>
                                        <td className="px-4 py-3 text-center">{r.kreatif}</td>
                                        <td className="px-5 py-3 text-center font-black text-primary-container">{r.total}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}

                <div className="mt-4">
                    <Pagination
                        currentPage={rekap.page}
                        totalPages={rekap.last_page}
                        totalItems={rekap.total}
                        perPage={rekap.per_page}
                        onPageChange={(page) => applyRekapFilter({ page })}
                        onPerPageChange={(perPage) => {
                            router.get('/super/laporan', {
                                rekap_asrama: rekapAsrama, rekap_from: rekapFrom, rekap_to: rekapTo,
                                rekap_page: 1, rekap_per_page: perPage,
                            }, { preserveScroll: true, preserveState: true, only: ['rekap', 'rekapFilters'] });
                        }}
                    />
                </div>
            </div>
        </AppLayout>
    );
}
