import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';

// ─── Types ────────────────────────────────────────────────────────────────────
type Kategori = 'akademik' | 'leadership' | 'karakter' | 'kreativitas';

interface KomponenItem { id: number; nama: string; kode: string; }
interface AktivitasItem {
    id: number; kategori: Kategori; kegiatan: string; komponen: string;
    waktu: string; tempat: string; keterangan?: string; nilai?: string; created_at: string;
}
interface AktivitasIndexProps extends PageProps {
    items: AktivitasItem[];
    komponens: Record<string, KomponenItem[]>;
    categories: Kategori[];
}

// ─── Constants ────────────────────────────────────────────────────────────────
const KAT_META: Record<Kategori, { label: string; icon: string; color: string; bg: string; border: string }> = {
    akademik:    { label: 'Akademik',                    icon: 'school',   color: 'text-blue-600',    bg: 'bg-blue-50',    border: 'border-blue-200' },
    leadership:  { label: 'Leadership',                  icon: 'groups',   color: 'text-purple-600',  bg: 'bg-purple-50',  border: 'border-purple-200' },
    karakter:    { label: 'Karakter Islami',              icon: 'mosque',   color: 'text-emerald-600', bg: 'bg-emerald-50', border: 'border-emerald-200' },
    kreativitas: { label: 'Kreativitas & Kewirausahaan', icon: 'palette',  color: 'text-amber-600',   bg: 'bg-amber-50',   border: 'border-amber-200' },
};

// Komponen aspek → kategori mapping
const ASPEK_MAP: Record<Kategori, string[]> = {
    akademik:    ['Akademik'],
    leadership:  ['Leadership'],
    karakter:    ['Karakter Islami'],
    kreativitas: ['Kreativitas', 'Kewirausahaan'], // both aspeks combined
};

