import { Head, useForm } from '@inertiajs/react';
import { FormEvent, useState } from 'react';
import { AuthLayout } from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Label } from '@/Components/ui/Label';
import { Icon } from '@/Components/ui/Icon';

interface UserData {
    name: string;
    role: 'mahasiswa' | 'mentor' | 'super' | 'alumni';
    no_hp: string | null;
    universitas: string | null;
    fakultas: string | null;
    prodi: string | null;
    semester: number | null;
    angkatan: string | null;
    alamat: string | null;
    provinsi: string | null;
    kota: string | null;
    kecamatan: string | null;
    nama_ayah: string | null;
    nama_ibu: string | null;
    no_telp: string | null;
    avatar: string | null;
}

interface Props { user: UserData }

const STEPS = [
    { key: 'kontak', label: 'Kontak & Foto', icon: 'person' },
    { key: 'akademik', label: 'Data Akademik', icon: 'school' },
    { key: 'alamat', label: 'Alamat & Kontak Darurat', icon: 'home' },
];

export default function Onboarding({ user }: Props) {
    const isMahasiswa = user.role === 'mahasiswa';
    const [step, setStep] = useState(0);
    const [avatarPreview, setAvatarPreview] = useState<string | null>(null);

    const { data, setData, post, processing, errors } = useForm({
        no_hp: user.no_hp ?? '',
        universitas: user.universitas ?? '',
        fakultas: user.fakultas ?? '',
        prodi: user.prodi ?? '',
        semester: user.semester ?? '',
        alamat: user.alamat ?? '',
        provinsi: user.provinsi ?? '',
        kota: user.kota ?? '',
        kecamatan: user.kecamatan ?? '',
        nama_ayah: user.nama_ayah ?? '',
        nama_ibu: user.nama_ibu ?? '',
        no_telp: user.no_telp ?? '',
        avatar: null as File | null,
    });

    const stepFields: Record<number, (keyof typeof data)[]> = {
        0: ['no_hp'],
        1: isMahasiswa ? ['universitas', 'prodi', 'semester'] : [],
        2: ['alamat'],
    };

    function isStepValid(i: number) {
        return stepFields[i].every(f => String(data[f] ?? '').trim() !== '');
    }

    function next() {
        if (isStepValid(step)) setStep(s => Math.min(s + 1, STEPS.length - 1));
    }

    function back() {
        setStep(s => Math.max(s - 1, 0));
    }

    function handleAvatarChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0] ?? null;
        setData('avatar', file);
        if (file) setAvatarPreview(URL.createObjectURL(file));
    }

    function submit(e: FormEvent) {
        e.preventDefault();
        post('/onboarding', { forceFormData: true });
    }

    const progress = ((step + 1) / STEPS.length) * 100;

    return (
        <AuthLayout>
            <Head title="Lengkapi Profil" />
            <div className="glass-panel w-full max-w-lg p-8">
                <div className="flex flex-col items-center text-center mb-6">
                    <div className="w-16 h-16 rounded-2xl overflow-hidden mb-4 shadow-glow">
                        <img src="/images/simonas_logo.png" alt="SIMONAS" className="w-full h-full object-cover" />
                    </div>
                    <h1 className="font-display text-headline-md text-primary-container">Selamat Datang, {user.name.split(' ')[0]}!</h1>
                    <p className="text-body-sm text-on-surface-variant mt-1">
                        Lengkapi profil kamu dulu sebelum mulai pakai SIMONAS.
                    </p>
                </div>

                {/* Progress steps */}
                <div className="flex items-center justify-between mb-6">
                    {STEPS.map((s, i) => (
                        <div key={s.key} className="flex items-center flex-1">
                            <div className="flex flex-col items-center gap-1 flex-shrink-0">
                                <div className={`w-9 h-9 rounded-full flex items-center justify-center text-sm font-black transition-colors ${
                                    i < step ? 'bg-emerald-500 text-white' : i === step ? 'bg-primary-container text-white' : 'bg-surface-container text-on-surface-variant'
                                }`}>
                                    {i < step ? <Icon name="check" className="text-lg" /> : <Icon name={s.icon} className="text-lg" />}
                                </div>
                                <span className="text-[10px] font-bold text-on-surface-variant text-center max-w-[70px]">{s.label}</span>
                            </div>
                            {i < STEPS.length - 1 && (
                                <div className={`h-0.5 flex-1 mx-1 ${i < step ? 'bg-emerald-500' : 'bg-surface-container'}`} />
                            )}
                        </div>
                    ))}
                </div>
                <div className="h-1 bg-surface-container rounded-full overflow-hidden mb-8">
                    <div className="h-full bg-primary-container rounded-full transition-all" style={{ width: `${progress}%` }} />
                </div>

                <form onSubmit={submit} className="space-y-5">
                    {step === 0 && (
                        <>
                            <div className="flex flex-col items-center gap-3 mb-2">
                                <div className="w-20 h-20 rounded-full overflow-hidden bg-surface-container flex items-center justify-center border-2 border-white/60 shadow">
                                    {avatarPreview || user.avatar ? (
                                        <img src={avatarPreview ?? `/storage/${user.avatar}`} alt="Avatar" className="w-full h-full object-cover" />
                                    ) : (
                                        <Icon name="person" className="text-4xl text-on-surface-variant" />
                                    )}
                                </div>
                                <label className="text-xs font-bold text-primary-container cursor-pointer hover:underline">
                                    Pilih Foto Profil
                                    <input type="file" accept="image/jpeg,image/png" className="hidden" onChange={handleAvatarChange} />
                                </label>
                                {errors.avatar && <p className="text-body-sm text-error">{errors.avatar}</p>}
                            </div>
                            <div>
                                <Label htmlFor="no_hp">No. HP / WhatsApp</Label>
                                <Input id="no_hp" value={data.no_hp} onChange={e => setData('no_hp', e.target.value)}
                                    placeholder="08xxxxxxxxxx" leftIcon={<Icon name="call" className="text-xl" />} error={errors.no_hp} required />
                            </div>
                        </>
                    )}

                    {step === 1 && (
                        <>
                            {!isMahasiswa && (
                                <p className="text-xs text-on-surface-variant -mt-1 mb-1">Opsional untuk role kamu, isi kalau relevan.</p>
                            )}
                            <div>
                                <Label htmlFor="universitas">Universitas / Kampus</Label>
                                <Input id="universitas" value={data.universitas} onChange={e => setData('universitas', e.target.value)}
                                    leftIcon={<Icon name="school" className="text-xl" />} error={errors.universitas} required={isMahasiswa} />
                            </div>
                            <div>
                                <Label htmlFor="fakultas">Fakultas</Label>
                                <Input id="fakultas" value={data.fakultas} onChange={e => setData('fakultas', e.target.value)}
                                    error={errors.fakultas} />
                            </div>
                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <Label htmlFor="prodi">Jurusan / Prodi</Label>
                                    <Input id="prodi" value={data.prodi} onChange={e => setData('prodi', e.target.value)}
                                        error={errors.prodi} required={isMahasiswa} />
                                </div>
                                <div>
                                    <Label htmlFor="semester">Semester</Label>
                                    <Input id="semester" type="number" min={1} max={14} value={data.semester}
                                        onChange={e => setData('semester', e.target.value)} error={errors.semester} required={isMahasiswa} />
                                </div>
                            </div>
                        </>
                    )}

                    {step === 2 && (
                        <>
                            <div>
                                <Label htmlFor="alamat">Alamat Lengkap</Label>
                                <Input id="alamat" value={data.alamat} onChange={e => setData('alamat', e.target.value)}
                                    leftIcon={<Icon name="home" className="text-xl" />} error={errors.alamat} required />
                            </div>
                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <Label htmlFor="provinsi">Provinsi</Label>
                                    <Input id="provinsi" value={data.provinsi} onChange={e => setData('provinsi', e.target.value)} error={errors.provinsi} />
                                </div>
                                <div>
                                    <Label htmlFor="kota">Kota/Kabupaten</Label>
                                    <Input id="kota" value={data.kota} onChange={e => setData('kota', e.target.value)} error={errors.kota} />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <Label htmlFor="nama_ayah">Nama Ayah (kontak darurat)</Label>
                                    <Input id="nama_ayah" value={data.nama_ayah} onChange={e => setData('nama_ayah', e.target.value)} error={errors.nama_ayah} />
                                </div>
                                <div>
                                    <Label htmlFor="nama_ibu">Nama Ibu (kontak darurat)</Label>
                                    <Input id="nama_ibu" value={data.nama_ibu} onChange={e => setData('nama_ibu', e.target.value)} error={errors.nama_ibu} />
                                </div>
                            </div>
                            <div>
                                <Label htmlFor="no_telp">No. Telp Keluarga</Label>
                                <Input id="no_telp" value={data.no_telp} onChange={e => setData('no_telp', e.target.value)} error={errors.no_telp} />
                            </div>
                        </>
                    )}

                    <div className="flex items-center gap-3 pt-2">
                        {step > 0 && (
                            <Button type="button" variant="secondary" onClick={back} disabled={processing}>
                                <Icon name="arrow_back" /> Kembali
                            </Button>
                        )}
                        {step < STEPS.length - 1 ? (
                            <Button type="button" fullWidth onClick={next} disabled={!isStepValid(step)}>
                                Lanjut <Icon name="arrow_forward" />
                            </Button>
                        ) : (
                            <Button type="submit" fullWidth disabled={processing || !isStepValid(step)}>
                                {processing ? 'Menyimpan...' : 'Selesai'}
                                {!processing && <Icon name="check" />}
                            </Button>
                        )}
                    </div>
                </form>
            </div>
        </AuthLayout>
    );
}
