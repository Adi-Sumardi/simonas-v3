import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';
import { Modal } from '@/Components/ui/Modal';
import { Icon } from '@/Components/ui/Icon';
import { ConfirmDialog } from '@/Components/ui/ConfirmDialog';

interface Mentee { id: number; name: string }
interface LogItem {
    id: number;
    tanggal: string;
    topik: string;
    tujuan: string | null;
    hasil_diskusi: string | null;
    kendala: string | null;
    solusi: string | null;
    tindak_lanjut: string | null;
    mentee: { id: number; name: string; avatar: string | null };
}
interface Props {
    logs: { data: LogItem[]; current_page: number; last_page: number; total: number; per_page: number };
    mentees: Mentee[];
    filters: { mentee_id?: string };
}

const emptyForm = {
    mentee_id: '',
    tanggal: new Date().toISOString().slice(0, 10),
    topik: '',
    tujuan: '',
    hasil_diskusi: '',
    kendala: '',
    solusi: '',
    tindak_lanjut: '',
};

export default function MentorLogbook({ logs, mentees, filters }: Props) {
    const [menteeFilter, setMenteeFilter] = useState(filters.mentee_id ?? '');
    const [modalOpen, setModalOpen] = useState(false);
    const [editing, setEditing] = useState<LogItem | null>(null);
    const [deleteId, setDeleteId] = useState<number | null>(null);
    const [expanded, setExpanded] = useState<number | null>(null);

    const { data, setData, post, put, processing, errors, reset } = useForm(emptyForm);

    function applyFilter(id: string) {
        setMenteeFilter(id);
        router.get('/mentor/logbook', { mentee_id: id }, { preserveScroll: true, preserveState: true });
    }

    function openAdd() {
        setEditing(null);
        reset();
        setData(emptyForm);
        setModalOpen(true);
    }

    function openEdit(log: LogItem) {
        setEditing(log);
        setData({
            mentee_id: String(log.mentee.id),
            tanggal: log.tanggal,
            topik: log.topik,
            tujuan: log.tujuan ?? '',
            hasil_diskusi: log.hasil_diskusi ?? '',
            kendala: log.kendala ?? '',
            solusi: log.solusi ?? '',
            tindak_lanjut: log.tindak_lanjut ?? '',
        });
        setModalOpen(true);
    }

    function submit(e: React.FormEvent) {
        e.preventDefault();
        if (editing) {
            put(`/mentor/logbook/${editing.id}`, { onSuccess: () => setModalOpen(false), preserveScroll: true });
        } else {
            post('/mentor/logbook', { onSuccess: () => setModalOpen(false), preserveScroll: true });
        }
    }

    function confirmDelete() {
        if (deleteId === null) return;
        router.delete(`/mentor/logbook/${deleteId}`, { preserveScroll: true, onFinish: () => setDeleteId(null) });
    }

    return (
        <AppLayout>
            <Head title="Log Book Mentoring" />
            <PageHeader
                title="Log Book Mentoring"
                subtitle="Catatan sesi mentoring dengan warga bimbingan kamu"
                breadcrumbs={[{ label: 'Dashboard', href: '/mentor' }, { label: 'Log Book' }]}
                actions={
                    <button onClick={openAdd} className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="add" className="text-lg" /> Tambah Log
                    </button>
                }
            />

            <div className="glass-card rounded-2xl p-4 mb-6 flex flex-wrap items-center gap-3">
                <span className="text-xs font-bold text-on-surface-variant">Mentee:</span>
                <select value={menteeFilter} onChange={e => applyFilter(e.target.value)} className="glass-input text-sm py-2 min-w-[180px]">
                    <option value="">Semua Mentee</option>
                    {mentees.map(m => <option key={m.id} value={m.id}>{m.name}</option>)}
                </select>
            </div>

            {logs.data.length === 0 ? (
                <div className="glass-card rounded-2xl py-16 flex flex-col items-center gap-2 text-on-surface-variant">
                    <Icon name="menu_book" className="text-4xl opacity-20" />
                    <p className="text-sm font-semibold">Belum ada log mentoring</p>
                </div>
            ) : (
                <div className="space-y-3 mb-6">
                    {logs.data.map(log => (
                        <div key={log.id} className="glass-card rounded-2xl p-4">
                            <div className="flex items-start justify-between gap-3">
                                <div className="flex items-start gap-3">
                                    <div className="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-xs font-black text-white flex-shrink-0">
                                        {log.mentee.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                    </div>
                                    <div>
                                        <p className="font-bold text-on-surface text-sm">{log.topik}</p>
                                        <p className="text-xs text-on-surface-variant">
                                            {log.mentee.name} &middot; {new Date(log.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-1 flex-shrink-0">
                                    <button onClick={() => setExpanded(expanded === log.id ? null : log.id)} className="w-8 h-8 rounded-lg bg-surface-container text-on-surface-variant hover:bg-white/60 flex items-center justify-center transition-colors">
                                        <Icon name={expanded === log.id ? 'expand_less' : 'expand_more'} className="text-lg" />
                                    </button>
                                    <button onClick={() => openEdit(log)} className="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition-colors">
                                        <Icon name="edit" className="text-sm" />
                                    </button>
                                    <button onClick={() => setDeleteId(log.id)} className="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 flex items-center justify-center transition-colors">
                                        <Icon name="delete" className="text-sm" />
                                    </button>
                                </div>
                            </div>

                            {expanded === log.id && (
                                <div className="mt-4 pt-4 border-t border-white/40 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    <Field label="Tujuan" value={log.tujuan} />
                                    <Field label="Hasil Diskusi" value={log.hasil_diskusi} />
                                    <Field label="Kendala" value={log.kendala} />
                                    <Field label="Solusi" value={log.solusi} />
                                    <Field label="Tindak Lanjut" value={log.tindak_lanjut} className="sm:col-span-2" />
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            )}

            <Pagination
                currentPage={logs.current_page}
                totalPages={logs.last_page}
                totalItems={logs.total}
                perPage={logs.per_page}
                onPageChange={(page) => router.get('/mentor/logbook', { mentee_id: menteeFilter, page, per_page: logs.per_page }, { preserveState: true })}
                onPerPageChange={(perPage) => router.get('/mentor/logbook', { mentee_id: menteeFilter, per_page: perPage }, { preserveState: true })}
            />

            <Modal open={modalOpen} onClose={() => setModalOpen(false)} title={editing ? 'Edit Log Mentoring' : 'Tambah Log Mentoring'} icon="menu_book" size="lg">
                <form onSubmit={submit} className="space-y-4">
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <FormLabel>Mentee *</FormLabel>
                            <select value={data.mentee_id} onChange={e => setData('mentee_id', e.target.value)} disabled={!!editing} className="glass-input w-full text-sm py-2 disabled:opacity-60">
                                <option value="">— Pilih Mentee —</option>
                                {mentees.map(m => <option key={m.id} value={m.id}>{m.name}</option>)}
                            </select>
                            {errors.mentee_id && <p className="text-xs text-rose-500 mt-1">{errors.mentee_id}</p>}
                        </div>
                        <div>
                            <FormLabel>Tanggal *</FormLabel>
                            <input type="date" value={data.tanggal} onChange={e => setData('tanggal', e.target.value)} className="glass-input w-full text-sm" />
                            {errors.tanggal && <p className="text-xs text-rose-500 mt-1">{errors.tanggal}</p>}
                        </div>
                    </div>
                    <div>
                        <FormLabel>Topik *</FormLabel>
                        <input value={data.topik} onChange={e => setData('topik', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. Evaluasi progres hafalan" />
                        {errors.topik && <p className="text-xs text-rose-500 mt-1">{errors.topik}</p>}
                    </div>
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <TextArea label="Tujuan" value={data.tujuan} onChange={v => setData('tujuan', v)} error={errors.tujuan} />
                        <TextArea label="Hasil Diskusi" value={data.hasil_diskusi} onChange={v => setData('hasil_diskusi', v)} error={errors.hasil_diskusi} />
                        <TextArea label="Kendala" value={data.kendala} onChange={v => setData('kendala', v)} error={errors.kendala} />
                        <TextArea label="Solusi" value={data.solusi} onChange={v => setData('solusi', v)} error={errors.solusi} />
                    </div>
                    <TextArea label="Tindak Lanjut" value={data.tindak_lanjut} onChange={v => setData('tindak_lanjut', v)} error={errors.tindak_lanjut} />

                    <div className="flex gap-3 pt-2">
                        <button type="submit" disabled={processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {processing ? 'Menyimpan...' : 'Simpan'}
                        </button>
                        <button type="button" onClick={() => setModalOpen(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">
                            Batal
                        </button>
                    </div>
                </form>
            </Modal>

            <ConfirmDialog
                open={deleteId !== null}
                onClose={() => setDeleteId(null)}
                onConfirm={confirmDelete}
                title="Hapus Log Mentoring?"
                message="Log yang dihapus tidak bisa dikembalikan."
                type="danger"
                confirmText="Hapus"
            />
        </AppLayout>
    );
}

function Field({ label, value, className }: { label: string; value: string | null; className?: string }) {
    return (
        <div className={className}>
            <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">{label}</p>
            <p className="text-on-surface whitespace-pre-wrap">{value || '-'}</p>
        </div>
    );
}

function FormLabel({ children }: { children: React.ReactNode }) {
    return <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">{children}</label>;
}

function TextArea({ label, value, onChange, error }: { label: string; value: string; onChange: (v: string) => void; error?: string }) {
    return (
        <div>
            <FormLabel>{label}</FormLabel>
            <textarea value={value} onChange={e => onChange(e.target.value)} rows={3} className="glass-input w-full text-sm" />
            {error && <p className="text-xs text-rose-500 mt-1">{error}</p>}
        </div>
    );
}
