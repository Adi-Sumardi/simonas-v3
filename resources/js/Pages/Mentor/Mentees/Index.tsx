import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Modal } from '@/Components/ui/Modal';
import { Icon } from '@/Components/ui/Icon';
import { EmptyState } from '@/Components/ui/EmptyState';
import { PageProps, Mentee, PaginatedData } from '@/types';

interface MenteesIndexProps extends PageProps {
    mentees: PaginatedData<Mentee>;
    available_students: { id: number; name: string; nim: string; asrama: string; prodi?: string }[];
}

export default function MenteesIndex({ mentees, available_students = [] }: MenteesIndexProps) {
    const [search, setSearch] = useState('');
    const [addModalOpen, setAddModalOpen] = useState(false);
    const [selectedStudent, setSelectedStudent] = useState<number | null>(null);
    const [processing, setProcessing] = useState(false);
    const [modalSearch, setModalSearch] = useState('');
    const [modalAsrama, setModalAsrama] = useState('');

    const dormitories = Array.from(new Set(available_students.map(s => s.asrama).filter(Boolean)));

    const filteredStudents = available_students.filter(s => {
        const matchesSearch = s.name.toLowerCase().includes(modalSearch.toLowerCase()) || 
                             s.nim?.toLowerCase().includes(modalSearch.toLowerCase());
        const matchesAsrama = !modalAsrama || s.asrama === modalAsrama;
        return matchesSearch && matchesAsrama;
    });

    function handleSearch(e: React.FormEvent) {
        e.preventDefault();
        router.get('/mentor/mentees', { search }, { preserveState: true, replace: true });
    }

    function handleAddMentee() {
        if (!selectedStudent) return;
        setProcessing(true);
        router.post('/mentor/mentees', { student_id: selectedStudent }, {
            onSuccess: () => {
                setAddModalOpen(false);
                setSelectedStudent(null);
            },
            onFinish: () => setProcessing(false)
        });
    }

    function handleRemoveMentee(id: number, name: string) {
        if (!confirm(`Hapus ${name} dari bimbingan Anda?`)) return;
        router.delete(`/mentor/mentees/${id}`);
    }

    return (
        <AppLayout searchPlaceholder="Cari warga bimbingan...">
            <Head title="Warga Bimbingan" />

            <PageHeader
                title="Warga Bimbingan"
                subtitle="Daftar santri yang berada dalam bimbinganmu."
                breadcrumbs={[
                    { label: 'Beranda', href: '/dashboard' },
                    { label: 'Warga Bimbingan' },
                ]}
                actions={
                    <button
                        onClick={() => setAddModalOpen(true)}
                        className="btn-primary flex items-center gap-2"
                    >
                        <Icon name="person_add" className="text-lg" />
                        Tambah Mentee
                    </button>
                }
            />

            {/* Search bar */}
            <form onSubmit={handleSearch} className="mb-4">
                <div className="relative w-full max-w-xs">
                    <Icon name="search" className="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg" />
                    <input
                        type="text"
                        value={search}
                        onChange={e => setSearch(e.target.value)}
                        placeholder="Nama atau NIM..."
                        className="glass-input pl-9 pr-4 py-2 text-sm w-full"
                    />
                </div>
            </form>

            <div className="glass-panel rounded-2xl overflow-hidden">
                {mentees.data.length === 0 ? (
                    <EmptyState
                        icon="people"
                        title="Belum ada warga bimbingan"
                        description="Santri akan muncul di sini setelah ditugaskan ke kamu."
                        className="py-16"
                    />
                ) : (
                    <>
                        {/* Table header */}
                        <div className="hidden md:grid grid-cols-6 px-6 py-4 border-b border-white/40 bg-blue-50/20 gap-4">
                            {['Santri', 'NIM', 'Asrama', 'Prodi', 'Skor', ''].map((h) => (
                                <div key={h} className="text-label-caps text-on-surface-variant">{h}</div>
                            ))}
                        </div>

                        <div className="divide-y divide-white/20">
                            {mentees.data.map((mentee) => (
                                <div
                                    key={mentee.id}
                                    className="flex flex-col md:grid md:grid-cols-6 gap-4 md:gap-0 items-start md:items-center p-5 hover:bg-white/40 transition-colors group"
                                >
                                    {/* Santri */}
                                    <div className="flex items-center gap-3">
                                        {mentee.avatar ? (
                                            <img src={mentee.avatar} alt={mentee.name} className="w-11 h-11 rounded-full object-cover border-2 border-white shadow-sm" />
                                        ) : (
                                            <div className="w-11 h-11 rounded-full bg-primary-fixed flex items-center justify-center font-bold text-primary-container text-sm shadow-sm">
                                                {mentee.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                            </div>
                                        )}
                                        <div>
                                            <span className="font-body font-semibold text-on-surface text-sm block">{mentee.name}</span>
                                            {mentee.status === 'review_needed' && (
                                                <span className="inline-flex items-center gap-1 text-[9px] font-black text-amber-600 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded-full mt-0.5">
                                                    <Icon name="pending_actions" className="text-xs" />
                                                    Review Needed
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    <span className="text-body-sm text-on-surface-variant font-mono">{mentee.nim}</span>
                                    <span className="text-body-sm text-secondary">{mentee.asrama}</span>
                                    <span className="text-body-sm text-secondary truncate">{mentee.kelas}</span>

                                    {/* Score */}
                                    <div className="flex items-center gap-2">
                                        <span className={`font-black text-base ${
                                            mentee.score >= 85 ? 'text-emerald-600' :
                                            mentee.score >= 70 ? 'text-primary-container' :
                                            mentee.score > 0   ? 'text-amber-600' :
                                            'text-outline-variant'
                                        }`}>
                                            {mentee.score > 0 ? mentee.score : '—'}
                                        </span>
                                        {mentee.score > 0 && <span className="text-[10px] text-on-surface-variant">/100</span>}
                                    </div>

                                    <div className="flex items-center gap-3">
                                        <Link
                                            href={`/mentor/mentees/${mentee.id}`}
                                            className="flex items-center gap-1.5 text-primary-container text-sm font-semibold hover:underline group-hover:gap-2 transition-all"
                                        >
                                            Detail
                                            <Icon name="chevron_right" className="text-base" />
                                        </Link>
                                        <button
                                            onClick={() => handleRemoveMentee(mentee.id, mentee.name)}
                                            className="p-1.5 text-on-surface-variant hover:text-error transition-colors rounded-lg hover:bg-error/10"
                                            title="Hapus dari bimbingan"
                                        >
                                            <Icon name="delete" className="text-lg" />
                                        </button>
                                    </div>
                                </div>
                            ))}
                        </div>

                        {/* Pagination */}
                        {mentees.last_page > 1 && (
                            <div className="p-5 border-t border-white/40 flex justify-between items-center">
                                <p className="text-body-sm text-on-surface-variant">
                                    {mentees.from}–{mentees.to} dari {mentees.total}
                                </p>
                                <div className="flex gap-2">
                                    {mentees.links.map((link, i) => (
                                        <Link
                                            key={i}
                                            href={link.url ?? '#'}
                                            preserveScroll
                                            className={`px-3 py-1.5 rounded-lg text-body-sm font-medium transition-colors ${
                                                link.active
                                                    ? 'bg-primary-container text-on-primary'
                                                    : link.url
                                                        ? 'text-on-surface hover:bg-white/50'
                                                        : 'text-outline-variant cursor-default'
                                            }`}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                        />
                                    ))}
                                </div>
                            </div>
                        )}
                    </>
                )}
            </div>

            <Modal
                open={addModalOpen}
                onClose={() => setAddModalOpen(false)}
                title="Tambah Warga Bimbingan"
                icon="person_add"
                footer={
                    <>
                        <button onClick={() => setAddModalOpen(false)} className="px-4 py-2 text-sm font-medium text-secondary hover:bg-black/5 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button
                            onClick={handleAddMentee}
                            disabled={!selectedStudent || processing}
                            className="btn-primary flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {processing ? (
                                <Icon name="sync" className="animate-spin text-lg" />
                            ) : (
                                <Icon name="check" className="text-lg" />
                            )}
                            Tambahkan
                        </button>
                    </>
                }
            >
                <div className="space-y-4">
                    <p className="text-sm text-on-surface-variant">
                        Pilih mahasiswa yang belum memiliki mentor untuk ditambahkan ke bimbingan Anda.
                    </p>

                    {/* Filters */}
                    <div className="flex flex-col sm:flex-row gap-3">
                        <div className="relative flex-1">
                            <Icon name="search" className="absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base" />
                            <input
                                type="text"
                                value={modalSearch}
                                onChange={e => setModalSearch(e.target.value)}
                                placeholder="Cari nama atau NIM..."
                                className="glass-input pl-9 pr-4 py-2 text-xs w-full"
                            />
                        </div>
                        <select
                            value={modalAsrama}
                            onChange={e => setModalAsrama(e.target.value)}
                            className="glass-input py-2 px-3 text-xs min-w-[140px]"
                        >
                            <option value="">Semua Asrama</option>
                            {dormitories.map(d => (
                                <option key={d} value={d}>{d}</option>
                            ))}
                        </select>
                    </div>

                    <div className="space-y-2 max-h-[350px] overflow-y-auto pr-1 custom-scrollbar">
                        {filteredStudents.length === 0 ? (
                            <div className="py-12 text-center text-on-surface-variant/60 border-2 border-dashed border-surface-container rounded-xl">
                                <Icon name="search_off" className="text-4xl mb-2" />
                                <p className="text-xs font-medium">Mahasiswa tidak ditemukan.</p>
                            </div>
                        ) : (
                            filteredStudents.map(s => (
                                <label
                                    key={s.id}
                                    className={`flex items-center gap-3 p-4 rounded-xl border-2 transition-all cursor-pointer ${
                                        selectedStudent === s.id
                                            ? 'border-primary-container bg-primary-container/5 ring-1 ring-primary-container/20'
                                            : 'border-white/40 bg-white/20 hover:bg-white/50 hover:border-white/60'
                                    }`}
                                >
                                    <input
                                        type="radio"
                                        name="student"
                                        className="sr-only"
                                        checked={selectedStudent === s.id}
                                        onChange={() => setSelectedStudent(s.id)}
                                    />
                                    <div className={`w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all ${
                                        selectedStudent === s.id ? 'border-primary-container' : 'border-outline-variant'
                                    }`}>
                                        {selectedStudent === s.id && <div className="w-2.5 h-2.5 rounded-full bg-primary-container animate-scale-in" />}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="font-bold text-on-surface text-sm truncate">{s.name}</p>
                                        <div className="flex items-center gap-2 mt-0.5">
                                            <span className="text-[10px] text-primary-container font-black uppercase tracking-wider">{s.asrama}</span>
                                            <span className="text-[10px] text-on-surface-variant">•</span>
                                            <span className="text-[10px] text-on-surface-variant font-medium">{s.nim}</span>
                                        </div>
                                    </div>
                                </label>
                            ))
                        )}
                    </div>
                </div>
            </Modal>
        </AppLayout>
    );
}
