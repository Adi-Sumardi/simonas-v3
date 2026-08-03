import { Head, useForm } from '@inertiajs/react';
import { useState, FormEvent } from 'react';
import { AuthLayout } from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Label } from '@/Components/ui/Label';
import { Icon } from '@/Components/ui/Icon';

interface Props {
    token: string;
    email: string;
}

export default function ResetPassword({ token, email }: Props) {
    const [showPassword, setShowPassword] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    function submit(e: FormEvent) {
        e.preventDefault();
        post('/password/reset');
    }

    return (
        <AuthLayout>
            <Head title="Reset Password" />
            <div className="glass-panel w-full max-w-md p-8">
                <div className="flex flex-col items-center text-center mb-8">
                    <div className="w-16 h-16 rounded-2xl overflow-hidden mb-4 shadow-glow">
                        <img src="/images/simonas_logo.png" alt="SIMONAS" className="w-full h-full object-cover" />
                    </div>
                    <h1 className="font-display text-headline-md text-primary-container">Reset Password</h1>
                    <p className="text-body-sm text-on-surface-variant mt-1">Buat password baru untuk akunmu.</p>
                </div>

                <form onSubmit={submit} className="space-y-5">
                    <div>
                        <Label htmlFor="email">Email Address</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            leftIcon={<Icon name="mail" className="text-xl" />}
                            error={errors.email}
                            required
                        />
                    </div>

                    <div>
                        <Label htmlFor="password">Password Baru</Label>
                        <Input
                            id="password"
                            type={showPassword ? 'text' : 'password'}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            placeholder="Minimal 8 karakter"
                            autoComplete="new-password"
                            autoFocus
                            leftIcon={<Icon name="lock" className="text-xl" />}
                            rightIcon={
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="text-on-surface-variant hover:text-on-surface"
                                    aria-label={showPassword ? 'Hide password' : 'Show password'}
                                >
                                    <Icon name={showPassword ? 'visibility_off' : 'visibility'} className="text-xl" />
                                </button>
                            }
                            error={errors.password}
                            required
                        />
                    </div>

                    <div>
                        <Label htmlFor="password_confirmation">Konfirmasi Password</Label>
                        <Input
                            id="password_confirmation"
                            type={showPassword ? 'text' : 'password'}
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            autoComplete="new-password"
                            leftIcon={<Icon name="lock" className="text-xl" />}
                            required
                        />
                    </div>

                    <Button type="submit" fullWidth size="lg" disabled={processing}>
                        {processing ? 'Menyimpan...' : 'Reset Password'}
                        {!processing && <Icon name="check" />}
                    </Button>
                </form>
            </div>
        </AuthLayout>
    );
}
