<?php

namespace App\Http\Controllers;

use App\Akademik;
use App\Alumni;
use App\Asrama;
use App\Karakter;
use App\Kegiatan;
use App\Kreatif;
use App\Leadership;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorController extends Controller
{
    public function index()
    {
        return view('mentor.index');
    }

    public function dashboard()
    {
        $user = User::all()->count();
        $kegiatan = Kegiatan::all()->count();
        $alumni = Alumni::all()->count();
        $asrama = Asrama::all()->count();
        $akademik = Akademik::all()->count();
        $leadership = Leadership::all()->count();
        $karakter = Karakter::all()->count();
        $kreatif = Kreatif::all()->count();

        return view('mentor.dashboard', compact('user', 
                                                'kegiatan', 
                                                'alumni', 
                                                'asrama',
                                                'akademik',
                                                'leadership',
                                                'karakter',
                                                'kreatif'));
    }

    public function viewKegiatan()
    {
        $kegiatans = Kegiatan::all();
        // Kegiatan::select('id', 'nama_kegiatan', 'tujuan', 'penyelenggara', 'jenis_kegiatan', 'waktu', 'tempat', 'keterangan', 'file')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('mentor.kegiatan.index', compact('kegiatans'));
    }

    public function kegiatanAsgj()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
        return view('mentor.kegiatan.asgj', compact('kegiatans'));
    }

    public function kegiatanAsg()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
        return view('mentor.kegiatan.asg', compact('kegiatans'));
    }

    public function kegiatanAws()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
        return view('mentor.kegiatan.aws', compact('kegiatans'));
    }

    public function kegiatanDqf()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Putri')->orderBy('waktu', 'desc')->get();
        return view('mentor.kegiatan.dqf', compact('kegiatans'));
    }

    public function viewAsrama()
    {
        $asramas = Asrama::all();
        return view('mentor.direktur-ketua.index', compact('asramas'));
    }

    public function asgj()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Sunan Gunung Jati')->orderBy('tahun_jabatan', 'desc')->get();
        return view('mentor.direktur-ketua.asgj', compact('asramas'));
    }

    public function asg()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Sunan Giri')->orderBy('tahun_jabatan', 'desc')->get();
        return view('mentor.direktur-ketua.asg', compact('asramas'));
    }

    public function aws()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Wali Songo')->orderBy('tahun_jabatan', 'desc')->get();
        return view('mentor.direktur-ketua.aws', compact('asramas'));
    }

    public function dqf()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Putri')->orderBy('tahun_jabatan', 'desc')->get();
        return view('mentor.direktur-ketua.dqf', compact('asramas'));
    }

    public function viewWarga()
    {
        $users = User::where('role', 'mahasiswa')->get();
        return view('mentor.warga.index', compact('users'));
    }

    public function wargaAsgj()
    {
        $users = User::where('asrama', 'Asrama Sunan Gunung Jati')->where('role', 'mahasiswa')->get();
        return view('mentor.warga.asgj', compact('users'));
    }

    public function wargaAsg()
    {
        $users = User::where('asrama', 'Asrama Sunan Giri')->where('role', 'mahasiswa')->get();
        return view('mentor.warga.asg', compact('users'));
    }

    public function wargaAws()
    {
        $users = User::where('asrama', 'Asrama Wali Songo')->where('role', 'mahasiswa')->get();
        return view('mentor.warga.aws', compact('users'));
    }

    public function wargaDqf()
    {
        $users = User::where('asrama', 'Asrama Putri')->where('role', 'mahasiswa')->get();
        return view('mentor.warga.dqf', compact('users'));
    }

    public function detailWarga($id)
    {
        $user = User::findOrFail($id);
        $akademiks = User::findOrFail($id)->akademiks->count();
        $leaderships = User::findOrFail($id)->leaderships->count();
        $karakters = User::findOrFail($id)->karakters->count();
        $kreatifs = User::findOrFail($id)->kreatifs->count();
        
        return view('mentor.warga.detail', compact(
            'user',
            'akademiks',
            'leaderships',
            'karakters',
            'kreatifs'));
    }

    public function viewAlumni()
    {
        $users = User::where('role', 'alumni')->orderBy('name', 'asc')->get();
        return view('mentor.alumni.index', compact('users'));
    }

    public function detailAlumni($id)
    {
        $user = User::findOrFail($id);

        return view('mentor.alumni.detail', compact('user'));
    }

    public function profile()
    {
        return view('mentor.profile.edit_foto', array('user' => Auth::user()));
    }

    public function update_avatar(Request $request)
    {
    	// Handle the user upload of avatar
    	// if ($request->hasFile('avatar')) {
        //     $avatar = $request->file('avatar');
        //     $filename = time() .'.'. $avatar->getClientOriginalExtension();
        //     Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/'. $filename));

        //     $user = Auth::user();
        //     $user->avatar = $filename;
        //     $user->save();
        // }

            return redirect()->back();

    }
    
    public function viewMentorProfile()
    {
        $users = User::find(auth()->id())->users;
        $akademiks = User::find(auth()->id())->akademiks->count();
        $leaderships = User::find(auth()->id())->leaderships->count();
        $karakters = User::find(auth()->id())->karakters->count();
        $kreatifs = User::find(auth()->id())->kreatifs->count();
        
        return view('mentor.profile.index', compact(
            'akademiks',
            'leaderships',
            'karakters',
            'kreatifs'));
    }

    public function editMentorProfile($id)
    {
        $user = User::findOrFail($id);
        return view('mentor.profile.edit', compact('user'));
    }

    public function updateMentorProfile(Request $request, $id)
    {
        User::findOrFail($id)->update($request->all());
        return redirect('/mentor/profile');
    }

    public function viewAkademikPenilaian()
    {
        $akademiks = Akademik::all();
        return view('mentor.akademik.penilaian_index', compact('akademiks'));
    }

    public function akademikAsgj()
    {
        $akademiks = Akademik::where('asrama', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
            
        return view('mentor.akademik.nilai_asgj', compact('akademiks'));
    }

    public function akademikAsg()
    {
        $akademiks = Akademik::where('asrama', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
            
        return view('mentor.akademik.nilai_asg', compact('akademiks'));
    }

    public function akademikAws()
    {
        $akademiks = Akademik::where('asrama', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
            
        return view('mentor.akademik.nilai_aws', compact('akademiks'));
    }

    public function akademikDqf()
    {
        $akademiks = Akademik::where('asrama', 'Asrama Darul Quran Fatahillah')->orderBy('waktu', 'desc')->get();
            
        return view('mentor.akademik.nilai_dqf', compact('akademiks'));
    }

    public function editAkademikPenilaian($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('mentor.akademik.penilaian_edit', compact('akademik'));
    }

    public function updateAkademikPenilaian(Request $request, $id)
    {
        Akademik::findOrFail($id)->update($request->all());
        return redirect('/mentor/penilaian/akademik');
    }

    public function detailNilaiAkademik($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('mentor.akademik.penilaian_detail', compact('akademik'));
    }

    public function viewLeadershipPenilaian()
    {
        $leaderships = Leadership::all();
        return view('mentor.leadership.penilaian_index', compact('leaderships'));
    }

    public function leadershipAsgj()
    {
        $leaderships = Leadership::where('asrama', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.leadership.nilai_asgj', compact('leaderships'));
    }

    public function leadershipAsg()
    {
        $leaderships = Leadership::where('asrama', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.leadership.nilai_asg', compact('leaderships'));
    }

    public function leadershipAws()
    {
        $leaderships = Leadership::where('asrama', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.leadership.nilai_aws', compact('leaderships'));
    }

    public function leadershipDqf()
    {
        $leaderships = Leadership::where('asrama', 'Asrama Darul Quran Fatahillah')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.leadership.nilai_dqf', compact('leaderships'));
    }

    public function editLeadershipPenilaian($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('mentor.leadership.penilaian_edit', compact('leadership'));
    }

    public function updateLeadershipPenilaian(Request $request, $id)
    {
        Leadership::findOrFail($id)->update($request->all());
        return redirect('/mentor/penilaian/leadership');
    }

    public function detailNilaiLeadership($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('mentor.leadership.penilaian_detail', compact('leadership'));
    }

    public function viewKarakterPenilaian()
    {
        $karakters = Karakter::all();
        return view('mentor.karakter.penilaian_index', compact('karakters'));
    }

    public function KarakterAsgj()
    {
        $karakters = Karakter::where('asrama', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.karakter.nilai_asgj', compact('karakters'));
    }

    public function KarakterAsg()
    {
        $karakters = Karakter::where('asrama', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.karakter.nilai_asg', compact('karakters'));
    }

    public function KarakterAws()
    {
        $karakters = Karakter::where('asrama', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.karakter.nilai_aws', compact('karakters'));
    }

    public function KarakterDqf()
    {
        $karakters = Karakter::where('asrama', 'Asrama Darul Quran Fatahillah')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.karakter.nilai_dqf', compact('karakters'));
    }

    public function editKarakterPenilaian($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('mentor.karakter.penilaian_edit', compact('karakter'));
    }

    public function updateKarakterPenilaian(Request $request, $id)
    {
        Karakter::findOrFail($id)->update($request->all());
        return redirect('/mentor/penilaian/karakter');
    }

    public function detailNilaiKarakter($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('mentor.karakter.penilaian_detail', compact('karakter'));
    }

    public function viewKreatifPenilaian()
    {
        $kreatifs = Kreatif::all();
        return view('mentor.kreatif.penilaian_index', compact('kreatifs'));
    }

    public function kreatifAsgj()
    {
        $kreatifs = Kreatif::where('asrama', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.kreatif.nilai_asgj', compact('kreatifs'));
    }

    public function kreatifAsg()
    {
        $kreatifs = Kreatif::where('asrama', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.kreatif.nilai_asg', compact('kreatifs'));
    }

    public function kreatifAws()
    {
        $kreatifs = Kreatif::where('asrama', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.kreatif.nilai_aws', compact('kreatifs'));
    }

    public function kreatifDqf()
    {
        $kreatifs = Kreatif::where('asrama', 'Asrama Darul Quran Fatahillah')->orderBy('waktu', 'desc')->get();
        
        return view('mentor.kreatif.nilai_dqf', compact('kreatifs'));
    }

    public function editKreatifPenilaian($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('mentor.kreatif.penilaian_edit', compact('kreatif'));
    }

    public function updateKreatifPenilaian(Request $request, $id)
    {
        Kreatif::findOrFail($id)->update($request->all());
        return redirect('/mentor/penilaian/kreatif');
    }

    public function detailNilaiKreatif($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('mentor.kreatif.penilaian_detail', compact('kreatif'));
    }

    public function indexAkademik()
    {
        $akademiks = User::find(auth()->id())->akademiks;
        // $akademiks = Akademik::all();
        return view('mentor.akademik.index', compact('akademiks'));
    }

    public function createAkademik()
    {
        return view('mentor.akademik.create');
    }

    public function storeAkademik(Request $request)
    {
        $akademik = new Akademik;
        if($request->file('file')){
            $file = $request->file('file');
                $filename = time().'.'.$file->getClientOriginalExtension();
                $request->file->move('storage/', $filename);

                $akademik->file = $filename;
        }
        $akademik->nama_warga = $request->nama_warga;
        $akademik->komponen = $request->komponen;
        $akademik->kegiatan = $request->kegiatan;
        $akademik->waktu = $request->waktu;
        $akademik->tempat = $request->tempat;
        $akademik->keterangan = $request->keterangan;
        $akademik->file = $filename;
        $akademik->nama_penilai = $request->nama_penilai;
        $akademik->nilai = $request->nilai;
        $akademik->user_id = auth()->id();
        $akademik->save();

        return redirect('/mentor/akademik');
    }
    
    public function viewAkademik()
    {
        $akademiks = Akademik::all();
        return view('mentor.akademik.index', compact('akademiks'));
    }

    public function editAkademik($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('mentor.akademik.edit', compact('akademik'));
    }

    public function detailAkademik($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('mentor.akademik.detail', compact('akademik'));
    }
    
    public function updateAkademik(Request $request, $id)
    {
        Akademik::findOrFail($id)->update($request->all());
        return redirect('/mentor/akademik');
    }

    public function deleteAkademik($id)
    {
        Akademik::destroy($id);
        return back();
    }

    // public function cetak_pdfAkademik()
    // {
    //     $kegiatans = Kegiatan::all();

    //     $pdf = PDF::loadView('mentor.akademik.mentor_pdf', compact('kegiatans'));
    //     return $pdf->download('laporan-data-kegiatan.pdf');
    // }

    public function indexLeadership()
    {
        $leaderships = User::find(auth()->id())->leaderships;
        // $leaderships = Leadership::all();
        return view('mentor.leadership.index', compact('leaderships'));
    }

    public function createLeadership()
    {
        return view('mentor.leadership.create');
    }

    public function storeLeadership(Request $request)
    {
        $leadership = new Leadership;
        if($request->file('file')){
            $file = $request->file('file');
                $filename = time().'.'.$file->getClientOriginalExtension();
                $request->file->move('storage/', $filename);

                $leadership->file = $filename;
        }
        $leadership->nama_warga = $request->nama_warga;
        $leadership->komponen = $request->komponen;
        $leadership->kegiatan = $request->kegiatan;
        $leadership->waktu = $request->waktu;
        $leadership->tempat = $request->tempat;
        $leadership->keterangan = $request->keterangan;
        $leadership->file = $filename;
        $leadership->nama_penilai = $request->nama_penilai;
        $leadership->nilai = $request->nilai;
        $leadership->user_id = auth()->id();
        $leadership->save();

        return redirect('/mentor/leadership');
    }
    
    public function viewLeadership()
    {
        $leaderships = User::find(auth()->id())->leaderships;
        // $leaderships = Leadership::all();
        return view('mentor.leadership.index', compact('leaderships'));
    }

    public function editLeadership($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('mentor.leadership.edit', compact('leadership'));
    }

    public function detailLeadership($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('mentor.leadership.detail', compact('leadership'));
    }
    
    public function updateLeadership(Request $request, $id)
    {
        Leadership::findOrFail($id)->update($request->all());
        return redirect('/mentor/leadership');
    }

    public function deleteLeadership($id)
    {
        Leadership::destroy($id);
        return back();
    }

    // public function cetak_pdfLeadership()
    // {
    //     $leaderships = Leadership::all();

    //     $pdf = PDF::loadView('mentor.leadership.mentor_pdf', compact('leaderships'));
    //     return $pdf->download('laporan-data-mahasiswa.pdf');
    // }

    public function indexKarakter()
    {
        $karakters = User::find(auth()->id())->karakters;
        // $karakters = Karakter::all();
        return view('mentor.karakter.index', compact('karakters'));
    }

    public function createKarakter()
    {
        return view('mentor.karakter.create');
    }

    public function storeKarakter(Request $request)
    {
        $karakter = new Karakter;
        if($request->file('file')){
            $file = $request->file('file');
                $filename = time().'.'.$file->getClientOriginalExtension();
                $request->file->move('storage/', $filename);

                $karakter->file = $filename;
        }
        $karakter->nama_warga = $request->nama_warga;
        $karakter->komponen = $request->komponen;
        $karakter->kegiatan = $request->kegiatan;
        $karakter->waktu = $request->waktu;
        $karakter->tempat = $request->tempat;
        $karakter->keterangan = $request->keterangan;
        $karakter->file = $filename;
        $karakter->nama_penilai = $request->nama_penilai;
        $karakter->nilai = $request->nilai;
        $karakter->user_id = auth()->id();
        $karakter->save();

        return redirect('/mentor/karakter');
    }
    
    public function viewKarakter()
    {
        $karakters = User::find(auth()->id())->karakters;
        // $karakters = Karakter::all();
        return view('mentor.karakter.index', compact('karakters'));
    }

    public function editKarakter($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('mentor.karakter.edit', compact('karakter'));
    }

    public function detailKarakter($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('mentor.karakter.detail', compact('karakter'));
    }
    
    public function updateKarakter(Request $request, $id)
    {
        Karakter::findOrFail($id)->update($request->all());
        return redirect('/mentor/karakter');
    }

    public function deleteKarakter($id)
    {
        Karakter::destroy($id);
        return back();
    }

    // public function cetak_pdfKarakter()
    // {
    //     $karakters = Karakter::all();

    //     $pdf = PDF::loadView('mentor.karakter.mahasiswa_pdf', compact('karakters'));
    //     return $pdf->download('laporan-data-mahasiswa.pdf');
    // }

    public function indexKreatif()
    {
        $kreatifs = User::find(auth()->id())->kreatifs;
        // $kreatifs = Kreatif::all();
        return view('mentor.kreatif.index', compact('kreatifs'));
    }

    public function createKreatif()
    {
        return view('mentor.kreatif.create');
    }

    public function storeKreatif(Request $request)
    {
        $kreatif = new Kreatif;
        if($request->file('file')){
            $file = $request->file('file');
                $filename = time().'.'.$file->getClientOriginalExtension();
                $request->file->move('storage/', $filename);

                $kreatif->file = $filename;
        }
        $kreatif->nama_warga = $request->nama_warga;
        $kreatif->komponen = $request->komponen;
        $kreatif->kegiatan = $request->kegiatan;
        $kreatif->waktu = $request->waktu;
        $kreatif->tempat = $request->tempat;
        $kreatif->keterangan = $request->keterangan;
        $kreatif->file = $filename;
        $kreatif->nama_penilai = $request->nama_penilai;
        $kreatif->nilai = $request->nilai;
        $kreatif->user_id = auth()->id();
        $kreatif->save();
        
        return redirect('/mentor/kreatif');
    }
    
    public function viewKreatif()
    {
        $kreatifs = User::find(auth()->id())->kreatifs;
        // $kreatifs = Kreatif::all();
        return view('mentor.kreatif.index', compact('kreatifs'));
    }

    public function editKreatif($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('mentor.kreatif.edit', compact('kreatif'));
    }

    public function detailKreatif($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('mentor.kreatif.detail', compact('kreatif'));
    }
    
    public function updateKreatif(Request $request, $id)
    {
        Kreatif::findOrFail($id)->update($request->all());
        return redirect('/mentor/kreatif');
    }

    public function deleteKreatif($id)
    {
        Kreatif::destroy($id);
        return back();
    }

    // public function cetak_pdfKreatif()
    // {
    //     $kreatifs = Kreatif::all();

    //     $pdf = PDF::loadView('mentor.kreatif.mentor_pdf', compact('kreatifs'));
    //     return $pdf->download('laporan-data-kreatif.pdf');
    // }

    public function indexHistory()
    {
        $akademiks = User::find(auth()->id())->akademiks;
        // $akademiks = 
        // Akademik::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file','nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);

        return view('mentor.history.index', compact('akademiks'));
    }

    public function viewAkademikHistory()
    {
        $akademiks = User::find(auth()->id())->akademiks;
        // $akademiks = Akademik::all();
        // Akademik::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file','nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);

        return view('mentor.history.akademik_index', compact('akademiks'));
    }

    public function viewLeadershipHistory()
    {
        $leaderships = User::find(auth()->id())->leaderships;
        // $leaderships = Leadership::all();
        // Leadership::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file', 'nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('mentor.history.leadership_index', compact('leaderships'));
    }

    public function viewKarakterHistory()
    {
        $karakters = User::find(auth()->id())->karakters;
        // $karakters = Karakter::all();
        // Karakter::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file', 'nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('mentor.history.karakter_index', compact('karakters'));
    }
    

    public function viewKreatifHistory()
    {
        $kreatifs = User::find(auth()->id())->kreatifs;
        // $kreatifs = Kreatif::all();
        // Kreatif::select('id', 'nama_warga', 'komponen', 'kegiatan', 'waktu', 'tempat', 'keterangan', 'file', 'nama_penilai', 'nilai')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('mentor.history.kreatif_index', compact('kreatifs'));
    }
}
