<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanAsramaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Kegiatan::query()
                ->orderBy('waktu', 'desc');

            // Filter berdasarkan penyelenggara jika ada
            if ($request->has('penyelenggara')) {
                $query->where('penyelenggara', $request->penyelenggara);
            }

            // Pagination dengan 50 data per halaman
            $kegiatan = $query->paginate(50);

            // Konversi format waktu
            $kegiatan->getCollection()->transform(function ($item) {
                $item->waktu = date('Y-m-d H:i:s', strtotime($item->waktu));
                return $item;
            });

            return response()->json([
                'success' => true,
                'data' => $kegiatan->items(),
                'meta' => [
                    'current_page' => $kegiatan->currentPage(),
                    'last_page' => $kegiatan->lastPage(),
                    'per_page' => $kegiatan->perPage(),
                    'total' => $kegiatan->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Endpoint untuk mendapatkan daftar penyelenggara
    public function getPenyelenggara()
    {
        try {
            $penyelenggara = Kegiatan::select('penyelenggara')
                ->distinct()
                ->orderBy('penyelenggara')
                ->pluck('penyelenggara');

            return response()->json([
                'success' => true,
                'data' => $penyelenggara
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data penyelenggara: ' . $e->getMessage()
            ], 500);
        }
    }
} 