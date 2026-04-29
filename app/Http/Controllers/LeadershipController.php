<?php

namespace App\Http\Controllers;

use App\Komponen;
use App\Leadership;
use Illuminate\Http\Request;

class LeadershipController extends Controller
{
    public function index()
    {
        $leaderships = Leadership::orderBy('waktu', 'desc')->paginate(10);
        return view('super.pages.leadership.index', compact('leaderships'));
    }

    public function create()
    {
        $komponens = Komponen::where('aspek', 'Leadership')->get();
        return view('super.pages.leadership.create', compact('komponens'));
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

        Leadership::create([

            $file = $request->file('file'),
            $nama_file = time()."_".$file->getClientOriginalName(),
            $tujuan_upload = 'data_file_leadership',
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

        return redirect('/super-kegiatan-leadership');
    }

    public function detail($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('super.pages.leadership.detail', compact('leadership'));
    }
    
    public function viewLeadership()
    {
        $leaderships = Leadership::orderBy('waktu', 'desc')->get();
        
        return view('leadership.index', compact('leaderships'));
    }

    public function LeadershipAsgj()
    {
        $leaderships = Leadership::where('asrama', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
        
        return view('leadership.penilaian_asgj', compact('leaderships'));
    }

    public function LeadershipAsg()
    {
        $leaderships = Leadership::where('asrama', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
        
        return view('leadership.penilaian_asg', compact('leaderships'));
    }

    public function LeadershipAws()
    {
        $leaderships = Leadership::where('asrama', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
        
        return view('leadership.penilaian_aws', compact('leaderships'));
    }

    public function LeadershipDqf()
    {
        $leaderships = Leadership::where('asrama', 'Asrama Darul Quran Fatahillah')->orderBy('waktu', 'desc')->get();
        
        return view('leadership.penilaian_dqf', compact('leaderships'));
    }

    public function edit($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('leadership.edit', compact('leadership'));
    }

    
    public function update(Request $request, $id)
    {
        Leadership::findOrFail($id)->update($request->all());
        return redirect('/leadership');
    }

    public function delete($id)
    {
        Leadership::destroy($id);
        return back();
    }

    public function download ($file)
    {
        return response()->download('storage/'.$file);
    }

    // public function cetak_pdfLeadership()
    // {
    //     $leaderships = Leadership::all();

    //     $pdf = PDF::loadView('leadership.leadership_pdf', compact('leaderships'));
    //     return $pdf->download('laporan-data-penilaian-leadership.pdf');
    // }

    public function viewLeadershipPenilaian()
    {
        $leaderships = Leadership::all();
        // Leadership::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file', 'nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('leadership.penilaian_index', compact('leaderships'));
    }

    public function editPenilaian($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('leadership.penilaian_edit', compact('leadership'));
    }

    public function updatePenilaian(Request $request, $id)
    {
        Leadership::findOrFail($id)->update($request->all());
        return redirect('/penilaian/leadership');
    }

    public function detailPenilaian($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('leadership.penilaian_detail', compact('leadership'));
    }
}
