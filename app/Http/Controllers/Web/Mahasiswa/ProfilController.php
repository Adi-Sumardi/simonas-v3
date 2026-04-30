<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load(['akademik', 'leadership', 'karakter', 'kreatif', 'ipk']);

        return Inertia::render('Mahasiswa/Profil', [
            'user' => $user,
        ]);
    }
}
