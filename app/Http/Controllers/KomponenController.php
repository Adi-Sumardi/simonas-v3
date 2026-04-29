<?php

namespace App\Http\Controllers;

use App\Komponen;
use Illuminate\Http\Request;

class KomponenController extends Controller
{
    public function index()
    {
        $komponens = Komponen::all();
        return view('super.pages.komponen.index', compact('komponens'));
    }

    public function create()
    {
        return view('super.pages.komponen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama_komponen' => 'required',
            'aspek' => 'required',
        ]);

        Komponen::create($request->all());
        return redirect('/super-komponen');
    }

    public function edit($id)
    {
        $komponens = Komponen::findOrFail($id);
        return view('super.pages.komponen.edit', compact('komponens'));
    }
}
