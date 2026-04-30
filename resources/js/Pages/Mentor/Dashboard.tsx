import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Icon } from '@/Components/ui/Icon';
import { PageProps, Mentee } from '@/types';

interface PerformanceTrendWeek {
    label: string;
    percent: number;
}

interface DashboardMentorProps extends PageProps {
    stats: {
        total_mentees: number;
        avg_performance: number;
        pending_nilai: number;
        quran_target_percent: number;
    };
    performance_trend: PerformanceTrendWeek[];
    mentees: Mentee[];
    featured_mentee?: Mentee & {
        tracks: string[];
        block: string;
        recent_logs: Array<{ icon: string; title: string; desc: string; time: string; icon_bg: string }>;
    };
}

export default function DashboardMentor({
    stats,
    performance_trend,
    mentees,
    featured_mentee,
}: DashboardMentorProps) {
    const [evalSpiritual, setEvalSpiritual] = useState(9);
    const [evalCommunity, setEvalCommunity] = useState(8);
    const [evalNotes, setEvalNotes] = useState('');
    const [submitting, setSubmitting] = useState(false);

    function handleSubmitEval() {
        if (!featured_mentee) return;
        setSubmitting(true);
        router.post(`/mentor/penilaian/${featured_mentee.id}`, {
            spiritual: evalSpiritual,
            community: evalCommunity,
            notes: evalNotes,
        }, {
            onFinish: () => setSubmitting(false),
        });
    }

    const maxTrend = Math.max(...performance_trend.map((w) => w.percent), 1);

    return (
        <AppLayout searchPlaceholder="Cari mentees...">
            <Head title="Dashboard Mentor" />

            <PageHeader
                title="Dashboard Mentor"
                subtitle="Peace and blessings be upon you. Here is an overview of your mentee progress."
            />

            {/* Stats */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <StatCard
                    icon="group"
                    label="TOTAL MENTEES"
                    value={`${stats.total_mentees} Students`}
                    badge="+2 New"
                    badgeColor="blue"
                />
                <StatCard
                    icon="trending_up"
                    label="AVG. PERFORMANCE"
                    value={`${stats.avg_performance} / 100`}
                    badge="Top 10%"
                    badgeColor="emerald"
                />
                <StatCard
                    icon="rate_review"
                    label="PENDING NILAI"
                    value={`${stats.pending_nilai} Reviews`}
                    badge="Pending"
                    badgeColor="amber"
                />
                <StatCard
                    icon="auto_stories"
                    label="QURAN TARGETS"
                    value="Weekly Goal"
                    badge={`${stats.quran_target_percent}% Goal`}
                    badgeColor="purple"
                />
            </div>

            {/* Main 2-col layout */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {/* Left: Performance Trend + Mentees List */}
                <div className="lg:col-span-2 space-y-6">

                    {/* Performance Trend */}
                    <section className="glass-card p-6 rounded-2xl">
                        <div className="flex justify-between items-center mb-6">
                            <h2 className="font-display text-headline-md">Performance Trend</h2>
                            <select className="bg-surface-container-low border-none text-xs rounded-lg py-1 px-3 focus:ring-1 focus:ring-primary-container">
                                <option>Last 30 Days</option>
                                <option>This Semester</option>
                            </select>
                        </div>
                        <div className="relative h-48 w-full flex items-end justify-between gap-2 px-4 pb-8 border-b border-outline-variant/30">
                            {performance_trend.map((week, i) => {
                                const heightPct = (week.percent / maxTrend) * 100;
                                const opacity = 0.3 + (i / performance_trend.length) * 0.7;
                                return (
                                    <div key={week.label} className="flex flex-col items-center flex-1">
                                        <div
                                            className="w-full bg-primary-container/20 rounded-t-lg relative"
                                            style={{ height: `${Math.max(8, heightPct)}%` }}
                                        >
                                            <div
                                                className="absolute inset-0 bg-primary-container rounded-t-lg"
                                                style={{ opacity }}
                                            />
                                        </div>
                                        <span className="text-[10px] mt-2 text-on-surface-variant">{week.label}</span>
                                    </div>
                                );
                            })}
                        </div>
                        <div className="mt-4 flex gap-6">
                            <div className="flex items-center gap-2">
                                <span className="w-3 h-3 rounded-full bg-primary-container" />
                                <span className="text-xs text-on-surface-variant">Spirituality</span>
                            </div>
                            <div className="flex items-center gap-2">
                                <span className="w-3 h-3 rounded-full bg-primary-container/40" />
                                <span className="text-xs text-on-surface-variant">Academic Achievement</span>
                            </div>
                        </div>
                    </section>

                    {/* Assigned Mentees */}
                    <section className="glass-card p-6 rounded-2xl">
                        <h2 className="font-display text-headline-md mb-6">Assigned Mentees</h2>
                        <div className="space-y-3">
                            {mentees.map((mentee) => (
                                <div
                                    key={mentee.id}
                                    className="flex items-center justify-between p-4 bg-white/40 border border-white/40 rounded-xl hover:shadow-md transition-all cursor-pointer group"
                                >
                                    <div className="flex items-center gap-4">
                                        {mentee.avatar ? (
                                            <img
                                                src={mentee.avatar}
                                                alt={mentee.name}
                                                className="w-12 h-12 rounded-full border-2 border-primary/10"
                                            />
                                        ) : (
                                            <div className="w-12 h-12 rounded-full bg-primary-fixed border-2 border-primary/10 flex items-center justify-center font-bold text-primary-container">
                                                {mentee.name.split(' ').map((n) => n[0]).slice(0, 2).join('')}
                                            </div>
                                        )}
                                        <div>
                                            <h4 className="font-body text-title-sm font-semibold text-on-surface">{mentee.name}</h4>
                                            <p className="text-xs text-on-surface-variant">{mentee.kelas} • Dormitory {mentee.asrama}</p>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-8">
                                        <div className="text-right">
                                            <p className="text-label-caps text-on-surface-variant">SCORE</p>
                                            <h5 className="font-body text-title-sm font-bold text-primary-container">{mentee.score}</h5>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <Link
                                                href={`/mentor/mentees/${mentee.id}`}
                                                className={`px-4 py-2 rounded-lg text-sm font-semibold transition-colors ${
                                                    mentee.status === 'review_needed'
                                                        ? 'bg-error/10 text-error hover:bg-error hover:text-on-primary'
                                                        : 'bg-primary-container/10 text-primary-container hover:bg-primary-container hover:text-on-primary'
                                                }`}
                                            >
                                                {mentee.status === 'review_needed' ? 'Review Needed' : 'Nilai Mentor'}
                                            </Link>
                                            <Icon name="chevron_right" className="text-xl text-outline group-hover:text-primary-container transition-colors" />
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>
                </div>

                {/* Right: Featured Mentee + Evaluation */}
                {featured_mentee && (
                    <div className="space-y-6">
                        <section className="glass-card p-6 rounded-2xl">
                            {/* Profile */}
                            <div className="flex flex-col items-center text-center mb-6">
                                <div className="w-20 h-20 rounded-full border-4 border-white shadow-lg overflow-hidden mb-3">
                                    {featured_mentee.avatar ? (
                                        <img src={featured_mentee.avatar} alt={featured_mentee.name} className="w-full h-full object-cover" />
                                    ) : (
                                        <div className="w-full h-full bg-primary-fixed flex items-center justify-center font-bold text-2xl text-primary-container">
                                            {featured_mentee.name.split(' ').map((n) => n[0]).slice(0, 2).join('')}
                                        </div>
                                    )}
                                </div>
                                <h3 className="font-display text-headline-md">{featured_mentee.name}</h3>
                                <div className="flex gap-2 mt-2 flex-wrap justify-center">
                                    {featured_mentee.tracks.map((t) => (
                                        <span key={t} className="bg-primary/10 text-primary-container text-[10px] font-bold px-3 py-1 rounded-full border border-primary/20 uppercase">
                                            {t}
                                        </span>
                                    ))}
                                    <span className="bg-secondary-container text-on-secondary-container text-[10px] font-bold px-3 py-1 rounded-full uppercase">
                                        {featured_mentee.asrama}
                                    </span>
                                </div>
                            </div>

                            {/* Evaluation Form */}
                            <div className="space-y-6">
                                <div className="p-4 bg-primary-container/5 rounded-xl border border-primary-container/10">
                                    <h5 className="text-xs font-bold text-primary-container uppercase mb-3">Evaluation (Nilai Mentor)</h5>
                                    <div className="space-y-4">
                                        <div>
                                            <div className="flex justify-between mb-1">
                                                <label className="text-xs font-medium text-on-surface">Spiritual Growth</label>
                                                <span className="text-xs font-bold text-primary-container">{evalSpiritual}/10</span>
                                            </div>
                                            <input
                                                type="range" min="0" max="10"
                                                value={evalSpiritual}
                                                onChange={(e) => setEvalSpiritual(Number(e.target.value))}
                                                className="w-full accent-primary h-1.5 bg-surface-container rounded-lg appearance-none cursor-pointer"
                                            />
                                        </div>
                                        <div>
                                            <div className="flex justify-between mb-1">
                                                <label className="text-xs font-medium text-on-surface">Community Service</label>
                                                <span className="text-xs font-bold text-primary-container">{evalCommunity}/10</span>
                                            </div>
                                            <input
                                                type="range" min="0" max="10"
                                                value={evalCommunity}
                                                onChange={(e) => setEvalCommunity(Number(e.target.value))}
                                                className="w-full accent-primary h-1.5 bg-surface-container rounded-lg appearance-none cursor-pointer"
                                            />
                                        </div>
                                        <div>
                                            <label className="text-xs font-medium text-on-surface">Mentor Feedback Notes</label>
                                            <textarea
                                                value={evalNotes}
                                                onChange={(e) => setEvalNotes(e.target.value)}
                                                rows={3}
                                                placeholder="Type behavioral observations..."
                                                className="glass-input w-full mt-2 resize-none text-body-sm"
                                            />
                                        </div>
                                        <button
                                            onClick={handleSubmitEval}
                                            disabled={submitting}
                                            className="w-full bg-primary-container text-on-primary py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/30 active:scale-95 transition-all disabled:opacity-50"
                                        >
                                            {submitting ? 'Submitting...' : 'Submit Evaluation'}
                                        </button>
                                    </div>
                                </div>

                                {/* Recent Logs */}
                                <div>
                                    <h5 className="text-xs font-bold text-on-surface-variant uppercase mb-3 px-2">Recent Logs</h5>
                                    <div className="space-y-3">
                                        {featured_mentee.recent_logs.map((log, i) => (
                                            <div key={i} className="flex gap-3 p-3 rounded-lg hover:bg-white/40 transition-colors">
                                                <div className={`w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 ${log.icon_bg}`}>
                                                    <Icon name={log.icon} className="text-xl" />
                                                </div>
                                                <div>
                                                    <p className="text-xs font-bold text-on-surface">{log.title}</p>
                                                    <p className="text-[10px] text-on-surface-variant">{log.desc}</p>
                                                    <p className="text-[10px] text-primary-container mt-1">{log.time}</p>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
