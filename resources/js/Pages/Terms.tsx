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

export default function Terms() {
    return (
        <PublicPageShell
            title="Syarat & Ketentuan"
            subtitle="Ketentuan penggunaan aplikasi SIMONAS Digital Asrama YAPI bagi seluruh pengguna."
            icon="gavel"
        >
            <PageSection title="Penerimaan Ketentuan" icon="handshake">
                <p>
                    Dengan mengakses dan menggunakan SIMONAS, pengguna (mahasiswa, mentor, admin, alumni,
                    maupun pengurus asrama) dianggap telah membaca, memahami, dan menyetujui seluruh
                    syarat & ketentuan yang berlaku di bawah ini.
                </p>
            </PageSection>

            <PageSection title="Akun Pengguna" icon="account_circle">
                <ul className="space-y-2.5 list-none pl-0">
                    <CheckItem>Setiap pengguna bertanggung jawab menjaga kerahasiaan email dan kata sandi akunnya sendiri.</CheckItem>
                    <CheckItem>Akun dibuat/diaktivasi oleh admin asrama atau melalui pendaftaran yang diverifikasi.</CheckItem>
                    <CheckItem>Segala aktivitas yang terjadi melalui akun pengguna menjadi tanggung jawab pemilik akun.</CheckItem>
                    <CheckItem>Login via Google hanya diperbolehkan untuk domain email yang diizinkan oleh admin.</CheckItem>
                </ul>
            </PageSection>

            <PageSection title="Ketentuan Penggunaan" icon="rule">
                <ul className="space-y-2.5 list-none pl-0">
                    <CheckItem>Data dan informasi yang dimasukkan (nilai, kegiatan, hafalan, dll) harus benar dan sesuai fakta.</CheckItem>
                    <CheckItem>Dilarang menyalahgunakan sistem untuk memalsukan data penilaian atau kegiatan.</CheckItem>
                    <CheckItem>File yang diunggah (bukti kegiatan) dibatasi format JPG/PNG/PDF dan ukuran maksimal tertentu.</CheckItem>
                    <CheckItem>Dilarang mengunggah konten yang melanggar hukum, SARA, atau tidak pantas.</CheckItem>
                </ul>
            </PageSection>

            <PageSection title="Hak & Kewajiban Pengelola" icon="admin_panel_settings">
                <ul className="space-y-2.5 list-none pl-0">
                    <CheckItem>Pengelola berhak menonaktifkan akun yang melanggar ketentuan penggunaan.</CheckItem>
                    <CheckItem>Pengelola berupaya menjaga ketersediaan layanan, namun tidak menjamin sistem bebas gangguan 100%.</CheckItem>
                    <CheckItem>Pengelola dapat memperbarui fitur dan ketentuan sewaktu-waktu dengan pemberitahuan melalui aplikasi.</CheckItem>
                </ul>
            </PageSection>

            <PageSection title="Perubahan Ketentuan" icon="update">
                <p>
                    Syarat & ketentuan ini dapat diperbarui sewaktu-waktu mengikuti perkembangan kebutuhan
                    dan kebijakan Asrama YAPI. Perubahan signifikan akan diinformasikan melalui aplikasi.
                </p>
            </PageSection>

            <PageSection title="Kontak" icon="mail">
                <p>
                    Pertanyaan seputar syarat & ketentuan dapat disampaikan melalui{' '}
                    <a href="/help-center" className="text-primary-container font-bold hover:underline">Pusat Bantuan</a>.
                </p>
            </PageSection>
        </PublicPageShell>
    );
}
