<?php
namespace Tests\Feature;

use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterAssignsRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_mahasiswa_gets_spatie_role_and_permissions_on_register()
    {
        $this->seed(RolePermissionSeeder::class);
        \App\Models\Asrama::create(['nama_asrama' => 'Asrama Test', 'kapasitas' => 10]);

        $response = $this->post('/register', [
            'name' => 'Test Mahasiswa',
            'no_induk' => '2509999999',
            'asrama' => 'Asrama Test',
            'tgl_masuk' => now()->toDateString(),
            'role' => 'mahasiswa',
            'email' => 'testmhs@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = \App\Models\User::where('email', 'testmhs@example.com')->first();
        $this->assertNotNull($user, 'User was not created');
        $this->assertTrue($user->hasRole('mahasiswa'), 'Spatie role was not assigned on register');
        $this->assertTrue($user->can('log-aktivitas'), 'log-aktivitas permission missing after register');
        $this->assertTrue($user->can('access-mahasiswa'), 'access-mahasiswa permission missing after register');
    }
}
