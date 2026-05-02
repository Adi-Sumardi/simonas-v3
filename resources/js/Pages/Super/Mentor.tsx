import { useState, useEffect } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { Head, router, Link } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { PageHeader } from '@/Components/ui/PageHeader';
import { useDebounce } from '@/hooks/useDebounce';

interface Mentee {
    id: number;
    name: string;
    asrama: string;
    avatar?: string;
}

interface MentorItem {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    asrama: string;
    no_telp?: string;
    is_active: boolean;
    last_login: string;
    last_activity: string;
    total_nilai: number;
    categories: string[];
    mentee_count: number;
    mentees: Mentee[];
}

interface Props {
    mentors: {
        data: MentorItem[];
        current_page: number;
        last_page: number;
        total: number;
    };
    stats: {
        total: number;
        active: number;
    };
    filters: {
        search?: string;
    };
}

export default function Mentor({ mentors, stats, filters }: Props) {
    const [search, setSearch] = useState(filters.search || '');
    const [selectedMentor, setSelectedMentor] = useState<MentorItem | null>(null);

    const debouncedSearch = useDebounce(search, 500);

    useEffect(() => {
        router.get('/super/mentor', { search: debouncedSearch }, {
            preserveState: true,
            replace: true
        });
    }, [debouncedSearch]);

    return (
        <AppLayout>
            <Head title="Manajemen Mentor" />

            <PageHeader 
                title="Manajemen Mentor" 
                subtitle="Pantau aktivitas dan bimbingan mentor terhadap mentee"
            />

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div className="glass-card p-5 rounded-2xl flex items-center gap-4">
                    <div className="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                        <Icon name="groups" className="text-2xl" />
                    </div>
                    <div>
                        <p className="text-label-caps text-on-surface-variant">TOTAL MENTOR</p>
                        <h3 className="text-2xl font-display font-black text-on-surface">{stats.total}</h3>
                    </div>
                </div>
                <div className="glass-card p-5 rounded-2xl flex items-center gap-4">
                    <div className="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <Icon name="bolt" className="text-2xl" />
                    </div>
                    <div>
                        <p className="text-label-caps text-on-surface-variant">MENTOR AKTIF</p>
                        <h3 className="text-2xl font-display font-black text-on-surface">{stats.active}</h3>
                    </div>
                </div>
            </div>

            <div className="glass-card rounded-2xl overflow-hidden mb-8">
                <div className="p-5 border-b border-white/40 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div className="flex items-center gap-3 bg-white/20 px-4 py-2 rounded-xl w-full sm:w-80 border border-white/40">
                        <Icon name="search" className="text-xl text-on-surface-variant" />
                        <input
                            type="text"
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            placeholder="Cari nama mentor..."
                            className="bg-transparent outline-none flex-1 text-sm placeholder:text-on-surface-variant/60"
                        />
                    </div>
                    <span className="text-label-caps text-on-surface-variant">{mentors.total} mentors found</span>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-white/10 border-b border-white/40">
                                <th className="px-6 py-4 text-label-caps text-on-surface-variant">Mentor</th>
                                <th className="px-6 py-4 text-label-caps text-on-surface-variant">Login Terakhir</th>
                                <th className="px-6 py-4 text-label-caps text-on-surface-variant">Penilaian</th>
                                <th className="px-6 py-4 text-label-caps text-on-surface-variant">Mentee</th>
                                <th className="px-6 py-4 text-label-caps text-on-surface-variant text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-white/40">
                            {mentors.data.map((m) => (
                                <tr key={m.id} className="hover:bg-white/20 transition-colors group">
                                    <td className="px-6 py-4">
                                        <Link href={`/super/mentor/${m.id}/analysis`} className="flex items-center gap-3 group/item">
                                            <div className="w-10 h-10 rounded-full bg-surface-container overflow-hidden flex-shrink-0 group-hover/item:ring-2 ring-primary-container transition-all">
                                                {m.avatar ? (
                                                    <img src={m.avatar} alt={m.name} className="w-full h-full object-cover" />
                                                ) : (
                                                    <div className="w-full h-full flex items-center justify-center bg-blue-500 text-white font-bold text-sm">
                                                        {m.name.charAt(0)}
                                                    </div>
                                                )}
                                            </div>
                                            <div>
                                                <p className="font-bold text-on-surface text-sm group-hover/item:text-primary-container transition-colors">{m.name}</p>
                                                <p className="text-[11px] text-on-surface-variant">{m.asrama}</p>
                                            </div>
                                        </Link>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex flex-col">
                                            <span className="text-xs font-bold text-on-surface">{m.last_login}</span>
                                            <span className="text-[10px] text-on-surface-variant">Status: {m.is_active ? 'Aktif' : 'Pasif'}</span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex flex-col gap-1">
                                            <div className="flex items-center gap-2">
                                                <span className="text-sm font-black text-primary-container">{m.total_nilai}</span>
                                                <span className="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider">Entries</span>
                                            </div>
                                            <div className="flex flex-wrap gap-1">
                                                {m.categories.slice(0, 2).map(cat => (
                                                    <span key={cat} className="text-[9px] px-1.5 py-0.5 rounded-md bg-surface-container text-on-surface-variant font-bold">
                                                        {cat}
                                                    </span>
                                                ))}
                                                {m.categories.length > 2 && <span className="text-[9px] text-on-surface-variant">+{m.categories.length - 2}</span>}
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex -space-x-2 overflow-hidden">
                                            {m.mentees.slice(0, 4).map((mentee) => (
                                                <div key={mentee.id} title={mentee.name} className="inline-block h-8 w-8 rounded-full ring-2 ring-white bg-surface-container overflow-hidden">
                                                    {mentee.avatar ? (
                                                        <img src={mentee.avatar} alt={mentee.name} />
                                                    ) : (
                                                        <div className="w-full h-full flex items-center justify-center bg-slate-200 text-[10px] font-bold">
                                                            {mentee.name.charAt(0)}
                                                        </div>
                                                    )}
                                                </div>
                                            ))}
                                            {m.mentee_count > 4 && (
                                                <div className="flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white bg-blue-100 text-[10px] font-bold text-blue-600">
                                                    +{m.mentee_count - 4}
                                                </div>
                                            )}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <Link 
                                            href={`/super/mentor/${m.id}/analysis`}
                                            className="p-2 rounded-lg bg-white/40 hover:bg-white/80 text-primary-container transition-colors inline-flex"
                                            title="Analisis Performa"
                                        >
                                            <Icon name="analytics" className="text-lg" />
                                        </Link>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {mentors.data.length === 0 && (
                    <div className="p-20 text-center">
                        <Icon name="search_off" className="text-6xl text-on-surface-variant/20 mb-4" />
                        <p className="text-on-surface-variant">Tidak ada data mentor yang ditemukan.</p>
                    </div>
                )}
            </div>

            {/* Mentor Detail Modal */}
            {selectedMentor && (
                <div 
                    className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
                    onClick={e => e.target === e.currentTarget && setSelectedMentor(null)}
                >
                    <div className="glass-card rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                        <div className="flex items-center justify-between px-6 py-4 border-b border-white/40 bg-white/20">
                            <h3 className="font-bold text-on-surface">Detail Mentorship</h3>
                            <button onClick={() => setSelectedMentor(null)} className="w-8 h-8 rounded-lg bg-white/40 hover:bg-white/80 flex items-center justify-center transition-colors">
                                <Icon name="close" className="text-on-surface-variant" />
                            </button>
                        </div>
                        <div className="p-6 overflow-y-auto">
                            <div className="flex flex-col sm:flex-row gap-6 mb-8 items-center sm:items-start text-center sm:text-left">
                                <div className="w-24 h-24 rounded-2xl bg-surface-container overflow-hidden shadow-lg flex-shrink-0">
                                    {selectedMentor.avatar ? (
                                        <img src={selectedMentor.avatar} alt={selectedMentor.name} className="w-full h-full object-cover" />
                                    ) : (
                                        <div className="w-full h-full flex items-center justify-center bg-blue-500 text-white font-bold text-3xl">
                                            {selectedMentor.name.charAt(0)}
                                        </div>
                                    )}
                                </div>
                                <div className="flex-1">
                                    <h4 className="text-xl font-display font-black text-on-surface mb-1">{selectedMentor.name}</h4>
                                    <p className="text-on-surface-variant mb-3">{selectedMentor.email}</p>
                                    <div className="flex flex-wrap gap-2 justify-center sm:justify-start">
                                        <span className="px-3 py-1 rounded-lg bg-blue-100 text-blue-600 text-[11px] font-bold flex items-center gap-1">
                                            <Icon name="home" className="text-xs" /> {selectedMentor.asrama}
                                        </span>
                                        <span className="px-3 py-1 rounded-lg bg-surface-container text-on-surface-variant text-[11px] font-bold flex items-center gap-1">
                                            <Icon name="phone" className="text-xs" /> {selectedMentor.no_telp || '-'}
                                        </span>
                                    </div>
                                </div>
                                <div className="p-4 rounded-2xl bg-white/40 border border-white/40 text-center min-w-[140px]">
                                    <p className="text-[10px] text-on-surface-variant uppercase font-black mb-1">Evaluasi Mentor</p>
                                    <div className="space-y-2">
                                        <div className="flex items-center justify-between gap-4">
                                            <span className="text-[10px] text-on-surface-variant">Total Penilaian:</span>
                                            <span className="text-sm font-black text-primary-container">{selectedMentor.total_nilai}</span>
                                        </div>
                                        <div className="flex items-center justify-between gap-4">
                                            <span className="text-[10px] text-on-surface-variant">Login Terakhir:</span>
                                            <span className="text-[10px] font-bold text-on-surface text-right">{selectedMentor.last_login}</span>
                                        </div>
                                    </div>
                                    <div className="mt-3 flex flex-wrap gap-1 justify-center">
                                        {selectedMentor.categories.map(cat => (
                                            <span key={cat} className="text-[9px] px-2 py-0.5 rounded-full bg-blue-500/10 text-blue-600 font-bold border border-blue-200">
                                                {cat}
                                            </span>
                                        ))}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h5 className="text-label-caps text-on-surface-variant mb-4 flex items-center gap-2">
                                    <Icon name="groups" className="text-lg" /> Daftar Mentee ({selectedMentor.mentee_count})
                                </h5>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    {selectedMentor.mentees.map((mentee) => (
                                        <div key={mentee.id} className="p-3 rounded-xl bg-white/20 border border-white/40 flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-full bg-surface-container overflow-hidden flex-shrink-0">
                                                {mentee.avatar ? (
                                                    <img src={mentee.avatar} alt={mentee.name} className="w-full h-full object-cover" />
                                                ) : (
                                                    <div className="w-full h-full flex items-center justify-center bg-slate-200 text-slate-500 font-bold text-xs">
                                                        {mentee.name.charAt(0)}
                                                    </div>
                                                )}
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="text-sm font-bold text-on-surface truncate">{mentee.name}</p>
                                                <p className="text-[10px] text-on-surface-variant">{mentee.asrama}</p>
                                            </div>
                                            <button 
                                                onClick={() => router.get('/super/warga', { search: mentee.name })}
                                                className="w-8 h-8 rounded-lg hover:bg-white/60 flex items-center justify-center text-primary-container transition-colors"
                                            >
                                                <Icon name="arrow_forward" className="text-lg" />
                                            </button>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AppLayout>
    );
}
