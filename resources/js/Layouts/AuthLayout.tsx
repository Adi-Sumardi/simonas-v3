import { ReactNode } from 'react';
import { Icon } from '@/Components/ui/Icon';
import { ToastProvider } from '@/Components/ui/Toast';

interface Props {
    children: ReactNode;
}

const decorations = [
    { icon: 'menu_book', top: '5%', left: '4%', size: 'text-6xl', rotate: '-12deg' },
    { icon: 'school', top: '20%', right: '8%', size: 'text-5xl', rotate: '14deg' },
    { icon: 'menu_book', bottom: '20%', left: '6%', size: 'text-5xl', rotate: '20deg' },
    { icon: 'auto_stories', bottom: '8%', right: '6%', size: 'text-6xl', rotate: '-8deg' },
];

export function AuthLayout({ children }: Props) {
    return (
        <ToastProvider>
            <div className="min-h-screen relative overflow-hidden">
                {/* Floating icon decorations */}
                <div className="absolute inset-0 pointer-events-none" aria-hidden>
                    {decorations.map((d, i) => (
                        <div
                            key={i}
                            className="absolute glass-card p-3"
                            style={{
                                top: d.top,
                                bottom: d.bottom,
                                left: d.left,
                                right: d.right,
                                transform: `rotate(${d.rotate})`,
                                opacity: 0.45,
                            }}
                        >
                            <Icon name={d.icon} className={`${d.size} text-primary-fixed-dim`} />
                        </div>
                    ))}
                </div>

                {/* Card content */}
                <div className="relative z-10 min-h-screen flex flex-col items-center justify-center px-4 py-12">
                    {children}
                    <div className="mt-8 flex items-center gap-6 text-body-sm text-on-surface-variant">
                        <span className="flex items-center gap-1.5">
                            <Icon name="verified_user" className="text-base" /> Secure Login
                        </span>
                        <span className="flex items-center gap-1.5">
                            <Icon name="help" className="text-base" /> Support Hub
                        </span>
                        <span className="flex items-center gap-1.5">
                            <Icon name="language" className="text-base" /> EN
                        </span>
                    </div>
                </div>
            </div>
        </ToastProvider>
    );
}
