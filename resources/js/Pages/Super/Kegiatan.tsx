import { Head, useForm, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';

interface KegiatanData {
    id: number;
    nama_kegiatan: string;
    waktu: string;
    tempat: string;
    jenis_kegiatan: string;
    penyelenggara: string;
    keterangan: string;
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
    stats: { upcoming: number; selesai: number; total: number };
    filters: any;
}

const TIPE_META: Record<string, { icon:string; bg:string; text:string }> = {
    akademik:  { icon:'school',        bg:'bg-blue-100',    text:'text-blue-600' },
    hafalan:   { icon:'auto_stories',  bg:'bg-emerald-100', text:'text-emerald-600' },
    kegiatan:  { icon:'event',         bg:'bg-purple-100',  text:'text-purple-600' },
    olahraga:  { icon:'fitness_center',bg:'bg-teal-100',    text:'text-teal-600' },
};

export default function Kegiatan({ kegiatan, stats, filters }: Props) {
    const [statusFilter, setStatusFilter] = useState(filters.status || 'all');
    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
    const [selectedKegiatan, setSelectedKegiatan] = useState<KegiatanData | null>(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        nama_kegiatan: '',
        tujuan: '',
        penyelenggara: 'Asrama SIMONAS',
        jenis_kegiatan: 'kegiatan',
        wajib_absen: false,
        waktu: '',
        tempat: '',
        keterangan: '',
    });

    useEffect(() => {
        router.get('/super/kegiatan', {
            status: statusFilter,
            per_page: kegiatan.per_page
        }, { preserveState: true, replace: true });
    }, [statusFilter]);

    const handlePageChange = (page: number) => {
        router.get('/super/kegiatan', { ...filters, page }, { preserveState: true });
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/super/kegiatan', {
            onSuccess: () => {
                setIsCreateModalOpen(false);
                reset();
            }
        });
    };

    return (
        <AppLayout>
            <Head title="Kegiatan & Event" />
            <PageHeader
                title="Kegiatan & Event"
                subtitle="Program Asrama, jadwal kegiatan, dan rencana aktivitas"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Kegiatan' }]}
                actions={
                    <button onClick={() => setIsCreateModalOpen(true)} className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="add" className="text-lg" />
                        Tambah Kegiatan
                    </button>
                }
            />

            {/* Stats */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                {[
                    { label:'Total', value:stats.total,    icon:'event',         bg:'bg-blue-100',   text:'text-blue-600' },
                    { label:'Mendatang', value:stats.upcoming, icon:'upcoming',  bg:'bg-amber-100',  text:'text-amber-600' },
                    { label:'Selesai', value:stats.selesai,   icon:'task_alt',   bg:'bg-emerald-100',text:'text-emerald-600' },
                ].map(s => (
                    <div key={s.label} className="glass-card rounded-2xl p-5 flex flex-col gap-2">
                        <div className={`w-10 h-10 ${s.bg} rounded-xl flex items-center justify-center`}><Icon name={s.icon} className={`text-xl ${s.text}`} filled /></div>
                        <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">{s.label}</p>
                        <p className="font-display text-2xl font-bold text-on-surface">{s.value}</p>
                    </div>
                ))}
            </div>

            {/* Filter tabs */}
            <div className="flex gap-2 mb-4">
                {(['all','upcoming','selesai'] as const).map(f => (
                    <button key={f} onClick={() => setStatusFilter(f)}
                        className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${statusFilter === f ? 'bg-primary-container text-white shadow-lg shadow-primary-container/20' : 'glass-card text-on-surface-variant hover:bg-white/60'}`}>
                        {f === 'all' ? 'Semua' : f === 'upcoming' ? '📅 Mendatang' : '✅ Selesai'}
                    </button>
                ))}
            </div>

            {/* Cards Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                {kegiatan.data.map((k, i) => {
                    const meta = TIPE_META[k.jenis_kegiatan] ?? TIPE_META.kegiatan;
                    const eventDate = new Date(k.waktu);
                    const isUpcoming = eventDate >= new Date();
                    const idx = (kegiatan.current_page - 1) * kegiatan.per_page + i + 1;

                    return (
                        <div key={k.id} className="glass-card rounded-2xl p-5 flex flex-col gap-4 hover:shadow-xl hover:-translate-y-0.5 transition-all group relative overflow-hidden">
                            <div className="flex items-start justify-between">
                                <div className="flex items-center gap-3">
                                    <span className="text-[10px] font-black text-on-surface-variant bg-surface-container w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">{idx}</span>
                                    <div className={`w-11 h-11 ${meta.bg} rounded-xl flex items-center justify-center`}>
                                        <Icon name={meta.icon} className={`text-xl ${meta.text}`} filled />
                                    </div>
                                </div>
                                <span className={`text-[10px] font-black px-2.5 py-1 rounded-full ${isUpcoming ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'}`}>
                                    {isUpcoming ? '📅 MENDATANG' : '✅ SELESAI'}
                                </span>
                            </div>
                            <div className="flex-1">
                                <h3 className="font-bold text-on-surface group-hover:text-primary transition-colors">{k.nama_kegiatan}</h3>
                                <div className="flex flex-col gap-1.5 mt-3">
                                    <div className="flex items-center gap-1.5 text-xs text-on-surface-variant">
                                        <Icon name="calendar_today" className="text-xs" />
                                        {eventDate.toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' })}
                                        {' · '}{eventDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                    </div>
                                    <div className="flex items-center gap-1.5 text-xs text-on-surface-variant">
                                        <Icon name="location_on" className="text-xs" />
                                        {k.tempat}
                                    </div>
                                    <div className="flex items-center gap-1.5 text-xs text-on-surface-variant">
                                        <Icon name="corporate_fare" className="text-xs" />
                                        {k.penyelenggara}
                                    </div>
                                </div>
                            </div>
                            <div className="flex gap-2 pt-1">
                                <button onClick={() => setSelectedKegiatan(k)} className="flex-1 py-2 rounded-xl text-xs font-bold bg-surface-container text-on-surface-variant hover:bg-white transition-colors">Detail</button>
                                {k.wajib_absen && (
                                    <a href={`/super/kegiatan/${k.id}/attendance`} className="flex-1 py-2 rounded-xl text-xs font-bold bg-primary-container/10 text-primary-container hover:bg-primary-container hover:text-white transition-all text-center">
                                        Absensi
                                    </a>
                                )}
                            </div>
                        </div>
                    );
                })}
            </div>

            {kegiatan.data.length === 0 && (
                <div className="glass-card rounded-2xl py-16 flex flex-col items-center gap-3 text-on-surface-variant mb-6">
                    <Icon name="event_busy" className="text-5xl opacity-20" />
                    <p className="text-sm font-semibold">Tidak ada kegiatan yang sesuai filter</p>
                </div>
            )}

            <Pagination
                currentPage={kegiatan.current_page}
                totalPages={kegiatan.last_page}
                totalItems={kegiatan.total}
                perPage={kegiatan.per_page}
                onPageChange={handlePageChange}
                onPerPageChange={(newPerPage) => {
                    router.get('/super/kegiatan', { ...filters, per_page: newPerPage }, { preserveState: true });
                }}
            />

            {/* Create Modal */}
            <Modal open={isCreateModalOpen} onClose={() => setIsCreateModalOpen(false)} title="Buat Kegiatan Baru" icon="add_task" size="md">
                <form onSubmit={submit} className="space-y-4">
                    <div className="bg-amber-50 border border-amber-200 rounded-xl p-3 flex gap-3">
                        <Icon name="info" className="text-amber-500 text-xl" />
                        <p className="text-[10px] text-amber-700 leading-relaxed">
                            <strong>PENTING:</strong> Menambahkan kegiatan di sini akan secara otomatis memasukkannya ke kalender <strong>seluruh mahasiswa</strong>. Mahasiswa diwajibkan melakukan ceklis kehadiran.
                        </p>
                    </div>

                    <div className="space-y-1">
                        <label className="text-[10px] font-black uppercase text-on-surface-variant ml-1">Nama Kegiatan</label>
                        <input type="text" value={data.nama_kegiatan} onChange={e => setData('nama_kegiatan', e.target.value)}
                            placeholder="Contoh: Seminar Kewirausahaan" className="glass-input w-full" required />
                        {errors.nama_kegiatan && <p className="text-rose-500 text-[10px] mt-1 font-bold">{errors.nama_kegiatan}</p>}
                    </div>

                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-1">
                            <label className="text-[10px] font-black uppercase text-on-surface-variant ml-1">Jenis</label>
                            <select value={data.jenis_kegiatan} onChange={e => setData('jenis_kegiatan', e.target.value)} className="glass-input w-full">
                                <option value="kegiatan">Kegiatan Umum</option>
                                <option value="akademik">Akademik</option>
                                <option value="hafalan">Hafalan</option>
                                <option value="olahraga">Olahraga</option>
                            </select>
                        </div>
                        <div className="space-y-1">
                            <label className="text-[10px] font-black uppercase text-on-surface-variant ml-1">Waktu & Tanggal</label>
                            <input type="datetime-local" value={data.waktu} onChange={e => setData('waktu', e.target.value)} className="glass-input w-full" required />
                        </div>
                    </div>

                    <div className="space-y-1">
                        <label className="text-[10px] font-black uppercase text-on-surface-variant ml-1">Tempat / Lokasi</label>
                        <input type="text" value={data.tempat} onChange={e => setData('tempat', e.target.value)}
                            placeholder="Contoh: Aula Utama" className="glass-input w-full" required />
                    </div>

                    <div className="space-y-1">
                        <label className="text-[10px] font-black uppercase text-on-surface-variant ml-1">Keterangan (Opsional)</label>
                        <textarea value={data.keterangan} onChange={e => setData('keterangan', e.target.value)}
                            placeholder="Deskripsi singkat kegiatan..." className="glass-input w-full h-24 resize-none" />
                    </div>

                    <label className="flex items-center gap-2 bg-surface-container/50 rounded-xl p-3 cursor-pointer">
                        <input type="checkbox" checked={data.wajib_absen} onChange={e => setData('wajib_absen', e.target.checked)} className="w-4 h-4 accent-primary-container" />
                        <span className="text-xs font-bold text-on-surface">Wajib Absen — mahasiswa harus check-in kehadiran (selfie + foto lokasi)</span>
                    </label>

                    <div className="flex gap-3 pt-4">
                        <button type="button" onClick={() => setIsCreateModalOpen(false)} className="flex-1 py-2.5 rounded-xl text-sm font-bold bg-surface-container text-on-surface-variant hover:bg-white transition-colors">Batal</button>
                        <button type="submit" disabled={processing} className="flex-1 py-2.5 rounded-xl text-sm font-bold bg-primary text-white shadow-lg shadow-primary/20 hover:opacity-90 transition-opacity">
                            {processing ? 'Menyimpan...' : 'Simpan & Sinkronkan'}
                        </button>
                    </div>
                </form>
            </Modal>

            {/* Detail Modal */}
            <Modal open={!!selectedKegiatan} onClose={() => setSelectedKegiatan(null)} title={selectedKegiatan?.nama_kegiatan} icon="event" size="md">
                {selectedKegiatan && (
                    <div className="space-y-4">
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Waktu</p>
                                <p className="text-sm font-semibold text-on-surface mt-0.5">
                                    {new Date(selectedKegiatan.waktu).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
                                    {' · '}{new Date(selectedKegiatan.waktu).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                </p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Jenis</p>
                                <p className="text-sm font-semibold text-on-surface mt-0.5 capitalize">{selectedKegiatan.jenis_kegiatan}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Tempat</p>
                                <p className="text-sm font-semibold text-on-surface mt-0.5">{selectedKegiatan.tempat || '-'}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Penyelenggara</p>
                                <p className="text-sm font-semibold text-on-surface mt-0.5">{selectedKegiatan.penyelenggara || '-'}</p>
                            </div>
                        </div>
                        <div>
                            <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Keterangan</p>
                            <p className="text-sm text-on-surface mt-0.5 leading-relaxed">{selectedKegiatan.keterangan || 'Tidak ada keterangan tambahan.'}</p>
                        </div>
                        <div className="flex gap-3 pt-2">
                            <button onClick={() => setSelectedKegiatan(null)} className="flex-1 py-2.5 rounded-xl text-sm font-bold bg-surface-container text-on-surface-variant hover:bg-white transition-colors">Tutup</button>
                        </div>
                    </div>
                )}
            </Modal>
        </AppLayout>
    );
}
