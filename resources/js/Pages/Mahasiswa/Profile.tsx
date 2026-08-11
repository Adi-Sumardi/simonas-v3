import { Head, router } from '@inertiajs/react';
import { useState } from 'react';
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

interface MentorInfo { id: number; name: string; email: string; avatar?: string; }

interface UserInfo {
    id: number; name: string; email: string; avatar?: string;
    nim?: string; asrama?: string; angkatan?: string; bio?: string; no_hp?: string;
}

interface ProfileProps extends PageProps {
    user: UserInfo;
    mentor: MentorInfo | null;
    riwayats: Record<Tipe, Riwayat[]>;
    asramas: string[];
}

// ─── Constants ────────────────────────────────────────────────────────────────
const TIPE_META: Record<Tipe, { label: string; icon: string; color: string; bg: string }> = {
    pendidikan:  { label: 'Pendidikan',   icon: 'school',            color: 'text-blue-600',    bg: 'bg-blue-100' },
    organisasi:  { label: 'Organisasi',   icon: 'groups',            color: 'text-purple-600',  bg: 'bg-purple-100' },
    pekerjaan:   { label: 'Pekerjaan',    icon: 'work',              color: 'text-emerald-600', bg: 'bg-emerald-100' },
    penghargaan: { label: 'Penghargaan',  icon: 'military_tech',     color: 'text-amber-600',   bg: 'bg-amber-100' },
    sertifikasi: { label: 'Sertifikasi',  icon: 'verified',          color: 'text-rose-600',    bg: 'bg-rose-100' },
};

const TIPES = Object.keys(TIPE_META) as Tipe[];

