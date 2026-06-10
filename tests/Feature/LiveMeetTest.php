<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LiveMeeting;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveMeetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->seed(RolePermissionSeeder::class);

        // Mock LiveKit config
        config([
            'livekit.host' => 'ws://127.0.0.1:7880',
            'livekit.api_key' => 'test_api_key',
            'livekit.api_secret' => 'test_api_secret_key_which_is_long_enough',
        ]);
    }

    public function test_mentor_can_start_and_stop_live_meet()
    {
        $mentor = User::factory()->create([
            'role' => 'mentor',
            'email' => 'mentor@example.com',
        ]);
        $mentor->assignRole('mentor');

        // 1. Start meeting
        $response = $this->actingAs($mentor)->post(route('mentor.live-meet.start'));
        $response->assertRedirect(route('mentor.live-meet.view'));

        $this->assertDatabaseHas('live_meetings', [
            'mentor_id' => $mentor->id,
            'is_active' => true,
        ]);

        $meeting = LiveMeeting::where('mentor_id', $mentor->id)->first();

        // 2. View meeting page
        $viewResponse = $this->actingAs($mentor)->get(route('mentor.live-meet.view'));
        $viewResponse->assertOk();
        $viewResponse->assertInertia(fn ($page) => $page
            ->component('Mentor/HafalanLiveMeet')
            ->has('token')
            ->has('wsUrl')
            ->where('roomName', $meeting->room_name)
        );

        // 3. Stop meeting
        $stopResponse = $this->actingAs($mentor)->post(route('mentor.live-meet.stop'));
        $stopResponse->assertRedirect(route('mentor.hafalan.pending'));

        $this->assertDatabaseHas('live_meetings', [
            'mentor_id' => $mentor->id,
            'is_active' => false,
        ]);
    }

    public function test_student_can_check_mentor_live_meet_status()
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

        // Check when mentor is offline
        $response1 = $this->actingAs($student)->get(route('mahasiswa.live-meet.status'));
        $response1->assertJson([
            'active' => false,
        ]);

        // Start meeting
        LiveMeeting::create([
            'mentor_id' => $mentor->id,
            'room_name' => 'meet_mentor_' . $mentor->id,
            'is_active' => true,
            'started_at' => now(),
        ]);

        // Check when mentor is online
        $response2 = $this->actingAs($student)->get(route('mahasiswa.live-meet.status'));
        $response2->assertJson([
            'active' => true,
            'mentor_name' => $mentor->name,
        ]);
    }

    public function test_student_can_join_active_live_meet()
    {
        $mentor = User::factory()->create([
            'role' => 'mentor',
            'name' => 'Ust. Ahmad',
            'email' => 'mentor@example.com',
        ]);
        $mentor->assignRole('mentor');

        $student = User::factory()->create([
            'role' => 'mahasiswa',
            'mentor_id' => $mentor->id,
            'email' => 'student@example.com',
        ]);
        $student->assignRole('mahasiswa');

        // Start meeting
        LiveMeeting::create([
            'mentor_id' => $mentor->id,
            'room_name' => 'meet_mentor_' . $mentor->id,
            'is_active' => true,
            'started_at' => now(),
        ]);

        $response = $this->actingAs($student)->get(route('mahasiswa.live-meet.join'));
        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Mahasiswa/HafalanLiveMeet')
            ->has('token')
            ->has('wsUrl')
            ->where('roomName', 'meet_mentor_' . $mentor->id)
            ->where('mentorName', 'Ust. Ahmad')
            ->has('hafalan')
        );
    }

    public function test_mentor_can_start_meeting_multiple_times()
    {
        $mentor = User::factory()->create([
            'role' => 'mentor',
            'email' => 'mentor@example.com',
        ]);
        $mentor->assignRole('mentor');

        // First start
        $this->actingAs($mentor)->post(route('mentor.live-meet.start'));
        // Stop
        $this->actingAs($mentor)->post(route('mentor.live-meet.stop'));

        // Second start - should not throw duplicate key error!
        $response = $this->actingAs($mentor)->post(route('mentor.live-meet.start'));
        $response->assertRedirect(route('mentor.live-meet.view'));

        $this->assertDatabaseHas('live_meetings', [
            'mentor_id' => $mentor->id,
            'is_active' => true,
        ]);
    }
}
