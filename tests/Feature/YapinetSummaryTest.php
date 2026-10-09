<?php

namespace Tests\Feature;

use App\Models\Ipk;
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

    public function test_ringkasan_v11_dengan_daftar_warga_per_asrama(): void
    {
        $this->warga('Putra', 'aktif', ['3,60', '3,40']);
        $rendah = $this->warga('Putra', 'aktif', ['2,50']);
        $putriAktif = $this->warga('Putri', 'aktif', ['3,20']);
        $this->warga('Putri', 'nonaktif');
        User::factory()->create(['role' => 'alumni', 'asrama' => 'Putri', 'name' => 'Alumni Rahasia']);

        $response = $this->withToken('kunci-yapinet')->getJson('/api/integrations/yapinet/summary')
            ->assertOk()
            ->assertJsonPath('contract_version', 2)
            ->assertJsonPath('status', 'warning')
            ->assertJsonPath('filters.0.key', 'asrama');

        $json = $response->json();

        // Nama warga hanya ada di daftar per asrama, tidak di cakupan "semua asrama"; alumni tidak ikut.
        $allScope = json_encode(collect($json['sections'])->where('when.asrama', 'all')->values());
        foreach (User::where('role', 'mahasiswa')->pluck('name') as $name) {
            $this->assertStringNotContainsString($name, $allScope);
        }
        $this->assertStringNotContainsString('Alumni Rahasia', $response->getContent());

        $putri = collect($json['sections'])->where('when.asrama', 'Putri')->firstWhere('type', 'table');
        $this->assertSame('Warga Putri (2)', $putri['title']);
        $this->assertSame(['Aktif', 'Nonaktif'], collect($putri['rows'])->pluck('status')->all());
        $this->assertSame($putriAktif->name, $putri['rows'][0]['nama']);

        $putra = collect(collect($json['sections'])->where('when.asrama', 'Putra')->firstWhere('type', 'table')['rows']);
        $this->assertSame('warning', $putra->firstWhere('nama', $rendah->name)['_emphasis']['ipk']);

        $all = collect($json['metrics'])->where('when.asrama', 'all')->keyBy('label');
        $this->assertSame(3, $all['Warga aktif']['value']);
        $this->assertSame(1, $all['Nonaktif']['value']);
        $this->assertArrayNotHasKey('Cuti', $all->all());

        $low = collect($json['attention'])->where('when.asrama', 'Putra')->pluck('title')->implode(' | ');
        $this->assertStringContainsString('1 warga IPK di bawah 2,75', $low);

        $table = collect($json['sections'])->firstWhere('title', 'Per asrama');
        $this->assertSame(['rendah' => 'warning'], collect($table['rows'])->firstWhere('asrama', 'Putra')['_emphasis']);

        file_put_contents(sys_get_temp_dir().'/yapinet-summary-simonas.json', json_encode($json));
    }
}
