import { Head, useForm } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';

interface Settings { app_name:string; app_url:string; mail_driver:string; google_oauth:boolean; shalat_target:number; hafalan_target:number; study_hour_target:number; point_shalat:number; point_hafalan:number; point_akademik:number; point_kegiatan:number; maintenance_mode:boolean }
interface Props { settings:Settings }

export default function Pengaturan({ settings }: Props) {
    const { data, setData, processing } = useForm({ ...settings });

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

                {/* Save */}
                <div className="flex gap-3">
                    <button disabled={processing} className="btn-primary px-8 py-3 rounded-xl font-bold text-sm flex items-center gap-2 hover:opacity-90 transition-opacity disabled:opacity-50">
                        <Icon name="save" className="text-lg" />
                        {processing ? 'Menyimpan...' : 'Simpan Pengaturan'}
                    </button>
                    <button className="px-6 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">
                        Reset Default
                    </button>
                </div>
            </div>
        </AppLayout>
    );
}
