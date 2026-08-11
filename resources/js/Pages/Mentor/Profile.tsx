import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';

interface UserInfo {
    id: number; name: string; email: string; avatar?: string;
    bio?: string; no_hp?: string;
}

interface ProfileProps extends PageProps {
    user: UserInfo;
    total_mentees: number;
}

export default function MentorProfile({ user, total_mentees }: ProfileProps) {
    const [nameVal, setNameVal] = useState(user.name);
    const [bioVal, setBioVal] = useState(user.bio ?? '');
    const [noHpVal, setNoHpVal] = useState(user.no_hp ?? '');
    const [updatingAvatar, setUpdatingAvatar] = useState(false);

    const [currentPassword, setCurrentPassword] = useState('');
    const [password, setPassword] = useState('');
    const [passwordConfirmation, setPasswordConfirmation] = useState('');
    const [savingPassword, setSavingPassword] = useState(false);
    const [passwordError, setPasswordError] = useState<string | null>(null);

    function handleAvatarChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (!file) return;

        setUpdatingAvatar(true);
        router.post('/mentor/profil/avatar', { avatar: file }, {
            onFinish: () => setUpdatingAvatar(false),
        });
    }

    function saveProfile() {
        router.put('/mentor/profil', { name: nameVal, bio: bioVal, no_hp: noHpVal }, {
            preserveState: true, preserveScroll: true,
        });
    }

    function submitPassword(e: React.FormEvent) {
        e.preventDefault();
        setSavingPassword(true);
        setPasswordError(null);
        router.post('/mentor/profil/password', {
            current_password: currentPassword,
            password,
            password_confirmation: passwordConfirmation,
        }, {
            preserveState: true, preserveScroll: true,
            onSuccess: () => {
                setCurrentPassword(''); setPassword(''); setPasswordConfirmation('');
            },
            onError: (errors) => {
                setPasswordError(errors.current_password || errors.password || 'Gagal memperbarui password.');
            },
            onFinish: () => setSavingPassword(false),
        });
    }

    return (
        <AppLayout searchPlaceholder="Cari profil...">
            <Head title="Profil Saya" />

            <PageHeader
                title="Profil Saya"
                subtitle="Kelola informasi akun mentor kamu."
                breadcrumbs={[{ label: 'Beranda', href: '/mentor' }, { label: 'Profil' }]}
            />

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Left: Avatar + Info */}
                <div className="space-y-5">
                    <div className="glass-card rounded-3xl p-6 text-center space-y-4">
                        <div className="relative inline-block group">
                            <div className="w-24 h-24 rounded-full overflow-hidden bg-primary-fixed mx-auto ring-4 ring-white/60 relative">
                                {user.avatar
                                    ? <img src={user.avatar} alt={user.name} className="w-full h-full object-cover" />
                                    : <div className="w-full h-full flex items-center justify-center">
                                        <Icon name="person" className="text-4xl text-primary-container" filled />
                                      </div>
                                }
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
                            <span className="inline-flex items-center gap-1 mt-2 px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold">
                                <Icon name="supervisor_account" className="text-sm" filled /> Mentor · {total_mentees} Mentee
                            </span>
                        </div>
                    </div>
                </div>

                {/* Right: Edit form + password */}
                <div className="lg:col-span-2 space-y-5">
                    <div className="glass-card rounded-3xl p-6 space-y-4">
                        <h3 className="font-bold text-on-surface flex items-center gap-2 text-sm">
                            <Icon name="edit" className="text-lg" /> Informasi Pribadi
                        </h3>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Nama</label>
                                <input value={nameVal} onChange={e => setNameVal(e.target.value)} className="glass-input w-full text-sm" />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">No. HP</label>
                                <input value={noHpVal} onChange={e => setNoHpVal(e.target.value)} className="glass-input w-full text-sm" />
                            </div>
                        </div>
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Bio</label>
                            <textarea value={bioVal} onChange={e => setBioVal(e.target.value)} rows={3}
                                className="glass-input w-full text-sm resize-none" placeholder="Tulis bio singkat..." />
                        </div>
                        <button onClick={saveProfile} className="px-5 py-2.5 rounded-xl font-bold text-sm bg-primary-container text-white hover:opacity-90 transition-opacity">
                            Simpan Perubahan
                        </button>
                    </div>

                    <div className="glass-card rounded-3xl p-6 space-y-4">
                        <h3 className="font-bold text-on-surface flex items-center gap-2 text-sm">
                            <Icon name="lock" className="text-lg" /> Ubah Password
                        </h3>
                        <form onSubmit={submitPassword} className="space-y-4">
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Password Saat Ini</label>
                                <input type="password" value={currentPassword} onChange={e => setCurrentPassword(e.target.value)}
                                    className="glass-input w-full text-sm" required />
                            </div>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Password Baru</label>
                                    <input type="password" value={password} onChange={e => setPassword(e.target.value)}
                                        className="glass-input w-full text-sm" minLength={8} required />
                                </div>
                                <div>
                                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Konfirmasi Password Baru</label>
                                    <input type="password" value={passwordConfirmation} onChange={e => setPasswordConfirmation(e.target.value)}
                                        className="glass-input w-full text-sm" minLength={8} required />
                                </div>
                            </div>
                            {passwordError && <p className="text-xs text-rose-600 font-semibold">{passwordError}</p>}
                            <button type="submit" disabled={savingPassword}
                                className="px-5 py-2.5 rounded-xl font-bold text-sm bg-primary-container text-white disabled:opacity-50 hover:opacity-90 transition-opacity">
                                {savingPassword ? 'Menyimpan...' : 'Perbarui Password'}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
