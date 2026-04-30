import { useState, useMemo } from 'react';
import {
    RadarChart, Radar, PolarGrid, PolarAngleAxis, PolarRadiusAxis,
    Tooltip, Legend, ResponsiveContainer,
} from 'recharts';
import { Icon } from '@/Components/ui/Icon';

interface ScoreEntry {
    subject: string;
    value: number;
    fullMark: number;
}

interface Student {
    id: number;
    name: string;
    asrama: string;
    total: number;
    scores: ScoreEntry[];
}

interface Props {
    students: Student[];
    asramas: string[];
}

// Up to 3 colors for comparison
const COLORS = ['#2563eb', '#10b981', '#f59e0b'];
const BG     = ['rgba(37,99,235,0.15)', 'rgba(16,185,129,0.15)', 'rgba(245,158,11,0.15)'];

function ScoreBadge({ value }: { value: number }) {
    const color = value >= 85 ? 'text-emerald-600 bg-emerald-50 border-emerald-200'
                : value >= 70 ? 'text-amber-600 bg-amber-50 border-amber-200'
                : 'text-rose-600 bg-rose-50 border-rose-200';
    return (
        <span className={`inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border ${color}`}>
            {value}
        </span>
    );
}

export function MahasiswaRadarChart({ students, asramas }: Props) {
    const [search, setSearch]       = useState('');
    const [asramaFilter, setAsrama] = useState('');
    const [selected, setSelected]   = useState<number[]>([students[0]?.id ?? 1]);
    const [showDropdown, setShowDropdown] = useState(false);

    // Filter candidates list
    const candidates = useMemo(() => {
        return students.filter(s =>
            (asramaFilter === '' || s.asrama === asramaFilter) &&
            (search === '' || s.name.toLowerCase().includes(search.toLowerCase()))
        );
    }, [students, asramaFilter, search]);

    // Selected students (max 3)
    const selectedStudents = useMemo(() =>
        selected.map(id => students.find(s => s.id === id)).filter(Boolean) as Student[]
    , [selected, students]);

    function toggleStudent(id: number) {
        setSelected(prev => {
            if (prev.includes(id)) return prev.filter(x => x !== id);
            if (prev.length >= 3) return [...prev.slice(1), id]; // replace oldest
            return [...prev, id];
        });
    }

    // Merge scores for multi-radar: [{subject, student1, student2, ...}]
    const chartData = useMemo(() => {
        if (selectedStudents.length === 0) return [];
        const base = selectedStudents[0].scores.map(s => ({ subject: s.subject }));
        return base.map((b, i) => {
            const row: Record<string, string | number> = { subject: b.subject };
            selectedStudents.forEach(st => { row[st.name] = st.scores[i].value; });
            return row;
        });
    }, [selectedStudents]);

    // Average per dimension across filtered students
    const avgData = useMemo(() => {
        if (candidates.length === 0) return [];
        return candidates[0].scores.map((s, i) => ({
            subject: s.subject,
            avg: Math.round(candidates.reduce((acc, c) => acc + c.scores[i].value, 0) / candidates.length),
        }));
    }, [candidates]);

    return (
        <div className="glass-card rounded-2xl overflow-hidden">
            {/* Header */}
            <div className="p-6 border-b border-white/40">
                <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 className="font-display text-lg font-bold text-on-surface">
                            Radar Perkembangan Mahasiswa
                        </h2>
                        <p className="text-xs text-on-surface-variant mt-0.5">
                            Pilih hingga 3 mahasiswa untuk membandingkan 6 dimensi kompetensi
                        </p>
                    </div>
                    {/* Filters */}
                    <div className="flex gap-2 flex-wrap">
                        <select
                            value={asramaFilter}
                            onChange={e => { setAsrama(e.target.value); setSelected([]); }}
                            className="glass-input text-sm py-1.5 pr-8 min-w-[130px]"
                        >
                            <option value="">Semua Asrama</option>
                            {asramas.map(a => <option key={a} value={a}>{a}</option>)}
                        </select>
                        <div className="relative">
                            <div className="flex items-center gap-2 glass-input py-1.5 px-3">
                                <Icon name="search" className="text-base text-on-surface-variant" />
                                <input
                                    type="text"
                                    value={search}
                                    onChange={e => { setSearch(e.target.value); setShowDropdown(true); }}
                                    onFocus={() => setShowDropdown(true)}
                                    placeholder="Cari mahasiswa..."
                                    className="bg-transparent outline-none text-sm w-36"
                                />
                                {search && (
                                    <button onClick={() => { setSearch(''); setShowDropdown(false); }}>
                                        <Icon name="close" className="text-sm text-outline" />
                                    </button>
                                )}
                            </div>
                            {showDropdown && candidates.length > 0 && (
                                <div
                                    className="absolute top-full left-0 mt-1 w-64 bg-white/95 backdrop-blur border border-white/60 rounded-xl shadow-2xl z-50 overflow-hidden"
                                    onMouseLeave={() => setShowDropdown(false)}
                                >
                                    {candidates.slice(0, 8).map(s => {
                                        const isSelected = selected.includes(s.id);
                                        return (
                                            <button
                                                key={s.id}
                                                onClick={() => { toggleStudent(s.id); setShowDropdown(false); setSearch(''); }}
                                                className={`w-full flex items-center justify-between px-4 py-2.5 hover:bg-blue-50 transition-colors text-left ${isSelected ? 'bg-blue-50' : ''}`}
                                            >
                                                <div>
                                                    <p className="text-sm font-semibold text-on-surface">{s.name}</p>
                                                    <p className="text-xs text-on-surface-variant">{s.asrama}</p>
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <ScoreBadge value={s.total} />
                                                    {isSelected && <Icon name="check_circle" className="text-primary-container text-base" filled />}
                                                </div>
                                            </button>
                                        );
                                    })}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>

            <div className="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                {/* Left: student list */}
                <div className="space-y-3">
                    <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">
                        {asramaFilter || 'Semua Asrama'} · {candidates.length} Mahasiswa
                    </p>
                    <div className="space-y-1.5 max-h-80 overflow-y-auto pr-1">
                        {candidates.map((s) => {
                            const selIdx = selected.indexOf(s.id);
                            const isSelected = selIdx !== -1;
                            const color = isSelected ? COLORS[selIdx] : undefined;
                            return (
                                <button
                                    key={s.id}
                                    onClick={() => toggleStudent(s.id)}
                                    className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border transition-all text-left ${
                                        isSelected
                                            ? 'border-2 shadow-md'
                                            : 'bg-white/40 border-white/60 hover:bg-white/70'
                                    }`}
                                    style={isSelected ? { borderColor: color, backgroundColor: BG[selIdx] } : {}}
                                >
                                    <div
                                        className="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black text-white flex-shrink-0"
                                        style={{ backgroundColor: color ?? '#94a3b8' }}
                                    >
                                        {s.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                    </div>
                                    <div className="flex-1 min-w-0">
                                        <p className="text-sm font-semibold text-on-surface truncate">{s.name}</p>
                                        <p className="text-[10px] text-on-surface-variant">{s.asrama}</p>
                                    </div>
                                    <ScoreBadge value={s.total} />
                                </button>
                            );
                        })}
                    </div>

                    {/* Average stats */}
                    <div className="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                        <p className="text-[10px] font-black uppercase tracking-widest text-blue-600 mb-3">
                            Rata-rata {asramaFilter || 'Semua'}
                        </p>
                        <div className="space-y-1.5">
                            {avgData.map(d => (
                                <div key={d.subject} className="flex items-center gap-2">
                                    <span className="text-xs text-on-surface-variant w-24 flex-shrink-0">{d.subject}</span>
                                    <div className="flex-1 h-1.5 bg-blue-100 rounded-full overflow-hidden">
                                        <div
                                            className="h-full bg-primary-container rounded-full transition-all"
                                            style={{ width: `${d.avg}%` }}
                                        />
                                    </div>
                                    <span className="text-xs font-bold text-primary-container w-7 text-right">{d.avg}</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Right: Radar chart */}
                <div className="lg:col-span-2">
                    {selectedStudents.length === 0 ? (
                        <div className="h-72 flex flex-col items-center justify-center gap-3 text-on-surface-variant">
                            <Icon name="radar" className="text-5xl opacity-20" />
                            <p className="text-sm">Pilih mahasiswa dari daftar untuk melihat radar</p>
                        </div>
                    ) : (
                        <>
                            {/* Selected chips */}
                            <div className="flex flex-wrap gap-2 mb-4">
                                {selectedStudents.map((s, i) => (
                                    <div
                                        key={s.id}
                                        className="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold text-white"
                                        style={{ backgroundColor: COLORS[i] }}
                                    >
                                        {s.name}
                                        <button onClick={() => toggleStudent(s.id)} className="opacity-70 hover:opacity-100">
                                            <Icon name="close" className="text-xs" />
                                        </button>
                                    </div>
                                ))}
                            </div>

                            <ResponsiveContainer width="100%" height={320}>
                                <RadarChart data={chartData} margin={{ top: 10, right: 20, bottom: 10, left: 20 }}>
                                    <PolarGrid stroke="#e2e8f0" strokeDasharray="3 3" />
                                    <PolarAngleAxis
                                        dataKey="subject"
                                        tick={{ fontSize: 11, fontWeight: 700, fill: '#64748b' }}
                                    />
                                    <PolarRadiusAxis
                                        angle={30}
                                        domain={[0, 100]}
                                        tick={{ fontSize: 9, fill: '#94a3b8' }}
                                        tickCount={6}
                                    />
                                    {selectedStudents.map((s, i) => (
                                        <Radar
                                            key={s.id}
                                            name={s.name}
                                            dataKey={s.name}
                                            stroke={COLORS[i]}
                                            fill={COLORS[i]}
                                            fillOpacity={0.2}
                                            strokeWidth={2.5}
                                            dot={{ r: 4, fill: COLORS[i], strokeWidth: 0 }}
                                        />
                                    ))}
                                    <Tooltip
                                        contentStyle={{
                                            background: 'rgba(255,255,255,0.95)',
                                            border: '1px solid rgba(255,255,255,0.6)',
                                            borderRadius: 12,
                                            boxShadow: '0 10px 30px rgba(0,0,0,0.1)',
                                            fontSize: 12,
                                        }}
                                        formatter={(val, name) => [`${val}/100`, name]}
                                    />
                                    {selectedStudents.length > 1 && (
                                        <Legend
                                            wrapperStyle={{ fontSize: 11, fontWeight: 700, paddingTop: 8 }}
                                        />
                                    )}
                                </RadarChart>
                            </ResponsiveContainer>

                            {/* Score breakdown table */}
                            <div className="mt-4 overflow-x-auto">
                                <table className="w-full text-xs">
                                    <thead>
                                        <tr className="border-b border-white/40">
                                            <th className="text-left py-2 text-on-surface-variant font-black uppercase tracking-wider">Dimensi</th>
                                            {selectedStudents.map((s, i) => (
                                                <th key={s.id} className="py-2 text-right font-black" style={{ color: COLORS[i] }}>
                                                    {s.name.split(' ')[0]}
                                                </th>
                                            ))}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {chartData.map(row => (
                                            <tr key={row.subject as string} className="border-b border-white/20 hover:bg-white/30 transition-colors">
                                                <td className="py-2 text-on-surface font-semibold">{row.subject as string}</td>
                                                {selectedStudents.map(s => (
                                                    <td key={s.id} className="py-2 text-right">
                                                        <ScoreBadge value={row[s.name] as number} />
                                                    </td>
                                                ))}
                                            </tr>
                                        ))}
                                        <tr className="bg-blue-50/50">
                                            <td className="py-2 font-black text-on-surface text-[10px] uppercase tracking-wider">Total Avg</td>
                                            {selectedStudents.map((s, i) => (
                                                <td key={s.id} className="py-2 text-right">
                                                    <span className="font-black text-sm" style={{ color: COLORS[i] }}>{s.total}</span>
                                                </td>
                                            ))}
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </>
                    )}
                </div>
            </div>
        </div>
    );
}
