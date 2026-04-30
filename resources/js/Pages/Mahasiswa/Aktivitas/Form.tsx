import { Head, Link, router } from '@inertiajs/react';
import { useState, FormEvent } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';

const PRAYERS = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'] as const;
const LEADERSHIP_OPTIONS = [
    { id: 'org_head', label: 'Organization Head', sub: 'Student Executive Board', icon: 'groups', color: 'bg-rose-100 text-rose-600' },
    { id: 'proj_mgr', label: 'Project Manager', sub: 'Event / Project', icon: 'record_voice_over', color: 'bg-blue-100 text-blue-600' },
    { id: 'committee', label: 'Committee Member', sub: 'Activity Committee', icon: 'diversity_3', color: 'bg-amber-100 text-amber-600' },
];
const CHAR_MARKS = [
    { key: 'integrity', label: 'Integrity (Amanah)' },
    { key: 'perseverance', label: 'Perseverance (Sabar)' },
    { key: 'excellence', label: 'Excellence (Ihsan)' },
];

interface AktivitasFormProps extends PageProps {}

export default function AktivitasForm({}: AktivitasFormProps) {
    // Form state
    const [prayers, setPrayers] = useState<Record<string, boolean>>({
        Subuh: false, Dzuhur: false, Ashar: false, Maghrib: false, Isya: false,
    });
    const [achievement, setAchievement] = useState('');
    const [competitionLevel, setCompetitionLevel] = useState('');
    const [studySubject, setStudySubject] = useState('');
    const [studyDuration, setStudyDuration] = useState(2);
    const [studyNotes, setStudyNotes] = useState('');
    const [leadership, setLeadership] = useState<string[]>([]);
    const [weeklyRevenue, setWeeklyRevenue] = useState('');
    const [newCustomers, setNewCustomers] = useState('');
    const [gpa, setGpa] = useState('');
    const [charMarks, setCharMarks] = useState<Record<string, number>>({
        integrity: 0, perseverance: 0, excellence: 0,
    });

    function toggleLeadership(id: string) {
        setLeadership((prev) =>
            prev.includes(id) ? prev.filter((l) => l !== id) : [...prev, id]
        );
    }

    function setStarRating(key: string, rating: number) {
        setCharMarks((prev) => ({ ...prev, [key]: rating }));
    }

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        router.post('/mahasiswa/aktivitas', {
            prayers,
            achievement,
            competition_level: competitionLevel,
            study_subject: studySubject,
            study_duration: studyDuration,
            study_notes: studyNotes,
            leadership,
            weekly_revenue: weeklyRevenue,
            new_customers: newCustomers,
            gpa,
            char_marks: charMarks,
        });
    }

    return (
        <AppLayout searchPlaceholder="Cari aktivitas...">
            <Head title="Input Aktivitas" />

            <PageHeader
                title="Input Aktivitas"
                subtitle="Record your daily spiritual and academic progress for the academic sanctuary."
                breadcrumbs={[
                    { label: 'Beranda', href: '/mahasiswa' },
                    { label: 'Aktivitas', href: '/mahasiswa/aktivitas' },
                    { label: 'Input Baru' },
                ]}
            />

            <form onSubmit={handleSubmit} className="max-w-5xl space-y-8">

                {/* Grid bento primary */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {/* Prayer Attendance */}
                    <div className="glass-card rounded-2xl p-6 border-l-4 border-l-primary-container">
                        <div className="flex items-center gap-3 mb-4">
                            <Icon name="auto_awesome" className="text-xl text-primary-container" />
                            <h3 className="font-body text-title-sm font-semibold">Prayer Attendance</h3>
                        </div>
                        <div className="grid grid-cols-5 gap-2">
                            {PRAYERS.map((p) => (
                                <label key={p} className="flex flex-col items-center cursor-pointer">
                                    <span className="text-label-caps text-on-surface-variant mb-2">{p}</span>
                                    <input
                                        type="checkbox"
                                        checked={prayers[p]}
                                        onChange={(e) => setPrayers((prev) => ({ ...prev, [p]: e.target.checked }))}
                                        className="w-8 h-8 rounded-lg text-primary-container focus:ring-primary-container/20 border-outline-variant bg-white/40 shadow-inner-soft"
                                    />
                                </label>
                            ))}
                        </div>
                    </div>

                    {/* Academic Achievements */}
                    <div className="glass-card rounded-2xl p-6">
                        <div className="flex items-center gap-3 mb-4">
                            <Icon name="military_tech" className="text-xl text-warning" />
                            <h3 className="font-body text-title-sm font-semibold">Academic Achievements</h3>
                        </div>
                        <div className="space-y-3">
                            <input
                                type="text"
                                value={achievement}
                                onChange={(e) => setAchievement(e.target.value)}
                                placeholder="Achievement Name (e.g. Dean's List)"
                                className="glass-input w-full text-body-sm"
                            />
                            <div className="flex gap-2">
                                <select
                                    value={competitionLevel}
                                    onChange={(e) => setCompetitionLevel(e.target.value)}
                                    className="glass-input flex-1 text-body-sm"
                                >
                                    <option value="">Competition Level</option>
                                    <option>International</option>
                                    <option>National</option>
                                    <option>University</option>
                                    <option>Faculty</option>
                                </select>
                                <button type="button" className="bg-primary-container text-on-primary p-3 rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                                    <Icon name="add" className="text-xl" />
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* Study Sessions */}
                    <div className="glass-card rounded-2xl p-6 md:row-span-2">
                        <div className="flex items-center gap-3 mb-6">
                            <Icon name="menu_book" className="text-xl text-purple-500" />
                            <h3 className="font-body text-title-sm font-semibold">Study Sessions</h3>
                        </div>
                        <div className="space-y-6">
                            <div>
                                <label className="block text-label-caps text-on-surface-variant mb-2">Subject Name</label>
                                <input
                                    type="text"
                                    value={studySubject}
                                    onChange={(e) => setStudySubject(e.target.value)}
                                    placeholder="E.g. Discrete Mathematics"
                                    className="glass-input w-full text-body-sm"
                                />
                            </div>
                            <div>
                                <div className="flex justify-between mb-2">
                                    <label className="text-label-caps text-on-surface-variant">Duration (Hours)</label>
                                    <span className="text-label-caps text-primary-container font-bold">{studyDuration}h</span>
                                </div>
                                <input
                                    type="range"
                                    min="0.5" max="10" step="0.5"
                                    value={studyDuration}
                                    onChange={(e) => setStudyDuration(Number(e.target.value))}
                                    className="w-full h-2 bg-surface-container rounded-lg appearance-none cursor-pointer accent-primary"
                                />
                                <div className="flex justify-between text-xs font-bold text-on-surface-variant mt-1">
                                    <span>0.5 Hr</span>
                                    <span>10 Hrs</span>
                                </div>
                            </div>
                            <div className="p-4 bg-primary/5 rounded-2xl border border-primary/10">
                                <div className="flex justify-between items-center">
                                    <span className="text-sm font-semibold text-primary-container">Target: 4h/day</span>
                                    <span className="text-xs font-bold bg-white/50 px-2 py-1 rounded text-primary-container">
                                        {studyDuration >= 4 ? 'On Track' : 'Progressing'}
                                    </span>
                                </div>
                                <div className="w-full h-1.5 bg-surface-container rounded-full mt-3 overflow-hidden">
                                    <div
                                        className="h-full bg-primary-container rounded-full transition-all"
                                        style={{ width: `${Math.min(100, (studyDuration / 4) * 100)}%` }}
                                    />
                                </div>
                            </div>
                            <textarea
                                value={studyNotes}
                                onChange={(e) => setStudyNotes(e.target.value)}
                                placeholder="Session notes..."
                                rows={4}
                                className="glass-input w-full text-body-sm resize-none"
                            />
                        </div>
                    </div>

                    {/* Leadership Roles */}
                    <div className="glass-card rounded-2xl p-6">
                        <div className="flex items-center gap-3 mb-4">
                            <Icon name="diversity_3" className="text-xl text-rose-500" />
                            <h3 className="font-body text-title-sm font-semibold">Leadership Roles</h3>
                        </div>
                        <div className="space-y-3">
                            {LEADERSHIP_OPTIONS.map((opt) => {
                                const active = leadership.includes(opt.id);
                                return (
                                    <button
                                        key={opt.id}
                                        type="button"
                                        onClick={() => toggleLeadership(opt.id)}
                                        className={`flex items-center gap-4 w-full bg-white/50 p-3 rounded-xl border transition-all ${
                                            active ? 'border-primary-container shadow-glow/10' : 'border-white'
                                        }`}
                                    >
                                        <div className={`p-2 rounded-lg ${opt.color}`}>
                                            <Icon name={opt.icon} className="text-xl" />
                                        </div>
                                        <div className="flex-1 text-left">
                                            <p className="text-sm font-bold text-on-surface">{opt.label}</p>
                                            <p className="text-xs text-on-surface-variant">{opt.sub}</p>
                                        </div>
                                        <Icon
                                            name={active ? 'check_circle' : 'radio_button_unchecked'}
                                            className={`text-xl ${active ? 'text-primary-container' : 'text-outline-variant'}`}
                                            filled={active}
                                        />
                                    </button>
                                );
                            })}
                        </div>
                    </div>
                </div>

                {/* Entrepreneurship + GPA */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div className="glass-card rounded-2xl p-6 md:col-span-2">
                        <div className="flex items-center gap-3 mb-4">
                            <Icon name="trending_up" className="text-xl text-emerald-500" />
                            <h3 className="font-body text-title-sm font-semibold">Entrepreneurship Activity</h3>
                        </div>
                        <div className="grid grid-cols-2 gap-4">
                            <div className="glass-input rounded-xl p-4">
                                <label className="block text-label-caps text-on-surface-variant mb-1">Weekly Revenue</label>
                                <div className="flex items-center">
                                    <span className="text-on-surface-variant font-bold mr-1">Rp</span>
                                    <input
                                        type="number"
                                        value={weeklyRevenue}
                                        onChange={(e) => setWeeklyRevenue(e.target.value)}
                                        placeholder="0"
                                        className="bg-transparent border-none focus:ring-0 outline-none w-full font-display text-2xl"
                                    />
                                </div>
                            </div>
                            <div className="glass-input rounded-xl p-4">
                                <label className="block text-label-caps text-on-surface-variant mb-1">New Customers</label>
                                <input
                                    type="number"
                                    value={newCustomers}
                                    onChange={(e) => setNewCustomers(e.target.value)}
                                    placeholder="0"
                                    className="bg-transparent border-none focus:ring-0 outline-none w-full font-display text-2xl"
                                />
                            </div>
                        </div>
                    </div>

                    {/* GPA */}
                    <div className="glass-card rounded-2xl p-6 bg-gradient-to-br from-primary-container to-primary text-on-primary border-0 shadow-xl shadow-blue-500/30">
                        <div className="flex items-center gap-3 mb-4">
                            <Icon name="school" className="text-xl text-on-primary/80" />
                            <h3 className="font-body text-title-sm font-semibold text-on-primary">Semester GPA</h3>
                        </div>
                        <div className="flex flex-col items-center justify-center py-2">
                            <input
                                type="number"
                                value={gpa}
                                onChange={(e) => setGpa(e.target.value)}
                                min="0" max="4" step="0.01"
                                placeholder="4.00"
                                className="bg-transparent border-none focus:ring-0 outline-none text-center font-display text-5xl text-on-primary placeholder:text-on-primary/30 w-full"
                            />
                            <p className="text-xs text-on-primary/60 font-bold uppercase mt-2">Target: 3.85</p>
                        </div>
                    </div>
                </div>

                {/* Islamic Character Marks */}
                <div className="glass-card rounded-2xl p-6">
                    <div className="flex items-center gap-3 mb-6">
                        <Icon name="verified" className="text-xl text-warning" />
                        <h3 className="font-body text-title-sm font-semibold">Islamic Character Marks</h3>
                    </div>
                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        {CHAR_MARKS.map(({ key, label }) => (
                            <div key={key} className="space-y-3">
                                <p className="text-label-caps text-on-surface-variant">{label}</p>
                                <div className="flex gap-1">
                                    {[1, 2, 3, 4, 5].map((star) => (
                                        <button
                                            key={star}
                                            type="button"
                                            onClick={() => setStarRating(key, star)}
                                            aria-label={`Rate ${star} stars`}
                                        >
                                            <Icon
                                                name="star"
                                                className={`text-2xl transition-colors ${
                                                    star <= charMarks[key] ? 'text-warning' : 'text-outline-variant'
                                                }`}
                                                filled={star <= charMarks[key]}
                                            />
                                        </button>
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Footer actions */}
                <div className="flex items-center justify-end gap-4 py-4">
                    <Link
                        href="/mahasiswa/aktivitas"
                        className="px-8 py-3 rounded-xl border-2 border-primary-container/30 text-primary-container font-bold hover:bg-primary-container/5 transition-all"
                    >
                        Discard Changes
                    </Link>
                    <button
                        type="submit"
                        className="px-12 py-3 rounded-xl bg-primary-container text-on-primary font-bold shadow-xl shadow-blue-600/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
                    >
                        Save Activity Log
                    </button>
                </div>
            </form>
        </AppLayout>
    );
}
