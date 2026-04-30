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
    googleEnabled?: boolean;
}

export default function Login({ canRegister = true, canResetPassword = true, googleEnabled = true }: Props) {
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

                {/* Google OAuth */}
                {googleEnabled && (
                    <>
                        <div className="flex items-center gap-3 my-5">
                            <div className="flex-1 h-px bg-outline-variant/50" />
                            <span className="text-label-caps text-on-surface-variant">Or continue with</span>
                            <div className="flex-1 h-px bg-outline-variant/50" />
                        </div>
                        <a
                            href="/auth/google/redirect"
                            className="btn-secondary w-full inline-flex items-center justify-center gap-3 py-2.5"
                        >
                            <svg className="w-5 h-5" viewBox="0 0 24 24" aria-hidden>
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0 0 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.1A6.6 6.6 0 0 1 5.5 12c0-.73.13-1.44.34-2.1V7.07H2.18a11 11 0 0 0 0 9.86l3.66-2.83z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.83C6.71 7.31 9.14 5.38 12 5.38z"/>
                            </svg>
                            Sign in with Google
                        </a>
                    </>
                )}

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
