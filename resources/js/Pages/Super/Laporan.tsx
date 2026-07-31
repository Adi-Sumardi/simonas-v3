import { Head } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Icon } from '@/Components/ui/Icon';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, BarChart, Bar } from 'recharts';

interface TrendData { bulan:string; shalat:number; hafalan:number; akademik:number; kegiatan:number }
interface AsramaPerf { asrama:string; avg:number; warga:number }
interface Props { trend:TrendData[]; asramaPerf:AsramaPerf[]; stats:Record<string,number> }

export default function Laporan({ trend, asramaPerf, stats }: Props) {
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
        </AppLayout>
    );
}
