import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';
import { AlumniSidebar } from '@/Components/Alumni/AlumniSidebar';

// ─── Types ────────────────────────────────────────────────────────────────────
interface Job {
    id: number;
    type: 'job' | 'internship' | 'freelance';
    title: string;
    company: string;
    location: string;
    work_type: 'onsite' | 'remote' | 'hybrid';
    description: string;
    salary_range: string | null;
    contact_info: string;
    deadline: string | null;
    posted_by: { name: string; angkatan: string };
    created_at: string;
}
interface Paginated<T> { data: T[]; current_page: number; last_page: number }
interface Props { jobs: Paginated<Job>; filter: { type?: string; search?: string } }

// ─── Meta ─────────────────────────────────────────────────────────────────────
const JOB_TYPE_META = {
    job:        { label: 'Full-time',  icon: 'work',        color: 'bg-blue-100 text-blue-700 border-blue-200' },
    internship: { label: 'Magang',     icon: 'school',      color: 'bg-amber-100 text-amber-700 border-amber-200' },
    freelance:  { label: 'Freelance',  icon: 'laptop',      color: 'bg-purple-100 text-purple-700 border-purple-200' },
};
const WORK_TYPE_META = {
    onsite: { label: 'Onsite', icon: 'location_on',  color: 'text-gray-600' },
    remote: { label: 'Remote', icon: 'home_work',    color: 'text-emerald-600' },
    hybrid: { label: 'Hybrid', icon: 'sync_alt',     color: 'text-blue-600' },
};

