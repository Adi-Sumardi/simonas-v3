import { Head, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { Pagination } from '@/Components/ui/Pagination';
import { Modal } from '@/Components/ui/Modal';

interface AlumniMember {
    id: number;
    name: string;
    avatar: string | null;
    asrama: string | null;
    angkatan: string | null;
    pekerjaan: string | null;
    perusahaan: string | null;
    lokasi: string | null;
}

interface DatabaseProps {
    alumni: {
        data: AlumniMember[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
    };
    asramas: string[];
    filters: {
        search?: string;
        asrama?: string;
    };
}

const TIPE_META: Record<string, { label: string; icon: string; color: string; bg: string }> = {
    pekerjaan:   { label: 'Pekerjaan',    icon: 'work',           color: 'text-emerald-600', bg: 'bg-emerald-100' },
    pendidikan:  { label: 'Pendidikan',   icon: 'school',         color: 'text-blue-600',    bg: 'bg-blue-100' },
    organisasi:  { label: 'Organisasi',   icon: 'groups',         color: 'text-purple-600',  bg: 'bg-purple-100' },
    penghargaan: { label: 'Penghargaan',  icon: 'military_tech',  color: 'text-amber-600',   bg: 'bg-amber-100' },
    sertifikasi: { label: 'Sertifikasi',  icon: 'verified',       color: 'text-rose-600',    bg: 'bg-rose-100' },
};
const TIPES = Object.keys(TIPE_META);

export default function Database({ alumni, asramas, filters }: DatabaseProps) {
    const [search, setSearch] = useState(filters.search || '');
    const [asrama, setAsrama] = useState(filters.asrama || '');
    const [selectedAlumni, setSelectedAlumni] = useState<any>(null);
    const [loadingDetails, setLoadingDetails] = useState(false);
    const [activeTab, setActiveTab] = useState('pekerjaan');

    // Debounce search
    useEffect(() => {
        const timer = setTimeout(() => {
            if (search !== (filters.search || '')) {
                handleFilter();
            }
        }, 500);
        return () => clearTimeout(timer);
    }, [search]);

    function handleFilter() {
        router.get('/alumni/database', { search, asrama }, {
            preserveState: true,
            replace: true,
        });
    }

    async function viewDetails(id: number) {
        setLoadingDetails(true);
        try {
            const res = await fetch(`/alumni/database/${id}`);
            const data = await res.json();
            setSelectedAlumni(data);
        } catch (e) {
            console.error(e);
        } finally {
            setLoadingDetails(false);
        }
    }

    return (
        <AppLayout searchPlaceholder="Cari nama atau profesi...">
            <Head title="Database Alumni" />

            <PageHeader
                title="Database Alumni"
                subtitle="Jalin silaturahmi dan temukan rekan alumni SIMONAS."
                breadcrumbs={[{ label: 'Beranda', href: '/dashboard' }, { label: 'Database' }]}
            />

            {/* Filters */}
            <div className="glass-card rounded-3xl p-6 mb-8">
                <div className="flex flex-col md:flex-row gap-4">
                    <div className="flex-1 relative">
                        <Icon name="search" className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                        <input 
                            type="text"
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            placeholder="Cari nama, profesi, atau perusahaan..."
                            className="glass-input w-full pl-12 py-3 text-sm"
                        />
                    </div>
                    <div className="w-full md:w-64 relative">
                        <Icon name="home" className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                        <select 
                            value={asrama}
                            onChange={e => { setAsrama(e.target.value); router.get('/alumni/database', { search, asrama: e.target.value }, { preserveState: true }); }}
                            className="glass-input w-full pl-12 py-3 text-sm appearance-none cursor-pointer"
                        >
                            <option value="">Semua Asrama</option>
                            {asramas.map(a => (
                                <option key={a} value={a}>{a}</option>
                            ))}
                        </select>
                    </div>
                </div>
            </div>

            {/* Alumni Grid */}
            {alumni.data.length === 0 ? (
                <div className="glass-card rounded-3xl p-20 text-center">
                    <div className="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4">
                        <Icon name="person_search" className="text-4xl text-slate-300" />
                    </div>
                    <h3 className="text-lg font-bold text-on-surface">Alumni tidak ditemukan</h3>
                    <p className="text-sm text-on-surface-variant mt-1">Coba gunakan kata kunci atau filter lain.</p>
                </div>
            ) : (
                <>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        {alumni.data.map(member => (
                            <AlumniCard key={member.id} member={member} onView={() => viewDetails(member.id)} />
                        ))}
                    </div>
                    <div className="mt-10">
                        <Pagination 
                            currentPage={alumni.current_page}
                            totalPages={alumni.last_page}
                            totalItems={alumni.total}
                            perPage={alumni.per_page}
                            onPageChange={(p) => router.get('/alumni/database', { ...filters, page: p }, { preserveState: true })}
                            onPerPageChange={() => {}} 
                        />
                    </div>
                </>
            )}

            {/* Profile Modal */}
            <Modal 
                open={!!selectedAlumni} 
                onClose={() => setSelectedAlumni(null)} 
                title="Profil Alumni" 
                size="lg"
                icon="person"
            >
                {selectedAlumni && (
                    <div className="space-y-6 pb-6">
                        {/* Header Banner & Avatar */}
                        <div className="relative">
                            <div className="h-32 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl shadow-inner" />
                            <div className="absolute -bottom-10 left-8">
                                <div className="w-24 h-24 rounded-full overflow-hidden bg-white ring-4 ring-white shadow-xl flex items-center justify-center">
                                    {selectedAlumni.avatar ? (
                                        <img src={selectedAlumni.avatar} alt={selectedAlumni.name} className="w-full h-full object-cover" />
                                    ) : (
                                        <span className="font-display font-black text-3xl text-emerald-600">
                                            {selectedAlumni.name.split(' ').map((n: string) => n[0]).slice(0, 2).join('').toUpperCase()}
                                        </span>
                                    )}
                                </div>
                            </div>
                        </div>

                        <div className="pt-10 px-4">
                            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h2 className="text-2xl font-display font-black text-on-surface">{selectedAlumni.name}</h2>
                                    <p className="text-emerald-600 font-bold flex items-center gap-2 mt-1">
                                        <Icon name="school" className="text-sm" />
                                        Asrama {selectedAlumni.asrama} · Angkatan {selectedAlumni.angkatan}
                                    </p>
                                </div>
                                {selectedAlumni.no_hp && (
                                    <a 
                                        href={`https://wa.me/${selectedAlumni.no_hp.replace(/[^0-9]/g, '')}`}
                                        target="_blank"
                                        className="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20"
                                    >
                                        <Icon name="chat" className="text-lg" filled />
                                        Hubungi via WhatsApp
                                    </a>
                                )}
                            </div>

                            {selectedAlumni.bio && (
                                <div className="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <p className="text-sm text-on-surface-variant leading-relaxed italic italic-quote">
                                        "{selectedAlumni.bio}"
                                    </p>
                                </div>
                            )}

                            {/* Tabs Navigation */}
                            <div className="mt-8 flex gap-1 overflow-x-auto pb-2 scrollbar-hide">
                                {TIPES.map(tipe => {
                                    const meta = TIPE_META[tipe];
                                    const count = (selectedAlumni.riwayats[tipe] ?? []).length;
                                    return (
                                        <button 
                                            key={tipe} 
                                            onClick={() => setActiveTab(tipe)}
                                            className={`flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition-all ${
                                                activeTab === tipe 
                                                    ? `${meta.bg} ${meta.color}`
                                                    : 'text-slate-500 hover:bg-slate-100'
                                            }`}
                                        >
                                            <Icon name={meta.icon} className="text-sm" filled={activeTab === tipe} />
                                            {meta.label}
                                            {count > 0 && (
                                                <span className={`px-1.5 py-0.5 rounded-full text-[10px] ${activeTab === tipe ? 'bg-white/50' : 'bg-slate-100'}`}>
                                                    {count}
                                                </span>
                                            )}
                                        </button>
                                    );
                                })}
                            </div>

                            {/* Tab Content */}
                            <div className="mt-4 space-y-3 min-h-[200px]">
                                {(selectedAlumni.riwayats[activeTab] ?? []).length === 0 ? (
                                    <div className="py-10 text-center">
                                        <div className={`w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center opacity-20 ${TIPE_META[activeTab].bg}`}>
                                            <Icon name={TIPE_META[activeTab].icon} className={`text-2xl ${TIPE_META[activeTab].color}`} />
                                        </div>
                                        <p className="text-sm text-slate-400 font-medium">Belum ada data {TIPE_META[activeTab].label.toLowerCase()}</p>
                                    </div>
                                ) : (
                                    (selectedAlumni.riwayats[activeTab] ?? []).map((item: any) => (
                                        <div key={item.id} className="flex gap-4 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all group">
                                            <div className={`w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110 ${TIPE_META[activeTab].bg}`}>
                                                <Icon name={TIPE_META[activeTab].icon} className={`text-lg ${TIPE_META[activeTab].color}`} />
                                            </div>
                                            <div>
                                                <p className="font-bold text-sm text-on-surface">{item.judul}</p>
                                                <p className="text-xs text-on-surface-variant font-medium">{item.posisi}</p>
                                                <div className="flex items-center gap-2 mt-1">
                                                    <span className="text-[10px] text-slate-400 flex items-center gap-1">
                                                        <Icon name="calendar_today" className="text-[10px]" />
                                                        {item.mulai} — {item.masih_berlangsung ? 'Sekarang' : item.selesai}
                                                    </span>
                                                    {item.lokasi && (
                                                        <>
                                                            <span className="text-slate-300">·</span>
                                                            <span className="text-[10px] text-slate-400 flex items-center gap-1">
                                                                <Icon name="location_on" className="text-[10px]" />
                                                                {item.lokasi}
                                                            </span>
                                                        </>
                                                    )}
                                                </div>
                                                {item.deskripsi && (
                                                    <p className="mt-2 text-xs text-on-surface-variant leading-relaxed line-clamp-2">{item.deskripsi}</p>
                                                )}
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>
                        </div>
                    </div>
                )}
            </Modal>

            {/* Loading Overlay */}
            {loadingDetails && (
                <div className="fixed inset-0 bg-white/20 backdrop-blur-sm z-[100] flex items-center justify-center">
                    <div className="w-12 h-12 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin" />
                </div>
            )}
        </AppLayout>
    );
}

function AlumniCard({ member, onView }: { member: AlumniMember; onView: () => void }) {
    const initials = member.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();

    return (
        <div className="glass-card rounded-3xl p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div className="flex flex-col items-center text-center">
                <div className="relative mb-4">
                    <div className="w-20 h-20 rounded-full overflow-hidden bg-gradient-to-br from-emerald-400 to-teal-600 ring-4 ring-white shadow-lg flex items-center justify-center transition-transform group-hover:scale-105">
                        {member.avatar ? (
                            <img src={member.avatar} alt={member.name} className="w-full h-full object-cover" />
                        ) : (
                            <span className="font-display font-black text-2xl text-white">{initials}</span>
                        )}
                    </div>
                    <div className="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-white shadow-md flex items-center justify-center">
                        <Icon name="verified" className="text-emerald-500 text-sm" filled />
                    </div>
                </div>

                <h3 className="font-display font-bold text-on-surface leading-tight line-clamp-1">{member.name}</h3>
                <p className="text-[11px] text-emerald-600 font-black uppercase tracking-wider mt-1">
                    {member.asrama} · Angkatan {member.angkatan}
                </p>

                <div className="w-full h-px bg-slate-100 my-4" />

                <div className="w-full space-y-2.5">
                    <div className="flex items-center gap-2 text-xs">
                        <Icon name="work" className="text-slate-400 text-sm flex-shrink-0" />
                        <span className="text-on-surface font-medium truncate">
                            {member.pekerjaan || <span className="opacity-40 italic">Belum diset</span>}
                        </span>
                    </div>
                    {member.perusahaan && (
                        <div className="flex items-center gap-2 text-xs">
                            <Icon name="business" className="text-slate-400 text-sm flex-shrink-0" />
                            <span className="text-on-surface-variant truncate">{member.perusahaan}</span>
                        </div>
                    )}
                    <div className="flex items-center gap-2 text-xs">
                        <Icon name="location_on" className="text-slate-400 text-sm flex-shrink-0" />
                        <span className="text-on-surface-variant truncate">{member.lokasi}</span>
                    </div>
                </div>

                <button 
                    onClick={onView}
                    className="w-full mt-5 py-2.5 rounded-xl bg-slate-50 text-slate-900 font-bold text-xs hover:bg-emerald-500 hover:text-white transition-all flex items-center justify-center gap-2"
                >
                    Lihat Profil
                </button>
            </div>
        </div>
    );
}
