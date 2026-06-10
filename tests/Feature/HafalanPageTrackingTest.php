<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\HafalanLog;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class HafalanPageTrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_student_can_submit_memorization_with_pages()
    {
        $mentor = User::factory()->create([
            'role' => 'mentor',
            'email' => 'mentor@example.com',
        ]);
        $mentor->assignRole('mentor');

        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'mentor_id' => $mentor->id,
            'email' => 'student@example.com',
        ]);
        $student->assignRole('mahasiswa');

        $response = $this->actingAs($student)->post(route('mahasiswa.hafalan.log.store'), [
            'surah'         => 'Al-Baqarah',
            'ayat_start'    => 1,
            'ayat_end'      => 5,
            'halaman_start' => 2,
            'halaman_end'   => 2,
            'notes'         => 'Setoran halaman 2',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('hafalan_logs', [
            'user_id'       => $student->id,
            'mentor_id'     => $mentor->id,
            'surah'         => 'Al-Baqarah',
            'ayat_start'    => 1,
            'ayat_end'      => 5,
            'halaman_start' => 2,
            'halaman_end'   => 2,
            'score'         => 'pending',
            'notes'         => 'Setoran halaman 2',
        ]);
    }

    public function test_student_validation_for_invalid_page_range()
    {
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'student@example.com',
        ]);
        $student->assignRole('mahasiswa');

        $response = $this->actingAs($student)->post(route('mahasiswa.hafalan.log.store'), [
            'surah'         => 'Al-Baqarah',
            'ayat_start'    => 1,
            'ayat_end'      => 5,
            'halaman_start' => 5,
            'halaman_end'   => 2, // invalid: halaman_end < halaman_start
            'notes'         => 'Invalid pages',
        ]);

        $response->assertSessionHasErrors(['halaman_end']);
    }

    public function test_mentor_can_grade_submission_with_pages()
    {
        $mentor = User::factory()->create([
            'role' => 'mentor',
            'email' => 'mentor@example.com',
        ]);
        $mentor->assignRole('mentor');

        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'mentor_id' => $mentor->id,
            'email' => 'student@example.com',
        ]);
        $student->assignRole('mahasiswa');

        $log = HafalanLog::create([
            'user_id'       => $student->id,
            'mentor_id'     => $mentor->id,
            'surah'         => 'Al-Baqarah',
            'ayat_start'    => 1,
            'ayat_end'      => 5,
            'halaman_start' => 2,
            'halaman_end'   => 2,
            'score'         => 'pending',
        ]);

        $response = $this->actingAs($mentor)->patch(route('mentor.hafalan.score', $log->id), [
            'score'        => 'memtas',
            'mentor_notes' => 'Sangat lancar, tingkatkan!',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('hafalan_logs', [
            'id'           => $log->id,
            'score'        => 'memtas',
            'mentor_notes' => 'Sangat lancar, tingkatkan!',
            'mentor_id'    => $mentor->id,
        ]);
    }

    public function test_student_dashboard_displays_current_page()
    {
        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'student.dash@example.com',
        ]);
        $student->assignRole('mahasiswa');

        // Create initial hafalan with page bookmark
        \App\Models\Hafalan::create([
            'user_id' => $student->id,
            'current_juz' => 2,
            'current_ayah' => 15,
            'target_juz' => 30,
            'current_surah_nomor' => 2,
            'current_surah_nama' => 'Al-Baqarah',
            'current_ayat' => 142,
            'current_page' => 22,
        ]);

        $response = $this->actingAs($student)->get(route('dashboard'));
        $response->assertStatus(200);

        $response->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->has('stats.hafalan', fn (Assert $h) => $h
                ->where('juz', 2)
                ->where('current_surah', 'Al-Baqarah')
                ->where('current_page', 22)
                ->etc()
            )
        );
    }
}
