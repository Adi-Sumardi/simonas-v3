<?php

namespace App\Http\Controllers;

use App\Ipk;
use App\User;
use Illuminate\Http\Request;

class IpkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'semester' => 'required',
            'tahun' => 'required',
            'ip' => 'required',
            'file' => 'required|mimes:pdf',
        ]);

        Ipk::create([

            $file = $request->file('file'),
            $nama_file = time()."_".$file->getClientOriginalName(),
            $tujuan_upload = 'data_file_khs',
            $file->move($tujuan_upload,$nama_file),

            'file' => $nama_file,
            'user_id' => auth()->id(),
            'semester' => $request->semester,
            'tahun' => $request->tahun,
            'ip' => $request->ip,
        ]);

        return back()->with('success-ip', 'Data Berhasil Dibuat!');
    }

    public function detail($id)
    {
        $ipk = Ipk::findOrFail($id);
        return view('super.pages.ipk.detail', compact('ipk'));
    }

    public function edit($id)
    {
        $ipk = Ipk::findOrFail($id);
        return view('super.pages.ipk.edit', compact('ipk'));
    }

    public function editKhs($id)
    {
        $ipk = Ipk::findOrFail($id);
        return view('super.pages.ipk.edit-khs', compact('ipk'));
    }

    public function update(Request $request, $id)
    {
        $ipk = Ipk::findOrFail($id);
  
            //for update in table
            $ipk->update([
                'semester' => $request->semester,
                'tahun' => $request->tahun,
                'ip' => $request->ip,
            ]);

        return redirect('/super-profile')->with('info-ip', 'Data berhasil di update');
    }

    public function updateKhs(Request $request, $id)
    {
        $ipk = Ipk::findOrFail($id);

        if($request->file != ''){
  
            //code for remove old file
            if($file = $request->file('file')){
                $nama_file = time()."_".$file->getClientOriginalName();
                $tujuan_upload = 'data_file_khs';
                $file->move($tujuan_upload, $nama_file);
            }
  
            //for update in table
            $ipk->update([
                'file' => $nama_file,
            ]);
        }

        return redirect('/super-profile')->with('info-khs', 'Data berhasil di update');
    }
}
