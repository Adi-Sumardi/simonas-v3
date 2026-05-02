import { Head, router } from '@inertiajs/react';
import { useState, useRef } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';
import { PageProps } from '@/types';

// ─── Types ────────────────────────────────────────────────────────────────────
type Tipe = 'pendidikan' | 'organisasi' | 'pekerjaan' | 'penghargaan' | 'sertifikasi';

interface Riwayat {
    id: number; tipe: Tipe; judul: string; posisi?: string;
    mulai?: string; selesai?: string; masih_berlangsung: boolean;
    deskripsi?: string; lokasi?: string;
}

interface UserInfo {
    id: number; name: string; email: string; avatar?: string;
    asrama?: string; angkatan?: string; bio?: string; no_hp?: string;
}

interface AlumniInfo {
    alamat_domisili: string | null;
    asal_asrama: string | null;
    tahun_masuk_asrama: string | null;
    tahun_keluar_asrama: string | null;
    bidang_keahlian: string | null;
    pekerjaan_sekarang: string | null;
    bidang_pekerjaan: string | null;
}

interface RecentPost {
    id: number; type: string; title: string | null; content: string;
    likes_count: number; comments_count: number; created_at: string;
}

interface ProfilProps extends PageProps {
    user: UserInfo;
    alumni: AlumniInfo;
    riwayats: Record<Tipe, Riwayat[]>;
    posts: RecentPost[];
    business?: { id: number; nama_usaha: string } | null;
}

// ─── Constants — emerald theme untuk alumni ────────────────────────────────────
const TIPE_META: Record<Tipe, { label: string; icon: string; color: string; bg: string }> = {
    pendidikan:  { label: 'Pendidikan',   icon: 'school',         color: 'text-blue-600',    bg: 'bg-blue-100' },
    organisasi:  { label: 'Organisasi',   icon: 'groups',         color: 'text-purple-600',  bg: 'bg-purple-100' },
    pekerjaan:   { label: 'Pekerjaan',    icon: 'work',           color: 'text-emerald-600', bg: 'bg-emerald-100' },
    penghargaan: { label: 'Penghargaan',  icon: 'military_tech',  color: 'text-amber-600',   bg: 'bg-amber-100' },
    sertifikasi: { label: 'Sertifikasi',  icon: 'verified',       color: 'text-rose-600',    bg: 'bg-rose-100' },
};
const TIPES = Object.keys(TIPE_META) as Tipe[];

function newEntry(tipe: Tipe): Omit<Riwayat, 'id'> {
    return { tipe, judul: '', posisi: '', mulai: '', selesai: '', masih_berlangsung: false, deskripsi: '', lokasi: '' };
}

// ─── Single entry form row ─────────────────────────────────────────────────────
function EntryRow({
    entry, idx, tipe, onChange, onRemove, showRemove,
}: {
    entry: Omit<Riwayat, 'id'>; idx: number; tipe: Tipe;
    onChange: (idx: number, field: string, val: unknown) => void;
    onRemove: (idx: number) => void; showRemove: boolean;
}) {
    return (
        <div className="glass-card rounded-2xl p-5 space-y-4 relative">
            {showRemove && (
                <button type="button" onClick={() => onRemove(idx)}
                    className="absolute top-3 right-3 w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center hover:bg-rose-200 transition-colors">
                    <Icon name="close" className="text-sm" />
                </button>
            )}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">
                        {tipe === 'pendidikan' ? 'Nama Institusi' : tipe === 'pekerjaan' ? 'Nama Perusahaan' : 'Nama Organisasi / Penghargaan'} *
                    </label>
                    <input value={entry.judul} onChange={e => onChange(idx, 'judul', e.target.value)}
                        className="glass-input w-full text-sm" placeholder="Contoh: Universitas Indonesia" required />
                </div>
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">
                        {tipe === 'pendidikan' ? 'Program Studi / Gelar' : tipe === 'pekerjaan' ? 'Jabatan' : 'Posisi / Peran'}
                    </label>
                    <input value={entry.posisi ?? ''} onChange={e => onChange(idx, 'posisi', e.target.value)}
                        className="glass-input w-full text-sm" placeholder="Contoh: S1 Teknik Informatika" />
                </div>
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Mulai</label>
                    <input type="month" value={entry.mulai ?? ''} onChange={e => onChange(idx, 'mulai', e.target.value)}
                        className="glass-input w-full text-sm" />
                </div>
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Selesai</label>
                    <input type="month" value={entry.selesai ?? ''} onChange={e => onChange(idx, 'selesai', e.target.value)}
                        disabled={entry.masih_berlangsung} className="glass-input w-full text-sm disabled:opacity-40" />
                    <label className="flex items-center gap-2 mt-2 cursor-pointer text-xs text-on-surface-variant">
                        <input type="checkbox" checked={entry.masih_berlangsung}
                            onChange={e => onChange(idx, 'masih_berlangsung', e.target.checked)} className="rounded" />
                        Masih berlangsung
                    </label>
                </div>
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Lokasi</label>
                    <input value={entry.lokasi ?? ''} onChange={e => onChange(idx, 'lokasi', e.target.value)}
                        className="glass-input w-full text-sm" placeholder="Kota, Negara" />
                </div>
                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Deskripsi</label>
                    <textarea value={entry.deskripsi ?? ''} onChange={e => onChange(idx, 'deskripsi', e.target.value)}
                        rows={2} className="glass-input w-full text-sm resize-none" placeholder="Catatan singkat..." />
                </div>
            </div>
        </div>
    );
}

