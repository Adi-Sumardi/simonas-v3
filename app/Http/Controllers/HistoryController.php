<?php

namespace App\Http\Controllers;

use App\Akademik;
use App\Karakter;
use App\Kreatif;
use App\Leadership;
use App\User;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $akademiks = Akademik::all();
        // Akademik::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file','nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);

        return view('history.index', compact('akademiks'));
    }
    
    public function viewAkademikHistory()
    {
        $akademiks = Akademik::all();
        // Akademik::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file','nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);

        return view('history.akademik_index', compact('akademiks'));
    }

    public function viewLeadershipHistory()
    {
        $leaderships = Leadership::all();
        // Leadership::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file', 'nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('history.leadership_index', compact('leaderships'));
    }

    public function viewKarakterHistory()
    {
        $karakters = Karakter::all();
        // Karakter::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file', 'nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('history.karakter_index', compact('karakters'));
    }
    

    public function viewKreatifHistory()
    {
        $kreatifs = Kreatif::all();
        // Kreatif::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file', 'nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('history.kreatif_index', compact('kreatifs'));
    }

    public function akademik_id(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $akademiks = User::findOrFail($id)->akademiks->get();
        return view('history.akademik_id', compact(
            'user',
            'akademiks'));
    }
}
