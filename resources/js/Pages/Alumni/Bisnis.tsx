import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { AlumniSidebar } from '@/Components/Alumni/AlumniSidebar';

// ─── Types ────────────────────────────────────────────────────────────────────
interface Owner { id: number; name: string; avatar: string | null }
interface Business {
    id: number;
    company_name: string;
    business_type: string;
    description: string;
    logo: string | null;
    address: string | null;
    website: string | null;
    phone: string | null;
    email: string | null;
    social_media: Record<string, string> | null;
    owner: Owner | null;
}
interface Paginated<T> { data: T[]; current_page: number; last_page: number; next_page_url: string | null }
interface Props { businesses: Paginated<Business>; filter: { search?: string; type?: string } }

// ─── Business Card ────────────────────────────────────────────────────────────
function BusinessCard({ biz }: { biz: Business }) {
    const [expanded, setExpanded] = useState(false);
    const initials = biz.company_name.slice(0, 2).toUpperCase();

    return (
        <div className="glass-card rounded-2xl overflow-hidden hover:shadow-lg transition-all group">
            {/* Header gradient */}
            <div className="h-2 bg-gradient-to-r from-emerald-400 to-teal-500" />
            <div className="p-5">
                <div className="flex items-start gap-4 mb-4">
                    {biz.logo ? (
                        <img src={biz.logo} alt={biz.company_name} className="w-14 h-14 rounded-xl object-cover flex-shrink-0 shadow-sm" />
                    ) : (
                        <div className="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-100 to-teal-100 border border-emerald-200 flex items-center justify-center font-black text-emerald-700 text-lg flex-shrink-0">
                            {initials}
                        </div>
                    )}
                    <div className="flex-1 min-w-0">
                        <h3 className="font-display font-bold text-on-surface group-hover:text-emerald-700 transition-colors leading-tight">
                            {biz.company_name}
                        </h3>
                        <span className="inline-block mt-1 px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full">
                            {biz.business_type}
                        </span>
                    </div>
                </div>

                <p className={`text-sm text-on-surface leading-relaxed ${expanded ? '' : 'line-clamp-3'}`}>
                    {biz.description}
                </p>

                {biz.description.length > 150 && (
                    <button onClick={() => setExpanded(!expanded)} className="text-xs font-bold text-primary-container mt-1 hover:underline">
                        {expanded ? 'Sembunyikan' : 'Selengkapnya'}
                    </button>
                )}

                {/* Contact info */}
                <div className="flex flex-wrap gap-2 mt-4 pt-3 border-t border-white/30">
                    {biz.address && (
                        <span className="flex items-center gap-1 text-xs text-on-surface-variant">
                            <Icon name="location_on" className="text-xs text-emerald-500" />
                            {biz.address}
                        </span>
                    )}
                </div>

                <div className="flex items-center gap-2 mt-3 flex-wrap">
                    {biz.website && (
                        <a href={biz.website} target="_blank" rel="noopener noreferrer"
                            className="flex items-center gap-1 px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl text-xs font-bold hover:bg-blue-100 transition-colors">
                            <Icon name="language" className="text-xs" />
                            Website
                        </a>
                    )}
                    {biz.phone && (
                        <a href={`https://wa.me/${biz.phone.replace(/\D/g,'')}`} target="_blank" rel="noopener noreferrer"
                            className="flex items-center gap-1 px-3 py-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-colors">
                            <Icon name="chat" className="text-xs" />
                            WhatsApp
                        </a>
                    )}
                    {biz.email && (
                        <a href={`mailto:${biz.email}`}
                            className="flex items-center gap-1 px-3 py-1.5 bg-surface-container text-on-surface-variant rounded-xl text-xs font-bold hover:bg-white/60 transition-colors">
                            <Icon name="email" className="text-xs" />
                            Email
                        </a>
                    )}
                    {biz.owner && (
                        <span className="ml-auto text-xs text-on-surface-variant flex items-center gap-1">
                            <Icon name="person" className="text-xs" />
                            {biz.owner.name}
                        </span>
                    )}
                </div>
            </div>
        </div>
    );
}

