<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Akademik, Leadership, Karakter, Kreatif}; // Gunakan group use
use Illuminate\Support\Facades\{Auth, Validator, DB, Log}; // Gunakan group use
use Exception;

class AktivitasController extends Controller
{
    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'komponen_id' => 'required|exists:komponens,id',
            'komponen' => 'required|string',
            'kegiatan' => 'required|string',
            'waktu' => 'required',
            'tempat' => 'required|string',
            'keterangan' => 'required|string',
            'file' => 'required|file|max:2048|mimes:jpeg,jpg,png,pdf'
        ]);
    }

    private function handleFileUpload($request, $folder)
    {
        try {
            if (!$request->hasFile('file')) {
                throw new Exception('File tidak ditemukan');
            }

            $file = $request->file('file');
            $fileName = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Gunakan storage_path untuk mendapatkan path yang benar
            $path = storage_path("app/public/data_file_{$folder}");
            
            Log::info("Upload path: $path");

            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            if (!$file->move($path, $fileName)) {
                throw new Exception("Gagal menyimpan file");
            }
            
            Log::info("File berhasil disimpan: $path/$fileName");
            return $fileName;

        } catch (Exception $e) {
            Log::error("File upload error: " . $e->getMessage());
            throw $e;
        }
    }

    private function createActivity($model, Request $request, $folder)
    {
        try {
            DB::beginTransaction();

            $validator = $this->validateRequest($request);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $fileName = $this->handleFileUpload($request, $folder);

            $activity = $model::create([
                'user_id' => Auth::id(),
                'komponen_id' => $request->komponen_id,
                'nama_warga' => Auth::user()->name,
                'komponen' => $request->komponen,
                'asrama' => Auth::user()->asrama,
                'kegiatan' => $request->kegiatan,
                'waktu' => $request->waktu,
                'tempat' => $request->tempat,
                'keterangan' => $request->keterangan,
                'file' => $fileName
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $activity
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error creating activity: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // CRUD methods for each model
    public function getAkademik()
    {
        return $this->getActivities(Akademik::class);
    }

    public function storeAkademik(Request $request)
    {
        return $this->createActivity(Akademik::class, $request, 'akademik');
    }

    public function getLeadership()
    {
        return $this->getActivities(Leadership::class);
    }

    public function storeLeadership(Request $request)
    {
        return $this->createActivity(Leadership::class, $request, 'leadership');
    }

    public function getKarakter()
    {
        return $this->getActivities(Karakter::class);
    }

    public function storeKarakter(Request $request)
    {
        return $this->createActivity(Karakter::class, $request, 'karakter');
    }

    public function getKreatif()
    {
        return $this->getActivities(Kreatif::class);
    }

    public function storeKreatif(Request $request)
    {
        return $this->createActivity(Kreatif::class, $request, 'kreatif');
    }

    private function getActivities($model)
    {
        try {
            $activities = $model::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $activities
            ]);
        } catch (Exception $e) {
            Log::error("Error getting activities: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data aktivitas: ' . $e->getMessage()
            ], 500);
        }
    }
}
