import { PublicPageShell, PageSection } from '@/Components/PublicPageShell';
import { Icon } from '@/Components/ui/Icon';

function CheckItem({ children }: { children: React.ReactNode }) {
    return (
        <li className="flex items-start gap-2.5">
            <Icon name="check_circle" className="text-base text-emerald-500 flex-shrink-0 mt-0.5" filled />
            <span>{children}</span>
        </li>
    );
}

export default function PrivacyPolicy() {
    return (
        <PublicPageShell
            title="Kebijakan Privasi"
            subtitle="Komitmen kami dalam melindungi privasi dan data pengguna SIMONAS Digital Asrama YAPI."
            icon="privacy_tip"
        >
            <PageSection title="Tentang SIMONAS" icon="info">
                <p>
                    SIMONAS (Sistem Informasi Monitoring Mahasiswa & Asrama) adalah aplikasi monitoring
                    Asrama YAPI yang digunakan untuk mendukung transparansi dan efektivitas pembinaan
                    mahasiswa, mentor, dan alumni di lingkungan Asrama YAPI.
                </p>
            </PageSection>

            <PageSection title="Informasi yang Kami Kumpulkan" icon="database">
                <ul className="space-y-2.5 list-none pl-0">
                    <CheckItem>Informasi pribadi (nama, email, nomor induk, kontak)</CheckItem>
                    <CheckItem>Data akademik dan riwayat pendidikan</CheckItem>
                    <CheckItem>Informasi kegiatan dan aktivitas asrama</CheckItem>
                    <CheckItem>Data penilaian, hafalan, dan monitoring perkembangan</CheckItem>
                    <CheckItem>Foto/dokumentasi bukti kegiatan yang diunggah pengguna</CheckItem>
                </ul>
            </PageSection>

            <PageSection title="Penggunaan Informasi" icon="task_alt">
                <ul className="space-y-2.5 list-none pl-0">
                    <CheckItem>Monitoring dan pencatatan kegiatan asrama</CheckItem>
                    <CheckItem>Evaluasi perkembangan akademik, karakter, dan kepemimpinan warga asrama</CheckItem>
                    <CheckItem>Peningkatan kualitas layanan dan pembinaan asrama</CheckItem>
                    <CheckItem>Komunikasi terkait program dan kegiatan asrama</CheckItem>
                </ul>
            </PageSection>

            <PageSection title="Keamanan Data" icon="lock">
                <div className="grid sm:grid-cols-2 gap-x-6 gap-2.5">
                    <ul className="space-y-2.5 list-none pl-0">
                        <CheckItem>Enkripsi data sensitif</CheckItem>
                        <CheckItem>Pembatasan akses berdasarkan peran (role-based access)</CheckItem>
                    </ul>
                    <ul className="space-y-2.5 list-none pl-0">
                        <CheckItem>Pemantauan keamanan secara berkala</CheckItem>
                        <CheckItem>Backup data rutin</CheckItem>
                    </ul>
                </div>
            </PageSection>

            <PageSection title="Hak Pengguna" icon="badge">
                <p>
                    Pengguna berhak mengakses, memperbarui, dan meminta koreksi atas data pribadinya sendiri
                    melalui halaman profil masing-masing. Permintaan penghapusan data dapat diajukan dengan
                    menghubungi admin melalui Pusat Bantuan.
                </p>
            </PageSection>

            <PageSection title="Kontak" icon="mail">
                <p>
                    Untuk pertanyaan terkait kebijakan privasi ini, silakan hubungi kami melalui{' '}
                    <a href="/help-center" className="text-primary-container font-bold hover:underline">Pusat Bantuan</a>.
                </p>
            </PageSection>
        </PublicPageShell>
    );
}