// ─── Job Card ─────────────────────────────────────────────────────────────────
function JobCard({ job }: { job: Job }) {
    const [expanded, setExpanded] = useState(false);
    const typeMeta = JOB_TYPE_META[job.type];
    const workMeta = WORK_TYPE_META[job.work_type];

    return (
        <div className="glass-card rounded-2xl p-5 hover:shadow-lg transition-all group">
            <div className="flex items-start justify-between gap-4 mb-3">
                <div className="flex-1 min-w-0">
                    <div className="flex items-center gap-2 mb-1 flex-wrap">
                        <span className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black border ${typeMeta.color}`}>
                            <Icon name={typeMeta.icon} className="text-xs" filled />
                            {typeMeta.label}
                        </span>
                        <span className={`inline-flex items-center gap-1 text-[10px] font-bold ${workMeta.color}`}>
                            <Icon name={workMeta.icon} className="text-xs" />
                            {workMeta.label}
                        </span>
                    </div>
                    <h3 className="font-display font-bold text-on-surface text-base leading-tight group-hover:text-emerald-700 transition-colors">
                        {job.title}
                    </h3>
                    <p className="text-sm font-semibold text-on-surface-variant mt-0.5">{job.company}</p>
                </div>
                {job.salary_range && (
                    <div className="flex-shrink-0 text-right">
                        <p className="text-[10px] text-on-surface-variant uppercase tracking-wide">Gaji</p>
                        <p className="text-sm font-black text-emerald-700">{job.salary_range}</p>
                    </div>
                )}
            </div>

            <div className="flex items-center gap-4 text-xs text-on-surface-variant mb-3 flex-wrap">
                <span className="flex items-center gap-1">
                    <Icon name="location_on" className="text-xs" />
                    {job.location}
                </span>
                {job.deadline && (
                    <span className="flex items-center gap-1 text-amber-600 font-bold">
                        <Icon name="schedule" className="text-xs" />
                        Deadline: {job.deadline}
                    </span>
                )}
                <span className="flex items-center gap-1 ml-auto">
                    <Icon name="person" className="text-xs" />
                    {job.posted_by.name}
                </span>
            </div>

            <p className={`text-sm text-on-surface leading-relaxed ${expanded ? '' : 'line-clamp-3'}`}>
                {job.description}
            </p>

            <div className="flex items-center justify-between mt-4 pt-3 border-t border-white/30 gap-3">
                <button onClick={() => setExpanded(!expanded)}
                    className="text-xs font-bold text-primary-container hover:underline">
                    {expanded ? 'Sembunyikan' : 'Lihat selengkapnya'}
                </button>

                <div className="flex items-center gap-2">
                    <span className="text-xs text-on-surface-variant">{job.created_at}</span>
                    <a href={`mailto:${job.contact_info}`}
                        className="flex items-center gap-1.5 px-4 py-2 bg-emerald-500 text-white rounded-xl text-xs font-bold hover:bg-emerald-600 transition-colors">
                        <Icon name="send" className="text-xs" />
                        Apply
                    </a>
                </div>
            </div>
        </div>
    );
}

// ─── Post Job Modal ───────────────────────────────────────────────────────────
function PostJobModal({ open, onClose }: { open: boolean; onClose: () => void }) {
    const { data, setData, post, processing, reset } = useForm({
        type: 'job',
        title: '',
        company: '',
        location: '',
        work_type: 'onsite',
        description: '',
        requirements: '',
        salary_range: '',
        contact_info: '',
        deadline: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/alumni/jobs', { onSuccess: () => { reset(); onClose(); } });
    }

    return (
        <Modal open={open} onClose={onClose} title="Posting Lowongan" icon="work" size="lg"
            footer={
                <>
                    <button type="button" onClick={onClose}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-black/10 transition-colors">
                        Batal
                    </button>
                    <button form="job-form" type="submit" disabled={processing}
                        className="px-5 py-2.5 rounded-xl font-bold text-sm bg-emerald-500 text-white disabled:opacity-50 hover:bg-emerald-600 transition-colors">
                        {processing ? 'Memposting...' : '🚀 Post Lowongan'}
                    </button>
                </>
            }>
            <form id="job-form" onSubmit={submit} className="space-y-4">
                <div className="grid grid-cols-2 gap-3">
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Tipe Lowongan *</label>
                        <select value={data.type} onChange={e => setData('type', e.target.value)} className="glass-input w-full text-sm">
                            <option value="job">Full-time</option>
                            <option value="internship">Magang / Internship</option>
                            <option value="freelance">Freelance / Project</option>
                        </select>
                    </div>
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Mode Kerja *</label>
                        <select value={data.work_type} onChange={e => setData('work_type', e.target.value)} className="glass-input w-full text-sm">
                            <option value="onsite">Onsite</option>
                            <option value="remote">Remote</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Posisi / Judul Pekerjaan *</label>
                    <input type="text" value={data.title} onChange={e => setData('title', e.target.value)}
                        placeholder="Contoh: Frontend Developer" className="glass-input w-full text-sm" required />
                </div>

                <div className="grid grid-cols-2 gap-3">
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Perusahaan *</label>
                        <input type="text" value={data.company} onChange={e => setData('company', e.target.value)}
                            placeholder="Nama perusahaan" className="glass-input w-full text-sm" required />
                    </div>
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Lokasi *</label>
                        <input type="text" value={data.location} onChange={e => setData('location', e.target.value)}
                            placeholder="Jakarta / Remote" className="glass-input w-full text-sm" required />
                    </div>
                </div>

                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Deskripsi Pekerjaan *</label>
                    <textarea rows={4} value={data.description} onChange={e => setData('description', e.target.value)}
                        placeholder="Deskripsikan pekerjaan, tanggung jawab, dan lingkungan kerja..."
                        className="glass-input w-full text-sm resize-none" required />
                </div>

                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Kualifikasi / Persyaratan</label>
                    <textarea rows={2} value={data.requirements} onChange={e => setData('requirements', e.target.value)}
                        placeholder="Pendidikan, skill, pengalaman yang dibutuhkan..."
                        className="glass-input w-full text-sm resize-none" />
                </div>

                <div className="grid grid-cols-2 gap-3">
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Kisaran Gaji</label>
                        <input type="text" value={data.salary_range} onChange={e => setData('salary_range', e.target.value)}
                            placeholder="Rp 5–8 jt / negotiable" className="glass-input w-full text-sm" />
                    </div>
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Deadline Lamaran</label>
                        <input type="date" value={data.deadline} onChange={e => setData('deadline', e.target.value)}
                            className="glass-input w-full text-sm" />
                    </div>
                </div>

                <div>
                    <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant block mb-1.5">Kontak / Email Apply *</label>
                    <input type="text" value={data.contact_info} onChange={e => setData('contact_info', e.target.value)}
                        placeholder="email@perusahaan.com atau link formulir" className="glass-input w-full text-sm" required />
                </div>
            </form>
        </Modal>
    );
}


// ─── Main Page ────────────────────────────────────────────────────────────────
export default function Jobs({ jobs, filter }: Props) {
    const [showPostModal, setShowPostModal] = useState(false);
    const [search, setSearch] = useState(filter.search ?? '');
    const [activeType, setActiveType] = useState(filter.type ?? '');

    function applyFilter(type?: string, q?: string) {
        const params = new URLSearchParams();
        if (type !== undefined) { setActiveType(type); if (type) params.set('type', type); }
        else if (activeType) params.set('type', activeType);
        if (q !== undefined) { setSearch(q); if (q) params.set('search', q); }
        else if (search) params.set('search', search);
        window.location.href = `/alumni/jobs?${params.toString()}`;
    }

    return (
        <AppLayout searchPlaceholder="Cari lowongan...">
            <Head title="Lowongan & Magang — SIMONAS Alumni" />

            <PageHeader
                title="Lowongan & Magang 💼"
                subtitle="Info kerja, magang, dan freelance dari sesama alumni"
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Alumni Hub', href: '/alumni/hub' },
                    { label: 'Lowongan & Magang' },
                ]}
            />

            {/* ── Filter bar ── */}
            <div className="flex flex-wrap items-center gap-3 mb-6">
                <div className="flex items-center gap-1 glass-card rounded-xl p-1">
                    {[{ key: '', label: 'Semua' }, { key: 'job', label: 'Full-time' }, { key: 'internship', label: 'Magang' }, { key: 'freelance', label: 'Freelance' }].map(opt => (
                        <button key={opt.key} onClick={() => applyFilter(opt.key, undefined)}
                            className={`px-3 py-1.5 rounded-lg text-xs font-bold transition-colors ${
                                activeType === opt.key ? 'bg-emerald-500 text-white' : 'text-on-surface-variant hover:bg-surface-container'
                            }`}>
                            {opt.label}
                        </button>
                    ))}
                </div>

                <div className="flex-1 flex items-center gap-2 glass-card rounded-xl px-3 py-2">
                    <Icon name="search" className="text-on-surface-variant text-base" />
                    <input value={search} onChange={e => setSearch(e.target.value)}
                        onKeyDown={e => e.key === 'Enter' && applyFilter(undefined, search)}
                        placeholder="Cari posisi atau perusahaan..."
                        className="flex-1 text-sm bg-transparent outline-none text-on-surface placeholder:text-on-surface-variant" />
                </div>

                <button onClick={() => setShowPostModal(true)}
                    className="flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors shadow-md shadow-emerald-200">
                    <Icon name="add" className="text-base" />
                    Post Lowongan
                </button>
            </div>

            <div className="grid grid-cols-1 xl:grid-cols-4 gap-6">
                {/* ── Jobs list ── */}
                <div className="xl:col-span-3 space-y-4">
                    {jobs.data.length === 0 ? (
                        <div className="glass-card rounded-2xl p-16 flex flex-col items-center gap-4 text-center">
                            <Icon name="work_off" className="text-5xl opacity-20 text-on-surface-variant" />
                            <h3 className="font-bold text-on-surface">Belum ada lowongan</h3>
                            <p className="text-sm text-on-surface-variant">Jadilah yang pertama memposting lowongan untuk sesama alumni!</p>
                            <button onClick={() => setShowPostModal(true)}
                                className="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-sm hover:bg-emerald-600 transition-colors">
                                Post Lowongan Pertama
                            </button>
                        </div>
                    ) : (
                        jobs.data.map(j => <JobCard key={j.id} job={j} />)
                    )}
                </div>

                {/* ── Sidebar ── */}
                <div className="space-y-5">
                    <AlumniSidebar active="/alumni/jobs" />

                    <div className="glass-card rounded-2xl p-5 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/50">
                        <h3 className="font-bold text-blue-800 text-sm mb-2 flex items-center gap-2">
                            <Icon name="tips_and_updates" className="text-blue-600" filled />
                            Tips Melamar
                        </h3>
                        <ul className="space-y-2 text-xs text-blue-700">
                            <li>📄 Siapkan CV yang up-to-date</li>
                            <li>💼 Sesuaikan cover letter dengan posisi</li>
                            <li>🤝 Manfaatkan jaringan alumni</li>
                            <li>⏰ Perhatikan deadline lamaran</li>
                        </ul>
                    </div>

                    <div className="glass-card rounded-2xl p-5">
                        <div className="flex items-center justify-between mb-3">
                            <h3 className="font-bold text-on-surface text-sm">Statistik</h3>
                        </div>
                        <div className="grid grid-cols-2 gap-2">
                            <div className="bg-blue-50 rounded-xl p-3 text-center">
                                <p className="font-black text-2xl text-blue-700">{jobs.data.filter(j => j.type === 'job').length}</p>
                                <p className="text-[10px] text-blue-600 font-bold">Full-time</p>
                            </div>
                            <div className="bg-amber-50 rounded-xl p-3 text-center">
                                <p className="font-black text-2xl text-amber-700">{jobs.data.filter(j => j.type === 'internship').length}</p>
                                <p className="text-[10px] text-amber-600 font-bold">Magang</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <PostJobModal open={showPostModal} onClose={() => setShowPostModal(false)} />
        </AppLayout>
    );
}
