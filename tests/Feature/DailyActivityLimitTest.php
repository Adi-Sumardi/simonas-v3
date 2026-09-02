<?php

namespace Tests\Feature;

use App\Models\Akademik;
use App\Models\AppSetting;
use App\Models\KomponenPenilaianAspek;
use App\Models\KomponenPenilaianJenis;
use App\Models\KomponenPenilaianSubAspek;
use App\Models\Leadership;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DailyActivityLimitTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private KomponenPenilaianSubAspek $subAspekAkademik;
    private KomponenPenilaianJenis $jenisAkademik;
    private KomponenPenilaianSubAspek $subAspekLeadership;
    private KomponenPenilaianJenis $jenisLeadership;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(RolePermissionSeeder::class);

        $this->student = User::factory()->create([
            'role' => 'mahasiswa',
            'email' => 'mahasiswa@example.com',
            'onboarding_completed_at' => now(),
        ]);
        $this->student->assignRole('mahasiswa');

        $aspekAkademik = KomponenPenilaianAspek::create([
            'kode' => 'akademik',
            'nama_aspek' => 'Akademik',
            'urutan' => 1,
        ]);

        $this->subAspekAkademik = KomponenPenilaianSubAspek::create([
            'aspek_id' => $aspekAkademik->id,
            'nama_sub_aspek' => 'IPK & Kuliah',
            'urutan' => 1,
        ]);

        $this->jenisAkademik = KomponenPenilaianJenis::create([
            'sub_aspek_id' => $this->subAspekAkademik->id,
            'nama_kegiatan' => 'Belajar Mandiri',
            'poin_a' => 5,
            'poin_u' => 10,
            'urutan' => 1,
        ]);

        $aspekLeadership = KomponenPenilaianAspek::create([
            'kode' => 'leadership',
            'nama_aspek' => 'Leadership',
            'urutan' => 2,
        ]);

        $this->subAspekLeadership = KomponenPenilaianSubAspek::create([
            'aspek_id' => $aspekLeadership->id,
            'nama_sub_aspek' => 'Organisasi',
            'urutan' => 1,
        ]);

        $this->jenisLeadership = KomponenPenilaianJenis::create([
            'sub_aspek_id' => $this->subAspekLeadership->id,
            'nama_kegiatan' => 'Rapat Pengurus',
            'poin_a' => 5,
            'poin_u' => 10,
            'urutan' => 1,
        ]);
    }

    private function createAkademikEntry(string $kegiatan = 'Belajar'): Akademik
    {
        return Akademik::create([
            'user_id' => $this->student->id,
            'nama_warga' => $this->student->name,
            'asrama' => 'Asrama A',
            'kegiatan' => $kegiatan,
            'sub_aspek_id' => $this->subAspekAkademik->id,
            'jenis_kegiatan_id' => $this->jenisAkademik->id,
            'level_kegiatan' => 'a',
            'poin' => 5,
            'waktu' => now()->toDateString(),
            'tempat' => 'Ruang Belajar',
            'file' => 'test.jpg',
        ]);
    }

    public function test_activity_index_returns_daily_quota_payload(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->createAkademikEntry("Kegiatan $i");
        }

        $response = $this->actingAs($this->student)->get(route('mahasiswa.aktivitas.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Mahasiswa/Aktivitas/Index')
            ->has('dailyQuota')
            ->where('dailyQuota.limit', 10)
            ->where('dailyQuota.counts.akademik', 3)
            ->where('dailyQuota.counts.leadership', 0)
        );
    }

    public function test_student_can_create_activity_within_daily_limit(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti.jpg');

        $response = $this->actingAs($this->student)->post(route('mahasiswa.aktivitas.store'), [
            'kategori' => 'akademik',
            'kegiatan' => 'Diskusi Matkul',
            'sub_aspek_id' => $this->subAspekAkademik->id,
            'jenis_kegiatan_id' => $this->jenisAkademik->id,
            'level_kegiatan' => 'a',
            'waktu' => now()->toDateString(),
            'tempat' => 'Ruang Belajar',
            'keterangan' => 'Belajar bersama',
            'image' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('akademiks', [
            'user_id' => $this->student->id,
            'kegiatan' => 'Diskusi Matkul',
        ]);
    }

    public function test_student_cannot_exceed_daily_limit_of_10_activities(): void
    {
        Storage::fake('public');

        for ($i = 0; $i < 10; $i++) {
            $this->createAkademikEntry("Kegiatan ke-$i");
        }

        $file = UploadedFile::fake()->image('bukti.jpg');

        // Attempt 11th creation today
        $response = $this->actingAs($this->student)->post(route('mahasiswa.aktivitas.store'), [
            'kategori' => 'akademik',
            'kegiatan' => 'Diskusi Matkul Ke-11',
            'sub_aspek_id' => $this->subAspekAkademik->id,
            'jenis_kegiatan_id' => $this->jenisAkademik->id,
            'level_kegiatan' => 'a',
            'waktu' => now()->toDateString(),
            'tempat' => 'Ruang Belajar',
            'keterangan' => 'Belajar bersama',
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['kategori']);
        $this->assertDatabaseMissing('akademiks', [
            'user_id' => $this->student->id,
            'kegiatan' => 'Diskusi Matkul Ke-11',
        ]);
    }

    public function test_daily_limit_is_scoped_per_category(): void
    {
        Storage::fake('public');

        // Fill 10 Akademik
        for ($i = 0; $i < 10; $i++) {
            $this->createAkademikEntry("Akademik $i");
        }

        $file = UploadedFile::fake()->image('bukti.jpg');

        // Student can still log Leadership
        $response = $this->actingAs($this->student)->post(route('mahasiswa.aktivitas.store'), [
            'kategori' => 'leadership',
            'kegiatan' => 'Rapat BEM',
            'sub_aspek_id' => $this->subAspekLeadership->id,
            'jenis_kegiatan_id' => $this->jenisLeadership->id,
            'level_kegiatan' => 'u',
            'waktu' => now()->toDateString(),
            'tempat' => 'Aula',
            'image' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('leaderships', [
            'user_id' => $this->student->id,
            'kegiatan' => 'Rapat BEM',
        ]);
    }

    public function test_editing_activity_does_not_consume_daily_quota(): void
    {
        $entry = $this->createAkademikEntry('Akademik Awal');

        // Fill remaining 9 slots
        for ($i = 1; $i < 10; $i++) {
            $this->createAkademikEntry("Akademik $i");
        }

        // Edit the first entry (even though count is 10)
        $response = $this->actingAs($this->student)->put(route('mahasiswa.aktivitas.update', $entry->id), [
            'kategori' => 'akademik',
            'kegiatan' => 'Akademik Diperbarui',
            'sub_aspek_id' => $this->subAspekAkademik->id,
            'jenis_kegiatan_id' => $this->jenisAkademik->id,
            'level_kegiatan' => 'a',
            'waktu' => now()->toDateString(),
            'tempat' => 'Perpustakaan',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('akademiks', [
            'id' => $entry->id,
            'kegiatan' => 'Akademik Diperbarui',
        ]);
    }

    public function test_custom_daily_limit_configured_in_app_settings(): void
    {
        Storage::fake('public');

        AppSetting::set('max_daily_activity_per_category', 5);

        for ($i = 0; $i < 5; $i++) {
            $this->createAkademikEntry("Kegiatan $i");
        }

        $file = UploadedFile::fake()->image('bukti.jpg');

        // 6th entry should be rejected
        $response = $this->actingAs($this->student)->post(route('mahasiswa.aktivitas.store'), [
            'kategori' => 'akademik',
            'kegiatan' => 'Entry Ke-6',
            'sub_aspek_id' => $this->subAspekAkademik->id,
            'jenis_kegiatan_id' => $this->jenisAkademik->id,
            'level_kegiatan' => 'a',
            'waktu' => now()->toDateString(),
            'tempat' => 'Ruang Belajar',
            'image' => $file,
        ]);

        $response->assertSessionHasErrors(['kategori']);
    }
}
