import { AppLayout } from '@/Layouts/AppLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { Icon } from '@/Components/ui/Icon';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { ResponsiveContainer, RadarChart, PolarGrid, PolarAngleAxis, PolarRadiusAxis, Radar, Tooltip } from 'recharts';

interface Warga {
    id: number;
    name: string;
    email: string;
    avatar?: string | null;
    no_induk: string | null;
    asrama: string | null;
    status_warga: string | null;
    tgl_masuk: string | null;
    angkatan: string | null;
    no_telp: string | null;
    alamat: string | null;
    universitas: string | null;
    fakultas: string | null;
    prodi: string | null;
}

interface ActivityRow {
    id: number;
    kegiatan: string | null;
    komponen: string | null;
    waktu: string | null;
    nilai: string | null;
    nama_penilai: string | null;
    keterangan: string | null;
    tempat: string | null;
    file: string | null;
    file_name: string | null;
}

function isPdfName(name?: string | null): boolean {
    return !!name && name.toLowerCase().endsWith('.pdf');
}

interface Ipk {
    id: number;
    semester: string;
    ip: string;
}

interface Hafalan {
    current_juz: number;
    total_ayah_completed: number;
    streak_days: number;
}

interface RadarScore { subject: string; value: number; fullMark: number }

interface Props {
    warga: Warga;
    stats: { akademik: number; leadership: number; karakter: number; kreatif: number; points: number };
    radarScores: RadarScore[];
    radarRange: { from: string; to: string };
    ipks: Ipk[];
    hafalan: Hafalan | null;
    akademiks: ActivityRow[];
    leaderships: ActivityRow[];
    karakters: ActivityRow[];
    kreatifs: ActivityRow[];
}

