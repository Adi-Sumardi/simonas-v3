<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Komponen;
use Illuminate\Http\Request;

class KomponenController extends Controller
{
    public function getByAspek($aspek)
    {
        try {
            // Normalize aspek parameter
            $normalizedAspek = str_replace('_', ' ', ucwords($aspek));
            
            $komponen = Komponen::where('aspek', 'LIKE', "%$normalizedAspek%")
                               ->orderBy('kode', 'asc')
                               ->get();

            return response()->json([
                'success' => true,
                'data' => $komponen
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data komponen: ' . $e->getMessage()
            ], 500);
        }
    }
}
