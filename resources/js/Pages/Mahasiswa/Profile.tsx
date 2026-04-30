import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';
import {
    RadarChart, Radar, PolarGrid, PolarAngleAxis, PolarRadiusAxis,
    ResponsiveContainer, Tooltip,
} from 'recharts';

interface ProfileStats {
    shalat_today: number;
    shalat_streak: number;
    hafalan_juz: number;
    hafalan_percent: number;
    study_hours: number;
    points: number;
    rank: number;
    badges: string[];
    completion: number;
}

interface Activity {
    date: string;
    type: string;
    desc: string;
    points: number;
}

interface RadarEntry { subject: string; value: number; fullMark: number }

interface ProfileUser {
    id: number; name: string; email: string; role: string;
    avatar?: string; asrama?: string; nim?: string; angkatan?: string;
}

interface ProfileProps extends PageProps {
    user: ProfileUser;
    stats: ProfileStats;
    activities: Activity[];
    radar: RadarEntry[];
}

const ACTIVITY_ICONS: Record<string, { icon: string; color: string; bg: string }> = {
    shalat:      { icon: 'mosque',       color: 'text-blue-600',    bg: 'bg-blue-100' },
    hafalan:     { icon: 'auto_stories', color: 'text-emerald-600', bg: 'bg-emerald-100' },
    akademik:    { icon: 'school',       color: 'text-amber-600',   bg: 'bg-amber-100' },
    leadership:  { icon: 'groups',       color: 'text-rose-600',    bg: 'bg-rose-100' },
    kreativitas: { icon: 'palette',      color: 'text-purple-600',  bg: 'bg-purple-100' },
};

const BADGE_COLORS = [
    'bg-gradient-to-r from-amber-400 to-orange-400 text-white',
    'bg-gradient-to-r from-blue-500 to-indigo-500 text-white',
    'bg-gradient-to-r from-emerald-500 to-teal-500 text-white',
    'bg-gradient-to-r from-purple-500 to-pink-500 text-white',
];

