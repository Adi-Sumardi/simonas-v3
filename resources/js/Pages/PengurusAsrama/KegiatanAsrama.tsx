import { Head, useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';

interface KegiatanData {
    id: number;
    nama_kegiatan: string;
    tujuan: string;
    waktu: string;
    tempat: string;
    jenis_kegiatan: string;
    penyelenggara: string;
    keterangan: string;
    asrama: string;
    wajib_absen: boolean;
}

interface Props {
    kegiatan: {
        data: KegiatanData[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    asrama: string;
    stats: { total: number; upcoming: number; selesai: number };
    filters: Record<string, string>;
}

const JENIS_META: Record<string, { icon: string; bg: string; text: string; label: string }> = {
    akademik:  { icon: 'school',          bg: 'bg-blue-100',    text: 'text-blue-600',    label: 'Akademik' },
    hafalan:   { icon: 'auto_stories',    bg: 'bg-emerald-100', text: 'text-emerald-600', label: 'Hafalan' },
    ibadah:    { icon: 'mosque',          bg: 'bg-indigo-100',  text: 'text-indigo-600',  label: 'Ibadah / Sholat Berjamaah' },
    kegiatan:  { icon: 'event',           bg: 'bg-purple-100',  text: 'text-purple-600',  label: 'Kegiatan' },
    olahraga:  { icon: 'fitness_center',  bg: 'bg-teal-100',    text: 'text-teal-600',    label: 'Olahraga' },
    sosial:    { icon: 'handshake',       bg: 'bg-rose-100',    text: 'text-rose-600',    label: 'Sosial' },
    lainnya:   { icon: 'category',        bg: 'bg-gray-100',    text: 'text-gray-600',    label: 'Lainnya' },
};

const emptyForm = {
    nama_kegiatan:  '',
    tujuan:         '',
    jenis_kegiatan: 'kegiatan',
    wajib_absen:    false,
    waktu:          '',
    tempat:         '',
    keterangan:     '',
};

export default function KegiatanAsrama({ kegiatan, asrama, stats, filters }: Props) {
    const [editing, setEditing]           = useState<KegiatanData | null>(null);
    const [confirmDelete, setConfirmDelete] = useState<KegiatanData | null>(null);
    const [modalOpen, setModalOpen]       = useState(false);
    const [search, setSearch]             = useState(filters.search ?? '');
    const [statusFilter, setStatusFilter] = useState(filters.status ?? 'all');

    const { data, setData, post, put, processing, errors, reset } = useForm(emptyForm);

    function openCreate() {
        reset();
        setEditing(null);
        setModalOpen(true);
    }

    function openEdit(k: KegiatanData) {
        setData({
            nama_kegiatan:  k.nama_kegiatan,
            tujuan:         k.tujuan,
            jenis_kegiatan: k.jenis_kegiatan,
            wajib_absen:    k.wajib_absen,
            waktu:          k.waktu?.slice(0, 16) ?? '',
            tempat:         k.tempat,
            keterangan:     k.keterangan ?? '',
        });
        setEditing(k);
        setModalOpen(true);
    }

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        if (editing) {
            put(`/pengurus-asrama/kegiatan/${editing.id}`, {
                onSuccess: () => { setModalOpen(false); reset(); setEditing(null); },
            });
        } else {
            post('/pengurus-asrama/kegiatan', {
                onSuccess: () => { setModalOpen(false); reset(); },
            });
        }
    }

    function handleDelete() {
        if (!confirmDelete) return;
        router.delete(`/pengurus-asrama/kegiatan/${confirmDelete.id}`, {
            onSuccess: () => setConfirmDelete(null),
        });
    }

    function applyFilter(newSearch = search, newStatus = statusFilter) {
        router.get('/pengurus-asrama/kegiatan', {
            search: newSearch,
            status: newStatus,
            per_page: kegiatan.per_page,
        }, { preserveState: true, replace: true });
    }

    function handlePageChange(page: number) {
        router.get('/pengurus-asrama/kegiatan', {
            search,
            status: statusFilter,
            per_page: kegiatan.per_page,
            page,
        }, { preserveState: true });
    }

    function isUpcoming(waktu: string) {
        return new Date(waktu) >= new Date();
    }

    return (
        <AppLayout>
            <Head title={`Kegiatan Asrama ${asrama}`} />
            <PageHeader
                title={`Kegiatan Asrama ${asrama}`}
                subtitle="Kelola kegiatan dan event di asrama Anda"
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Kegiatan Asrama' },
                ]}
                actions={
                    <button
                        onClick={openCreate}
                        className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold"
                    >
                        <Icon name="add" className="text-lg" />
                        Tambah Kegiatan
                    </button>
                }
            />

            {/* Stats */}
            <div className="grid grid-cols-3 gap-4 mb-6">
                {[
                    { label: 'Total',    value: stats.total,    icon: 'event',          bg: 'bg-blue-100',    text: 'text-blue-600' },
                    { label: 'Upcoming', value: stats.upcoming, icon: 'event_upcoming',  bg: 'bg-emerald-100', text: 'text-emerald-600' },
                    { label: 'Selesai',  value: stats.selesai,  icon: 'event_available', bg: 'bg-violet-100',  text: 'text-violet-600' },
                ].map(s => (
                    <div key={s.label} className="glass-card rounded-2xl p-5 flex items-center gap-4">
                        <div className={`w-12 h-12 ${s.bg} rounded-2xl flex items-center justify-center flex-shrink-0`}>
                            <Icon name={s.icon} className={`text-2xl ${s.text}`} filled />
                        </div>
                        <div>
                            <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">{s.label}</p>
                            <p className="font-display text-2xl font-bold text-on-surface">{s.value}</p>
                        </div>
                    </div>
                ))}
            </div>

            {/* Filters */}
            <div className="glass-card rounded-2xl p-4 mb-4 flex flex-col sm:flex-row gap-3">
                <div className="flex-1 flex items-center gap-2 glass-input px-3 py-2.5 rounded-xl">
                    <Icon name="search" className="text-on-surface-variant text-lg" />
                    <input
                        value={search}
                        onChange={e => setSearch(e.target.value)}
                        onKeyDown={e => e.key === 'Enter' && applyFilter()}
                        placeholder="Cari nama kegiatan..."
                        className="bg-transparent outline-none text-sm flex-1"
                    />
                    {search && (
                        <button onClick={() => { setSearch(''); applyFilter(''); }}>
                            <Icon name="close" className="text-sm text-outline" />
                        </button>
                    )}
                </div>
                <div className="flex gap-2">
                    {['all', 'upcoming', 'selesai'].map(s => (
                        <button
                            key={s}
                            onClick={() => { setStatusFilter(s); applyFilter(search, s); }}
                            className={`px-3 py-2 rounded-xl text-xs font-bold capitalize transition-all ${
                                statusFilter === s
                                    ? 'bg-primary text-on-primary shadow-md'
                                    : 'glass-input text-on-surface-variant hover:bg-white/60'
                            }`}
                        >
                            {s === 'all' ? 'Semua' : s}
                        </button>
                    ))}
                </div>
            </div>

            {/* Table */}
            <div className="glass-card rounded-2xl overflow-hidden">
                {kegiatan.data.length === 0 ? (
                    <div className="py-16 text-center text-on-surface-variant">
                        <Icon name="event_busy" className="text-5xl mb-3 opacity-30" />
                        <p className="text-sm font-medium">Belum ada kegiatan</p>
                    </div>
                ) : (
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-white/20 bg-white/20">
                                <th className="text-left px-5 py-3 font-bold text-on-surface-variant text-xs uppercase tracking-wide">Kegiatan</th>
                                <th className="text-left px-5 py-3 font-bold text-on-surface-variant text-xs uppercase tracking-wide hidden sm:table-cell">Waktu & Tempat</th>
                                <th className="text-left px-5 py-3 font-bold text-on-surface-variant text-xs uppercase tracking-wide hidden md:table-cell">Jenis</th>
                                <th className="text-left px-5 py-3 font-bold text-on-surface-variant text-xs uppercase tracking-wide">Status</th>
                                <th className="px-5 py-3 w-24"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {kegiatan.data.map(k => {
                                const meta = JENIS_META[k.jenis_kegiatan] ?? JENIS_META.lainnya;
                                const upcoming = isUpcoming(k.waktu);
                                return (
                                    <tr key={k.id} className="border-b border-white/10 hover:bg-white/20 transition-colors">
                                        <td className="px-5 py-3.5">
                                            <p className="font-semibold text-on-surface">{k.nama_kegiatan}</p>
                                            <p className="text-xs text-on-surface-variant line-clamp-1">{k.tujuan}</p>
                                        </td>
                                        <td className="px-5 py-3.5 hidden sm:table-cell">
                                            <p className="font-medium">{new Date(k.waktu).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })}</p>
                                            <p className="text-xs text-on-surface-variant">{k.tempat}</p>
                                        </td>
                                        <td className="px-5 py-3.5 hidden md:table-cell">
                                            <span className={`inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold ${meta.bg} ${meta.text}`}>
                                                <Icon name={meta.icon} className="text-xs" />
                                                {meta.label}
                                            </span>
                                        </td>
                                        <td className="px-5 py-3.5">
                                            <span className={`inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold ${
                                                upcoming ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'
                                            }`}>
                                                <Icon name={upcoming ? 'schedule' : 'check_circle'} className="text-xs" />
                                                {upcoming ? 'Upcoming' : 'Selesai'}
                                            </span>
                                        </td>
                                        <td className="px-5 py-3.5">
                                            <div className="flex items-center gap-1 justify-end">
                                                {k.wajib_absen && (
                                                    <a
                                                        href={`/pengurus-asrama/kegiatan/${k.id}/attendance`}
                                                        className="p-1.5 text-emerald-600 hover:bg-emerald-100 rounded-lg transition-colors"
                                                        title="Absensi"
                                                    >
                                                        <Icon name="how_to_reg" className="text-lg" />
                                                    </a>
                                                )}
                                                <button
                                                    onClick={() => openEdit(k)}
                                                    className="p-1.5 text-primary-container hover:bg-primary/10 rounded-lg transition-colors"
                                                    title="Edit"
                                                >
                                                    <Icon name="edit" className="text-lg" />
                                                </button>
                                                <button
                                                    onClick={() => setConfirmDelete(k)}
                                                    className="p-1.5 text-error hover:bg-error/10 rounded-lg transition-colors"
                                                    title="Hapus"
                                                >
                                                    <Icon name="delete" className="text-lg" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                )}
            </div>

            <Pagination
                currentPage={kegiatan.current_page}
                totalPages={kegiatan.last_page}
                totalItems={kegiatan.total}
                perPage={kegiatan.per_page}
                onPageChange={handlePageChange}
                onPerPageChange={newPerPage => {
                    router.get('/pengurus-asrama/kegiatan', {
                        search, status: statusFilter, per_page: newPerPage, page: 1,
                    }, { preserveState: true });
                }}
            />

            {/* Create/Edit Modal */}
            <Modal
                open={modalOpen}
                onClose={() => { setModalOpen(false); reset(); setEditing(null); }}
                title={editing ? 'Edit Kegiatan' : 'Tambah Kegiatan'}
                icon="event"
            >
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-xs font-bold text-on-surface-variant mb-1">Nama Kegiatan *</label>
                        <input
                            value={data.nama_kegiatan}
                            onChange={e => setData('nama_kegiatan', e.target.value)}
                            className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            placeholder="Nama kegiatan..."
                        />
                        {errors.nama_kegiatan && <p className="text-xs text-error mt-1">{errors.nama_kegiatan}</p>}
                    </div>

                    <div>
                        <label className="block text-xs font-bold text-on-surface-variant mb-1">Tujuan *</label>
                        <textarea
                            value={data.tujuan}
                            onChange={e => setData('tujuan', e.target.value)}
                            rows={2}
                            className="glass-input w-full px-3 py-2.5 rounded-xl text-sm resize-none"
                            placeholder="Tujuan kegiatan..."
                        />
                        {errors.tujuan && <p className="text-xs text-error mt-1">{errors.tujuan}</p>}
                    </div>

                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="block text-xs font-bold text-on-surface-variant mb-1">Jenis *</label>
                            <select
                                value={data.jenis_kegiatan}
                                onChange={e => setData('jenis_kegiatan', e.target.value)}
                                className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            >
                                {Object.entries(JENIS_META).map(([v, m]) => (
                                    <option key={v} value={v}>{m.label}</option>
                                ))}
                            </select>
                        </div>
                        <div>
                            <label className="block text-xs font-bold text-on-surface-variant mb-1">Waktu *</label>
                            <input
                                type="datetime-local"
                                value={data.waktu}
                                onChange={e => setData('waktu', e.target.value)}
                                className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            />
                            {errors.waktu && <p className="text-xs text-error mt-1">{errors.waktu}</p>}
                        </div>
                    </div>

                    <div>
                        <label className="block text-xs font-bold text-on-surface-variant mb-1">Tempat *</label>
                        <input
                            value={data.tempat}
                            onChange={e => setData('tempat', e.target.value)}
                            className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            placeholder="Lokasi kegiatan..."
                        />
                        {errors.tempat && <p className="text-xs text-error mt-1">{errors.tempat}</p>}
                    </div>

                    <div>
                        <label className="block text-xs font-bold text-on-surface-variant mb-1">Keterangan</label>
                        <textarea
                            value={data.keterangan}
                            onChange={e => setData('keterangan', e.target.value)}
                            rows={2}
                            className="glass-input w-full px-3 py-2.5 rounded-xl text-sm resize-none"
                            placeholder="Keterangan tambahan..."
                        />
                    </div>

                    <label className="flex items-center gap-2 bg-surface-container/50 rounded-xl p-3 cursor-pointer">
                        <input type="checkbox" checked={data.wajib_absen} onChange={e => setData('wajib_absen', e.target.checked)} className="w-4 h-4 accent-primary-container" />
                        <span className="text-xs font-bold text-on-surface">Wajib Absen — mahasiswa harus check-in kehadiran (selfie + foto lokasi)</span>
                    </label>

                    <div className="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            onClick={() => { setModalOpen(false); reset(); setEditing(null); }}
                            className="px-4 py-2 rounded-xl text-sm font-medium glass-input hover:bg-white/60"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            disabled={processing}
                            className="px-5 py-2 rounded-xl text-sm font-bold bg-primary text-on-primary hover:opacity-90 disabled:opacity-50"
                        >
                            {processing ? 'Menyimpan...' : (editing ? 'Perbarui' : 'Simpan')}
                        </button>
                    </div>
                </form>
            </Modal>

            {/* Delete Confirm */}
            {confirmDelete && (
                <Modal
                    open={!!confirmDelete}
                    onClose={() => setConfirmDelete(null)}
                    title="Hapus Kegiatan"
                    icon="delete"
                >
                    <p className="text-sm text-on-surface-variant mb-4">
                        Hapus kegiatan <span className="font-bold text-on-surface">"{confirmDelete.nama_kegiatan}"</span>? Tindakan ini tidak bisa dibatalkan.
                    </p>
                    <div className="flex justify-end gap-3">
                        <button onClick={() => setConfirmDelete(null)} className="px-4 py-2 rounded-xl text-sm font-medium glass-input hover:bg-white/60">Batal</button>
                        <button onClick={handleDelete} className="px-5 py-2 rounded-xl text-sm font-bold bg-error text-white hover:opacity-90">Hapus</button>
                    </div>
                </Modal>
            )}
        </AppLayout>
    );
}
