import { Head, Link, useForm } from '@inertiajs/react';
import { useState, FormEvent } from 'react';
import { AuthLayout } from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Label } from '@/Components/ui/Label';
import { Icon } from '@/Components/ui/Icon';

interface Asrama {
    value: string;
    label: string;
}

interface Props {
    asramas: Asrama[];
}

export default function Register({ asramas }: Props) {
    const [showPassword, setShowPassword] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        no_induk: '',
        asrama: '',
        tgl_masuk: '',
        role: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    function submit(e: FormEvent) {
        e.preventDefault();
        post('/register');
    }

    return (
        <AuthLayout>
            <Head title="Create Account" />
            <div className="glass-panel w-full max-w-md p-8">
                <div className="flex items-center justify-center gap-2 mb-1">
                    <div className="w-8 h-8 rounded-lg bg-primary-container flex items-center justify-center">
                        <Icon name="apartment" className="text-base text-on-primary" filled />
                    </div>
                    <h1 className="font-display text-title-sm text-primary-container">SIMONAS</h1>
                </div>

                <div className="text-center mb-6">
                    <h2 className="font-display text-headline-md text-on-surface mt-4">Begin Your Journey</h2>
                    <p className="text-body-sm text-on-surface-variant mt-1">
                        Bergabung dengan digital asrama YAPI hari ini.
                    </p>
                </div>

                <form onSubmit={submit} className="space-y-4">
                    <div>
                        <Label htmlFor="name">Full Name</Label>
                        <Input
                            id="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            placeholder="Enter your full name"
                            leftIcon={<Icon name="person" className="text-xl" />}
                            error={errors.name}
                            required
                        />
                    </div>

                    <div className="grid grid-cols-2 gap-3">
                        <div>
                            <Label htmlFor="no_induk">Student ID (NIM)</Label>
                            <Input
                                id="no_induk"
                                value={data.no_induk}
                                onChange={(e) => setData('no_induk', e.target.value)}
                                placeholder="ID Number"
                                leftIcon={<Icon name="badge" className="text-xl" />}
                                error={errors.no_induk}
                                required
                            />
                        </div>
                        <div>
                            <Label htmlFor="asrama">Dormitory</Label>
                            <div className="glass-input flex items-center gap-2">
                                <Icon name="apartment" className="text-xl text-on-surface-variant" />
                                <select
                                    id="asrama"
                                    value={data.asrama}
                                    onChange={(e) => setData('asrama', e.target.value)}
                                    className="flex-1 bg-transparent border-0 outline-none text-on-surface py-1"
                                    required
                                >
                                    <option value="">Select Asrama</option>
                                    {asramas.map((a) => (
                                        <option key={a.value} value={a.value}>
                                            {a.label}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            {errors.asrama && <p className="mt-1 text-body-sm text-error">{errors.asrama}</p>}
                        </div>
                    </div>

                    <div className="grid grid-cols-2 gap-3">
                        <div>
                            <Label htmlFor="tgl_masuk">Tanggal Masuk Asrama</Label>
                            <div className="glass-input flex items-center gap-2">
                                <Icon name="calendar_today" className="text-xl text-on-surface-variant" />
                                <input
                                    id="tgl_masuk"
                                    type="date"
                                    value={data.tgl_masuk}
                                    onChange={(e) => setData('tgl_masuk', e.target.value)}
                                    className="flex-1 bg-transparent border-0 outline-none text-on-surface py-1"
                                    required
                                />
                            </div>
                            {errors.tgl_masuk && <p className="mt-1 text-body-sm text-error">{errors.tgl_masuk}</p>}
                        </div>
                        <div>
                            <Label htmlFor="role">Role</Label>
                            <div className="glass-input flex items-center gap-2">
                                <Icon name="manage_accounts" className="text-xl text-on-surface-variant" />
                                <select
                                    id="role"
                                    value={data.role}
                                    onChange={(e) => setData('role', e.target.value)}
                                    className="flex-1 bg-transparent border-0 outline-none text-on-surface py-1"
                                    required
                                >
                                    <option value="">Pilih Role</option>
                                    <option value="mahasiswa">Mahasiswa</option>
                                    <option value="mentor">Mentor</option>
                                    <option value="alumni">Alumni</option>
                                </select>
                            </div>
                            {errors.role && <p className="mt-1 text-body-sm text-error">{errors.role}</p>}
                        </div>
                    </div>

                    <div>
                        <Label htmlFor="email">Email Address</Label>
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            placeholder="name@university.edu"
                            leftIcon={<Icon name="mail" className="text-xl" />}
                            error={errors.email}
                            required
                        />
                    </div>

                    <div>
                        <Label htmlFor="password">Password</Label>
                        <Input
                            id="password"
                            type={showPassword ? 'text' : 'password'}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            placeholder="••••••••"
                            leftIcon={<Icon name="lock" className="text-xl" />}
                            rightIcon={
                                <button
                                    type="button"
                                    onClick={() => setShowPassword(!showPassword)}
                                    className="text-on-surface-variant hover:text-on-surface"
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
                            placeholder="••••••••"
                            leftIcon={<Icon name="lock" className="text-xl" />}
                            error={errors.password_confirmation}
                            required
                        />
                    </div>

                    <Button type="submit" fullWidth size="lg" disabled={processing}>
                        {processing ? 'Creating account...' : 'Sign Up'}
                        {!processing && <Icon name="arrow_forward" />}
                    </Button>

                    <div className="flex items-center gap-3 pt-2">
                        <div className="flex-1 h-px bg-outline-variant/50" />
                        <span className="text-label-caps text-on-surface-variant">Or</span>
                        <div className="flex-1 h-px bg-outline-variant/50" />
                    </div>

                    <p className="text-center text-body-sm text-on-surface-variant">
                        Already a member?{' '}
                        <Link href="/login" className="text-primary-container font-semibold hover:underline">
                            Sign in
                        </Link>
                    </p>
                </form>
            </div>

            {/* Trust cards */}
            <div className="mt-6 grid grid-cols-2 gap-3 w-full max-w-md">
                <div className="glass-card p-3 flex items-center gap-3">
                    <div className="w-9 h-9 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <Icon name="shield" filled />
                    </div>
                    <div>
                        <div className="text-body-sm font-semibold text-on-surface">Secure</div>
                        <div className="text-label-caps text-on-surface-variant">Verified Registration</div>
                    </div>
                </div>
                <div className="glass-card p-3 flex items-center gap-3">
                    <div className="w-9 h-9 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <Icon name="self_improvement" filled />
                    </div>
                    <div>
                        <div className="text-body-sm font-semibold text-on-surface">Reflection</div>
                        <div className="text-label-caps text-on-surface-variant">Daily Sanctuary Habit</div>
                    </div>
                </div>
            </div>
        </AuthLayout>
    );
}
