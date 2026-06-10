<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\AlumniJob;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class AlumniJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_alumni_can_post_job_and_it_shows_on_student_dashboard()
    {
        // 1. Create Alumni User and assign role
        $alumni = User::factory()->create([
            'role' => 'alumni',
            'email' => 'alumni@example.com',
            'name' => 'Alumni Budi',
        ]);
        $alumni->assignRole('alumni');

        // 2. Post a job vacancy as Alumni
        $jobData = [
            'type'         => 'job',
            'title'        => 'Frontend Engineer React',
            'company'      => 'Astra Tech',
            'location'     => 'Jakarta',
            'work_type'    => 'remote',
            'description'  => 'Requirements: React, TypeScript, TailwindCSS.',
            'requirements' => '1+ years experience.',
            'salary_range' => 'Rp 8.000.000 - 12.000.000',
            'contact_info' => 'Email to: career@astratech.com',
            'deadline'     => now()->addDays(7)->format('Y-m-d'),
        ];

        $response = $this->actingAs($alumni)->post(route('alumni.jobs.store'), $jobData);
        $response->assertRedirect();

        $this->assertDatabaseHas('alumni_jobs', [
            'user_id' => $alumni->id,
            'title'   => 'Frontend Engineer React',
            'company' => 'Astra Tech',
        ]);

        // 3. Create Student User and assign role
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'student@example.com',
            'name' => 'Student Ahmad',
        ]);
        $student->assignRole('mahasiswa');

        // 4. Access Student Dashboard and assert latest_jobs contains the posted job
        $response = $this->actingAs($student)->get(route('dashboard'));
        $response->assertStatus(200);

        // Assert using Inertia.js Testing helpers
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('latest_jobs', 1)
            ->where('latest_jobs.0.title', 'Frontend Engineer React')
            ->where('latest_jobs.0.company', 'Astra Tech')
            ->where('latest_jobs.0.posted_by', 'Alumni Budi')
        );
    }
}
