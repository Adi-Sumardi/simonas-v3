import { Head, router, useForm } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';

interface Props extends PageProps {
    room: { id: number; title: string; status: string };
}

export default function LiveMeetJoin({ room }: Props) {
    const [waiting, setWaiting] = useState(false);
    const form = useForm({ passcode: '' });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        form.post(`/super/live-meet/${room.id}/join`, {
            preserveScroll: true,
            onSuccess: () => setWaiting(true),
        });
    }

    useEffect(() => {
        if (!waiting) return;
        const interval = setInterval(() => {
            fetch(`/super/live-meet/${room.id}/waiting-status`)
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'admitted') {
                        router.visit(`/super/live-meet/${room.id}`);
                    } else if (data.status === 'denied') {
                        clearInterval(interval);
                    }
                });
        }, 4000);
        return () => clearInterval(interval);
    }, [waiting, room.id]);

    return (
        <AppLayout>
            <Head title={`Join: ${room.title}`} />
            <div className="max-w-md mx-auto mt-16">
                <div className="glass-card rounded-3xl p-8 text-center">
                    <Icon name="video_camera_front" className="text-4xl text-primary-container mb-3" />
                    <h1 className="font-display text-headline-md text-on-surface mb-1">{room.title}</h1>

                    {waiting ? (
                        <div className="mt-6 space-y-3">
                            <div className="w-10 h-10 mx-auto rounded-full border-4 border-primary-container/30 border-t-primary-container animate-spin" />
                            <p className="text-sm text-on-surface-variant">Menunggu izin masuk dari host...</p>
                        </div>
                    ) : (
                        <form onSubmit={submit} className="mt-6 space-y-4">
                            <input
                                type="text"
                                inputMode="numeric"
                                maxLength={6}
                                value={form.data.passcode}
                                onChange={e => form.setData('passcode', e.target.value)}
                                placeholder="Masukkan passcode 6-digit"
                                className="glass-input w-full text-center text-lg tracking-[0.3em] py-3"
                                autoFocus
                            />
                            {form.errors.passcode && <p className="text-xs text-rose-500">{form.errors.passcode}</p>}
                            <button type="submit" disabled={form.processing} className="btn-primary w-full py-3 rounded-xl font-bold text-sm">
                                {form.processing ? 'Memeriksa...' : 'Gabung Meeting'}
                            </button>
                        </form>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
