import { Head, Link, useForm } from '@inertiajs/react';
import { useState, FormEvent } from 'react';
import { AuthLayout } from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Label } from '@/Components/ui/Label';
import { Icon } from '@/Components/ui/Icon';

interface Props {
    canRegister?: boolean;
    canResetPassword?: boolean;
}

export default function Login({ canRegister = true, canResetPassword = true }: Props) {
    const [showPassword, setShowPassword] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    function submit(e: FormEvent) {
        e.preventDefault();
        post('/login');
    }

    return (
        <AuthLayout>
            <Head title="Sign In" />
            <div className="glass-panel w-full max-w-md p-8">
                {/* Logo + Brand */}
                <div className="flex flex-col items-center text-center mb-8">
                    <div className="w-16 h-16 rounded-2xl bg-primary-container flex items-center justify-center mb-4 shadow-glow">
                        <Icon name="apartment" className="text-3xl text-on-primary" filled />
                    </div>
                    <h1 className="font-display text-headline-md text-primary-container">SIMONAS</h1>
                    <p className="text-body-sm text-on-surface-variant mt-1">Academic Sanctuary Management</p>
                </div>

                <form onSubmit={submit} className="space-y-5">
                    {/* Email */}
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

                    {/* Password */}
                    <div>
                        <div className="flex items-center justify-between mb-2">
                            <Label htmlFor="password" className="mb-0">
                                Password
                            </Label>
                            {canResetPassword && (
                                <Link
                                    href="/password/reset"
                                    className="text-body-sm text-primary-container font-semibold hover:underline"
                                >
                                    Forgot Password?
                                </Link>
                            )}
                        </div>
                        <Input
                            id="password"
                            type={showPassword ? 'text' : 'password'}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            placeholder="••••••••••••"
                            autoComplete="current-password"
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

                    {/* Remember */}
                    <label className="flex items-center gap-2 cursor-pointer text-body-sm text-on-surface-variant">
                        <input
                            type="checkbox"
                            checked={data.remember}
                            onChange={(e) => setData('remember', e.target.checked)}
                            className="w-4 h-4 rounded border-outline-variant text-primary-container focus:ring-primary-container"
                        />
                        Remember this device
                    </label>

                    <Button type="submit" fullWidth size="lg" disabled={processing}>
                        {processing ? 'Signing in...' : 'Sign In'}
                        {!processing && <Icon name="arrow_forward" />}
                    </Button>
                </form>

                {/* Divider + Register link */}
                {canRegister && (
                    <div className="mt-6">
                        <div className="flex items-center gap-3 mb-4">
                            <div className="flex-1 h-px bg-outline-variant/50" />
                            <span className="text-label-caps text-on-surface-variant">New to the Sanctuary?</span>
                            <div className="flex-1 h-px bg-outline-variant/50" />
                        </div>
                        <p className="text-center text-body-sm text-on-surface-variant">
                            Don't have an account?{' '}
                            <Link href="/register" className="text-primary-container font-semibold hover:underline">
                                Create Account
                            </Link>
                        </p>
                    </div>
                )}
            </div>
        </AuthLayout>
    );
}
