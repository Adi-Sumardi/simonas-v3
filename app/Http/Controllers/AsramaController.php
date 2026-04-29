<?php

namespace App\Http\Controllers;

use App\Asrama;
use Illuminate\Http\Request;

class AsramaController extends Controller
{
    public function index()
    {
        $asramas = Asrama::orderBy('tahun_jabatan', 'desc')->get();
        return view('super.pages.direktur-ketua.index', compact('asramas'));
    }

    public function create()
    {
        return view('super.pages.direktur-ketua.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_asrama' => 'required',
            'tahun_jabatan' => 'required',
            'direktur' => 'required',
            'ketua' => 'required'
        ]);

        Asrama::create($request->all());
        return redirect('/super-direktur-asrama');
    }
    
    public function viewAsrama()
    {
        $asramas = Asrama::orderBy('tahun_jabatan', 'desc')->get();
        return view('asrama.index', compact('asramas'));
    }

    public function asgj()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Sunan Gunung Jati')->orderBy('tahun_jabatan', 'desc')->get();
        return view('asrama.asgj', compact('asramas'));
    }

    public function asg()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Sunan Giri')->orderBy('tahun_jabatan', 'desc')->get();
        return view('asrama.asg', compact('asramas'));
    }

    public function aws()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Wali Songo')->orderBy('tahun_jabatan', 'desc')->get();
        return view('asrama.aws', compact('asramas'));
    }

    public function dqf()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Putri')->orderBy('tahun_jabatan', 'desc')->get();
        return view('asrama.dqf', compact('asramas'));
    }

    public function edit($id)
    {
        $asrama = Asrama::findOrFail($id);
        return view('super.pages.direktur-ketua.edit', compact('asrama'));
    }
    
    public function update(Request $request, $id)
    {
        Asrama::findOrFail($id)->update($request->all());
        return redirect('/super-direktur-asrama');
    }

    public function delete($id)
    {
        Asrama::destroy($id);
        return back();
    }
}
