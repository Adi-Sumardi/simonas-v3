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

            // Create a default Hafalan progress record for the student
            if ($user->role === 'mahasiswa') {
                \App\Models\Hafalan::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'target_juz' => 30,
                        'current_juz' => 10,
                        'current_ayah' => 20,
                        'total_ayah_completed' => 1200,
                        'streak_days' => 5,
                        'current_surah_nomor' => 9,
                        'current_surah_nama' => 'At-Taubah',
                        'current_ayat' => 1,
                        'current_page' => 187,
                    ]
                );
            }

            $this->command->info("✅ {$user->email} → roles: " . implode(', ', $roles));
        }

        // ── Seed Demo Job Vacancies from Alumni ───────────────────
        $alumniUser = User::where('email', 'alumni@simonas.test')->first();
        if ($alumniUser) {
            \App\Models\AlumniJob::updateOrCreate(
                ['title' => 'Software Engineer (React/Laravel)', 'company' => 'SIMONAS Tech'],
                [
                    'user_id'      => $alumniUser->id,
                    'type'         => 'job',
                    'location'     => 'Jakarta (Hybrid)',
                    'work_type'    => 'hybrid',
                    'description'  => "Bergabunglah dengan tim kami untuk membangun platform SIMONAS.\nKeahlian yang dibutuhkan:\n- React & TypeScript\n- Laravel / PHP 8\n- TailwindCSS & SQL",
                    'requirements' => "1. Pengalaman kerja minimal 1 tahun\n2. Memahami konsep REST API & Clean Code\n3. Bersemangat belajar hal baru",
                    'salary_range' => 'Rp 8.000.000 - Rp 15.000.000',
                    'contact_info' => 'Kirim CV ke hrd@simonas.test',
                    'deadline'     => now()->addDays(30),
                    'is_active'    => true,
                ]
            );

            \App\Models\AlumniJob::updateOrCreate(
                ['title' => 'UI/UX Designer Intern', 'company' => 'Amanah Studio'],
                [
                    'user_id'      => $alumniUser->id,
                    'type'         => 'internship',
                    'location'     => 'Bandung (Remote)',
                    'work_type'    => 'remote',
                    'description'  => "Kami mencari mahasiswa tingkat akhir atau fresh graduate untuk belajar bersama mendesain interface web & mobile apps.",
                    'requirements' => "1. Menguasai Figma\n2. Portofolio UI/UX\n3. Bersedia magang 3 bulan",
                    'salary_range' => 'Uang Saku Menarik',
                    'contact_info' => 'Apply di amanahstudio.test/career',
                    'deadline'     => now()->addDays(15),
                    'is_active'    => true,
                ]
            );

            $this->command->info("✅ Seeded demo job vacancies from alumni.");
        }
    }
}
