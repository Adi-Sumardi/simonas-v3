import { Icon } from './Icon';

type BadgeColor = 'blue' | 'emerald' | 'amber' | 'purple' | 'rose' | 'teal';

interface StatCardProps {
    icon: string;
    label: string;
    value: string | number;
    badge?: string;
    badgeColor?: BadgeColor;
    className?: string;
}

const COLOR: Record<BadgeColor, { icon: string; badge: string; glow: string }> = {
    blue:    { icon: 'bg-blue-100    text-blue-600',    badge: 'bg-blue-50    text-blue-600    border-blue-100',    glow: 'shadow-blue-100' },
    emerald: { icon: 'bg-emerald-100 text-emerald-600', badge: 'bg-emerald-50 text-emerald-600 border-emerald-100', glow: 'shadow-emerald-100' },
    amber:   { icon: 'bg-amber-100   text-amber-600',   badge: 'bg-amber-50   text-amber-600   border-amber-100',   glow: 'shadow-amber-100' },
    purple:  { icon: 'bg-purple-100  text-purple-600',  badge: 'bg-purple-50  text-purple-600  border-purple-100',  glow: 'shadow-purple-100' },
    rose:    { icon: 'bg-rose-100    text-rose-600',    badge: 'bg-rose-50    text-rose-600    border-rose-100',    glow: 'shadow-rose-100' },
    teal:    { icon: 'bg-teal-100    text-teal-600',    badge: 'bg-teal-50    text-teal-600    border-teal-100',    glow: 'shadow-teal-100' },
};

export function StatCard({ icon, label, value, badge, badgeColor = 'blue', className = '' }: StatCardProps) {
    const c = COLOR[badgeColor];
    return (
        <div className={`glass-card rounded-2xl p-4 sm:p-5 flex flex-col gap-3 sm:gap-4 h-full ${className}`}>
            {/* Top row: icon + badge */}
            <div className="flex items-center justify-between">
                <div className={`w-9 h-9 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center flex-shrink-0 ${c.icon}`}>
                    <Icon name={icon} className="text-lg sm:text-xl" filled />
                </div>
                {badge && (
                    <span className={`text-[9px] sm:text-[10px] font-bold px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full border ${c.badge}`}>
                        {badge}
                    </span>
                )}
            </div>

            {/* Bottom: label + value */}
            <div>
                <p className="text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1">
                    {label}
                </p>
                <p className="font-display text-lg sm:text-2xl font-bold text-on-surface leading-tight">
                    {value}
                </p>
            </div>
        </div>
    );
}
