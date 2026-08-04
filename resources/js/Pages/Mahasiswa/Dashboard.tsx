import { Head, Link, router } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { ProgressDonut } from '@/Components/ui/ProgressDonut';
import {
    RadarChart, Radar, PolarGrid, PolarAngleAxis, PolarRadiusAxis,
    Tooltip, ResponsiveContainer,
} from 'recharts';

import { Icon } from '@/Components/ui/Icon';
import { PageProps, AktivitasItem } from '@/types';

interface RadarScoreItem {
    subject: string;
    A: number;
    fullMark: number;
}

interface DashboardMahasiswaProps extends PageProps {
    shalat: {
        completed: number;
        total: number;
        next_prayer: string;
        list: {
            id: number;
            title: string;
            time: string;
            completed: boolean;
            is_late: boolean;
        }[];
    };
    study_hours: {
        today: number;
        target: number;
    };
    hafalan: {
        progress_percent: number;
        current_surah: string;
        current_juz: number;
        current_page: number;
    };
    points: {
        total: number;
        rank: number;
        to_next: number;
    };
    recent_activities: AktivitasItem[];
    radarScores: RadarScoreItem[];
}

export default function Dashboard({
    auth,
    shalat,
    study_hours,
    hafalan,
    points,
    recent_activities,
    radarScores,
}: DashboardMahasiswaProps) {
    const prayerPercent = Math.round((shalat.completed / shalat.total) * 100);

    function toggleShalat(id: number) {
        const today = new Date().toISOString().split('T')[0];
        router.patch(`/mahasiswa/kalender/${id}/toggle`, { date: today }, {
            preserveState: true,
            preserveScroll: true,
        });
    }

    return (
        <AppLayout searchPlaceholder="Cari aktivitas...">
            <Head title="Dashboard Mahasiswa" />

            <PageHeader
                title="Dashboard Mahasiswa"
                subtitle={`Selamat datang, ${auth.user.name}. Semoga hari ini penuh berkah.`}
            />

            {/* Bento Grid */}
            <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

                {/* Shalat Card */}
                <div className="glass-card p-6 rounded-xl shadow-glass-sm flex flex-col justify-between md:col-span-1">
                    <div>
                        <div className="flex justify-between items-start mb-6">
                            <div className="p-3 bg-[#dae2fd] rounded-lg text-tertiary">
                                <Icon name="mosque" className="text-xl" />
                            </div>
                            <span className="bg-primary/10 text-primary px-3 py-1 rounded-full text-label-caps uppercase">
                                Today
                            </span>
                        </div>
                        <h3 className="font-display text-headline-sm text-on-surface mb-1">Shalat 5 Waktu</h3>
                        <p className="text-body-sm text-secondary">{shalat.completed}/{shalat.total} Completed</p>
                    </div>
                    
                    <div className="mt-4 flex-1 overflow-y-auto max-h-[160px] space-y-2 pr-1">
                        {shalat.list.length === 0 ? (
                            <p className="text-xs text-on-surface-variant italic">Belum ada jadwal shalat.</p>
                        ) : (
                            shalat.list.map((s) => (
                                <div key={s.id} className={`flex items-center gap-3 p-2 rounded-xl transition-all ${
                                    s.completed ? 'bg-primary/5 opacity-60' : 'bg-surface-container/30'
                                }`}>
                                    <button
                                        onClick={() => toggleShalat(s.id)}
                                        className={`w-5 h-5 rounded-lg border-2 flex-shrink-0 flex items-center justify-center transition-all ${
                                            s.completed ? 'bg-primary-container border-primary-container text-white' : 'border-on-surface-variant/30'
                                        }`}
                                    >
                                        {s.completed && <Icon name="check" className="text-xs font-bold" />}
                                    </button>
                                    <div className="flex-1 min-w-0">
                                        <p className={`text-sm font-bold text-on-surface truncate ${s.completed ? 'line-through' : ''}`}>
                                            {s.title}
                                        </p>
                                        <div className="flex items-center gap-2">
                                            <span className="text-[10px] text-on-surface-variant font-medium">{s.time}</span>
                                            {s.is_late && (
                                                <span className="text-[10px] text-rose-500 font-bold uppercase tracking-wider">Late</span>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            ))
                        )}
                    </div>

                    <div className="mt-4 space-y-2">
                        <div className="w-full bg-surface-container rounded-full h-1.5">
                            <div
                                className="bg-primary-container h-1.5 rounded-full shadow-[0_0_8px_rgba(37,99,235,0.4)] transition-all duration-700"
                                style={{ width: `${prayerPercent}%` }}
                            />
                        </div>
                        <p className="text-[10px] text-secondary font-medium">Next: {shalat.next_prayer}</p>
                    </div>
                </div>

                {/* Study Hours */}
                <div className="glass-card p-6 rounded-xl shadow-glass-sm flex flex-col justify-between md:col-span-1">
                    <div>
                        <div className="flex justify-between items-start mb-6">
                            <div className="p-3 bg-secondary-container rounded-lg text-on-secondary-container">
                                <Icon name="auto_stories" className="text-xl" />
                            </div>
                            <span className="bg-on-secondary-container/10 text-on-secondary-container px-3 py-1 rounded-full text-label-caps uppercase">
                                Active
                            </span>
                        </div>
                        <h3 className="font-display text-headline-md text-on-surface mb-1">Study Hours</h3>
                        <p className="text-body-md text-secondary">Focused Sessions</p>
                    </div>
                    <div className="mt-6 flex items-end gap-2">
                        <span className="text-4xl font-bold text-primary-container">{study_hours.today}</span>
                        <span className="text-sm text-secondary mb-1">/ {study_hours.target} hrs today</span>
                    </div>
                </div>

                {/* Memorization Donut */}
                <div className="glass-card p-6 rounded-xl shadow-glass-sm md:col-span-1 lg:col-span-2">
                    <div className="flex items-center justify-between mb-6">
                        <h3 className="font-display text-headline-md text-on-surface">Memorization Goal</h3>
                        <div className="p-2 bg-primary/5 rounded-full text-primary-container">
                            <Icon name="menu_book" className="text-xl" />
                        </div>
                    </div>
                    <div className="flex flex-col md:flex-row items-center gap-6">
                        <ProgressDonut
                            value={hafalan.progress_percent}
                            size={128}
                            label={hafalan.current_surah}
                        />
                        <div className="flex-1 space-y-4">
                            <div className="bg-white/40 p-4 rounded-xl border border-white/20">
                                <p className="text-label-caps text-secondary uppercase mb-1">Current Progress</p>
                                <p className="font-body text-title-sm text-on-surface font-semibold">
                                    Juz {hafalan.current_juz}, Page {hafalan.current_page}
                                </p>
                            </div>
                            <Link
                                href="/mahasiswa/hafalan"
                                className="w-full py-2 bg-primary-container text-on-primary rounded-lg font-body text-sm font-semibold hover:opacity-90 transition-opacity flex items-center justify-center gap-2"
                            >
                                <Icon name="menu_book" className="text-base" />
                                Resume Reading
                            </Link>
                        </div>
                    </div>
                </div>

                {/* Radar Profil Penilaian */}
                <div className="glass-card p-6 rounded-xl shadow-glass-sm md:col-span-3 lg:col-span-2">
                    <div className="flex items-center justify-between mb-4">
                        <h3 className="font-display text-headline-md text-on-surface">Profil Penilaian</h3>
                        <div className="p-2 bg-primary/5 rounded-full text-primary-container">
                            <Icon name="radar" className="text-xl" />
                        </div>
                    </div>
                    {radarScores.every(r => r.A === 0) ? (
                        <p className="text-body-sm text-on-surface-variant text-center py-12">
                            Belum ada data penilaian untuk ditampilkan.
                        </p>
                    ) : (
                        <div className="h-[280px]">
                            <ResponsiveContainer width="100%" height="100%">
                                <RadarChart data={radarScores} outerRadius="75%">
                                    <PolarGrid stroke="#e2e8f0" />
                                    <PolarAngleAxis dataKey="subject" tick={{ fontSize: 11, fill: '#64748b' }} />
                                    <PolarRadiusAxis domain={[0, 100]} tick={{ fontSize: 9, fill: '#94a3b8' }} />
                                    <Radar
                                        name="Skor"
                                        dataKey="A"
                                        stroke="#2563eb"
                                        fill="#2563eb"
                                        fillOpacity={0.35}
                                    />
                                    <Tooltip />
                                </RadarChart>
                            </ResponsiveContainer>
                        </div>
                    )}
                </div>

                {/* Recent Activities */}
                <div className="glass-card p-6 rounded-xl shadow-glass-sm md:col-span-3 lg:col-span-3">
                    <div className="flex items-center justify-between mb-6">
                        <h3 className="font-display text-headline-md text-on-surface">Recent Activities</h3>
                        <Link
                            href="/mahasiswa/aktivitas"
                            className="text-primary-container font-body text-sm font-semibold hover:underline"
                        >
                            View All
                        </Link>
                    </div>
                    <div className="space-y-2">
                        {recent_activities.length === 0 ? (
                            <p className="text-body-sm text-on-surface-variant text-center py-8">
                                Belum ada aktivitas. <Link href="/mahasiswa/aktivitas/create" className="text-primary-container font-semibold hover:underline">Log sekarang →</Link>
                            </p>
                        ) : (
                            recent_activities.map((act) => (
                                <div
                                    key={act.id}
                                    className="flex items-center gap-4 p-4 hover:bg-white/40 transition-colors rounded-xl group"
                                >
                                    <div className="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-primary-fixed flex items-center justify-center">
                                        {act.image_url ? (
                                            <img src={act.image_url} alt={act.jenis} className="w-full h-full object-cover" />
                                        ) : (
                                            <Icon name={act.icon ?? 'event_note'} className="text-xl text-primary-container" />
                                        )}
                                    </div>
                                    <div className="flex-1">
                                        <h4 className="font-body text-title-sm text-on-surface font-semibold">{act.jenis}</h4>
                                        <p className="text-sm text-secondary">{act.deskripsi}</p>
                                    </div>
                                    <span className="text-xs font-medium text-outline">{act.created_at}</span>
                                </div>
                            ))
                        )}
                    </div>
                </div>

                {/* Serenity Points */}
                <div className="glass-card p-6 rounded-xl shadow-glass-sm md:col-span-1 lg:col-span-1 bg-gradient-to-br from-primary/5 to-primary/20">
                    <h3 className="font-body text-title-sm font-semibold text-on-surface mb-6">Serenity Points</h3>
                    <div className="flex flex-col items-center py-4">
                        <div className="p-4 bg-white rounded-full shadow-lg shadow-blue-500/10 mb-4">
                            <Icon name="military_tech" className="text-4xl text-primary-container" filled />
                        </div>
                        <span className="text-3xl font-extrabold text-primary-container">
                            {points.total.toLocaleString('id-ID')}
                        </span>
                        <p className="text-label-caps text-secondary uppercase tracking-widest mt-2">
                            Rank #{points.rank} this week
                        </p>
                    </div>
                    <div className="mt-4 pt-4 border-t border-white/40 flex justify-between text-sm">
                        <span className="text-secondary font-medium">To #{points.rank - 1}</span>
                        <span className="text-primary-container font-bold">{points.to_next.toLocaleString('id-ID')} pts</span>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
