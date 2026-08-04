import { Head, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { PageProps } from '@/types';

interface RoleItem {
    id: number;
    name: string;
    permissions: string[];
    users_count: number;
}

interface UserItem {
    id: number;
    name: string;
    email: string;
    role: string;
    roles: string[];
    avatar?: string;
    asrama?: string | null;
}

interface Props extends PageProps {
    roles: RoleItem[];
    permissions: string[];
    users: UserItem[];
}

const ROLE_COLOR: Record<string, string> = {
    super:     'bg-violet-100 text-violet-700 border-violet-200',
    admin:     'bg-blue-100 text-blue-700 border-blue-200',
    mentor:    'bg-emerald-100 text-emerald-700 border-emerald-200',
    mahasiswa: 'bg-amber-100 text-amber-700 border-amber-200',
    alumni:    'bg-rose-100 text-rose-700 border-rose-200',
};

const ROLE_ICON: Record<string, string> = {
    super:     'admin_panel_settings',
    admin:     'manage_accounts',
    mentor:    'supervisor_account',
    mahasiswa: 'school',
    alumni:    'workspace_premium',
};

export default function RolePermission({ roles, permissions, users }: Props) {
    const [activeTab, setActiveTab] = useState<'roles' | 'users'>('roles');
    const [selectedRole, setSelectedRole] = useState<RoleItem | null>(roles[0] ?? null);
    const [newRoleName, setNewRoleName] = useState('');
    const [newPermName, setNewPermName] = useState('');
    const [userSearch, setUserSearch] = useState('');
    const [asramaFilter, setAsramaFilter] = useState('');

    // Local copy of users — prevents list from blanking during Inertia reloads
    const [localUsers, setLocalUsers] = useState<UserItem[]>(users ?? []);

    // Sync localUsers whenever Inertia refreshes the users prop after a POST
    useEffect(() => { setLocalUsers(users ?? []); }, [users]);

    // Toast notification
    const [toast, setToast] = useState<{ msg: string; type: 'success' | 'error' } | null>(null);
    function showToast(msg: string, type: 'success' | 'error' = 'success') {
        setToast({ msg, type });
        setTimeout(() => setToast(null), 4000);
    }

    // Track local permission state for selected role
    const [localPerms, setLocalPerms] = useState<Record<number, string[]>>(
        Object.fromEntries(roles.map(r => [r.id, [...r.permissions]]))
    );

    const currentPerms = selectedRole ? (localPerms[selectedRole.id] ?? []) : [];

    function togglePerm(perm: string) {
        if (!selectedRole) return;
        setLocalPerms(prev => {
            const curr = prev[selectedRole.id] ?? [];
            return {
                ...prev,
                [selectedRole.id]: curr.includes(perm)
                    ? curr.filter(p => p !== perm)
                    : [...curr, perm],
            };
        });
    }

    function savePerms() {
        if (!selectedRole) return;
        router.post(`/super/roles/${selectedRole.id}/permissions`, {
            permissions: localPerms[selectedRole.id] ?? [],
            _method: 'patch',
        }, {
            preserveState:  true,
            preserveScroll: true,
        });
    }

    function createRole() {
        if (!newRoleName.trim()) return;
        router.post('/super/roles', { name: newRoleName });
        setNewRoleName('');
    }

    function deleteRole(role: RoleItem) {
        if (!confirm(`Hapus role "${role.name}"?`)) return;
        router.delete(`/super/roles/${role.id}`);
    }

    function createPermission() {
        if (!newPermName.trim()) return;
        router.post('/super/permissions', { name: newPermName });
        setNewPermName('');
    }

    function deletePermission(perm: string) {
        if (!confirm(`Hapus permission "${perm}"?`)) return;
        router.delete(`/super/permissions/${encodeURIComponent(perm)}`);
    }

    function deleteUser(user: UserItem) {
        if (!confirm(`Hapus user "${user.name}" (${user.email})? Tindakan ini tidak bisa dibatalkan.`)) return;
        router.delete(`/super/users/${user.id}`, { preserveScroll: true });
    }

    // Multi-role modal state
    const [pendingRoles, setPendingRoles] = useState<Record<number, string[]>>(
        Object.fromEntries(users.map(u => [u.id, Array.isArray(u.roles) && u.roles.length > 0 ? [...u.roles] : [u.role]]))
    );
    const [editingUser, setEditingUser] = useState<UserItem | null>(null);

    function openModal(user: UserItem) {
        // Reset pending to current Spatie roles when opening
        setPendingRoles(prev => ({
            ...prev,
            [user.id]: Array.isArray(user.roles) && user.roles.length > 0 ? [...user.roles] : [user.role],
        }));
        setEditingUser(user);
    }
    function closeModal() { setEditingUser(null); }

    // Password modal state
    const [pwUser, setPwUser]   = useState<UserItem | null>(null);
    const [pw, setPw]           = useState('');
    const [pwConfirm, setPwConfirm] = useState('');
    const [showPw, setShowPw]   = useState(false);
    const [pwError, setPwError] = useState('');

    // Add user modal state
    const [isAddModalOpen, setIsAddModalOpen] = useState(false);
    const [newUser, setNewUser] = useState({ name: '', email: '', password: '', role: 'mahasiswa' });

    function openAddModal() {
        setNewUser({ name: '', email: '', password: '', role: 'mahasiswa' });
        setIsAddModalOpen(true);
    }
    function closeAddModal() { setIsAddModalOpen(false); }

    function createUser() {
        if (!newUser.name || !newUser.email || !newUser.password || !newUser.role) {
            showToast('Semua field harus diisi.', 'error');
            return;
        }
        router.post('/super/users', newUser, {
            onSuccess: () => {
                closeAddModal();
                showToast(`✓ User ${newUser.name} berhasil dibuat.`);
            },
            onError: (errs) => {
                showToast(Object.values(errs)[0] as string ?? 'Gagal membuat user.', 'error');
            }
        });
    }

    function openPasswordModal(user: UserItem) {
        setPwUser(user);
        setPw(''); setPwConfirm(''); setPwError(''); setShowPw(false);
    }
    function closePasswordModal() { setPwUser(null); }

    function savePassword() {
        if (pw.length < 8)    { setPwError('Password minimal 8 karakter.'); return; }
        if (pw !== pwConfirm) { setPwError('Konfirmasi password tidak cocok.'); return; }
        if (!pwUser) return;
        const targetName = pwUser.name;
        router.post(`/super/users/${pwUser.id}/password`, {
            password: pw, password_confirmation: pwConfirm,
        }, {
            preserveState:  true,
            preserveScroll: true,
            onSuccess: () => {
                closePasswordModal();
                showToast(`✓ Password ${targetName} berhasil diubah.`);
            },
            onError: (errs) => {
                setPwError(Object.values(errs)[0] as string ?? 'Gagal mengubah password.');
            },
        });
    }

    function toggleUserRole(userId: number, roleName: string) {
        setPendingRoles(prev => {
            const curr = prev[userId] ?? [];
            return {
                ...prev,
                [userId]: curr.includes(roleName)
                    ? curr.filter(r => r !== roleName)
                    : [...curr, roleName],
            };
        });
    }

    function saveUserRoles(user: UserItem) {
        const selected = pendingRoles[user.id] ?? [];
        if (selected.length === 0) return;
        router.post(`/super/users/${user.id}/role`, { roles: selected }, {
            preserveState:  true,
            preserveScroll: true,
            onSuccess: () => {
                // Update local copy so badges refresh without page reload
                setLocalUsers(prev => prev.map(u =>
                    u.id === user.id ? { ...u, roles: selected, role: selected[0] } : u
                ));
                closeModal();
                showToast(`✓ Role ${user.name} berhasil diperbarui.`);
            },
        });
    }

    const asramaOptions = Array.from(
        new Set((localUsers ?? []).map(u => u.asrama).filter((a): a is string => !!a))
    ).sort();

    const filteredUsers = (localUsers ?? []).filter(u =>
        (u.name.toLowerCase().includes(userSearch.toLowerCase()) ||
            u.email.toLowerCase().includes(userSearch.toLowerCase())) &&
        (asramaFilter === '' || u.asrama === asramaFilter)
    );

    const PERM_GROUPS: Record<string, string[]> = {};
    permissions.forEach(p => {
        const prefix = p.split('-')[0];
        (PERM_GROUPS[prefix] ??= []).push(p);
    });

    return (
        <AppLayout searchPlaceholder="Cari role / permission...">
            <Head title="Role & Permission" />

            <PageHeader
                title="Role & Permission"
                subtitle="Kelola hak akses dan izin fitur untuk setiap role pengguna SIMONAS."
                breadcrumbs={[
                    { label: 'Beranda', href: '/dashboard' },
                    { label: 'Role & Permission' },
                ]}
            />

            {/* Tab switcher */}
            <div className="flex gap-2 p-1.5 glass-panel rounded-xl w-fit mb-8">
                {(['roles', 'users'] as const).map(tab => (
                    <button
                        key={tab}
                        onClick={() => setActiveTab(tab)}
                        className={`px-6 py-2 rounded-lg text-sm font-semibold transition-all capitalize ${
                            activeTab === tab
                                ? 'bg-primary-container text-on-primary shadow-md'
                                : 'text-on-surface-variant hover:text-on-surface'
                        }`}
                    >
                        <Icon name={tab === 'roles' ? 'shield' : 'group'} className="text-base mr-1.5 align-text-bottom" />
                        {tab === 'roles' ? 'Roles & Permissions' : 'User Roles'}
                    </button>
                ))}
            </div>

            {activeTab === 'roles' && (
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {/* Left: Roles list */}
                    <div className="space-y-4">
                        {/* Create role */}
                        <div className="glass-card p-4 rounded-2xl">
                            <h3 className="text-label-caps text-on-surface-variant mb-3">Tambah Role Baru</h3>
                            <div className="flex gap-2">
                                <input
                                    type="text"
                                    value={newRoleName}
                                    onChange={e => setNewRoleName(e.target.value)}
                                    onKeyDown={e => e.key === 'Enter' && createRole()}
                                    placeholder="nama-role"
                                    className="glass-input flex-1 text-body-sm"
                                />
                                <button
                                    onClick={createRole}
                                    className="btn-primary px-3 py-2 rounded-xl"
                                >
                                    <Icon name="add" className="text-xl" />
                                </button>
                            </div>
                        </div>

                        {/* Roles list */}
                        <div className="space-y-2">
                            {roles.map(role => (
                                <div
                                    key={role.id}
                                    onClick={() => setSelectedRole(role)}
                                    className={`w-full flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer ${
                                        selectedRole?.id === role.id
                                            ? 'bg-primary-container/10 border-primary-container shadow-md'
                                            : 'glass-card hover:shadow-md'
                                    }`}
                                >
                                    <div className="flex items-center gap-3">
                                        <div className={`w-10 h-10 rounded-xl flex items-center justify-center ${
                                            ROLE_COLOR[role.name] ?? 'bg-surface-container text-on-surface'
                                        }`}>
                                            <Icon
                                                name={ROLE_ICON[role.name] ?? 'person'}
                                                className="text-xl"
                                                filled
                                            />
                                        </div>
                                        <div>
                                            <p className="font-bold text-on-surface capitalize">{role.name}</p>
                                            <p className="text-xs text-on-surface-variant">
                                                {role.permissions.length} permissions · {role.users_count} users
                                            </p>
                                        </div>
                                    </div>
                                    {role.name !== 'super' && (
                                        <button
                                            onClick={e => { e.stopPropagation(); deleteRole(role); }}
                                            className="p-1.5 rounded-lg text-outline hover:text-error hover:bg-error/10 transition-colors"
                                        >
                                            <Icon name="delete" className="text-base" />
                                        </button>
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Right: Permission matrix for selected role */}
                    <div className="lg:col-span-2">
                        {selectedRole ? (
                            <div className="glass-card p-6 rounded-2xl space-y-6">
                                {/* Header */}
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-3">
                                        <div className={`w-12 h-12 rounded-xl flex items-center justify-center ${
                                            ROLE_COLOR[selectedRole.name] ?? 'bg-surface-container text-on-surface'
                                        }`}>
                                            <Icon
                                                name={ROLE_ICON[selectedRole.name] ?? 'person'}
                                                className="text-2xl"
                                                filled
                                            />
                                        </div>
                                        <div>
                                            <h2 className="font-display text-headline-md capitalize">{selectedRole.name}</h2>
                                            <p className="text-body-sm text-on-surface-variant">
                                                {currentPerms.length} dari {permissions.length} permissions aktif
                                            </p>
                                        </div>
                                    </div>
                                    <button
                                        onClick={savePerms}
                                        className="btn-primary px-6 py-2.5 rounded-xl flex items-center gap-2"
                                    >
                                        <Icon name="save" className="text-xl" />
                                        Simpan
                                    </button>
                                </div>

                                {/* Progress bar */}
                                <div className="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                                    <div
                                        className="h-full bg-primary-container rounded-full transition-all"
                                        style={{ width: `${permissions.length ? (currentPerms.length / permissions.length) * 100 : 0}%` }}
                                    />
                                </div>

                                {/* Permission groups */}
                                <div className="space-y-5">
                                    {Object.entries(PERM_GROUPS).map(([group, perms]) => (
                                        <div key={group}>
                                            <h4 className="text-label-caps text-on-surface-variant mb-3 uppercase">{group}</h4>
                                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                {perms.map(perm => {
                                                    const active = currentPerms.includes(perm);
                                                    return (
                                                        <label
                                                            key={perm}
                                                            className={`flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all ${
                                                                active
                                                                    ? 'bg-primary-container/10 border-primary-container/30'
                                                                    : 'bg-white/40 border-white hover:bg-white/70'
                                                            }`}
                                                        >
                                                            <div className={`w-5 h-5 rounded flex items-center justify-center flex-shrink-0 border-2 transition-colors ${
                                                                active
                                                                    ? 'bg-primary-container border-primary-container'
                                                                    : 'border-outline-variant'
                                                            }`}>
                                                                {active && (
                                                                    <Icon name="check" className="text-xs text-on-primary" />
                                                                )}
                                                            </div>
                                                            <span className="text-body-sm font-medium text-on-surface">{perm}</span>
                                                            <button
                                                                type="button"
                                                                onClick={e => { e.preventDefault(); deletePermission(perm); }}
                                                                className="ml-auto p-1 text-outline hover:text-error transition-colors"
                                                            >
                                                                <Icon name="close" className="text-xs" />
                                                            </button>
                                                            <input
                                                                type="checkbox"
                                                                checked={active}
                                                                onChange={() => togglePerm(perm)}
                                                                className="sr-only"
                                                            />
                                                        </label>
                                                    );
                                                })}
                                            </div>
                                        </div>
                                    ))}
                                </div>

                                {/* Add permission */}
                                <div className="border-t border-white/40 pt-5">
                                    <h4 className="text-label-caps text-on-surface-variant mb-3">Tambah Permission Baru</h4>
                                    <div className="flex gap-2">
                                        <input
                                            type="text"
                                            value={newPermName}
                                            onChange={e => setNewPermName(e.target.value)}
                                            onKeyDown={e => e.key === 'Enter' && createPermission()}
                                            placeholder="nama-permission (e.g. export-data)"
                                            className="glass-input flex-1 text-body-sm"
                                        />
                                        <button
                                            onClick={createPermission}
                                            className="btn-primary px-4 py-2 rounded-xl text-sm"
                                        >
                                            <Icon name="add" className="text-xl" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="glass-card p-12 rounded-2xl flex flex-col items-center text-center gap-3 text-on-surface-variant">
                                <Icon name="shield" className="text-5xl opacity-30" />
                                <p>Pilih role di sebelah kiri untuk mengatur permissions.</p>
                            </div>
                        )}
                    </div>
                </div>
            )}

            {activeTab === 'users' && (
                <div className="glass-card rounded-2xl overflow-hidden">
                    {/* Search */}
                    <div className="p-5 border-b border-white/40 flex flex-wrap items-center gap-3">
                        <Icon name="search" className="text-xl text-on-surface-variant" />
                        <input
                            type="text" value={userSearch}
                            onChange={e => setUserSearch(e.target.value)}
                            placeholder="Cari nama atau email..."
                            className="bg-transparent outline-none flex-1 text-body-sm placeholder:text-on-surface-variant/60 min-w-[160px]"
                        />
                        <div className="flex items-center gap-2">
                            <Icon name="home_work" className="text-lg text-on-surface-variant" />
                            <select
                                value={asramaFilter}
                                onChange={e => setAsramaFilter(e.target.value)}
                                className="glass-input text-body-sm py-2 pr-8 rounded-xl"
                            >
                                <option value="">Semua Asrama</option>
                                {asramaOptions.map(a => (
                                    <option key={a} value={a}>{a}</option>
                                ))}
                            </select>
                        </div>
                        <div className="flex items-center gap-4 ml-auto">
                            <span className="text-label-caps text-on-surface-variant hidden sm:inline">{filteredUsers.length} users</span>
                            <button
                                onClick={openAddModal}
                                className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm"
                            >
                                <Icon name="person_add" className="text-xl" />
                                <span className="hidden sm:inline">Tambah User</span>
                            </button>
                        </div>
                    </div>

                    {/* Table header */}
                    <div className="hidden md:grid grid-cols-12 px-6 py-3 bg-blue-50/20 border-b border-white/40">
                        {[
                            { label:'#',        cols:'col-span-1' },
                            { label:'Pengguna', cols:'col-span-3' },
                            { label:'Email',    cols:'col-span-3' },
                            { label:'Roles Aktif', cols:'col-span-3' },
                            { label:'Kelola Roles', cols:'col-span-2 text-right' },
                        ].map(h => (
                            <p key={h.label} className={`text-label-caps text-on-surface-variant ${h.cols}`}>{h.label}</p>
                        ))}
                    </div>
                    <div className="divide-y divide-white/20">
                        {filteredUsers.map((user, idx) => (
                            <div key={user.id} className="grid grid-cols-1 md:grid-cols-12 gap-4 px-6 py-4 hover:bg-white/40 transition-colors items-center">
                                {/* Index */}
                                <p className="col-span-1 text-xs font-black text-on-surface-variant tabular-nums">{idx + 1}</p>

                                {/* Name */}
                                <div className="col-span-3 flex items-center gap-3">
                                    {user.avatar ? (
                                        <img src={user.avatar} alt={user.name} className="w-9 h-9 rounded-full object-cover border border-white" />
                                    ) : (
                                        <div className="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center text-sm font-bold text-primary-container flex-shrink-0">
                                            {user.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                        </div>
                                    )}
                                    <span className="font-semibold text-on-surface text-sm">{user.name}</span>
                                </div>

                                {/* Email */}
                                <span className="col-span-3 text-body-sm text-on-surface-variant truncate">{user.email}</span>

                                {/* All role badges */}
                                <div className="col-span-3 flex flex-wrap gap-1.5">
                                    {(Array.isArray(user.roles) && user.roles.length > 0 ? user.roles : [user.role]).map(r => (
                                        <span key={r} className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black border capitalize ${
                                            ROLE_COLOR[r] ?? 'bg-surface-container text-on-surface border-outline-variant'
                                        }`}>
                                            <Icon name={ROLE_ICON[r] ?? 'person'} className="text-xs" filled />
                                            {r.replace(/_/g, ' ')}
                                        </span>
                                    ))}
                                </div>

                                {/* Action buttons */}
                                <div className="col-span-2 flex justify-end gap-1.5">
                                    <button
                                        onClick={() => openModal(user)}
                                        title="Ubah Roles"
                                        className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-surface-container text-on-surface-variant hover:bg-white/60 hover:shadow-md transition-all"
                                    >
                                        <Icon name="manage_accounts" className="text-base" />
                                        Ubah
                                    </button>
                                    <button
                                        onClick={() => openPasswordModal(user)}
                                        title="Ganti Password"
                                        className="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 hover:shadow-md transition-all"
                                    >
                                        <Icon name="lock_reset" className="text-base" />
                                        Password
                                    </button>
                                    <button
                                        onClick={() => deleteUser(user)}
                                        title="Hapus User"
                                        className="flex items-center justify-center w-8 h-8 rounded-xl text-xs font-bold bg-rose-50 text-rose-600 hover:bg-rose-100 hover:shadow-md transition-all"
                                    >
                                        <Icon name="delete" className="text-base" />
                                    </button>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {/* ── Multi-Role Modal ──────────────────────────────────── */}
            {editingUser && (() => {
                const uid = editingUser.id;
                const userPendingRoles = pendingRoles[uid] ?? [];
                return (
                    <div
                        className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
                        onClick={e => e.target === e.currentTarget && closeModal()}
                    >
                        <div className="glass-card rounded-2xl w-full max-w-md shadow-2xl">
                            {/* Header */}
                            <div className="flex items-center justify-between px-6 py-4 border-b border-white/40">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 rounded-xl bg-primary-container/10 flex items-center justify-center font-black text-primary-container text-sm flex-shrink-0">
                                        {editingUser.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                                    </div>
                                    <div>
                                        <h3 className="font-bold text-on-surface">{editingUser.name}</h3>
                                        <p className="text-xs text-on-surface-variant">{editingUser.email}</p>
                                    </div>
                                </div>
                                <button
                                    onClick={closeModal}
                                    className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center transition-colors"
                                >
                                    <Icon name="close" className="text-on-surface-variant" />
                                </button>
                            </div>

                            {/* Body */}
                            <div className="px-6 py-5">
                                <p className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-4">
                                    Pilih Roles — bisa lebih dari satu
                                </p>

                                <div className="grid grid-cols-2 gap-2 mb-5">
                                    {roles.map(r => {
                                        const checked = userPendingRoles.includes(r.name);
                                        return (
                                            <button
                                                key={r.id}
                                                type="button"
                                                onClick={() => toggleUserRole(uid, r.name)}
                                                className={`flex items-center gap-2 px-3 py-3 rounded-xl text-sm font-bold border-2 transition-all text-left min-w-0 ${
                                                    checked
                                                        ? (ROLE_COLOR[r.name] ?? 'bg-primary-container') + ' border-current shadow-md scale-[1.02]'
                                                        : 'border-surface-container text-on-surface-variant bg-white/50 hover:bg-white'
                                                }`}
                                            >
                                                <div className={`w-5 h-5 rounded border-2 flex items-center justify-center flex-shrink-0 transition-all ${
                                                    checked ? 'bg-current border-current' : 'border-outline-variant'
                                                }`}>
                                                    {checked && <Icon name="check" className="text-[10px] text-white" />}
                                                </div>
                                                <Icon name={ROLE_ICON[r.name] ?? 'person'} className="text-base flex-shrink-0" filled />
                                                <span className="capitalize truncate min-w-0">{r.name.replace(/_/g, ' ')}</span>
                                            </button>
                                        );
                                    })}
                                </div>

                                {/* Selected summary badges */}
                                <div className="flex flex-wrap gap-1.5 min-h-[28px] mb-1">
                                    {userPendingRoles.length === 0 ? (
                                        <p className="text-xs text-rose-500 font-bold">⚠ Pilih minimal 1 role</p>
                                    ) : userPendingRoles.map(r => (
                                        <span key={r} className={`inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black border capitalize ${
                                            ROLE_COLOR[r] ?? 'bg-surface-container text-on-surface border-outline-variant'
                                        }`}>
                                            <Icon name={ROLE_ICON[r] ?? 'person'} className="text-xs" filled />
                                            {r.replace(/_/g, ' ')}
                                        </span>
                                    ))}
                                </div>
                            </div>

                            {/* Footer */}
                            <div className="flex gap-3 px-6 pb-5">
                                <button
                                    onClick={closeModal}
                                    className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors"
                                >
                                    Batal
                                </button>
                                <button
                                    onClick={() => saveUserRoles(editingUser)}
                                    disabled={userPendingRoles.length === 0}
                                    className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-primary-container text-white hover:opacity-90 transition-opacity disabled:opacity-40 flex items-center justify-center gap-2"
                                >
                                    <Icon name="save" className="text-base" />
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                );
            })()}

            {/* ── Ganti Password Modal ─────────────────────────────── */}
            {pwUser && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
                    onClick={e => e.target === e.currentTarget && closePasswordModal()}
                >
                    <div className="glass-card rounded-2xl w-full max-w-sm shadow-2xl">
                        {/* Header */}
                        <div className="flex items-center justify-between px-6 py-4 border-b border-white/40">
                            <div className="flex items-center gap-3">
                                <div className="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                                    <Icon name="lock_reset" className="text-amber-600 text-xl" />
                                </div>
                                <div>
                                    <h3 className="font-bold text-on-surface text-sm">Ganti Password</h3>
                                    <p className="text-xs text-on-surface-variant">{pwUser.name}</p>
                                </div>
                            </div>
                            <button onClick={closePasswordModal}
                                className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center transition-colors">
                                <Icon name="close" className="text-on-surface-variant" />
                            </button>
                        </div>

                        {/* Body — wrapped in form to satisfy browser password manager */}
                        <form onSubmit={e => { e.preventDefault(); savePassword(); }} className="px-6 py-5 space-y-3">
                            {/* New password */}
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">
                                    Password Baru
                                </label>
                                <div className="relative">
                                    <input
                                        type={showPw ? 'text' : 'password'}
                                        value={pw}
                                        autoComplete="new-password"
                                        onChange={e => { setPw(e.target.value); setPwError(''); }}
                                        placeholder="Minimal 8 karakter"
                                        className="glass-input w-full pr-10 text-sm"
                                    />
                                    <button type="button" onClick={() => setShowPw(s => !s)}
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant">
                                        <Icon name={showPw ? 'visibility_off' : 'visibility'} className="text-base" />
                                    </button>
                                </div>
                            </div>

                            {/* Confirm */}
                            <div>
                                <label className="text-[10px] font-black uppercase tracking-widest text-on-surface-variant mb-1.5 block">
                                    Konfirmasi Password
                                </label>
                                <input
                                    type={showPw ? 'text' : 'password'}
                                    value={pwConfirm}
                                    autoComplete="new-password"
                                    onChange={e => { setPwConfirm(e.target.value); setPwError(''); }}
                                    placeholder="Ulangi password baru"
                                    className="glass-input w-full text-sm"
                                />
                            </div>

                            {/* Strength indicator */}
                            {pw.length > 0 && (
                                <div className="space-y-1">
                                    <div className="flex gap-1">
                                        {[8, 10, 12, 16].map(len => (
                                            <div key={len} className={`flex-1 h-1 rounded-full transition-colors ${
                                                pw.length >= len
                                                    ? pw.length >= 16 ? 'bg-emerald-500'
                                                    : pw.length >= 12 ? 'bg-blue-500'
                                                    : pw.length >= 10 ? 'bg-amber-500'
                                                    : 'bg-rose-400'
                                                    : 'bg-surface-container'
                                            }`} />
                                        ))}
                                    </div>
                                    <p className="text-[10px] text-on-surface-variant">
                                        {pw.length < 10 ? 'Lemah' : pw.length < 12 ? 'Cukup' : pw.length < 16 ? 'Kuat' : 'Sangat Kuat'}
                                    </p>
                                </div>
                            )}

                            {/* Error */}
                            {pwError && (
                                <p className="text-xs text-rose-500 font-bold flex items-center gap-1">
                                    <Icon name="error" className="text-sm" /> {pwError}
                                </p>
                            )}

                            {/* Footer inside form so Enter key submits */}
                            <div className="flex gap-3 pt-2">
                                <button type="button" onClick={closePasswordModal}
                                    className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">
                                    Batal
                                </button>
                                <button type="submit"
                                    disabled={pw.length < 8 || pw !== pwConfirm}
                                    className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-amber-500 text-white hover:bg-amber-600 transition-colors disabled:opacity-40 flex items-center justify-center gap-2">
                                    <Icon name="save" className="text-base" />
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}


            {/* ── Add User Modal ────────────────────────────────────── */}
            {isAddModalOpen && (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
                    onClick={e => e.target === e.currentTarget && closeAddModal()}
                >
                    <div className="glass-card rounded-2xl w-full max-w-md shadow-2xl overflow-hidden">
                        <div className="flex items-center justify-between px-6 py-4 border-b border-white/40">
                            <h3 className="font-bold text-on-surface">Tambah User Manual</h3>
                            <button onClick={closeAddModal} className="w-8 h-8 rounded-lg bg-surface-container hover:bg-white/80 flex items-center justify-center transition-colors">
                                <Icon name="close" className="text-on-surface-variant" />
                            </button>
                        </div>
                        <div className="p-6 space-y-4">
                            <div>
                                <label className="text-label-caps text-on-surface-variant mb-1.5 block">Nama Lengkap</label>
                                <input
                                    type="text"
                                    value={newUser.name}
                                    onChange={e => setNewUser({ ...newUser, name: e.target.value })}
                                    placeholder="Masukkan nama lengkap"
                                    className="glass-input w-full"
                                />
                            </div>
                            <div>
                                <label className="text-label-caps text-on-surface-variant mb-1.5 block">Email</label>
                                <input
                                    type="email"
                                    value={newUser.email}
                                    onChange={e => setNewUser({ ...newUser, email: e.target.value })}
                                    placeholder="email@contoh.com"
                                    className="glass-input w-full"
                                />
                            </div>
                            <div>
                                <label className="text-label-caps text-on-surface-variant mb-1.5 block">Password</label>
                                <input
                                    type="password"
                                    value={newUser.password}
                                    onChange={e => setNewUser({ ...newUser, password: e.target.value })}
                                    placeholder="Minimal 8 karakter"
                                    className="glass-input w-full"
                                />
                            </div>
                            <div>
                                <label className="text-label-caps text-on-surface-variant mb-1.5 block">Role Utama</label>
                                <select
                                    value={newUser.role}
                                    onChange={e => setNewUser({ ...newUser, role: e.target.value })}
                                    className="glass-input w-full"
                                >
                                    {roles.map(r => (
                                        <option key={r.id} value={r.name}>{r.name.charAt(0).toUpperCase() + r.name.slice(1)}</option>
                                    ))}
                                </select>
                            </div>
                        </div>
                        <div className="flex gap-3 px-6 pb-6">
                            <button onClick={closeAddModal} className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors">Batal</button>
                            <button onClick={createUser} className="flex-1 py-2.5 rounded-xl font-bold text-sm bg-primary-container text-white hover:opacity-90 transition-opacity">Simpan User</button>
                        </div>
                    </div>
                </div>
            )}
            {/* ── Toast Notification ─────────────────────────────── */}
            {toast && (
                <div className={`fixed bottom-6 right-6 z-[70] flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-2xl text-sm font-bold text-white transition-all ${
                    toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'
                }`}>
                    <Icon
                        name={toast.type === 'success' ? 'check_circle' : 'error'}
                        className="text-xl flex-shrink-0"
                        filled
                    />
                    <span>{toast.msg}</span>
                    <button
                        onClick={() => setToast(null)}
                        className="ml-2 opacity-70 hover:opacity-100 transition-opacity"
                    >
                        <Icon name="close" className="text-base" />
                    </button>
                </div>
            )}
        </AppLayout>
    );
}
