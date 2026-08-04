import { Link } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';

const ALUMNI_NAV = [
    { href: '/dashboard',    icon: 'home',         label: 'Dashboard' },
    { href: '/alumni/profil',icon: 'person',       label: 'Profil Saya' },
    { href: '/alumni/hub',   icon: 'people',       label: 'Postingan' },
    { href: '/alumni/bisnis',icon: 'storefront',   label: 'Bisnis' },
    { href: '/alumni/jobs',  icon: 'work',         label: 'Lowongan & Magang' },
    { href: '/alumni/leaderboard', icon: 'leaderboard', label: 'Leaderboard' },
];

interface AlumniSidebarProps {
    active: string; // one of the href values above
}

export function AlumniSidebar({ active }: AlumniSidebarProps) {
    return (
        <div className="space-y-5">
            {/* Navigation */}
            <div className="glass-card rounded-2xl p-4">
                <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-3 px-2">Menu Alumni</p>
                <div className="space-y-1">
                    {ALUMNI_NAV.map(item => {
                        const isActive = active === item.href;
                        return (
                            <Link key={item.href} href={item.href}
                                className={`flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all ${
                                    isActive
                                        ? 'bg-emerald-500 text-white shadow-sm shadow-emerald-200'
                                        : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface'
                                }`}>
                                <Icon name={item.icon} className="text-base flex-shrink-0" filled={isActive} />
                                {item.label}
                            </Link>
                        );
                    })}
                </div>
            </div>

            {/* Quick tips */}
            <div className="glass-card rounded-2xl p-4 bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200/50">
                <h3 className="font-bold text-emerald-800 text-xs mb-2 flex items-center gap-2">
                    <Icon name="lightbulb" className="text-emerald-600 text-sm" filled />
                    Tips Alumni
                </h3>
                <ul className="space-y-1.5 text-[11px] text-emerald-700 leading-relaxed">
                    <li>📣 Bagikan cerita sukses ke Hub</li>
                    <li>💼 Update profil karir secara rutin</li>
                    <li>🤝 Bantu sesama dengan info lowongan</li>
                    <li>🏪 Promosikan bisnis kamu gratis</li>
                </ul>
            </div>
        </div>
    );
}