// ─── Add/Edit Modal ────────────────────────────────────────────────────────────
function RiwayatModal({
    tipe, editItem, onClose,
}: { tipe: Tipe; editItem: Riwayat | null; onClose: () => void }) {
    const [entries, setEntries] = useState<Omit<Riwayat, 'id'>[]>(
        editItem ? [{ ...editItem }] : [newEntry(tipe)]
    );
    const [saving, setSaving] = useState(false);

    function change(idx: number, field: string, val: unknown) {
        setEntries(prev => prev.map((e, i) => i === idx ? { ...e, [field]: val } : e));
    }
    function addRow() { setEntries(prev => [...prev, newEntry(tipe)]); }
    function removeRow(idx: number) { setEntries(prev => prev.filter((_, i) => i !== idx)); }

    function submit(e: React.FormEvent) {
        e.preventDefault();
        setSaving(true);
        if (editItem) {
            router.put(`/alumni/profil/riwayat/${editItem.id}`, entries[0], {
                preserveState: true, preserveScroll: true,
                onFinish: () => { setSaving(false); onClose(); },
            });
        } else {
            router.post('/alumni/profil/riwayat', { entries }, {
                preserveState: true, preserveScroll: true,
                onFinish: () => { setSaving(false); onClose(); },
            });
        }
    }

    const meta = TIPE_META[tipe];

    return (
        <Modal open={true} onClose={onClose} title={`${editItem ? 'Edit' : 'Tambah'} ${meta.label}`} icon={meta.icon} size="md"
            footer={
                <>
                    <button type="button" onClick={onClose}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-black/10 transition-colors">
                        Batal
                    </button>
                    <button form="riwayat-form" type="submit" disabled={saving}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-emerald-500 text-white disabled:opacity-50 hover:bg-emerald-600 transition-colors flex items-center gap-2">
                        <Icon name="save" className="text-base" />
                        {saving ? 'Menyimpan...' : editItem ? 'Simpan Perubahan' : `Simpan ${entries.length > 1 ? `(${entries.length})` : ''}`}
                    </button>
                </>
            }>
            {!editItem && <p className="text-xs text-on-surface-variant mb-5">Bisa tambahkan lebih dari satu sekaligus</p>}
            <form id="riwayat-form" onSubmit={submit} className="space-y-4">
                {entries.map((entry, idx) => (
                    <EntryRow key={idx} entry={entry} idx={idx} tipe={tipe}
                        onChange={change} onRemove={removeRow} showRemove={entries.length > 1} />
                ))}

                {!editItem && (
                    <button type="button" onClick={addRow}
                        className="w-full py-3 rounded-2xl border-2 border-dashed border-outline-variant text-on-surface-variant text-sm font-bold flex items-center justify-center gap-2 hover:border-emerald-500 hover:text-emerald-600 transition-colors">
                        <Icon name="add_circle" className="text-xl" />
                        Tambah {meta.label} Lagi
                    </button>
                )}
            </form>
        </Modal>
    );
}