export default function Profile({ user, stats, activities, radar }: ProfileProps) {
    const [editing, setEditing] = useState(false);

    const { data, setData, put, processing, errors } = useForm({
        name: user.name,
        email: user.email,
        asrama: user.asrama ?? '',
        angkatan: user.angkatan ?? '',
        nim: user.nim ?? '',
    });

    function initials(name: string) {
        return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
    }

    function handleSave() {
        put('/mahasiswa/profil', { onSuccess: () => setEditing(false) });
    }

    return (
        <AppLayout searchPlaceholder="Cari aktivitas...">
            <Head title="Profil Saya" />

            <PageHeader
                title="Profil Saya"
                subtitle="Lihat dan kelola informasi pribadi, pencapaian, dan perkembangan kamu"
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Profil' },
                ]}
            />

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {/* ── Left Column: Avatar + Info ── */}
                <div className="space-y-5">

                    {/* Profile Card */}
                    <div className="glass-card rounded-2xl overflow-hidden">
                        {/* Cover gradient */}
                        <div className="h-24 bg-gradient-to-br from-primary-container via-blue-500 to-indigo-600 relative">
                            <div className="absolute inset-0 opacity-20"
                                style={{ backgroundImage: 'radial-gradient(circle at 30% 50%, white 1px, transparent 1px)', backgroundSize: '20px 20px' }} />
                        </div>

                        <div className="px-6 pb-6">
                            {/* Avatar */}
                            <div className="relative -mt-10 mb-4 w-fit">
                                <div className="w-20 h-20 rounded-2xl border-4 border-white shadow-lg overflow-hidden bg-primary-container flex items-center justify-center">
                                    {user.avatar ? (
                                        <img src={user.avatar} alt={user.name} className="w-full h-full object-cover" />
                                    ) : (
                                        <span className="text-2xl font-black text-white">{initials(user.name)}</span>
                                    )}
                                </div>
                                <label className="absolute -bottom-1 -right-1 w-7 h-7 bg-primary-container rounded-full flex items-center justify-center cursor-pointer shadow-md hover:scale-110 transition-transform">
                                    <Icon name="photo_camera" className="text-sm text-white" />
                                    <input type="file" accept="image/*" className="hidden"
                                        onChange={e => {
                                            if (e.target.files?.[0]) {
                                                const fd = new FormData();
                                                fd.append('avatar', e.target.files[0]);
                                                fetch('/mahasiswa/profil/avatar', { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name=csrf-token]')!.content } })
                                                    .then(() => window.location.reload());
                                            }
                                        }}
                                    />
                                </label>
                            </div>

                            {/* Name + Role */}
                            <h2 className="font-display text-xl font-bold text-on-surface">{user.name}</h2>
                            <p className="text-xs text-on-surface-variant mt-0.5 capitalize">{user.role} · {user.asrama ?? 'Asrama -'}</p>

                            {/* Profile completion */}
                            <div className="mt-4">
                                <div className="flex justify-between text-xs mb-1.5">
                                    <span className="font-semibold text-on-surface-variant">Kelengkapan Profil</span>
                                    <span className="font-black text-primary-container">{stats.completion}%</span>
                                </div>
                                <div className="h-2 bg-surface-container rounded-full overflow-hidden">
                                    <div
                                        className="h-full bg-gradient-to-r from-primary-container to-blue-400 rounded-full transition-all duration-700"
                                        style={{ width: `${stats.completion}%` }}
                                    />
                                </div>
                            </div>

                            {/* Edit button */}
                            <button
                                onClick={() => setEditing(!editing)}
                                className={`w-full mt-4 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 ${
                                    editing ? 'bg-surface-container text-on-surface-variant' : 'btn-primary'
                                }`}
                            >
                                <Icon name={editing ? 'close' : 'edit'} className="text-base" />
                                {editing ? 'Batal Edit' : 'Edit Profil'}
                            </button>
                        </div>
                    </div>

                    {/* Edit Form */}
                    {editing && (
                        <div className="glass-card rounded-2xl p-5 space-y-4">
                            <h3 className="font-bold text-on-surface">Edit Informasi</h3>
                            {[
                                { label: 'Nama Lengkap', key: 'name', type: 'text' },
                                { label: 'Email', key: 'email', type: 'email' },
                                { label: 'NIM', key: 'nim', type: 'text' },
                                { label: 'Asrama', key: 'asrama', type: 'text' },
                                { label: 'Angkatan', key: 'angkatan', type: 'text' },
                            ].map(f => (
                                <div key={f.key}>
                                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1">{f.label}</label>
                                    <input
                                        type={f.type}
                                        value={data[f.key as keyof typeof data]}
                                        onChange={e => setData(f.key as keyof typeof data, e.target.value)}
                                        className="glass-input w-full text-sm"
                                    />
                                    {errors[f.key as keyof typeof errors] && (
                                        <p className="text-xs text-error mt-1">{errors[f.key as keyof typeof errors]}</p>
                                    )}
                                </div>
                            ))}
                            <button
                                onClick={handleSave}
                                disabled={processing}
                                className="w-full py-2.5 bg-primary-container text-on-primary rounded-xl text-sm font-bold hover:opacity-90 transition-opacity disabled:opacity-50"
                            >
                                {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                            </button>
                        </div>
                    )}

                    {/* Info Card */}
                    <div className="glass-card rounded-2xl p-5 space-y-3">
                        <h3 className="font-bold text-on-surface text-sm">Informasi Akademik</h3>
                        {[
                            { icon: 'badge',       label: 'NIM',      value: user.nim ?? '-' },
                            { icon: 'apartment',   label: 'Asrama',   value: user.asrama ?? '-' },
                            { icon: 'school',      label: 'Angkatan', value: user.angkatan ?? '-' },
                            { icon: 'email',       label: 'Email',    value: user.email },
                        ].map(item => (
                            <div key={item.label} className="flex items-center gap-3">
                                <div className="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center flex-shrink-0">
                                    <Icon name={item.icon} className="text-sm text-on-surface-variant" />
                                </div>
                                <div className="min-w-0">
                                    <p className="text-[10px] text-on-surface-variant font-bold uppercase tracking-wider">{item.label}</p>
                                    <p className="text-sm font-semibold text-on-surface truncate">{item.value}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* ── Right Column: Stats + Radar + Feed ── */}
                <div className="lg:col-span-2 space-y-6">

                    {/* Stats row */}
                    <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        {[
                            { icon: 'military_tech', label: 'Poin', value: stats.points.toLocaleString(), color: 'bg-amber-100 text-amber-600' },
                            { icon: 'leaderboard',   label: 'Rank',  value: `#${stats.rank}`,             color: 'bg-blue-100 text-blue-600' },
                            { icon: 'auto_stories',  label: 'Juz',   value: `${stats.hafalan_juz} Juz`,   color: 'bg-emerald-100 text-emerald-600' },
                            { icon: 'local_fire_department', label: 'Streak', value: `${stats.shalat_streak}hr`, color: 'bg-rose-100 text-rose-600' },
                        ].map(s => (
                            <div key={s.label} className="glass-card rounded-2xl p-4 flex flex-col gap-3 items-start h-full">
                                <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${s.color}`}>
                                    <Icon name={s.icon} className="text-xl" filled />
                                </div>
                                <div>
                                    <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">{s.label}</p>
                                    <p className="font-display text-xl font-bold text-on-surface">{s.value}</p>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Badges */}
                    <div className="glass-card rounded-2xl p-5">
                        <h3 className="font-bold text-on-surface mb-3 flex items-center gap-2">
                            <Icon name="workspace_premium" className="text-amber-500 text-xl" filled />
                            Pencapaian & Badge
                        </h3>
                        <div className="flex flex-wrap gap-2">
                            {stats.badges.map((b, i) => (
                                <span key={b} className={`px-3 py-1.5 rounded-full text-xs font-bold shadow-sm ${BADGE_COLORS[i % BADGE_COLORS.length]}`}>
                                    ✦ {b}
                                </span>
                            ))}
                            <span className="px-3 py-1.5 rounded-full text-xs font-bold bg-surface-container text-on-surface-variant border border-dashed border-outline-variant">
                                + Kumpulkan lebih banyak
                            </span>
                        </div>
                    </div>

                    {/* Radar + Activity side by side */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {/* Radar */}
                        <div className="glass-card rounded-2xl p-5">
                            <h3 className="font-bold text-on-surface mb-1">Radar Kompetensi</h3>
                            <p className="text-xs text-on-surface-variant mb-4">Perkembangan 6 dimensi minggu ini</p>
                            <ResponsiveContainer width="100%" height={220}>
                                <RadarChart data={radar} margin={{ top: 5, right: 20, bottom: 5, left: 20 }}>
                                    <PolarGrid stroke="#e2e8f0" />
                                    <PolarAngleAxis dataKey="subject" tick={{ fontSize: 10, fontWeight: 700, fill: '#64748b' }} />
                                    <PolarRadiusAxis domain={[0, 100]} tick={false} axisLine={false} />
                                    <Radar dataKey="value" stroke="#2563eb" fill="#2563eb" fillOpacity={0.2} strokeWidth={2.5}
                                        dot={{ r: 3, fill: '#2563eb', strokeWidth: 0 }} />
                                    <Tooltip formatter={(v) => [`${v}/100`]} contentStyle={{ borderRadius: 12, fontSize: 11 }} />
                                </RadarChart>
                            </ResponsiveContainer>
                            <div className="grid grid-cols-3 gap-2 mt-3">
                                {radar.map(r => (
                                    <div key={r.subject} className="text-center">
                                        <p className="text-[10px] font-black uppercase text-on-surface-variant">{r.subject.slice(0,4)}</p>
                                        <p className="text-sm font-bold text-primary-container">{r.value}</p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Activity Feed */}
                        <div className="glass-card rounded-2xl p-5">
                            <h3 className="font-bold text-on-surface mb-4">Aktivitas Terbaru</h3>
                            <div className="space-y-3">
                                {activities.map((a, i) => {
                                    const meta = ACTIVITY_ICONS[a.type] ?? { icon: 'event', color: 'text-slate-600', bg: 'bg-slate-100' };
                                    return (
                                        <div key={i} className="flex items-center gap-3">
                                            <div className={`w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 ${meta.bg}`}>
                                                <Icon name={meta.icon} className={`text-base ${meta.color}`} filled />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="text-sm font-semibold text-on-surface line-clamp-1">{a.desc}</p>
                                                <p className="text-[10px] text-on-surface-variant">{a.date}</p>
                                            </div>
                                            <span className="text-xs font-black text-emerald-600 flex-shrink-0">+{a.points}</span>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </div>

                    {/* Hafalan Progress Bar */}
                    <div className="glass-card rounded-2xl p-5">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="font-bold text-on-surface">Progress Hafalan Al-Qur'an</h3>
                            <span className="text-sm font-black text-primary-container">{stats.hafalan_percent}% · Juz {stats.hafalan_juz}</span>
                        </div>
                        <div className="relative h-6 bg-surface-container rounded-full overflow-hidden">
                            <div
                                className="h-full rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 transition-all duration-1000 flex items-center justify-end pr-3"
                                style={{ width: `${stats.hafalan_percent}%` }}
                            >
                                <span className="text-[10px] font-black text-white">{stats.hafalan_percent}%</span>
                            </div>
                        </div>
                        <div className="flex justify-between mt-2 text-[10px] text-on-surface-variant font-bold">
                            <span>Juz 1</span><span>Juz {stats.hafalan_juz} (Saat ini)</span><span>Juz 30</span>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
