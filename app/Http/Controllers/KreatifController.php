<?php

namespace App\Http\Controllers;

use App\Karakter;
use App\Komponen;
use App\Kreatif;
use Illuminate\Http\Request;

class KreatifController extends Controller
{
    public function index()
    {
        $kreatifs = Karakter::orderBy('waktu', 'desc')->paginate(10);
        return view('super.pages.kreatif.index', compact('kreatifs'));
    }

    public function create()
    {
        $komponens = Komponen::where('aspek', 'Kreativitas & Kewirausahaan')
                                ->get();
        return view('super.pages.kreatif.create', compact('komponens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'komponen' => 'required',
            'waktu' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
            'file' => 'required',

        ]);

        Kreatif::create([

            $file = $request->file('file'),
            $nama_file = time()."_".$file->getClientOriginalName(),
            $tujuan_upload = 'data_file_kreatif',
            $file->move($tujuan_upload,$nama_file),

            'file' => $nama_file,
            'user_id' => auth()->id(),
            'komponen_id' => $request->komponen_id,
            'komponen' => $request->komponen,
            'nama_warga' => $request->nama_warga,
            'asrama' => $request->asrama,
            'kegiatan' => $request->kegiatan,
            'waktu' => $request->waktu,
            'tempat' => $request->tempat,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/super-kegiatan-kreatif');
    }

    public function detail($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('super.pages.kreatif.detail', compact('kreatif'));
    }
    
    public function viewKreatif()
    {
        $kreatifs = Kreatif::orderBy('waktu', 'desc')->get();
        return view('kreatif.index', compact('kreatifs'));
    }

    public function edit($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('kreatif.edit', compact('kreatif'));
    }
    
    public function update(Request $request, $id)
    {
        Kreatif::findOrFail($id)->update($request->all());
        return redirect('/kreatif');
    }

    public function delete($id)
    {
        Kreatif::destroy($id);
        return back();
    }

    public function download ($file)
    {
        return response()->download('storage/'.$file);
    }

    // public function cetak_pdfKreatif()
    // {
    //     $kreatifs = Kreatif::all();

    //     $pdf = PDF::loadView('kreatif.kreatif_pdf', compact('kreatifs'));
    //     return $pdf->download('laporan-data-penilaian-kreatif.pdf');
    // }

    public function viewKreatifPenilaian()
    {
        $kreatifs = Kreatif::all();
        
        return view('kreatif.penilaian_index', compact('kreatifs'));
    }

    public function KreatifAsgj()
    {
        $kreatifs = Kreatif::where('asrama', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
        
        return view('kreatif.penilaian_asgj', compact('kreatifs'));
    }

    public function KreatifAsg()
    {
        $kreatifs = Kreatif::where('asrama', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
        
        return view('kreatif.penilaian_asg', compact('kreatifs'));
    }

    public function KreatifAws()
    {
        $kreatifs = Kreatif::where('asrama', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
        
        return view('kreatif.penilaian_aws', compact('kreatifs'));
    }

    public function KreatifDqf()
    {
        $kreatifs = Kreatif::where('asrama', 'Asrama Darul Quran Fatahillah')->orderBy('waktu', 'desc')->get();
        
        return view('kreatif.penilaian_dqf', compact('kreatifs'));
    }

    public function editPenilaian($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('kreatif.penilaian_edit', compact('kreatif'));
    }

    public function updatePenilaian(Request $request, $id)
    {
        Kreatif::findOrFail($id)->update($request->all());
        return redirect('/penilaian/kreatif');
    }

    public function detailPenilaian($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('kreatif.penilaian_detail', compact('kreatif'));
    }
}
