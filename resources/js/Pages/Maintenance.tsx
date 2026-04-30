import { Head } from '@inertiajs/react';
import { Button } from '@/Components/ui/Button';
import { Icon } from '@/Components/ui/Icon';

interface Props {
    progress?: number;
    statusCode?: string;
}

export default function Maintenance({ progress = 65, statusCode = 'SE_DORM_SNF_404' }: Props) {
    return (
        <>
            <Head title="Maintenance" />
            <div className="min-h-screen flex items-center justify-center px-4">
                <div className="glass-panel max-w-lg w-full p-10 text-center">
                    <div className="flex items-center justify-center gap-2 mb-8">
                        <div className="w-7 h-7 rounded-lg bg-primary-container flex items-center justify-center">
                            <Icon name="apartment" className="text-base text-on-primary" filled />
                        </div>
                        <span className="font-display text-title-sm text-primary-container">SIMONAS</span>
                    </div>

                    {/* Decorative icons */}
                    <div className="relative mb-8 h-32 flex items-center justify-center">
                        <div className="glass-card w-24 h-24 rounded-full flex items-center justify-center shadow-glass-lg">
                            <Icon name="schedule" className="text-5xl text-primary-container" filled />
                        </div>
                        <div
                            className="absolute glass-card p-2"
                            style={{ top: 0, right: '20%', transform: 'rotate(12deg)' }}
                        >
                            <Icon name="notifications_active" className="text-2xl text-primary-fixed-dim" />
                        </div>
                        <div
                            className="absolute glass-card p-2"
                            style={{ top: '30%', left: '15%', transform: 'rotate(-15deg)' }}
                        >
                            <Icon name="settings" className="text-2xl text-on-surface-variant" />
                        </div>
                    </div>

                    <h1 className="font-display text-display-md text-on-surface mb-3">
                        Sanctuary Under Maintenance.
                    </h1>
                    <p className="text-body-md text-on-surface-variant mb-6">
                        We're polishing your academic experience. Please check back shortly.
                    </p>

                    {/* Progress */}
                    <div className="mb-8">
                        <div className="flex justify-between text-label-caps text-on-surface-variant mb-2">
                            <span>Optimizing Performance</span>
                            <span>{progress}% Complete</span>
                        </div>
                        <div className="h-2 bg-surface-container rounded-full overflow-hidden">
                            <div
                                className="h-full bg-primary-container rounded-full transition-all"
                                style={{ width: `${progress}%` }}
                            />
                        </div>
                    </div>

                    <div className="flex gap-3 justify-center">
                        <Button onClick={() => window.location.reload()}>
                            <Icon name="refresh" />
                            Refresh Page
                        </Button>
                        <Button variant="secondary" onClick={() => (window.location.href = '/')}>
                            <Icon name="home" />
                            Go Back Home
                        </Button>
                    </div>

                    <div className="mt-8 pt-6 border-t border-outline-variant/30 flex items-center justify-between text-label-caps text-on-surface-variant">
                        <span className="flex items-center gap-1">
                            <span className="w-1.5 h-1.5 rounded-full bg-warning" />
                            Planned System Update
                        </span>
                        <span>Status: {statusCode}</span>
                    </div>
                </div>
            </div>
            <p className="absolute bottom-4 inset-x-0 text-center text-body-sm text-on-surface-variant">
                Need urgent assistance? Reach out to the{' '}
                <a href="mailto:support@simonas.id" className="text-primary-container font-semibold">
                    Academic Support Desk
                </a>
            </p>
        </>
    );
}
