import { Head, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';
import { ConfirmDialog } from '@/Components/ui/ConfirmDialog';
import { ActionButtons } from '@/Components/ui/ActionButtons';
import { Pagination } from '@/Components/ui/Pagination';
import { PageProps } from '@/types';

// ─── Types ────────────────────────────────────────────────────────────────────
type Kategori = 'akademik' | 'leadership' | 'karakter' | 'kreativitas';

interface KomponenItem { id: number; nama: string; kode: string; }
interface AktivitasItem {
    id: number; kategori: Kategori; kegiatan: string; komponen: string; komponen_id?: number;
    sub_aspek_id?: number | null; jenis_kegiatan_id?: number | null; level_kegiatan?: string | null; poin?: number;
    tipe_kegiatan?: string; image?: string | null; image_name?: string | null;
    waktu: string; tempat: string; keterangan?: string; nilai?: string; created_at: string;
}

// Komponen Penilaian (3-level) types untuk cascade form
interface KPAspek { id: number; kode: string; nama_aspek: string; urutan: number; }
interface KPSubAspek { id: number; nama_sub_aspek: string; }
interface KPJenis {
    id: number; nama_kegiatan: string;
    poin_a: number|null; poin_p: number|null; poin_f: number|null; poin_u: number|null;
    poin_w: number|null; poin_n: number|null; poin_i: number|null;
    keterangan_bukti: string|null;
}

function isPdfName(name?: string | null): boolean {
    return !!name && name.toLowerCase().endsWith('.pdf');
}
interface AktivitasIndexProps extends PageProps {
    items: AktivitasItem[];
    komponens: Record<string, KomponenItem[]>;
    categories: Kategori[];
    komponenPenilaian: KPAspek[];
    pagination: {
        current_page: number;
        per_page: number;
        total: number;
        last_page: number;
    };
    filters: {
        search: string | null;
        tipe: string | null;
        date_from: string | null;
        date_to: string | null;
    };
}

// ─── Constants ────────────────────────────────────────────────────────────────
const KAT_META: Record<Kategori, { label: string; icon: string; color: string; bg: string; border: string }> = {
    akademik:    { label: 'Akademik',                    icon: 'school',   color: 'text-blue-600',    bg: 'bg-blue-50',    border: 'border-blue-200' },
    leadership:  { label: 'Leadership',                  icon: 'groups',   color: 'text-purple-600',  bg: 'bg-purple-50',  border: 'border-purple-200' },
    karakter:    { label: 'Karakter Islami',              icon: 'mosque',   color: 'text-emerald-600', bg: 'bg-emerald-50', border: 'border-emerald-200' },
    kreativitas: { label: 'Kreativitas & Kewirausahaan', icon: 'palette',  color: 'text-amber-600',   bg: 'bg-amber-50',   border: 'border-amber-200' },
};

// Komponen aspek → kategori mapping
// Poin level labels
const LEVEL_LABELS: Record<string, string> = {
    a: 'Asrama (A)', p: 'Prodi (P)', f: 'Fakultas (F)', u: 'Universitas (U)',
    w: 'Wilayah/Jabodetabek (W)', n: 'Nasional (N)', i: 'Internasional (I)',
};

// ─── Form Modal ───────────────────────────────────────────────────────────────
function AktivitasModal({
    activeKat, editItem, onClose, komponenPenilaian,
}: {
    activeKat: Kategori;
    komponens: Record<string, KomponenItem[]>;
    editItem: AktivitasItem | null;
    onClose: () => void;
    komponenPenilaian: KPAspek[];
}) {
    const [form, setForm] = useState<any>({
        kategori:      editItem?.kategori    ?? activeKat,
        kegiatan:      editItem?.kegiatan    ?? '',
        tipe_kegiatan: editItem?.tipe_kegiatan ?? '',
        waktu:         editItem?.waktu       ?? '',
        tempat:        editItem?.tempat      ?? '',
        keterangan:    editItem?.keterangan  ?? '',
        image:         null,
    });
    const [saving, setSaving] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [preview, setPreview] = useState<string | null>(editItem?.image ?? null);
    const [previewIsPdf, setPreviewIsPdf] = useState(isPdfName(editItem?.image_name));

    // ── Cascade Komponen Penilaian ──────────────────────────────────────────────
    // Map kategori → kode aspek
    const KAT_TO_ASPEK: Record<Kategori, string> = {
        akademik: 'akademik', leadership: 'leadership',
        karakter: 'karakter_islami', kreativitas: 'kreatifitas',
    };
    const currentAspek = komponenPenilaian.find(a => a.kode === KAT_TO_ASPEK[form.kategori as Kategori]);
    const [subAspeks, setSubAspeks] = useState<KPSubAspek[]>([]);
    const [selectedSub, setSelectedSub] = useState<string>('');
    const [jenisOptions, setJenisOptions] = useState<KPJenis[]>([]);
    const [selectedJenis, setSelectedJenis] = useState<KPJenis|null>(null);
    const [selectedLevel, setSelectedLevel] = useState<string>('');

    // Fetch sub-aspek when aspek changes
    useEffect(() => {
        if (!currentAspek) return;
        setSubAspeks([]); setJenisOptions([]);
        const prefillSub = editItem?.sub_aspek_id ? String(editItem.sub_aspek_id) : '';
        setSelectedSub(prefillSub);
        if (!prefillSub) { setSelectedJenis(null); setSelectedLevel(''); }
        fetch(`/mahasiswa/komponen-penilaian/sub-aspek?aspek_id=${currentAspek.id}`)
            .then(r => r.json()).then(setSubAspeks).catch(() => {});
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [form.kategori]);

    // Fetch jenis when sub-aspek changes
    useEffect(() => {
        if (!selectedSub) { setJenisOptions([]); setSelectedJenis(null); setSelectedLevel(''); return; }
        fetch(`/mahasiswa/komponen-penilaian/jenis?sub_aspek_id=${selectedSub}`)
            .then(r => r.json())
            .then((list: KPJenis[]) => {
                setJenisOptions(list);
                if (editItem?.jenis_kegiatan_id && String(editItem.sub_aspek_id) === selectedSub) {
                    const found = list.find(j => j.id === editItem.jenis_kegiatan_id) ?? null;
                    setSelectedJenis(found);
                    setSelectedLevel(editItem.level_kegiatan ?? '');
                } else {
                    setSelectedJenis(null);
                    setSelectedLevel('');
                }
            })
            .catch(() => {});
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [selectedSub]);

    // When jenis selected, get poin for chosen level
    const availableLevels: { code: string; label: string; poin: number }[] = selectedJenis
        ? Object.entries(LEVEL_LABELS)
            .filter(([code]) => (selectedJenis as any)[`poin_${code}`] != null)
            .map(([code, label]) => ({ code, label, poin: (selectedJenis as any)[`poin_${code}`] }))
        : [];
    const selectedPoin = selectedLevel && selectedJenis
        ? (selectedJenis as any)[`poin_${selectedLevel}`]
        : null;

    const meta = KAT_META[form.kategori as Kategori];
    const isKomponenValid = !!(selectedSub && selectedJenis && selectedLevel);

    function set(field: string, val: any) { setForm((p: any) => ({ ...p, [field]: val })); }

    function handleFile(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (file) {
            set('image', file);
            setPreviewIsPdf(file.type === 'application/pdf');
            setPreview(URL.createObjectURL(file));
        }
    }

    function submit(e: React.FormEvent) {
        e.preventDefault();
        if (!isKomponenValid) return;
        setSaving(true);

        const method = editItem ? 'put' : 'post';
        const url = editItem ? `/mahasiswa/aktivitas/${editItem.id}` : '/mahasiswa/aktivitas';

        // Use router.post with forceFormData for file uploads even on PUT (Laravel spoofing)
        router.post(url, {
            ...form,
            sub_aspek_id:      selectedSub,
            jenis_kegiatan_id: selectedJenis?.id,
            level_kegiatan:    selectedLevel,
            _method: method.toUpperCase(),
        }, {
            forceFormData: true,
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => onClose(),
            onError: (e) => setErrors(e as Record<string, string>),
            onFinish: () => setSaving(false),
        });
    }

    return (
        <Modal open={true} onClose={onClose} title={`${editItem ? 'Edit' : 'Log'} Aktivitas`} icon={meta.icon} size="md"
            footer={
                <>
                    <button type="button" onClick={onClose}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-black/10 transition-colors">
                        Batal
                    </button>
                    <button form="aktivitas-form" type="submit" disabled={saving || !isKomponenValid}
                        title={!isKomponenValid ? 'Pilih Sub-Aspek, Jenis Kegiatan, dan Cakupan/Level dulu' : undefined}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm text-white bg-primary hover:bg-primary/90 shadow-lg shadow-primary/20 transition-all disabled:opacity-50 flex items-center gap-2">
                        <Icon name="save" className="text-base" />
                        {saving ? 'Menyimpan...' : editItem ? 'Simpan' : 'Log Aktivitas'}
                    </button>
                </>
            }>
            <form id="aktivitas-form" onSubmit={submit} className="space-y-4">
                {/* Kategori select */}
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Kategori *</label>
                    <select value={form.kategori} onChange={e => set('kategori', e.target.value)}
                        className="glass-input w-full text-sm">
                        {Object.entries(KAT_META).map(([k, v]) => (
                            <option key={k} value={k}>{v.label}</option>
                        ))}
                    </select>
                </div>

                {/* Komponen Penilaian (cascade 3-level: Sub-Aspek → Jenis Kegiatan → Level) */}
                <div className="bg-indigo-50 border border-indigo-200 rounded-xl p-3 space-y-3">
                    <p className="text-[10px] font-black uppercase tracking-widest text-indigo-700 flex items-center gap-1.5">
                        <span className="w-2 h-2 rounded-full bg-indigo-600 inline-block" /> Komponen Penilaian *
                    </p>

                    {/* Sub-Aspek */}
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Sub-Aspek *</label>
                        <select
                            value={selectedSub}
                            onChange={e => setSelectedSub(e.target.value)}
                            className="glass-input w-full text-sm"
                        >
                            <option value="">— Pilih Sub-Aspek —</option>
                            {subAspeks.map(s => <option key={s.id} value={s.id}>{s.nama_sub_aspek}</option>)}
                        </select>
                    </div>

                    {/* Jenis Kegiatan */}
                    {selectedSub && (
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Jenis Kegiatan</label>
                            <select
                                value={selectedJenis?.id ?? ''}
                                onChange={e => {
                                    const found = jenisOptions.find(j => String(j.id) === e.target.value) ?? null;
                                    setSelectedJenis(found); setSelectedLevel('');
                                }}
                                className="glass-input w-full text-sm"
                            >
                                <option value="">— Pilih Jenis Kegiatan —</option>
                                {jenisOptions.map(j => <option key={j.id} value={j.id}>{j.nama_kegiatan}</option>)}
                            </select>
                        </div>
                    )}

                    {/* Cakupan/Level */}
                    {selectedJenis && availableLevels.length > 0 && (
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Cakupan/Level Kegiatan</label>
                            <div className="flex flex-wrap gap-2">
                                {availableLevels.map(lv => (
                                    <button key={lv.code} type="button"
                                        onClick={() => setSelectedLevel(selectedLevel === lv.code ? '' : lv.code)}
                                        className={`px-3 py-1.5 rounded-lg text-xs font-bold border transition-all flex items-center gap-1.5 ${
                                            selectedLevel === lv.code
                                                ? 'bg-indigo-600 text-white border-indigo-600 shadow-md'
                                                : 'bg-white text-indigo-700 border-indigo-200 hover:border-indigo-400'
                                        }`}
                                    >
                                        {lv.label}
                                        <span className={`font-black ${selectedLevel === lv.code ? 'text-indigo-200' : 'text-indigo-500'}`}>{lv.poin} poin</span>
                                    </button>
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Poin Preview */}
                    {selectedPoin != null && (
                        <div className="bg-indigo-600 rounded-xl p-3 flex items-center justify-between">
                            <div>
                                <p className="text-[10px] font-black text-indigo-200 uppercase">Poin yang akan dicatat</p>
                                <p className="text-2xl font-black text-white mt-0.5">{selectedPoin} <span className="text-sm font-bold text-indigo-200">poin</span></p>
                            </div>
                            <div className="text-right">
                                <p className="text-[10px] text-indigo-200">Level: <span className="font-bold text-white">{LEVEL_LABELS[selectedLevel]}</span></p>
                            </div>
                        </div>
                    )}

                    {/* Keterangan Bukti Info */}
                    {selectedJenis?.keterangan_bukti && (
                        <div className="bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                            <p className="text-[10px] font-black text-amber-700 uppercase mb-0.5 flex items-center gap-1">
                                <Icon name="info" className="text-xs" filled /> Bukti yang diperlukan
                            </p>
                            <p className="text-xs text-amber-700">{selectedJenis.keterangan_bukti}</p>
                        </div>
                    )}
                </div>

                {/* Tipe Kegiatan (Optional) */}
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Tipe Kegiatan (Optional)</label>
                    <div className="flex gap-2">
                        {['Prestasi', 'Unggulan'].map(t => (
                            <button key={t} type="button" onClick={() => set('tipe_kegiatan', form.tipe_kegiatan === t ? '' : t)}
                                className={`flex-1 py-2 rounded-xl text-xs font-bold border transition-all ${
                                    form.tipe_kegiatan === t 
                                        ? 'bg-primary-container text-white border-primary-container shadow-md' 
                                        : 'bg-white/50 text-on-surface-variant border-slate-200 hover:border-primary-container/30'
                                }`}>
                                {t}
                            </button>
                        ))}
                    </div>
                </div>

                {/* Kegiatan */}
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Nama Kegiatan *</label>
                    <input value={form.kegiatan} onChange={e => set('kegiatan', e.target.value)}
                        className={`glass-input w-full text-sm ${errors.kegiatan ? 'border-rose-400' : ''}`}
                        placeholder="Contoh: Seminar Kepemimpinan" />
                    {errors.kegiatan && <p className="text-xs text-rose-500 mt-1">{errors.kegiatan}</p>}
                </div>

                {/* Image & Camera Upload * */}
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Bukti Kegiatan (Foto / PDF) *</label>
                    <div className="flex flex-col sm:flex-row items-start gap-4">
                        <div className={`relative w-28 h-28 rounded-2xl border-2 border-dashed flex-shrink-0 flex items-center justify-center overflow-hidden transition-colors ${
                            errors.image ? 'border-rose-400 bg-rose-50' : 'border-slate-300 bg-white/40 hover:border-primary-container/60'
                        }`}>
                            {preview ? (
                                previewIsPdf ? (
                                    <div className="flex flex-col items-center gap-1 text-rose-500 p-2 text-center">
                                        <Icon name="picture_as_pdf" className="text-3xl" />
                                        <span className="text-[9px] font-bold truncate max-w-full">PDF File</span>
                                    </div>
                                ) : (
                                    <img src={preview} alt="Preview Bukti" className="w-full h-full object-cover" />
                                )
                            ) : (
                                <div className="flex flex-col items-center gap-1 text-slate-400 text-center p-2">
                                    <Icon name="add_a_photo" className="text-2xl" />
                                    <span className="text-[9px] font-bold">Belum ada file</span>
                                </div>
                            )}
                        </div>

                        <div className="flex-1 space-y-2 w-full">
                            <div className="grid grid-cols-2 gap-2">
                                {/* Button 1: Camera */}
                                <label className="relative flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border border-indigo-200 bg-indigo-50/70 hover:bg-indigo-100 text-indigo-700 text-xs font-bold cursor-pointer transition-colors text-center">
                                    <Icon name="photo_camera" className="text-base" />
                                    <span>Ambil Kamera</span>
                                    <input 
                                        type="file" 
                                        accept="image/*" 
                                        capture="environment" 
                                        onChange={handleFile} 
                                        className="absolute inset-0 opacity-0 cursor-pointer" 
                                    />
                                </label>

                                {/* Button 2: File Picker */}
                                <label className="relative flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold cursor-pointer transition-colors text-center">
                                    <Icon name="upload_file" className="text-base text-slate-500" />
                                    <span>Pilih File</span>
                                    <input 
                                        type="file" 
                                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" 
                                        onChange={handleFile} 
                                        className="absolute inset-0 opacity-0 cursor-pointer" 
                                    />
                                </label>
                            </div>

                            <p className="text-[10px] text-on-surface-variant leading-relaxed">
                                Maksimal 20MB. Format yang didukung: JPG, PNG, atau PDF.
                            </p>
                            {errors.image && <p className="text-xs text-rose-500 font-bold">{errors.image}</p>}
                        </div>
                    </div>
                </div>

                {/* Waktu & Tempat */}
                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Waktu *</label>
                        <input type="date" value={form.waktu} onChange={e => set('waktu', e.target.value)}
                            className={`glass-input w-full text-sm ${errors.waktu ? 'border-rose-400' : ''}`} />
                    </div>
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Tempat *</label>
                        <input value={form.tempat} onChange={e => set('tempat', e.target.value)}
                            className={`glass-input w-full text-sm ${errors.tempat ? 'border-rose-400' : ''}`}
                            placeholder="Aula / Online / dll" />
                    </div>
                </div>

                {/* Keterangan */}
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Keterangan</label>
                    <textarea value={form.keterangan} onChange={e => set('keterangan', e.target.value)}
                        rows={3} className="glass-input w-full text-sm resize-none"
                        placeholder="Catatan tambahan, hasil, atau pencapaian..." />
                </div>
            </form>
        </Modal>
    );
}

function AktivitasDetailModal({ item, onClose }: { item: AktivitasItem; onClose: () => void }) {
    const meta = KAT_META[item.kategori];
    return (
        <Modal open={true} onClose={onClose} title="Detail Aktivitas" icon={meta.icon} size="md">
            <div className="space-y-6">
                {/* Header info */}
                <div className="flex items-start gap-4">
                    <div className={`w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 ${meta.bg}`}>
                        <Icon name={meta.icon} className={`text-2xl ${meta.color}`} filled />
                    </div>
                    <div className="flex-1 min-w-0">
                        <h2 className="text-xl font-bold text-on-surface leading-tight mb-1">{item.kegiatan}</h2>
                        <div className="flex items-center gap-2 flex-wrap">
                            <span className={`px-2 py-0.5 rounded-full text-[10px] font-black uppercase ${meta.bg} ${meta.color} border ${meta.border}`}>
                                {meta.label}
                            </span>
                            {item.tipe_kegiatan && (
                                <span className="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-primary-container text-white">
                                    {item.tipe_kegiatan}
                                </span>
                            )}
                        </div>
                    </div>
                </div>

                {/* Main Content */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="space-y-4">
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Komponen</label>
                            <p className="text-sm font-bold text-on-surface">{item.komponen}</p>
                        </div>
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Waktu & Tempat</label>
                            <div className="flex items-center gap-2 text-sm text-on-surface font-medium">
                                <Icon name="calendar_today" className="text-base text-on-surface-variant" />
                                {item.waktu}
                            </div>
                            <div className="flex items-center gap-2 text-sm text-on-surface font-medium mt-1">
                                <Icon name="location_on" className="text-base text-on-surface-variant" />
                                {item.tempat}
                            </div>
                        </div>
                        {item.keterangan && (
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Keterangan</label>
                                <p className="text-sm text-on-surface-variant leading-relaxed italic">"{item.keterangan}"</p>
                            </div>
                        )}
                    </div>

                    {/* Image */}
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-2 block">Dokumentasi</label>
                        {item.image ? (
                            isPdfName(item.image_name) ? (
                                <a href={item.image} target="_blank" rel="noopener" className="rounded-2xl border-2 border-slate-100 shadow-sm aspect-video flex flex-col items-center justify-center gap-2 text-rose-500 hover:bg-rose-50 transition-colors">
                                    <Icon name="picture_as_pdf" className="text-4xl" />
                                    <span className="text-xs font-bold">Buka PDF</span>
                                </a>
                            ) : (
                                <div className="rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm aspect-video group relative">
                                    <img src={item.image} className="w-full h-full object-cover" alt="Foto" />
                                    <a href={item.image} target="_blank" rel="noopener" className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold text-sm gap-2">
                                        <Icon name="open_in_new" className="text-xl" /> Buka Gambar
                                    </a>
                                </div>
                            )
                        ) : (
                            <div className="rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 aspect-video flex flex-col items-center justify-center text-slate-300 gap-2">
                                <Icon name="no_photography" className="text-4xl" />
                                <span className="text-[10px] font-bold">Tidak ada foto</span>
                            </div>
                        )}
                    </div>
                </div>

                {/* Footer status */}
                <div className="pt-6 border-t border-slate-100 flex items-center justify-between">
                    <div className="flex flex-col">
                        <span className="text-[10px] font-black text-on-surface-variant uppercase">Dicatat Pada</span>
                        <span className="text-xs font-medium text-on-surface">{item.created_at}</span>
                    </div>
                    <button onClick={onClose} className="px-6 py-2 bg-surface-container hover:bg-black/5 rounded-xl text-sm font-bold transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </Modal>
    );
}

// ─── Item Row ─────────────────────────────────────────────────────────────────
function AktivitasRow({ item, index, onDetail, onEdit, onDelete }: { item: AktivitasItem; index: number; onDetail: () => void; onEdit: () => void; onDelete: () => void }) {
    const meta = KAT_META[item.kategori];
    return (
        <div 
            onClick={onDetail}
            className={`flex items-start gap-4 p-4 rounded-2xl border ${meta.border} ${meta.bg} hover:shadow-md cursor-pointer transition-all group relative overflow-hidden active:scale-[0.99]`}
        >
            {/* Indexing number */}
            <div className="absolute top-0 right-0 px-3 py-1 bg-white/30 backdrop-blur text-[10px] font-black text-on-surface-variant/40 rounded-bl-xl group-hover:bg-primary group-hover:text-white transition-colors">
                #{index}
            </div>
            
            <div className={`w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-white/70 shadow-sm`}>
                <Icon name={meta.icon} className={`text-xl ${meta.color}`} filled />
            </div>
            <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2 flex-wrap">
                    <span className={`px-2 py-0.5 rounded-full text-[10px] font-black uppercase ${meta.bg} ${meta.color} border ${meta.border}`}>
                        {meta.label}
                    </span>
                    {item.tipe_kegiatan && (
                        <span className="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-primary-container text-white">
                            {item.tipe_kegiatan}
                        </span>
                    )}
                    {item.komponen !== '-' && (
                        <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/70 text-on-surface-variant">
                            {item.komponen}
                        </span>
                    )}
                </div>
                <div className="flex gap-3 mt-2">
                    {item.image && (
                        <div className="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 border border-white/50 flex items-center justify-center bg-white/70">
                            {isPdfName(item.image_name) ? (
                                <Icon name="picture_as_pdf" className="text-lg text-rose-500" />
                            ) : (
                                <img src={item.image} alt="Foto" className="w-full h-full object-cover" />
                            )}
                        </div>
                    )}
                    <div>
                        <p className="font-bold text-on-surface text-sm">{item.kegiatan}</p>
                        <p className="text-xs text-on-surface-variant mt-0.5">
                            {item.waktu} · {item.tempat}
                        </p>
                    </div>
                </div>
                {item.keterangan && (
                    <p className="text-xs text-on-surface-variant mt-1 line-clamp-1 italic">"{item.keterangan}"</p>
                )}
            </div>
            <div onClick={e => e.stopPropagation()}>
                <ActionButtons
                    onEdit={onEdit}
                    onDelete={onDelete}
                    variant="glass"
                />
            </div>
        </div>
    );
}

// ─── Main ─────────────────────────────────────────────────────────────────────
export default function AktivitasIndex({ items, komponens, categories, pagination, filters, komponenPenilaian }: AktivitasIndexProps) {
    const [activeKat, setActiveKat] = useState<Kategori>('akademik');
    const [search, setSearch] = useState(filters.search || '');
    const [tipe, setTipe] = useState(filters.tipe || '');
    const [dateFrom, setDateFrom] = useState(filters.date_from || '');
    const [dateTo, setDateTo] = useState(filters.date_to || '');
    const [showModal, setShowModal] = useState(false);
    const [editItem, setEditItem] = useState<AktivitasItem | null>(null);
    const [detailItem, setDetailItem] = useState<AktivitasItem | null>(null);
    const [itemToDelete, setItemToDelete] = useState<AktivitasItem | null>(null);
    const [deleting, setDeleting] = useState(false);
    const [actionProcessed, setActionProcessed] = useState(false);

    // Filter handling
    useEffect(() => {
        const timer = setTimeout(() => {
            if (search !== (filters.search || '') || tipe !== (filters.tipe || '')
                || dateFrom !== (filters.date_from || '') || dateTo !== (filters.date_to || '')) {
                router.get('/mahasiswa/aktivitas', { search, tipe, date_from: dateFrom, date_to: dateTo, page: 1 }, {
                    preserveState: true,
                    replace: true,
                });
            }
        }, 500);
        return () => clearTimeout(timer);
    }, [search, tipe, dateFrom, dateTo]);

    const handlePageChange = (page: number) => {
        router.get('/mahasiswa/aktivitas', { search, tipe, date_from: dateFrom, date_to: dateTo, page }, { preserveScroll: true });
    };

    const handlePerPageChange = (perPage: number) => {
        router.get('/mahasiswa/aktivitas', { search, tipe, date_from: dateFrom, date_to: dateTo, perPage, page: 1 }, { preserveScroll: true });
    };

    function resetDateRange() { setDateFrom(''); setDateTo(''); }

    useEffect(() => {
        const params = new URLSearchParams(window.location.search);
        if (params.get('action') === 'create' && !actionProcessed) {
            openAdd();
            setActionProcessed(true);
            // Clean URL without refresh
            const newUrl = window.location.pathname;
            window.history.replaceState({}, '', newUrl);
        }
    }, []);

    function openAdd() { setEditItem(null); setShowModal(true); }
    function openEdit(item: AktivitasItem) { setEditItem(item); setShowModal(true); }
    function closeModal() { setShowModal(false); setEditItem(null); }

    function deleteItem() {
        if (!itemToDelete) return;
        setDeleting(true);
        router.delete(`/mahasiswa/aktivitas/${itemToDelete.id}`, {
            data: { kategori: itemToDelete.kategori },
            preserveState: true, preserveScroll: true,
            onSuccess: () => setItemToDelete(null),
            onFinish: () => setDeleting(false),
        });
    }

    const filteredByTab = items.filter(i => i.kategori === activeKat);

    // Summary counts
    const counts = categories.reduce((acc, k) => {
        acc[k] = items.filter(i => i.kategori === k).length;
        return acc;
    }, {} as Record<string, number>);

    return (
        <AppLayout searchPlaceholder="Cari aktivitas...">
            <Head title="Aktivitas Saya" />

            <PageHeader
                title="Aktivitas Saya"
                subtitle="Log semua kegiatan akademik, leadership, dan pengembangan diri."
                breadcrumbs={[{ label: 'Beranda', href: '/mahasiswa' }, { label: 'Aktivitas' }]}
                actions={
                    <button onClick={openAdd}
                        className="btn-primary flex items-center gap-2 text-sm px-4 py-2.5 rounded-xl font-bold">
                        <Icon name="add" className="text-xl" />
                        Log Aktivitas
                    </button>
                }
            />

            {/* Search & Filter bar */}
            <div className="flex flex-col md:flex-row gap-3 mb-6">
                <div className="flex-1 relative">
                    <Icon name="search" className="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/50" />
                    <input
                        value={search}
                        onChange={e => setSearch(e.target.value)}
                        placeholder="Cari kegiatan..."
                        className="glass-input w-full pl-10 pr-4 py-2.5"
                    />
                </div>
                <div className="flex items-center gap-2">
                    <Icon name="filter_list" className="text-on-surface-variant" />
                    <select
                        value={tipe}
                        onChange={e => setTipe(e.target.value)}
                        className="glass-input text-sm py-2 px-4"
                    >
                        <option value="">Semua Tipe</option>
                        <option value="Prestasi">Prestasi</option>
                        <option value="Unggulan">Unggulan</option>
                    </select>
                </div>
            </div>

            {/* Filter Rentang Tanggal */}
            <div className="glass-card rounded-2xl p-3 mb-6 flex flex-wrap items-center gap-3">
                <div className="flex items-center gap-1.5 text-xs font-bold text-on-surface-variant uppercase tracking-wide">
                    <Icon name="date_range" className="text-base" />
                    Rentang Tanggal
                </div>
                <div className="flex items-center gap-2">
                    <input type="date" value={dateFrom} onChange={e => setDateFrom(e.target.value)}
                        max={dateTo || undefined}
                        className="glass-input text-sm py-1.5 px-3" />
                    <span className="text-on-surface-variant text-sm">s/d</span>
                    <input type="date" value={dateTo} onChange={e => setDateTo(e.target.value)}
                        min={dateFrom || undefined}
                        className="glass-input text-sm py-1.5 px-3" />
                </div>
                {(dateFrom || dateTo) && (
                    <button onClick={resetDateRange}
                        className="flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors">
                        <Icon name="close" className="text-sm" /> Reset Tanggal
                    </button>
                )}
                <span className="text-[11px] text-on-surface-variant ml-auto">
                    Lihat aktivitas sebelumnya dengan memilih rentang tanggal.
                </span>
            </div>

            {/* Summary cards — 4 categories */}
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                {(Object.entries(KAT_META) as [Kategori, typeof KAT_META[Kategori]][]).map(([k, meta]) => (
                    <button key={k} onClick={() => setActiveKat(k)}
                        className={`rounded-2xl p-3 text-left transition-all border ${
                            activeKat === k ? `${meta.bg} ${meta.border} shadow-md` : 'glass-card border-transparent hover:shadow-sm'
                        }`}>
                        <div className={`w-8 h-8 rounded-lg flex items-center justify-center mb-2 ${meta.bg}`}>
                            <Icon name={meta.icon} className={`text-base ${meta.color}`} filled />
                        </div>
                        <p className="text-[10px] font-black uppercase text-on-surface-variant leading-tight">{meta.label}</p>
                        <p className={`text-xl font-bold mt-0.5 ${meta.color}`}>{counts[k] ?? 0}</p>
                    </button>
                ))}
            </div>

            {/* Tab header */}
            <div className="flex items-center justify-between mb-4">
                <h3 className={`font-bold text-on-surface flex items-center gap-2`}>
                    <Icon name={KAT_META[activeKat].icon} className={`text-xl ${KAT_META[activeKat].color}`} filled />
                    {KAT_META[activeKat].label}
                    <span className="text-sm font-normal text-on-surface-variant">({filteredByTab.length} kegiatan)</span>
                </h3>
            </div>

            {/* List */}
            <div className="space-y-3 mb-8">
                {filteredByTab.length === 0 ? (
                    <div className={`rounded-3xl p-12 text-center ${KAT_META[activeKat].bg} border ${KAT_META[activeKat].border}`}>
                        <Icon name={KAT_META[activeKat].icon} className={`text-5xl ${KAT_META[activeKat].color} opacity-30 mb-3`} filled />
                        <p className="font-bold text-on-surface mb-1">Belum ada aktivitas {KAT_META[activeKat].label}</p>
                        <p className="text-sm text-on-surface-variant mb-4">Mulai log kegiatan kamu sekarang.</p>
                        <button onClick={openAdd}
                            className="px-5 py-2 rounded-xl bg-primary-container text-white text-sm font-bold inline-flex items-center gap-2">
                            <Icon name="add" className="text-base" /> Log Pertama
                        </button>
                    </div>
                ) : (
                    filteredByTab.map((item, i) => (
                        <AktivitasRow key={`${item.kategori}-${item.id}`} 
                            index={(pagination.current_page - 1) * pagination.per_page + i + 1}
                            item={item}
                            onDetail={() => setDetailItem(item)}
                            onEdit={() => openEdit(item)}
                            onDelete={() => setItemToDelete(item)} />
                    ))
                )}
            </div>

            {/* Pagination */}
            <div className="glass-card rounded-2xl overflow-hidden mb-10">
                <Pagination
                    currentPage={pagination.current_page}
                    totalPages={pagination.last_page}
                    totalItems={pagination.total}
                    perPage={pagination.per_page}
                    onPageChange={handlePageChange}
                    onPerPageChange={handlePerPageChange}
                />
            </div>

            {/* Modal */}
            {showModal && (
                <AktivitasModal
                    activeKat={activeKat}
                    komponens={komponens}
                    editItem={editItem}
                    onClose={closeModal}
                    komponenPenilaian={komponenPenilaian}
                />
            )}
            {/* Detail Modal */}
            {detailItem && (
                <AktivitasDetailModal
                    item={detailItem}
                    onClose={() => setDetailItem(null)}
                />
            )}

            <ConfirmDialog
                open={!!itemToDelete}
                onClose={() => setItemToDelete(null)}
                onConfirm={deleteItem}
                loading={deleting}
                title="Hapus Aktivitas?"
                message={`Apakah Anda yakin ingin menghapus "${itemToDelete?.kegiatan}" dari log aktivitas Anda?`}
                confirmText="Ya, Hapus"
                type="danger"
            />
        </AppLayout>
    );
}
