<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Single-role demo accounts ──────────────────────────────
        $users = [
            [
                'name'     => 'Ahmad Fauzi',
                'email'    => 'mahasiswa@simonas.test',
                'password' => Hash::make('password'),
                'role'     => 'mahasiswa',
                'roles'    => ['mahasiswa'],
            ],
            [
                'name'     => 'Ust. Ahmad Yani',
                'email'    => 'mentor@simonas.test',
                'password' => Hash::make('password'),
                'role'     => 'mentor',
                'roles'    => ['mentor'],
            ],
            [
                'name'     => 'Abdullah Karim',
                'email'    => 'alumni@simonas.test',
                'password' => Hash::make('password'),
                'role'     => 'alumni',
                'roles'    => ['alumni'],
            ],
            // ── Multi-role demo: mentor sekaligus alumni ───────────
            [
                'name'     => 'Ust. Hilman Fathoni',
                'email'    => 'mentor.alumni@simonas.test',
                'password' => Hash::make('password'),
                'role'     => 'mentor',   // primary role (legacy column)
                'roles'    => ['mentor', 'alumni'], // both Spatie roles
            ],
        ];

        foreach ($users as $data) {
            $roles = $data['roles'];
            unset($data['roles']);

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            // Assign all Spatie roles (multi-role support)
            $user->syncRoles($roles);

            $this->command->info("✅ {$user->email} → roles: " . implode(', ', $roles));
        }
    }
}
