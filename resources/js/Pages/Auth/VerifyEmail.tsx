import { Head, router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import { AuthLayout } from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Icon } from '@/Components/ui/Icon';

interface Props {
    email: string;
}

export default function VerifyEmail({ email }: Props) {
    const { resent } = usePage<{ resent?: boolean }>().props;
    const [sending, setSending] = useState(false);

    function resend() {
        setSending(true);
        router.post('/email/resend', {}, {
            preserveScroll: true,
            onFinish: () => setSending(false),
        });
    }

    function logout() {
        router.post('/logout');
    }

    return (
        <AuthLayout>
            <Head title="Verifikasi Email" />
            <div className="glass-panel w-full max-w-md p-8 text-center">
                <div className="w-16 h-16 rounded-2xl bg-primary-container flex items-center justify-center mx-auto mb-6 shadow-glow">
                    <Icon name="mark_email_unread" className="text-3xl text-white" filled />
                </div>
                <h1 className="font-display text-headline-md text-primary-container mb-3">Verifikasi Email Kamu</h1>
                <p className="text-body-sm text-on-surface-variant leading-relaxed mb-1">
                    Kami sudah kirim link verifikasi ke:
                </p>
                <p className="font-bold text-on-surface mb-6">{email}</p>
                <p className="text-body-sm text-on-surface-variant leading-relaxed mb-8">
                    Klik link di email tersebut untuk mengaktifkan akunmu. Cek juga folder Spam/Promosi
                    kalau belum kelihatan di Inbox.
                </p>

                {resent && (
                    <div className="mb-6 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center justify-center gap-2">
                        <Icon name="check_circle" className="text-lg" filled />
                        Email verifikasi baru sudah dikirim ulang.
                    </div>
                )}

                <div className="space-y-3">
                    <Button onClick={resend} fullWidth size="lg" disabled={sending}>
                        {sending ? 'Mengirim...' : 'Kirim Ulang Email'}
                        {!sending && <Icon name="refresh" />}
                    </Button>
                    <button
                        onClick={logout}
                        className="w-full text-center text-body-sm text-on-surface-variant hover:text-on-surface font-semibold py-2"
                    >
                        Keluar
                    </button>
                </div>
            </div>
        </AuthLayout>
    );
}