// ─── Register Business Modal ──────────────────────────────────────────────────
function RegisterModal({ onClose }: { onClose: () => void }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        company_name:  '',
        business_type: '',
        description:   '',
        address:       '',
        website:       '',
        phone:         '',
        email:         '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/alumni/bisnis', { onSuccess: () => { reset(); onClose(); } });
    }

    const TYPES = ['Kuliner', 'Teknologi', 'Pendidikan', 'Perdagangan', 'Jasa', 'Kreatif', 'Kesehatan', 'Properti', 'Pertanian', 'Lainnya'];

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm overflow-y-auto">
            <div className="glass-card rounded-2xl w-full max-w-xl p-6 shadow-2xl animate-slide-up my-4">
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h2 className="font-display text-lg font-bold text-on-surface">Daftarkan Bisnis</h2>
                        <p className="text-xs text-on-surface-variant mt-0.5">Promosikan bisnis kamu ke seluruh alumni</p>
                    </div>
                    <button onClick={onClose} className="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center">
                        <Icon name="close" className="text-on-surface-variant" />
                    </button>
                </div>

                <form onSubmit={submit} className="space-y-4">
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Nama Bisnis *</label>
                        <input type="text" value={data.company_name} onChange={e => setData('company_name', e.target.value)}
                            placeholder="Nama usaha atau perusahaan" className="glass-input w-full text-sm" required />
                    </div>

                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Bidang Usaha *</label>
                        <div className="flex flex-wrap gap-1.5">
                            {TYPES.map(t => (
                                <button type="button" key={t} onClick={() => setData('business_type', t)}
                                    className={`px-3 py-1.5 rounded-full text-xs font-bold border transition-all ${
                                        data.business_type === t
                                            ? 'bg-emerald-500 text-white border-emerald-600'
                                            : 'border-white/30 text-on-surface-variant hover:bg-surface-container'
                                    }`}>
                                    {t}
                                </button>
                            ))}
                        </div>
                        {errors.business_type && <p className="text-xs text-rose-600 mt-1">{errors.business_type}</p>}
                    </div>

                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Deskripsi Bisnis *</label>
                        <textarea rows={3} value={data.description} onChange={e => setData('description', e.target.value)}
                            placeholder="Ceritakan produk/jasa yang kamu tawarkan, keunggulan, dll..."
                            className="glass-input w-full text-sm resize-none" required />
                    </div>

                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Alamat / Lokasi</label>
                        <input type="text" value={data.address} onChange={e => setData('address', e.target.value)}
                            placeholder="Kota, Provinsi atau Online" className="glass-input w-full text-sm" />
                    </div>

                    <div className="grid grid-cols-2 gap-3">
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Website</label>
                            <input type="url" value={data.website} onChange={e => setData('website', e.target.value)}
                                placeholder="https://..." className="glass-input w-full text-sm" />
                        </div>
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">No. WhatsApp</label>
                            <input type="text" value={data.phone} onChange={e => setData('phone', e.target.value)}
                                placeholder="08xxxxxxxxxx" className="glass-input w-full text-sm" />
                        </div>
                    </div>

                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Email Bisnis</label>
                        <input type="email" value={data.email} onChange={e => setData('email', e.target.value)}
                            placeholder="info@bisnisku.com" className="glass-input w-full text-sm" />
                    </div>

                    <div className="flex gap-3 pt-2">
                        <button type="button" onClick={onClose}
                            className="flex-1 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant">
                            Batal
                        </button>
                        <button type="submit" disabled={processing}
                            className="flex-1 py-3 rounded-xl font-bold text-sm bg-emerald-500 text-white disabled:opacity-50 hover:bg-emerald-600 transition-colors">
                            {processing ? 'Mendaftarkan...' : '🏪 Daftar Bisnis'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

// ─── Main Page ────────────────────────────────────────────────────────────────
export default function Bisnis({ businesses, filter }: Props) {
    const [showModal, setShowModal] = useState(false);
    const [search, setSearch] = useState(filter.search ?? '');

    function doSearch() {
        window.location.href = `/alumni/bisnis?search=${encodeURIComponent(search)}`;
    }

    return (
        <AppLayout searchPlaceholder="Cari bisnis alumni...">
            <Head title="Direktori Bisnis Alumni — SIMONAS" />

            <PageHeader
                title="Bisnis Alumni 🏪"
                subtitle="Direktori usaha dan produk dari para alumni"
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Alumni Hub', href: '/alumni/hub' },
                    { label: 'Bisnis' },
                ]}
            />

            <div className="grid grid-cols-1 xl:grid-cols-4 gap-6">

                {/* ── Main content ── */}
                <div className="xl:col-span-3 space-y-5">
                    {/* Search + CTA */}
                    <div className="flex items-center gap-3">
                        <div className="flex-1 flex items-center gap-2 glass-card rounded-xl px-3 py-2.5">
                            <Icon name="search" className="text-on-surface-variant text-base" />
                            <input value={search} onChange={e => setSearch(e.target.value)}
                                onKeyDown={e => e.key === 'Enter' && doSearch()}
                                placeholder="Cari nama bisnis, bidang, atau deskripsi..."
                                className="flex-1 text-sm bg-transparent outline-none text-on-surface placeholder:text-on-surface-variant" />
                        </div>
                        <button onClick={() => setShowModal(true)}
                            className="flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors shadow-md shadow-emerald-200 whitespace-nowrap">
                            <Icon name="add_business" className="text-base" />
                            Daftarkan Bisnis
                        </button>
                    </div>

                    {/* Grid */}
                    {businesses.data.length === 0 ? (
                        <div className="glass-card rounded-2xl p-16 flex flex-col items-center gap-4 text-center">
                            <Icon name="storefront" className="text-6xl opacity-20 text-on-surface-variant" />
                            <h3 className="font-bold text-on-surface">Belum ada bisnis terdaftar</h3>
                            <p className="text-sm text-on-surface-variant">Jadilah yang pertama mendaftarkan bisnis ke direktori alumni!</p>
                            <button onClick={() => setShowModal(true)}
                                className="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm">
                                Daftar Sekarang
                            </button>
                        </div>
                    ) : (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {businesses.data.map(b => <BusinessCard key={b.id} biz={b} />)}
                        </div>
                    )}
                </div>

                {/* ── Sidebar ── */}
                <div>
                    <AlumniSidebar active="/alumni/bisnis" />

                    <div className="mt-5 glass-card rounded-2xl p-5 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/50">
                        <h3 className="font-bold text-amber-800 text-sm mb-2 flex items-center gap-2">
                            <Icon name="storefront" className="text-amber-600" filled />
                            Mengapa Daftar?
                        </h3>
                        <ul className="space-y-1.5 text-xs text-amber-700 leading-relaxed">
                            <li>🎯 Jangkau 340+ alumni sebagai calon pelanggan</li>
                            <li>🤝 Peluang B2B antar alumni</li>
                            <li>📢 Promosi gratis tanpa batas</li>
                            <li>🔗 Tampil di direktori resmi SIMONAS</li>
                        </ul>
                    </div>
                </div>
            </div>

            {showModal && <RegisterModal onClose={() => setShowModal(false)} />}
        </AppLayout>
    );
}
