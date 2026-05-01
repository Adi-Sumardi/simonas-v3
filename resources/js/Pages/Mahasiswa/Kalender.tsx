import { Head, router } from '@inertiajs/react';
import { useState, useMemo } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';

// ─── Types ────────────────────────────────────────────────────────────────────
interface CalEvent {
    id: number; title: string; date: string; time: string;
    type: string; color: string; desc?: string; recurring?: boolean;
}
interface KalenderProps extends PageProps {
    events: CalEvent[]; upcoming: CalEvent[]; today: string;
}

// ─── Config ───────────────────────────────────────────────────────────────────
const TYPE_META: Record<string, { label: string; icon: string; bg: string; text: string; border: string; hex: string }> = {
    shalat:   { label: 'Shalat',    icon: 'mosque',         bg: 'bg-blue-100',    text: 'text-blue-700',    border: 'border-blue-200',    hex: '#2563eb' },
    hafalan:  { label: 'Hafalan',   icon: 'auto_stories',   bg: 'bg-emerald-100', text: 'text-emerald-700', border: 'border-emerald-200', hex: '#10b981' },
    akademik: { label: 'Akademik',  icon: 'school',         bg: 'bg-amber-100',   text: 'text-amber-700',   border: 'border-amber-200',   hex: '#d97706' },
    kegiatan: { label: 'Kegiatan',  icon: 'event',          bg: 'bg-purple-100',  text: 'text-purple-700',  border: 'border-purple-200',  hex: '#7c3aed' },
    belajar:  { label: 'Belajar',   icon: 'menu_book',      bg: 'bg-rose-100',    text: 'text-rose-700',    border: 'border-rose-200',    hex: '#e11d48' },
    olahraga: { label: 'Olahraga',  icon: 'fitness_center', bg: 'bg-teal-100',    text: 'text-teal-700',    border: 'border-teal-200',    hex: '#0d9488' },
};
const TYPES = Object.keys(TYPE_META);
const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
const DAYS   = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];

