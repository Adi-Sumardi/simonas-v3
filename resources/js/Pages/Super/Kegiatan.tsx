import { Head } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';

interface Kegiatan { id:number; judul:string; tanggal:string; waktu:string; tempat:string; tipe:string; status:string; peserta:number }
interface Props { kegiatan:Kegiatan[]; stats:{ upcoming:number; selesai:number; total:number } }

const TIPE_META: Record<string, { icon:string; bg:string; text:string }> = {
    akademik:  { icon:'school',        bg:'bg-blue-100',    text:'text-blue-600' },
    hafalan:   { icon:'auto_stories',  bg:'bg-emerald-100', text:'text-emerald-600' },
    kegiatan:  { icon:'event',         bg:'bg-purple-100',  text:'text-purple-600' },
    olahraga:  { icon:'fitness_center',bg:'bg-teal-100',    text:'text-teal-600' },
};

export default function Kegiatan({ kegiatan, stats }: Props) {
    const [filter, setFilter] = useState<'all'|'upcoming'|'selesai'>('all');

    const filtered = kegiatan.filter(k => filter === 'all' || k.status === filter);

    return (
        <AppLayout>
            <Head title="Kegiatan & Event" />
            <PageHeader
                title="Kegiatan & Event"
                subtitle="Program pesantren, jadwal kegiatan, dan rencana aktivitas"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Kegiatan' }]}
                actions={
                    <button className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="add" className="text-lg" />
                        Tambah Kegiatan
                    </button>
                }
            />

            {/* Stats */}
            <div className="grid grid-cols-3 gap-4 mb-6">
                {[
                    { label:'Total', value:stats.total,    icon:'event',         bg:'bg-blue-100',   text:'text-blue-600' },
                    { label:'Mendatang', value:stats.upcoming, icon:'upcoming',  bg:'bg-amber-100',  text:'text-amber-600' },
                    { label:'Selesai', value:stats.selesai,   icon:'task_alt',   bg:'bg-emerald-100',text:'text-emerald-600' },
                ].map(s => (
                    <div key={s.label} className="glass-card rounded-2xl p-5 flex flex-col gap-2">
                        <div className={`w-10 h-10 ${s.bg} rounded-xl flex items-center justify-center`}><Icon name={s.icon} className={`text-xl ${s.text}`} filled /></div>
                        <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">{s.label}</p>
                        <p className="font-display text-2xl font-bold text-on-surface">{s.value}</p>
                    </div>
                ))}
            </div>

            {/* Filter tabs */}
            <div className="flex gap-2 mb-4">
                {(['all','upcoming','selesai'] as const).map(f => (
                    <button key={f} onClick={() => setFilter(f)}
                        className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${filter === f ? 'bg-primary-container text-white' : 'glass-card text-on-surface-variant hover:bg-white/60'}`}>
                        {f === 'all' ? 'Semua' : f === 'upcoming' ? '📅 Mendatang' : '✅ Selesai'}
                    </button>
                ))}
            </div>

            {/* Cards */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {filtered.map(k => {
                    const meta = TIPE_META[k.tipe] ?? TIPE_META.kegiatan;
                    const isUpcoming = k.status === 'upcoming';
                    return (
                        <div key={k.id} className="glass-card rounded-2xl p-5 flex flex-col gap-4 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                            <div className="flex items-start justify-between">
                                <div className={`w-11 h-11 ${meta.bg} rounded-xl flex items-center justify-center`}>
                                    <Icon name={meta.icon} className={`text-xl ${meta.text}`} filled />
                                </div>
                                <span className={`text-[10px] font-black px-2.5 py-1 rounded-full ${isUpcoming ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700'}`}>
                                    {isUpcoming ? '📅 Mendatang' : '✅ Selesai'}
                                </span>
                            </div>
                            <div>
                                <h3 className="font-bold text-on-surface">{k.judul}</h3>
                                <div className="flex flex-col gap-1 mt-2">
                                    <div className="flex items-center gap-1.5 text-xs text-on-surface-variant">
                                        <Icon name="calendar_today" className="text-xs" />
                                        {new Date(k.tanggal+'T00:00:00').toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' })}
                                        {' · '}{k.waktu}
                                    </div>
                                    <div className="flex items-center gap-1.5 text-xs text-on-surface-variant">
                                        <Icon name="location_on" className="text-xs" />
                                        {k.tempat}
                                    </div>
                                    <div className="flex items-center gap-1.5 text-xs text-on-surface-variant">
                                        <Icon name="people" className="text-xs" />
                                        {k.peserta} peserta
                                    </div>
                                </div>
                            </div>
                            <div className="flex gap-2 pt-1">
                                <button className="flex-1 py-2 rounded-xl text-xs font-bold bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">Detail</button>
                                {isUpcoming && (
                                    <button className="flex-1 py-2 rounded-xl text-xs font-bold bg-primary-container text-white hover:opacity-90 transition-opacity">Edit</button>
                                )}
                            </div>
                        </div>
                    );
                })}
            </div>
        </AppLayout>
    );
}
