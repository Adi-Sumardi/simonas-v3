import { useState } from 'react';
import { PublicPageShell, PageSection } from '@/Components/PublicPageShell';
import { Icon } from '@/Components/ui/Icon';
import { ERROR_REPORT_WHATSAPP_URL, adminWhatsappUrl } from '@/lib/adminContact';

const FAQS = [
    {
        q: 'Bagaimana cara login ke SIMONAS?',
        a: 'Gunakan email & password yang diberikan admin asrama, atau klik "Login dengan Google" jika akun Google-mu sudah terdaftar. Lupa password? Hubungi admin asrama untuk direset.',
    },
    {
        q: 'Kenapa saya tidak bisa mengunggah foto bukti kegiatan?',
        a: 'Pastikan file berformat JPG, PNG, atau PDF dan berukuran maksimal 20MB. Format lain (mis. HEIC dari iPhone, atau video) belum didukung — konversi dulu ke JPG/PNG sebelum diunggah.',
    },
    {
        q: 'Poin/skor saya tidak muncul atau tidak sesuai, kenapa?',
        a: 'Poin dihitung otomatis dari data akademik, leadership, karakter, kreativitas, hafalan, dan shalat yang tercatat di sistem. Jika ada kegiatan yang belum tercatat atau salah, hubungi mentor/admin asrama untuk verifikasi.',
    },
    {
        q: 'Bagaimana cara mengganti data profil (asrama, angkatan, dll)?',
        a: 'Data profil dasar bisa diubah sendiri lewat halaman Profil. Untuk perubahan status warga, asrama, atau nomor induk, hubungi admin karena butuh verifikasi.',
    },
    {
        q: 'Saya menemukan bug atau tampilan error, harus lapor ke mana?',
        a: 'Klik tombol "Chat Admin via WhatsApp" di bawah, jelaskan menu yang error dan lampirkan screenshot supaya lebih cepat ditindaklanjuti.',
    },
];

function FaqItem({ q, a }: { q: string; a: string }) {
    const [open, setOpen] = useState(false);
    return (
        <div className="border border-outline-variant/30 rounded-2xl overflow-hidden bg-white/40">
            <button
                onClick={() => setOpen(v => !v)}
                className="w-full flex items-center justify-between gap-4 px-5 py-4 text-left"
            >
                <span className="font-bold text-on-surface text-sm">{q}</span>
                <Icon name={open ? 'expand_less' : 'expand_more'} className="text-xl text-on-surface-variant flex-shrink-0" />
            </button>
            {open && (
                <div className="px-5 pb-4 text-sm text-on-surface-variant leading-relaxed">{a}</div>
            )}
        </div>
    );
}

export default function HelpCenter() {
    return (
        <PublicPageShell
            title="Pusat Bantuan"
            subtitle="Punya pertanyaan atau menemukan masalah di SIMONAS? Cari jawabannya di sini atau langsung hubungi admin."
            icon="support_agent"
        >
            {/* Contact CTA */}
            <div className="bg-primary-container rounded-3xl p-8 sm:p-10 text-center relative overflow-hidden shadow-glow">
                <div className="absolute top-0 right-0 p-6 opacity-10 pointer-events-none">
                    <Icon name="chat" className="text-[140px]" />
                </div>
                <Icon name="support_agent" className="text-4xl text-white mb-4" filled />
                <h2 className="font-display text-title-lg text-white font-black mb-2">Butuh bantuan langsung?</h2>
                <p className="text-blue-100 mb-6 max-w-md mx-auto">
                    Tim admin siap membantu lewat WhatsApp — jelaskan kendalamu dan kami bantu secepatnya.
                </p>
                <a
                    href={ERROR_REPORT_WHATSAPP_URL}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary-container rounded-2xl font-black shadow-xl hover:scale-105 active:scale-95 transition-all"
                >
                    <Icon name="chat" className="text-xl" filled />
                    Chat Admin via WhatsApp
                </a>
            </div>

            <PageSection title="Pertanyaan yang Sering Ditanyakan" icon="quiz">
                <div className="space-y-3">
                    {FAQS.map(f => <FaqItem key={f.q} q={f.q} a={f.a} />)}
                </div>
            </PageSection>

            <PageSection title="Butuh Bantuan Lain?" icon="alternate_email">
                <p className="mb-4">
                    Kalau pertanyaanmu belum terjawab di FAQ, jangan ragu hubungi kami langsung:
                </p>
                <div className="flex flex-col sm:flex-row gap-3">
                    <a
                        href={adminWhatsappUrl('Halo Admin SIMONAS, saya ingin bertanya tentang:')}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="flex-1 flex items-center gap-3 px-5 py-4 rounded-2xl bg-white/60 border border-outline-variant/30 hover:bg-white transition-colors"
                    >
                        <Icon name="chat" className="text-xl text-emerald-600" filled />
                        <div>
                            <p className="font-bold text-on-surface text-sm">WhatsApp Admin</p>
                            <p className="text-xs text-on-surface-variant">Respon tercepat</p>
                        </div>
                    </a>
                    <a
                        href="mailto:info@simonas.id"
                        className="flex-1 flex items-center gap-3 px-5 py-4 rounded-2xl bg-white/60 border border-outline-variant/30 hover:bg-white transition-colors"
                    >
                        <Icon name="mail" className="text-xl text-primary-container" filled />
                        <div>
                            <p className="font-bold text-on-surface text-sm">info@simonas.id</p>
                            <p className="text-xs text-on-surface-variant">Untuk pertanyaan formal</p>
                        </div>
                    </a>
                </div>
            </PageSection>
        </PublicPageShell>
    );
}
