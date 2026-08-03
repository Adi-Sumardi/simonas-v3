<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function provinces()
    {
        return response()->json(Province::orderBy('name')->pluck('name', 'id'));
    }

    public function regencies(Request $request)
    {
        $provinceName = $request->query('province');
        if (!$provinceName) {
            return response()->json([]);
        }

        $province = Province::whereRaw('LOWER(name) = LOWER(?)', [trim($provinceName)])->first();
        if (!$province) {
            return response()->json([]);
        }

        return response()->json(
            Regency::where('province_id', $province->id)->orderBy('name')->pluck('name')
        );
    }
}
