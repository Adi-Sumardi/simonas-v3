import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { StatCard } from '@/Components/ui/StatCard';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { useDebounce } from '@/hooks/useDebounce';

interface WargaData {
    id: number;
    no_induk: string;
    name: string;
    asrama: string;
    angkatan: number;
    status_warga: string;
    tingkat_keanggotaan: string | null;
    semester: number | null;
    hafalan_juz: number;
    skor: number;
    mentor: string;
}

const TINGKAT_LABEL: Record<string, string> = {
    percobaan: 'Percobaan',
    tetap: 'Tetap',
    senior: 'Senior',
};

interface Props {
    warga: {
        data: WargaData[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    asramas: string[];
    stats: Record<string, number>;
    filters: any;
}

export default function Warga({ warga, asramas, stats, filters }: Props) {
    const [search, setSearch] = useState(filters.search || '');
    const [asrama, setAsrama] = useState(filters.asrama || '');
    const [status, setStatus] = useState(filters.status || '');
    const [semester, setSemester] = useState(filters.semester || '');
    const [tingkat, setTingkat] = useState(filters.tingkat || '');
    const [percobaanMinBulan, setPercobaanMinBulan] = useState(filters.percobaan_min_bulan || '');
    const [showAddModal, setShowAddModal] = useState(false);
    const addForm = useForm({ name: '', email: '', password: '', no_induk: '', asrama: '', status_warga: 'aktif', angkatan: '' });

    function submitAdd(e: React.FormEvent) {
        e.preventDefault();
        addForm.post('/super/warga', {
            preserveScroll: true,
            onSuccess: () => { addForm.reset(); setShowAddModal(false); },
        });
    }

    const debouncedSearch = useDebounce(search, 500);

    useEffect(() => {
        router.get('/super/warga', {
            search: debouncedSearch,
            asrama,
            status,
            semester,
            tingkat,
            percobaan_min_bulan: percobaanMinBulan,
            per_page: warga.per_page
        }, {
            preserveState: true,
            replace: true
        });
    }, [debouncedSearch, asrama, status, semester, tingkat, percobaanMinBulan]);

    const handlePageChange = (newPage: number) => {
        router.get('/super/warga', {
            ...filters,
            page: newPage
        }, {
            preserveState: true
        });
    };

    return (
        <AppLayout>
            <Head title="Manajemen Warga" />
            <PageHeader
                title="Manajemen Warga"
                subtitle="Data seluruh warga aktif dan nonaktif di Asrama"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Warga' }]}
                actions={
                    <button onClick={() => setShowAddModal(true)} className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="person_add" className="text-lg" />
                        Tambah Warga
                    </button>
                }
            />

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <StatCard icon="people"        label="TOTAL WARGA"  value={stats.total}    badgeColor="blue" />
                <StatCard icon="check_circle"  label="AKTIF"        value={stats.aktif}    badge="Aktif"    badgeColor="emerald" />
                <StatCard icon="cancel"        label="NONAKTIF"     value={stats.nonaktif} badge="Off"      badgeColor="rose" />
                <StatCard icon="analytics"     label="AVG. SKOR"    value={`${stats.avg_skor}/100`} badge="Semester ini" badgeColor="amber" />
            </div>

            <div className="glass-card rounded-2xl p-4 mb-6 flex flex-col md:flex-row gap-4">
                <div className="flex-1 flex items-center gap-3 bg-white/60 rounded-xl px-4 py-2.5 shadow-inner-soft">
                    <Icon name="search" className="text-on-surface-variant text-lg flex-shrink-0" />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        placeholder="Cari nama atau NIM..." className="bg-transparent outline-none text-sm flex-1 w-full" />
                    {search && <button onClick={() => setSearch('')}><Icon name="close" className="text-sm text-outline" /></button>}
                </div>
                <div className="flex flex-col sm:flex-row gap-3">
                    <select value={asrama} onChange={e => setAsrama(e.target.value)} className="glass-input text-sm py-2.5 min-w-[140px] flex-1">
                        <option value="">Semua Asrama</option>
                        {asramas.map(a => <option key={a} value={a}>{a}</option>)}
                    </select>
                    <select value={status} onChange={e => setStatus(e.target.value)} className="glass-input text-sm py-2.5 min-w-[120px] flex-1">
                        <option value="">Semua Akun</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                    <select value={semester} onChange={e => setSemester(e.target.value)} className="glass-input text-sm py-2.5 min-w-[130px] flex-1">
                        <option value="">Semua Semester</option>
                        {Array.from({ length: 14 }, (_, i) => i + 1).map(s => (
                            <option key={s} value={s}>Semester {s}</option>
                        ))}
                    </select>
                    <select value={tingkat} onChange={e => setTingkat(e.target.value)} className="glass-input text-sm py-2.5 min-w-[150px] flex-1">
                        <option value="">Semua Status</option>
                        <option value="percobaan">Percobaan</option>
                        <option value="tetap">Tetap</option>
                        <option value="senior">Senior</option>
                    </select>
                    <select value={percobaanMinBulan} onChange={e => setPercobaanMinBulan(e.target.value)} className="glass-input text-sm py-2.5 min-w-[170px] flex-1">
                        <option value="">Semua Lama Percobaan</option>
                        <option value="1">Percobaan &ge; 1 bulan</option>
                        <option value="3">Percobaan &ge; 3 bulan</option>
                        <option value="6">Percobaan &ge; 6 bulan</option>
                        <option value="12">Percobaan &ge; 12 bulan</option>
                    </select>
                </div>
            </div>

            {/* Desktop Table */}
            <div className="hidden md:block glass-card rounded-2xl overflow-hidden mb-6">
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead className="border-b border-white/40 bg-surface-container/30">
                            <tr>
                                {['#','Nomor Induk Warga','Nama','Asrama','Angkatan','Semester','Status','Hafalan','Skor','Akun','Aksi'].map(h => (
                                    <th key={h} className="text-left py-3 px-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant whitespace-nowrap last:text-right">{h}</th>
                                ))}
                            </tr>
                        </thead>
                        <tbody>
                            {warga.data.map((w, i) => (
                                <tr key={w.id} className="border-b border-white/20 hover:bg-white/30 transition-colors">
                                    <td className="py-3 px-4 text-xs font-black text-on-surface-variant tabular-nums">
                                        {(warga.current_page - 1) * warga.per_page + i + 1}
                                    </td>
                                    <td className="py-3 px-4 font-mono text-xs text-on-surface-variant">{w.no_induk}</td>
                                    <td className="py-3 px-4">
                                        <div className="flex items-center gap-2">
                                            <div className="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center text-xs font-black text-white flex-shrink-0">
                                                {w.name.split(' ').map((n: string) => n[0]).slice(0,2).join('')}
                                            </div>
                                            <span className="font-semibold text-on-surface">{w.name}</span>
                                        </div>
                                    </td>
                                    <td className="py-3 px-4">
                                        <span className="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-600 font-bold">{w.asrama}</span>
                                    </td>
                                    <td className="py-3 px-4 text-on-surface-variant">{w.angkatan}</td>
                                    <td className="py-3 px-4 text-on-surface-variant">{w.semester ?? '-'}</td>
                                    <td className="py-3 px-4">
                                        <span className={`text-xs font-bold px-2 py-1 rounded-full ${
                                            w.tingkat_keanggotaan === 'senior' ? 'bg-purple-50 text-purple-600' :
                                            w.tingkat_keanggotaan === 'tetap' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600'
                                        }`}>{TINGKAT_LABEL[w.tingkat_keanggotaan ?? ''] ?? '-'}</span>
                                    </td>
                                    <td className="py-3 px-4">
                                        <span className="text-xs font-bold text-emerald-600">Juz {w.hafalan_juz || 0}</span>
                                    </td>
                                    <td className="py-3 px-4">
                                        <span className={`text-xs font-bold px-2 py-1 rounded-full ${w.skor >= 85 ? 'bg-emerald-50 text-emerald-600' : w.skor >= 70 ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600'}`}>
                                            {w.skor || 0}
                                        </span>
                                    </td>
                                    <td className="py-3 px-4">
                                        <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${w.status_warga === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'}`}>
                                            {w.status_warga === 'aktif' ? '● Aktif' : '○ Nonaktif'}
                                        </span>
                                    </td>
                                    <td className="py-3 px-4 text-right">
                                        <div className="flex justify-end gap-1">
                                            <Link href={`/super/warga/${w.id}`} className="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center transition-colors">
                                                <Icon name="visibility" className="text-sm" />
                                            </Link>
                                            <Link href={`/super/warga/${w.id}/edit`} className="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 flex items-center justify-center transition-colors">
                                                <Icon name="edit" className="text-sm" />
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Mobile Card View */}
            <div className="md:hidden space-y-3 mb-6">
                {warga.data.map((w) => (
                    <div key={w.id} className="glass-card rounded-2xl p-4 flex flex-col gap-3">
                        <div className="flex justify-between items-start">
                            <div className="flex items-center gap-3">
                                <div className="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-sm font-black text-white">
                                    {w.name.split(' ').map((n: string) => n[0]).slice(0,2).join('')}
                                </div>
                                <div>
                                    <h3 className="font-bold text-on-surface text-sm">{w.name}</h3>
                                    <p className="text-[10px] font-mono text-on-surface-variant">{w.no_induk}</p>
                                </div>
                            </div>
                            <span className={`text-[10px] font-black px-2 py-0.5 rounded-full ${w.status_warga === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'}`}>
                                {(w.status_warga || '').toUpperCase()}
                            </span>
                        </div>
                        <div className="grid grid-cols-3 gap-2">
                            <div className="bg-surface-container/30 rounded-xl p-2 text-center">
                                <p className="text-[8px] font-black text-on-surface-variant uppercase">Asrama</p>
                                <p className="text-xs font-bold text-blue-600">{w.asrama}</p>
                            </div>
                            <div className="bg-surface-container/30 rounded-xl p-2 text-center">
                                <p className="text-[8px] font-black text-on-surface-variant uppercase">Hafalan</p>
                                <p className="text-xs font-bold text-emerald-600">Juz {w.hafalan_juz || 0}</p>
                            </div>
                            <div className="bg-surface-container/30 rounded-xl p-2 text-center">
                                <p className="text-[8px] font-black text-on-surface-variant uppercase">Skor</p>
                                <p className="text-xs font-bold text-amber-600">{w.skor || 0}</p>
                            </div>
                        </div>
                        <div className="flex gap-2">
                            <Link href={`/super/warga/${w.id}`} className="flex-1 py-2 rounded-xl bg-blue-50 text-blue-600 font-bold text-xs flex items-center justify-center gap-1">
                                <Icon name="visibility" className="text-sm" /> Detail
                            </Link>
                            <Link href={`/super/warga/${w.id}/edit`} className="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <Icon name="edit" className="text-sm" />
                            </Link>
                        </div>
                    </div>
                ))}
            </div>

            {warga.data.length === 0 && (
                <div className="glass-card rounded-2xl py-12 flex flex-col items-center gap-2 text-on-surface-variant mb-6">
                    <Icon name="search_off" className="text-4xl opacity-20" />
                    <p className="text-sm font-semibold">Tidak ada data warga yang sesuai</p>
                </div>
            )}

            <Pagination
                currentPage={warga.current_page}
                totalPages={warga.last_page}
                totalItems={warga.total}
                perPage={warga.per_page}
                onPageChange={handlePageChange}
                onPerPageChange={(newPerPage) => {
                    router.get('/super/warga', { ...filters, per_page: newPerPage }, { preserveState: true });
                }}
            />

            {showAddModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
                    <div className="bg-white rounded-3xl shadow-2xl w-full max-w-md animate-modal-in">
                        <div className="flex items-center justify-between p-6 border-b border-zinc-100">
                            <h3 className="font-bold text-on-surface">Tambah Warga</h3>
                            <button onClick={() => setShowAddModal(false)} className="w-8 h-8 rounded-xl bg-zinc-100 flex items-center justify-center hover:bg-zinc-200">
                                <Icon name="close" className="text-sm" />
                            </button>
                        </div>
                        <form onSubmit={submitAdd} className="p-6 space-y-4">
                            <div>
                                <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Nama Lengkap *</label>
                                <input value={addForm.data.name} onChange={e => addForm.setData('name', e.target.value)} className="glass-input w-full text-sm" />
                                {addForm.errors.name && <p className="text-xs text-rose-500 mt-1">{addForm.errors.name}</p>}
                            </div>
                            <div>
                                <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Email *</label>
                                <input type="email" value={addForm.data.email} onChange={e => addForm.setData('email', e.target.value)} className="glass-input w-full text-sm" />
                                {addForm.errors.email && <p className="text-xs text-rose-500 mt-1">{addForm.errors.email}</p>}
                            </div>
                            <div>
                                <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Password *</label>
                                <input type="password" value={addForm.data.password} onChange={e => addForm.setData('password', e.target.value)} className="glass-input w-full text-sm" placeholder="Min. 8 karakter" />
                                {addForm.errors.password && <p className="text-xs text-rose-500 mt-1">{addForm.errors.password}</p>}
                            </div>
                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">No. Induk</label>
                                    <input value={addForm.data.no_induk} onChange={e => addForm.setData('no_induk', e.target.value)} className="glass-input w-full text-sm" />
                                </div>
                                <div>
                                    <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Angkatan</label>
                                    <input value={addForm.data.angkatan} onChange={e => addForm.setData('angkatan', e.target.value)} className="glass-input w-full text-sm" placeholder="cth. 2024" />
                                </div>
                            </div>
                            <div>
                                <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Asrama</label>
                                <select value={addForm.data.asrama} onChange={e => addForm.setData('asrama', e.target.value)} className="glass-input w-full text-sm py-2">
                                    <option value="">— Pilih Asrama —</option>
                                    {asramas.map(a => <option key={a} value={a}>{a}</option>)}
                                </select>
                            </div>
                            <div className="flex gap-3 pt-2">
                                <button type="submit" disabled={addForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                                    {addForm.processing ? 'Menyimpan...' : 'Tambah Warga'}
                                </button>
                                <button type="button" onClick={() => setShowAddModal(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
