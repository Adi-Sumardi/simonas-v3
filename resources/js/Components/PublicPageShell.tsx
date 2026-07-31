import { Head, Link } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';
import { ReactNode } from 'react';

interface Props {
    title: string;
    subtitle: string;
    icon: string;
    children: ReactNode;
}

export function PublicPageShell({ title, subtitle, icon, children }: Props) {
    return (
        <div className="min-h-screen bg-serene-gradient selection:bg-primary/20 selection:text-primary">
            <Head title={title} />

            {/* Navigation */}
            <nav className="fixed top-0 left-0 w-full z-50 bg-white/60 backdrop-blur-xl border-b border-white/40">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-20">
                        <Link href="/" className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-xl overflow-hidden shadow-glow">
                                <img src="/images/simonas_logo.png" alt="SIMONAS" className="w-full h-full object-cover" />
                            </div>
                            <span className="font-display text-xl font-black text-primary tracking-tight">SIMONAS</span>
                        </Link>
                        <Link href="/" className="flex items-center gap-1.5 text-sm font-bold text-secondary hover:text-primary transition-colors">
                            <Icon name="arrow_back" className="text-lg" />
                            Beranda
                        </Link>
                    </div>
                </div>
            </nav>

            <main className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-36 pb-24">
                <div className="text-center mb-14">
                    <div className="w-16 h-16 rounded-2xl bg-primary-container flex items-center justify-center mx-auto mb-6 shadow-glow">
                        <Icon name={icon} className="text-3xl text-white" filled />
                    </div>
                    <h1 className="font-display text-display-md text-on-surface font-black mb-3">{title}</h1>
                    <p className="text-body-md text-on-surface-variant max-w-xl mx-auto">{subtitle}</p>
                </div>

                <div className="space-y-6">{children}</div>
            </main>

            <footer className="py-10 text-center text-[11px] font-bold uppercase tracking-[0.2em] text-on-surface-variant">
                © 2026 SIMONAS Digital Asrama YAPI · All rights reserved.
            </footer>
        </div>
    );
}

export function PageSection({ title, icon, children }: { title: string; icon: string; children: ReactNode }) {
    return (
        <section className="glass-panel rounded-3xl p-6 sm:p-8">
            <div className="flex items-center gap-3 mb-5">
                <div className="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center flex-shrink-0">
                    <Icon name={icon} className="text-xl text-primary-container" filled />
                </div>
                <h2 className="font-display text-title-lg text-on-surface font-bold">{title}</h2>
            </div>
            <div className="text-body-md text-on-surface-variant leading-relaxed space-y-3">{children}</div>
        </section>
    );
}
