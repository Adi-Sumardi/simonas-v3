import { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';

// ─── Interfaces ───────────────────────────────────────────────────────────────
interface Settings { app_name:string; app_url:string; mail_driver:string; google_oauth:boolean; maintenance_mode:boolean }

interface PointRule { id:number; label:string; activity_type:string; poin:number; unit:string|null; is_active:boolean }
interface DailyTarget { id:number; label:string; key:string; value:number; unit:string|null; description:string|null; is_active:boolean }
interface AsramaJabatan { id:number; tahun:number; direktur:string|null; ketua:string|null }
interface Asrama { id:number; nama_asrama:string; kapasitas:number|null; jabatans:AsramaJabatan[] }
interface Komponen { id:number; kode:string; nama_komponen:string; aspek:string; bobot:number }

// ─── Komponen Penilaian (3-level) ─────────────────────────────────────────────
interface JenisKegiatan {
    id:number; nama_kegiatan:string; urutan:number;
    poin_a:number|null; poin_p:number|null; poin_f:number|null; poin_u:number|null;
    poin_w:number|null; poin_n:number|null; poin_i:number|null;
    keterangan_bukti:string|null;
}
interface SubAspek { id:number; nama_sub_aspek:string; urutan:number; jenis_kegiatans:JenisKegiatan[] }
interface KomponenPenilaianAspek { id:number; kode:string; nama_aspek:string; urutan:number; sub_aspeks:SubAspek[] }

interface Props {
    settings: Settings;
    asramas: Asrama[];
    pointRules: PointRule[];
    dailyTargets: DailyTarget[];
    activityTypes: Record<string, string>;
    komponens: Komponen[];
    aspekList: string[];
    komponenPenilaian: KomponenPenilaianAspek[];
}

// ─── Badge colours per activity type ─────────────────────────────────────────
const TYPE_COLORS: Record<string, string> = {
    shalat:     'bg-blue-100 text-blue-700',
    hafalan:    'bg-emerald-100 text-emerald-700',
    kegiatan:   'bg-violet-100 text-violet-700',
    akademik:   'bg-amber-100 text-amber-700',
    leadership: 'bg-rose-100 text-rose-700',
    karakter:   'bg-cyan-100 text-cyan-700',
    kreatif:    'bg-orange-100 text-orange-700',
};

// ─── Re-usable Modal shell ────────────────────────────────────────────────────
function ModalShell({ title, subtitle, onClose, children }: { title:string; subtitle?:string; onClose:()=>void; children:React.ReactNode }) {
    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div className="bg-white rounded-3xl shadow-2xl w-full max-w-md animate-modal-in">
                <div className="flex items-center justify-between p-6 border-b border-zinc-100">
                    <div>
                        <h3 className="font-bold text-on-surface">{title}</h3>
                        {subtitle && <p className="text-xs text-on-surface-variant mt-0.5">{subtitle}</p>}
                    </div>
                    <button onClick={onClose} className="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center hover:bg-zinc-200">
                        <Icon name="close" className="text-sm" />
                    </button>
                </div>
                {children}
            </div>
        </div>
    );
}

// ─── Layout helpers (harus di luar komponen utama — kalau dideklarasikan di
// dalam Pengaturan(), fungsi ini dibuat ulang tiap render dan React akan
// unmount/remount seluruh Section pada tiap perubahan state, termasuk
// mereset posisi scroll ke atas) ─────────────────────────────────────────────
function Section({ title, icon, children, action }: { title:string; icon:string; children:React.ReactNode; action?:React.ReactNode }) {
    return (
        <div className="glass-card rounded-2xl overflow-hidden">
            <div className="flex items-center justify-between px-6 py-4 border-b border-white/40 bg-surface-container/20">
                <div className="flex items-center gap-3">
                    <Icon name={icon} className="text-xl text-primary-container" filled />
                    <h3 className="font-bold text-on-surface">{title}</h3>
                </div>
                {action}
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
        <button type="button" onClick={() => onChange(!checked)} className={`relative w-12 h-6 rounded-full transition-colors ${checked ? 'bg-primary-container' : 'bg-surface-container'}`}>
            <div className={`absolute top-1 w-4 h-4 rounded-full bg-white shadow transition-all ${checked ? 'left-7' : 'left-1'}`} />
        </button>
    );
}

