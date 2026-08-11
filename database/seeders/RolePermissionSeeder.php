<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Define Permissions ────────────────────────────────────
        $permissions = [
            // Feature access gates (used by can() middleware in routes)
            'access-mahasiswa',
            'access-mentor',
            'access-super',
            'access-admin',
            'access-alumni',
            'access-pengurus-asrama',
            'access-live-meet',

            // Granular permissions
            'view-dashboard',
            'log-aktivitas',
            'log-hafalan',
            'view-leaderboard',
            'nilai-santri',
            'view-warga',
            'manage-warga',
            'manage-kegiatan',
            'manage-kegiatan-asrama',
            'manage-program-kerja',
            'manage-akun',
            'view-laporan',
            'export-data',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── Define Roles + assign permissions ─────────────────────

        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);
        $mahasiswa->syncPermissions([
            'access-mahasiswa', 'view-dashboard',
            'log-aktivitas', 'log-hafalan', 'view-leaderboard',
        ]);

        $mentor = Role::firstOrCreate(['name' => 'mentor', 'guard_name' => 'web']);
        $mentor->syncPermissions([
            'access-mentor', 'access-mahasiswa', 'view-dashboard',
            'nilai-santri', 'view-warga', 'view-leaderboard', 'log-hafalan',
        ]);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'access-admin', 'access-mahasiswa', 'access-mentor', 'view-dashboard',
            'view-warga', 'manage-warga', 'manage-kegiatan',
            'nilai-santri', 'view-laporan',
        ]);

        $super = Role::firstOrCreate(['name' => 'super', 'guard_name' => 'web']);
        $super->syncPermissions(Permission::all()); // Super gets everything

        $alumni = Role::firstOrCreate(['name' => 'alumni', 'guard_name' => 'web']);
        $alumni->syncPermissions([
            'access-alumni', 'view-dashboard',
        ]);

        $pengurusAsrama = Role::firstOrCreate(['name' => 'pengurus_asrama', 'guard_name' => 'web']);
        $pengurusAsrama->syncPermissions([
            'access-mahasiswa',
            'access-pengurus-asrama',
            'view-dashboard',
            'log-aktivitas',
            'log-hafalan',
            'view-leaderboard',
            'manage-kegiatan-asrama',
            'manage-program-kerja',
            'manage-warga',
        ]);

        // ── Sync existing users' role column → Spatie role ───────
        $roleMap = ['mahasiswa', 'mentor', 'admin', 'super', 'alumni', 'pengurus_asrama'];
        User::whereIn('role', $roleMap)->each(function (User $user) {
            if ($user->role) {
                $user->syncRoles([$user->role]);
            }
        });

        $this->command->info('✅ Roles + permissions seeded. Users synced.');
    }
}
