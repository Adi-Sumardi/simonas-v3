<?php

namespace App\Http\Controllers;

use App\Akademik;
use App\Komponen;
use Illuminate\Http\Request;

class AkademikController extends Controller
{
    public function index()
    {
        $akademiks = Akademik::orderBy('waktu', 'desc')->paginate(10);
        return view('super.pages.akademik.index', compact('akademiks'));
    }

    public function create()
    {
        $komponens = Komponen::where('aspek', 'Akademik')
                                ->get();
        return view('super.pages.akademik.create', compact('komponens'));
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

        Akademik::create([

            $file = $request->file('file'),
            $nama_file = time()."_".$file->getClientOriginalName(),
            $tujuan_upload = 'data_file_akademik',
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

        return redirect('/super-kegiatan-akademik');
    }

    public function detail($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('super.pages.akademik.detail', compact('akademik'));
    }
    
    public function viewAkademik()
    {
        $akademiks = Akademik::orderBy('waktu', 'desc')->get();
        return view('akademik.index', compact('akademiks'));
    }

    public function edit($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('akademik.edit', compact('akademik'));
    }
    
    public function update(Request $request, $id)
    {
        Akademik::findOrFail($id)->update($request->all());
        return redirect('/akademik');
    }

    public function delete($id)
    {
        Akademik::destroy($id);
        return back();
    }

    public function download ($file)
    {
        return response()->download('storage/'.$file);
    }

    public function cetak_pdfAkademik()
    {
        // $akademiks = Akademik::all();

        // $pdf = PDF::loadView('akademik.akademik_pdf', compact('akademiks'));
        // return $pdf->download('laporan-data-penilaian-akademik.pdf');
    }


    public function viewAkademikPenilaian()
    {
        $akademiks = Akademik::all();
            
        return view('akademik.penilaian_index', compact('akademiks'));
    }

    public function AkademikAsgj()
    {
        $akademiks = Akademik::where('asrama', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
            
        return view('akademik.penilaian_asgj', compact('akademiks'));
    }

    public function AkademikAsg()
    {
        $akademiks = Akademik::where('asrama', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
            
        return view('akademik.penilaian_asg', compact('akademiks'));
    }

    public function AkademikAws()
    {
        $akademiks = Akademik::where('asrama', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
            
        return view('akademik.penilaian_aws', compact('akademiks'));
    }

    public function AkademikDqf()
    {
        $akademiks = Akademik::where('asrama', 'Asrama Darul Quran Fatahillah')->orderBy('waktu', 'desc')->get();
            
        return view('akademik.penilaian_dqf', compact('akademiks'));
    }

    public function editPenilaian($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('akademik.penilaian_edit', compact('akademik'));
    }

    public function updatePenilaian(Request $request, $id)
    {
        Akademik::findOrFail($id)->update($request->all());
        return redirect('/penilaian/akademik');
    }

    public function detailPenilaian($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('akademik.penilaian_detail', compact('akademik'));
    }
}
