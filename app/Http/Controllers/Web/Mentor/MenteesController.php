<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenteesController extends Controller
{
    public function index(Request $request)
    {
        // TODO: load from User/Mahasiswa model filtered by mentor
        $mentees = [
            'data'         => [],
            'current_page' => 1,
            'last_page'    => 1,
            'per_page'     => 15,
            'total'        => 0,
            'from'         => 0,
            'to'           => 0,
            'links'        => [],
        ];

        return Inertia::render('Mentor/Mentees/Index', compact('mentees'));
    }

    public function show(Request $request, $id)
    {
        // TODO: load mentee detail
        return Inertia::render('Mentor/Mentees/Show', ['mentee_id' => $id]);
    }
}
