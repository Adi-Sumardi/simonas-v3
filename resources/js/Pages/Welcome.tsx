import { useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';

interface StatItem {
    raw: number;
    formatted: string;
}

interface Props {
    appName?: string;
    version?: string;
    stats?: {
        mahasiswa: StatItem;
        mentor: StatItem;
        asrama: StatItem;
        program: StatItem;
        avgScore: string;
    };
}

export default function Welcome({ version, stats }: Props) {
    // Auto-redirect to login if running as an installed PWA (standalone)
    useEffect(() => {
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches 
            || (window.navigator as any).standalone 
            || document.referrer.includes('android-keystore');

        if (isStandalone) {
            window.location.href = '/login';
        }
    }, []);

    const statList = [
        { label: 'Mahasiswa Aktif', value: stats?.mahasiswa?.formatted ?? '94', icon: 'groups' },
        { label: 'Mentor Berpengalaman', value: stats?.mentor?.formatted ?? '7', icon: 'supervisor_account' },
        { label: 'Asrama Terintegrasi', value: stats?.asrama?.formatted ?? '4', icon: 'apartment' },
        { label: 'Program Unggulan', value: stats?.program?.formatted ?? '3.2k+', icon: 'verified' },
    ];

    return (
        <div className="min-h-screen bg-serene-gradient selection:bg-primary/20 selection:text-primary">
            <Head title="SIMONAS - Digital Asrama YAPI" />

            {/* Navigation */}
            <nav className="fixed top-0 left-0 w-full z-50 bg-white/60 backdrop-blur-xl border-b border-white/40">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-20">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-xl overflow-hidden shadow-glow">
                                <img src="/images/simonas_logo.png" alt="SIMONAS" className="w-full h-full object-cover" />
                            </div>
                            <span className="font-display text-xl font-black text-primary tracking-tight">SIMONAS</span>
                        </div>
                        <div className="hidden md:flex items-center gap-8">
                            <a href="#fitur" className="text-sm font-semibold text-secondary hover:text-primary transition-colors">Fitur</a>
                            <a href="#manfaat" className="text-sm font-semibold text-secondary hover:text-primary transition-colors">Manfaat</a>
                            <a href="#statistik" className="text-sm font-semibold text-secondary hover:text-primary transition-colors">Statistik</a>
                        </div>
                        <div className="flex items-center gap-4">
                            <Link href="/login" className="btn-primary flex items-center gap-2">
                                Masuk Portal <Icon name="login" className="text-lg" />
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <section className="pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                        <div className="relative z-10 text-center lg:text-left">
                            <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-widest mb-6 border border-blue-200">
                                <span className="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse" />
                                Monitoring Sistem Terintegrasi
                            </div>
                            <h1 className="font-display text-display-md sm:text-display-lg lg:text-[64px] lg:leading-[72px] text-on-surface mb-6 font-black tracking-tight">
                                Membentuk Generasi Unggul dalam <span className="text-primary-container">Harmoni Akademik.</span>
                            </h1>
                            <p className="text-body-md sm:text-headline-md text-on-surface-variant mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium">
                                Sistem pemantauan perkembangan mahasiswa yang transparan, terintegrasi, dan berorientasi pada pembentukan karakter serta prestasi.
                            </p>
                            <div className="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                                <Link href="/login" className="w-full sm:w-auto px-8 py-4 bg-primary text-on-primary rounded-2xl shadow-glow font-bold text-lg hover:scale-105 active:scale-95 transition-all text-center">
                                    Mulai Sekarang
                                </Link>
                                <a href="#fitur" className="w-full sm:w-auto px-8 py-4 bg-white/60 backdrop-blur border-2 border-primary/20 text-primary rounded-2xl font-bold text-lg hover:bg-white transition-all text-center">
                                    Pelajari Fitur
                                </a>
                            </div>
                        </div>

                        <div className="relative">
                            {/* Decorative Blobs */}
                            <div className="absolute -top-20 -right-20 w-64 h-64 bg-blue-400/20 rounded-full blur-[100px]" />
                            <div className="absolute -bottom-20 -left-20 w-64 h-64 bg-purple-400/20 rounded-full blur-[100px]" />
                            
                            <div className="glass-panel p-3 rounded-[32px] rotate-2 hover:rotate-0 transition-transform duration-700 shadow-2xl relative z-10">
                                <img 
                                    src="/images/hero_landing.png" 
                                    alt="SIMONAS Illustration" 
                                    className="w-full h-auto rounded-[24px] shadow-inner"
                                />
                                
                                {/* Floating Stat Card */}
                                <div className="absolute -bottom-10 -left-10 hidden sm:block animate-bounce-slow">
                                    <div className="glass-card p-5 rounded-2xl shadow-xl flex items-center gap-4">
                                        <div className="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                                            <Icon name="trending_up" className="text-2xl" filled />
                                        </div>
                                        <div>
                                            <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Progress Evaluasi</p>
                                            <p className="text-xl font-bold text-on-surface">{stats?.avgScore ?? '79.0%'} Rata-rata</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Stats Section */}
            <section id="statistik" className="py-20 bg-white/40 border-y border-white/40">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
                        {statList.map((stat, i) => (
                            <div key={i} className="text-center group">
                                <div className="w-14 h-14 bg-white/80 rounded-2xl flex items-center justify-center text-primary-container mx-auto mb-4 shadow-sm group-hover:scale-110 transition-transform">
                                    <Icon name={stat.icon} className="text-2xl" />
                                </div>
                                <p className="text-3xl font-black text-on-surface mb-1">{stat.value}</p>
                                <p className="text-xs font-bold text-on-surface-variant uppercase tracking-widest">{stat.label}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Features Section */}
            <section id="fitur" className="py-32">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-20">
                        <h2 className="font-display text-display-md text-on-surface mb-4 font-black">Fitur Unggulan SIMONAS</h2>
                        <p className="text-headline-md text-on-surface-variant max-w-2xl mx-auto">
                            Segala kebutuhan monitoring dan pengembangan mahasiswa dalam satu genggaman.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        {[
                            { 
                                title: 'Monitoring Akademik', 
                                desc: 'Pelacakan IPK, KRS, dan kehadiran secara digital dan transparan untuk setiap semester.', 
                                icon: 'analytics', 
                                color: 'blue' 
                            },
                            { 
                                title: 'Pembinaan Karakter', 
                                desc: 'Pencatatan aktivitas organisasi, kepemimpinan, dan kedisiplinan yang terukur.', 
                                icon: 'military_tech', 
                                color: 'emerald' 
                            },
                            { 
                                title: 'Database Alumni', 
                                desc: 'Jaringan alumni yang kuat untuk mendukung karir dan kolaborasi lintas generasi.', 
                                icon: 'hub', 
                                color: 'purple' 
                            },
                            { 
                                title: 'Hafalan & Quran', 
                                desc: 'Manajemen setoran hafalan harian dengan sistem review langsung oleh mentor.', 
                                icon: 'auto_stories', 
                                color: 'amber' 
                            },
                            { 
                                title: 'Manajemen Asrama', 
                                desc: 'Kontrol kapasitas dan database penghuni asrama yang rapi dan selalu terupdate.', 
                                icon: 'meeting_room', 
                                color: 'teal' 
                            },
                            { 
                                title: 'Laporan Otomatis', 
                                desc: 'Generate laporan perkembangan periodik hanya dengan satu klik untuk evaluasi.', 
                                icon: 'description', 
                                color: 'rose' 
                            },
                        ].map((f, i) => (
                            <div key={i} className="glass-card p-8 rounded-3xl group hover:-translate-y-2 transition-all duration-300">
                                <div className={`w-14 h-14 rounded-2xl flex items-center justify-center mb-6 shadow-sm
                                    ${f.color === 'blue' ? 'bg-blue-100 text-blue-600' :
                                      f.color === 'emerald' ? 'bg-emerald-100 text-emerald-600' :
                                      f.color === 'purple' ? 'bg-purple-100 text-purple-600' :
                                      f.color === 'amber' ? 'bg-amber-100 text-amber-600' :
                                      f.color === 'teal' ? 'bg-teal-100 text-teal-600' :
                                      'bg-rose-100 text-rose-600'}`}>
                                    <Icon name={f.icon} className="text-3xl" filled />
                                </div>
                                <h3 className="text-xl font-bold text-on-surface mb-3">{f.title}</h3>
                                <p className="text-body-sm text-on-surface-variant leading-relaxed">{f.desc}</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-20">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-primary-container rounded-[40px] p-10 md:p-20 text-center relative overflow-hidden shadow-glow">
                        <div className="absolute top-0 right-0 p-10 opacity-10 pointer-events-none">
                            <Icon name="school" className="text-[200px]" />
                        </div>
                        <h2 className="text-display-md font-display text-white mb-6 font-black">Siap Untuk Berkembang?</h2>
                        <p className="text-headline-md text-blue-100 mb-10 max-w-xl mx-auto">
                            Bergabunglah dengan ribuan mahasiswa lainnya dan mulai pantau perkembangan akademikmu hari ini.
                        </p>
                        <Link href="/login" className="px-10 py-5 bg-white text-primary-container rounded-2xl font-black text-xl shadow-xl hover:scale-105 active:scale-95 transition-all inline-block">
                            Masuk ke Dashboard
                        </Link>
                    </div>
                </div>
            </section>

            {/* Footer */}
            <footer className="bg-surface-container-highest py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12 border-b border-on-surface/10 pb-12">
                        <div className="col-span-1 md:col-span-2">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="w-10 h-10 rounded-xl overflow-hidden shadow-glow">
                                    <img src="/images/simonas_logo.png" alt="SIMONAS" className="w-full h-full object-cover" />
                                </div>
                                <span className="font-display text-2xl font-black text-primary tracking-tight">SIMONAS</span>
                            </div>
                            <p className="text-on-surface-variant max-w-sm mb-6 leading-relaxed">
                                Sistem Informasi Monitoring Mahasiswa & Asrama (SIMONAS) adalah platform terpadu untuk mendukung transparansi dan efektivitas pembinaan mahasiswa.
                            </p>
                            <div className="flex gap-4">
                                <a href="#" className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary-container hover:bg-primary-container hover:text-white transition-all shadow-sm">
                                    <Icon name="facebook" className="text-xl" />
                                </a>
                                <a href="#" className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary-container hover:bg-primary-container hover:text-white transition-all shadow-sm">
                                    <Icon name="camera_alt" className="text-xl" />
                                </a>
                                <a href="#" className="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary-container hover:bg-primary-container hover:text-white transition-all shadow-sm">
                                    <Icon name="alternate_email" className="text-xl" />
                                </a>
                            </div>
                        </div>
                        <div>
                            <h4 className="font-black text-on-surface uppercase tracking-widest text-xs mb-6">Navigasi</h4>
                            <ul className="space-y-4">
                                <li><a href="#" className="text-on-surface-variant hover:text-primary transition-colors text-sm font-medium">Beranda</a></li>
                                <li><a href="#fitur" className="text-on-surface-variant hover:text-primary transition-colors text-sm font-medium">Fitur Utama</a></li>
                                <li><a href="#statistik" className="text-on-surface-variant hover:text-primary transition-colors text-sm font-medium">Laporan Statistik</a></li>
                                <li><a href="/login" className="text-on-surface-variant hover:text-primary transition-colors text-sm font-medium">Login Portal</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-black text-on-surface uppercase tracking-widest text-xs mb-6">Legalitas</h4>
                            <ul className="space-y-4">
                                <li><a href="/privacy-policy" className="text-on-surface-variant hover:text-primary transition-colors text-sm font-medium">Kebijakan Privasi</a></li>
                                <li><a href="#" className="text-on-surface-variant hover:text-primary transition-colors text-sm font-medium">Syarat & Ketentuan</a></li>
                                <li><a href="#" className="text-on-surface-variant hover:text-primary transition-colors text-sm font-medium">Pusat Bantuan</a></li>
                            </ul>
                        </div>
                    </div>
                    <div className="flex flex-col md:flex-row justify-between items-center gap-4 text-on-surface-variant text-[11px] font-bold uppercase tracking-[0.2em]">
                        <p>© 2024 SIMONAS DIGITAL ASRAMA YAPI. ALL RIGHTS RESERVED.</p>
                        <p>POWERED BY LARAVEL {version}</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
