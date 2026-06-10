import { Head } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { PageProps, LeaderboardEntry } from '@/types';

interface LeaderboardPageProps extends PageProps {
    top3: LeaderboardEntry[];
    entries: LeaderboardEntry[];
    period: 'weekly' | 'monthly' | 'all_time';
    asrama_filter: string;
    available_asrama: string[];
    current_user_rank?: LeaderboardEntry;
}

const podiumColors = [
    { rank: 2, badge: 'bg-slate-300 text-slate-600', border: 'border-white/50', size: 'w-24 h-24', order: 'md:order-1', rounded: 'rounded-3xl', padding: 'p-8' },
    { rank: 1, badge: 'bg-yellow-400 text-white shadow-lg shadow-yellow-500/30', border: 'border-primary/20 border-4', size: 'w-32 h-32', order: 'md:order-2', rounded: 'rounded-[2rem]', padding: 'p-10 bg-blue-50/40 border-primary/20' },
    { rank: 3, badge: 'bg-orange-300 text-orange-800', border: 'border-white/50', size: 'w-24 h-24', order: 'order-3', rounded: 'rounded-3xl', padding: 'p-8' },
];

export default function Leaderboard({
    top3,
    entries,
    asrama_filter,
    available_asrama,
    current_user_rank,
}: LeaderboardPageProps) {
    const [activeAsrama, setActiveAsrama] = useState(asrama_filter);
    const [showAll, setShowAll] = useState(false);

    const displayEntries = showAll ? entries : entries.slice(0, 10);

    function handleAsramaChange(a: string) {
        setActiveAsrama(a);
        // In real app, trigger Inertia visit with filter
    }

    return (
        <AppLayout searchPlaceholder="Cari warga...">
            <Head title="Leaderboard" />

            <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <h1 className="font-display text-display-lg text-primary leading-tight mb-2">Leaderboard</h1>
                    <p className="text-on-surface-variant text-body-md max-w-xl">
                        Celebrating excellence and dedication within our sanctuary.
                    </p>
                </div>

                {/* Asrama Filter */}
                <div className="flex flex-col gap-2">
                    <span className="text-label-caps text-on-surface-variant px-2">Filter Dormitory</span>
                    <div className="flex flex-wrap gap-2 p-1.5 glass-panel rounded-full w-fit">
                        {['Semua', ...available_asrama].map((a) => (
                            <button
                                key={a}
                                onClick={() => handleAsramaChange(a)}
                                className={`px-5 py-2 rounded-full text-sm font-semibold transition-all ${
                                    activeAsrama === a
                                        ? 'bg-primary-container text-on-primary shadow-md shadow-blue-500/20'
                                        : 'text-secondary hover:bg-white/50'
                                }`}
                            >
                                {a}
                            </button>
                        ))}
                    </div>
                </div>
            </div>

            {/* Top 3 Podium */}
            {top3.length >= 3 && (
                <section className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
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
                                            <div className={`w-full h-full ${p.rounded} border-2 ${p.border} bg-primary-fixed flex items-center justify-center`}>
                                                <span className="font-display text-2xl font-bold text-primary-container">
                                                    {entry.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                    <h3 className={`font-display ${entry.rank === 1 ? 'text-3xl text-primary mb-1' : 'text-headline-md text-on-surface'}`}>
                                        {entry.name}
                                    </h3>
                                    <p className={`font-bold mb-2 ${entry.rank === 1 ? 'text-primary-container text-2xl mb-4' : 'text-primary text-lg'}`}>
                                        {entry.points.toLocaleString('id-ID')} pts
                                    </p>
                                    <div className="flex justify-center gap-2">
                                        {entry.badge && (
                                            <span className="inline-block px-4 py-1.5 bg-primary-container text-on-primary rounded-full text-xs font-bold">
                                                {entry.badge}
                                            </span>
                                        )}
                                        <span className="inline-block px-3 py-1 bg-surface-container text-on-surface-variant rounded-full text-xs font-bold">
                                            {entry.asrama}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        );
                    })}
                </section>
            )}

            {/* Leaderboard Table */}
            <div className="glass-panel rounded-[2rem] overflow-hidden mb-8">
                {entries.length === 0 ? (
                    <EmptyState icon="military_tech" title="Belum ada data leaderboard" className="py-12" />
                ) : (
                    <>
                        <table className="w-full border-collapse">
                            <thead>
                                <tr className="bg-blue-50/30 border-b border-white/40">
                                    {['Rank', 'Student Name', 'Dormitory', 'Total Points'].map((h, i) => (
                                        <th
                                            key={h}
                                            className={`px-8 py-5 text-label-caps text-on-surface-variant ${i === 3 ? 'text-right' : 'text-left'}`}
                                        >
                                            {h}
                                        </th>
                                    ))}
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-white/20">
                                {displayEntries.map((entry) => (
                                    <tr key={entry.user_id} className="hover:bg-white/40 transition-colors">
                                        <td className="px-8 py-6 font-bold text-on-surface-variant">#{entry.rank}</td>
                                        <td className="px-8 py-6">
                                            <div className="flex items-center gap-4">
                                                {entry.avatar ? (
                                                    <img
                                                        src={entry.avatar}
                                                        alt={entry.name}
                                                        className="w-10 h-10 rounded-full object-cover border border-white"
                                                    />
                                                ) : (
                                                    <div className="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container text-sm">
                                                        {entry.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                                    </div>
                                                )}
                                                <span className="font-semibold text-on-surface">{entry.name}</span>
                                            </div>
                                        </td>
                                        <td className="px-8 py-6 text-secondary">{entry.asrama}</td>
                                        <td className="px-8 py-6 text-right font-black text-primary-container">
                                            {entry.points.toLocaleString('id-ID')} pts
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>

                        {entries.length > 10 && (
                            <div className="p-8 border-t border-white/40 flex justify-center">
                                <button
                                    onClick={() => setShowAll((v) => !v)}
                                    className="flex items-center gap-2 text-primary-container font-bold hover:gap-3 transition-all"
                                >
                                    {showAll ? 'Sembunyikan' : 'View More Rankings'}
                                    <Icon name={showAll ? 'expand_less' : 'expand_more'} className="text-xl" />
                                </button>
                            </div>
                        )}
                    </>
                )}
            </div>

            {/* My Rank banner */}
            {current_user_rank && (
                <div className="glass-card p-5 rounded-2xl flex items-center justify-between bg-gradient-to-r from-primary/5 to-primary/15">
                    <div className="flex items-center gap-4">
                        <div className="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container">
                            #{current_user_rank.rank}
                        </div>
                        <div>
                            <p className="font-body font-semibold text-on-surface">Posisi Kamu Saat Ini</p>
                            <p className="text-label-caps text-on-surface-variant">{current_user_rank.asrama}</p>
                        </div>
                    </div>
                    <span className="text-xl font-black text-primary-container">
                        {current_user_rank.points.toLocaleString('id-ID')} pts
                    </span>
                </div>
            )}
        </AppLayout>
    );
}
