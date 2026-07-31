import { Head, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';
import { useDebounce } from '@/hooks/useDebounce';

interface AlumniData {
    id: number;
    nama: string;
    tahun_masuk_asrama: number;
    tahun_keluar_asrama: number;
    asal_asrama: string;
    pekerjaan_sekarang: string;
    alamat_domisili: string;
    foto: string | null;
    bidang_keahlian: string;
    no_whatsapp: string;
    email: string;
    nia: string;
    provinsi_asal: string;
    tanggal_lahir: string;
    pendidikan?: any[];
    pekerjaan?: any[];
    organisasi?: any[];
    prestasi?: any[];
}

interface Props {
    alumni: {
        data: AlumniData[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    asrama_list: string[];
    stats: { total: number; hafidz: number; angkatan_list: number[]; tahun_min: number; tahun_max: number };
    filters: { search?: string; asrama?: string; tahun_dari?: string; tahun_sampai?: string; per_page?: number };
}

const ASRAMA_COLOR: Record<string, { gradient: string; badge: string; text: string }> = {
    'Al-Farabi':  { gradient: 'from-blue-500 to-indigo-600',    badge: 'bg-blue-100 text-blue-700',       text: 'text-blue-600' },
    'Al-Ghazali': { gradient: 'from-emerald-500 to-teal-600',   badge: 'bg-emerald-100 text-emerald-700', text: 'text-emerald-600' },
    'Ibnu Sina':  { gradient: 'from-violet-500 to-purple-600',  badge: 'bg-violet-100 text-violet-700',   text: 'text-violet-600' },
    'Al-Kindi':   { gradient: 'from-amber-500 to-orange-500',   badge: 'bg-amber-100 text-amber-700',     text: 'text-amber-600' },
};

function initials(name: string) {
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
}

export default function Alumni({ alumni, asrama_list, stats, filters }: Props) {
    const [search, setSearch]           = useState(filters.search || '');
    const [asrama, setAsrama]           = useState(filters.asrama || '');
    const [tahunDari, setTahunDari]     = useState(filters.tahun_dari || '');
    const [tahunSampai, setTahunSampai] = useState(filters.tahun_sampai || '');
    const [selectedAlumni, setSelectedAlumni] = useState<AlumniData | null>(null);
    const [activeTab, setActiveTab] = useState('biodata');

    const debouncedSearch = useDebounce(search, 500);

    const hasYearFilter = tahunDari !== '' || tahunSampai !== '';

    useEffect(() => {
        router.get('/super/alumni', {
            search: debouncedSearch,
            asrama,
            tahun_dari:   tahunDari,
            tahun_sampai: tahunSampai,
            per_page: alumni.per_page,
        }, { preserveState: true, replace: true });
    }, [debouncedSearch, asrama, tahunDari, tahunSampai]);

    function clearYearFilter() {
        setTahunDari('');
        setTahunSampai('');
    }

    const handlePageChange = (page: number) => {
        router.get('/super/alumni', {
            search: debouncedSearch,
            asrama,
            tahun_dari: tahunDari,
            tahun_sampai: tahunSampai,
            per_page: alumni.per_page,
            page,
        }, { preserveState: true });
    };

    return (
        <AppLayout>
            <Head title="Data Alumni" />
            <PageHeader
                title="Data Alumni"
                subtitle="Direktori alumni, jejak karir, dan komunitas Asrama"
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Alumni' }]}
                actions={
                    <button className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="person_add" className="text-lg" />
                        Tambah Alumni
                    </button>
                }
            />

            {/* Stats */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div className="glass-card rounded-2xl p-5 flex items-center gap-4">
                    <div className="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <Icon name="school" className="text-2xl text-blue-600" filled />
                    </div>
                    <div>
                        <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Total Alumni</p>
                        <p className="font-display text-2xl font-bold text-on-surface">{stats.total}</p>
                    </div>
                </div>
                <div className="glass-card rounded-2xl p-5 flex items-center gap-4">
                    <div className="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <Icon name="auto_stories" className="text-2xl text-emerald-600" filled />
                    </div>
                    <div>
                        <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Hafidz Qur'an</p>
                        <p className="font-display text-2xl font-bold text-on-surface">{stats.hafidz}</p>
                    </div>
                </div>
                {/* Year range result card */}
                <div className={`glass-card rounded-2xl p-5 flex items-center gap-4 transition-all ${hasYearFilter ? 'ring-2 ring-primary-container/40' : ''}`}>
                    <div className={`w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 ${hasYearFilter ? 'bg-primary-container/20' : 'bg-violet-100'}`}>
                        <Icon name="calendar_today" className={`text-2xl ${hasYearFilter ? 'text-primary-container' : 'text-violet-600'}`} filled />
                    </div>
                    <div>
                        <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">
                            {hasYearFilter ? 'Hasil Filter Tahun' : 'Rentang Angkatan'}
                        </p>
                        <p className="font-display text-2xl font-bold text-on-surface">{alumni.total}</p>
                        {hasYearFilter ? (
                            <p className="text-[10px] text-primary-container font-bold">
                                {tahunDari || stats.tahun_min} — {tahunSampai || stats.tahun_max}
                            </p>
                        ) : (
                            <p className="text-[10px] text-on-surface-variant">
                                {stats.tahun_min > 0 ? `${stats.tahun_min} — ${stats.tahun_max}` : `${stats.angkatan_list.length} angkatan`}
                            </p>
                        )}
                    </div>
                </div>
            </div>

            {/* Filters */}
            <div className="glass-card rounded-2xl p-4 mb-4 space-y-3">
                {/* Row 1: search + asrama */}
                <div className="flex flex-col sm:flex-row gap-3">
                    <div className="flex-1 flex items-center gap-2 glass-input px-3 py-2.5 rounded-xl">
                        <Icon name="search" className="text-on-surface-variant text-lg flex-shrink-0" />
                        <input value={search} onChange={e => setSearch(e.target.value)}
                            placeholder="Cari nama atau profesi..." className="bg-transparent outline-none text-sm flex-1" />
                        {search && <button onClick={() => setSearch('')} className="flex-shrink-0"><Icon name="close" className="text-sm text-outline" /></button>}
                    </div>
                    <select value={asrama} onChange={e => setAsrama(e.target.value)} className="glass-input text-sm py-2.5 min-w-[160px]">
                        <option value="">Semua Asrama</option>
                        {asrama_list.map(a => <option key={a} value={a}>{a}</option>)}
                    </select>
                </div>

                {/* Row 2: year range */}
                <div className="flex items-center gap-3 flex-wrap">
                    <div className="flex items-center gap-2">
                        <Icon name="date_range" className="text-on-surface-variant text-lg flex-shrink-0" />
                        <span className="text-xs font-bold text-on-surface-variant whitespace-nowrap">Filter Tahun Masuk:</span>
                    </div>
                    <div className="flex items-center gap-2 flex-1 flex-wrap">
                        <div className="flex items-center gap-1.5">
                            <span className="text-xs text-on-surface-variant">Dari</span>
                            <input
                                type="number" min={1990} max={2099}
                                value={tahunDari}
                                onChange={e => setTahunDari(e.target.value)}
                                placeholder={String(stats.tahun_min || new Date().getFullYear())}
                                className="glass-input w-24 text-sm text-center py-2"
                            />
                        </div>
                        <div className="flex items-center gap-1.5">
                            <span className="text-xs text-on-surface-variant">s.d.</span>
                            <input
                                type="number" min={1990} max={2099}
                                value={tahunSampai}
                                onChange={e => setTahunSampai(e.target.value)}
                                placeholder={String(stats.tahun_max || new Date().getFullYear())}
                                className="glass-input w-24 text-sm text-center py-2"
                            />
                        </div>
                        {hasYearFilter && (
                            <button onClick={clearYearFilter}
                                className="flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 hover:bg-rose-100 transition-colors">
                                <Icon name="close" className="text-xs" /> Reset
                            </button>
                        )}
                        {hasYearFilter && (
                            <span className="text-xs font-bold text-primary-container bg-primary-container/10 px-2.5 py-1.5 rounded-xl">
                                {alumni.total} alumni ditemukan
                            </span>
                        )}
                    </div>
                </div>
            </div>

            <p className="text-xs text-on-surface-variant mb-4 font-semibold">
                Menampilkan <span className="text-on-surface font-black">{alumni.total}</span> alumni
                {hasYearFilter && (
                    <span className="ml-1 text-primary-container">
                        · tahun {tahunDari || stats.tahun_min} s.d. {tahunSampai || stats.tahun_max}
                    </span>
                )}
            </p>

            {/* Card Grid */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-6">
                {alumni.data.map((a, i) => {
                    const color  = ASRAMA_COLOR[a.asal_asrama] ?? ASRAMA_COLOR['Al-Farabi'];
                    const idx    = (alumni.current_page - 1) * alumni.per_page + i + 1;

                    return (
                        <div key={a.id} className="glass-card rounded-2xl overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-200 group">
                            <div className={`h-1.5 bg-gradient-to-r ${color.gradient}`} />
                            <div className="p-4 flex flex-col gap-3 flex-1">
                                <div className="flex items-center gap-3">
                                    <span className="text-[10px] font-black text-on-surface-variant bg-surface-container w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">{idx}</span>
                                    {a.foto ? (
                                        <img src={a.foto} className="w-10 h-10 rounded-xl object-cover shadow shadow-black/10" />
                                    ) : (
                                        <div className={`w-10 h-10 rounded-xl bg-gradient-to-br ${color.gradient} flex items-center justify-center text-xs font-black text-white shadow flex-shrink-0`}>
                                            {initials(a.nama)}
                                        </div>
                                    )}
                                    <div className="flex-1 min-w-0">
                                        <h3 className="font-bold text-on-surface text-sm leading-tight truncate">{a.nama}</h3>
                                        <span className={`inline-block text-[10px] font-black px-2 py-0.5 rounded-full ${color.badge}`}>{a.asal_asrama}</span>
                                    </div>
                                    <span className="text-[10px] font-black text-on-surface-variant bg-surface-container px-1.5 py-1 rounded-lg flex-shrink-0">{a.tahun_masuk_asrama}</span>
                                </div>
                                <div className="space-y-1 flex-1">
                                    <div className="flex items-start gap-1.5">
                                        <Icon name="work" className="text-xs text-on-surface-variant flex-shrink-0 mt-0.5" />
                                        <p className="text-xs font-semibold text-on-surface leading-snug">{a.pekerjaan_sekarang || 'Belum bekerja'}</p>
                                    </div>
                                    <div className="flex items-center gap-1.5">
                                        <Icon name="location_on" className="text-xs text-on-surface-variant flex-shrink-0" />
                                        <p className="text-xs text-on-surface-variant">{a.alamat_domisili || 'N/A'}</p>
                                    </div>
                                </div>
                                <div className="flex gap-2">
                                    <button onClick={() => { setSelectedAlumni(a); setActiveTab('biodata'); }}
                                        className="flex-1 py-1.5 rounded-xl text-[11px] font-bold bg-surface-container text-on-surface-variant hover:bg-white transition-colors flex items-center justify-center gap-1">
                                        <Icon name="visibility" className="text-xs" /> Detail
                                    </button>
                                    <button className="w-8 h-8 rounded-xl bg-surface-container text-on-surface-variant hover:bg-white transition-colors flex items-center justify-center flex-shrink-0">
                                        <Icon name="edit" className="text-xs" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    );
                })}
            </div>

            {alumni.data.length === 0 && (
                <div className="glass-card rounded-2xl py-16 flex flex-col items-center gap-3 text-on-surface-variant mb-6">
                    <Icon name="search_off" className="text-5xl opacity-20" />
                    <p className="text-sm font-semibold">Tidak ada alumni yang sesuai filter</p>
                </div>
            )}

            <Pagination
                currentPage={alumni.current_page}
                totalPages={alumni.last_page}
                totalItems={alumni.total}
                perPage={alumni.per_page}
                onPageChange={handlePageChange}
                onPerPageChange={(newPerPage) => {
                    router.get('/super/alumni', {
                        search: debouncedSearch,
                        asrama,
                        tahun_dari: tahunDari,
                        tahun_sampai: tahunSampai,
                        per_page: newPerPage,
                        page: 1,
                    }, { preserveState: true });
                }}
            />

            {/* Detail Modal */}
            {selectedAlumni && (
                <Modal open={!!selectedAlumni} onClose={() => setSelectedAlumni(null)} title="Profil Detail Alumni" icon="account_circle" size="lg">
                    <div className="flex flex-col md:flex-row gap-6">
                        <div className="flex flex-col items-center gap-4 w-full md:w-1/3">
                            {selectedAlumni.foto ? (
                                <img src={selectedAlumni.foto} className="w-40 h-40 rounded-2xl object-cover shadow-xl border-4 border-white" />
                            ) : (
                                <div className="w-40 h-40 rounded-2xl bg-primary-container flex items-center justify-center text-5xl font-black text-white shadow-xl">
                                    {initials(selectedAlumni.nama)}
                                </div>
                            )}
                            <div className="text-center">
                                <h2 className="font-bold text-on-surface text-lg">{selectedAlumni.nama}</h2>
                                <p className="text-xs text-on-surface-variant">{selectedAlumni.nia}</p>
                                <div className="mt-2 flex flex-wrap justify-center gap-1">
                                    <span className="text-[10px] font-black px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">{selectedAlumni.asal_asrama}</span>
                                    <span className="text-[10px] font-black px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant">Angkatan {selectedAlumni.tahun_masuk_asrama}</span>
                                </div>
                            </div>
                        </div>

                        <div className="flex-1 flex flex-col min-w-0">
                            {/* Tabs Header */}
                            <div className="flex border-b border-black/10 overflow-x-auto no-scrollbar gap-4 mb-4">
                                {['biodata', 'pendidikan', 'pekerjaan', 'organisasi', 'prestasi'].map(tab => (
                                    <button key={tab} onClick={() => setActiveTab(tab)}
                                        className={`pb-2 px-1 text-xs font-bold uppercase tracking-wider transition-all border-b-2 whitespace-nowrap ${activeTab === tab ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface'}`}>
                                        {tab}
                                    </button>
                                ))}
                            </div>

                            {/* Tab Content */}
                            <div className="flex-1 space-y-4">
                                {activeTab === 'biodata' && (
                                    <div className="grid grid-cols-2 gap-4">
                                        <InfoItem label="Email" value={selectedAlumni.email} />
                                        <InfoItem label="WhatsApp" value={selectedAlumni.no_whatsapp} />
                                        <InfoItem label="Kota Lahir" value={selectedAlumni.provinsi_asal} />
                                        <InfoItem label="Tanggal Lahir" value={selectedAlumni.tanggal_lahir} />
                                        <InfoItem label="Keahlian" value={selectedAlumni.bidang_keahlian} />
                                        <InfoItem label="Domisili" value={selectedAlumni.alamat_domisili} className="col-span-2" />
                                    </div>
                                )}

                                {activeTab === 'pendidikan' && (
                                    <div className="space-y-3">
                                        {selectedAlumni.pendidikan?.length ? selectedAlumni.pendidikan.map((p, i) => (
                                            <TimelineItem key={i} title={p.nama_sekolah} subtitle={p.jenjang + ' · ' + p.program_studi} time={p.tahun_lulus} icon="school" />
                                        )) : <EmptyState text="Belum ada data pendidikan" />}
                                    </div>
                                )}

                                {activeTab === 'pekerjaan' && (
                                    <div className="space-y-3">
                                        {selectedAlumni.pekerjaan?.length ? selectedAlumni.pekerjaan.map((p, i) => (
                                            <TimelineItem key={i} title={p.nama_perusahaan} subtitle={p.jabatan} time={p.tahun_masuk + ' - ' + (p.tahun_keluar || 'Sekarang')} icon="work" />
                                        )) : <EmptyState text="Belum ada data pekerjaan" />}
                                    </div>
                                )}

                                {activeTab === 'organisasi' && (
                                    <div className="space-y-3">
                                        {selectedAlumni.organisasi?.length ? selectedAlumni.organisasi.map((o, i) => (
                                            <TimelineItem key={i} title={o.nama_organisasi} subtitle={o.jabatan} time={o.tahun_aktif} icon="groups" />
                                        )) : <EmptyState text="Belum ada data organisasi" />}
                                    </div>
                                )}

                                {activeTab === 'prestasi' && (
                                    <div className="space-y-3">
                                        {selectedAlumni.prestasi?.length ? selectedAlumni.prestasi.map((p, i) => (
                                            <TimelineItem key={i} title={p.nama_prestasi} subtitle={p.penyelenggara} time={p.tahun} icon="emoji_events" />
                                        )) : <EmptyState text="Belum ada data prestasi" />}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </Modal>
            )}
        </AppLayout>
    );
}

function InfoItem({ label, value, className = '' }: { label: string; value?: string | null; className?: string }) {
    return (
        <div className={className}>
            <p className="text-[10px] font-black uppercase text-on-surface-variant tracking-widest">{label}</p>
            <p className="text-xs font-semibold text-on-surface">{value || '-'}</p>
        </div>
    );
}

function TimelineItem({ title, subtitle, time, icon }: { title: string; subtitle: string; time: string | number; icon: string }) {
    return (
        <div className="flex gap-3 bg-surface-container/30 p-3 rounded-xl">
            <div className="w-8 h-8 rounded-lg bg-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <Icon name={icon} className="text-primary text-base" />
            </div>
            <div className="flex-1 min-w-0">
                <h4 className="text-xs font-bold text-on-surface truncate">{title}</h4>
                <p className="text-[10px] text-on-surface-variant truncate">{subtitle}</p>
            </div>
            <span className="text-[10px] font-black text-primary-container bg-primary-container/10 px-2 py-1 rounded-lg self-start">{time}</span>
        </div>
    );
}

function EmptyState({ text }: { text: string }) {
    return (
        <div className="flex flex-col items-center py-8 gap-2 text-on-surface-variant/40">
            <Icon name="inbox" className="text-3xl" />
            <p className="text-xs font-bold">{text}</p>
        </div>
    );
}
