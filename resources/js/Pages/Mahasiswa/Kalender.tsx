import { Head } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';

interface CalEvent {
    id: number;
    title: string;
    date: string;   // YYYY-MM-DD
    time: string;   // HH:mm
    type: string;
    color: string;
    desc?: string;
    recurring?: boolean;
}

interface KalenderProps extends PageProps {
    events: CalEvent[];
    upcoming: CalEvent[];
    today: string;
}

const TYPE_META: Record<string, { label: string; icon: string; bg: string; text: string; border: string }> = {
    shalat:   { label: 'Shalat',    icon: 'mosque',       bg: 'bg-blue-100',    text: 'text-blue-600',    border: 'border-blue-300' },
    hafalan:  { label: 'Hafalan',   icon: 'auto_stories', bg: 'bg-emerald-100', text: 'text-emerald-600', border: 'border-emerald-300' },
    akademik: { label: 'Akademik',  icon: 'school',       bg: 'bg-amber-100',   text: 'text-amber-600',   border: 'border-amber-300' },
    kegiatan: { label: 'Kegiatan',  icon: 'event',        bg: 'bg-purple-100',  text: 'text-purple-600',  border: 'border-purple-300' },
    belajar:  { label: 'Belajar',   icon: 'menu_book',    bg: 'bg-rose-100',    text: 'text-rose-600',    border: 'border-rose-300' },
    olahraga: { label: 'Olahraga',  icon: 'fitness_center',bg: 'bg-teal-100',   text: 'text-teal-600',    border: 'border-teal-300' },
};

const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const DAYS   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

