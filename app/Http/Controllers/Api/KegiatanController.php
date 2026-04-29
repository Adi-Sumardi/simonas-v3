<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Kegiatan::query()
                ->orderBy('waktu', 'desc');

            // Filter berdasarkan penyelenggara
            if ($request->has('penyelenggara')) {
                $query->where('penyelenggara', $request->penyelenggara);
            }

            // Filter berdasarkan tanggal
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('waktu', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            // Ubah limit pagination menjadi lebih besar
            $kegiatan = $query->paginate(50); // Ubah dari 10 menjadi 50 atau sesuai kebutuhan

            return response()->json([
                'success' => true,
                'data' => $kegiatan->items(),
                'meta' => [
                    'current_page' => $kegiatan->currentPage(),
                    'last_page' => $kegiatan->lastPage(),
                    'total' => $kegiatan->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kegiatan: ' . $e->getMessage()
            ], 500);
        }
    }
} 