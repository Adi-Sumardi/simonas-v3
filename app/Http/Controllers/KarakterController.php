<?php

namespace App\Http\Controllers;

use App\Karakter;
use App\Komponen;
use Illuminate\Http\Request;

class KarakterController extends Controller
{
    public function index()
    {
        $karakters = Karakter::orderBy('waktu', 'desc')->paginate(10);
        return view('super.pages.karakter.index', compact('karakters'));
    }

    public function create()
    {
        $komponens = Komponen::where('aspek', 'Karakter Islami')
                                ->get();
        return view('super.pages.karakter.create', compact('komponens'));
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

        Karakter::create([

            $file = $request->file('file'),
            $nama_file = time()."_".$file->getClientOriginalName(),
            $tujuan_upload = 'data_file_karakter',
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

        return redirect('/super-kegiatan-karakter');
    }

    public function detail($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('super.pages.karakter.detail', compact('karakter'));
    }
    
    public function viewKarakter()
    {
        $karakters = Karakter::orderBy('waktu', 'desc')->get();
        return view('karakter.index', compact('karakters'));
    }

    public function edit($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('karakter.edit', compact('karakter'));
    }
    
    public function update(Request $request, $id)
    {
        Karakter::findOrFail($id)->update($request->all());
        return redirect('/karakter');
    }

    public function delete($id)
    {
        Karakter::destroy($id);
        return back();
    }

    public function download ($file)
    {
        return response()->download('storage/'.$file);
    }

    // public function cetak_pdfKarakter()
    // {
    //     $karakters = Karakter::all();

    //     $pdf = PDF::loadView('karakter.karakter_pdf', compact('karakters'));
    //     return $pdf->download('laporan-data-penilaian-karakter.pdf');
    // }

    public function viewKarakterPenilaian()
    {
        $karakters = Karakter::all();
        
        return view('karakter.penilaian_index', compact('karakters'));
    }

    public function KarakterAsgj()
    {
        $karakters = Karakter::where('asrama', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
        
        return view('karakter.penilaian_asgj', compact('karakters'));
    }

    public function KarakterAsg()
    {
        $karakters = Karakter::where('asrama', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
        
        return view('karakter.penilaian_asg', compact('karakters'));
    }

    public function KarakterAws()
    {
        $karakters = Karakter::where('asrama', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
        
        return view('karakter.penilaian_aws', compact('karakters'));
    }

    public function KarakterDqf()
    {
        $karakters = Karakter::where('asrama', 'Asrama Darul Quran Fatahillah')->orderBy('waktu', 'desc')->get();
        
        return view('karakter.penilaian_dqf', compact('karakters'));
    }

    public function editPenilaian($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('karakter.penilaian_edit', compact('karakter'));
    }

    public function updatePenilaian(Request $request, $id)
    {
        Karakter::findOrFail($id)->update($request->all());
        return redirect('/penilaian/karakter');
    }

    public function detailPenilaian($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('karakter.penilaian_detail', compact('karakter'));
    }
}
