<?php

namespace App\Http\Controllers\Web\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionController extends Controller
{
    // ── Roles ──────────────────────────────────────────────────

    public function index()
    {
        $roles = Role::with('permissions')->get()->map(fn ($r) => [
            'id'          => $r->id,
            'name'        => $r->name,
            'permissions' => $r->permissions->pluck('name'),
            'users_count' => User::role($r->name)->count(),
        ]);

        $permissions = Permission::orderBy('name')->pluck('name');

        $users = User::select('id', 'name', 'email', 'role', 'avatar')
            ->orderBy('name')
            ->get()
            ->map(fn ($u) => [
                'id'     => $u->id,
                'name'   => $u->name,
                'email'  => $u->email,
                'role'   => $u->role,
                'roles'  => $u->getRoleNames(),
                'avatar' => $u->avatar,
            ]);

        return Inertia::render('Super/RolePermission', compact('roles', 'permissions', 'users'));
    }

    public function storeRole(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('roles', 'name')],
        ]);

        Role::create(['name' => $data['name'], 'guard_name' => 'web']);

        return back()->with('success', "Role '{$data['name']}' berhasil dibuat.");
    }

    public function updateRole(Request $request, Role $role)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('roles', 'name')->ignore($role->id)],
        ]);

        $role->update(['name' => $data['name']]);

        return back()->with('success', "Role berhasil diperbarui.");
    }

    public function destroyRole(Role $role)
    {
        abort_if($role->name === 'super', 403, 'Role super tidak bisa dihapus.');

        $role->delete();

        return back()->with('success', "Role '{$role->name}' berhasil dihapus.");
    }

    // ── Permissions ────────────────────────────────────────────

    public function storePermission(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('permissions', 'name')],
        ]);

        Permission::create(['name' => $data['name'], 'guard_name' => 'web']);

        return back()->with('success', "Permission '{$data['name']}' berhasil dibuat.");
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();

        return back()->with('success', "Permission '{$permission->name}' berhasil dihapus.");
    }

    // ── Sync role ↔ permissions ────────────────────────────────

    public function syncRolePermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', "Permissions untuk role '{$role->name}' berhasil diperbarui.");
    }

    // ── User Management ────────────────────────────────────────

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        $user->assignRole($data['role']);

        return back()->with('success', "User '{$user->name}' berhasil dibuat dengan role {$data['role']}.");
    }

    // ── Assign role(s) to user — multi-role support ───────────

    public function assignUserRole(Request $request, User $user)
    {
        $data = $request->validate([
            'roles'   => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        // Sync all Spatie roles
        $user->syncRoles($data['roles']);

        // Update legacy role column to the primary (first) role
        $user->update(['role' => $data['roles'][0]]);

        $roleList = implode(', ', $data['roles']);
        return back()->with('success', "Role '{$user->name}' berhasil diubah ke: {$roleList}.");
    }

    // ── Change user password (admin) ──────────────────────────

    public function changePassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($data['password'])]);

        return back()->with('success', "Password '{$user->name}' berhasil diubah.");
    }
}
