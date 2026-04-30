import { Head, Link } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';

interface Props {
    role: string;
    user: { name: string; email: string };
}

const ROLE_CONFIG: Record<string, { label: string; color: string; icon: string; next_route?: string }> = {
    super: {
        label: 'Super Admin',
        color: 'from-violet-600 to-purple-600',
        icon: 'admin_panel_settings',
        next_route: '/super/dashboard',
    },
    admin: {
        label: 'Admin Asrama',
        color: 'from-blue-600 to-cyan-600',
        icon: 'manage_accounts',
        next_route: '/admin/dashboard',
    },
    alumni: {
        label: 'Alumni',
        color: 'from-emerald-600 to-teal-600',
        icon: 'school',
        next_route: '/alumni/dashboard',
    },
};

export default function ComingSoon({ role, user }: Props) {
    const cfg = ROLE_CONFIG[role] ?? { label: role, color: 'from-primary to-primary-container', icon: 'person' };

    return (
        <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 flex items-center justify-center p-6">
            <Head title={`Dashboard ${cfg.label}`} />

            <div className="max-w-lg w-full bg-white/80 backdrop-blur-xl border border-white/40 shadow-2xl rounded-3xl p-10 text-center space-y-6">
                {/* Icon */}
                <div className={`w-20 h-20 rounded-2xl bg-gradient-to-br ${cfg.color} flex items-center justify-center mx-auto shadow-xl`}>
                    <Icon name={cfg.icon} className="text-4xl text-white" filled />
                </div>

                {/* Greeting */}
                <div>
                    <p className="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Selamat datang</p>
                    <h1 className="text-3xl font-bold text-slate-800">{user.name}</h1>
                    <p className="text-slate-500 text-sm mt-1">{user.email}</p>
                </div>

                {/* Status badge */}
                <div className={`inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r ${cfg.color} text-white text-sm font-bold shadow-lg`}>
                    <Icon name={cfg.icon} className="text-base" />
                    {cfg.label}
                </div>

                {/* Coming soon notice */}
                <div className="bg-amber-50 border border-amber-200 rounded-2xl p-5 text-left">
                    <div className="flex gap-3">
                        <Icon name="construction" className="text-amber-500 text-2xl flex-shrink-0 mt-0.5" />
                        <div>
                            <h3 className="font-bold text-amber-800 text-sm">Dashboard Dalam Pengembangan</h3>
                            <p className="text-amber-700 text-xs mt-1 leading-relaxed">
                                Dashboard untuk role <strong>{cfg.label}</strong> sedang dalam proses migrasi ke tampilan baru.
                                Akan segera tersedia pada Fase 5 pengembangan SIMONAS.
                            </p>
                        </div>
                    </div>
                </div>

                {/* Actions */}
                <div className="flex gap-3">
                    <form method="POST" action="/logout" className="flex-1">
                        <input type="hidden" name="_token" value={document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? ''} />
                        <button
                            type="submit"
                            className="w-full py-3 rounded-xl border-2 border-slate-300 text-slate-600 font-semibold hover:bg-slate-100 transition-colors text-sm"
                        >
                            Logout
                        </button>
                    </form>
                    <Link
                        href="/"
                        className="flex-1 py-3 rounded-xl bg-primary-container text-white font-semibold text-center hover:opacity-90 transition-opacity text-sm"
                    >
                        Beranda
                    </Link>
                </div>
            </div>
        </div>
    );
}