export default function Kalender({ events, upcoming, today }: KalenderProps) {
    const todayDate = new Date(today);
    const [currentYear, setYear]   = useState(todayDate.getFullYear());
    const [currentMonth, setMonth] = useState(todayDate.getMonth());
    const [selectedDate, setSelected] = useState<string | null>(today);
    const [filterType, setFilter]  = useState<string>('');

    function prevMonth() {
        if (currentMonth === 0) { setMonth(11); setYear(y => y - 1); }
        else setMonth(m => m - 1);
    }
    function nextMonth() {
        if (currentMonth === 11) { setMonth(0); setYear(y => y + 1); }
        else setMonth(m => m + 1);
    }

    // Calendar grid
    const calendarDays = useMemo(() => {
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const days: (number | null)[] = [];
        for (let i = 0; i < firstDay; i++) days.push(null);
        for (let d = 1; d <= daysInMonth; d++) days.push(d);
        return days;
    }, [currentYear, currentMonth]);

    // Events filtered by type
    const filteredEvents = useMemo(() =>
        events.filter(e => filterType === '' || e.type === filterType),
    [events, filterType]);

    // Events for selected date
    const dayEvents = useMemo(() => {
        if (!selectedDate) return [];
        return filteredEvents.filter(e => e.date === selectedDate)
            .sort((a, b) => a.time.localeCompare(b.time));
    }, [selectedDate, filteredEvents]);

    // Events per day in current month (for dots)
    const eventsByDay = useMemo(() => {
        const map: Record<string, CalEvent[]> = {};
        filteredEvents.forEach(e => {
            const [y, m] = e.date.split('-').map(Number);
            if (y === currentYear && m === currentMonth + 1) {
                map[e.date] = [...(map[e.date] ?? []), e];
            }
        });
        return map;
    }, [filteredEvents, currentYear, currentMonth]);

    function dateStr(day: number) {
        return `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }

    const isToday = (day: number) => dateStr(day) === today;
    const isSelected = (day: number) => dateStr(day) === selectedDate;

    return (
        <AppLayout searchPlaceholder="Cari kegiatan...">
            <Head title="Kalender Kegiatan" />

            <PageHeader
                title="Kalender Kegiatan"
                subtitle="Jadwal shalat, hafalan, akademik, dan semua aktivitas pesantren kamu"
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Kalender' },
                ]}
            />

            {/* Filter pills */}
            <div className="flex flex-wrap gap-2 mb-6">
                <button
                    onClick={() => setFilter('')}
                    className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${filterType === '' ? 'bg-on-surface text-white shadow-md' : 'bg-white/60 text-on-surface-variant hover:bg-white/80'}`}
                >
                    Semua
                </button>
                {Object.entries(TYPE_META).map(([key, meta]) => (
                    <button
                        key={key}
                        onClick={() => setFilter(filterType === key ? '' : key)}
                        className={`flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold transition-all border ${
                            filterType === key ? `${meta.bg} ${meta.text} ${meta.border}` : 'bg-white/60 text-on-surface-variant border-transparent hover:bg-white/80'
                        }`}
                    >
                        <Icon name={meta.icon} className="text-sm" filled={filterType === key} />
                        {meta.label}
                    </button>
                ))}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {/* Calendar grid */}
                <div className="lg:col-span-2 glass-card rounded-2xl p-5">
                    {/* Month nav */}
                    <div className="flex items-center justify-between mb-6">
                        <button onClick={prevMonth} className="w-9 h-9 rounded-xl bg-surface-container hover:bg-white/80 flex items-center justify-center transition-all hover:scale-105">
                            <Icon name="chevron_left" className="text-xl text-on-surface-variant" />
                        </button>
                        <div className="text-center">
                            <h2 className="font-display text-lg font-bold text-on-surface">{MONTHS[currentMonth]}</h2>
                            <p className="text-xs text-on-surface-variant">{currentYear}</p>
                        </div>
                        <button onClick={nextMonth} className="w-9 h-9 rounded-xl bg-surface-container hover:bg-white/80 flex items-center justify-center transition-all hover:scale-105">
                            <Icon name="chevron_right" className="text-xl text-on-surface-variant" />
                        </button>
                    </div>

                    {/* Day headers */}
                    <div className="grid grid-cols-7 mb-2">
                        {DAYS.map(d => (
                            <div key={d} className={`text-center text-[10px] font-black uppercase tracking-wider py-2 ${d === 'Min' || d === 'Sab' ? 'text-rose-400' : 'text-on-surface-variant'}`}>
                                {d}
                            </div>
                        ))}
                    </div>

                    {/* Days grid */}
                    <div className="grid grid-cols-7 gap-1">
                        {calendarDays.map((day, idx) => {
                            if (!day) return <div key={`e-${idx}`} />;
                            const ds = dateStr(day);
                            const dayEvs = eventsByDay[ds] ?? [];
                            const types = [...new Set(dayEvs.map(e => e.type))].slice(0, 3);
                            const dow = (new Date(currentYear, currentMonth, day).getDay());
                            const isWeekend = dow === 0 || dow === 6;

                            return (
                                <button
                                    key={day}
                                    onClick={() => setSelected(ds)}
                                    className={`relative flex flex-col items-center py-2 rounded-xl transition-all group ${
                                        isSelected(day)
                                            ? 'bg-primary-container shadow-lg shadow-blue-500/20 scale-105'
                                            : isToday(day)
                                            ? 'bg-blue-50 border-2 border-primary-container/40'
                                            : 'hover:bg-white/60'
                                    }`}
                                >
                                    <span className={`text-sm font-bold ${
                                        isSelected(day) ? 'text-white'
                                        : isToday(day)  ? 'text-primary-container'
                                        : isWeekend     ? 'text-rose-400'
                                        : 'text-on-surface'
                                    }`}>
                                        {day}
                                    </span>
                                    {/* Event dots */}
                                    {types.length > 0 && (
                                        <div className="flex gap-0.5 mt-1">
                                            {types.map(t => (
                                                <div key={t}
                                                    className="w-1.5 h-1.5 rounded-full"
                                                    style={{ backgroundColor: isSelected(day) ? 'rgba(255,255,255,0.8)' : (TYPE_META[t]?.text.replace('text-', '') || '#94a3b8') }}
                                                />
                                            ))}
                                        </div>
                                    )}
                                    {dayEvs.length > 3 && (
                                        <span className={`text-[8px] font-black mt-0.5 ${isSelected(day) ? 'text-white/70' : 'text-on-surface-variant'}`}>
                                            +{dayEvs.length - 3}
                                        </span>
                                    )}
                                </button>
                            );
                        })}
                    </div>

                    {/* Legend */}
                    <div className="flex flex-wrap gap-x-4 gap-y-1 mt-5 pt-4 border-t border-white/40">
                        {Object.entries(TYPE_META).map(([key, meta]) => (
                            <div key={key} className="flex items-center gap-1.5">
                                <div className={`w-2 h-2 rounded-full ${meta.bg.replace('bg-', 'bg-').replace('100', '400')}`}
                                    style={{ backgroundColor: events.find(e => e.type === key)?.color }} />
                                <span className="text-[10px] text-on-surface-variant font-bold">{meta.label}</span>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Right: Selected day + Upcoming */}
                <div className="space-y-5">
                    {/* Selected day events */}
                    <div className="glass-card rounded-2xl p-5">
                        <h3 className="font-bold text-on-surface mb-1">
                            {selectedDate ? new Date(selectedDate + 'T00:00:00').toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long' }) : 'Pilih tanggal'}
                        </h3>
                        {dayEvents.length === 0 ? (
                            <div className="flex flex-col items-center py-8 gap-2 text-on-surface-variant">
                                <Icon name="event_busy" className="text-4xl opacity-20" />
                                <p className="text-sm">Tidak ada kegiatan</p>
                            </div>
                        ) : (
                            <div className="space-y-3 mt-4">
                                {dayEvents.map(ev => {
                                    const meta = TYPE_META[ev.type] ?? TYPE_META.kegiatan;
                                    return (
                                        <div key={ev.id}
                                            className={`flex gap-3 p-3 rounded-xl border ${meta.bg} ${meta.border}`}
                                        >
                                            <div className={`w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 bg-white/60`}>
                                                <Icon name={meta.icon} className={`text-base ${meta.text}`} filled />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className={`text-sm font-bold ${meta.text} line-clamp-1`}>{ev.title}</p>
                                                <div className="flex items-center gap-2 mt-0.5">
                                                    <Icon name="schedule" className="text-xs text-on-surface-variant" />
                                                    <span className="text-xs text-on-surface-variant">{ev.time}</span>
                                                    {ev.recurring && <span className="text-[9px] bg-white/60 px-1.5 py-0.5 rounded-full text-on-surface-variant font-bold">↻ Rutin</span>}
                                                </div>
                                                {ev.desc && <p className="text-xs text-on-surface-variant mt-1">{ev.desc}</p>}
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </div>

                    {/* Upcoming events */}
                    <div className="glass-card rounded-2xl p-5">
                        <h3 className="font-bold text-on-surface mb-4 flex items-center gap-2">
                            <Icon name="upcoming" className="text-primary-container text-xl" filled />
                            Jadwal Mendatang
                        </h3>
                        <div className="space-y-2">
                            {upcoming.map(ev => {
                                const meta = TYPE_META[ev.type] ?? TYPE_META.kegiatan;
                                const evDate = new Date(ev.date + 'T00:00:00');
                                const isThisToday = ev.date === today;
                                return (
                                    <button
                                        key={ev.id}
                                        onClick={() => setSelected(ev.date)}
                                        className="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-white/40 transition-all text-left group"
                                    >
                                        {/* Date box */}
                                        <div className={`w-10 h-10 rounded-xl flex flex-col items-center justify-center flex-shrink-0 ${isThisToday ? 'bg-primary-container' : 'bg-surface-container'}`}>
                                            <span className={`text-[10px] font-black leading-none ${isThisToday ? 'text-white/70' : 'text-on-surface-variant'}`}>
                                                {evDate.toLocaleDateString('id-ID', { month: 'short' }).toUpperCase()}
                                            </span>
                                            <span className={`text-base font-black leading-tight ${isThisToday ? 'text-white' : 'text-on-surface'}`}>
                                                {evDate.getDate()}
                                            </span>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-sm font-semibold text-on-surface line-clamp-1 group-hover:text-primary-container transition-colors">{ev.title}</p>
                                            <div className="flex items-center gap-2">
                                                <Icon name={meta.icon} className={`text-xs ${meta.text}`} />
                                                <span className="text-xs text-on-surface-variant">{ev.time} · {meta.label}</span>
                                            </div>
                                        </div>
                                    </button>
                                );
                            })}
                        </div>
                    </div>

                    {/* Mini stats */}
                    <div className="grid grid-cols-2 gap-3">
                        <div className="glass-card rounded-2xl p-4 text-center">
                            <p className="text-2xl font-black text-primary-container">{events.length}</p>
                            <p className="text-[10px] font-black uppercase tracking-wider text-on-surface-variant mt-1">Total Event</p>
                        </div>
                        <div className="glass-card rounded-2xl p-4 text-center">
                            <p className="text-2xl font-black text-emerald-600">{upcoming.length}</p>
                            <p className="text-[10px] font-black uppercase tracking-wider text-on-surface-variant mt-1">Mendatang</p>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
