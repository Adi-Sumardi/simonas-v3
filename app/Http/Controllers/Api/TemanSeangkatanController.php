<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TemanSeangkatanController extends Controller
{
    public function getTemanSeangkatan(Request $request, $userId)
    {
        try {
            // Ambil data user yang sedang dilihat
            $user = User::findOrFail($userId);
            
            if (empty($user->tgl_masuk)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal masuk tidak tersedia',
                    'teman_seangkatan' => []
                ]);
            }

            // Ambil tahun masuk dari tgl_masuk dengan memastikan format yang benar
            try {
                $tahunMasuk = Carbon::createFromFormat('Y-m-d', $user->tgl_masuk)->year;
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format tanggal masuk tidak valid',
                    'teman_seangkatan' => []
                ]);
            }

            // Cari teman seangkatan dengan whereRaw untuk handle format tanggal
            $temanSeangkatan = User::where('id', '!=', $userId)
                ->whereRaw("substr(tgl_masuk, 1, 4) = ?", [(string) $tahunMasuk])
                ->where('asrama', $user->asrama)
                ->select([
                    'id',
                    'name',
                    'avatar',
                    'universitas',
                    'prodi'
                ])
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data teman seangkatan berhasil diambil',
                'teman_seangkatan' => $temanSeangkatan
            ]);

        } catch (\Exception $e) {
            \Log::error('TemanSeangkatan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'teman_seangkatan' => []
            ]);
        }
    }
} 