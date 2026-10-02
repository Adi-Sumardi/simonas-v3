import { Head, Link } from '@inertiajs/react';
import { Button } from '@/Components/ui/Button';
import { Icon } from '@/Components/ui/Icon';
import { ERROR_REPORT_WHATSAPP_URL } from '@/lib/adminContact';

interface Props {
    dashboardUrl?: string;
    status?: number;
}

// The exception handler renders this page for 403/419/500 too, not just 404.
const COPY: Record<number, { title: string; headline: string; body: string; badge: string }> = {
    404: { title: '404 — Not Found', headline: 'Oops! This page is not in our archives.', body: 'Di Digital Asrama YAPI pun, terkadang kita tersesat. Halaman yang kamu cari sepertinya tidak ditemukan.', badge: 'Lost' },
    403: { title: '403 — Akses Ditolak', headline: 'Kamu tidak punya akses ke halaman ini.', body: 'Akun kamu belum memiliki izin untuk membuka halaman ini. Jika menurutmu ini keliru, hubungi admin agar role/izin akunmu diperiksa.', badge: 'Locked' },
    419: { title: '419 — Sesi Kedaluwarsa', headline: 'Sesi kamu sudah kedaluwarsa.', body: 'Halaman terlalu lama terbuka sehingga sesi keamanan habis. Muat ulang halaman lalu coba lagi.', badge: 'Expired' },
    500: { title: '500 — Server Error', headline: 'Terjadi kesalahan di server.', body: 'Ada yang tidak beres di sistem kami. Coba lagi beberapa saat lagi; jika terus terjadi, laporkan ke admin.', badge: 'Error' },
};

export default function Error404({ dashboardUrl = '/', status = 404 }: Props) {
    const copy = COPY[status] ?? COPY[status >= 500 ? 500 : 404];
    return (
        <>
            <Head title={copy.title} />
            <header className="px-6 py-4 flex items-center gap-2">
                <div className="w-8 h-8 rounded-lg overflow-hidden">
                    <img src="/images/simonas_logo.png" alt="SIMONAS" className="w-full h-full object-cover" />
                </div>
                <span className="font-display text-title-sm text-primary-container">SIMONAS</span>
            </header>

            <main className="px-6 py-12 max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
                {/* Illustration */}
                <div className="relative flex justify-center">
                    <div className="glass-panel p-6 max-w-sm">
                        <div className="aspect-square rounded-xl bg-gradient-to-br from-surface-container-high to-surface-container-low flex items-center justify-center relative overflow-hidden">
                            <Icon name="local_library" className="text-8xl text-primary-container/40" filled />
                            <div className="absolute bottom-4 left-1/2 -translate-x-1/2 text-display-md font-display italic text-primary-container/70">
                                {copy.badge}
                            </div>
                        </div>
                        <div className="mt-4 status-pill-info inline-flex items-center gap-1">
                            <Icon name="cloud_done" className="text-base" />
                            Archive Error · Internet Found
                        </div>
                    </div>
                </div>

                {/* Text */}
                <div>
                    <div className="text-label-caps text-primary-container mb-3">System Notice</div>
                    <h1 className="font-display text-display-lg text-on-surface mb-4">
                        {copy.headline}
                    </h1>
                    <p className="text-body-md text-on-surface-variant mb-8">
                        {copy.body}
                    </p>

                    <div className="flex flex-wrap gap-3">
                        <Link href={dashboardUrl}>
                            <Button>
                                <Icon name="dashboard" />
                                Return to Dashboard
                            </Button>
                        </Link>
                        <a href={ERROR_REPORT_WHATSAPP_URL} target="_blank" rel="noopener noreferrer">
                            <Button variant="secondary">
                                <Icon name="chat" />
                                Kontak Admin
                            </Button>
                        </a>
                    </div>

                    <div className="mt-8 pt-6 border-t border-outline-variant/30 flex items-center gap-6 text-label-caps text-on-surface-variant">
                        <span className="flex items-center gap-1.5">
                            <span className="w-1.5 h-1.5 rounded-full bg-success" />
                            Network Active
                        </span>
                        <span className="flex items-center gap-1.5">
                            <span className="w-1.5 h-1.5 rounded-full bg-error" />
                            Error Code: {status}
                        </span>
                    </div>
                </div>
            </main>

            <footer className="px-6 py-6 text-center text-label-caps text-on-surface-variant">
                © 2026 SIMONAS Digital Asrama YAPI · All records are secured.
            </footer>
        </>
    );
}
