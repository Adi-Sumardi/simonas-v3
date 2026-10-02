<?php

namespace Tests\Feature;

use App\Models\MentorEvaluation;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MentorEvalAndAccountProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function mentorWithMentee(): array
    {
        $mentor = User::factory()->create(['role' => 'mentor']);
        $mentor->assignRole('mentor');
        $mentee = User::factory()->create(['role' => 'mahasiswa', 'mentor_id' => $mentor->id]);
        $mentee->assignRole('mahasiswa');
        return [$mentor, $mentee];
    }

    public function test_mentor_can_save_evaluation_for_own_mentee(): void
    {
        [$mentor, $mentee] = $this->mentorWithMentee();

        $this->actingAs($mentor)
            ->post("/mentor/eval/{$mentee->id}", ['spiritual' => 9, 'community' => 7, 'notes' => 'Bagus'])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('mentor_evaluations', [
            'mentor_id' => $mentor->id, 'mentee_id' => $mentee->id, 'spiritual' => 9, 'community' => 7,
        ]);

        $this->actingAs($mentor)->get("/mentor/mentees/{$mentee->id}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page->has('evaluations', 1)->where('evaluations.0.notes', 'Bagus'));
    }

    public function test_mentor_cannot_evaluate_someone_elses_mentee(): void
    {
        [, $mentee] = $this->mentorWithMentee();
        $other = User::factory()->create(['role' => 'mentor']);
        $other->assignRole('mentor');

        $this->actingAs($other)
            ->post("/mentor/eval/{$mentee->id}", ['spiritual' => 5, 'community' => 5])
            ->assertNotFound();

        $this->assertSame(0, MentorEvaluation::count());
    }

    public function test_evaluation_scores_are_validated(): void
    {
        [$mentor, $mentee] = $this->mentorWithMentee();

        $this->actingAs($mentor)
            ->post("/mentor/eval/{$mentee->id}", ['spiritual' => 11, 'community' => -1])
            ->assertSessionHasErrors(['spiritual', 'community']);
    }

    public function test_admin_can_view_and_update_account_profile_and_password(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => Hash::make('oldpassword')]);
        $admin->assignRole('admin');

        $this->actingAs($admin)->get('/akun/profil')
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Account/Profile')->where('user.roles', ['admin']));

        $this->actingAs($admin)->put('/akun/profil', ['name' => 'Admin Baru', 'no_hp' => '0812', 'bio' => 'Hai'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Admin Baru', $admin->fresh()->name);

        $this->actingAs($admin)->post('/akun/profil/password', [
            'current_password' => 'wrong', 'password' => 'newpassword1', 'password_confirmation' => 'newpassword1',
        ])->assertSessionHasErrors('current_password');

        $this->actingAs($admin)->post('/akun/profil/password', [
            'current_password' => 'oldpassword', 'password' => 'newpassword1', 'password_confirmation' => 'newpassword1',
        ])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('newpassword1', $admin->fresh()->password));
    }
}
