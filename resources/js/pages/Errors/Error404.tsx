import { Head, Link } from '@inertiajs/react';
import { Button } from '@/Components/ui/Button';
import { Icon } from '@/Components/ui/Icon';

interface Props {
    dashboardUrl?: string;
}

export default function Error404({ dashboardUrl = '/' }: Props) {
    return (
        <>
            <Head title="404 — Not Found" />
            <header className="px-6 py-4 flex items-center gap-2">
                <div className="w-8 h-8 rounded-lg bg-primary-container flex items-center justify-center">
                    <Icon name="apartment" className="text-base text-on-primary" filled />
                </div>
                <span className="font-display text-title-sm text-primary-container">SIMONAS</span>
            </header>

            <main className="px-6 py-12 max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
                {/* Illustration */}
                <div className="relative flex justify-center">
                    <div className="glass-panel p-6 max-w-sm">
                        <div className="aspect-square rounded-xl bg-gradient-to-br from-surface-container-high to-surface-container-low flex items-center justify-center relative overflow-hidden">
                            <Icon name="local_library" className="text-8xl text-primary-container/40" filled />
                            <div className="absolute bottom-4 left-1/2 -translate-x-1/2 text-display-md font-display italic text-primary-container/70">
                                Lost
                            </div>
                        </div>
                        <div className="mt-4 status-pill-info inline-flex items-center gap-1">
                            <Icon name="cloud_done" className="text-base" />
                            Archive Error · Internet Found
                        </div>
                    </div>
                </div>

                {/* Text */}
                <div>
                    <div className="text-label-caps text-primary-container mb-3">System Notice</div>
                    <h1 className="font-display text-display-lg text-on-surface mb-4">
                        Oops! This page is not in our archives.
                    </h1>
                    <p className="text-body-md text-on-surface-variant mb-8">
                        Even in a sanctuary of focus, sometimes we drift off course. The records you're
                        looking for seem to have been misplaced.
                    </p>

                    <div className="flex flex-wrap gap-3">
                        <Link href={dashboardUrl}>
                            <Button>
                                <Icon name="dashboard" />
                                Return to Dashboard
                            </Button>
                        </Link>
                        <a href="mailto:support@simonas.id">
                            <Button variant="secondary">
                                <Icon name="help" />
                                Help Center
                            </Button>
                        </a>
                    </div>

                    <div className="mt-8 pt-6 border-t border-outline-variant/30 flex items-center gap-6 text-label-caps text-on-surface-variant">
                        <span className="flex items-center gap-1.5">
                            <span className="w-1.5 h-1.5 rounded-full bg-success" />
                            Network Active
                        </span>
                        <span className="flex items-center gap-1.5">
                            <span className="w-1.5 h-1.5 rounded-full bg-error" />
                            Error Code: 404
                        </span>
                    </div>
                </div>
            </main>

            <footer className="px-6 py-6 text-center text-label-caps text-on-surface-variant">
                © 2026 SIMONAS Academic Sanctuary · All records are secured.
            </footer>
        </>
    );
}