function ActivePill({ active, onToggle }: { active:boolean; onToggle:()=>void }) {
    return (
        <button type="button" onClick={onToggle} className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-black transition-colors ${active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-zinc-100 text-zinc-500 hover:bg-zinc-200'}`}>
            <span className={`w-1.5 h-1.5 rounded-full ${active ? 'bg-emerald-500' : 'bg-zinc-400'}`} />
            {active ? 'Aktif' : 'Nonaktif'}
        </button>
    );
}

function FormLabel({ children }: { children: React.ReactNode }) {
    return <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">{children}</label>;
}

// ─── Main ─────────────────────────────────────────────────────────────────────
export default function Pengaturan({ settings, asramas, pointRules, dailyTargets, activityTypes, komponens, aspekList, komponenPenilaian }: Props) {
    const { data, setData, post, processing } = useForm({ ...settings });

    // ── Asrama state ──────────────────────────────────────────────────────────
    const [showAsramaModal, setShowAsramaModal]   = useState(false);
    const [editAsrama, setEditAsrama]             = useState<Asrama|null>(null);
    const asramaForm = useForm({ nama_asrama: '', kapasitas: '' });
    const [expandedAsrama, setExpandedAsrama]     = useState<number|null>(null);
    const [showJabatanModal, setShowJabatanModal] = useState(false);
    const [jabatanAsrama, setJabatanAsrama]       = useState<Asrama|null>(null);
    const [editJabatan, setEditJabatan]           = useState<AsramaJabatan|null>(null);
    const jabatanForm = useForm({ tahun: new Date().getFullYear().toString(), direktur: '', ketua: '' });

    // ── Point Rule state ──────────────────────────────────────────────────────
    const [showRuleModal, setShowRuleModal]   = useState(false);
    const [editRule, setEditRule]             = useState<PointRule|null>(null);
    const ruleForm = useForm<{ label:string; activity_type:string; poin:string; unit:string }>({ label: '', activity_type: 'shalat', poin: '', unit: '' });

    // ── Daily Target state ────────────────────────────────────────────────────
    const [showTargetModal, setShowTargetModal] = useState(false);
    const [editTarget, setEditTarget]           = useState<DailyTarget|null>(null);
    const targetForm = useForm<{ label:string; key:string; value:string; unit:string; description:string }>({ label: '', key: '', value: '', unit: '', description: '' });

    // ── Komponen state (lama) ────────────────────────────────────────────────────
    const [komponenTab, setKomponenTab]         = useState(aspekList[0]);
    const [showKomponenModal, setShowKomponenModal] = useState(false);
    const [editKomponen, setEditKomponen]       = useState<Komponen|null>(null);
    const komponenForm = useForm<{ kode:string; nama_komponen:string; aspek:string; bobot:string }>({ kode: '', nama_komponen: '', aspek: aspekList[0], bobot: '1' });

    // ── Komponen Penilaian Baru (3-level) state ─────────────────────────────────
    const ASPEK_ICONS: Record<string,string> = { akademik:'📚', leadership:'👑', karakter_islami:'🕌', kreatifitas:'🎨' };
    const [kpTab, setKpTab]                     = useState<string>(komponenPenilaian[0]?.kode ?? 'akademik');
    const [expandedSub, setExpandedSub]         = useState<number|null>(null);
    const [showJenisModal, setShowJenisModal]   = useState(false);
    const [editJenis, setEditJenis]             = useState<JenisKegiatan|null>(null);
    const [jenisSubAspekId, setJenisSubAspekId] = useState<number|null>(null);
    const [showSubModal, setShowSubModal]       = useState(false);
    const [editSub, setEditSub]                 = useState<SubAspek|null>(null);
    const jenisForm = useForm<{
        nama_kegiatan:string; urutan:string;
        poin_a:string; poin_p:string; poin_f:string; poin_u:string;
        poin_w:string; poin_n:string; poin_i:string;
        keterangan_bukti:string;
    }>({ nama_kegiatan:'', urutan:'', poin_a:'', poin_p:'', poin_f:'', poin_u:'', poin_w:'', poin_n:'', poin_i:'', keterangan_bukti:'' });
    const subForm = useForm<{ nama_sub_aspek:string; aspek_id:string; urutan:string }>({ nama_sub_aspek:'', aspek_id:'', urutan:'' });

    // Handlers
    function openAddSub(aspekId: number) {
        subForm.reset(); subForm.setData({ nama_sub_aspek:'', aspek_id: String(aspekId), urutan:'' });
        setEditSub(null); setShowSubModal(true);
    }
    function openEditSub(s: SubAspek) {
        subForm.setData({ nama_sub_aspek: s.nama_sub_aspek, aspek_id:'', urutan: String(s.urutan) });
        setEditSub(s); setShowSubModal(true);
    }
    function submitSub() {
        if (editSub) subForm.put(`/super/sub-aspek/${editSub.id}`, { onSuccess: () => setShowSubModal(false) });
        else         subForm.post('/super/sub-aspek', { onSuccess: () => setShowSubModal(false) });
    }
    function deleteSub(id: number) { if (confirm('Hapus Sub-Aspek ini beserta semua jenis kegiatannya?')) router.delete(`/super/sub-aspek/${id}`, { preserveScroll: true }); }

    function openAddJenis(subId: number) {
        jenisForm.reset(); setEditJenis(null); setJenisSubAspekId(subId); setShowJenisModal(true);
    }
    function openEditJenis(j: JenisKegiatan) {
        jenisForm.setData({
            nama_kegiatan: j.nama_kegiatan, urutan: String(j.urutan),
            poin_a: j.poin_a != null ? String(j.poin_a) : '',
            poin_p: j.poin_p != null ? String(j.poin_p) : '',
            poin_f: j.poin_f != null ? String(j.poin_f) : '',
            poin_u: j.poin_u != null ? String(j.poin_u) : '',
            poin_w: j.poin_w != null ? String(j.poin_w) : '',
            poin_n: j.poin_n != null ? String(j.poin_n) : '',
            poin_i: j.poin_i != null ? String(j.poin_i) : '',
            keterangan_bukti: j.keterangan_bukti ?? '',
        });
        setEditJenis(j); setShowJenisModal(true);
    }
    function submitJenis() {
        if (editJenis) jenisForm.put(`/super/jenis/${editJenis.id}`, { onSuccess: () => setShowJenisModal(false) });
        else if (jenisSubAspekId) jenisForm.post(`/super/sub-aspek/${jenisSubAspekId}/jenis`, { onSuccess: () => setShowJenisModal(false) });
    }
    function deleteJenis(id: number) { if (confirm('Hapus jenis kegiatan ini?')) router.delete(`/super/jenis/${id}`, { preserveScroll: true }); }

    // ── Komponen handlers ─────────────────────────────────────────────────────
    function openAddKomponen() {
        komponenForm.clearErrors();
        komponenForm.setData({ kode: '', nama_komponen: '', aspek: komponenTab, bobot: '1' });
        setEditKomponen(null);
        setShowKomponenModal(true);
    }
    function openEditKomponen(k: Komponen) {
        komponenForm.clearErrors();
        komponenForm.setData({ kode: k.kode, nama_komponen: k.nama_komponen, aspek: k.aspek, bobot: String(k.bobot) });
        setEditKomponen(k);
        setShowKomponenModal(true);
    }
    function submitKomponen() {
        if (editKomponen) komponenForm.put(`/super/komponen/${editKomponen.id}`, { onSuccess: () => setShowKomponenModal(false) });
        else               komponenForm.post('/super/komponen', { onSuccess: () => setShowKomponenModal(false) });
    }
    function deleteKomponen(id: number) { if (confirm('Hapus komponen ini?')) router.delete(`/super/komponen/${id}`, { preserveScroll: true }); }

    // ── Asrama handlers ───────────────────────────────────────────────────────
    function openAddAsrama() { asramaForm.reset(); setEditAsrama(null); setShowAsramaModal(true); }
    function openEditAsrama(a: Asrama) { asramaForm.setData({ nama_asrama: a.nama_asrama, kapasitas: a.kapasitas?.toString() ?? '' }); setEditAsrama(a); setShowAsramaModal(true); }
    function submitAsrama() {
        if (editAsrama) asramaForm.put(`/super/asrama/${editAsrama.id}`, { onSuccess: () => setShowAsramaModal(false) });
        else            asramaForm.post('/super/asrama', { onSuccess: () => setShowAsramaModal(false) });
    }
    function deleteAsrama(id: number) { if (confirm('Hapus asrama ini?')) router.delete(`/super/asrama/${id}`, { preserveScroll: true }); }
    function openAddJabatan(a: Asrama) { jabatanForm.reset(); jabatanForm.setData({ tahun: new Date().getFullYear().toString(), direktur: '', ketua: '' }); setJabatanAsrama(a); setEditJabatan(null); setShowJabatanModal(true); }
    function openEditJabatan(a: Asrama, j: AsramaJabatan) { jabatanForm.setData({ tahun: j.tahun.toString(), direktur: j.direktur ?? '', ketua: j.ketua ?? '' }); setJabatanAsrama(a); setEditJabatan(j); setShowJabatanModal(true); }
    function submitJabatan() {
        if (!jabatanAsrama) return;
        if (editJabatan) jabatanForm.put(`/super/asrama/${jabatanAsrama.id}/jabatan/${editJabatan.id}`, { onSuccess: () => setShowJabatanModal(false) });
        else             jabatanForm.post(`/super/asrama/${jabatanAsrama.id}/jabatan`, { onSuccess: () => setShowJabatanModal(false) });
    }
    function deleteJabatan(asramaId: number, jabatanId: number) { if (confirm('Hapus data jabatan ini?')) router.delete(`/super/asrama/${asramaId}/jabatan/${jabatanId}`, { preserveScroll: true }); }

    // ── Point Rule handlers ───────────────────────────────────────────────────
    function openAddRule() {
        ruleForm.clearErrors();
        ruleForm.setData({ label: '', activity_type: 'shalat', poin: '', unit: '' });
        setEditRule(null);
        setShowRuleModal(true);
    }
    function openEditRule(r: PointRule) {
        ruleForm.clearErrors();
        ruleForm.setData({ label: r.label, activity_type: r.activity_type, poin: String(r.poin), unit: r.unit ?? '' });
        setEditRule(r);
        setShowRuleModal(true);
    }
    function submitRule() {
        if (editRule) ruleForm.put(`/super/point-rules/${editRule.id}`, { onSuccess: () => setShowRuleModal(false) });
        else          ruleForm.post('/super/point-rules', { onSuccess: () => setShowRuleModal(false) });
    }
    function deleteRule(id: number) { if (confirm('Hapus aturan poin ini?')) router.delete(`/super/point-rules/${id}`, { preserveScroll: true }); }
    function toggleRule(id: number) { router.patch(`/super/point-rules/${id}/toggle`, {}, { preserveScroll: true }); }

    // ── Daily Target handlers ─────────────────────────────────────────────────
    function openAddTarget() {
        targetForm.clearErrors();
        targetForm.setData({ label: '', key: '', value: '', unit: '', description: '' });
        setEditTarget(null);
        setShowTargetModal(true);
    }
    function openEditTarget(t: DailyTarget) {
        targetForm.clearErrors();
        targetForm.setData({ label: t.label, key: t.key, value: String(t.value), unit: t.unit ?? '', description: t.description ?? '' });
        setEditTarget(t);
        setShowTargetModal(true);
    }
    function submitTarget() {
        if (editTarget) targetForm.put(`/super/daily-targets/${editTarget.id}`, { onSuccess: () => setShowTargetModal(false) });
        else            targetForm.post('/super/daily-targets', { onSuccess: () => setShowTargetModal(false) });
    }
    function deleteTarget(id: number) { if (confirm('Hapus target ini?')) router.delete(`/super/daily-targets/${id}`, { preserveScroll: true }); }
    function toggleTarget(id: number) { router.patch(`/super/daily-targets/${id}/toggle`, {}, { preserveScroll: true }); }

    function handleSave() { post('/super/pengaturan', { preserveScroll: true }); }

    return (
        <AppLayout>
            <Head title="Pengaturan Sistem" />
            <PageHeader title="Pengaturan Sistem" subtitle="Konfigurasi aplikasi, target, poin, dan integrasi"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Pengaturan' }]} />

            <div className="space-y-6">

                {/* ── App info ─────────────────────────────────────────────── */}
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
                    <div className="pt-2">
                        <button onClick={handleSave} disabled={processing} className="btn-primary px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 disabled:opacity-50">
                            <Icon name="save" className="text-base" />
                            {processing ? 'Menyimpan...' : 'Simpan'}
                        </button>
                    </div>
                </Section>

                {/* ── Target Harian CRUD ────────────────────────────────────── */}
                <Section title="Target Harian" icon="flag"
                    action={
                        <button onClick={openAddTarget} className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary-container text-white text-xs font-bold hover:opacity-90 transition-opacity">
                            <Icon name="add" className="text-sm" /> Tambah Target
                        </button>
                    }>
                    <p className="text-xs text-on-surface-variant -mt-3">
                        Target yang aktif dipakai sebagai acuan progress dashboard mahasiswa. Klik nilai untuk mengedit langsung.
                    </p>

                    {dailyTargets.length === 0 ? (
                        <div className="text-center py-8 text-on-surface-variant text-sm">Belum ada target.</div>
                    ) : (
                        <div className="overflow-x-auto -mx-6">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b border-white/40">
                                        <th className="text-left px-6 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Label</th>
                                        <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Nilai</th>
                                        <th className="text-left px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Satuan</th>
                                        <th className="text-left px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest hidden sm:table-cell">Keterangan</th>
                                        <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Status</th>
                                        <th className="px-6 py-2.5" />
                                    </tr>
                                </thead>
                                <tbody>
                                    {dailyTargets.map(t => (
                                        <tr key={t.id} className={`border-b border-white/20 transition-colors hover:bg-white/20 ${!t.is_active ? 'opacity-50' : ''}`}>
                                            <td className="px-6 py-3">
                                                <p className="font-bold text-on-surface text-sm">{t.label}</p>
                                                <p className="text-[10px] text-on-surface-variant font-mono">{t.key}</p>
                                            </td>
                                            <td className="px-4 py-3 text-center">
                                                <span className="font-black text-2xl text-primary-container">{t.value}</span>
                                            </td>
                                            <td className="px-4 py-3 text-xs text-on-surface-variant">{t.unit || '—'}</td>
                                            <td className="px-4 py-3 text-xs text-on-surface-variant hidden sm:table-cell">{t.description || '—'}</td>
                                            <td className="px-4 py-3 text-center">
                                                <ActivePill active={t.is_active} onToggle={() => toggleTarget(t.id)} />
                                            </td>
                                            <td className="px-6 py-3">
                                                <div className="flex gap-1 justify-end">
                                                    <button onClick={() => openEditTarget(t)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-blue-50 text-blue-600 transition-colors">
                                                        <Icon name="edit" className="text-xs" />
                                                    </button>
                                                    <button onClick={() => deleteTarget(t.id)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-rose-50 text-rose-500 transition-colors">
                                                        <Icon name="delete" className="text-xs" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </Section>

                {/* ── Sistem Poin CRUD ──────────────────────────────────────── */}
                <Section title="Sistem Poin" icon="military_tech"
                    action={
                        <button onClick={openAddRule} className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary-container text-white text-xs font-bold hover:opacity-90 transition-opacity">
                            <Icon name="add" className="text-sm" /> Tambah Aturan
                        </button>
                    }>
                    <p className="text-xs text-on-surface-variant -mt-3">
                        Setiap aturan aktif dihitung otomatis ke total poin mahasiswa. Beberapa aturan per aktivitas diperbolehkan.
                    </p>

                    {pointRules.length === 0 ? (
                        <div className="text-center py-8 text-on-surface-variant text-sm">Belum ada aturan poin.</div>
                    ) : (
                        <div className="overflow-x-auto -mx-6">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b border-white/40">
                                        <th className="text-left px-6 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Label</th>
                                        <th className="text-left px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Aktivitas</th>
                                        <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Poin</th>
                                        <th className="text-left px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest hidden sm:table-cell">Satuan</th>
                                        <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Status</th>
                                        <th className="px-6 py-2.5" />
                                    </tr>
                                </thead>
                                <tbody>
                                    {pointRules.map(r => (
                                        <tr key={r.id} className={`border-b border-white/20 transition-colors hover:bg-white/20 ${!r.is_active ? 'opacity-50' : ''}`}>
                                            <td className="px-6 py-3 font-bold text-on-surface">{r.label}</td>
                                            <td className="px-4 py-3">
                                                <span className={`inline-block text-[10px] font-black px-2 py-0.5 rounded-full ${TYPE_COLORS[r.activity_type] ?? 'bg-zinc-100 text-zinc-600'}`}>
                                                    {activityTypes[r.activity_type] ?? r.activity_type}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 text-center">
                                                <span className="font-black text-2xl text-amber-600">{r.poin}</span>
                                            </td>
                                            <td className="px-4 py-3 text-xs text-on-surface-variant hidden sm:table-cell">{r.unit || '—'}</td>
                                            <td className="px-4 py-3 text-center">
                                                <ActivePill active={r.is_active} onToggle={() => toggleRule(r.id)} />
                                            </td>
                                            <td className="px-6 py-3">
                                                <div className="flex gap-1 justify-end">
                                                    <button onClick={() => openEditRule(r)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-blue-50 text-blue-600 transition-colors">
                                                        <Icon name="edit" className="text-xs" />
                                                    </button>
                                                    <button onClick={() => deleteRule(r.id)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-rose-50 text-rose-500 transition-colors">
                                                        <Icon name="delete" className="text-xs" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}

                    {/* How points connect */}
                    <div className="mt-2 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <p className="text-xs font-bold text-amber-700 flex items-center gap-2">
                            <Icon name="info" className="text-sm" filled /> Cara kerja
                        </p>
                        <p className="text-xs text-amber-600 mt-1 leading-relaxed">
                            Total poin mahasiswa = Σ (jumlah aktivitas tiap tipe × poin per unit). Poin ditampilkan di leaderboard, dashboard mahasiswa, dan profil. Perubahan langsung berlaku setelah cache 5 menit.
                        </p>
                    </div>
                </Section>

                {/* ── Integrasi ─────────────────────────────────────────────── */}
                <Section title="Integrasi" icon="link">
                    <Field label="Google OAuth" hint="Dikonfigurasi via GOOGLE_CLIENT_ID di .env">
                        <div className="flex items-center gap-3">
                            <span className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold ${data.google_oauth ? 'bg-emerald-100 text-emerald-700' : 'bg-zinc-100 text-zinc-500'}`}>
                                <span className={`w-1.5 h-1.5 rounded-full ${data.google_oauth ? 'bg-emerald-500' : 'bg-zinc-400'}`} />
                                {data.google_oauth ? 'Aktif' : 'Nonaktif'}
                            </span>
                            <span className="text-[10px] text-on-surface-variant italic">Ubah via .env</span>
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

                {/* ── Manajemen Asrama ──────────────────────────────────────── */}
                <Section title="Manajemen Asrama" icon="apartment"
                    action={
                        <button onClick={openAddAsrama} className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary-container text-white text-xs font-bold hover:opacity-90 transition-opacity">
                            <Icon name="add" className="text-sm" /> Tambah Asrama
                        </button>
                    }>
                    <div className="space-y-2">
                        {asramas.length === 0 && (
                            <div className="text-center py-8 text-on-surface-variant text-sm">Belum ada asrama.</div>
                        )}
                        {asramas.map(a => {
                            const latestJabatan = a.jabatans?.[0];
                            const isExpanded = expandedAsrama === a.id;
                            return (
                                <div key={a.id} className="border border-white/30 rounded-xl overflow-hidden">
                                    <div className="flex items-center justify-between p-4 bg-surface-container/30">
                                        <div className="flex items-center gap-4 flex-1 min-w-0">
                                            <button onClick={() => setExpandedAsrama(isExpanded ? null : a.id)} className="w-10 h-10 bg-primary-container/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <Icon name={isExpanded ? 'expand_less' : 'expand_more'} className="text-primary-container text-lg" />
                                            </button>
                                            <div className="min-w-0">
                                                <p className="font-bold text-on-surface text-sm">{a.nama_asrama}</p>
                                                <p className="text-xs text-on-surface-variant">
                                                    {a.kapasitas ? `Kapasitas: ${a.kapasitas}` : 'Kapasitas: -'}
                                                    {latestJabatan ? ` · Ketua ${latestJabatan.tahun}: ${latestJabatan.ketua || '-'}` : ' · Belum ada data jabatan'}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="flex gap-2 flex-shrink-0">
                                            <button onClick={() => openAddJabatan(a)} className="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition-colors">
                                                <Icon name="add" className="text-sm" /> Jabatan
                                            </button>
                                            <button onClick={() => openEditAsrama(a)} className="w-8 h-8 rounded-lg bg-white border border-zinc-200 flex items-center justify-center hover:bg-blue-50 text-blue-600 transition-colors">
                                                <Icon name="edit" className="text-sm" />
                                            </button>
                                            <button onClick={() => deleteAsrama(a.id)} className="w-8 h-8 rounded-lg bg-white border border-zinc-200 flex items-center justify-center hover:bg-rose-50 text-rose-500 transition-colors">
                                                <Icon name="delete" className="text-sm" />
                                            </button>
                                        </div>
                                    </div>
                                    {isExpanded && (
                                        <div className="border-t border-white/30 bg-white/30 p-4">
                                            <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-3">Riwayat Jabatan</p>
                                            {a.jabatans?.length === 0 ? (
                                                <p className="text-xs text-on-surface-variant italic">Belum ada data jabatan.</p>
                                            ) : (
                                                <div className="space-y-2">
                                                    {a.jabatans?.map(j => (
                                                        <div key={j.id} className="flex items-center justify-between px-4 py-2.5 bg-white rounded-xl border border-zinc-100 text-sm">
                                                            <div className="flex items-center gap-4">
                                                                <span className="font-black text-primary-container w-12">{j.tahun}</span>
                                                                <div>
                                                                    <span className="text-on-surface-variant text-xs">Direktur: </span>
                                                                    <span className="font-semibold text-on-surface text-xs">{j.direktur || '-'}</span>
                                                                    <span className="text-on-surface-variant text-xs ml-4">Ketua: </span>
                                                                    <span className="font-semibold text-on-surface text-xs">{j.ketua || '-'}</span>
                                                                </div>
                                                            </div>
                                                            <div className="flex gap-1">
                                                                <button onClick={() => openEditJabatan(a, j)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-blue-50 text-blue-600 transition-colors">
                                                                    <Icon name="edit" className="text-xs" />
                                                                </button>
                                                                <button onClick={() => deleteJabatan(a.id, j.id)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-rose-50 text-rose-500 transition-colors">
                                                                    <Icon name="delete" className="text-xs" />
                                                                </button>
                                                            </div>
                                                        </div>
                                                    ))}
                                                </div>
                                            )}
                                        </div>
                                    )}
                                </div>
                            );
                        })}
                    </div>
                </Section>

                {/* ── Komponen Penilaian ───────────────────────────────────────── */}
                <Section title="Komponen Penilaian" icon="rule"
                    action={
                        <button onClick={openAddKomponen} className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary-container text-white text-xs font-bold hover:opacity-90 transition-opacity">
                            <Icon name="add" className="text-sm" /> Tambah Komponen
                        </button>
                    }>
                    <p className="text-xs text-on-surface-variant -mt-3">
                        Komponen dipakai warga saat mengisi log akademik/leadership/karakter/kreativitas. Bobot menentukan kontribusi tiap komponen ke penilaian.
                    </p>

                    <div className="flex gap-2 flex-wrap -mt-1">
                        {aspekList.map(a => (
                            <button
                                key={a}
                                onClick={() => setKomponenTab(a)}
                                className={`px-3.5 py-1.5 rounded-xl text-xs font-bold transition-colors ${komponenTab === a ? 'bg-primary-container text-white' : 'bg-surface-container/40 text-on-surface-variant hover:bg-surface-container/70'}`}
                            >
                                {a}
                            </button>
                        ))}
                    </div>

                    {(() => {
                        const rows = komponens.filter(k => k.aspek === komponenTab);
                        if (rows.length === 0) {
                            return <div className="text-center py-8 text-on-surface-variant text-sm">Belum ada komponen untuk aspek {komponenTab}.</div>;
                        }
                        return (
                            <div className="overflow-x-auto -mx-6">
                                <table className="w-full text-sm">
                                    <thead>
                                        <tr className="border-b border-white/40">
                                            <th className="text-left px-6 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Kode</th>
                                            <th className="text-left px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Nama Komponen</th>
                                            <th className="text-center px-4 py-2.5 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Bobot</th>
                                            <th className="px-6 py-2.5" />
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {rows.map(k => (
                                            <tr key={k.id} className="border-b border-white/20 transition-colors hover:bg-white/20">
                                                <td className="px-6 py-3 font-mono text-xs text-on-surface-variant">{k.kode}</td>
                                                <td className="px-4 py-3 font-semibold text-on-surface">{k.nama_komponen}</td>
                                                <td className="px-4 py-3 text-center">
                                                    <span className="font-black text-lg text-amber-600">{k.bobot}</span>
                                                </td>
                                                <td className="px-6 py-3">
                                                    <div className="flex gap-1 justify-end">
                                                        <button onClick={() => openEditKomponen(k)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-blue-50 text-blue-600 transition-colors">
                                                            <Icon name="edit" className="text-xs" />
                                                        </button>
                                                        <button onClick={() => deleteKomponen(k.id)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-rose-50 text-rose-500 transition-colors">
                                                            <Icon name="delete" className="text-xs" />
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        );
                    })()}

                    <div className="mt-2 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <p className="text-xs font-bold text-amber-700 flex items-center gap-2">
                            <Icon name="info" className="text-sm" filled /> Cara kerja
                        </p>
                        <p className="text-xs text-amber-600 mt-1 leading-relaxed">
                            Bobot tiap komponen dijumlahkan sebagai skor aktivitas warga di dimensi terkait, menggantikan hitungan jumlah aktivitas polos. Perubahan langsung berlaku untuk penilaian baru.
                        </p>
                    </div>
                </Section>

                {/* ── Komponen Penilaian Baru (3-Level) ────────────────────────── */}
                <Section title="Komponen Penilaian (Baru)" icon="inventory_2">
                    <p className="text-xs text-on-surface-variant -mt-3">
                        Sistem penilaian 3-level: <strong>Aspek Utama</strong> (4, tetap) → <strong>Sub-Aspek</strong> (dinamis) → <strong>Jenis Kegiatan</strong> dengan poin berlevel Internal (A/P/F/U) & Eksternal (W/N/I).
                    </p>

                    {/* Tab 4 Aspek */}
                    <div className="flex gap-2 flex-wrap -mt-1">
                        {komponenPenilaian.map(a => (
                            <button
                                key={a.kode}
                                onClick={() => setKpTab(a.kode)}
                                className={`px-4 py-2 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5 ${kpTab === a.kode ? 'bg-indigo-600 text-white shadow-md' : 'bg-surface-container/40 text-on-surface-variant hover:bg-surface-container/70'}`}
                            >
                                <span>{ASPEK_ICONS[a.kode]}</span> {a.nama_aspek}
                            </button>
                        ))}
                    </div>

                    {/* Sub-Aspek Accordions */}
                    {komponenPenilaian.filter(a => a.kode === kpTab).map(aspek => (
                        <div key={aspek.id} className="space-y-3">
                            {aspek.sub_aspeks.map(sub => (
                                <div key={sub.id} className="border border-white/20 rounded-xl overflow-hidden">
                                    {/* Sub-Aspek Header */}
                                    <div className="flex items-center justify-between px-4 py-3 bg-surface-container/30">
                                        <button
                                            className="flex items-center gap-2 flex-1 text-left"
                                            onClick={() => setExpandedSub(expandedSub === sub.id ? null : sub.id)}
                                        >
                                            <Icon name={expandedSub === sub.id ? 'expand_less' : 'expand_more'} className="text-base text-on-surface-variant" />
                                            <span className="font-bold text-sm text-on-surface">{sub.nama_sub_aspek}</span>
                                            <span className="text-xs text-on-surface-variant ml-1">({sub.jenis_kegiatans.length} kegiatan)</span>
                                        </button>
                                        <div className="flex gap-1">
                                            <button onClick={() => openEditSub(sub)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-blue-50 text-blue-600 transition-colors" title="Edit nama">
                                                <Icon name="edit" className="text-xs" />
                                            </button>
                                            <button onClick={() => deleteSub(sub.id)} className="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-rose-50 text-rose-500 transition-colors" title="Hapus sub-aspek">
                                                <Icon name="delete" className="text-xs" />
                                            </button>
                                        </div>
                                    </div>

                                    {/* Tabel Jenis Kegiatan (expanded) */}
                                    {expandedSub === sub.id && (
                                        <div>
                                            <div className="overflow-x-auto">
                                                <table className="w-full text-xs">
                                                    <thead>
                                                        <tr className="border-b border-white/30">
                                                            <th className="text-left px-4 py-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant w-6">No</th>
                                                            <th className="text-left px-3 py-2 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Jenis Kegiatan</th>
                                                            {/* Internal header */}
                                                            <th colSpan={4} className="text-center px-2 py-1 text-[10px] font-black uppercase tracking-widest text-blue-700 bg-blue-50/60">Internal</th>
                                                            {/* Eksternal header */}
                                                            <th colSpan={3} className="text-center px-2 py-1 text-[10px] font-black uppercase tracking-widest text-red-700 bg-red-50/60">Eksternal</th>
                                                            <th className="px-3 py-2 w-16" />
                                                        </tr>
                                                        <tr className="border-b border-white/20 text-[10px] font-bold uppercase">
                                                            <th /><th />
                                                            {['A','P','F','U'].map(l => <th key={l} className="text-center px-2 py-1 text-blue-600 bg-blue-50/40">{l}</th>)}
                                                            {['W','N','I'].map(l => <th key={l} className="text-center px-2 py-1 text-red-600 bg-red-50/40">{l}</th>)}
                                                            <th />
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        {sub.jenis_kegiatans.map((j, idx) => (
                                                            <tr key={j.id} className="border-b border-white/10 hover:bg-white/10 transition-colors">
                                                                <td className="px-4 py-2.5 text-on-surface-variant text-center">{idx+1}</td>
                                                                <td className="px-3 py-2.5 font-semibold text-on-surface">{j.nama_kegiatan}</td>
                                                                {(['poin_a','poin_p','poin_f','poin_u'] as const).map(k => (
                                                                    <td key={k} className="text-center px-2 py-2.5 bg-blue-50/20">
                                                                        {j[k] != null ? <span className="font-bold text-blue-700">{j[k]}</span> : <span className="text-on-surface-variant/40">–</span>}
                                                                    </td>
                                                                ))}
                                                                {(['poin_w','poin_n','poin_i'] as const).map(k => (
                                                                    <td key={k} className="text-center px-2 py-2.5 bg-red-50/20">
                                                                        {j[k] != null ? <span className="font-bold text-red-700">{j[k]}</span> : <span className="text-on-surface-variant/40">–</span>}
                                                                    </td>
                                                                ))}
                                                                <td className="px-3 py-2.5">
                                                                    <div className="flex gap-1 justify-end">
                                                                        <button onClick={() => openEditJenis(j)} className="w-6 h-6 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-blue-50 text-blue-600 transition-colors">
                                                                            <Icon name="edit" className="text-[10px]" />
                                                                        </button>
                                                                        <button onClick={() => deleteJenis(j.id)} className="w-6 h-6 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center hover:bg-rose-50 text-rose-500 transition-colors">
                                                                            <Icon name="delete" className="text-[10px]" />
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        ))}
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div className="px-4 py-3 border-t border-white/10">
                                                <button onClick={() => openAddJenis(sub.id)} className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-emerald-300 text-emerald-700 bg-emerald-50 text-xs font-bold hover:bg-emerald-100 transition-colors">
                                                    <Icon name="add" className="text-sm" /> Tambah Jenis Kegiatan
                                                </button>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            ))}

                            {/* Tambah Sub-Aspek */}
                            <button onClick={() => openAddSub(aspek.id)} className="w-full flex items-center justify-center gap-2 py-3 rounded-xl border border-dashed border-indigo-300 text-indigo-600 bg-indigo-50/40 text-sm font-bold hover:bg-indigo-50 transition-colors">
                                <Icon name="add_circle" className="text-base" /> Tambah Sub-Aspek
                            </button>
                        </div>
                    ))}
                </Section>
            </div>


            {/* ── Asrama Modal ──────────────────────────────────────────────── */}
            {showAsramaModal && (
                <ModalShell title={editAsrama ? 'Edit Asrama' : 'Tambah Asrama'} onClose={() => setShowAsramaModal(false)}>
                    <div className="p-6 space-y-4">
                        <div>
                            <FormLabel>Nama Asrama *</FormLabel>
                            <input value={asramaForm.data.nama_asrama} onChange={e => asramaForm.setData('nama_asrama', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. Al-Farabi" />
                            {asramaForm.errors.nama_asrama && <p className="text-xs text-rose-500 mt-1">{asramaForm.errors.nama_asrama}</p>}
                        </div>
                        <div>
                            <FormLabel>Kapasitas</FormLabel>
                            <input type="number" value={asramaForm.data.kapasitas} onChange={e => asramaForm.setData('kapasitas', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. 30" />
                        </div>
                        <p className="text-xs text-on-surface-variant italic">Data direktur dan ketua dikelola per tahun di Riwayat Jabatan.</p>
                    </div>
                    <div className="p-6 pt-0 flex gap-3">
                        <button onClick={submitAsrama} disabled={asramaForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {asramaForm.processing ? 'Menyimpan...' : (editAsrama ? 'Simpan' : 'Tambah')}
                        </button>
                        <button onClick={() => setShowAsramaModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </ModalShell>
            )}

            {/* ── Jabatan Modal ──────────────────────────────────────────────── */}
            {showJabatanModal && jabatanAsrama && (
                <ModalShell title={editJabatan ? 'Edit Jabatan' : 'Tambah Jabatan'} subtitle={jabatanAsrama.nama_asrama} onClose={() => setShowJabatanModal(false)}>
                    <div className="p-6 space-y-4">
                        <div>
                            <FormLabel>Tahun *</FormLabel>
                            <input type="number" min={2000} max={2100} value={jabatanForm.data.tahun} onChange={e => jabatanForm.setData('tahun', e.target.value)} className="glass-input w-full text-sm" />
                            {jabatanForm.errors.tahun && <p className="text-xs text-rose-500 mt-1">{jabatanForm.errors.tahun}</p>}
                        </div>
                        <div>
                            <FormLabel>Direktur</FormLabel>
                            <input value={jabatanForm.data.direktur} onChange={e => jabatanForm.setData('direktur', e.target.value)} className="glass-input w-full text-sm" placeholder="Nama direktur" />
                        </div>
                        <div>
                            <FormLabel>Ketua Asrama</FormLabel>
                            <input value={jabatanForm.data.ketua} onChange={e => jabatanForm.setData('ketua', e.target.value)} className="glass-input w-full text-sm" placeholder="Nama ketua asrama" />
                        </div>
                    </div>
                    <div className="p-6 pt-0 flex gap-3">
                        <button onClick={submitJabatan} disabled={jabatanForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {jabatanForm.processing ? 'Menyimpan...' : (editJabatan ? 'Simpan' : 'Tambah')}
                        </button>
                        <button onClick={() => setShowJabatanModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </ModalShell>
            )}

            {/* ── Point Rule Modal ───────────────────────────────────────────── */}
            {showRuleModal && (
                <ModalShell title={editRule ? 'Edit Aturan Poin' : 'Tambah Aturan Poin'} onClose={() => setShowRuleModal(false)}>
                    <div className="p-6 space-y-4">
                        <div>
                            <FormLabel>Label *</FormLabel>
                            <input value={ruleForm.data.label} onChange={e => ruleForm.setData('label', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. Shalat Wajib" />
                            {ruleForm.errors.label && <p className="text-xs text-rose-500 mt-1">{ruleForm.errors.label}</p>}
                        </div>
                        <div>
                            <FormLabel>Jenis Aktivitas *</FormLabel>
                            <select value={ruleForm.data.activity_type} onChange={e => ruleForm.setData('activity_type', e.target.value)} className="glass-input w-full text-sm py-2">
                                {Object.entries(activityTypes).map(([k, v]) => (
                                    <option key={k} value={k}>{v}</option>
                                ))}
                            </select>
                            <p className="text-[10px] text-on-surface-variant mt-1">Menentukan data mana yang dihitung untuk aturan ini.</p>
                            {ruleForm.errors.activity_type && <p className="text-xs text-rose-500 mt-1">{ruleForm.errors.activity_type}</p>}
                        </div>
                        <div className="grid grid-cols-2 gap-3">
                            <div>
                                <FormLabel>Poin per Unit *</FormLabel>
                                <input type="number" min={0} value={ruleForm.data.poin} onChange={e => ruleForm.setData('poin', e.target.value)} className="glass-input w-full text-sm text-center" placeholder="0" />
                                {ruleForm.errors.poin && <p className="text-xs text-rose-500 mt-1">{ruleForm.errors.poin}</p>}
                            </div>
                            <div>
                                <FormLabel>Satuan</FormLabel>
                                <input value={ruleForm.data.unit} onChange={e => ruleForm.setData('unit', e.target.value)} className="glass-input w-full text-sm" placeholder="per aktivitas" />
                            </div>
                        </div>
                    </div>
                    <div className="p-6 pt-0 flex gap-3">
                        <button onClick={submitRule} disabled={ruleForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {ruleForm.processing ? 'Menyimpan...' : (editRule ? 'Simpan' : 'Tambah')}
                        </button>
                        <button onClick={() => setShowRuleModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </ModalShell>
            )}

            {/* ── Daily Target Modal ─────────────────────────────────────────── */}
            {showTargetModal && (
                <ModalShell title={editTarget ? 'Edit Target' : 'Tambah Target'} onClose={() => setShowTargetModal(false)}>
                    <div className="p-6 space-y-4">
                        <div>
                            <FormLabel>Label *</FormLabel>
                            <input value={targetForm.data.label} onChange={e => targetForm.setData('label', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. Target Shalat" />
                            {targetForm.errors.label && <p className="text-xs text-rose-500 mt-1">{targetForm.errors.label}</p>}
                        </div>
                        {!editTarget && (
                            <div>
                                <FormLabel>Key (kode unik) *</FormLabel>
                                <input value={targetForm.data.key} onChange={e => targetForm.setData('key', e.target.value)} className="glass-input w-full text-sm font-mono" placeholder="cth. shalat_target" />
                                <p className="text-[10px] text-on-surface-variant mt-1">Huruf kecil, angka, dan underscore. Tidak bisa diubah setelah disimpan.</p>
                                {targetForm.errors.key && <p className="text-xs text-rose-500 mt-1">{targetForm.errors.key}</p>}
                            </div>
                        )}
                        <div className="grid grid-cols-2 gap-3">
                            <div>
                                <FormLabel>Nilai Target *</FormLabel>
                                <input type="number" min={0} value={targetForm.data.value} onChange={e => targetForm.setData('value', e.target.value)} className="glass-input w-full text-sm text-center" placeholder="0" />
                                {targetForm.errors.value && <p className="text-xs text-rose-500 mt-1">{targetForm.errors.value}</p>}
                            </div>
                            <div>
                                <FormLabel>Satuan</FormLabel>
                                <input value={targetForm.data.unit} onChange={e => targetForm.setData('unit', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. waktu / hari" />
                            </div>
                        </div>
                        <div>
                            <FormLabel>Keterangan</FormLabel>
                            <input value={targetForm.data.description} onChange={e => targetForm.setData('description', e.target.value)} className="glass-input w-full text-sm" placeholder="Penjelasan singkat tentang target ini" />
                        </div>
                    </div>
                    <div className="p-6 pt-0 flex gap-3">
                        <button onClick={submitTarget} disabled={targetForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {targetForm.processing ? 'Menyimpan...' : (editTarget ? 'Simpan' : 'Tambah')}
                        </button>
                        <button onClick={() => setShowTargetModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </ModalShell>
            )}

            {/* ── Komponen Modal ─────────────────────────────────────────────── */}
            {showKomponenModal && (
                <ModalShell title={editKomponen ? 'Edit Komponen' : 'Tambah Komponen'} onClose={() => setShowKomponenModal(false)}>
                    <div className="p-6 space-y-4">
                        <div>
                            <FormLabel>Aspek *</FormLabel>
                            <select value={komponenForm.data.aspek} onChange={e => komponenForm.setData('aspek', e.target.value)} className="glass-input w-full text-sm py-2">
                                {aspekList.map(a => <option key={a} value={a}>{a}</option>)}
                            </select>
                            {komponenForm.errors.aspek && <p className="text-xs text-rose-500 mt-1">{komponenForm.errors.aspek}</p>}
                        </div>
                        <div>
                            <FormLabel>Nama Komponen *</FormLabel>
                            <input value={komponenForm.data.nama_komponen} onChange={e => komponenForm.setData('nama_komponen', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. Mengikuti kegiatan mentoring" />
                            {komponenForm.errors.nama_komponen && <p className="text-xs text-rose-500 mt-1">{komponenForm.errors.nama_komponen}</p>}
                        </div>
                        <div className="grid grid-cols-2 gap-3">
                            <div>
                                <FormLabel>Kode *</FormLabel>
                                <input value={komponenForm.data.kode} onChange={e => komponenForm.setData('kode', e.target.value)} className="glass-input w-full text-sm font-mono" placeholder="cth. 1009" />
                                {komponenForm.errors.kode && <p className="text-xs text-rose-500 mt-1">{komponenForm.errors.kode}</p>}
                            </div>
                            <div>
                                <FormLabel>Bobot Nilai *</FormLabel>
                                <input type="number" min={0} value={komponenForm.data.bobot} onChange={e => komponenForm.setData('bobot', e.target.value)} className="glass-input w-full text-sm text-center" placeholder="1" />
                                {komponenForm.errors.bobot && <p className="text-xs text-rose-500 mt-1">{komponenForm.errors.bobot}</p>}
                            </div>
                        </div>
                    </div>
                    <div className="p-6 pt-0 flex gap-3">
                        <button onClick={submitKomponen} disabled={komponenForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {komponenForm.processing ? 'Menyimpan...' : (editKomponen ? 'Simpan' : 'Tambah')}
                        </button>
                        <button onClick={() => setShowKomponenModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </ModalShell>
            )}

            {/* ── Sub-Aspek Modal ─────────────────────────────────────────────── */}
            {showSubModal && (
                <ModalShell title={editSub ? 'Edit Sub-Aspek' : 'Tambah Sub-Aspek'} onClose={() => setShowSubModal(false)}>
                    <div className="p-6 space-y-4">
                        <div>
                            <FormLabel>Nama Sub-Aspek *</FormLabel>
                            <input
                                value={subForm.data.nama_sub_aspek}
                                onChange={e => subForm.setData('nama_sub_aspek', e.target.value)}
                                className="glass-input w-full text-sm"
                                placeholder="cth. Kompetisi Akademik"
                            />
                            {subForm.errors.nama_sub_aspek && <p className="text-xs text-rose-500 mt-1">{subForm.errors.nama_sub_aspek}</p>}
                        </div>
                        <div>
                            <FormLabel>Urutan</FormLabel>
                            <input
                                type="number" min={0}
                                value={subForm.data.urutan}
                                onChange={e => subForm.setData('urutan', e.target.value)}
                                className="glass-input w-full text-sm text-center" placeholder="0"
                            />
                        </div>
                    </div>
                    <div className="p-6 pt-0 flex gap-3">
                        <button onClick={submitSub} disabled={subForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {subForm.processing ? 'Menyimpan...' : (editSub ? 'Simpan' : 'Tambah')}
                        </button>
                        <button onClick={() => setShowSubModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </ModalShell>
            )}

            {/* ── Jenis Kegiatan Modal ────────────────────────────────────────── */}
            {showJenisModal && (
                <ModalShell
                    title={editJenis ? 'Edit Jenis Kegiatan' : 'Tambah Jenis Kegiatan'}
                    subtitle="Kosongkan poin yang tidak relevan untuk level tersebut (akan tampil sebagai –)"
                    onClose={() => setShowJenisModal(false)}
                >
                    <div className="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div>
                            <FormLabel>Nama Kegiatan *</FormLabel>
                            <input
                                value={jenisForm.data.nama_kegiatan}
                                onChange={e => jenisForm.setData('nama_kegiatan', e.target.value)}
                                className="glass-input w-full text-sm"
                                placeholder="cth. Juara I Lomba Esai"
                            />
                            {jenisForm.errors.nama_kegiatan && <p className="text-xs text-rose-500 mt-1">{jenisForm.errors.nama_kegiatan}</p>}
                        </div>

                        {/* Internal poin */}
                        <div>
                            <p className="text-xs font-black text-blue-700 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                <span className="w-3 h-3 rounded-sm bg-blue-600 inline-block" /> Poin Internal
                            </p>
                            <div className="grid grid-cols-4 gap-2">
                                {([['a','Asrama'],['p','Prodi'],['f','Fakultas'],['u','Universitas']] as const).map(([code, label]) => (
                                    <div key={code}>
                                        <label className="text-[10px] font-bold text-blue-600 uppercase">{code.toUpperCase()} – {label}</label>
                                        <input
                                            type="number" min={0} max={20}
                                            value={(jenisForm.data as any)[`poin_${code}`]}
                                            onChange={e => jenisForm.setData(`poin_${code}` as any, e.target.value)}
                                            className="glass-input w-full text-sm text-center mt-0.5"
                                            placeholder="–"
                                        />
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Eksternal poin */}
                        <div>
                            <p className="text-xs font-black text-red-700 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                <span className="w-3 h-3 rounded-sm bg-red-500 inline-block" /> Poin Eksternal
                            </p>
                            <div className="grid grid-cols-3 gap-2">
                                {([['w','Wilayah/JABODETABEK'],['n','Nasional'],['i','Internasional']] as const).map(([code, label]) => (
                                    <div key={code}>
                                        <label className="text-[10px] font-bold text-red-600 uppercase">{code.toUpperCase()} – {label}</label>
                                        <input
                                            type="number" min={0} max={20}
                                            value={(jenisForm.data as any)[`poin_${code}`]}
                                            onChange={e => jenisForm.setData(`poin_${code}` as any, e.target.value)}
                                            className="glass-input w-full text-sm text-center mt-0.5"
                                            placeholder="–"
                                        />
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Keterangan Bukti */}
                        <div>
                            <FormLabel>Keterangan Bukti</FormLabel>
                            <textarea
                                value={jenisForm.data.keterangan_bukti}
                                onChange={e => jenisForm.setData('keterangan_bukti', e.target.value)}
                                className="glass-input w-full text-sm resize-none"
                                rows={3}
                                placeholder="Dokumen/bukti yang perlu dilampirkan..."
                            />
                        </div>
                    </div>
                    <div className="p-6 pt-0 flex gap-3">
                        <button onClick={submitJenis} disabled={jenisForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {jenisForm.processing ? 'Menyimpan...' : (editJenis ? 'Simpan Perubahan' : 'Tambah Kegiatan')}
                        </button>
                        <button onClick={() => setShowJenisModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </ModalShell>
            )}
        </AppLayout>
    );
}
