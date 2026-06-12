import { Head, useForm, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';

interface ProgramData {
    id: number;
    asrama: string;
    nama_program: string;
    deskripsi: string | null;
    tahun: number;
    semester: number;
    status: 'rencana' | 'berjalan' | 'selesai' | 'dibatalkan';
    tanggal_mulai: string | null;
    tanggal_selesai: string | null;
    penanggung_jawab: string | null;
}

interface Props {
    programs: {
        data: ProgramData[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    asrama: string;
    stats: { total: number; rencana: number; berjalan: number; selesai: number };
    tahunList: number[];
    filters: Record<string, string>;
}

const STATUS_META: Record<string, { label: string; icon: string; bg: string; text: string }> = {
    rencana:    { label: 'Rencana',    icon: 'pending',       bg: 'bg-yellow-100', text: 'text-yellow-700' },
    berjalan:   { label: 'Berjalan',   icon: 'play_circle',   bg: 'bg-blue-100',   text: 'text-blue-700' },
    selesai:    { label: 'Selesai',    icon: 'check_circle',  bg: 'bg-emerald-100',text: 'text-emerald-700' },
    dibatalkan: { label: 'Dibatalkan', icon: 'cancel',        bg: 'bg-red-100',    text: 'text-red-700' },
};

const currentYear = new Date().getFullYear();
const currentSemester = new Date().getMonth() < 7 ? 1 : 2;

const emptyForm = {
    nama_program:     '',
    deskripsi:        '',
    tahun:            String(currentYear),
    semester:         String(currentSemester),
    status:           'rencana',
    tanggal_mulai:    '',
    tanggal_selesai:  '',
    penanggung_jawab: '',
};

export default function ProgramKerja({ programs, asrama, stats, tahunList, filters }: Props) {
    const [editing, setEditing]             = useState<ProgramData | null>(null);
    const [confirmDelete, setConfirmDelete] = useState<ProgramData | null>(null);
    const [modalOpen, setModalOpen]         = useState(false);

    const [search, setSearch]         = useState(filters.search ?? '');
    const [tahunFilter, setTahunFilter] = useState(filters.tahun ?? '');
    const [semesterFilter, setSemesterFilter] = useState(filters.semester ?? '');
    const [statusFilter, setStatusFilter]     = useState(filters.status ?? '');

    const { data, setData, post, put, processing, errors, reset } = useForm(emptyForm);

    function openCreate() {
        reset();
        setEditing(null);
        setModalOpen(true);
    }

    function openEdit(p: ProgramData) {
        setData({
            nama_program:     p.nama_program,
            deskripsi:        p.deskripsi ?? '',
            tahun:            String(p.tahun),
            semester:         String(p.semester),
            status:           p.status,
            tanggal_mulai:    p.tanggal_mulai ?? '',
            tanggal_selesai:  p.tanggal_selesai ?? '',
            penanggung_jawab: p.penanggung_jawab ?? '',
        });
        setEditing(p);
        setModalOpen(true);
    }

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        if (editing) {
            put(`/pengurus-asrama/program-kerja/${editing.id}`, {
                onSuccess: () => { setModalOpen(false); reset(); setEditing(null); },
            });
        } else {
            post('/pengurus-asrama/program-kerja', {
                onSuccess: () => { setModalOpen(false); reset(); },
            });
        }
    }

    function handleDelete() {
        if (!confirmDelete) return;
        router.delete(`/pengurus-asrama/program-kerja/${confirmDelete.id}`, {
            onSuccess: () => setConfirmDelete(null),
        });
    }

    function applyFilter(
        newSearch = search,
        newTahun = tahunFilter,
        newSemester = semesterFilter,
        newStatus = statusFilter,
    ) {
        router.get('/pengurus-asrama/program-kerja', {
            search: newSearch,
            tahun: newTahun,
            semester: newSemester,
            status: newStatus,
            per_page: programs.per_page,
        }, { preserveState: true, replace: true });
    }

    function handlePageChange(page: number) {
        router.get('/pengurus-asrama/program-kerja', {
            search, tahun: tahunFilter, semester: semesterFilter, status: statusFilter,
            per_page: programs.per_page, page,
        }, { preserveState: true });
    }

    return (
        <AppLayout>
            <Head title={`Program Kerja Asrama ${asrama}`} />
            <PageHeader
                title={`Program Kerja Asrama ${asrama}`}
                subtitle="Rencanakan dan pantau program kerja kepengurusan asrama"
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Program Kerja' },
                ]}
                actions={
                    <button
                        onClick={openCreate}
                        className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold"
                    >
                        <Icon name="add" className="text-lg" />
                        Tambah Program
                    </button>
                }
            />

            {/* Stats */}
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                {[
                    { label: 'Total',    value: stats.total,    icon: 'assignment',          bg: 'bg-blue-100',    text: 'text-blue-600' },
                    { label: 'Rencana',  value: stats.rencana,  icon: 'pending',             bg: 'bg-yellow-100',  text: 'text-yellow-600' },
                    { label: 'Berjalan', value: stats.berjalan, icon: 'play_circle',          bg: 'bg-emerald-100', text: 'text-emerald-600' },
                    { label: 'Selesai',  value: stats.selesai,  icon: 'assignment_turned_in', bg: 'bg-violet-100',  text: 'text-violet-600' },
                ].map(s => (
                    <div key={s.label} className="glass-card rounded-2xl p-4 flex items-center gap-3">
                        <div className={`w-10 h-10 ${s.bg} rounded-xl flex items-center justify-center flex-shrink-0`}>
                            <Icon name={s.icon} className={`text-xl ${s.text}`} filled />
                        </div>
                        <div>
                            <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">{s.label}</p>
                            <p className="font-display text-xl font-bold text-on-surface">{s.value}</p>
                        </div>
                    </div>
                ))}
            </div>

            {/* Filters */}
            <div className="glass-card rounded-2xl p-4 mb-4 flex flex-wrap gap-3">
                <div className="flex-1 min-w-48 flex items-center gap-2 glass-input px-3 py-2.5 rounded-xl">
                    <Icon name="search" className="text-on-surface-variant text-lg" />
                    <input
                        value={search}
                        onChange={e => setSearch(e.target.value)}
                        onKeyDown={e => e.key === 'Enter' && applyFilter()}
                        placeholder="Cari program..."
                        className="bg-transparent outline-none text-sm flex-1"
                    />
                    {search && <button onClick={() => { setSearch(''); applyFilter(''); }}><Icon name="close" className="text-sm text-outline" /></button>}
                </div>

                <select
                    value={tahunFilter}
                    onChange={e => { setTahunFilter(e.target.value); applyFilter(search, e.target.value); }}
                    className="glass-input text-sm py-2.5 px-3 rounded-xl min-w-[100px]"
                >
                    <option value="">Semua Tahun</option>
                    {tahunList.map(t => <option key={t} value={t}>{t}</option>)}
                </select>

                <select
                    value={semesterFilter}
                    onChange={e => { setSemesterFilter(e.target.value); applyFilter(search, tahunFilter, e.target.value); }}
                    className="glass-input text-sm py-2.5 px-3 rounded-xl"
                >
                    <option value="">Semua Semester</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>

                <select
                    value={statusFilter}
                    onChange={e => { setStatusFilter(e.target.value); applyFilter(search, tahunFilter, semesterFilter, e.target.value); }}
                    className="glass-input text-sm py-2.5 px-3 rounded-xl"
                >
                    <option value="">Semua Status</option>
                    {Object.entries(STATUS_META).map(([v, m]) => (
                        <option key={v} value={v}>{m.label}</option>
                    ))}
                </select>
            </div>

            {/* Cards */}
            {programs.data.length === 0 ? (
                <div className="glass-card rounded-2xl py-16 text-center text-on-surface-variant">
                    <Icon name="assignment" className="text-5xl mb-3 opacity-30" />
                    <p className="text-sm font-medium">Belum ada program kerja</p>
                </div>
            ) : (
                <div className="space-y-3">
                    {programs.data.map(p => {
                        const meta = STATUS_META[p.status];
                        return (
                            <div key={p.id} className="glass-card rounded-2xl p-5 flex gap-4">
                                <div className="flex-1 min-w-0">
                                    <div className="flex items-start gap-3 mb-2">
                                        <div>
                                            <p className="font-bold text-on-surface">{p.nama_program}</p>
                                            <div className="flex flex-wrap items-center gap-2 mt-1">
                                                <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold ${meta.bg} ${meta.text}`}>
                                                    <Icon name={meta.icon} className="text-xs" />
                                                    {meta.label}
                                                </span>
                                                <span className="text-xs text-on-surface-variant">
                                                    Semester {p.semester} · {p.tahun}
                                                </span>
                                                {p.penanggung_jawab && (
                                                    <span className="text-xs text-on-surface-variant">
                                                        · PJ: {p.penanggung_jawab}
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                    {p.deskripsi && (
                                        <p className="text-sm text-on-surface-variant line-clamp-2">{p.deskripsi}</p>
                                    )}
                                    {(p.tanggal_mulai || p.tanggal_selesai) && (
                                        <p className="text-xs text-on-surface-variant mt-2 flex items-center gap-1">
                                            <Icon name="date_range" className="text-xs" />
                                            {p.tanggal_mulai
                                                ? new Date(p.tanggal_mulai).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
                                                : '?'}
                                            {' — '}
                                            {p.tanggal_selesai
                                                ? new Date(p.tanggal_selesai).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
                                                : 'Sekarang'}
                                        </p>
                                    )}
                                </div>
                                <div className="flex items-center gap-1 flex-shrink-0">
                                    <button
                                        onClick={() => openEdit(p)}
                                        className="p-2 text-primary-container hover:bg-primary/10 rounded-xl transition-colors"
                                        title="Edit"
                                    >
                                        <Icon name="edit" className="text-lg" />
                                    </button>
                                    <button
                                        onClick={() => setConfirmDelete(p)}
                                        className="p-2 text-error hover:bg-error/10 rounded-xl transition-colors"
                                        title="Hapus"
                                    >
                                        <Icon name="delete" className="text-lg" />
                                    </button>
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}

            <Pagination
                currentPage={programs.current_page}
                totalPages={programs.last_page}
                totalItems={programs.total}
                perPage={programs.per_page}
                onPageChange={handlePageChange}
                onPerPageChange={newPerPage => {
                    router.get('/pengurus-asrama/program-kerja', {
                        search, tahun: tahunFilter, semester: semesterFilter, status: statusFilter,
                        per_page: newPerPage, page: 1,
                    }, { preserveState: true });
                }}
            />

            {/* Create/Edit Modal */}
            <Modal
                open={modalOpen}
                onClose={() => { setModalOpen(false); reset(); setEditing(null); }}
                title={editing ? 'Edit Program Kerja' : 'Tambah Program Kerja'}
                icon="assignment"
            >
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-xs font-bold text-on-surface-variant mb-1">Nama Program *</label>
                        <input
                            value={data.nama_program}
                            onChange={e => setData('nama_program', e.target.value)}
                            className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            placeholder="Nama program kerja..."
                        />
                        {errors.nama_program && <p className="text-xs text-error mt-1">{errors.nama_program}</p>}
                    </div>

                    <div>
                        <label className="block text-xs font-bold text-on-surface-variant mb-1">Deskripsi</label>
                        <textarea
                            value={data.deskripsi}
                            onChange={e => setData('deskripsi', e.target.value)}
                            rows={3}
                            className="glass-input w-full px-3 py-2.5 rounded-xl text-sm resize-none"
                            placeholder="Deskripsi program..."
                        />
                    </div>

                    <div className="grid grid-cols-3 gap-3">
                        <div>
                            <label className="block text-xs font-bold text-on-surface-variant mb-1">Tahun *</label>
                            <input
                                type="number"
                                value={data.tahun}
                                onChange={e => setData('tahun', e.target.value)}
                                min={2000} max={2100}
                                className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-bold text-on-surface-variant mb-1">Semester *</label>
                            <select
                                value={data.semester}
                                onChange={e => setData('semester', e.target.value)}
                                className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            >
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>
                        <div>
                            <label className="block text-xs font-bold text-on-surface-variant mb-1">Status *</label>
                            <select
                                value={data.status}
                                onChange={e => setData('status', e.target.value)}
                                className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            >
                                {Object.entries(STATUS_META).map(([v, m]) => (
                                    <option key={v} value={v}>{m.label}</option>
                                ))}
                            </select>
                        </div>
                    </div>

                    <div className="grid grid-cols-2 gap-3">
                        <div>
                            <label className="block text-xs font-bold text-on-surface-variant mb-1">Tanggal Mulai</label>
                            <input
                                type="date"
                                value={data.tanggal_mulai}
                                onChange={e => setData('tanggal_mulai', e.target.value)}
                                className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-bold text-on-surface-variant mb-1">Tanggal Selesai</label>
                            <input
                                type="date"
                                value={data.tanggal_selesai}
                                onChange={e => setData('tanggal_selesai', e.target.value)}
                                className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            />
                            {errors.tanggal_selesai && <p className="text-xs text-error mt-1">{errors.tanggal_selesai}</p>}
                        </div>
                    </div>

                    <div>
                        <label className="block text-xs font-bold text-on-surface-variant mb-1">Penanggung Jawab</label>
                        <input
                            value={data.penanggung_jawab}
                            onChange={e => setData('penanggung_jawab', e.target.value)}
                            className="glass-input w-full px-3 py-2.5 rounded-xl text-sm"
                            placeholder="Nama penanggung jawab..."
                        />
                    </div>

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
                    title="Hapus Program Kerja"
                    icon="delete"
                >
                    <p className="text-sm text-on-surface-variant mb-4">
                        Hapus program <span className="font-bold text-on-surface">"{confirmDelete.nama_program}"</span>? Tindakan ini tidak bisa dibatalkan.
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
