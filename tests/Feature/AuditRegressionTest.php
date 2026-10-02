<?php

namespace Tests\Feature;

use App\Models\HafalanLog;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_hafalan_log_can_be_stored_without_optional_fields(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $student->assignRole('mahasiswa');

        $this->actingAs($student)
            ->post('/mahasiswa/hafalan/log', ['surah' => 'Al-Fatihah', 'ayat_start' => 1, 'ayat_end' => 7])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertSame(1, HafalanLog::where('user_id', $student->id)->count());
    }

    public function test_seeder_rerun_keeps_additional_roles(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $user->syncRoles(['mahasiswa', 'mentor', 'pengurus_asrama']);

        $this->seed(RolePermissionSeeder::class);

        $this->assertEqualsCanonicalizing(['mahasiswa', 'mentor', 'pengurus_asrama'], $user->fresh()->getRoleNames()->all());
    }

    public function test_login_self_heals_missing_spatie_role(): void
    {
        $user = User::factory()->create(['role' => 'mahasiswa']);
        $this->assertFalse($user->hasRole('mahasiswa'));

        event(new Login('web', $user, false));

        $this->assertTrue($user->fresh()->can('log-aktivitas'));
    }

    public function test_error_page_reports_real_status_and_valid_dashboard_link(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $student->assignRole('mahasiswa');

        $this->app['env'] = 'production';
        $this->actingAs($student)->get('/super/warga')
            ->assertStatus(403)
            ->assertInertia(fn ($page) => $page
                ->component('Errors/Error404')
                ->where('status', 403)
                ->where('dashboardUrl', '/dashboard'));
    }
}
