import { Head } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';

interface PortfolioProps {
    user: {
        name: string;
        nim: string;
        avatar: string | null;
        asrama: string;
        angkatan: string;
        bio?: string;
        no_hp?: string;
        prodi?: string;
        universitas?: string;
    };
    riwayats: Record<string, any[]>;
    activities: Record<string, any[]>;
    hafalan: {
        summary: any;
        logs: any[];
    };
}

export default function Portfolio({ user, riwayats, activities, hafalan }: PortfolioProps) {
    const handlePrint = () => {
        window.print();
    };

    return (
        <div className="min-h-screen bg-zinc-50 py-10 print:py-0 print:bg-white selection:bg-primary/20">
            <Head title={`Portofolio - ${user.name}`} />

            {/* Controls */}
            <div className="max-w-[210mm] mx-auto mb-6 flex justify-between items-center px-4 print:hidden">
                <button 
                    onClick={() => window.history.back()}
                    className="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors font-semibold"
                >
                    <Icon name="arrow_back" /> Kembali
                </button>
                <button 
                    onClick={handlePrint}
                    className="btn-primary flex items-center gap-2 shadow-lg"
                >
                    <Icon name="print" /> Cetak Portofolio (PDF)
                </button>
            </div>

            {/* Main CV Container (A4 style) */}
            <div className="max-w-[210mm] mx-auto bg-white shadow-2xl print:shadow-none min-h-[297mm] relative overflow-hidden flex flex-col">
                
                {/* Header Section */}
                <header className="bg-primary-container text-white p-12 flex items-center gap-10 relative overflow-hidden">
                    {/* Decorative Elements */}
                    <div className="absolute top-0 right-0 p-10 opacity-10 pointer-events-none select-none">
                        <Icon name="school" className="text-[200px]" />
                    </div>

                    <div className="w-40 h-40 rounded-3xl overflow-hidden border-4 border-white/30 shadow-xl flex-shrink-0 z-10">
                        {user.avatar ? (
                            <img src={user.avatar} className="w-full h-full object-cover" alt={user.name} />
                        ) : (
                            <div className="w-full h-full bg-white/20 flex items-center justify-center text-5xl font-black">
                                {user.name.charAt(0)}
                            </div>
                        )}
                    </div>

                    <div className="flex-1 z-10">
                        <h1 className="text-4xl font-black tracking-tight mb-2 uppercase">{user.name}</h1>
                        <p className="text-lg font-medium opacity-90 mb-4">{user.prodi || 'Mahasiswa'} • {user.universitas || 'SIMONAS Academic Sanctuary'}</p>
                        
                        <div className="flex flex-wrap gap-4 text-sm font-semibold">
                            <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full">
                                <Icon name="badge" className="text-base" /> {user.nim}
                            </div>
                            <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full">
                                <Icon name="home" className="text-base" /> Asrama {user.asrama}
                            </div>
                            {user.no_hp && (
                                <div className="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-full">
                                    <Icon name="call" className="text-base" /> {user.no_hp}
                                </div>
                            )}
                        </div>
                    </div>
                </header>

                <div className="flex-1 flex flex-col md:flex-row print:flex-row">
                    {/* Left Sidebar */}
                    <aside className="w-full md:w-1/3 print:w-1/3 bg-zinc-50/50 border-r border-zinc-100 p-8 space-y-10">
                        
                        {/* Bio */}
                        {user.bio && (
                            <section>
                                <h3 className="text-xs font-black text-primary tracking-[0.2em] uppercase mb-4">Profil</h3>
                                <p className="text-sm text-on-surface-variant leading-relaxed text-justify">{user.bio}</p>
                            </section>
                        )}

                        {/* Hafalan Progress */}
                        <section>
                            <h3 className="text-xs font-black text-primary tracking-[0.2em] uppercase mb-4">Hafalan Quran</h3>
                            <div className="bg-white p-4 rounded-2xl shadow-sm border border-zinc-100">
                                <div className="flex justify-between items-end mb-2">
                                    <p className="text-xl font-black text-on-surface">Juz {hafalan.summary?.current_juz || 0}</p>
                                    <p className="text-[10px] font-bold text-primary-container">{hafalan.summary?.progress_percent || 0}% Progress</p>
                                </div>
                                <div className="w-full h-1.5 bg-zinc-100 rounded-full overflow-hidden">
                                    <div className="h-full bg-primary-container" style={{ width: `${hafalan.summary?.progress_percent || 0}%` }} />
                                </div>
                                <div className="mt-4 flex flex-wrap gap-2">
                                    {hafalan.logs?.slice(0, 5).map((log, i) => (
                                        <span key={i} className="text-[10px] bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded font-bold border border-emerald-100">
                                            Surah {log.surah}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        </section>

                        {/* Disiplin Shalat */}
                        <section>
                            <h3 className="text-xs font-black text-primary tracking-[0.2em] uppercase mb-4">Disiplin Shalat</h3>
                            <div className="space-y-3">
                                <div className="flex items-center justify-between">
                                    <span className="text-xs font-semibold text-on-surface-variant">Tingkat Kedisiplinan</span>
                                    <span className="text-xs font-black text-primary">Sangat Baik</span>
                                </div>
                                <div className="grid grid-cols-5 gap-1">
                                    {[1, 2, 3, 4, 5].map(i => (
                                        <div key={i} className="h-1 bg-emerald-500 rounded-full" />
                                    ))}
                                </div>
                            </div>
                        </section>
                    </aside>

                    {/* Main Content Area */}
                    <main className="flex-1 p-10 space-y-12">
                        
                        {/* Riwayats - Education */}
                        {riwayats.pendidikan && (
                            <section>
                                <div className="flex items-center gap-3 mb-6">
                                    <div className="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                        <Icon name="history_edu" className="text-lg" />
                                    </div>
                                    <h3 className="text-lg font-black text-on-surface uppercase tracking-tight">Pendidikan</h3>
                                </div>
                                <div className="space-y-6 border-l-2 border-zinc-100 ml-4 pl-8 relative">
                                    {riwayats.pendidikan.map((r, i) => (
                                        <div key={i} className="relative">
                                            <div className="absolute -left-[41px] top-1.5 w-4 h-4 bg-white border-2 border-primary rounded-full" />
                                            <p className="text-[10px] font-black text-primary-container uppercase tracking-widest mb-1">
                                                {r.mulai} — {r.masih_berlangsung ? 'Saat Ini' : r.selesai}
                                            </p>
                                            <h4 className="font-bold text-on-surface">{r.judul}</h4>
                                            <p className="text-sm text-secondary font-medium">{r.posisi}</p>
                                            {r.deskripsi && <p className="text-xs text-on-surface-variant mt-2 leading-relaxed">{r.deskripsi}</p>}
                                        </div>
                                    ))}
                                </div>
                            </section>
                        )}

                        {/* Aktivitas Terkini */}
                        <section>
                            <div className="flex items-center gap-3 mb-6">
                                <div className="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                                    <Icon name="stars" className="text-lg" />
                                </div>
                                <h3 className="text-lg font-black text-on-surface uppercase tracking-tight">Aktivitas & Karakter</h3>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                {Object.entries(activities).map(([key, items]) => (
                                    <div key={key} className="p-4 bg-zinc-50 rounded-2xl border border-zinc-100">
                                        <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-3 border-b border-zinc-200 pb-2">{key}</p>
                                        <ul className="space-y-2">
                                            {items.slice(0, 3).map((it, i) => (
                                                <li key={i} className="text-xs flex items-start gap-2">
                                                    <Icon name="check_circle" className="text-emerald-500 text-[14px] mt-0.5" />
                                                    <span className="font-bold text-on-surface leading-tight">{it.kegiatan || it.nama_kegiatan}</span>
                                                </li>
                                            ))}
                                            {items.length === 0 && <li className="text-[10px] italic text-on-surface-variant/50">Belum ada catatan</li>}
                                        </ul>
                                    </div>
                                ))}
                            </div>
                        </section>

                        {/* Pengalaman & Organisasi */}
                        {(riwayats.organisasi || riwayats.pekerjaan) && (
                            <section>
                                <div className="flex items-center gap-3 mb-6">
                                    <div className="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                                        <Icon name="hub" className="text-lg" />
                                    </div>
                                    <h3 className="text-lg font-black text-on-surface uppercase tracking-tight">Pengalaman & Organisasi</h3>
                                </div>
                                <div className="space-y-6">
                                    {[...(riwayats.organisasi || []), ...(riwayats.pekerjaan || [])].slice(0, 5).map((r, i) => (
                                        <div key={i} className="flex gap-4 group">
                                            <div className="w-12 text-right flex-shrink-0">
                                                <p className="text-[10px] font-bold text-on-surface-variant leading-tight">{r.mulai}</p>
                                            </div>
                                            <div className="flex-1">
                                                <h4 className="text-sm font-black text-on-surface leading-none group-hover:text-primary transition-colors">{r.judul}</h4>
                                                <p className="text-[11px] font-bold text-primary-container mt-1">{r.posisi}</p>
                                                {r.deskripsi && <p className="text-xs text-on-surface-variant mt-2 leading-relaxed">{r.deskripsi}</p>}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </section>
                        )}
                    </main>
                </div>

                {/* Footer Certification */}
                <footer className="mt-auto p-12 bg-zinc-900 text-white flex justify-between items-end border-t-8 border-primary-container">
                    <div>
                        <div className="flex items-center gap-2 mb-3">
                            <Icon name="school" className="text-2xl text-primary-container" />
                            <span className="font-display font-black text-xl tracking-tight">SIMONAS</span>
                        </div>
                        <p className="text-[10px] font-bold opacity-40 uppercase tracking-widest leading-loose">
                            Dokumen ini di-generate secara otomatis oleh Sistem Informasi <br />
                            Monitoring Mahasiswa & Asrama (SIMONAS) Academic Sanctuary.
                        </p>
                    </div>
                    <div className="text-right">
                        <p className="text-xs font-bold mb-1 opacity-60">Dikeluarkan pada:</p>
                        <p className="text-sm font-black">{new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</p>
                    </div>
                </footer>
            </div>

            {/* Custom CSS for print */}
            <style dangerouslySetInnerHTML={{ __html: `
                @media print {
                    @page { size: A4; margin: 0; }
                    body { -webkit-print-color-adjust: exact; }
                    .print\\:hidden { display: none !important; }
                }
                .custom-scrollbar::-webkit-scrollbar { width: 4px; }
                .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
                .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
            `}} />
        </div>
    );
}
