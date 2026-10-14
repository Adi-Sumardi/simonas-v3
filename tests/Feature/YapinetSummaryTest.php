<?php

namespace Tests\Feature;

use App\Models\Ipk;
use App\Models\Akademik;
use App\Models\Karakter;
use App\Models\Leadership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YapinetSummaryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.yapinet.api_key' => 'kunci-yapinet']);
    }

    private function warga(string $asrama, string $status, array $ips = []): User
    {
        $user = User::factory()->create(['role' => 'mahasiswa', 'asrama' => $asrama, 'status_warga' => $status]);
        foreach ($ips as $i => $ip) {
            Ipk::create(['user_id' => $user->id, 'ip' => $ip, 'tahun' => (string) (2026 - $i), 'semester' => '1', 'file' => 'khs.pdf']);
        }

        return $user;
    }

    public function test_menolak_tanpa_api_key(): void
    {
        $this->getJson('/api/integrations/yapinet/summary')->assertUnauthorized();
    }

    private function catat(User $user, int $poin, string $waktu, string $model = Akademik::class): void
    {
        $model::create([
            'user_id' => $user->id, 'nama_warga' => $user->name, 'asrama' => $user->asrama,
            'kegiatan' => 'Kajian', 'waktu' => $waktu, 'tempat' => 'Asrama', 'poin' => $poin,
        ]);
    }

    public function test_ringkasan_memakai_poin_leaderboard_dan_keaktifan_mengisi(): void
    {
        $this->warga('Putra', 'aktif', ['3,60', '3,40']);
        $rendah = $this->warga('Putra', 'aktif', ['2,50']);
        $putriAktif = $this->warga('Putri', 'aktif', ['3,20']);
        // Status nonaktif tapi tetap mengisi: dihitung aktif mengisi.
        $putriNonaktif = $this->warga('Putri', 'nonaktif');
        User::factory()->create(['role' => 'alumni', 'asrama' => 'Putri', 'name' => 'Alumni Rahasia']);

        $bulanIni = now()->startOfMonth()->addDays(2)->toDateString();
        $this->catat($putriAktif, 100, $bulanIni);
        $this->catat($putriAktif, 30, $bulanIni, Karakter::class);
        $this->catat($putriNonaktif, 15, $bulanIni, Leadership::class);
        $this->catat($rendah, 50, now()->subMonthNoOverflow()->startOfMonth()->toDateString()); // bulan lalu: tidak dihitung

        $response = $this->withToken('kunci-yapinet')->getJson('/api/integrations/yapinet/summary')
            ->assertOk()
            ->assertJsonPath('contract_version', 2)
            ->assertJsonPath('status', 'warning')
            ->assertJsonPath('filters.0.key', 'asrama');
        $json = $response->json();

        // Nama warga hanya di daftar per asrama; alumni tidak ikut.
        $allScope = json_encode(collect($json['sections'])->where('when.asrama', 'all')->values());
        foreach (User::where('role', 'mahasiswa')->pluck('name') as $name) {
            $this->assertStringNotContainsString($name, $allScope);
        }
        $this->assertStringNotContainsString('Alumni Rahasia', $response->getContent());

        $all = collect($json['metrics'])->where('when.asrama', 'all')->keyBy('label');
        $this->assertSame(4, $all['Warga terdaftar']['value']);
        $this->assertEquals(0.5, $all['Aktif mengisi']['value']); // 2 dari 4
        $this->assertSame('ring', $all['Aktif mengisi']['display']);
        $this->assertSame(36, $all['Rata-rata poin']['value']); // (130 + 15 + 0 + 0) / 4
        $this->assertSame(1, $all['Capai target']['value']); // 130 ≥ 120

        $hero = collect($json['hero'])->firstWhere('when.asrama', 'all');
        $this->assertSame(['Aktif mengisi'], $hero['side']);
        $this->assertStringContainsString('**2** aktif mengisi', $hero['story']);

        // Kartu warga Putri: poin bulan ini (Leaderboard), terbanyak dulu; yang belum mengisi ditandai.
        $putri = collect($json['sections'])->where('when.asrama', 'Putri')->firstWhere('type', 'table');
        $this->assertSame('cards', $putri['display']);
        $this->assertSame([$putriAktif->name, $putriNonaktif->name], collect($putri['rows'])->pluck('nama')->all());
        $this->assertSame([130, 15], collect($putri['rows'])->pluck('poin')->all());
        $this->assertSame('ok', $putri['rows'][0]['_emphasis']['poin']);

        $putra = collect(collect($json['sections'])->where('when.asrama', 'Putra')->firstWhere('type', 'table')['rows']);
        $this->assertSame('belum ada', $putra->firstWhere('nama', $rendah->name)['catatan']);
        $this->assertSame('warning', $putra->firstWhere('nama', $rendah->name)['_emphasis']['ipk']);

        $titles = collect($json['attention'])->where('when.asrama', 'Putra')->pluck('title')->implode(' | ');
        $this->assertStringContainsString('2 warga belum mengisi Simonas', $titles);
        $this->assertStringContainsString('1 warga IPK di bawah 2,75', $titles);

        file_put_contents(sys_get_temp_dir().'/yapinet-summary-simonas.json', json_encode($json));
    }
}