function ActivityTable({ title, rows, onView }: { title: string; rows: ActivityRow[]; onView: (row: ActivityRow) => void }) {
    return (
        <div className="glass-card rounded-2xl overflow-hidden">
            <div className="px-6 py-4 border-b border-white/40 bg-surface-container/20">
                <h3 className="font-bold text-on-surface text-sm">{title}</h3>
                <p className="text-[10px] text-on-surface-variant mt-0.5">10 data terakhir</p>
            </div>
            {rows.length === 0 ? (
                <p className="text-xs text-on-surface-variant text-center py-6">Belum ada data.</p>
            ) : (
                <div className="overflow-x-auto">
                    <table className="w-full text-xs">
                        <thead>
                            <tr className="border-b border-white/30">
                                <th className="text-left px-4 py-2 font-black text-on-surface-variant uppercase tracking-wider">Kegiatan</th>
                                <th className="text-left px-4 py-2 font-black text-on-surface-variant uppercase tracking-wider">Komponen</th>
                                <th className="text-left px-4 py-2 font-black text-on-surface-variant uppercase tracking-wider">Waktu</th>
                                <th className="px-4 py-2" />
                            </tr>
                        </thead>
                        <tbody>
                            {rows.map(r => (
                                <tr key={r.id} className="border-b border-white/20">
                                    <td className="px-4 py-2 font-semibold text-on-surface">{r.kegiatan || '-'}</td>
                                    <td className="px-4 py-2 text-on-surface-variant">{r.komponen || '-'}</td>
                                    <td className="px-4 py-2 text-on-surface-variant">{r.waktu || '-'}</td>
                                    <td className="px-4 py-2 text-right">
                                        <button onClick={() => onView(r)} className="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-primary-container/10 text-primary-container font-bold hover:bg-primary-container/20 transition-colors">
                                            <Icon name="visibility" className="text-xs" /> Lihat
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
        </div>
    );
}

export default function WargaDetail({ warga, stats, radarScores, radarRange, ipks, hafalan, akademiks, leaderships, karakters, kreatifs }: Props) {
    const [radarFrom, setRadarFrom] = useState(radarRange.from);
    const [radarTo, setRadarTo]     = useState(radarRange.to);
    const [viewingActivity, setViewingActivity] = useState<ActivityRow | null>(null);

    function applyRadarRange(from: string, to: string) {
        router.get(`/super/warga/${warga.id}`, { radar_from: from, radar_to: to }, { preserveScroll: true, preserveState: true, only: ['radarScores', 'radarRange'] });
    }

    function setThisMonth() {
        const now = new Date();
        const from = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().slice(0, 10);
        const to   = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().slice(0, 10);
        setRadarFrom(from); setRadarTo(to);
        applyRadarRange(from, to);
    }

    return (
        <AppLayout>
            <Head title={`Detail Warga - ${warga.name}`} />

            <div className="mb-6">
                <Link href="/super/warga" className="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary-container transition-colors">
                    <Icon name="arrow_back" className="text-lg" />
                    Kembali ke Daftar Warga
                </Link>
            </div>

            <PageHeader
                title={warga.name}
                subtitle={`${warga.no_induk || 'Tanpa NIM'} · ${warga.asrama || 'Belum ada asrama'}`}
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Warga', href: '/super/warga' }, { label: warga.name }]}
                actions={
                    <Link href={`/super/warga/${warga.id}/edit`} className="flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-container text-white text-sm font-bold hover:opacity-90 transition-opacity">
                        <Icon name="edit" className="text-sm" /> Edit Warga
                    </Link>
                }
            />

            {/* Profile card */}
            <div className="glass-card rounded-2xl p-6 mb-6 flex flex-col sm:flex-row gap-6 items-start">
                <div className="w-20 h-20 rounded-2xl bg-primary-container flex items-center justify-center text-2xl font-black text-white flex-shrink-0">
                    {warga.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                </div>
                <div className="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 w-full">
                    <div><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Email</p><p className="text-sm font-semibold text-on-surface">{warga.email}</p></div>
                    <div><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">No. Induk</p><p className="text-sm font-semibold text-on-surface">{warga.no_induk || '-'}</p></div>
                    <div><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Status</p>
                        <span className={`inline-block mt-0.5 text-xs font-bold px-2.5 py-0.5 rounded-full ${warga.status_warga === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'}`}>
                            {warga.status_warga === 'aktif' ? 'Aktif' : 'Nonaktif'}
                        </span>
                    </div>
                    <div><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Angkatan</p><p className="text-sm font-semibold text-on-surface">{warga.angkatan || '-'}</p></div>
                    <div><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">No. Telp</p><p className="text-sm font-semibold text-on-surface">{warga.no_telp || '-'}</p></div>
                    <div><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Tgl Masuk</p><p className="text-sm font-semibold text-on-surface">{warga.tgl_masuk || '-'}</p></div>
                    <div><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Universitas</p><p className="text-sm font-semibold text-on-surface">{warga.universitas || '-'}</p></div>
                    <div><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Prodi</p><p className="text-sm font-semibold text-on-surface">{warga.prodi || '-'}</p></div>
                    <div className="sm:col-span-2 lg:col-span-1"><p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Alamat</p><p className="text-sm font-semibold text-on-surface">{warga.alamat || '-'}</p></div>
                </div>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <StatCard label="Total Poin" value={stats.points} icon="military_tech" badgeColor="amber" />
                <StatCard label="Akademik" value={stats.akademik} icon="school" badgeColor="blue" />
                <StatCard label="Leadership" value={stats.leadership} icon="groups" badgeColor="rose" />
                <StatCard label="Karakter" value={stats.karakter} icon="favorite" badgeColor="emerald" />
                <StatCard label="Kreativitas" value={stats.kreatif} icon="lightbulb" badgeColor="purple" />
            </div>

            {/* Radar profile */}
            <div className="glass-card rounded-2xl p-6 mb-6">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-1">
                    <div>
                        <h3 className="font-bold text-on-surface text-sm">Profil Penilaian</h3>
                        <p className="text-xs text-on-surface-variant mt-0.5">6 dimensi, skala 0-100 (20 aktivitas dalam rentang = 100)</p>
                    </div>
                    <div className="flex items-center gap-2">
                        <input
                            type="date"
                            value={radarFrom}
                            onChange={e => { setRadarFrom(e.target.value); applyRadarRange(e.target.value, radarTo); }}
                            className="glass-input text-xs py-1.5"
                        />
                        <span className="text-xs text-on-surface-variant">s/d</span>
                        <input
                            type="date"
                            value={radarTo}
                            onChange={e => { setRadarTo(e.target.value); applyRadarRange(radarFrom, e.target.value); }}
                            className="glass-input text-xs py-1.5"
                        />
                        <button onClick={setThisMonth} className="text-xs font-bold px-3 py-1.5 rounded-lg bg-primary-container/10 text-primary-container hover:bg-primary-container/20 transition-colors whitespace-nowrap">
                            Bulan Ini
                        </button>
                    </div>
                </div>
                <div className="h-[280px] w-full mt-3">
                    <ResponsiveContainer width="100%" height="100%" minWidth={0} minHeight={0}>
                        <RadarChart cx="50%" cy="50%" outerRadius="75%" data={radarScores}>
                            <PolarGrid stroke="#e2e8f0" />
                            <PolarAngleAxis dataKey="subject" tick={{ fill: '#94a3b8', fontSize: 11 }} />
                            <PolarRadiusAxis angle={30} domain={[0, 100]} tick={false} axisLine={false} />
                            <Radar name={warga.name} dataKey="value" stroke="#3b82f6" fill="#3b82f6" fillOpacity={0.6} />
                            <Tooltip
                                contentStyle={{ backgroundColor: 'rgba(255,255,255,0.95)', borderRadius: '12px', border: 'none', boxShadow: '0 10px 15px -3px rgba(0,0,0,0.1)' }}
                            />
                        </RadarChart>
                    </ResponsiveContainer>
                </div>
            </div>

            {/* Hafalan + IPK */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div className="glass-card rounded-2xl p-6">
                    <h3 className="font-bold text-on-surface text-sm mb-4">Progress Hafalan</h3>
                    {hafalan ? (
                        <div className="grid grid-cols-3 gap-3 text-center">
                            <div><p className="text-2xl font-black text-primary-container">{hafalan.current_juz}</p><p className="text-[10px] text-on-surface-variant uppercase font-bold">Juz</p></div>
                            <div><p className="text-2xl font-black text-primary-container">{hafalan.total_ayah_completed}</p><p className="text-[10px] text-on-surface-variant uppercase font-bold">Ayat</p></div>
                            <div><p className="text-2xl font-black text-primary-container">{hafalan.streak_days}</p><p className="text-[10px] text-on-surface-variant uppercase font-bold">Streak</p></div>
                        </div>
                    ) : (
                        <p className="text-xs text-on-surface-variant text-center py-4">Belum ada data hafalan.</p>
                    )}
                </div>
                <div className="glass-card rounded-2xl p-6">
                    <h3 className="font-bold text-on-surface text-sm mb-4">Riwayat IPK</h3>
                    {ipks.length === 0 ? (
                        <p className="text-xs text-on-surface-variant text-center py-4">Belum ada data IPK.</p>
                    ) : (
                        <div className="flex flex-wrap gap-2">
                            {ipks.map(i => (
                                <span key={i.id} className="text-xs font-bold px-3 py-1.5 rounded-xl bg-surface-container/40">
                                    Sem {i.semester}: <span className="text-primary-container">{i.ip}</span>
                                </span>
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* Activity tables */}
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <ActivityTable title="Aktivitas Akademik Terakhir" rows={akademiks} onView={setViewingActivity} />
                <ActivityTable title="Aktivitas Leadership Terakhir" rows={leaderships} onView={setViewingActivity} />
                <ActivityTable title="Aktivitas Karakter Terakhir" rows={karakters} onView={setViewingActivity} />
                <ActivityTable title="Aktivitas Kreativitas Terakhir" rows={kreatifs} onView={setViewingActivity} />
            </div>

            {/* Activity detail modal */}
            {viewingActivity && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm" onClick={() => setViewingActivity(null)}>
                    <div className="bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto animate-modal-in" onClick={e => e.stopPropagation()}>
                        <div className="flex items-center justify-between p-6 border-b border-zinc-100">
                            <h3 className="font-bold text-on-surface">{viewingActivity.kegiatan || 'Detail Aktivitas'}</h3>
                            <button onClick={() => setViewingActivity(null)} className="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center hover:bg-zinc-200 flex-shrink-0">
                                <Icon name="close" className="text-sm" />
                            </button>
                        </div>
                        <div className="p-6 space-y-4">
                            {viewingActivity.file && (
                                isPdfName(viewingActivity.file_name) ? (
                                    <a href={viewingActivity.file} target="_blank" rel="noopener" className="flex flex-col items-center justify-center gap-2 rounded-2xl border border-zinc-100 py-10 text-rose-500 hover:bg-rose-50 transition-colors">
                                        <Icon name="picture_as_pdf" className="text-4xl" />
                                        <span className="text-sm font-bold">Buka PDF</span>
                                    </a>
                                ) : (
                                    <img src={viewingActivity.file} alt={viewingActivity.kegiatan ?? 'Bukti kegiatan'} className="w-full rounded-2xl border border-zinc-100 object-cover max-h-80" />
                                )
                            )}
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Komponen</p>
                                    <p className="text-sm font-semibold text-on-surface mt-0.5">{viewingActivity.komponen || '-'}</p>
                                </div>
                                <div>
                                    <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Waktu</p>
                                    <p className="text-sm font-semibold text-on-surface mt-0.5">{viewingActivity.waktu || '-'}</p>
                                </div>
                                <div>
                                    <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Tempat</p>
                                    <p className="text-sm font-semibold text-on-surface mt-0.5">{viewingActivity.tempat || '-'}</p>
                                </div>
                                <div>
                                    <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Penilai</p>
                                    <p className="text-sm font-semibold text-on-surface mt-0.5">{viewingActivity.nama_penilai || '-'}</p>
                                </div>
                            </div>
                            <div>
                                <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Keterangan</p>
                                <p className="text-sm text-on-surface mt-0.5 leading-relaxed">{viewingActivity.keterangan || 'Tidak ada keterangan tambahan.'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
