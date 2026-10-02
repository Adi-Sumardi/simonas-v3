import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';

interface AccountUser {
    id: number;
    name: string;
    email: string;
    avatar?: string | null;
    bio?: string | null;
    no_hp?: string | null;
    roles: string[];
    last_login_at?: string | null;
}

interface Props extends PageProps {
    user: AccountUser;
}

const ROLE_LABEL: Record<string, string> = {
    super: 'Super Admin',
    admin: 'Admin',
    mentor: 'Mentor',
    mahasiswa: 'Mahasiswa',
    alumni: 'Alumni',
    pengurus_asrama: 'Pengurus Asrama',
};

const labelCls = 'text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block';

export default function AccountProfile({ user }: Props) {
    const [updatingAvatar, setUpdatingAvatar] = useState(false);

    const profile = useForm({ name: user.name, no_hp: user.no_hp ?? '', bio: user.bio ?? '' });
    const pwd = useForm({ current_password: '', password: '', password_confirmation: '' });

    function handleAvatarChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (!file) return;
        setUpdatingAvatar(true);
        router.post('/akun/profil/avatar', { avatar: file }, {
            forceFormData: true,
            preserveScroll: true,
            onFinish: () => setUpdatingAvatar(false),
        });
    }

    function saveProfile(e: React.FormEvent) {
        e.preventDefault();
        profile.put('/akun/profil', { preserveScroll: true });
    }

    function savePassword(e: React.FormEvent) {
        e.preventDefault();
        pwd.post('/akun/profil/password', {
            preserveScroll: true,
            onSuccess: () => pwd.reset(),
        });
    }

    return (
        <AppLayout searchPlaceholder="Cari...">
            <Head title="Profil Saya" />

            <PageHeader
                title="Profil Saya"
                subtitle="Kelola informasi dan keamanan akun kamu."
                breadcrumbs={[{ label: 'Beranda', href: '/dashboard' }, { label: 'Profil' }]}
            />

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Left: avatar + identity */}
                <div className="space-y-5">
                    <div className="glass-card rounded-3xl p-6 text-center space-y-4">
                        <div className="relative inline-block">
                            <div className="w-24 h-24 rounded-full overflow-hidden bg-primary-fixed mx-auto ring-4 ring-white/60 relative">
                                {user.avatar
                                    ? <img src={user.avatar} alt={user.name} className="w-full h-full object-cover" />
                                    : <div className="w-full h-full flex items-center justify-center">
                                        <Icon name="person" className="text-4xl text-primary-container" filled />
                                      </div>}
                                {updatingAvatar && (
                                    <div className="absolute inset-0 bg-black/40 flex items-center justify-center">
                                        <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin" />
                                    </div>
                                )}
                            </div>
                            <label className="absolute bottom-0 right-0 w-8 h-8 bg-primary-container text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition-transform ring-4 ring-white">
                                <Icon name="photo_camera" className="text-sm" />
                                <input type="file" className="hidden" accept="image/*" onChange={handleAvatarChange} disabled={updatingAvatar} />
                            </label>
                        </div>
                        <div>
                            <h2 className="font-display font-bold text-xl text-on-surface">{user.name}</h2>
                            <p className="text-sm text-on-surface-variant">{user.email}</p>
                        </div>
                        <div className="flex flex-wrap gap-2 justify-center">
                            {user.roles.map(r => (
                                <span key={r} className="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center gap-1">
                                    <Icon name="verified_user" className="text-sm" filled /> {ROLE_LABEL[r] ?? r}
                                </span>
                            ))}
                        </div>
                        {user.last_login_at && (
                            <p className="text-[11px] text-on-surface-variant">Login terakhir: {user.last_login_at}</p>
                        )}
                    </div>
                </div>

                {/* Right: forms */}
                <div className="lg:col-span-2 space-y-5">
                    <form onSubmit={saveProfile} className="glass-card rounded-3xl p-6 space-y-4">
                        <h3 className="font-bold text-on-surface flex items-center gap-2 text-sm">
                            <Icon name="edit" className="text-lg" /> Informasi Akun
                        </h3>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className={labelCls}>Nama</label>
                                <input value={profile.data.name} onChange={e => profile.setData('name', e.target.value)}
                                    className="glass-input w-full text-sm" required />
                                {profile.errors.name && <p className="text-xs text-rose-600 mt-1">{profile.errors.name}</p>}
                            </div>
                            <div>
                                <label className={labelCls}>No. HP</label>
                                <input value={profile.data.no_hp} onChange={e => profile.setData('no_hp', e.target.value)}
                                    className="glass-input w-full text-sm" />
                                {profile.errors.no_hp && <p className="text-xs text-rose-600 mt-1">{profile.errors.no_hp}</p>}
                            </div>
                            <div className="sm:col-span-2">
                                <label className={labelCls}>Email</label>
                                <input value={user.email} disabled className="glass-input w-full text-sm opacity-60" />
                                <p className="text-[11px] text-on-surface-variant mt-1">Email digunakan untuk login dan tidak dapat diubah di sini.</p>
                            </div>
                        </div>
                        <div>
                            <label className={labelCls}>Bio</label>
                            <textarea value={profile.data.bio} onChange={e => profile.setData('bio', e.target.value)} rows={3}
                                maxLength={500} className="glass-input w-full text-sm resize-none" placeholder="Tulis bio singkat..." />
                            {profile.errors.bio && <p className="text-xs text-rose-600 mt-1">{profile.errors.bio}</p>}
                        </div>
                        <button type="submit" disabled={profile.processing}
                            className="px-5 py-2.5 rounded-xl font-bold text-sm bg-primary-container text-white disabled:opacity-50 hover:opacity-90 transition-opacity">
                            {profile.processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                        </button>
                    </form>

                    <form onSubmit={savePassword} className="glass-card rounded-3xl p-6 space-y-4">
                        <h3 className="font-bold text-on-surface flex items-center gap-2 text-sm">
                            <Icon name="lock" className="text-lg" /> Ubah Password
                        </h3>
                        <div>
                            <label className={labelCls}>Password Saat Ini</label>
                            <input type="password" value={pwd.data.current_password} onChange={e => pwd.setData('current_password', e.target.value)}
                                className="glass-input w-full text-sm" required autoComplete="current-password" />
                            {pwd.errors.current_password && <p className="text-xs text-rose-600 mt-1">{pwd.errors.current_password}</p>}
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className={labelCls}>Password Baru</label>
                                <input type="password" value={pwd.data.password} onChange={e => pwd.setData('password', e.target.value)}
                                    className="glass-input w-full text-sm" minLength={8} required autoComplete="new-password" />
                                {pwd.errors.password && <p className="text-xs text-rose-600 mt-1">{pwd.errors.password}</p>}
                            </div>
                            <div>
                                <label className={labelCls}>Konfirmasi Password Baru</label>
                                <input type="password" value={pwd.data.password_confirmation} onChange={e => pwd.setData('password_confirmation', e.target.value)}
                                    className="glass-input w-full text-sm" minLength={8} required autoComplete="new-password" />
                            </div>
                        </div>
                        <button type="submit" disabled={pwd.processing}
                            className="px-5 py-2.5 rounded-xl font-bold text-sm bg-primary-container text-white disabled:opacity-50 hover:opacity-90 transition-opacity">
                            {pwd.processing ? 'Menyimpan...' : 'Perbarui Password'}
                        </button>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