// ─── Riwayat Card ──────────────────────────────────────────────────────────────
function RiwayatCard({ item, onEdit, onDelete }: { item: Riwayat; onEdit: () => void; onDelete: () => void }) {
    const meta = TIPE_META[item.tipe];
    return (
        <div className="glass-card rounded-2xl p-4 flex gap-4 items-start hover:shadow-md transition-shadow">
            <div className={`w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 ${meta.bg}`}>
                <Icon name={meta.icon} className={`text-lg ${meta.color}`} filled />
            </div>
            <div className="flex-1 min-w-0">
                <p className="font-bold text-on-surface text-sm">{item.judul}</p>
                {item.posisi && <p className="text-xs text-on-surface-variant">{item.posisi}</p>}
                <p className="text-[11px] text-outline mt-0.5">
                    {item.mulai ?? '?'} → {item.masih_berlangsung ? 'Sekarang' : (item.selesai ?? '?')}
                    {item.lokasi && ` · ${item.lokasi}`}
                </p>
                {item.deskripsi && <p className="text-xs text-on-surface-variant mt-1 line-clamp-2">{item.deskripsi}</p>}
            </div>
            <div className="flex gap-1 flex-shrink-0">
                <button onClick={onEdit} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-blue-100 hover:text-blue-600 text-on-surface-variant flex items-center justify-center transition-colors">
                    <Icon name="edit" className="text-sm" />
                </button>
                <button onClick={onDelete} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-rose-100 hover:text-rose-600 text-on-surface-variant flex items-center justify-center transition-colors">
                    <Icon name="delete" className="text-sm" />
                </button>
            </div>
        </div>
    );
}

