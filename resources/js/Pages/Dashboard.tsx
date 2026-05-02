import { Head, router } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { 
    ResponsiveContainer, 
    AreaChart, 
    Area, 
    XAxis, 
    YAxis, 
    CartesianGrid, 
    Tooltip, 
} from 'recharts';
import { StatCard } from '@/Components/ui/StatCard';
import { ProgressDonut } from '@/Components/ui/ProgressDonut';
import { Icon } from '@/Components/ui/Icon';
import { Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import { MahasiswaRadarChart } from '@/Components/MahasiswaRadarChart';

// ─── Types per role ───────────────────────────────────────────

interface MahasiswaStats {
    shalat: { 
        completed: number; 
        visual_done: number;
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
    activity_logs: { count: number; target: number };
    hafalan: { progress_percent: number; current_surah: string; juz: number };
    points: { total: number; rank: number; to_next: number };
}

interface MentorStats {
    total_mentees: number;
    avg_performance: number;
    pending_nilai: number;
    quran_target_percent: number;
    mentees: Array<{
        id: number;
        name: string;
        avatar?: string;
        asrama: string;
        progress: number;
        last_log: string;
    }>;
    featured_mentee: any;
    performance_trend: Array<{ label: string; percent: number }>;
}

interface SuperStats {
    total_warga: number;
    total_mentor: number;
    total_alumni: number;
    avg_score: number;
}

interface StudentScore {
    subject: string;
    value: number;
    fullMark: number;
}

interface AktivitasItem {
    id: number;
    jenis: string;
    deskripsi: string;
    created_at: string;
    icon?: string;
    image_url?: string;
}

interface StudentData {
    id: number;
    name: string;
    asrama: string;
    total: number;
    scores: StudentScore[];
}

interface AsramaStat {
    id: number;
    name: string;
    capacity: number;
    current: number;
}

interface DashboardProps extends PageProps {
    role: string;
    permissions: string[];
    stats: MahasiswaStats | MentorStats | SuperStats | AlumniStats | Record<string, unknown>;
    recent_activities?: AktivitasItem[];
    students?: StudentData[];
    asramas?: string[];
    asrama_stats?: AsramaStat[];
}

// ─── Sub-dashboards per role ──────────────────────────────────

function MahasiswaDashboard({ stats, permissions, recent_activities = [] }: { stats: MahasiswaStats; permissions: string[]; recent_activities?: AktivitasItem[] }) {
    const s = stats;
    const shalatPct = Math.round((s.shalat.visual_done / s.shalat.total) * 100);
    const activityPct = Math.round((s.activity_logs.count / s.activity_logs.target) * 100);

    function toggleShalat(id: number) {
        const today = new Date().toISOString().split('T')[0];
        router.patch(`/mahasiswa/kalender/${id}/toggle`, { date: today }, {
            preserveState: true,
            preserveScroll: true,
        });
    }

    return (
        <div className="space-y-6">
            <div className="grid grid-cols-2 md:grid-cols-4 gap-5">
                <StatCard icon="mosque" label="SKOR DISIPLIN" value={`${s.shalat.completed}/${s.shalat.total}`} badge="Tepat Waktu" badgeColor="blue" />
                <StatCard icon="assignment" label="LOG AKTIVITAS" value={`${s.activity_logs.count}`} badge={`Target ${s.activity_logs.target}`} badgeColor="purple" />
                <StatCard icon="auto_stories" label="HAFALAN" value={`Juz ${s.hafalan.juz}`} badge={s.hafalan.current_surah} badgeColor="emerald" />
                <StatCard icon="emoji_events" label="POIN SAYA" value={`${s.points.total}`} badge={`Rank #${s.points.rank}`} badgeColor="amber" />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
                {/* Hafalan progress */}
                <div className="glass-card p-6 rounded-2xl flex flex-col items-center gap-4 min-h-[320px]">
                    <h3 className="font-display text-headline-md self-start">Progress Hafalan</h3>
                    <div className="flex-1 flex flex-col items-center justify-center gap-4 w-full">
                        <ProgressDonut value={s.hafalan.progress_percent} size={130} label={`${s.hafalan.progress_percent}%`} />
                        <p className="text-body-sm text-on-surface-variant text-center px-4">
                            <strong>Juz {s.hafalan.juz}</strong> · {s.hafalan.current_surah}
                        </p>
                    </div>
                    {permissions.includes('log-hafalan') && (
                        <Link href="/mahasiswa/hafalan" className="w-full py-2.5 bg-primary-container/10 text-primary-container rounded-xl text-sm font-bold text-center hover:bg-primary-container hover:text-white transition-all">
                            Lihat Detail Hafalan
                        </Link>
                    )}
                </div>

                {/* Shalat card */}
                <div className="glass-card p-6 rounded-2xl flex flex-col min-h-[320px]">
                    <h3 className="font-display text-headline-md mb-4">Shalat Hari Ini</h3>
                    
                    <div className="flex-1 space-y-2 mb-4 overflow-y-auto pr-1">
                        {s.shalat.list?.filter(p => !p.completed).length === 0 ? (
                            <div className="flex flex-col items-center justify-center py-8 text-center gap-2">
                                <div className="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <Icon name="check_circle" className="text-2xl text-emerald-600" />
                                </div>
                                <p className="text-xs font-bold text-on-surface">Alhamdulillah!</p>
                                <p className="text-[10px] text-on-surface-variant px-4">Semua sholat hari ini telah ditunaikan.</p>
                            </div>
                        ) : (
                            s.shalat.list?.filter(p => !p.completed).map(prayer => (
                                <div key={prayer.id} className="flex items-center gap-3 p-2.5 rounded-xl transition-all bg-surface-container/30">
                                    <button onClick={() => toggleShalat(prayer.id)}
                                        className="w-5 h-5 rounded-lg border-2 border-on-surface-variant/30 flex-shrink-0 flex items-center justify-center transition-all hover:border-primary-container">
                                        {prayer.completed && <Icon name="check" className="text-[10px] font-bold" />}
                                    </button>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-xs font-bold text-on-surface truncate">
                                            {prayer.title}
                                        </p>
                                        <div className="flex items-center gap-2">
                                            <span className="text-[9px] text-on-surface-variant font-medium">{prayer.time}</span>
                                            {prayer.is_late && <span className="text-[9px] text-rose-500 font-bold uppercase">Late</span>}
                                        </div>
                                    </div>
                                </div>
                            ))
                        )}
                    </div>

                    <div className="pt-4 border-t border-surface-container flex items-center justify-between">
                        <div className="flex flex-col">
                            <span className="text-[10px] text-on-surface-variant font-medium">Berikutnya</span>
                            <span className="text-xs font-bold text-primary-container">{s.shalat.next_prayer}</span>
                        </div>
                        <ProgressDonut value={shalatPct} size={60} label={`${s.shalat.visual_done}/${s.shalat.total}`} />
                    </div>
                </div>

                {/* Log Aktivitas */}
                <div className="glass-card p-6 rounded-2xl flex flex-col items-center gap-4 min-h-[320px]">
                    <h3 className="font-display text-headline-md self-start">Log Aktivitas</h3>
                    <div className="flex-1 flex flex-col items-center justify-center gap-4 w-full">
                        <ProgressDonut value={activityPct} size={130} label={`${s.activity_logs.count} Logs`} />
                        <p className="text-body-sm text-on-surface-variant text-center px-4">
                            Target bulan ini: <strong>{s.activity_logs.target} log aktivitas</strong>
                        </p>
                    </div>
                    {permissions.includes('log-aktivitas') && (
                        <Link href="/mahasiswa/aktivitas" className="w-full py-2.5 bg-primary-container text-white rounded-xl text-sm font-bold text-center shadow-lg shadow-primary-container/20 hover:opacity-90 transition-all flex items-center justify-center gap-2">
                            <Icon name="add" className="text-base" />
                            Catat Aktivitas
                        </Link>
                    )}
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {permissions.includes('view-leaderboard') && (
                    <div className="glass-card p-6 rounded-2xl">
                        <div className="flex justify-between items-center mb-4">
                            <h3 className="font-display text-headline-md">Leaderboard</h3>
                            <Link href="/mahasiswa/leaderboard" className="text-primary-container text-body-sm font-semibold hover:underline">Lihat Semua</Link>
                        </div>
                        <div className="flex items-center gap-4 p-4 bg-gradient-to-r from-primary/5 to-primary/15 rounded-xl border border-primary/10">
                            <div className="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container text-lg">#{s.points.rank}</div>
                            <div>
                                <p className="font-semibold text-on-surface">Posisi Kamu</p>
                                <p className="text-body-sm text-on-surface-variant">{s.points.to_next} pts lagi ke rank #{s.points.rank - 1}</p>
                            </div>
                            <span className="ml-auto font-black text-primary-container text-xl">{s.points.total} pts</span>
                        </div>
                    </div>
                )}
                <div className="glass-card p-6 rounded-2xl">
                    <h3 className="font-display text-headline-md mb-4">Aktivitas Terbaru</h3>
                    <div className="space-y-3">
                        {recent_activities.length === 0 ? (
                            <div className="flex flex-col items-center py-6 text-on-surface-variant gap-2">
                                <Icon name="event_note" className="text-4xl opacity-30" />
                                <p className="text-body-sm">Belum ada aktivitas baru.</p>
                                {permissions.includes('log-aktivitas') && (
                                    <Link href="/mahasiswa/aktivitas" className="text-primary-container text-sm font-semibold hover:underline mt-1">
                                        Catat Sekarang →
                                    </Link>
                                )}
                            </div>
                        ) : (
                            recent_activities.map((act) => (
                                <div key={act.id} className="flex items-center gap-4 p-3 hover:bg-primary/5 rounded-xl transition-all">
                                    <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        {act.image_url ? (
                                            <img src={act.image_url} className="w-full h-full object-cover rounded-full" />
                                        ) : (
                                            <Icon name={act.icon || 'event_note'} className="text-xl text-primary" />
                                        )}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-sm font-bold text-on-surface truncate">{act.jenis}</p>
                                        <p className="text-[10px] text-on-surface-variant truncate">{act.deskripsi}</p>
                                    </div>
                                    <span className="text-[10px] font-medium text-outline flex-shrink-0">{act.created_at}</span>
                                </div>
                            ))
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

function MentorDashboard({ stats, permissions }: { stats: MentorStats; permissions: string[] }) {
    return (
        <div className="space-y-6">
            <div className="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-5">
                <StatCard icon="group" label="TOTAL MENTEES" value={`${stats.total_mentees}`} badge="Santri" badgeColor="blue" />
                <StatCard icon="trending_up" label="AVG. PERFORMA" value={`${stats.avg_performance}%`} badge="Avg" badgeColor="emerald" />
                <StatCard icon="rate_review" label="PENDING NILAI" value={`${stats.pending_nilai}`} badge="Setoran" badgeColor="amber" />
                <StatCard icon="auto_stories" label="PROGRESS QURAN" value={`${stats.quran_target_percent}%`} badge="Avg" badgeColor="purple" />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                {/* Main Content (Left) */}
                <div className="lg:col-span-8 space-y-6">
                    {/* Performance Trend Chart */}
                    <div className="glass-card p-4 sm:p-6 rounded-2xl flex flex-col h-full">
                        <div className="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-2">
                            <h3 className="font-display text-title-sm sm:text-headline-md flex items-center gap-2">
                                <Icon name="show_chart" className="text-blue-500" />
                                Tren Performa Bimbingan
                            </h3>
                            <div className="w-fit flex items-center gap-1 text-[9px] font-black text-on-surface-variant uppercase tracking-wider bg-surface-container px-2 py-1 rounded-md">
                                <span className="w-2 h-2 rounded-full bg-blue-500" />
                                Rata-rata Nilai
                            </div>
                        </div>
                        <div className="h-[200px] sm:h-[280px] w-full relative">
                            <ResponsiveContainer width="100%" height="100%">
                                <AreaChart data={stats.performance_trend || []} margin={{ top: 10, right: 5, left: -20, bottom: 0 }}>
                                    <defs>
                                        <linearGradient id="colorPerf" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="5%" stopColor="#3b82f6" stopOpacity={0.1}/>
                                            <stop offset="95%" stopColor="#3b82f6" stopOpacity={0}/>
                                        </linearGradient>
                                    </defs>
                                    <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#e2e8f0" />
                                    <XAxis dataKey="label" stroke="#94a3b8" fontSize={10} tickLine={false} axisLine={false} tickMargin={10} />
                                    <YAxis stroke="#94a3b8" fontSize={10} tickLine={false} axisLine={false} domain={[0, 100]} tickMargin={10} />
                                    <Tooltip 
                                        contentStyle={{ backgroundColor: '#fff', borderRadius: '12px', border: 'none', boxShadow: '0 10px 15px -3px rgba(0,0,0,0.1)' }}
                                    />
                                    <Area type="monotone" dataKey="percent" stroke="#3b82f6" strokeWidth={3} fillOpacity={1} fill="url(#colorPerf)" />
                                </AreaChart>
                            </ResponsiveContainer>
                        </div>
                    </div>

                    {/* Mentees List */}
                    <div className="glass-card p-5 sm:p-6 rounded-2xl">
                        <div className="flex justify-between items-start sm:items-center mb-6 gap-2">
                            <div className="min-w-0">
                                <h3 className="font-display text-title-sm sm:text-headline-md truncate">Warga Bimbingan</h3>
                                <p className="text-[9px] text-on-surface-variant font-black uppercase tracking-widest">Daftar Mahasiswa Aktif</p>
                            </div>
                            <Link href="/mentor/mentees" className="text-primary-container text-[10px] sm:text-xs font-bold hover:bg-primary-container/10 px-2 sm:px-3 py-1.5 rounded-lg transition-all flex items-center gap-1 border border-primary-container/20 flex-shrink-0">
                                <span className="hidden xs:inline">Semua</span> <Icon name="chevron_right" className="text-sm" />
                            </Link>
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            {(!stats.mentees || stats.mentees.length === 0) ? (
                                <div className="col-span-full py-12 flex flex-col items-center justify-center text-on-surface-variant opacity-40 border-2 border-dashed border-surface-container rounded-2xl">
                                    <Icon name="groups" className="text-5xl mb-3" />
                                    <p className="font-bold">Belum ada warga bimbingan</p>
                                    <p className="text-xs">Data mahasiswa bimbingan akan tampil di sini</p>
                                </div>
                            ) : (
                                stats.mentees.slice(0, 4).map((m) => (
                                    <div key={m.id} className="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-white/40 hover:bg-white/80 rounded-2xl transition-all border border-white/60 group">
                                        <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-surface-container overflow-hidden shadow-sm flex-shrink-0 group-hover:scale-105 transition-transform">
                                            {m.avatar ? <img src={m.avatar} alt={m.name} className="w-full h-full object-cover" /> : <div className="w-full h-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold text-xs sm:text-sm">{m.name.charAt(0)}</div>}
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-xs sm:text-sm font-bold text-on-surface truncate">{m.name}</p>
                                            <div className="flex items-center gap-2 mt-1">
                                                <div className="flex-1 h-1 bg-surface-container rounded-full overflow-hidden">
                                                    <div className="h-full bg-primary-container rounded-full" style={{ width: `${m.progress}%` }} />
                                                </div>
                                                <span className="text-[9px] font-black text-primary-container">{m.progress}%</span>
                                            </div>
                                        </div>
                                        <Link href={`/mentor/mentees/${m.id}`} className="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-primary-container hover:text-white transition-colors text-on-surface-variant">
                                            <Icon name="arrow_forward" className="text-lg" />
                                        </Link>
                                    </div>
                                ))
                            )}
                        </div>
                    </div>
                </div>

                {/* Sidebar (Right) */}
                <div className="lg:col-span-4 space-y-6">
                    {/* Pending Actions */}
                    {permissions.includes('nilai-santri') && (
                        <div className="glass-card p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-amber-500/10 to-transparent border-amber-200/50">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                                    <Icon name="pending_actions" className="text-2xl" />
                                </div>
                                <div>
                                    <h4 className="font-bold text-on-surface text-sm">Hafalan Pending</h4>
                                    <p className="text-[10px] text-on-surface-variant uppercase tracking-wider font-black">Butuh Penilaian</p>
                                </div>
                            </div>
                            
                            <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-6">
                                <span className="text-5xl font-display font-black text-amber-600 leading-none">{stats.pending_nilai}</span>
                                <span className="text-[10px] sm:text-xs text-on-surface-variant font-bold">Setoran Mahasiswa</span>
                            </div>

                            <Link href="/mentor/hafalan/pending" className="w-full py-3.5 bg-amber-500 text-white rounded-xl text-sm font-bold text-center shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-all flex items-center justify-center gap-2 group">
                                <Icon name="rate_review" className="text-lg" />
                                Nilai Sekarang
                                <Icon name="arrow_forward" className="text-sm group-hover:translate-x-1 transition-transform" />
                            </Link>
                        </div>
                    )}

                    {/* Featured Mentee Card */}
                    <div className="glass-card p-5 sm:p-6 rounded-2xl overflow-hidden relative min-h-[350px] sm:min-h-[380px] flex flex-col">
                        <div className="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
                            <Icon name="star" className="text-[100px] sm:text-[120px]" />
                        </div>
                        <h3 className="font-display text-headline-md mb-1">Santri Teraktif</h3>
                        <p className="text-[10px] text-on-surface-variant font-black uppercase tracking-widest mb-6 sm:mb-8">Pencapaian Pekan Ini</p>

                        {stats.featured_mentee ? (
                            <div className="flex-1 flex flex-col items-center text-center">
                                <div className="w-24 h-24 rounded-3xl bg-surface-container overflow-hidden shadow-xl mb-4 border-4 border-white relative">
                                    {stats.featured_mentee.avatar ? <img src={stats.featured_mentee.avatar} alt={stats.featured_mentee.name} className="w-full h-full object-cover" /> : <div className="w-full h-full flex items-center justify-center bg-violet-100 text-violet-600 font-bold text-3xl">{stats.featured_mentee.name.charAt(0)}</div>}
                                    <div className="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-amber-400 border-4 border-white flex items-center justify-center shadow-md">
                                        <Icon name="workspace_premium" className="text-white text-xs" />
                                    </div>
                                </div>
                                <h4 className="text-lg font-bold text-on-surface mb-1">{stats.featured_mentee.name}</h4>
                                <p className="text-xs text-on-surface-variant mb-8">Asrama {stats.featured_mentee.asrama}</p>
                                
                                <div className="w-full grid grid-cols-2 gap-3">
                                    <div className="p-4 bg-white/40 rounded-2xl border border-white/60">
                                        <p className="text-[10px] font-black text-on-surface-variant uppercase mb-1">Hafalan</p>
                                        <p className="text-2xl font-display font-black text-primary-container">{stats.featured_mentee.progress}%</p>
                                    </div>
                                    <div className="p-4 bg-white/40 rounded-2xl border border-white/60">
                                        <p className="text-[10px] font-black text-on-surface-variant uppercase mb-1">Rank</p>
                                        <p className="text-2xl font-display font-black text-emerald-600">#1</p>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="flex-1 flex flex-col items-center justify-center text-center opacity-40">
                                <Icon name="military_tech" className="text-6xl mb-3" />
                                <p className="font-bold">Belum ada data</p>
                                <p className="text-xs">Lakukan penilaian untuk memicu ranking</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

function SuperDashboard({ stats, students = [], asramas = [], asrama_stats = [] }: { stats: SuperStats; permissions: string[]; students: StudentData[]; asramas: string[]; asrama_stats?: AsramaStat[] }) {
    return (
        <div className="space-y-8">
            {/* Stat cards — equal height via items-stretch */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
                <StatCard icon="people"            label="TOTAL WARGA"  value={stats.total_warga}  badge="Aktif"      badgeColor="blue" />
                <StatCard icon="supervisor_account" label="MENTOR"       value={stats.total_mentor} badge="Terdaftar"  badgeColor="emerald" />
                <StatCard icon="workspace_premium"  label="ALUMNI"       value={stats.total_alumni} badge="Total"      badgeColor="purple" />
                <StatCard icon="analytics"          label="AVG. SCORE"   value={`${stats.avg_score}/100`} badge="Semester ini" badgeColor="amber" />
            </div>

            {/* Asrama Capacity Cards */}
            {asrama_stats.length > 0 && (
                <div>
                    <h2 className="font-display text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <Icon name="home" className="text-primary-container" />
                        Status Kapasitas Asrama
                    </h2>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {asrama_stats.map((asrama) => {
                            const percent = Math.round((asrama.current / asrama.capacity) * 100);
                            return (
                                <div key={asrama.id} className="glass-card p-5 rounded-2xl flex flex-col gap-3 relative overflow-hidden group hover:shadow-lg transition-all">
                                    <div className="flex justify-between items-start">
                                        <div>
                                            <h3 className="font-bold text-on-surface">{asrama.name}</h3>
                                            <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-wider">Kapasitas</p>
                                        </div>
                                        <div className="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center text-primary-container">
                                            <Icon name="meeting_room" className="text-xl" />
                                        </div>
                                    </div>
                                    <div className="mt-1">
                                        <div className="flex justify-between items-end mb-1.5">
                                            <span className="text-2xl font-display font-black text-on-surface">{asrama.current}<span className="text-sm font-medium text-on-surface-variant ml-1">/ {asrama.capacity}</span></span>
                                            <span className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${percent > 90 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600'}`}>
                                                {percent}% Terisi
                                            </span>
                                        </div>
                                        <div className="w-full h-1.5 bg-surface-container rounded-full overflow-hidden">
                                            <div 
                                                className={`h-full transition-all duration-500 ${percent > 90 ? 'bg-rose-500' : 'bg-primary-container'}`} 
                                                style={{ width: `${percent}%` }} 
                                            />
                                        </div>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>
            )}

            {/* Radar chart — Perkembangan Mahasiswa */}
            {students.length > 0 && (
                <MahasiswaRadarChart students={students} asramas={asramas} />
            )}
        </div>
    );
}

interface AlumniStats {
    total_alumni: number;
    network_size: number;
    total_posts: number;
    total_jobs: number;
}

function AlumniDashboard({ stats }: { stats: AlumniStats }) {
    const quickLinks = [
        {
            href: '/alumni/hub',
            icon: 'people',
            iconBg: 'bg-emerald-100',
            iconColor: 'text-emerald-600',
            title: 'Alumni Hub',
            desc: 'Bagikan cerita, pencapaian, dan diskusi sesama alumni',
            badge: `${stats.total_posts} postingan`,
            badgeColor: 'bg-emerald-100 text-emerald-700',
        },
        {
            href: '/alumni/jobs',
            icon: 'work',
            iconBg: 'bg-blue-100',
            iconColor: 'text-blue-600',
            title: 'Lowongan & Magang',
            desc: 'Info kerja, magang, dan peluang karir dari sesama alumni',
            badge: `${stats.total_jobs} lowongan`,
            badgeColor: 'bg-blue-100 text-blue-700',
        },
        {
            href: '#',
            icon: 'person',
            iconBg: 'bg-purple-100',
            iconColor: 'text-purple-600',
            title: 'Profil Alumni',
            desc: 'Update profil, karir, dan pengalaman profesionalmu',
            badge: 'Coming Soon',
            badgeColor: 'bg-surface-container text-on-surface-variant',
            disabled: true,
        },
        {
            href: '#',
            icon: 'event',
            iconBg: 'bg-amber-100',
            iconColor: 'text-amber-600',
            title: 'Event Alumni',
            desc: 'Reuni, seminar, dan gathering komunitas alumni',
            badge: 'Coming Soon',
            badgeColor: 'bg-surface-container text-on-surface-variant',
            disabled: true,
        },
    ];

    return (
        <div className="space-y-6">
            {/* Stats */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                <StatCard icon="people"            label="TOTAL ALUMNI"    value={stats.total_alumni}  badge="Terdaftar"    badgeColor="emerald" />
                <StatCard icon="hub"               label="NETWORK"         value={stats.network_size}  badge="Koneksi"      badgeColor="blue" />
                <StatCard icon="auto_stories"      label="TOTAL POST"      value={stats.total_posts}   badge="Di Hub"       badgeColor="purple" />
                <StatCard icon="work"              label="LOWONGAN AKTIF"  value={stats.total_jobs}    badge="Available"    badgeColor="amber" />
            </div>

            {/* Hero banner */}
            <div className="glass-card rounded-2xl p-8 bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-transparent border border-emerald-200/40 relative overflow-hidden">
                <div className="absolute inset-0 opacity-5 pointer-events-none select-none flex items-center justify-end pr-8">
                    <span style={{ fontSize: 200 }}>🎓</span>
                </div>
                <h2 className="font-display text-2xl font-black text-on-surface mb-2">
                    Selamat Datang di Portal Alumni 🌟
                </h2>
                <p className="text-on-surface-variant text-sm max-w-xl mb-6 leading-relaxed">
                    Tetap terhubung dengan komunitas alumni, berbagi pengalaman, dan bantu sesama dalam perjalanan karir.
                </p>
                <div className="flex gap-3">
                    <Link href="/alumni/hub"
                        className="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors shadow-md shadow-emerald-200 flex items-center gap-2">
                        <Icon name="people" className="text-base" />
                        Buka Alumni Hub
                    </Link>
                    <Link href="/alumni/jobs"
                        className="px-5 py-2.5 glass-card border border-white/40 text-on-surface rounded-xl font-bold text-sm hover:bg-white/60 transition-colors flex items-center gap-2">
                        <Icon name="work" className="text-base" />
                        Lihat Lowongan
                    </Link>
                </div>
            </div>

            {/* Quick links */}
            <div>
                <h2 className="font-display text-lg font-bold text-on-surface mb-4">Fitur Alumni</h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {quickLinks.map(item => (
                        item.disabled ? (
                            <div key={item.title} className="glass-card rounded-2xl p-6 flex items-start gap-4 opacity-60 cursor-not-allowed">
                                <div className={`w-12 h-12 rounded-xl ${item.iconBg} flex items-center justify-center flex-shrink-0`}>
                                    <Icon name={item.icon} className={`text-2xl ${item.iconColor}`} filled />
                                </div>
                                <div className="flex-1">
                                    <div className="flex items-center gap-2 mb-1">
                                        <h3 className="font-bold text-on-surface text-sm">{item.title}</h3>
                                        <span className={`text-[10px] font-black px-2 py-0.5 rounded-full ${item.badgeColor}`}>{item.badge}</span>
                                    </div>
                                    <p className="text-xs text-on-surface-variant leading-relaxed">{item.desc}</p>
                                </div>
                            </div>
                        ) : (
                            <Link key={item.href} href={item.href}
                                className="glass-card rounded-2xl p-6 flex items-start gap-4 hover:shadow-xl hover:-translate-y-0.5 transition-all group">
                                <div className={`w-12 h-12 rounded-xl ${item.iconBg} flex items-center justify-center flex-shrink-0`}>
                                    <Icon name={item.icon} className={`text-2xl ${item.iconColor}`} filled />
                                </div>
                                <div className="flex-1">
                                    <div className="flex items-center gap-2 mb-1">
                                        <h3 className="font-bold text-on-surface text-sm">{item.title}</h3>
                                        <span className={`text-[10px] font-black px-2 py-0.5 rounded-full ${item.badgeColor}`}>{item.badge}</span>
                                    </div>
                                    <p className="text-xs text-on-surface-variant leading-relaxed">{item.desc}</p>
                                    <span className="text-xs font-bold text-primary-container flex items-center gap-1 mt-2 group-hover:gap-2 transition-all">
                                        Buka <Icon name="arrow_forward" className="text-sm" />
                                    </span>
                                </div>
                            </Link>
                        )
                    ))}
                </div>
            </div>
        </div>
    );
}

// ─── Main unified Dashboard ───────────────────────────────────

export default function Dashboard({ role, permissions, stats, students = [], asramas = [], recent_activities = [], asrama_stats = [] }: DashboardProps) {
    const greeting = (() => {
        const h = new Date().getHours();
        if (h < 12) return 'Selamat Pagi';
        if (h < 15) return 'Selamat Siang';
        if (h < 18) return 'Selamat Sore';
        return 'Selamat Malam';
    })();

    const { auth } = usePage<PageProps>().props;

    return (
        <AppLayout searchPlaceholder="Cari...">
            <Head title="Dashboard" />

            <PageHeader
                title={`${greeting}, ${auth.user.name.split(' ')[0]} 👋`}
                subtitle={`Dashboard ${role.charAt(0).toUpperCase() + role.slice(1)} — SIMONAS Academic Sanctuary`}
            />

            {role === 'mahasiswa' && (
                <MahasiswaDashboard 
                    stats={stats as MahasiswaStats} 
                    permissions={permissions} 
                    recent_activities={recent_activities}
                />
            )}
            {role === 'mentor' && (
                <MentorDashboard stats={stats as MentorStats} permissions={permissions} />
            )}
            {(role === 'super' || role === 'admin') && (
                <SuperDashboard
                    stats={stats as SuperStats}
                    permissions={permissions}
                    students={students as StudentData[]}
                    asramas={asramas as string[]}
                    asrama_stats={asrama_stats}
                />
            )}
            {role === 'alumni' && (
                <AlumniDashboard stats={stats as unknown as AlumniStats} />
            )}
            {!['mahasiswa', 'mentor', 'super', 'admin', 'alumni'].includes(role) && (
                <div className="glass-card p-12 rounded-2xl flex flex-col items-center gap-4 text-center">
                    <Icon name="construction" className="text-5xl text-amber-400" />
                    <h2 className="font-display text-headline-md">Dashboard {role} dalam pengembangan</h2>
                    <p className="text-body-sm text-on-surface-variant max-w-sm">
                        Halaman ini akan tersedia pada Fase 5 pengembangan SIMONAS.
                    </p>
                </div>
            )}
        </AppLayout>
    );
}
