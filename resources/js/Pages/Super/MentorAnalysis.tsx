import { AppLayout } from '@/Layouts/AppLayout';
import { Head, Link } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { PageHeader } from '@/Components/ui/PageHeader';
import { 
    ResponsiveContainer, 
    XAxis, 
    YAxis, 
    CartesianGrid, 
    Tooltip, 
    Radar, 
    RadarChart, 
    PolarGrid, 
    PolarAngleAxis, 
    PolarRadiusAxis,
    AreaChart,
    Area
} from 'recharts';

interface Mentor {
    id: number;
    name: string;
    email: string;
    asrama: string;
    avatar?: string;
    last_login_at?: string;
}

interface ActivityTrend {
    month: string;
    count: number;
}

interface MenteeStat {
    name: string;
    avatar?: string;
    values: Array<{
        subject: string;
        A: number;
        fullMark: number;
    }>;
}

interface Props {
    mentor: Mentor;
    activityTrend: ActivityTrend[];
    menteeStats: MenteeStat[];
}

export default function MentorAnalysis({ mentor, activityTrend, menteeStats }: Props) {
    return (
        <AppLayout>
            <Head title={`Analisis Mentor - ${mentor.name}`} />

            <div className="mb-6">
                <Link 
                    href="/super/mentor"
                    className="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary-container transition-colors"
                >
                    <Icon name="arrow_back" className="text-lg" />
                    Kembali ke Daftar Mentor
                </Link>
            </div>

            <PageHeader 
                title={`Analisis Performa: ${mentor.name}`} 
                subtitle={`Menganalisis kontribusi mentor dan perkembangan mentee di Asrama ${mentor.asrama}`}
            />

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                {/* Mentor Activity Chart */}
                <div className="lg:col-span-2 glass-card p-6 rounded-2xl">
                    <div className="flex items-center justify-between mb-6">
                        <h3 className="font-display font-black text-on-surface flex items-center gap-2">
                            <Icon name="show_chart" className="text-blue-500" />
                            Tren Aktivitas Mentor (6 Bulan Terakhir)
                        </h3>
                        <span className="text-[10px] bg-blue-100 text-blue-600 px-2 py-1 rounded-full font-bold uppercase tracking-wider">
                            Total Kontribusi
                        </span>
                    </div>
                    <div className="h-[300px] min-h-[300px] w-full relative">
                        <ResponsiveContainer width="100%" height="100%" minWidth={0} minHeight={0}>
                            <AreaChart data={activityTrend}>
                                <defs>
                                    <linearGradient id="colorCount" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="5%" stopColor="#3b82f6" stopOpacity={0.3}/>
                                        <stop offset="95%" stopColor="#3b82f6" stopOpacity={0}/>
                                    </linearGradient>
                                </defs>
                                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#e2e8f0" />
                                <XAxis dataKey="month" stroke="#94a3b8" fontSize={12} tickLine={false} axisLine={false} />
                                <YAxis stroke="#94a3b8" fontSize={12} tickLine={false} axisLine={false} />
                                <Tooltip 
                                    contentStyle={{ 
                                        backgroundColor: 'rgba(255,255,255,0.9)', 
                                        borderRadius: '12px', 
                                        border: 'none', 
                                        boxShadow: '0 10px 15px -3px rgba(0,0,0,0.1)' 
                                    }} 
                                />
                                <Area type="monotone" dataKey="count" name="Jumlah Penilaian" stroke="#3b82f6" strokeWidth={3} fillOpacity={1} fill="url(#colorCount)" />
                            </AreaChart>
                        </ResponsiveContainer>
                    </div>
                </div>

                {/* Mentor Quick Info */}
                <div className="glass-card p-6 rounded-2xl flex flex-col items-center justify-center text-center">
                    <div className="w-24 h-24 rounded-3xl bg-surface-container overflow-hidden shadow-xl mb-4 border-4 border-white">
                        {mentor.avatar ? (
                            <img src={mentor.avatar} alt={mentor.name} className="w-full h-full object-cover" />
                        ) : (
                            <div className="w-full h-full flex items-center justify-center bg-primary-container text-white text-3xl font-black">
                                {mentor.name.charAt(0)}
                            </div>
                        )}
                    </div>
                    <h4 className="text-xl font-display font-black text-on-surface mb-1">{mentor.name}</h4>
                    <p className="text-on-surface-variant text-sm mb-6">{mentor.email}</p>
                    
                    <div className="grid grid-cols-2 gap-3 w-full">
                        <div className="p-3 rounded-xl bg-blue-50 border border-blue-100">
                            <p className="text-[10px] text-blue-400 font-bold uppercase tracking-wider mb-1">Mentees</p>
                            <p className="text-xl font-black text-blue-600">{menteeStats.length}</p>
                        </div>
                        <div className="p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                            <p className="text-[10px] text-emerald-400 font-bold uppercase tracking-wider mb-1">Total Logs</p>
                            <p className="text-xl font-black text-emerald-600">
                                {activityTrend.reduce((acc, curr) => acc + curr.count, 0)}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <h3 className="font-display font-black text-2xl text-on-surface mb-6 flex items-center gap-2">
                <Icon name="analytics" className="text-violet-500" />
                Analisis Perkembangan Mentee
            </h3>

            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 pb-12">
                {menteeStats.map((stat, idx) => (
                    <div key={idx} className="glass-card p-6 rounded-2xl border-t-4 border-t-primary-container/30">
                        <div className="flex items-center gap-3 mb-6">
                            <div className="w-10 h-10 rounded-full bg-surface-container overflow-hidden shadow-sm">
                                {stat.avatar ? (
                                    <img src={stat.avatar} alt={stat.name} className="w-full h-full object-cover" />
                                ) : (
                                    <div className="w-full h-full flex items-center justify-center bg-slate-200 text-slate-500 font-bold text-sm">
                                        {stat.name.charAt(0)}
                                    </div>
                                )}
                            </div>
                            <div className="min-w-0">
                                <p className="font-bold text-on-surface truncate">{stat.name}</p>
                                <p className="text-[10px] text-on-surface-variant uppercase tracking-widest font-black">Student Profile</p>
                            </div>
                        </div>

                        <div className="h-[250px] min-h-[250px] w-full relative">
                            <ResponsiveContainer width="100%" height="100%" minWidth={0} minHeight={0}>
                                <RadarChart cx="50%" cy="50%" outerRadius="80%" data={stat.values}>
                                    <PolarGrid stroke="#e2e8f0" />
                                    <PolarAngleAxis dataKey="subject" tick={{ fill: '#94a3b8', fontSize: 10 }} />
                                    <PolarRadiusAxis angle={30} domain={[0, 100]} tick={false} axisLine={false} />
                                    <Radar
                                        name={stat.name}
                                        dataKey="A"
                                        stroke="#3b82f6"
                                        fill="#3b82f6"
                                        fillOpacity={0.6}
                                    />
                                    <Tooltip 
                                        contentStyle={{ 
                                            backgroundColor: 'rgba(255,255,255,0.9)', 
                                            borderRadius: '12px', 
                                            border: 'none', 
                                            boxShadow: '0 10px 15px -3px rgba(0,0,0,0.1)' 
                                        }} 
                                    />
                                </RadarChart>
                            </ResponsiveContainer>
                        </div>
                        
                        <div className="mt-4 pt-4 border-t border-white/40 flex justify-between items-center">
                            <p className="text-[11px] font-bold text-on-surface-variant">Overall Progress</p>
                            <span className="text-sm font-black text-primary-container">
                                {Math.round(stat.values.reduce((acc, curr) => acc + curr.A, 0) / stat.values.length)}%
                            </span>
                        </div>
                    </div>
                ))}

                {menteeStats.length === 0 && (
                    <div className="col-span-full py-20 glass-card rounded-2xl flex flex-col items-center justify-center text-center opacity-60">
                        <Icon name="person_off" className="text-6xl mb-4" />
                        <p className="font-display font-black">Mentor belum memiliki mentee</p>
                        <p className="text-sm text-on-surface-variant">Silakan hubungkan mahasiswa dengan mentor ini terlebih dahulu.</p>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
