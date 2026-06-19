<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = User::alumni()
                ->select([
                    'id', 
                    'name', 
                    'email',
                    'asrama',
                    'status_warga',
                    'no_induk',
                    'tgl_masuk',
                    'tgl_keluar',
                    'alamat_sekarang',
                    'pekerjaan',
                    'universitas',
                    'fakultas',
                    'prodi',
                    'angkatan',
                    'tgl_seminar',
                    'tgl_skripsi',
                    'tgl_wisuda',
                    'nik',
                    'alamat',
                    'provinsi',
                    'kota',
                    'kecamatan',
                    'kode_pos',
                    'no_telp',
                    'asal_sekolah',
                    'tgl_lahir',
                    'prestasi',
                    'organisasi',
                    'nama_ayah',
                    'nama_ibu',
                    'avatar'
                ])
                ->orderBy('name');

            // Filter berdasarkan asrama
            if ($request->has('asrama') && $request->asrama !== 'Semua') {
                $asrama = 'Asrama ' . $request->asrama;
                $query->where('asrama', $asrama);
            }

            // Filter berdasarkan pencarian nama
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $alumni = $query->paginate(20);

            // Debug info
            \Log::info('Alumni data retrieved:', [
                'count' => $alumni->count(),
                'sample_data' => $alumni->first()
            ]);

            return response()->json([
                'success' => true,
                'data' => $alumni->items(),
                'meta' => [
                    'current_page' => $alumni->currentPage(),
                    'last_page' => $alumni->lastPage(),
                    'total' => $alumni->total(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in AlumniController@index: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data alumni'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Panggil semua field yang ada di database
            $alumni = User::select([
                'id', 
                'name', 
                'email', 
                'role', 
                'avatar',
                'asrama',
                'status_warga',
                'no_induk',
                'tgl_masuk',
                'tgl_keluar',
                'alamat_sekarang',
                'pekerjaan',
                'universitas',
                'fakultas',
                'prodi',
                'angkatan',
                'tgl_seminar',
                'tgl_skripsi',
                'tgl_wisuda',
                'nik',
                'alamat',
                'provinsi',
                'kota',
                'kecamatan',
                'kode_pos',
                'no_telp',
                'asal_sekolah',
                'tgl_lahir',
                'prestasi',
                'organisasi',
                'nama_ayah',
                'nama_ibu'
            ])->find($id);

            if (!$alumni) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alumni tidak ditemukan'
                ], 404);
            }

            // Debug info
            \Log::info('Alumni data retrieved:', [
                'id' => $id,
                'data' => $alumni->toArray()
            ]);

            return response()->json([
                'success' => true,
                'data' => $alumni
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in AlumniController@show: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail alumni'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Debug info
            \Log::info('Updating alumni profile', [
                'id' => $id,
                'request_data' => $request->all()
            ]);

            // Cari user berdasarkan ID dan role
            $alumni = User::where('id', $id)
                         ->where('role', 'alumni')
                         ->first();

            if (!$alumni) {
                \Log::warning('Alumni not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Alumni tidak ditemukan'
                ], 404);
            }

            $data = $request->except(['_method', 'avatar', 'password']);
            
            // Validasi email unik kecuali untuk user yang sedang update
            if ($request->has('email')) {
                $emailExists = User::where('email', $request->email)
                                 ->where('id', '!=', $id)
                                 ->exists();
                if ($emailExists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email sudah digunakan'
                    ], 422);
                }
            }

            // Handle password update
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '_' . $avatar->getClientOriginalName();
                
                // Delete old avatar
                if ($alumni->avatar) {
                    Storage::delete('public/data_photo/' . $alumni->avatar);
                }
                
                $avatar->storeAs('public/data_photo', $filename);
                $data['avatar'] = $filename;
            }

            \Log::info('Updating alumni with data', ['data' => $data]);
            
            $alumni->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data alumni berhasil diperbarui',
                'data' => $alumni
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating alumni: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStatistics()
    {
        try {
            // Debug: tampilkan query yang dijalankan
            $query = User::where('role', 'alumni');
            \Log::info('SQL Query:', [
                'query' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Hitung total alumni
            $totalAlumni = $query->count();
            \Log::info('Total Alumni Found:', ['count' => $totalAlumni]);

            // Selalu return success true dengan data yang ada
            return response()->json([
                'success' => true,
                'data' => [
                    'total_alumni' => $totalAlumni
                ],
                'message' => 'Data total alumni berhasil diambil'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting alumni statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik alumni: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk mendapatkan detail statistik per asrama
    public function getAsramaStatistics($asrama)
    {
        try {
            $asramaName = 'Asrama ' . $asrama;
            
            // Hitung total alumni di asrama tersebut
            $totalAlumni = User::alumni()
                ->where('asrama', $asramaName)
                ->count();

            // Hitung berdasarkan tahun wisuda
            $alumniPerTahun = User::alumni()
                ->where('asrama', $asramaName)
                ->select(DB::raw('substr(tgl_wisuda, 1, 4) as tahun'), DB::raw('count(*) as total'))
                ->whereNotNull('tgl_wisuda')
                ->groupBy(DB::raw('substr(tgl_wisuda, 1, 4)'))
                ->orderBy('tahun', 'desc')
                ->get();

            // Hitung berdasarkan universitas
            $alumniPerUniversitas = User::alumni()
                ->where('asrama', $asramaName)
                ->select('universitas', DB::raw('count(*) as total'))
                ->whereNotNull('universitas')
                ->groupBy('universitas')
                ->orderBy('total', 'desc')
                ->get();

            // Status pekerjaan
            $statusPekerjaan = User::alumni()
                ->where('asrama', $asramaName)
                ->select(DB::raw('CASE 
                    WHEN pekerjaan IS NULL OR pekerjaan = "" THEN "Belum Bekerja"
                    ELSE "Sudah Bekerja"
                    END as status'), 
                    DB::raw('count(*) as total'))
                ->groupBy(DB::raw('CASE 
                    WHEN pekerjaan IS NULL OR pekerjaan = "" THEN "Belum Bekerja"
                    ELSE "Sudah Bekerja"
                    END'))
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'asrama' => $asramaName,
                    'total_alumni' => $totalAlumni,
                    'per_tahun' => $alumniPerTahun,
                    'per_universitas' => $alumniPerUniversitas,
                    'status_pekerjaan' => $statusPekerjaan,
                    'last_updated' => now()->format('Y-m-d H:i:s'),
                ],
                'message' => "Data statistik alumni $asramaName berhasil diambil"
            ]);

        } catch (\Exception $e) {
            \Log::error("Error getting $asrama statistics: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Gagal mengambil statistik alumni $asrama: " . $e->getMessage()
            ], 500);
        }
    }

    public function getRandomAlumni()
    {
        \Log::info('getRandomAlumni endpoint called');
        
        try {
            $randomAlumni = User::alumni()
                ->select([
                    'id', 
                    'name',
                    'avatar',
                    'universitas',
                    'tgl_masuk',
                    'asrama',
                    'pekerjaan'
                ])
                ->whereNotNull('avatar')
                ->where('avatar', '!=', '')
                ->inRandomOrder()
                ->limit(5)
                ->get();

            \Log::info('Random alumni query result:', [
                'count' => $randomAlumni->count(),
                'data' => $randomAlumni->toArray()
            ]);

            return response()->json([
                'success' => true,
                'data' => $randomAlumni
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getRandomAlumni:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data alumni: ' . $e->getMessage()
            ], 500);
        }
    }
} 