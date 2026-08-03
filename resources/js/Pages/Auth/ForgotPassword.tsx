import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { FormEvent } from 'react';
import { AuthLayout } from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Label } from '@/Components/ui/Label';
import { Icon } from '@/Components/ui/Icon';

export default function ForgotPassword() {
    const { status } = usePage<{ status?: string }>().props;
    const { data, setData, post, processing, errors } = useForm({ email: '' });

    function submit(e: FormEvent) {
        e.preventDefault();
        post('/password/email');
    }

    return (
        <AuthLayout>
            <Head title="Lupa Password" />
            <div className="glass-panel w-full max-w-md p-8">
                <div className="flex flex-col items-center text-center mb-8">
                    <div className="w-16 h-16 rounded-2xl overflow-hidden mb-4 shadow-glow">
                        <img src="/images/simonas_logo.png" alt="SIMONAS" className="w-full h-full object-cover" />
                    </div>
                    <h1 className="font-display text-headline-md text-primary-container">Lupa Password</h1>
                    <p className="text-body-sm text-on-surface-variant mt-1">
                        Masukkan email akunmu, kami kirim link buat reset password.
                    </p>
                </div>

                {status && (
                    <div className="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-start gap-2">
                        <Icon name="check_circle" className="text-lg flex-shrink-0" filled />
                        <span>{status}</span>
                    </div>
                )}

                <form onSubmit={submit} className="space-y-5">
                    <div>
                        <Label htmlFor="email">Email Address</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            placeholder="mentor@simonas.id"
                            autoComplete="email"
                            autoFocus
                            leftIcon={<Icon name="mail" className="text-xl" />}
                            error={errors.email}
                            required
                        />
                    </div>

                    <Button type="submit" fullWidth size="lg" disabled={processing}>
                        {processing ? 'Mengirim...' : 'Kirim Link Reset'}
                        {!processing && <Icon name="send" />}
                    </Button>
                </form>

                <p className="text-center text-body-sm text-on-surface-variant mt-6">
                    Ingat password-nya?{' '}
                    <Link href="/login" className="text-primary-container font-semibold hover:underline">
                        Kembali ke Login
                    </Link>
                </p>
            </div>
        </AuthLayout>
    );
}