// ─── Empty entry factory ───────────────────────────────────────────────────────
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
                            onChange={e => onChange(idx, 'masih_berlangsung', e.target.checked)}
                            className="rounded" />
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
            router.put(`/mahasiswa/profil/riwayat/${editItem.id}`, entries[0], {
                preserveState: true, preserveScroll: true,
                onFinish: () => { setSaving(false); onClose(); },
            });
        } else {
            router.post('/mahasiswa/profil/riwayat', { entries }, {
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
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-primary-container text-white disabled:opacity-50 hover:opacity-90 transition-opacity flex items-center gap-2">
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
                        className="w-full py-3 rounded-2xl border-2 border-dashed border-outline-variant text-on-surface-variant text-sm font-bold flex items-center justify-center gap-2 hover:border-primary-container hover:text-primary-container transition-colors">
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
export default function Profile({ user, mentor, riwayats, asramas }: ProfileProps) {
    const [activeTab, setActiveTab] = useState<Tipe>('pendidikan');
    const [modalTipe, setModalTipe] = useState<Tipe | null>(null);
    const [editItem, setEditItem] = useState<Riwayat | null>(null);
    const [editBio, setEditBio] = useState(false);
    const [bioVal, setBioVal] = useState(user.bio ?? '');
    const [asramaVal, setAsramaVal] = useState(user.asrama ?? '');
    const [updatingAvatar, setUpdatingAvatar] = useState(false);

    const [currentPassword, setCurrentPassword] = useState('');
    const [newPassword, setNewPassword] = useState('');
    const [newPasswordConfirmation, setNewPasswordConfirmation] = useState('');
    const [savingPassword, setSavingPassword] = useState(false);
    const [passwordError, setPasswordError] = useState<string | null>(null);

    function submitPassword(e: React.FormEvent) {
        e.preventDefault();
        setSavingPassword(true);
        setPasswordError(null);
        router.post('/mahasiswa/profil/password', {
            current_password: currentPassword,
            password: newPassword,
            password_confirmation: newPasswordConfirmation,
        }, {
            preserveState: true, preserveScroll: true,
            onSuccess: () => {
                setCurrentPassword(''); setNewPassword(''); setNewPasswordConfirmation('');
            },
            onError: (errors) => {
                setPasswordError(errors.current_password || errors.password || 'Gagal memperbarui password.');
            },
            onFinish: () => setSavingPassword(false),
        });
    }

    function handleAvatarChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (!file) return;

        setUpdatingAvatar(true);
        router.post('/mahasiswa/profil/avatar', { avatar: file }, {
            onFinish: () => setUpdatingAvatar(false),
        });
    }

    function openAdd(tipe: Tipe) { setModalTipe(tipe); setEditItem(null); }
    function openEdit(item: Riwayat) { setModalTipe(item.tipe); setEditItem(item); }
    function closeModal() { setModalTipe(null); setEditItem(null); }

    function deleteRiwayat(id: number) {
        if (!confirm('Hapus riwayat ini?')) return;
        router.delete(`/mahasiswa/profil/riwayat/${id}`, { preserveState: true, preserveScroll: true });
    }

    function saveBio() {
        router.put('/mahasiswa/profil', { name: user.name, bio: bioVal, no_hp: user.no_hp, angkatan: user.angkatan, asrama: asramaVal }, {
            preserveState: true, preserveScroll: true,
            onSuccess: () => setEditBio(false),
        });
    }

    const currentItems: Riwayat[] = riwayats[activeTab] ?? [];

    return (
        <AppLayout searchPlaceholder="Cari profil...">
            <Head title="Profil Saya" />

            <PageHeader
                title="Profil Saya"
                subtitle="Kelola informasi pribadi dan riwayat kamu."
                breadcrumbs={[{ label: 'Beranda', href: '/mahasiswa' }, { label: 'Profil' }]}
                actions={
                    <a href="/mahasiswa/portfolio" className="btn-secondary flex items-center gap-2">
                        <Icon name="print" /> Cetak Portofolio
                    </a>
                }
            />

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {/* ── Left: Profile card + Mentor ── */}
                <div className="space-y-5">

                    {/* Avatar + Info */}
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
                            {user.nim && <p className="text-xs font-bold text-primary-container mt-1">NIM: {user.nim}</p>}
                        </div>

                        {/* Stats pills */}
                        <div className="flex flex-wrap gap-2 justify-center text-xs">
                            {user.asrama && (
                                <span className="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center gap-1">
                                    <Icon name="home" className="text-sm" /> {user.asrama}
                                </span>
                            )}
                            {user.angkatan && (
                                <span className="px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-bold flex items-center gap-1">
                                    <Icon name="calendar_today" className="text-sm" /> {user.angkatan}
                                </span>
                            )}
                        </div>

                        {/* Bio */}
                        <div className="text-left">
                            {editBio ? (
                                <div className="space-y-2">
                                    <div>
                                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Asrama</label>
                                        <select value={asramaVal} onChange={e => setAsramaVal(e.target.value)}
                                            className="glass-input w-full text-sm py-1.5">
                                            <option value="">— Pilih Asrama —</option>
                                            {asramas.map(a => (
                                                <option key={a} value={a}>{a}</option>
                                            ))}
                                        </select>
                                    </div>
                                    <div>
                                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Bio</label>
                                        <textarea value={bioVal} onChange={e => setBioVal(e.target.value)}
                                            rows={3} className="glass-input w-full text-sm resize-none" placeholder="Tulis bio singkat..." />
                                    </div>
                                    <div className="flex gap-2">
                                        <button onClick={() => setEditBio(false)} className="flex-1 py-1.5 rounded-xl text-xs font-bold bg-surface-container text-on-surface-variant">Batal</button>
                                        <button onClick={saveBio} className="flex-1 py-1.5 rounded-xl text-xs font-bold bg-primary-container text-white">Simpan</button>
                                    </div>
                                </div>
                            ) : (
                                <div className="flex items-start justify-between gap-2">
                                    <p className="text-sm text-on-surface-variant leading-relaxed flex-1">
                                        {user.bio || <span className="italic opacity-60">Belum ada bio</span>}
                                    </p>
                                    <button onClick={() => setEditBio(true)} className="flex-shrink-0 p-1 rounded-lg hover:bg-surface-container text-on-surface-variant transition-colors">
                                        <Icon name="edit" className="text-sm" />
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Mentor Card */}
                    <div className="glass-card rounded-3xl p-5">
                        <h3 className="font-bold text-on-surface mb-3 flex items-center gap-2 text-sm">
                            <Icon name="supervisor_account" className="text-purple-600 text-lg" filled />
                            Mentor Saya
                        </h3>
                        {mentor ? (
                            <div className="flex items-center gap-3">
                                <div className="w-11 h-11 rounded-xl overflow-hidden bg-purple-100 flex-shrink-0">
                                    {mentor.avatar
                                        ? <img src={mentor.avatar} alt={mentor.name} className="w-full h-full object-cover" />
                                        : <div className="w-full h-full flex items-center justify-center">
                                            <Icon name="person" className="text-2xl text-purple-600" filled />
                                          </div>
                                    }
                                </div>
                                <div>
                                    <p className="font-bold text-on-surface text-sm">{mentor.name}</p>
                                    <p className="text-xs text-on-surface-variant">{mentor.email}</p>
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-center gap-3 text-on-surface-variant">
                                <div className="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center">
                                    <Icon name="person_off" className="text-xl" />
                                </div>
                                <p className="text-sm">Belum ditugaskan mentor</p>
                            </div>
                        )}
                    </div>

                    {/* Ubah Password */}
                    <div className="glass-card rounded-3xl p-5">
                        <h3 className="font-bold text-on-surface mb-3 flex items-center gap-2 text-sm">
                            <Icon name="lock" className="text-lg" />
                            Ubah Password
                        </h3>
                        <form onSubmit={submitPassword} className="space-y-3">
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Password Saat Ini</label>
                                <input type="password" value={currentPassword} onChange={e => setCurrentPassword(e.target.value)}
                                    className="glass-input w-full text-sm" required />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Password Baru</label>
                                <input type="password" value={newPassword} onChange={e => setNewPassword(e.target.value)}
                                    className="glass-input w-full text-sm" minLength={8} required />
                            </div>
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1 block">Konfirmasi Password Baru</label>
                                <input type="password" value={newPasswordConfirmation} onChange={e => setNewPasswordConfirmation(e.target.value)}
                                    className="glass-input w-full text-sm" minLength={8} required />
                            </div>
                            {passwordError && <p className="text-xs text-rose-600 font-semibold">{passwordError}</p>}
                            <button type="submit" disabled={savingPassword}
                                className="w-full py-2 rounded-xl text-xs font-bold bg-primary-container text-white disabled:opacity-50">
                                {savingPassword ? 'Menyimpan...' : 'Perbarui Password'}
                            </button>
                        </form>
                    </div>
                </div>

                {/* ── Right: Riwayat ── */}
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

                        {/* Add button */}
                        <button onClick={() => openAdd(activeTab)}
                            className={`w-full py-3 rounded-2xl border-2 border-dashed text-sm font-bold flex items-center justify-center gap-2 transition-colors border-outline-variant text-on-surface-variant hover:border-primary-container hover:text-primary-container`}>
                            <Icon name="add_circle" className="text-xl" />
                            Tambah {TIPE_META[activeTab].label}
                        </button>
                    </div>
                </div>
            </div>

            {/* Modal */}
            {modalTipe && (
                <RiwayatModal tipe={modalTipe} editItem={editItem} onClose={closeModal} />
            )}
        </AppLayout>
    );
}
