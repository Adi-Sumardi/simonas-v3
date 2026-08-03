import { AppLayout } from '@/Layouts/AppLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { PageHeader } from '@/Components/ui/PageHeader';

interface Warga {
    id: number;
    name: string;
    email: string;
    no_induk: string | null;
    asrama: string | null;
    status_warga: string | null;
    tgl_masuk: string | null;
    angkatan: string | null;
    no_telp: string | null;
    alamat: string | null;
    semester: number | null;
    tingkat_keanggotaan: string | null;
    tgl_mulai_percobaan: string | null;
    tgl_akhir_percobaan: string | null;
}

interface Props {
    warga: Warga;
    asramas: string[];
}

function FormLabel({ children }: { children: React.ReactNode }) {
    return <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">{children}</label>;
}

export default function WargaEdit({ warga, asramas }: Props) {
    const { data, setData, put, processing, errors } = useForm({
        name: warga.name ?? '',
        email: warga.email ?? '',
        no_induk: warga.no_induk ?? '',
        asrama: warga.asrama ?? '',
        status_warga: warga.status_warga ?? 'aktif',
        tgl_masuk: warga.tgl_masuk ?? '',
        angkatan: warga.angkatan ?? '',
        no_telp: warga.no_telp ?? '',
        alamat: warga.alamat ?? '',
        semester: warga.semester ?? '',
        tingkat_keanggotaan: warga.tingkat_keanggotaan ?? 'percobaan',
        tgl_mulai_percobaan: warga.tgl_mulai_percobaan ?? '',
        tgl_akhir_percobaan: warga.tgl_akhir_percobaan ?? '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        put(`/super/warga/${warga.id}`);
    }

    return (
        <AppLayout>
            <Head title={`Edit Warga - ${warga.name}`} />

            <div className="mb-6">
                <Link href={`/super/warga/${warga.id}`} className="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary-container transition-colors">
                    <Icon name="arrow_back" className="text-lg" />
                    Kembali ke Detail Warga
                </Link>
            </div>

            <PageHeader
                title={`Edit ${warga.name}`}
                subtitle="Perbarui data profil warga"
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Warga', href: '/super/warga' }, { label: warga.name, href: `/super/warga/${warga.id}` }, { label: 'Edit' }]}
            />

            <form onSubmit={submit} className="glass-card rounded-2xl p-6 space-y-5 max-w-3xl">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <FormLabel>Nama Lengkap *</FormLabel>
                        <input value={data.name} onChange={e => setData('name', e.target.value)} className="glass-input w-full text-sm" />
                        {errors.name && <p className="text-xs text-rose-500 mt-1">{errors.name}</p>}
                    </div>
                    <div>
                        <FormLabel>Email *</FormLabel>
                        <input type="email" value={data.email} onChange={e => setData('email', e.target.value)} className="glass-input w-full text-sm" />
                        {errors.email && <p className="text-xs text-rose-500 mt-1">{errors.email}</p>}
                    </div>
                    <div>
                        <FormLabel>No. Induk</FormLabel>
                        <input value={data.no_induk} onChange={e => setData('no_induk', e.target.value)} className="glass-input w-full text-sm" />
                        {errors.no_induk && <p className="text-xs text-rose-500 mt-1">{errors.no_induk}</p>}
                    </div>
                    <div>
                        <FormLabel>Asrama</FormLabel>
                        <select value={data.asrama} onChange={e => setData('asrama', e.target.value)} className="glass-input w-full text-sm py-2">
                            <option value="">— Pilih Asrama —</option>
                            {asramas.map(a => <option key={a} value={a}>{a}</option>)}
                        </select>
                        {errors.asrama && <p className="text-xs text-rose-500 mt-1">{errors.asrama}</p>}
                    </div>
                    <div>
                        <FormLabel>Akun</FormLabel>
                        <select value={data.status_warga} onChange={e => setData('status_warga', e.target.value)} className="glass-input w-full text-sm py-2">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                        {errors.status_warga && <p className="text-xs text-rose-500 mt-1">{errors.status_warga}</p>}
                    </div>
                    <div>
                        <FormLabel>Angkatan</FormLabel>
                        <input value={data.angkatan} onChange={e => setData('angkatan', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. 2021" />
                        {errors.angkatan && <p className="text-xs text-rose-500 mt-1">{errors.angkatan}</p>}
                    </div>
                    <div>
                        <FormLabel>Tanggal Masuk</FormLabel>
                        <input type="date" value={data.tgl_masuk} onChange={e => setData('tgl_masuk', e.target.value)} className="glass-input w-full text-sm" />
                        {errors.tgl_masuk && <p className="text-xs text-rose-500 mt-1">{errors.tgl_masuk}</p>}
                    </div>
                    <div>
                        <FormLabel>No. Telp</FormLabel>
                        <input value={data.no_telp} onChange={e => setData('no_telp', e.target.value)} className="glass-input w-full text-sm" />
                        {errors.no_telp && <p className="text-xs text-rose-500 mt-1">{errors.no_telp}</p>}
                    </div>
                    <div>
                        <FormLabel>Semester</FormLabel>
                        <input type="number" min={1} max={14} value={data.semester} onChange={e => setData('semester', e.target.value)} className="glass-input w-full text-sm" />
                        {errors.semester && <p className="text-xs text-rose-500 mt-1">{errors.semester}</p>}
                    </div>
                    <div>
                        <FormLabel>Status</FormLabel>
                        <select value={data.tingkat_keanggotaan} onChange={e => setData('tingkat_keanggotaan', e.target.value)} className="glass-input w-full text-sm py-2">
                            <option value="percobaan">Percobaan</option>
                            <option value="tetap">Tetap</option>
                            <option value="senior">Senior</option>
                        </select>
                        {errors.tingkat_keanggotaan && <p className="text-xs text-rose-500 mt-1">{errors.tingkat_keanggotaan}</p>}
                    </div>
                    <div>
                        <FormLabel>Tanggal Mulai Percobaan</FormLabel>
                        <input type="date" value={data.tgl_mulai_percobaan} onChange={e => setData('tgl_mulai_percobaan', e.target.value)} className="glass-input w-full text-sm" />
                        {errors.tgl_mulai_percobaan && <p className="text-xs text-rose-500 mt-1">{errors.tgl_mulai_percobaan}</p>}
                    </div>
                    <div>
                        <FormLabel>Tanggal Akhir Percobaan</FormLabel>
                        <input type="date" value={data.tgl_akhir_percobaan} onChange={e => setData('tgl_akhir_percobaan', e.target.value)} className="glass-input w-full text-sm" />
                        {errors.tgl_akhir_percobaan && <p className="text-xs text-rose-500 mt-1">{errors.tgl_akhir_percobaan}</p>}
                    </div>
                    <div className="sm:col-span-2">
                        <FormLabel>Alamat</FormLabel>
                        <textarea value={data.alamat} onChange={e => setData('alamat', e.target.value)} rows={3} className="glass-input w-full text-sm" />
                        {errors.alamat && <p className="text-xs text-rose-500 mt-1">{errors.alamat}</p>}
                    </div>
                </div>

                <div className="flex gap-3 pt-2">
                    <button type="submit" disabled={processing} className="btn-primary px-6 py-3 rounded-xl font-bold text-sm">
                        {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                    </button>
                    <Link href={`/super/warga/${warga.id}`} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">
                        Batal
                    </Link>
                </div>
            </form>
        </AppLayout>
    );
}