// ─── Form Modal ───────────────────────────────────────────────────────────────
function AktivitasModal({
    activeKat, komponens, editItem, onClose,
}: {
    activeKat: Kategori;
    komponens: Record<string, KomponenItem[]>;
    editItem: AktivitasItem | null;
    onClose: () => void;
}) {
    const [form, setForm] = useState({
        kategori:    editItem?.kategori    ?? activeKat,
        kegiatan:    editItem?.kegiatan    ?? '',
        komponen_id: '',
        waktu:       editItem?.waktu       ?? '',
        tempat:      editItem?.tempat      ?? '',
        keterangan:  editItem?.keterangan  ?? '',
    });
    const [saving, setSaving] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});

    const aspeks = ASPEK_MAP[form.kategori as Kategori];
    // Flatten all matching aspeks (kreativitas has 2)
    const availKomponen: KomponenItem[] = aspeks.flatMap(a => komponens[a] ?? []);
    const meta = KAT_META[form.kategori as Kategori];

    function set(field: string, val: string) { setForm(p => ({ ...p, [field]: val })); }

    function submit(e: React.FormEvent) {
        e.preventDefault();
        if (!form.kegiatan || !form.waktu || !form.tempat) {
            setErrors({ kegiatan: !form.kegiatan ? 'Wajib diisi' : '', waktu: !form.waktu ? 'Wajib diisi' : '', tempat: !form.tempat ? 'Wajib diisi' : '' });
            return;
        }
        setSaving(true);
        if (editItem) {
            router.put(`/mahasiswa/aktivitas/${editItem.id}`, form, {
                preserveState: true, preserveScroll: true,
                onSuccess: () => onClose(),
                onError: (e) => setErrors(e as Record<string, string>),
                onFinish: () => setSaving(false),
            });
        } else {
            router.post('/mahasiswa/aktivitas', form, {
                preserveState: true, preserveScroll: true,
                onSuccess: () => onClose(),
                onError: (e) => setErrors(e as Record<string, string>),
                onFinish: () => setSaving(false),
            });
        }
    }

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div className="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                {/* Header */}
                <div className="flex items-center justify-between px-6 py-5 border-b border-white/40">
                    <div className="flex items-center gap-3">
                        <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${meta.bg}`}>
                            <Icon name={meta.icon} className={`text-xl ${meta.color}`} filled />
                        </div>
                        <h3 className="font-bold text-on-surface">
                            {editItem ? 'Edit' : 'Log'} Aktivitas
                        </h3>
                    </div>
                    <button onClick={onClose} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center">
                        <Icon name="close" className="text-on-surface-variant" />
                    </button>
                </div>

                <form onSubmit={submit} className="px-6 py-5 space-y-4">
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

                    {/* Komponen */}
                    {availKomponen.length > 0 && (
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Komponen</label>
                            <select value={form.komponen_id} onChange={e => set('komponen_id', e.target.value)}
                                className="glass-input w-full text-sm">
                                <option value="">— Pilih komponen —</option>
                                {availKomponen.map(k => (
                                    <option key={k.id} value={k.id}>[{k.kode}] {k.nama}</option>
                                ))}
                            </select>
                        </div>
                    )}

                    {/* Kegiatan */}
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Nama Kegiatan *</label>
                        <input value={form.kegiatan} onChange={e => set('kegiatan', e.target.value)}
                            className={`glass-input w-full text-sm ${errors.kegiatan ? 'border-rose-400' : ''}`}
                            placeholder="Contoh: Seminar Kepemimpinan" />
                        {errors.kegiatan && <p className="text-xs text-rose-500 mt-1">{errors.kegiatan}</p>}
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

                    <div className="flex gap-3 pt-2">
                        <button type="button" onClick={onClose}
                            className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">
                            Batal
                        </button>
                        <button type="submit" disabled={saving}
                            className={`flex-1 py-2.5 rounded-xl font-bold text-sm text-white transition-opacity disabled:opacity-50 flex items-center justify-center gap-2 ${
                                meta.bg.replace('bg-', 'bg-').replace('-50', '-500') || 'bg-primary-container'
                            }`}
                            style={{ background: `var(--color-primary-container)` }}>
                            <Icon name="save" className="text-base" />
                            {saving ? 'Menyimpan...' : editItem ? 'Simpan' : 'Log Aktivitas'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

// ─── Item Row ─────────────────────────────────────────────────────────────────
function AktivitasRow({ item, onEdit, onDelete }: { item: AktivitasItem; onEdit: () => void; onDelete: () => void }) {
    const meta = KAT_META[item.kategori];
    return (
        <div className={`flex items-start gap-4 p-4 rounded-2xl border ${meta.border} ${meta.bg} hover:shadow-sm transition-shadow`}>
            <div className={`w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-white/70`}>
                <Icon name={meta.icon} className={`text-xl ${meta.color}`} filled />
            </div>
            <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2 flex-wrap">
                    <span className={`px-2 py-0.5 rounded-full text-[10px] font-black uppercase ${meta.bg} ${meta.color} border ${meta.border}`}>
                        {meta.label}
                    </span>
                    {item.komponen !== '-' && (
                        <span className="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/70 text-on-surface-variant">
                            {item.komponen}
                        </span>
                    )}
                </div>
                <p className="font-bold text-on-surface text-sm mt-1">{item.kegiatan}</p>
                <p className="text-xs text-on-surface-variant mt-0.5">
                    {item.waktu} · {item.tempat}
                </p>
                {item.keterangan && (
                    <p className="text-xs text-on-surface-variant mt-1 line-clamp-1">{item.keterangan}</p>
                )}
            </div>
            <div className="flex gap-1 flex-shrink-0 mt-1">
                <button onClick={onEdit} className="w-8 h-8 rounded-lg bg-white/70 hover:bg-blue-100 hover:text-blue-600 text-on-surface-variant flex items-center justify-center transition-colors">
                    <Icon name="edit" className="text-sm" />
                </button>
                <button onClick={onDelete} className="w-8 h-8 rounded-lg bg-white/70 hover:bg-rose-100 hover:text-rose-600 text-on-surface-variant flex items-center justify-center transition-colors">
                    <Icon name="delete" className="text-sm" />
                </button>
            </div>
        </div>
    );
}

// ─── Main ─────────────────────────────────────────────────────────────────────
export default function AktivitasIndex({ items, komponens, categories }: AktivitasIndexProps) {
    const [activeKat, setActiveKat] = useState<Kategori>('akademik');
    const [showModal, setShowModal] = useState(false);
    const [editItem, setEditItem] = useState<AktivitasItem | null>(null);

    function openAdd() { setEditItem(null); setShowModal(true); }
    function openEdit(item: AktivitasItem) { setEditItem(item); setShowModal(true); }
    function closeModal() { setShowModal(false); setEditItem(null); }

    function deleteItem(item: AktivitasItem) {
        if (!confirm(`Hapus aktivitas "${item.kegiatan}"?`)) return;
        router.delete(`/mahasiswa/aktivitas/${item.id}`, {
            data: { kategori: item.kategori },
            preserveState: true, preserveScroll: true,
        });
    }

    const filtered = items.filter(i => activeKat === 'akademik' ? true : i.kategori === activeKat);
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
            <div className="space-y-3">
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
                    filteredByTab.map(item => (
                        <AktivitasRow key={`${item.kategori}-${item.id}`} item={item}
                            onEdit={() => openEdit(item)}
                            onDelete={() => deleteItem(item)} />
                    ))
                )}
            </div>

            {/* Modal */}
            {showModal && (
                <AktivitasModal
                    activeKat={activeKat}
                    komponens={komponens}
                    editItem={editItem}
                    onClose={closeModal}
                />
            )}
        </AppLayout>
    );
}
