import { useState } from 'react';
import { Head, useForm, usePage, router } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';

interface Settings { app_name:string; app_url:string; mail_driver:string; google_oauth:boolean; shalat_target:number; hafalan_target:number; study_hour_target:number; point_shalat:number; point_hafalan:number; point_akademik:number; point_kegiatan:number; maintenance_mode:boolean }
interface Asrama { id:number; nama_asrama:string; kapasitas:number|null; direktur:string|null; ketua:string|null }
interface Props { settings:Settings; asramas:Asrama[] }

export default function Pengaturan({ settings, asramas }: Props) {
    const { data, setData, post, processing } = useForm({ ...settings });
    const { flash } = usePage<{ flash: { success?: string } }>().props;

    // Asrama state
    const [showAsramaModal, setShowAsramaModal] = useState(false);
    const [editAsrama, setEditAsrama] = useState<Asrama|null>(null);
    const asramaForm = useForm({ nama_asrama: '', kapasitas: '', direktur: '', ketua: '' });

    function openAddAsrama() {
        asramaForm.reset();
        setEditAsrama(null);
        setShowAsramaModal(true);
    }
    function openEditAsrama(a: Asrama) {
        asramaForm.setData({ nama_asrama: a.nama_asrama, kapasitas: a.kapasitas?.toString() ?? '', direktur: a.direktur ?? '', ketua: a.ketua ?? '' });
        setEditAsrama(a);
        setShowAsramaModal(true);
    }
    function submitAsrama() {
        if (editAsrama) {
            asramaForm.put(`/super/asrama/${editAsrama.id}`, { onSuccess: () => setShowAsramaModal(false) });
        } else {
            asramaForm.post('/super/asrama', { onSuccess: () => setShowAsramaModal(false) });
        }
    }
    function deleteAsrama(id: number) {
        if (confirm('Hapus asrama ini?')) router.delete(`/super/asrama/${id}`, { preserveScroll: true });
    }

    function handleSave() {
        post('/super/pengaturan', { preserveScroll: true });
    }

    function Section({ title, icon, children }: { title:string; icon:string; children:React.ReactNode }) {
        return (
            <div className="glass-card rounded-2xl overflow-hidden">
                <div className="flex items-center gap-3 px-6 py-4 border-b border-white/40 bg-surface-container/20">
                    <Icon name={icon} className="text-xl text-primary-container" filled />
                    <h3 className="font-bold text-on-surface">{title}</h3>
                </div>
                <div className="p-6 space-y-5">{children}</div>
            </div>
        );
    }

    function Field({ label, hint, children }: { label:string; hint?:string; children:React.ReactNode }) {
        return (
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-2 items-start">
                <div className="sm:col-span-1">
                    <p className="text-sm font-semibold text-on-surface">{label}</p>
                    {hint && <p className="text-xs text-on-surface-variant mt-0.5">{hint}</p>}
                </div>
                <div className="sm:col-span-2">{children}</div>
            </div>
        );
    }

    function Toggle({ checked, onChange }: { checked:boolean; onChange:(v:boolean)=>void }) {
        return (
            <button onClick={() => onChange(!checked)} className={`relative w-12 h-6 rounded-full transition-colors ${checked ? 'bg-primary-container' : 'bg-surface-container'}`}>
                <div className={`absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-all ${checked ? 'left-7' : 'left-1'}`} />
            </button>
        );
    }

    return (
        <AppLayout>
            <Head title="Pengaturan Sistem" />
            <PageHeader title="Pengaturan Sistem" subtitle="Konfigurasi aplikasi, target, poin, dan integrasi"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Pengaturan' }]} />

            <div className="space-y-6">
                {/* Flash message */}
                {flash?.success && (
                    <div className="flex items-center gap-3 px-5 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-semibold animate-slide-up">
                        <Icon name="check_circle" className="text-xl" filled />
                        {flash.success}
                    </div>
                )}

                {/* App info */}
                <Section title="Informasi Aplikasi" icon="info">
                    <Field label="Nama Aplikasi" hint="Nama yang tampil di header dan email">
                        <input value={data.app_name} onChange={e => setData('app_name', e.target.value)} className="glass-input w-full text-sm" />
                    </Field>
                    <Field label="URL Aplikasi" hint="Base URL untuk email dan redirect">
                        <input value={data.app_url} onChange={e => setData('app_url', e.target.value)} className="glass-input w-full text-sm font-mono" />
                    </Field>
                    <Field label="Mode Maintenance" hint="Matikan akses untuk non-admin sementara">
                        <Toggle checked={data.maintenance_mode} onChange={v => setData('maintenance_mode', v)} />
                    </Field>
                </Section>

                {/* Target harian */}
                <Section title="Target Harian" icon="flag">
                    <Field label="Target Shalat" hint="Jumlah waktu shalat per hari">
                        <div className="flex items-center gap-3">
                            <input type="number" min={1} max={5} value={data.shalat_target} onChange={e => setData('shalat_target', Number(e.target.value))} className="glass-input w-24 text-sm text-center" />
                            <span className="text-sm text-on-surface-variant">waktu / hari</span>
                        </div>
                    </Field>
                    <Field label="Target Hafalan" hint="Target juz hafalan total">
                        <div className="flex items-center gap-3">
                            <input type="number" min={1} max={30} value={data.hafalan_target} onChange={e => setData('hafalan_target', Number(e.target.value))} className="glass-input w-24 text-sm text-center" />
                            <span className="text-sm text-on-surface-variant">juz</span>
                        </div>
                    </Field>
                    <Field label="Target Aktivitas" hint="Jumlah aktivitas per bulan">
                        <div className="flex items-center gap-3">
                            <input type="number" min={1} max={100} value={data.study_hour_target} onChange={e => setData('study_hour_target', Number(e.target.value))} className="glass-input w-24 text-sm text-center" />
                            <span className="text-sm text-on-surface-variant">aktivitas / bulan</span>
                        </div>
                    </Field>
                </Section>

                {/* Sistem poin */}
                <Section title="Sistem Poin" icon="military_tech">
                    {[
                        { key:'point_shalat', label:'Poin Shalat', hint:'Per waktu shalat yang tercatat' },
                        { key:'point_hafalan', label:'Poin Hafalan', hint:'Per setoran hafalan yang diterima' },
                        { key:'point_akademik', label:'Poin Aktivitas', hint:'Per aktivitas yang tercatat' },
                        { key:'point_kegiatan', label:'Poin Kegiatan', hint:'Per kegiatan yang diikuti' },
                    ].map(f => (
                        <Field key={f.key} label={f.label} hint={f.hint}>
                            <div className="flex items-center gap-3">
                                <input type="number" min={1} value={data[f.key as keyof typeof data] as number} onChange={e => setData(f.key as keyof typeof data, Number(e.target.value) as never)} className="glass-input w-24 text-sm text-center" />
                                <span className="text-sm text-on-surface-variant">poin</span>
                            </div>
                        </Field>
                    ))}
                </Section>

                {/* Integrasi */}
                <Section title="Integrasi" icon="link">
                    <Field label="Google OAuth" hint="Login dengan akun Google">
                        <div className="flex items-center gap-3">
                            <Toggle checked={data.google_oauth} onChange={v => setData('google_oauth', v)} />
                            <span className={`text-xs font-bold ${data.google_oauth ? 'text-emerald-600' : 'text-on-surface-variant'}`}>
                                {data.google_oauth ? '● Aktif' : '○ Nonaktif'}
                            </span>
                        </div>
                    </Field>
                    <Field label="Mail Driver" hint="Driver email yang digunakan">
                        <select value={data.mail_driver} onChange={e => setData('mail_driver', e.target.value)} className="glass-input text-sm py-2">
                            <option value="smtp">SMTP</option>
                            <option value="mailgun">Mailgun</option>
                            <option value="ses">Amazon SES</option>
                            <option value="log">Log (Development)</option>
                        </select>
                    </Field>
                </Section>

                {/* Asrama CRUD */}
                <Section title="Manajemen Asrama" icon="apartment">
                    <div className="flex justify-between items-center mb-4">
                        <p className="text-sm text-on-surface-variant">Kelola daftar asrama yang tersedia di sistem.</p>
                        <button onClick={openAddAsrama} className="btn-primary flex items-center gap-2 text-sm px-4 py-2">
                            <Icon name="add" className="text-base" /> Tambah Asrama
                        </button>
                    </div>
                    <div className="space-y-2">
                        {asramas.length === 0 && (
                            <div className="text-center py-8 text-on-surface-variant text-sm">Belum ada asrama. Klik "Tambah Asrama" untuk memulai.</div>
                        )}
                        {asramas.map(a => (
                            <div key={a.id} className="flex items-center justify-between p-4 bg-surface-container/30 rounded-xl border border-white/30">
                                <div className="flex items-center gap-4">
                                    <div className="w-10 h-10 bg-primary-container/10 rounded-xl flex items-center justify-center">
                                        <Icon name="apartment" className="text-primary-container text-lg" />
                                    </div>
                                    <div>
                                        <p className="font-bold text-on-surface text-sm">{a.nama_asrama}</p>
                                        <p className="text-xs text-on-surface-variant">
                                            {a.kapasitas ? `Kapasitas: ${a.kapasitas}` : 'Kapasitas: -'}
                                            {a.ketua ? ` · Ketua: ${a.ketua}` : ''}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex gap-2">
                                    <button onClick={() => openEditAsrama(a)} className="w-8 h-8 rounded-lg bg-white border border-zinc-200 flex items-center justify-center hover:bg-blue-50 text-blue-600 transition-colors">
                                        <Icon name="edit" className="text-sm" />
                                    </button>
                                    <button onClick={() => deleteAsrama(a.id)} className="w-8 h-8 rounded-lg bg-white border border-zinc-200 flex items-center justify-center hover:bg-rose-50 text-rose-500 transition-colors">
                                        <Icon name="delete" className="text-sm" />
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </Section>

                {/* Save */}
                <div className="flex gap-3">
                    <button onClick={handleSave} disabled={processing} className="btn-primary px-8 py-3 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 transition-opacity disabled:opacity-50">
                        <Icon name="save" className="text-lg" />
                        {processing ? 'Menyimpan...' : 'Simpan Pengaturan'}
                    </button>
                    <button className="px-6 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">
                        Reset Default
                    </button>
                </div>
            </div>
            {/* Asrama Modal */}
            {showAsramaModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
                    <div className="bg-white rounded-3xl shadow-2xl w-full max-w-md animate-modal-in">
                        <div className="flex items-center justify-between p-6 border-b border-zinc-100">
                            <h3 className="font-bold text-on-surface">{editAsrama ? 'Edit Asrama' : 'Tambah Asrama'}</h3>
                            <button onClick={() => setShowAsramaModal(false)} className="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center hover:bg-zinc-200">
                                <Icon name="close" className="text-sm" />
                            </button>
                        </div>
                        <div className="p-6 space-y-4">
                            <div>
                                <label className="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5 block">Nama Asrama *</label>
                                <input value={asramaForm.data.nama_asrama} onChange={e => asramaForm.setData('nama_asrama', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. Al-Farabi" />
                                {asramaForm.errors.nama_asrama && <p className="text-xs text-rose-500 mt-1">{asramaForm.errors.nama_asrama}</p>}
                            </div>
                            <div>
                                <label className="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5 block">Kapasitas</label>
                                <input type="number" value={asramaForm.data.kapasitas} onChange={e => asramaForm.setData('kapasitas', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. 30" />
                            </div>
                            <div>
                                <label className="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5 block">Direktur</label>
                                <input value={asramaForm.data.direktur} onChange={e => asramaForm.setData('direktur', e.target.value)} className="glass-input w-full text-sm" placeholder="Nama direktur" />
                            </div>
                            <div>
                                <label className="text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5 block">Ketua Asrama</label>
                                <input value={asramaForm.data.ketua} onChange={e => asramaForm.setData('ketua', e.target.value)} className="glass-input w-full text-sm" placeholder="Nama ketua" />
                            </div>
                        </div>
                        <div className="p-6 pt-0 flex gap-3">
                            <button onClick={submitAsrama} disabled={asramaForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                                {asramaForm.processing ? 'Menyimpan...' : (editAsrama ? 'Simpan Perubahan' : 'Tambah Asrama')}
                            </button>
                            <button onClick={() => setShowAsramaModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant hover:bg-zinc-200">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
