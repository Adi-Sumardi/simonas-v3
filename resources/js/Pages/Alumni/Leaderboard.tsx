import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { AlumniSidebar } from '@/Components/Alumni/AlumniSidebar';
import { PageProps, LeaderboardEntry } from '@/types';

interface AlumniLeaderboardPageProps extends PageProps {
    top3: LeaderboardEntry[];
    entries: LeaderboardEntry[];
    asrama_filter: string;
    available_asrama: string[];
    monthlyTarget: number;
}

const podiumColors = [
    { rank: 2, badge: 'bg-slate-300 text-slate-600', border: 'border-white/50', size: 'w-20 h-20', order: 'md:order-1', rounded: 'rounded-3xl', padding: 'p-6' },
    { rank: 1, badge: 'bg-yellow-400 text-white shadow-lg shadow-yellow-500/30', border: 'border-emerald-400/30 border-4', size: 'w-28 h-28', order: 'md:order-2', rounded: 'rounded-[2rem]', padding: 'p-8 bg-emerald-50/40 border-emerald-400/20' },
    { rank: 3, badge: 'bg-orange-300 text-orange-800', border: 'border-white/50', size: 'w-20 h-20', order: 'order-3', rounded: 'rounded-3xl', padding: 'p-6' },
];

export default function AlumniLeaderboard({
    top3,
    entries,
    asrama_filter,
    available_asrama,
    monthlyTarget,
}: AlumniLeaderboardPageProps) {
    const [activeAsrama, setActiveAsrama] = useState(asrama_filter);
    const [showAll, setShowAll] = useState(false);

    const displayEntries = showAll ? entries : entries.slice(0, 10);

    function handleAsramaChange(a: string) {
        setActiveAsrama(a);
        router.get('/alumni/leaderboard', { asrama: a }, { preserveScroll: true, preserveState: true });
    }

    return (
        <AppLayout searchPlaceholder="Cari warga...">
            <Head title="Leaderboard — SIMONAS Alumni" />

            <PageHeader
                title="Leaderboard 🏆"
                subtitle="Peringkat mahasiswa berdasarkan total poin (akademik, leadership, karakter, kreativitas) bulan berjalan."
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Alumni Hub', href: '/alumni/hub' },
                    { label: 'Leaderboard' },
                ]}
            />

            <div className="grid grid-cols-1 xl:grid-cols-4 gap-6">
                {/* ── Main content ── */}
                <div className="xl:col-span-3 space-y-8">
                    <div className="flex flex-wrap items-center justify-between gap-3">
                        <p className="text-xs text-on-surface-variant">
                            Target poin/bulan: <b className="text-on-surface">{monthlyTarget}</b> &middot; &ge;{monthlyTarget} = Terpenuhi &middot; &lt;{monthlyTarget} = Belum Terpenuhi
                        </p>
                        <div className="flex flex-wrap gap-2 p-1.5 glass-panel rounded-full w-fit">
                            {['Semua', ...available_asrama].map((a) => (
                                <button
                                    key={a}
                                    onClick={() => handleAsramaChange(a)}
                                    className={`px-4 py-1.5 rounded-full text-xs font-semibold transition-all ${
                                        activeAsrama === a
                                            ? 'bg-emerald-500 text-white shadow-md shadow-emerald-200'
                                            : 'text-secondary hover:bg-white/50'
                                    }`}
                                >
                                    {a}
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Top 3 Podium */}
                    {top3.length >= 3 && (
                        <section className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {[top3[1], top3[0], top3[2]].map((entry, i) => {
                                const p = podiumColors[i];
                                return (
                                    <div key={entry.rank} className={`${p.order} flex flex-col justify-end`}>
                                        <div className={`glass-panel ${p.padding} rounded-[2rem] text-center transform transition-transform hover:-translate-y-2`}>
                                            <div className={`relative ${p.size} mx-auto mb-4`}>
                                                <div className={`absolute -top-2 -right-2 w-8 h-8 ${p.badge} rounded-full border-4 border-white flex items-center justify-center font-bold ${entry.rank === 1 ? 'w-10 h-10 -top-3 -right-3 text-sm' : ''}`}>
                                                    {entry.rank}
                                                </div>
                                                {entry.avatar ? (
                                                    <img
                                                        src={entry.avatar}
                                                        alt={entry.name}
                                                        className={`w-full h-full object-cover ${p.rounded} border-2 ${p.border}`}
                                                    />
                                                ) : (
                                                    <div className={`w-full h-full ${p.rounded} border-2 ${p.border} bg-emerald-100 flex items-center justify-center`}>
                                                        <span className="font-display text-xl font-bold text-emerald-600">
                                                            {entry.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                                        </span>
                                                    </div>
                                                )}
                                            </div>
                                            <h3 className={`font-display ${entry.rank === 1 ? 'text-2xl text-emerald-700 mb-1' : 'text-headline-sm text-on-surface'}`}>
                                                {entry.name}
                                            </h3>
                                            <p className={`font-bold mb-2 ${entry.rank === 1 ? 'text-emerald-600 text-xl mb-3' : 'text-emerald-500 text-base'}`}>
                                                {entry.points.toLocaleString('id-ID')} poin
                                            </p>
                                            <span className="inline-block px-3 py-1 bg-surface-container text-on-surface-variant rounded-full text-xs font-bold">
                                                {entry.asrama}
                                            </span>
                                        </div>
                                    </div>
                                );
                            })}
                        </section>
                    )}

                    {/* Leaderboard Table */}
                    <div className="glass-panel rounded-[2rem] overflow-hidden">
                        {entries.length === 0 ? (
                            <EmptyState icon="military_tech" title="Belum ada data leaderboard" className="py-12" />
                        ) : (
                            <>
                                <div className="overflow-x-auto">
                                    <table className="w-full border-collapse">
                                        <thead>
                                            <tr className="bg-emerald-50/30 border-b border-white/40">
                                                {['Rank', 'Nama', 'Asrama', 'Total Poin', 'Status'].map((h, i) => (
                                                    <th
                                                        key={h}
                                                        className={`px-6 py-4 text-label-caps text-on-surface-variant whitespace-nowrap ${i === 3 ? 'text-right' : i === 4 ? 'text-center' : 'text-left'}`}
                                                    >
                                                        {h}
                                                    </th>
                                                ))}
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-white/20">
                                            {displayEntries.map((entry) => (
                                                <tr key={entry.user_id} className="hover:bg-white/40 transition-colors">
                                                    <td className="px-6 py-5 font-bold text-on-surface-variant">#{entry.rank}</td>
                                                    <td className="px-6 py-5">
                                                        <div className="flex items-center gap-3">
                                                            {entry.avatar ? (
                                                                <img
                                                                    src={entry.avatar}
                                                                    alt={entry.name}
                                                                    className="w-9 h-9 rounded-full object-cover border border-white"
                                                                />
                                                            ) : (
                                                                <div className="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-emerald-600 text-xs">
                                                                    {entry.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                                                </div>
                                                            )}
                                                            <span className="font-semibold text-on-surface text-sm">{entry.name}</span>
                                                        </div>
                                                    </td>
                                                    <td className="px-6 py-5 text-secondary text-sm">{entry.asrama}</td>
                                                    <td className="px-6 py-5 text-right font-black text-emerald-600">
                                                        {entry.points.toLocaleString('id-ID')}
                                                    </td>
                                                    <td className="px-6 py-5 text-center">
                                                        <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${entry.terpenuhi ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'}`}>
                                                            {entry.terpenuhi ? 'Terpenuhi' : 'Belum Terpenuhi'}
                                                        </span>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>

                                {entries.length > 10 && (
                                    <div className="p-6 border-t border-white/40 flex justify-center">
                                        <button
                                            onClick={() => setShowAll((v) => !v)}
                                            className="flex items-center gap-2 text-emerald-600 font-bold hover:gap-3 transition-all"
                                        >
                                            {showAll ? 'Sembunyikan' : 'Lihat Semua Ranking'}
                                            <Icon name={showAll ? 'expand_less' : 'expand_more'} className="text-xl" />
                                        </button>
                                    </div>
                                )}
                            </>
                        )}
                    </div>
                </div>

                {/* ── Sidebar ── */}
                <div className="space-y-5">
                    <AlumniSidebar active="/alumni/leaderboard" />
                </div>
            </div>
        </AppLayout>
    );
}