// ─── Event Form Modal ─────────────────────────────────────────────────────────
function EventModal({
    editItem, defaultDate, onClose,
}: { editItem: CalEvent | null; defaultDate: string; onClose: () => void }) {
    const [form, setForm] = useState({
        title:     editItem?.title     ?? '',
        date:      editItem?.date      ?? defaultDate,
        time:      editItem?.time      ?? '08:00',
        type:      editItem?.type      ?? 'kegiatan',
        color:     editItem?.color     ?? TYPE_META['kegiatan'].hex,
        desc:      editItem?.desc      ?? '',
        recurring: editItem?.recurring ?? false,
    });
    const [saving, setSaving] = useState(false);

    function set(f: string, v: string | boolean) {
        setForm(p => {
            const next = { ...p, [f]: v };
            if (f === 'type') next.color = TYPE_META[v as string]?.hex ?? '#6366f1';
            return next;
        });
    }

    function submit(e: React.FormEvent) {
        e.preventDefault();
        setSaving(true);
        const url    = editItem ? `/mahasiswa/kalender/${editItem.id}` : '/mahasiswa/kalender';
        const method = editItem ? router.put : router.post;
        method(url, form, {
            preserveState: true, preserveScroll: true,
            onSuccess: () => onClose(),
            onFinish: () => setSaving(false),
        });
    }

    const meta = TYPE_META[form.type] ?? TYPE_META['kegiatan'];

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
            <div className="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl w-full max-w-md">
                <div className="flex items-center justify-between px-6 py-5 border-b border-white/40">
                    <div className="flex items-center gap-3">
                        <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${meta.bg}`}>
                            <Icon name={meta.icon} className={`text-xl ${meta.text}`} filled />
                        </div>
                        <h3 className="font-bold text-on-surface">
                            {editItem ? 'Edit Kegiatan' : 'Tambah Kegiatan'}
                        </h3>
                    </div>
                    <button onClick={onClose} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center">
                        <Icon name="close" className="text-on-surface-variant" />
                    </button>
                </div>

                <form onSubmit={submit} className="px-6 py-5 space-y-4">
                    {/* Type selector */}
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-2 block">Kategori *</label>
                        <div className="grid grid-cols-3 gap-2">
                            {TYPES.map(t => {
                                const m = TYPE_META[t];
                                return (
                                    <button key={t} type="button" onClick={() => set('type', t)}
                                        className={`flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold border transition-all ${
                                            form.type === t ? `${m.bg} ${m.text} ${m.border}` : 'bg-surface-container text-on-surface-variant border-transparent'
                                        }`}>
                                        <Icon name={m.icon} className="text-sm" filled={form.type === t} />
                                        {m.label}
                                    </button>
                                );
                            })}
                        </div>
                    </div>

                    {/* Title */}
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Judul Kegiatan *</label>
                        <input value={form.title} onChange={e => set('title', e.target.value)}
                            className="glass-input w-full text-sm" placeholder="Contoh: Halaqah Mingguan" required />
                    </div>

                    {/* Date & Time */}
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Tanggal *</label>
                            <input type="date" value={form.date} onChange={e => set('date', e.target.value)}
                                className="glass-input w-full text-sm" required />
                        </div>
                        <div>
                            <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Waktu</label>
                            <input type="time" value={form.time} onChange={e => set('time', e.target.value)}
                                className="glass-input w-full text-sm" />
                        </div>
                    </div>

                    {/* Description */}
                    <div>
                        <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">Keterangan</label>
                        <textarea value={form.desc} onChange={e => set('desc', e.target.value)}
                            rows={2} className="glass-input w-full text-sm resize-none" placeholder="Detail tambahan..." />
                    </div>

                    {/* Recurring */}
                    <label className="flex items-center gap-3 cursor-pointer glass-card rounded-xl px-4 py-3">
                        <input type="checkbox" checked={form.recurring} onChange={e => set('recurring', e.target.checked)}
                            className="w-4 h-4 rounded" />
                        <div>
                            <p className="text-sm font-bold text-on-surface">Kegiatan Berulang</p>
                            <p className="text-xs text-on-surface-variant">Tandai jika ini kegiatan rutin mingguan</p>
                        </div>
                        <Icon name="repeat" className="text-on-surface-variant ml-auto" />
                    </label>

                    <div className="flex gap-3 pt-1">
                        <button type="button" onClick={onClose}
                            className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">
                            Batal
                        </button>
                        <button type="submit" disabled={saving}
                            className={`flex-1 py-2.5 rounded-xl font-bold text-sm text-white transition-colors disabled:opacity-50 flex items-center justify-center gap-2`}
                            style={{ backgroundColor: form.color }}>
                            <Icon name="save" className="text-base" />
                            {saving ? 'Menyimpan...' : editItem ? 'Simpan' : 'Tambah'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

// ─── Main ─────────────────────────────────────────────────────────────────────
export default function Kalender({ events, upcoming, today }: KalenderProps) {
    const todayDate = new Date(today);
    const [currentYear, setYear]   = useState(todayDate.getFullYear());
    const [currentMonth, setMonth] = useState(todayDate.getMonth());
    const [selectedDate, setSelected] = useState<string | null>(today);
    const [filterType, setFilter]  = useState('');
    const [showModal, setShowModal] = useState(false);
    const [editItem, setEditItem]  = useState<CalEvent | null>(null);

    function openAdd(date?: string) { setEditItem(null); setSelected(date ?? today); setShowModal(true); }
    function openEdit(e: CalEvent) { setEditItem(e); setShowModal(true); }
    function closeModal() { setShowModal(false); setEditItem(null); }

    function deleteEvent(e: CalEvent) {
        if (!confirm(`Hapus "${e.title}"?`)) return;
        router.delete(`/mahasiswa/kalender/${e.id}`, { preserveState: true, preserveScroll: true });
    }

    function prevMonth() { if (currentMonth === 0) { setMonth(11); setYear(y => y - 1); } else setMonth(m => m - 1); }
    function nextMonth() { if (currentMonth === 11) { setMonth(0); setYear(y => y + 1); } else setMonth(m => m + 1); }

    // Calendar grid
    const calendarDays = useMemo(() => {
        const firstDay     = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth  = new Date(currentYear, currentMonth + 1, 0).getDate();
        const days: (number | null)[] = [];
        for (let i = 0; i < firstDay; i++) days.push(null);
        for (let d = 1; d <= daysInMonth; d++) days.push(d);
        return days;
    }, [currentYear, currentMonth]);

    const filteredEvents = events.filter(e =>
        (!filterType || e.type === filterType)
    );

    function dateStr(d: number) {
        return `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    }

    function eventsOnDay(d: number): CalEvent[] {
        const ds = dateStr(d);
        return filteredEvents.filter(e => e.date === ds);
    }

    const selectedEvents = selectedDate
        ? filteredEvents.filter(e => e.date === selectedDate)
        : [];

    return (
        <AppLayout searchPlaceholder="Cari kegiatan...">
            <Head title="Kalender Kegiatan" />

            <PageHeader
                title="Kalender Kegiatan"
                subtitle="Jadwal dan kegiatan harian kamu."
                breadcrumbs={[{ label: 'Beranda', href: '/mahasiswa' }, { label: 'Kalender' }]}
                actions={
                    <button onClick={() => openAdd(today)}
                        className="btn-primary flex items-center gap-2 text-sm px-4 py-2.5 rounded-xl font-bold">
                        <Icon name="add" className="text-xl" />
                        Tambah Kegiatan
                    </button>
                }
            />

            {/* Filter type pills */}
            <div className="flex gap-2 mb-5 overflow-x-auto pb-1">
                <button onClick={() => setFilter('')}
                    className={`flex-shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold border transition-all ${
                        !filterType ? 'bg-on-surface text-white border-transparent' : 'glass-card border-transparent text-on-surface-variant'
                    }`}>
                    Semua ({events.length})
                </button>
                {TYPES.map(t => {
                    const m = TYPE_META[t];
                    const cnt = events.filter(e => e.type === t).length;
                    if (cnt === 0) return null;
                    return (
                        <button key={t} onClick={() => setFilter(t)}
                            className={`flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border transition-all ${
                                filterType === t ? `${m.bg} ${m.text} ${m.border}` : 'glass-card border-transparent text-on-surface-variant'
                            }`}>
                            <Icon name={m.icon} className="text-sm" filled={filterType === t} />
                            {m.label} ({cnt})
                        </button>
                    );
                })}
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {/* ── Calendar grid ── */}
                <div className="lg:col-span-2">
                    <div className="glass-card rounded-3xl p-5">
                        {/* Month navigation */}
                        <div className="flex items-center justify-between mb-5">
                            <button onClick={prevMonth} className="w-9 h-9 rounded-xl bg-surface-container hover:bg-white/80 flex items-center justify-center transition-colors">
                                <Icon name="chevron_left" className="text-on-surface-variant text-xl" />
                            </button>
                            <h2 className="font-display font-bold text-on-surface">
                                {MONTHS[currentMonth]} {currentYear}
                            </h2>
                            <button onClick={nextMonth} className="w-9 h-9 rounded-xl bg-surface-container hover:bg-white/80 flex items-center justify-center transition-colors">
                                <Icon name="chevron_right" className="text-on-surface-variant text-xl" />
                            </button>
                        </div>

                        {/* Day headers */}
                        <div className="grid grid-cols-7 mb-2">
                            {DAYS.map(d => (
                                <div key={d} className="text-center text-[10px] font-black text-on-surface-variant py-1">{d}</div>
                            ))}
                        </div>

                        {/* Day cells */}
                        <div className="grid grid-cols-7 gap-1">
                            {calendarDays.map((day, i) => {
                                if (!day) return <div key={`empty-${i}`} />;
                                const ds       = dateStr(day);
                                const dayEvs   = eventsOnDay(day);
                                const isToday  = ds === today;
                                const isSel    = ds === selectedDate;

                                return (
                                    <button key={day} onClick={() => setSelected(ds)}
                                        className={`relative p-1.5 rounded-xl flex flex-col items-center min-h-[52px] transition-all ${
                                            isSel   ? 'bg-primary-container text-white shadow-md'
                                            : isToday ? 'ring-2 ring-primary-container/60 bg-primary-fixed/30'
                                            : 'hover:bg-surface-container'
                                        }`}>
                                        <span className={`text-xs font-bold ${isSel ? 'text-white' : isToday ? 'text-primary-container' : 'text-on-surface'}`}>
                                            {day}
                                        </span>
                                        {/* Event dots */}
                                        <div className="flex gap-0.5 flex-wrap justify-center mt-0.5">
                                            {dayEvs.slice(0, 3).map((ev, j) => (
                                                <div key={j} className="w-1.5 h-1.5 rounded-full flex-shrink-0"
                                                    style={{ backgroundColor: ev.color }} />
                                            ))}
                                            {dayEvs.length > 3 && (
                                                <span className="text-[8px] text-on-surface-variant">+{dayEvs.length - 3}</span>
                                            )}
                                        </div>
                                    </button>
                                );
                            })}
                        </div>

                        {/* Double-click hint */}
                        <p className="text-xs text-on-surface-variant text-center mt-4">
                            Klik tanggal untuk lihat kegiatan · <button onClick={() => openAdd(selectedDate ?? today)} className="text-primary-container font-bold hover:underline">+ Tambah di tanggal ini</button>
                        </p>
                    </div>
                </div>

                {/* ── Right: selected day events + upcoming ── */}
                <div className="space-y-4">

                    {/* Selected day */}
                    <div className="glass-card rounded-3xl p-5">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="font-bold text-on-surface text-sm">
                                {selectedDate === today ? 'Hari Ini' : selectedDate ?? '—'}
                            </h3>
                            <button onClick={() => openAdd(selectedDate ?? today)}
                                className="w-8 h-8 rounded-xl bg-primary-container/10 hover:bg-primary-container/20 text-primary-container flex items-center justify-center transition-colors">
                                <Icon name="add" className="text-base" />
                            </button>
                        </div>

                        {selectedEvents.length === 0 ? (
                            <div className="text-center py-6 text-on-surface-variant">
                                <Icon name="event_busy" className="text-3xl opacity-30 mb-2" />
                                <p className="text-sm">Tidak ada kegiatan</p>
                            </div>
                        ) : (
                            <div className="space-y-3">
                                {selectedEvents.map(ev => {
                                    const m = TYPE_META[ev.type] ?? TYPE_META['kegiatan'];
                                    return (
                                        <div key={ev.id} className={`flex items-start gap-3 p-3 rounded-2xl border ${m.border} ${m.bg}`}>
                                            <div className="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" style={{ backgroundColor: ev.color }} />
                                            <div className="flex-1 min-w-0">
                                                <p className="font-bold text-sm text-on-surface line-clamp-1">{ev.title}</p>
                                                <p className="text-xs text-on-surface-variant">{ev.time} · {m.label}</p>
                                                {ev.desc && <p className="text-xs text-on-surface-variant mt-0.5 line-clamp-2">{ev.desc}</p>}
                                                {ev.recurring && (
                                                    <span className="text-[10px] font-bold text-on-surface-variant flex items-center gap-0.5 mt-0.5">
                                                        <Icon name="repeat" className="text-xs" /> Berulang
                                                    </span>
                                                )}
                                            </div>
                                            <div className="flex gap-1 flex-shrink-0">
                                                <button onClick={() => openEdit(ev)} className="w-7 h-7 rounded-lg hover:bg-blue-100 hover:text-blue-600 text-on-surface-variant flex items-center justify-center transition-colors">
                                                    <Icon name="edit" className="text-xs" />
                                                </button>
                                                <button onClick={() => deleteEvent(ev)} className="w-7 h-7 rounded-lg hover:bg-rose-100 hover:text-rose-600 text-on-surface-variant flex items-center justify-center transition-colors">
                                                    <Icon name="delete" className="text-xs" />
                                                </button>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </div>

                    {/* Upcoming */}
                    <div className="glass-card rounded-3xl p-5">
                        <h3 className="font-bold text-on-surface text-sm mb-4 flex items-center gap-2">
                            <Icon name="upcoming" className="text-purple-600 text-lg" filled />
                            Akan Datang
                        </h3>
                        {upcoming.length === 0 ? (
                            <p className="text-sm text-on-surface-variant text-center py-4">Tidak ada kegiatan mendatang</p>
                        ) : (
                            <div className="space-y-3">
                                {upcoming.map(ev => {
                                    const m = TYPE_META[ev.type] ?? TYPE_META['kegiatan'];
                                    return (
                                        <div key={ev.id} className="flex items-center gap-3 cursor-pointer hover:opacity-80"
                                            onClick={() => { setSelected(ev.date); setYear(new Date(ev.date).getFullYear()); setMonth(new Date(ev.date).getMonth()); }}>
                                            <div className={`w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 ${m.bg}`}>
                                                <Icon name={m.icon} className={`text-base ${m.text}`} filled />
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <p className="font-bold text-sm text-on-surface line-clamp-1">{ev.title}</p>
                                                <p className="text-xs text-on-surface-variant">{ev.date} · {ev.time}</p>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {showModal && (
                <EventModal
                    editItem={editItem}
                    defaultDate={selectedDate ?? today}
                    onClose={closeModal}
                />
            )}
        </AppLayout>
    );
}
