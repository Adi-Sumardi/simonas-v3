import { Head } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';

interface CalEvent {
    id: number; title: string; date: string; time: string;
    type: string; color: string; santri?: string | null; asrama?: string | null;
}
interface JadwalSetoran { santri: string; hari: string; waktu: string; asrama: string }
interface Props { events: CalEvent[]; upcoming: CalEvent[]; jadwal_setoran: JadwalSetoran[]; today: string }

const TYPE_META: Record<string, { label: string; icon: string; bg: string; text: string; border: string }> = {
    hafalan:   { label: 'Setoran Hafalan', icon: 'auto_stories',      bg: 'bg-emerald-100', text: 'text-emerald-700', border: 'border-emerald-200' },
    mentoring: { label: 'Mentoring',       icon: 'people',            bg: 'bg-blue-100',    text: 'text-blue-700',    border: 'border-blue-200' },
    evaluasi:  { label: 'Evaluasi',        icon: 'rate_review',       bg: 'bg-amber-100',   text: 'text-amber-700',   border: 'border-amber-200' },
    rapat:     { label: 'Rapat',           icon: 'groups',            bg: 'bg-purple-100',  text: 'text-purple-700',  border: 'border-purple-200' },
};

const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const DAYS   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

export default function MentorKalender({ events, upcoming, jadwal_setoran, today }: Props) {
    const todayDate = new Date(today);
    const [curYear, setYear]    = useState(todayDate.getFullYear());
    const [curMonth, setMonth]  = useState(todayDate.getMonth());
    const [selected, setSelected]= useState<string | null>(today);
    const [filterType, setFilter]= useState('');

    function prevMonth() { if (curMonth===0){setMonth(11);setYear(y=>y-1);}else setMonth(m=>m-1); }
    function nextMonth() { if (curMonth===11){setMonth(0);setYear(y=>y+1);}else setMonth(m=>m+1); }

    const calDays = useMemo(() => {
        const first = new Date(curYear, curMonth, 1).getDay();
        const days  = new Date(curYear, curMonth+1, 0).getDate();
        const arr: (number|null)[] = [];
        for (let i=0;i<first;i++) arr.push(null);
        for (let d=1;d<=days;d++) arr.push(d);
        return arr;
    }, [curYear, curMonth]);

    const filtered = useMemo(() => events.filter(e => filterType==='' || e.type===filterType), [events, filterType]);

    const dayEvents = useMemo(() => {
        if (!selected) return [];
        return filtered.filter(e => e.date===selected).sort((a,b)=>a.time.localeCompare(b.time));
    }, [selected, filtered]);

    const eventsByDay = useMemo(() => {
        const map: Record<string, CalEvent[]> = {};
        filtered.forEach(e => {
            const [y,m] = e.date.split('-').map(Number);
            if (y===curYear && m===curMonth+1) map[e.date] = [...(map[e.date]??[]), e];
        });
        return map;
    }, [filtered, curYear, curMonth]);

    function dateStr(d: number) { return `${curYear}-${String(curMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`; }
    const isToday    = (d: number) => dateStr(d)===today;
    const isSelected = (d: number) => dateStr(d)===selected;

    return (
        <AppLayout>
            <Head title="Kalender Mentor" />
            <PageHeader
                title="Kalender Mentor"
                subtitle="Jadwal setoran, mentoring, evaluasi, dan rapat"
                breadcrumbs={[{ label:'Dashboard', href:'/dashboard' }, { label:'Kalender' }]}
            />

            {/* Filter pills */}
            <div className="flex flex-wrap gap-2 mb-6">
                <button onClick={() => setFilter('')}
                    className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${filterType==='' ? 'bg-on-surface text-white' : 'bg-white/60 text-on-surface-variant hover:bg-white/80'}`}>
                    Semua
                </button>
                {Object.entries(TYPE_META).map(([key, m]) => (
                    <button key={key} onClick={() => setFilter(filterType===key ? '' : key)}
                        className={`flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition-all border ${filterType===key ? `${m.bg} ${m.text} ${m.border}` : 'bg-white/60 text-on-surface-variant border-transparent hover:bg-white/80'}`}>
                        <Icon name={m.icon} className="text-sm" filled={filterType===key} />
                        {m.label}
                    </button>
                ))}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Calendar */}
                <div className="lg:col-span-2 glass-card rounded-2xl p-5">
                    <div className="flex items-center justify-between mb-6">
                        <button onClick={prevMonth} className="w-9 h-9 rounded-xl bg-surface-container hover:bg-white/80 flex items-center justify-center transition-all"><Icon name="chevron_left" className="text-xl text-on-surface-variant"/></button>
                        <div className="text-center">
                            <h2 className="font-display text-lg font-bold text-on-surface">{MONTHS[curMonth]}</h2>
                            <p className="text-xs text-on-surface-variant">{curYear}</p>
                        </div>
                        <button onClick={nextMonth} className="w-9 h-9 rounded-xl bg-surface-container hover:bg-white/80 flex items-center justify-center transition-all"><Icon name="chevron_right" className="text-xl text-on-surface-variant"/></button>
                    </div>
                    <div className="grid grid-cols-7 mb-2">
                        {DAYS.map(d => <div key={d} className={`text-center text-[10px] font-black uppercase tracking-wider py-2 ${d==='Min'||d==='Sab'?'text-rose-400':'text-on-surface-variant'}`}>{d}</div>)}
                    </div>
                    <div className="grid grid-cols-7 gap-1">
                        {calDays.map((day, idx) => {
                            if (!day) return <div key={`e-${idx}`} />;
                            const ds = dateStr(day);
                            const devs = eventsByDay[ds] ?? [];
                            const types = [...new Set(devs.map(e=>e.type))].slice(0,3);
                            const dow = new Date(curYear, curMonth, day).getDay();
                            return (
                                <button key={day} onClick={() => setSelected(ds)}
                                    className={`relative flex flex-col items-center py-2 rounded-xl transition-all ${isSelected(day) ? 'bg-primary-container shadow-lg scale-105' : isToday(day) ? 'bg-blue-50 border-2 border-primary-container/40' : 'hover:bg-white/60'}`}>
                                    <span className={`text-sm font-bold ${isSelected(day)?'text-white':isToday(day)?'text-primary-container':(dow===0||dow===6)?'text-rose-400':'text-on-surface'}`}>{day}</span>
                                    {types.length>0 && (
                                        <div className="flex gap-0.5 mt-1">
                                            {types.map(t => <div key={t} className="w-1.5 h-1.5 rounded-full" style={{ backgroundColor: isSelected(day)?'rgba(255,255,255,0.7)':events.find(e=>e.type===t)?.color }} />)}
                                        </div>
                                    )}
                                </button>
                            );
                        })}
                    </div>
                    {/* Legend */}
                    <div className="flex flex-wrap gap-x-4 gap-y-1 mt-4 pt-4 border-t border-white/40">
                        {Object.entries(TYPE_META).map(([key, m]) => (
                            <div key={key} className="flex items-center gap-1.5">
                                <div className="w-2 h-2 rounded-full" style={{ backgroundColor: events.find(e=>e.type===key)?.color ?? '#94a3b8' }} />
                                <span className="text-[10px] text-on-surface-variant font-bold">{m.label}</span>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Right panel */}
                <div className="space-y-5">
                    {/* Selected day */}
                    <div className="glass-card rounded-2xl p-5">
                        <h3 className="font-bold text-on-surface mb-3 text-sm">
                            {selected ? new Date(selected+'T00:00:00').toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long' }) : 'Pilih tanggal'}
                        </h3>
                        {dayEvents.length === 0 ? (
                            <div className="flex flex-col items-center py-6 gap-2 text-on-surface-variant">
                                <Icon name="event_busy" className="text-3xl opacity-20" />
                                <p className="text-xs">Tidak ada jadwal</p>
                            </div>
                        ) : (
                            <div className="space-y-2">
                                {dayEvents.map(ev => {
                                    const m = TYPE_META[ev.type] ?? TYPE_META.rapat;
                                    return (
                                        <div key={ev.id} className={`flex gap-3 p-3 rounded-xl border ${m.bg} ${m.border}`}>
                                            <div className="w-8 h-8 rounded-lg bg-white/60 flex items-center justify-center flex-shrink-0">
                                                <Icon name={m.icon} className={`text-sm ${m.text}`} filled />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className={`text-xs font-bold ${m.text} line-clamp-1`}>{ev.title}</p>
                                                <p className="text-[10px] text-on-surface-variant">{ev.time}{ev.santri ? ` · ${ev.santri}` : ''}</p>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </div>

                    {/* Upcoming */}
                    <div className="glass-card rounded-2xl p-5">
                        <h3 className="font-bold text-on-surface mb-3 flex items-center gap-2 text-sm">
                            <Icon name="upcoming" className="text-primary-container" filled />
                            Jadwal Mendatang
                        </h3>
                        <div className="space-y-2">
                            {upcoming.map(ev => {
                                const m = TYPE_META[ev.type] ?? TYPE_META.rapat;
                                const d = new Date(ev.date+'T00:00:00');
                                return (
                                    <button key={ev.id} onClick={() => setSelected(ev.date)}
                                        className="w-full flex items-center gap-3 p-2.5 rounded-xl hover:bg-white/40 transition-all text-left group">
                                        <div className={`w-9 h-9 rounded-xl flex flex-col items-center justify-center flex-shrink-0 ${ev.date===today ? 'bg-primary-container' : 'bg-surface-container'}`}>
                                            <span className={`text-[9px] font-black ${ev.date===today?'text-white/70':'text-on-surface-variant'}`}>{d.toLocaleDateString('id-ID',{month:'short'}).toUpperCase()}</span>
                                            <span className={`text-sm font-black ${ev.date===today?'text-white':'text-on-surface'}`}>{d.getDate()}</span>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-xs font-semibold text-on-surface line-clamp-1 group-hover:text-primary-container transition-colors">{ev.title}</p>
                                            <div className="flex items-center gap-1">
                                                <Icon name={m.icon} className={`text-xs ${m.text}`} />
                                                <span className="text-[10px] text-on-surface-variant">{ev.time}</span>
                                            </div>
                                        </div>
                                    </button>
                                );
                            })}
                        </div>
                    </div>

                    {/* Jadwal setoran rutin */}
                    <div className="glass-card rounded-2xl p-5">
                        <h3 className="font-bold text-on-surface mb-3 flex items-center gap-2 text-sm">
                            <Icon name="repeat" className="text-emerald-600" />
                            Jadwal Setoran Rutin
                        </h3>
                        <div className="space-y-2">
                            {jadwal_setoran.map(j => (
                                <div key={j.santri} className="flex items-center gap-3 p-2.5 rounded-xl bg-emerald-50/50 border border-emerald-100">
                                    <div className="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-xs font-black text-emerald-600 flex-shrink-0">
                                        {j.santri.split(' ').map(n=>n[0]).slice(0,2).join('')}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-xs font-bold text-on-surface">{j.santri}</p>
                                        <p className="text-[10px] text-on-surface-variant">{j.hari} · {j.waktu}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
