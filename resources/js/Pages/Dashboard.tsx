import { Head } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { ProgressDonut } from '@/Components/ui/ProgressDonut';
import { Icon } from '@/Components/ui/Icon';
import { Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import { MahasiswaRadarChart } from '@/Components/MahasiswaRadarChart';

// ─── Types per role ───────────────────────────────────────────

interface MahasiswaStats {
    shalat: { completed: number; total: number; next: string };
    study_hours: { today: number; target: number };
    hafalan: { progress_percent: number; current_surah: string; juz: number };
    points: { total: number; rank: number; to_next: number };
}

interface MentorStats {
    total_mentees: number;
    avg_performance: number;
    pending_nilai: number;
    quran_target_percent: number;
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

interface StudentData {
    id: number;
    name: string;
    asrama: string;
    total: number;
    scores: StudentScore[];
}

interface DashboardProps extends PageProps {
    role: string;
    permissions: string[];
    stats: MahasiswaStats | MentorStats | SuperStats | AlumniStats | Record<string, unknown>;
    students?: StudentData[];
    asramas?: string[];
}

// ─── Sub-dashboards per role ──────────────────────────────────

function MahasiswaDashboard({ stats, permissions }: { stats: MahasiswaStats; permissions: string[] }) {
    const s = stats;
    const shalatPct = Math.round((s.shalat.completed / s.shalat.total) * 100);
    const studyPct  = Math.round((s.study_hours.today / s.study_hours.target) * 100);

    return (
        <div className="space-y-6">
            <div className="grid grid-cols-2 md:grid-cols-4 gap-5">
                <StatCard icon="mosque" label="SHALAT HARI INI" value={`${s.shalat.completed}/${s.shalat.total}`} badge="Berikutnya" badgeColor="blue" />
                <StatCard icon="menu_book" label="JAM BELAJAR" value={`${s.study_hours.today}h`} badge={`Target ${s.study_hours.target}h`} badgeColor="purple" />
                <StatCard icon="auto_stories" label="HAFALAN" value={`Juz ${s.hafalan.juz}`} badge={s.hafalan.current_surah} badgeColor="emerald" />
                <StatCard icon="emoji_events" label="POIN SAYA" value={`${s.points.total}`} badge={`Rank #${s.points.rank}`} badgeColor="amber" />
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {/* Hafalan progress */}
                <div className="glass-card p-6 rounded-2xl flex flex-col items-center gap-4">
                    <h3 className="font-display text-headline-md self-start">Progress Hafalan</h3>
                    <ProgressDonut value={s.hafalan.progress_percent} size={140} label={`Juz ${s.hafalan.juz}`} />
                    <p className="text-body-sm text-on-surface-variant text-center">
                        Sedang di <strong>{s.hafalan.current_surah}</strong> · Juz {s.hafalan.juz}
                    </p>
                    {permissions.includes('log-hafalan') && (
                        <Link href="/mahasiswa/hafalan" className="btn-primary w-full text-center py-2 rounded-xl text-sm">
                            Lihat Detail Hafalan
                        </Link>
                    )}
                </div>

                {/* Shalat card */}
                <div className="glass-card p-6 rounded-2xl space-y-4">
                    <h3 className="font-display text-headline-md">Shalat Hari Ini</h3>
                    <ProgressDonut value={shalatPct} size={120} label={`${s.shalat.completed}/${s.shalat.total} waktu`} />
                    <p className="text-body-sm text-center text-on-surface-variant">Berikutnya: <strong>{s.shalat.next}</strong></p>
                </div>

                {/* Study hours */}
                <div className="glass-card p-6 rounded-2xl space-y-4">
                    <h3 className="font-display text-headline-md">Jam Belajar</h3>
                    <ProgressDonut value={studyPct} size={120} label={`${s.study_hours.today}h / ${s.study_hours.target}h`} />
                    {permissions.includes('log-aktivitas') && (
                        <Link href="/mahasiswa/aktivitas/create" className="btn-primary w-full text-center py-2 rounded-xl text-sm">
                            + Log Aktivitas
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
                    <div className="flex flex-col items-center py-6 text-on-surface-variant gap-2">
                        <Icon name="event_note" className="text-4xl opacity-30" />
                        <p className="text-body-sm">Belum ada aktivitas hari ini.</p>
                        {permissions.includes('log-aktivitas') && (
                            <Link href="/mahasiswa/aktivitas/create" className="text-primary-container text-sm font-semibold hover:underline mt-1">
                                Catat Sekarang →
                            </Link>
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
            <div className="grid grid-cols-2 md:grid-cols-4 gap-5">
                <StatCard icon="group" label="TOTAL MENTEES" value={`${stats.total_mentees} Santri`} badge="+2 New" badgeColor="blue" />
                <StatCard icon="trending_up" label="AVG. PERFORMA" value={`${stats.avg_performance}`} badge="/ 100" badgeColor="emerald" />
                <StatCard icon="rate_review" label="PENDING NILAI" value={`${stats.pending_nilai}`} badge="Review" badgeColor="amber" />
                <StatCard icon="auto_stories" label="TARGET QURAN" value="Mingguan" badge={`${stats.quran_target_percent}%`} badgeColor="purple" />
            </div>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {permissions.includes('nilai-santri') && (
                    <div className="glass-card p-6 rounded-2xl">
                        <h3 className="font-display text-headline-md mb-4">Hafalan Pending Penilaian</h3>
                        <div className="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <Icon name="pending" className="text-amber-500 text-2xl" />
                            <div>
                                <p className="font-semibold text-amber-800">{stats.pending_nilai} setoran menunggu</p>
                                <p className="text-xs text-amber-600">Segera nilai sebelum deadline</p>
                            </div>
                            <Link href="/mentor/hafalan/pending" className="ml-auto bg-amber-500 text-white px-4 py-1.5 rounded-lg text-sm font-bold">
                                Nilai →
                            </Link>
                        </div>
                    </div>
                )}
                {permissions.includes('view-warga') && (
                    <div className="glass-card p-6 rounded-2xl">
                        <div className="flex justify-between items-center mb-4">
                            <h3 className="font-display text-headline-md">Warga Bimbingan</h3>
                            <Link href="/mentor/mentees" className="text-primary-container text-sm font-semibold hover:underline">Lihat Semua</Link>
                        </div>
                        <div className="text-center py-4 text-on-surface-variant text-body-sm">
                            <Icon name="people" className="text-4xl opacity-30 block mx-auto mb-2" />
                            Data warga dimuat dari database.
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}

function SuperDashboard({ stats, students = [], asramas = [] }: { stats: SuperStats; permissions: string[]; students: StudentData[]; asramas: string[] }) {
    const MENU_ITEMS = [
        {
            href: '/super/role-permission',
            icon: 'admin_panel_settings',
            iconBg: 'bg-violet-100',
            iconColor: 'text-violet-600',
            title: 'Role & Permission',
            desc: 'Kelola hak akses dan izin fitur untuk setiap role pengguna',
            available: true,
        },
        {
            href: '#',
            icon: 'people_alt',
            iconBg: 'bg-blue-100',
            iconColor: 'text-blue-600',
            title: 'Manajemen Warga',
            desc: 'Data warga pondok, kehadiran, dan status akademik',
            available: false,
        },
        {
            href: '#',
            icon: 'school',
            iconBg: 'bg-emerald-100',
            iconColor: 'text-emerald-600',
            title: 'Data Alumni',
            desc: 'Direktori alumni, jejak karir, dan komunitas',
            available: false,
        },
        {
            href: '#',
            icon: 'event',
            iconBg: 'bg-amber-100',
            iconColor: 'text-amber-600',
            title: 'Kegiatan & Event',
            desc: 'Program pesantren, jadwal kegiatan, dan rencana',
            available: false,
        },
        {
            href: '#',
            icon: 'bar_chart',
            iconBg: 'bg-rose-100',
            iconColor: 'text-rose-600',
            title: 'Laporan Eksekutif',
            desc: 'Analitik performa, tren, dan ringkasan data',
            available: false,
        },
        {
            href: '#',
            icon: 'settings',
            iconBg: 'bg-slate-100',
            iconColor: 'text-slate-600',
            title: 'Pengaturan Sistem',
            desc: 'Konfigurasi aplikasi, notifikasi, dan integrasi',
            available: false,
        },
    ];

    return (
        <div className="space-y-8">
            {/* Stat cards — equal height via items-stretch */}
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 items-stretch">
                <StatCard icon="people"            label="TOTAL WARGA"  value={stats.total_warga}  badge="Aktif"      badgeColor="blue" />
                <StatCard icon="supervisor_account" label="MENTOR"       value={stats.total_mentor} badge="Terdaftar"  badgeColor="emerald" />
                <StatCard icon="workspace_premium"  label="ALUMNI"       value={stats.total_alumni} badge="Total"      badgeColor="purple" />
                <StatCard icon="analytics"          label="AVG. SCORE"   value={`${stats.avg_score}/100`} badge="Semester ini" badgeColor="amber" />
            </div>

            {/* Quick menu — bento grid */}
            <div>
                <h2 className="font-display text-lg font-bold text-on-surface mb-4">Menu Manajemen</h2>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    {MENU_ITEMS.map((item) => (
                        item.available ? (
                            <Link
                                key={item.href}
                                href={item.href}
                                className="glass-card rounded-2xl p-6 flex flex-col gap-4 hover:shadow-xl hover:-translate-y-0.5 transition-all group"
                            >
                                <div className={`w-12 h-12 rounded-xl ${item.iconBg} flex items-center justify-center`}>
                                    <Icon name={item.icon} className={`text-2xl ${item.iconColor}`} filled />
                                </div>
                                <div className="flex-1">
                                    <h3 className="font-bold text-on-surface">{item.title}</h3>
                                    <p className="text-xs text-on-surface-variant mt-1 leading-relaxed">{item.desc}</p>
                                </div>
                                <div className="flex items-center gap-1 text-xs font-bold text-primary-container">
                                    Buka <Icon name="arrow_forward" className="text-sm group-hover:translate-x-1 transition-transform" />
                                </div>
                            </Link>
                        ) : (
                            <div key={item.title} className="glass-card rounded-2xl p-6 flex flex-col gap-4 opacity-50 cursor-not-allowed">
                                <div className={`w-12 h-12 rounded-xl ${item.iconBg} flex items-center justify-center`}>
                                    <Icon name={item.icon} className={`text-2xl ${item.iconColor}`} filled />
                                </div>
                                <div className="flex-1">
                                    <h3 className="font-bold text-on-surface">{item.title}</h3>
                                    <p className="text-xs text-on-surface-variant mt-1 leading-relaxed">{item.desc}</p>
                                </div>
                                <div className="flex items-center gap-1.5">
                                    <span className="text-[10px] font-black uppercase tracking-widest bg-surface-container text-on-surface-variant px-2 py-1 rounded-full">
                                        Fase 5 — Coming Soon
                                    </span>
                                </div>
                            </div>
                        )
                    ))}
                </div>
            </div>

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

export default function Dashboard({ role, permissions, stats, students = [], asramas = [] }: DashboardProps) {
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
                <MahasiswaDashboard stats={stats as MahasiswaStats} permissions={permissions} />
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
