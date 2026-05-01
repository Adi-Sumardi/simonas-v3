import { Head, useForm, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { AlumniSidebar } from '@/Components/Alumni/AlumniSidebar';
import { PageProps } from '@/types';

// ─── Types ────────────────────────────────────────────────────────────────────
interface AlumniProfile {
    id: number;
    nama: string | null;
    email: string | null;
    no_whatsapp: string | null;
    alamat_domisili: string | null;
    pendidikan_terakhir: string | null;
    kampus_s1: string | null;
    jurusan_s1: string | null;
    pekerjaan_sekarang: string | null;
    bidang_pekerjaan: string | null;
    bidang_keahlian: string | null;
    asal_asrama: string | null;
    tahun_masuk_asrama: string | null;
    tahun_keluar_asrama: string | null;
}

interface RecentPost {
    id: number;
    type: string;
    title: string | null;
    content: string;
    likes_count: number;
    comments_count: number;
    created_at: string;
}

interface Props {
    profile: AlumniProfile | null;
    posts: RecentPost[];
    business: any | null;
}

const POST_TYPE_COLOR: Record<string, string> = {
    story:       'bg-blue-100 text-blue-700',
    achievement: 'bg-amber-100 text-amber-700',
    event:       'bg-purple-100 text-purple-700',
    question:    'bg-emerald-100 text-emerald-700',
};

export default function Profil({ profile, posts, business }: Props) {
    const { auth } = usePage<PageProps>().props;
    const [editing, setEditing] = useState(false);

    const { data, setData, put, processing, reset } = useForm({
        nama:                 profile?.nama                 ?? auth.user.name,
        no_whatsapp:          profile?.no_whatsapp          ?? '',
        alamat_domisili:      profile?.alamat_domisili      ?? '',
        pendidikan_terakhir:  profile?.pendidikan_terakhir  ?? '',
        kampus_s1:            profile?.kampus_s1            ?? '',
        jurusan_s1:           profile?.jurusan_s1           ?? '',
        pekerjaan_sekarang:   profile?.pekerjaan_sekarang   ?? '',
        bidang_pekerjaan:     profile?.bidang_pekerjaan     ?? '',
        bidang_keahlian:      profile?.bidang_keahlian      ?? '',
        asal_asrama:          profile?.asal_asrama          ?? '',
        tahun_masuk_asrama:   profile?.tahun_masuk_asrama   ?? '',
        tahun_keluar_asrama:  profile?.tahun_keluar_asrama  ?? '',
    });

    const initials = auth.user.name.split(' ').map((n: string) => n[0]).slice(0, 2).join('');
    const completeness = [
        data.nama, data.no_whatsapp, data.alamat_domisili,
        data.pendidikan_terakhir, data.pekerjaan_sekarang, data.asal_asrama,
    ].filter(Boolean).length;
    const completenessPct = Math.round((completeness / 6) * 100);

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        put('/alumni/profil', { onSuccess: () => setEditing(false) });
    }

    return (
        <AppLayout searchPlaceholder="Cari di portal alumni...">
            <Head title="Profil Alumni — SIMONAS" />

            <PageHeader
                title="Profil Saya 👤"
                subtitle="Kelola informasi karir dan data diri alumni"
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Profil' },
                ]}
            />

            <div className="grid grid-cols-1 xl:grid-cols-4 gap-6">

                {/* ── Left: sidebar ── */}
                <div className="xl:col-span-1">
                    <AlumniSidebar active="/alumni/profil" />
                </div>

                {/* ── Right: profile content ── */}
                <div className="xl:col-span-3 space-y-6">

                    {/* Profile card */}
                    <div className="glass-card rounded-2xl p-6">
                        <div className="flex items-start gap-5 mb-6">
                            {/* Avatar */}
                            <div className="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center font-black text-3xl text-white flex-shrink-0 shadow-lg">
                                {initials}
                            </div>
                            <div className="flex-1 min-w-0">
                                <h2 className="font-display text-xl font-bold text-on-surface">{data.nama || auth.user.name}</h2>
                                <p className="text-sm text-on-surface-variant">{auth.user.email}</p>
                                {data.pekerjaan_sekarang && (
                                    <p className="text-sm font-semibold text-emerald-700 mt-1 flex items-center gap-1">
                                        <Icon name="work" className="text-sm" filled />
                                        {data.pekerjaan_sekarang}
                                        {data.bidang_pekerjaan && ` · ${data.bidang_pekerjaan}`}
                                    </p>
                                )}
                                {data.asal_asrama && (
                                    <p className="text-xs text-on-surface-variant mt-1 flex items-center gap-1">
                                        <Icon name="home" className="text-xs" />
                                        Asrama {data.asal_asrama}
                                        {data.tahun_masuk_asrama && ` (${data.tahun_masuk_asrama}–${data.tahun_keluar_asrama ?? '...'})`}
                                    </p>
                                )}
                            </div>
                            <button onClick={() => setEditing(!editing)}
                                className={`flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl font-bold text-sm transition-colors ${
                                    editing ? 'bg-surface-container text-on-surface-variant' : 'bg-emerald-500 text-white hover:bg-emerald-600'
                                }`}>
                                <Icon name={editing ? 'close' : 'edit'} className="text-sm" />
                                {editing ? 'Batal' : 'Edit Profil'}
                            </button>
                        </div>

                        {/* Completeness bar */}
                        <div className="mb-4">
                            <div className="flex justify-between text-xs mb-1.5">
                                <span className="text-on-surface-variant">Kelengkapan Profil</span>
                                <span className={`font-bold ${completenessPct >= 80 ? 'text-emerald-600' : 'text-amber-600'}`}>{completenessPct}%</span>
                            </div>
                            <div className="h-2 bg-surface-container rounded-full overflow-hidden">
                                <div className={`h-full rounded-full transition-all ${completenessPct >= 80 ? 'bg-emerald-500' : 'bg-amber-400'}`}
                                    style={{ width: `${completenessPct}%` }} />
                            </div>
                            {completenessPct < 100 && (
                                <p className="text-[10px] text-on-surface-variant mt-1">
                                    Lengkapi profil untuk meningkatkan visibilitas di direktori alumni.
                                </p>
                            )}
                        </div>

                        {/* Edit form */}
                        {editing ? (
                            <form onSubmit={handleSubmit} className="space-y-4 border-t border-white/30 pt-5">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {[
                                        { field: 'nama',                 label: 'Nama Lengkap',        placeholder: 'Nama lengkap dengan gelar' },
                                        { field: 'no_whatsapp',          label: 'No. WhatsApp',        placeholder: '08xxxxxxxxxx' },
                                        { field: 'alamat_domisili',      label: 'Kota Domisili',       placeholder: 'Jakarta Selatan' },
                                        { field: 'asal_asrama',          label: 'Nama Asrama',         placeholder: 'Al-Farabi' },
                                        { field: 'tahun_masuk_asrama',   label: 'Tahun Masuk Asrama',  placeholder: '2018' },
                                        { field: 'tahun_keluar_asrama',  label: 'Tahun Keluar Asrama', placeholder: '2022' },
                                        { field: 'pendidikan_terakhir',  label: 'Pendidikan Terakhir', placeholder: 'S1 / S2 / S3' },
                                        { field: 'kampus_s1',            label: 'Kampus',              placeholder: 'Universitas Indonesia' },
                                        { field: 'jurusan_s1',           label: 'Jurusan / Prodi',     placeholder: 'Teknik Informatika' },
                                        { field: 'pekerjaan_sekarang',   label: 'Pekerjaan Sekarang',  placeholder: 'Software Engineer' },
                                        { field: 'bidang_pekerjaan',     label: 'Perusahaan / Instansi', placeholder: 'PT Teknologi Maju' },
                                    ].map(({ field, label, placeholder }) => (
                                        <div key={field}>
                                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">{label}</label>
                                            <input type="text"
                                                value={(data as any)[field]}
                                                onChange={e => setData(field as any, e.target.value)}
                                                placeholder={placeholder}
                                                className="glass-input w-full text-sm" />
                                        </div>
                                    ))}
                                </div>

                                <div>
                                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Bidang Keahlian</label>
                                    <input type="text"
                                        value={data.bidang_keahlian}
                                        onChange={e => setData('bidang_keahlian', e.target.value)}
                                        placeholder="React, Laravel, Data Science, Desain Grafis, ..."
                                        className="glass-input w-full text-sm" />
                                </div>

                                <div className="flex gap-3 pt-2">
                                    <button type="button" onClick={() => { setEditing(false); reset(); }}
                                        className="flex-1 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant">
                                        Batal
                                    </button>
                                    <button type="submit" disabled={processing}
                                        className="flex-1 py-3 rounded-xl font-bold text-sm bg-emerald-500 text-white disabled:opacity-50 hover:bg-emerald-600 transition-colors">
                                        {processing ? 'Menyimpan...' : '✓ Simpan Profil'}
                                    </button>
                                </div>
                            </form>
                        ) : (
                            /* View mode */
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-white/30 pt-5">
                                {[
                                    { icon: 'phone',        label: 'WhatsApp',          value: data.no_whatsapp },
                                    { icon: 'location_on',  label: 'Domisili',          value: data.alamat_domisili },
                                    { icon: 'school',       label: 'Pendidikan',        value: [data.kampus_s1, data.jurusan_s1].filter(Boolean).join(' — ') },
                                    { icon: 'work',         label: 'Karir',             value: [data.pekerjaan_sekarang, data.bidang_pekerjaan].filter(Boolean).join(' @ ') },
                                    { icon: 'home',         label: 'Asrama',            value: data.asal_asrama },
                                    { icon: 'psychology',   label: 'Keahlian',          value: data.bidang_keahlian },
                                ].map(row => (
                                    <div key={row.label} className="flex items-start gap-3">
                                        <div className="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <Icon name={row.icon} className="text-sm text-on-surface-variant" />
                                        </div>
                                        <div>
                                            <p className="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant">{row.label}</p>
                                            <p className="text-sm text-on-surface">{row.value || <span className="text-on-surface-variant italic">—</span>}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Recent posts */}
                    <div className="glass-card rounded-2xl overflow-hidden">
                        <div className="px-6 py-4 border-b border-white/30 flex items-center justify-between">
                            <h3 className="font-bold text-on-surface">Postingan Saya</h3>
                            <a href="/alumni/hub" className="text-xs font-bold text-primary-container hover:underline">Lihat Hub →</a>
                        </div>
                        {posts.length === 0 ? (
                            <div className="flex flex-col items-center py-12 gap-3 text-on-surface-variant">
                                <Icon name="article" className="text-4xl opacity-20" />
                                <p className="text-sm">Belum ada postingan</p>
                                <a href="/alumni/hub" className="text-sm font-bold text-emerald-600 hover:underline">Tulis sesuatu →</a>
                            </div>
                        ) : (
                            <div className="divide-y divide-white/20">
                                {posts.map(p => (
                                    <div key={p.id} className="p-5 hover:bg-white/30 transition-colors">
                                        <div className="flex items-start justify-between gap-3">
                                            <div className="flex-1 min-w-0">
                                                <div className="flex items-center gap-2 mb-1">
                                                    <span className={`text-[10px] font-black px-2 py-0.5 rounded-full ${POST_TYPE_COLOR[p.type] ?? 'bg-surface-container text-on-surface-variant'}`}>
                                                        {p.type}
                                                    </span>
                                                    <span className="text-[10px] text-on-surface-variant">{p.created_at}</span>
                                                </div>
                                                {p.title && <p className="font-bold text-sm text-on-surface">{p.title}</p>}
                                                <p className="text-sm text-on-surface-variant line-clamp-2">{p.content}</p>
                                            </div>
                                            <div className="flex items-center gap-3 text-xs text-on-surface-variant flex-shrink-0">
                                                <span className="flex items-center gap-1"><Icon name="favorite" className="text-xs text-rose-400" filled />{p.likes_count}</span>
                                                <span className="flex items-center gap-1"><Icon name="chat_bubble" className="text-xs" />{p.comments_count}</span>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Business preview */}
                    {business && (
                        <div className="glass-card rounded-2xl p-5 border border-emerald-200/50 bg-emerald-50/30">
                            <div className="flex items-center justify-between mb-3">
                                <h3 className="font-bold text-on-surface flex items-center gap-2">
                                    <Icon name="storefront" className="text-emerald-600" filled />
                                    Bisnis Saya
                                </h3>
                                <a href="/alumni/bisnis" className="text-xs font-bold text-emerald-600 hover:underline">Kelola →</a>
                            </div>
                            <p className="font-bold text-emerald-800">{business.company_name}</p>
                            <p className="text-xs text-emerald-700">{business.business_type}</p>
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
