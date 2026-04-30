<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AktivitasController extends Controller
{
    public function index(Request $request)
    {
        // TODO: replace with real Aktivitas model query
        $activities = [
            'data' => [],
            'current_page' => 1,
            'last_page' => 1,
            'per_page' => 15,
            'total' => 0,
            'from' => 0,
            'to' => 0,
            'links' => [],
        ];

        return Inertia::render('Mahasiswa/Aktivitas/Index', compact('activities'));
    }

    public function create()
    {
        return Inertia::render('Mahasiswa/Aktivitas/Form');
    }

    public function store(Request $request)
    {
        // TODO: validate and save aktivitas
        $request->validate([
            'study_subject' => 'nullable|string|max:255',
            'study_duration' => 'nullable|numeric|min:0.5|max:10',
            'gpa' => 'nullable|numeric|min:0|max:4',
        ]);

        return redirect()->route('mahasiswa.aktivitas.index')
            ->with('success', 'Aktivitas berhasil disimpan!');
    }
}
