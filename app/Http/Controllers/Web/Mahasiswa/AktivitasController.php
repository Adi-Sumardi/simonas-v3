<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Akademik;
use App\Models\Karakter;
use App\Models\Komponen;
use App\Models\Kreatif;
use App\Models\Leadership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AktivitasController extends Controller
{
    // 4 categories — kreativitas & kewirausahaan share the kreatifs table
    private const CATS = [
        'akademik'    => ['model' => Akademik::class,   'label' => 'Akademik'],
        'leadership'  => ['model' => Leadership::class, 'label' => 'Leadership'],
        'karakter'    => ['model' => Karakter::class,   'label' => 'Karakter Islami'],
        'kreativitas' => ['model' => Kreatif::class,    'label' => 'Kreativitas & Kewirausahaan'],
    ];

    private const VALID_CATS = 'required|in:akademik,leadership,karakter,kreativitas';

    public function index(Request $request)
    {
        $user   = Auth::user();
        $search = $request->query('search');
        $tipe   = $request->query('tipe');
        $all    = collect();

        foreach (self::CATS as $cat => $cfg) {
            $query = $cfg['model']::where('user_id', $user->id)->with('komponen');
            
            if ($search) {
                $query->where('kegiatan', 'like', "%{$search}%");
            }
            if ($tipe) {
                $query->where('tipe_kegiatan', $tipe);
            }

            $items = $query->latest()->get()->map(fn($r) => [
                'id'            => $r->id,
                'kategori'      => $cat,
                'kegiatan'      => $r->kegiatan ?? $r->nama_kegiatan ?? '-',
                'komponen'      => optional($r->komponen)->nama_komponen ?? $r->komponen ?? '-',
                'komponen_id'   => $r->komponen_id,
                'tipe_kegiatan' => $r->tipe_kegiatan,
                'image'         => $r->file,
                'waktu'         => $r->waktu,
                'tempat'        => $r->tempat,
                'keterangan'    => $r->keterangan,
                'nilai'         => $r->nilai,
                'created_at'    => $r->created_at?->format('d M Y'),
            ]);
            $all = $all->merge($items);
        }

        $sorted = $all->sortByDesc('waktu')->values();
        
        // Manual collection pagination
        $page    = (int) $request->query('page', 1);
        $perPage = (int) $request->query('perPage', 10);
        $total   = $sorted->count();
        $paged   = $sorted->slice(($page - 1) * $perPage, $perPage)->values();

        // Group komponens by aspek for the form selects
        $komponens = Komponen::orderBy('aspek')->orderBy('nama_komponen')->get()
            ->groupBy('aspek')
            ->map(fn($g) => $g->map(fn($k) => [
                'id'   => $k->id,
                'nama' => $k->nama_komponen,
                'kode' => $k->kode,
            ])->values());

        return Inertia::render('Mahasiswa/Aktivitas/Index', [
            'items'      => $paged,
            'komponens'  => $komponens,
            'categories' => array_keys(self::CATS),
            'pagination' => [
                'current_page' => $page,
                'per_page'     => $perPage,
                'total'        => $total,
                'last_page'    => ceil($total / $perPage),
            ],
            'filters' => [
                'search' => $search,
                'tipe'   => $tipe,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori'      => self::VALID_CATS,
            'kegiatan'      => 'required|string|max:255',
            'komponen_id'   => 'nullable|exists:komponens,id',
            'tipe_kegiatan' => 'nullable|string|in:Prestasi,Unggulan',
            'waktu'         => 'required|date',
            'tempat'        => 'required|string|max:255',
            'keterangan'    => 'nullable|string|max:1000',
            'image'         => 'required|image|max:2048', // 2MB max
        ]);

        $model = self::CATS[$data['kategori']]['model'];

        $filePath = null;
        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('aktivitas', 'public');
        }

        $model::create([
            'user_id'       => Auth::id(),
            'nama_warga'    => Auth::user()->name,
            'asrama'        => Auth::user()->asrama ?? '-',
            'kegiatan'      => $data['kegiatan'],
            'komponen_id'   => $data['komponen_id'] ?? null,
            'tipe_kegiatan' => $data['tipe_kegiatan'] ?? null,
            'waktu'         => $data['waktu'],
            'tempat'        => $data['tempat'],
            'keterangan'    => $data['keterangan'] ?? null,
            'file'          => $filePath,
        ]);

        return back()->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'kategori'      => self::VALID_CATS,
            'kegiatan'      => 'required|string|max:255',
            'komponen_id'   => 'nullable|exists:komponens,id',
            'tipe_kegiatan' => 'nullable|string|in:Prestasi,Unggulan',
            'waktu'         => 'required|date',
            'tempat'        => 'required|string|max:255',
            'keterangan'    => 'nullable|string|max:1000',
            'image'         => 'nullable|image|max:2048',
        ]);

        $model  = self::CATS[$data['kategori']]['model'];
        $record = $model::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $updateData = [
            'kegiatan'      => $data['kegiatan'],
            'komponen_id'   => $data['komponen_id'] ?? null,
            'tipe_kegiatan' => $data['tipe_kegiatan'] ?? null,
            'waktu'         => $data['waktu'],
            'tempat'        => $data['tempat'],
            'keterangan'    => $data['keterangan'] ?? null,
        ];

        if ($request->hasFile('image')) {
            // Delete old file if exists
            if ($record->file && \Storage::disk('public')->exists($record->file)) {
                \Storage::disk('public')->delete($record->file);
            }
            $updateData['file'] = $request->file('image')->store('aktivitas', 'public');
        }

        $record->update($updateData);

        return back()->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id)
    {
        $kat   = $request->validate(['kategori' => self::VALID_CATS])['kategori'];
        $model = self::CATS[$kat]['model'];
        $model::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();

        return back()->with('success', 'Aktivitas dihapus.');
    }
}
