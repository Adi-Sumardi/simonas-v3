<?php

namespace App\Http\Controllers;

use App\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class KegiatanController extends Controller
{
    public function index()
    {
        $events= array();
        $warna = null;
        $kegiatan = Kegiatan::all();
        foreach ($kegiatan  as $kegiatan){
            if($kegiatan->penyelenggara == "YAPI")
            {
                  $warna = '#F1B9AC';
            }
            elseif($kegiatan->penyelenggara == "Direktorat Keasramaan")
            {
                $warna = '#F4D291';
            }
            elseif($kegiatan->penyelenggara == "Asrama Sunan Gunung Jati")
            {
                $warna = '#FAEBBD';
            }
            elseif($kegiatan->penyelenggara == "Asrama Sunan Giri")
            {
                $warna = '#BBEDBE';
            }
            $events[]=[
                'id' => $kegiatan->id,
                'title' => $kegiatan->nama_kegiatan,
                'start' => $kegiatan->waktu,
                'end' => date('Y-m-d',strtotime($kegiatan->waktu. '+1 days')),
                'tempat' => $kegiatan->tempat,
                'penyelenggara' => $kegiatan->penyelenggara,
                'jenis_kegiatan' => $kegiatan->jenis_kegiatan,
                'keterangan' => $kegiatan->keterangan,
                'file' => $kegiatan->file,
                'color'=>$warna,
                'tujuan' => $kegiatan->tujuan
            ];
        }
        $kegiatans = Kegiatan::orderBy('waktu', 'desc')->paginate(10);
        return view('super.pages.kegiatan.index', compact('kegiatans','events'));
    }

    public function create()
    {
        return view('super.pages.kegiatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tujuan' => 'required',
            'penyelenggara' => 'required',
            'jenis_kegiatan' => 'required',
            'waktu' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
            'file' => 'required|mimes:jpg,jpeg,png|max:8120',
        ]);
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $tujuan_upload = 'data_file_kegiatan';

            $image = Image::make($file)->encode('jpg', 80);

            $maxWidth = 1024;
            $maxHeight = 1024;
            $image->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $image->encode('jpg', 80);

            Storage::put("public/$tujuan_upload/$nama_file", $image);
        }

        Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'tujuan' => $request->tujuan,
            'penyelenggara' => $request->penyelenggara,
            'jenis_kegiatan' => $request->jenis_kegiatan,
            'waktu' => $request->waktu,
            'tempat' => $request->tempat,
            'keterangan' => $request->keterangan,
            'file' => $nama_file,
        ]);

        return redirect('/super-kegiatan-asrama');
    }

    public function kegiatanAsgj()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->paginate(10);
        return view('kegiatan.asgj', compact('kegiatans'));
    }

    public function kegiatanAsg()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->paginate(10);
        return view('kegiatan.asg', compact('kegiatans'));
    }

    public function kegiatanAws()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->paginate(10);
        return view('kegiatan.aws', compact('kegiatans'));
    }

    public function kegiatanDqf()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Putri')->orderBy('waktu', 'desc')->paginate(10);
        return view('kegiatan.dqf', compact('kegiatans'));
    }

    public function kegiatanAsrama()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Direktorat Keasramaan')->orderBy('waktu', 'desc')->paginate(10);
        return view('kegiatan.asrama', compact('kegiatans'));
    }

    public function kegiatanYapi()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'YAPI')->orderBy('waktu', 'desc')->paginate(10);
        return view('kegiatan.yapi', compact('kegiatans'));
    }

    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('kegiatan.edit', compact('kegiatan'));
    }

    public function detail($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('super.pages.kegiatan.detail', compact('kegiatan'));
    }

    public function update(Request $request, $id)
    {
        Kegiatan::findOrFail($id)->update($request->all());
        return redirect('/kegiatan');
    }

    public function delete($id)
    {
        Kegiatan::destroy($id);
        return back();
    }
}
