import { Head, Link } from '@inertiajs/react';

interface Props {
    appName: string;
    version: string;
}

export default function Welcome({ appName, version }: Props) {
    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen flex items-center justify-center p-md-">
                <div className="glass-panel max-w-2xl w-full p-lg-">
                    <div className="flex items-center gap-4 mb-6">
                        <div className="w-14 h-14 rounded-xl bg-primary-container flex items-center justify-center text-on-primary text-2xl">
                            <span className="material-symbols-outlined">apartment</span>
                        </div>
                        <div>
                            <h1 className="text-headline-md text-primary">{appName}</h1>
                            <p className="text-body-sm text-on-surface-variant">Academic Sanctuary</p>
                        </div>
                    </div>
                    <h2 className="text-display-md text-on-surface mb-4">
                        Inertia + React + Vite siap.
                    </h2>
                    <p className="text-body-md text-on-surface-variant mb-8">
                        Design system <strong>Serene Academic Glassmorphism</strong> sudah aktif.
                        Laravel <code className="bg-surface-container px-2 py-0.5 rounded">{version}</code> running.
                    </p>
                    <div className="flex gap-3 mb-8">
                        <Link href="/login" className="btn-primary">
                            Coba Login →
                        </Link>
                        <a href="https://inertiajs.com" target="_blank" rel="noreferrer" className="btn-secondary">
                            Inertia Docs
                        </a>
                    </div>
                    <div className="grid grid-cols-3 gap-4">
                        {[
                            { icon: 'school', label: 'Akademik', tone: 'bg-primary-fixed text-primary' },
                            { icon: 'volunteer_activism', label: 'Karakter', tone: 'bg-success/15 text-success' },
                            { icon: 'lightbulb', label: 'Kreatif', tone: 'bg-warning/15 text-warning' },
                        ].map((f) => (
                            <div key={f.label} className="glass-card p-4 flex flex-col items-center gap-2">
                                <div className={`w-10 h-10 rounded-lg flex items-center justify-center ${f.tone}`}>
                                    <span className="material-symbols-outlined">{f.icon}</span>
                                </div>
                                <span className="text-body-sm font-medium">{f.label}</span>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </>
    );
}