// ─── Main Component ────────────────────────────────────────────────────────────
export default function Profil({ user, alumni, riwayats, posts }: ProfilProps) {
    const [activeTab, setActiveTab] = useState<Tipe>('pekerjaan');
    const [modalTipe, setModalTipe] = useState<Tipe | null>(null);
    const [editItem, setEditItem] = useState<Riwayat | null>(null);
    const [editingBasic, setEditingBasic] = useState(false);

    // Basic info form state
    const [form, setForm] = useState({
        name: user.name,
        bio: user.bio ?? '',
        no_hp: user.no_hp ?? '',
        asrama: user.asrama ?? '',
        angkatan: user.angkatan ?? '',
        alamat_domisili: alumni.alamat_domisili ?? '',
        tahun_masuk_asrama: alumni.tahun_masuk_asrama ?? '',
        tahun_keluar_asrama: alumni.tahun_keluar_asrama ?? '',
        bidang_keahlian: alumni.bidang_keahlian ?? '',
    });

    const [avatarProcessing, setAvatarProcessing] = useState(false);
    const fileInputRef = useRef<HTMLInputElement>(null);

    function handleAvatarChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (file) {
            setAvatarProcessing(true);
            router.post('/alumni/profil/avatar', { avatar: file }, {
                forceFormData: true,
                onFinish: () => {
                    setAvatarProcessing(false);
                    if (fileInputRef.current) fileInputRef.current.value = '';
                }
            });
        }
    }

    function openAdd(tipe: Tipe) { setModalTipe(tipe); setEditItem(null); }
    function openEdit(item: Riwayat) { setModalTipe(item.tipe); setEditItem(item); }
    function closeModal() { setModalTipe(null); setEditItem(null); }

    function deleteRiwayat(id: number) {
        if (!confirm('Hapus riwayat ini?')) return;
        router.delete(`/alumni/profil/riwayat/${id}`, { preserveState: true, preserveScroll: true });
    }

    function saveBasic() {
        router.put('/alumni/profil', form, {
            preserveState: true, preserveScroll: true,
            onSuccess: () => setEditingBasic(false),
        });
    }

    const initials = user.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
    const currentItems: Riwayat[] = riwayats[activeTab] ?? [];

    return (
        <AppLayout searchPlaceholder="Cari profil...">
            <Head title="Profil Alumni" />

            <PageHeader
                title="Profil Saya"
                subtitle="Kelola informasi karir dan riwayat alumni."
                breadcrumbs={[{ label: 'Beranda', href: '/dashboard' }, { label: 'Profil' }]}
            />

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {/* ── Left: Profile card + Alumni-specific basic info ── */}
                <div className="space-y-5">

                    {/* Avatar + Info */}
                    <div className="glass-card rounded-3xl p-6 text-center space-y-4">
                        <div className="relative inline-block group">
                            <div className="w-24 h-24 rounded-full overflow-hidden bg-gradient-to-br from-emerald-400 to-teal-600 mx-auto ring-4 ring-white/60 flex items-center justify-center">
                                {user.avatar
                                    ? <img src={user.avatar} alt={user.name} className="w-full h-full object-cover" />
                                    : <span className="font-display font-black text-3xl text-white">{initials}</span>
                                }
                            </div>
                            <button 
                                onClick={() => fileInputRef.current?.click()}
                                disabled={avatarProcessing}
                                className="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-white shadow-lg border border-slate-100 text-emerald-600 flex items-center justify-center hover:scale-110 transition-all hover:bg-emerald-50"
                                title="Ganti Foto"
                            >
                                <Icon name={avatarProcessing ? 'sync' : 'photo_camera'} className={`text-sm ${avatarProcessing ? 'animate-spin' : ''}`} />
                            </button>
                            <input ref={fileInputRef} type="file" accept="image/*" onChange={handleAvatarChange} className="hidden" />
                        </div>
                        <div>
                            <h2 className="font-display font-bold text-xl text-on-surface">{user.name}</h2>
                            <p className="text-sm text-on-surface-variant">{user.email}</p>
                            {alumni.pekerjaan_sekarang && (
                                <p className="text-xs font-bold text-emerald-700 mt-1 flex items-center justify-center gap-1">
                                    <Icon name="work" className="text-xs" filled /> {alumni.pekerjaan_sekarang}
                                    {alumni.bidang_pekerjaan && <span className="text-on-surface-variant"> · {alumni.bidang_pekerjaan}</span>}
                                </p>
                            )}
                        </div>

                        {/* Stats pills */}
                        <div className="flex flex-wrap gap-2 justify-center text-xs">
                            {alumni.asal_asrama && (
                                <span className="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center gap-1">
                                    <Icon name="home" className="text-sm" /> {alumni.asal_asrama}
                                </span>
                            )}
                            {alumni.tahun_keluar_asrama && (
                                <span className="px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-bold flex items-center gap-1">
                                    <Icon name="calendar_today" className="text-sm" /> Lulus {alumni.tahun_keluar_asrama}
                                </span>
                            )}
                        </div>

                        {/* Bio */}
                        <div className="text-left">
                            <p className="text-sm text-on-surface-variant leading-relaxed">
                                {user.bio || <span className="italic opacity-60">Belum ada bio</span>}
                            </p>
                        </div>
                    </div>

                    {/* Alumni-specific basic info */}
                    <div className="glass-card rounded-3xl p-5">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="font-bold text-on-surface text-sm flex items-center gap-2">
                                <Icon name="badge" className="text-emerald-600 text-lg" filled />
                                Info Alumni
                            </h3>
                            <button onClick={() => setEditingBasic(!editingBasic)}
                                className={`flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors ${
                                    editingBasic ? 'bg-surface-container text-on-surface-variant' : 'bg-emerald-500 text-white hover:bg-emerald-600'
                                }`}>
                                <Icon name={editingBasic ? 'close' : 'edit'} className="text-sm" />
                                {editingBasic ? 'Batal' : 'Edit'}
                            </button>
                        </div>

                        {editingBasic ? (
                            <div className="space-y-3">
                                {([
                                    ['name', 'Nama Lengkap', 'text'],
                                    ['no_hp', 'No. WhatsApp', 'tel'],
                                    ['alamat_domisili', 'Kota Domisili', 'text'],
                                    ['asrama', 'Asal Asrama', 'text'],
                                    ['tahun_masuk_asrama', 'Tahun Masuk', 'text'],
                                    ['tahun_keluar_asrama', 'Tahun Lulus', 'text'],
                                    ['bidang_keahlian', 'Bidang Keahlian', 'text'],
                                ] as [keyof typeof form, string, string][]).map(([key, label, type]) => (
                                    <div key={key}>
                                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1">{label}</label>
                                        <input type={type} value={form[key]}
                                            onChange={e => setForm(f => ({ ...f, [key]: e.target.value }))}
                                            className="glass-input w-full text-sm" />
                                    </div>
                                ))}
                                <div>
                                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1">Bio</label>
                                    <textarea value={form.bio} onChange={e => setForm(f => ({ ...f, bio: e.target.value }))}
                                        rows={2} className="glass-input w-full text-sm resize-none" placeholder="Cerita singkat tentang dirimu..." />
                                </div>
                                <button onClick={saveBasic}
                                    className="w-full py-2.5 rounded-xl bg-emerald-500 text-white font-bold text-sm hover:bg-emerald-600 transition-colors flex items-center justify-center gap-2">
                                    <Icon name="save" className="text-base" /> Simpan
                                </button>
                            </div>
                        ) : (
                            <div className="space-y-2.5 text-sm">
                                {([
                                    ['phone', 'WhatsApp', user.no_hp],
                                    ['location_on', 'Domisili', alumni.alamat_domisili],
                                    ['psychology', 'Keahlian', alumni.bidang_keahlian],
                                ] as [string, string, string | null | undefined][]).map(([icon, label, val]) => (
                                    <div key={label} className="flex items-start gap-2.5">
                                        <Icon name={icon} className="text-base text-on-surface-variant mt-0.5 flex-shrink-0" />
                                        <div className="flex-1 min-w-0">
                                            <p className="text-[10px] font-bold uppercase tracking-wider text-on-surface-variant">{label}</p>
                                            <p className="text-on-surface truncate">{val || '—'}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>

                {/* ── Right: Riwayat (unified pattern dengan Mahasiswa) ── */}
                <div className="lg:col-span-2 space-y-5">

                    {/* Tab nav */}
                    <div className="glass-card rounded-2xl p-2 flex gap-1 overflow-x-auto">
                        {TIPES.map(tipe => {
                            const meta = TIPE_META[tipe];
                            const count = (riwayats[tipe] ?? []).length;
                            return (
                                <button key={tipe} onClick={() => setActiveTab(tipe)}
                                    className={`flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex-shrink-0 ${
                                        activeTab === tipe
                                            ? `${meta.bg} ${meta.color}`
                                            : 'text-on-surface-variant hover:bg-surface-container'
                                    }`}>
                                    <Icon name={meta.icon} className="text-base" filled={activeTab === tipe} />
                                    {meta.label}
                                    {count > 0 && (
                                        <span className={`px-1.5 py-0.5 rounded-full text-[10px] ${activeTab === tipe ? 'bg-white/60' : 'bg-surface-container'}`}>
                                            {count}
                                        </span>
                                    )}
                                </button>
                            );
                        })}
                    </div>

                    {/* Content */}
                    <div className="space-y-3">
                        {currentItems.length === 0 ? (
                            <div className="glass-card rounded-2xl p-10 text-center text-on-surface-variant">
                                <Icon name={TIPE_META[activeTab].icon} className={`text-5xl ${TIPE_META[activeTab].color} mb-3 opacity-40`} filled />
                                <p className="font-bold mb-1">Belum ada {TIPE_META[activeTab].label}</p>
                                <p className="text-sm">Tambahkan riwayat {TIPE_META[activeTab].label.toLowerCase()} kamu.</p>
                            </div>
                        ) : (
                            currentItems.map(item => (
                                <RiwayatCard key={item.id} item={item}
                                    onEdit={() => openEdit(item)}
                                    onDelete={() => deleteRiwayat(item.id)} />
                            ))
                        )}

                        <button onClick={() => openAdd(activeTab)}
                            className="w-full py-3 rounded-2xl border-2 border-dashed border-outline-variant text-on-surface-variant text-sm font-bold flex items-center justify-center gap-2 hover:border-emerald-500 hover:text-emerald-600 transition-colors">
                            <Icon name="add_circle" className="text-xl" />
                            Tambah {TIPE_META[activeTab].label}
                        </button>
                    </div>

                    {/* Recent posts */}
                    {posts.length > 0 && (
                        <div className="glass-card rounded-2xl overflow-hidden">
                            <div className="px-5 py-3 border-b border-white/30 flex items-center justify-between">
                                <h3 className="font-bold text-on-surface text-sm flex items-center gap-2">
                                    <Icon name="forum" className="text-emerald-600 text-base" />
                                    Postingan Terakhir
                                </h3>
                                <a href="/alumni/hub" className="text-xs font-bold text-emerald-600 hover:underline">Lihat Hub →</a>
                            </div>
                            <div className="divide-y divide-white/20">
                                {posts.map(p => (
                                    <div key={p.id} className="p-4 flex items-center justify-between hover:bg-white/20 transition-colors">
                                        <div className="min-w-0 flex-1">
                                            {p.title && <p className="font-bold text-sm text-on-surface truncate">{p.title}</p>}
                                            <p className="text-xs text-on-surface-variant truncate">{p.content}</p>
                                        </div>
                                        <div className="flex items-center gap-3 text-xs text-on-surface-variant flex-shrink-0 ml-4">
                                            <span>❤ {p.likes_count}</span>
                                            <span>💬 {p.comments_count}</span>
                                            <span>{p.created_at}</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>

            {/* Modal */}
            {modalTipe && <RiwayatModal tipe={modalTipe} editItem={editItem} onClose={closeModal} />}
        </AppLayout>
    );
}
