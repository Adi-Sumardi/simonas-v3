<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Akademik;
use App\Models\Karakter;
use App\Models\Komponen;
use App\Models\Kreatif;
use App\Models\Leadership;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    private const TABLES = [
        'akademik'    => 'akademiks',
        'leadership'  => 'leaderships',
        'karakter'    => 'karakters',
        'kreativitas' => 'kreatifs',
    ];

    private static function tableFor(string $kategori): string
    {
        return self::TABLES[$kategori];
    }

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
                'image'         => $r->file_data ? route('files.show', ['table' => self::tableFor($cat), 'id' => $r->id]) : null,
                'image_name'    => $r->file,
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

    // Hanya 3 tipe ini yang diterima — mimes: memvalidasi isi file asli,
    // bukan cuma ekstensi, jadi file .heic/.mp4 yang di-rename tetap ditolak.
    private const ALLOWED_UPLOAD_RULE = 'file|mimes:jpg,jpeg,png,pdf|max:20480'; // 20MB mentah, sebelum dikompres

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
            'image'         => 'required|' . self::ALLOWED_UPLOAD_RULE,
        ]);

        $model = self::CATS[$data['kategori']]['model'];
        $file  = $this->processUpload($request->file('image'));

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
            'file'          => $file['name'],
            'file_data'     => $this->binaryExpr($file['data']),
            'file_mime'     => $file['mime'],
            'file_size'     => $file['size'],
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
            'image'         => 'nullable|' . self::ALLOWED_UPLOAD_RULE,
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
            // BLOB lama otomatis "hilang" begitu kolomnya ditimpa — tidak ada
            // file fisik yang perlu dibersihkan.
            $file = $this->processUpload($request->file('image'));
            $updateData['file']      = $file['name'];
            $updateData['file_data'] = $this->binaryExpr($file['data']);
            $updateData['file_mime'] = $file['mime'];
            $updateData['file_size'] = $file['size'];
        }

        $record->update($updateData);

        return back()->with('success', 'Aktivitas berhasil diperbarui.');
    }

    /**
     * PDO pgsql mem-bind string biasa sebagai teks UTF-8 — data biner mentah
     * (JPEG/PDF) bukan UTF-8 valid dan bikin insert gagal ("invalid byte
     * sequence"). decode(hex) di sisi Postgres yang menuliskan bytea-nya,
     * bukan parameter binding, jadi aman dari isu encoding ini.
     */
    private function binaryExpr(string $binary): \Illuminate\Database\Query\Expression
    {
        return DB::raw("decode('" . bin2hex($binary) . "', 'hex')");
    }

    /**
     * Kompres gambar (resize max 1600px, re-encode JPEG kualitas 75) sebelum
     * disimpan sebagai BLOB. PDF disimpan apa adanya (tidak ada kompresi yang
     * aman dilakukan tanpa dependensi tambahan seperti Ghostscript).
     *
     * @return array{data:string,mime:string,size:int,name:string}
     */
    private function processUpload(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            $manager = new \Intervention\Image\ImageManager(['driver' => 'gd']);
            $image = $manager->make($file->getRealPath());
            $image->resize(1600, 1600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $encoded = (string) $image->encode('jpg', 75);

            return [
                'data' => $encoded,
                'mime' => 'image/jpeg',
                'size' => strlen($encoded),
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg',
            ];
        }

        $contents = file_get_contents($file->getRealPath());

        return [
            'data' => $contents,
            'mime' => 'application/pdf',
            'size' => strlen($contents),
            'name' => $file->getClientOriginalName(),
        ];
    }

    public function destroy(Request $request, int $id)
    {
        $kat   = $request->validate(['kategori' => self::VALID_CATS])['kategori'];
        $model = self::CATS[$kat]['model'];
        $model::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();

        return back()->with('success', 'Aktivitas dihapus.');
    }
}
