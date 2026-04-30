import { Head, Link } from '@inertiajs/react';

import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { ProgressDonut } from '@/Components/ui/ProgressDonut';
import { ProgressBar } from '@/Components/ui/ProgressBar';
import { StatusPill, hafalanScoreVariant, hafalanScoreLabel } from '@/Components/ui/StatusPill';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { PageProps, HafalanData, HafalanLog } from '@/types';

interface HafalanPageProps extends PageProps {
    hafalan: HafalanData;
    weekly: {
        completed_pages: number;
        target_pages: number;
        percent: number;
    };
    logs: HafalanLog[];
    quality: {
        mutqin_percent: number;
        murajaah_percent: number;
    };
    murojaah_plan?: {
        surah: string;
        advice: string;
    };
}

export default function Hafalan({
    hafalan,
    weekly,
    logs,
    quality,
    murojaah_plan,
}: HafalanPageProps) {
    const completedJuz = hafalan.current_juz + (hafalan.current_ayah > 0 ? 0.5 : 0);

    return (
        <AppLayout searchPlaceholder="Cari progress hafalan...">
            <Head title="Hafalan Qur'an" />

            <PageHeader
                title="Hafalan Qur'an"
                subtitle="Track your spiritual journey and Quranic memorization progress."
            />

            <div className="grid grid-cols-1 md:grid-cols-12 gap-8">

                {/* Main Progress Card */}
                <div className="md:col-span-8 glass-card rounded-3xl p-8 relative overflow-hidden">
                    {/* Background watermark icon */}
                    <div className="absolute top-0 right-0 p-8 opacity-10 pointer-events-none" aria-hidden>
                        <Icon name="auto_stories" className="text-[120px] text-primary-container" filled />
                    </div>

                    <div className="relative z-10">
                        <div className="flex justify-between items-start mb-8">
                            <div>
                                <span className="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-label-caps mb-3 inline-block">
                                    Active Journey
                                </span>
                                <h3 className="font-display text-headline-md text-on-surface">
                                    Target: {hafalan.target_juz} Juz
                                </h3>
                            </div>
                            <div className="text-right">
                                <p className="text-4xl font-black text-primary-container">
                                    {completedJuz}{' '}
                                    <span className="text-lg font-normal text-outline">Juz</span>
                                </p>
                                <p className="text-label-caps text-outline mt-1">
                                    {hafalan.progress_percent}% Completed
                                </p>
                            </div>
                        </div>

                        {/* Main progress bar */}
                        <div className="w-full bg-surface-container h-4 rounded-full overflow-hidden mb-12">
                            <div
                                className="bg-primary-container h-full rounded-full transition-all duration-700"
                                style={{ width: `${hafalan.progress_percent}%` }}
                            />
                        </div>

                        {/* Stats 3-col */}
                        <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div className="bg-white/40 p-4 rounded-2xl border border-white/60">
                                <p className="text-label-caps text-outline mb-1">Current Juz</p>
                                <p className="text-xl font-bold text-on-surface">Juz {hafalan.current_juz}</p>
                                <p className="text-sm text-primary-container mt-2 flex items-center gap-1">
                                    <Icon name="trending_up" className="text-sm" /> Ar-Ra'd
                                </p>
                            </div>
                            <div className="bg-white/40 p-4 rounded-2xl border border-white/60">
                                <p className="text-label-caps text-outline mb-1">Total Ayah</p>
                                <p className="text-xl font-bold text-on-surface">
                                    {hafalan.total_ayah.toLocaleString('id-ID')}
                                </p>
                                <p className="text-sm text-on-surface-variant mt-2">Hafalan Mutqin</p>
                            </div>
                            <div className="bg-white/40 p-4 rounded-2xl border border-white/60">
                                <p className="text-label-caps text-outline mb-1">Streak</p>
                                <p className="text-xl font-bold text-on-surface">{hafalan.streak_days} Days</p>
                                <p className="text-sm text-error mt-2 flex items-center gap-1">
                                    <Icon name="local_fire_department" className="text-sm" /> Keep going!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Weekly Tasmi Goal */}
                <div className="md:col-span-4 glass-card rounded-3xl p-8 flex flex-col items-center justify-center text-center">
                    <ProgressDonut
                        value={weekly.percent}
                        size={160}
                        label="Weekly Target"
                        className="mb-6"
                    />
                    <h4 className="font-body text-title-sm font-semibold mb-2">Weekly Tasmi Goal</h4>
                    <p className="text-sm text-on-surface-variant mb-6">
                        You've completed {weekly.completed_pages} out of {weekly.target_pages} pages this week. Almost there!
                    </p>
                    <button className="text-primary-container font-bold text-sm flex items-center gap-2 hover:underline">
                        Set New Target <Icon name="arrow_forward" className="text-sm" />
                    </button>
                </div>

                {/* Tasmi Log Table */}
                <div className="md:col-span-12 glass-card rounded-3xl overflow-hidden">
                    <div className="p-8 border-b border-white/40 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 className="font-display text-headline-md text-on-surface">Recent Tasmi Log</h3>
                            <p className="text-sm text-on-surface-variant">Record of your recent memorization deposits</p>
                        </div>
                        <div className="flex gap-2">
                            <button className="bg-white/80 border border-outline-variant px-4 py-2 rounded-xl text-sm font-bold text-on-surface-variant hover:bg-white transition-colors">
                                Export PDF
                            </button>
                            <Link
                                href="/mahasiswa/hafalan/log/create"
                                className="bg-primary-container text-on-primary px-6 py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 active:scale-95 transition-transform inline-flex items-center gap-2"
                            >
                                <Icon name="add" className="text-base" /> New Session
                            </Link>
                        </div>
                    </div>

                    {logs.length === 0 ? (
                        <EmptyState
                            icon="menu_book"
                            title="Belum ada log tasmi"
                            description="Tambahkan session tasmi pertamamu untuk mulai tracking hafalan."
                            className="py-12"
                        />
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead className="bg-blue-50/30">
                                    <tr>
                                        {['Date', 'Surah / Ayah', 'Mentor', 'Score', 'Status'].map((h) => (
                                            <th key={h} className="px-8 py-4 text-label-caps text-on-surface-variant">{h}</th>
                                        ))}
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-white/20">
                                    {logs.map((log) => {
                                        const variant = hafalanScoreVariant[log.score] ?? 'neutral';
                                        const label = hafalanScoreLabel[log.score] ?? log.score.toUpperCase();
                                        return (
                                            <tr key={log.id} className="hover:bg-white/40 transition-colors">
                                                <td className="px-8 py-6">
                                                    <p className="font-bold text-on-surface">{log.tested_at}</p>
                                                </td>
                                                <td className="px-8 py-6">
                                                    <div className="flex items-center gap-3">
                                                        <div className="h-10 w-10 bg-primary-fixed rounded-lg flex items-center justify-center">
                                                            <span className="text-primary-container font-bold text-sm">{log.surah.slice(0, 2)}</span>
                                                        </div>
                                                        <div>
                                                            <p className="font-bold text-on-surface">{log.surah}</p>
                                                            <p className="text-xs text-on-surface-variant">
                                                                Ayah {log.ayat_start} - {log.ayat_end}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="px-8 py-6">
                                                    <span className="text-sm font-medium text-on-surface">{log.mentor_name}</span>
                                                </td>
                                                <td className="px-8 py-6">
                                                    <StatusPill variant={variant}>{label}</StatusPill>
                                                </td>
                                                <td className="px-8 py-6">
                                                    <Icon
                                                        name="check_circle"
                                                        className="text-primary-container text-xl"
                                                        filled
                                                    />
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {/* Memorization Quality */}
                <div className="md:col-span-6 glass-card rounded-3xl p-8">
                    <div className="flex items-center gap-4 mb-6">
                        <div className="w-12 h-12 rounded-2xl bg-[#dae2fd] flex items-center justify-center">
                            <Icon name="assignment_turned_in" className="text-xl text-tertiary" />
                        </div>
                        <div>
                            <h4 className="font-body text-title-sm font-semibold">Memorization Quality</h4>
                            <p className="text-xs text-on-surface-variant">Stability and revision strength</p>
                        </div>
                    </div>
                    <div className="space-y-4">
                        <ProgressBar
                            value={quality.mutqin_percent}
                            label="Mutqin (Strong)"
                            showValue
                            color="success"
                        />
                        <ProgressBar
                            value={quality.murajaah_percent}
                            label="Murajaah Needed"
                            showValue
                            color="warning"
                        />
                    </div>
                </div>

                {/* Murojaah Plan */}
                <div className="md:col-span-6 glass-card rounded-3xl p-8 flex items-center gap-6">
                    <div className="flex-1">
                        <h4 className="font-body text-title-sm font-semibold mb-1">Murojaah Plan</h4>
                        <p className="text-sm text-on-surface-variant mb-4">
                            {murojaah_plan?.advice ?? 'Focus on recent surahs to maintain strength.'}
                        </p>
                        <button className="bg-secondary-container text-on-secondary-container px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-secondary-container/70 transition-colors">
                            Start Review
                        </button>
                    </div>
                    <div className="w-24 h-24 rounded-full border-4 border-primary-fixed flex items-center justify-center flex-shrink-0 bg-primary-fixed/50">
                        <Icon name="auto_stories" className="text-4xl text-primary-container" filled />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
