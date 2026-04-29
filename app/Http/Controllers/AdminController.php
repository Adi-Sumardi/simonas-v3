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

class AdminController extends Controller
{

    public function index()
    {
        return view('admin.index');
    }

    public function createKegiatan()
    {
        return view('admin.create_kegiatan');
    }

    public function storeKegiatan(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tujuan' => 'required',
            'penyelenggara' => 'required',
            'jenis_kegiatan' => 'required',
            'waktu' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
            'file' => 'required|file|image|mimes:jpeg,png,jpg|max:500',
        ]);

        // menyimpan data file yang diupload ke variabel $file
		$file = $request->file('file');
 
		$nama_file = time()."_".$file->getClientOriginalName();
 
      	// isi dengan nama folder tempat kemana file diupload
		$tujuan_upload = 'data_file';
		$file->move($tujuan_upload,$nama_file);

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

        return redirect('/admin/kegiatan');
    }
    
    public function viewKegiatan()
    {
        $kegiatans = Kegiatan::all();
        // Kegiatan::select('id', 'nama_kegiatan', 'tujuan', 'penyelenggara', 'jenis_kegiatan', 'waktu', 'tempat', 'keterangan', 'file')
        //             ->orderBy('waktu', 'desc')
        //             ->paginate(10);
        return view('admin.index_kegiatan', compact('kegiatans'));
    }

    public function editKegiatan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('admin.edit_kegiatan', compact('kegiatan'));
    }

    public function detailKegiatan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('admin.detail_kegiatan', compact('kegiatan'));
    }
    
    public function updateKegiatan(Request $request, $id)
    {
        Kegiatan::findOrFail($id)->update($request->all());
        return redirect('/admin/kegiatan');
    }

    public function deleteKegiatan($id)
    {
        Kegiatan::destroy($id);
        return back();
    }

    // public function cetak_pdfKegiatan()
    // {
    //     $kegiatans = Kegiatan::all();

    //     $pdf = PDF::loadView('kegiatan.kegiatan_pdf', compact('kegiatans'));
    //     return $pdf->download('laporan-data-kegiatan.pdf');
    // }

    public function createAsrama()
    {
        return view('admin.create_asrama');
    }

    public function storeAsrama(Request $request)
    {
        $request->validate([
            'nama_asrama' => 'required',
            'alamat_asrama' => 'required',
            'tahun_jabatan' => 'required',
            'direktur' => 'required',
            'ketua' => 'required'
        ]);

        Asrama::create($request->all());
        return redirect('/admin/asrama');
    }
    
    public function viewAsrama()
    {
        $asramas = Asrama::all();
        return view('admin.index_asrama', compact('asramas'));
    }

    public function editAsrama($id)
    {
        $asrama = Asrama::findOrFail($id);
        return view('admin.edit_asrama', compact('asrama'));
    }
    
    public function updateAsrama(Request $request, $id)
    {
        Asrama::findOrFail($id)->update($request->all());
        return redirect('/admin/asrama');
    }

    public function deleteAsrama($id)
    {
        Asrama::destroy($id);
        return back();
    }

    public function createAlumni()
    {
        return view('admin.create_alumni');
    }

    public function storeAlumni(Request $request)
    {
        $request->validate([
            'nama_lengkap',
            'email',
            'no_telp',
            'asrama',
            'angkatan',
            'gelar',
            'alamat_asal',
            'alamat_sekarang',
            'pekerjaan',
        ]);

        Alumni::create($request->all());
        return redirect('/admin/alumni');
    }
    
    public function viewAlumni()
    {
        $alumnis = Alumni::all();
        return view('admin.index_alumni', compact('alumnis'));
    }

    public function editAlumni($id)
    {
        $alumni = Alumni::findOrFail($id);
        return view('admin.edit_alumni', compact('alumni'));
    }

    public function detailAlumni($id)
    {
        $alumni = Alumni::findOrFail($id);
        return view('admin.detail_alumni', compact('alumni'));
    }
    
    public function updateAlumni(Request $request, $id)
    {
        Alumni::findOrFail($id)->update($request->all());
        return redirect('/admin/alumni');
    }

    public function deleteAlumni($id)
    {
        Alumni::destroy($id);
        return back();
    }

    public function viewWarga()
    {
        $users = User::all();
        return view('admin.index_warga', compact('users'));
    }

    public function detailWarga($id)
    {
        $user = User::findOrFail($id);
        return view('admin.detail_warga', compact('user'));
    }

    public function viewAdminProfile()
    {
        Auth::user()->id;
        $akademiks = User::find(auth()->id())->akademiks->count();
        $leaderships = User::find(auth()->id())->leaderships->count();
        $karakters = User::find(auth()->id())->karakters->count();
        $kreatifs = User::find(auth()->id())->kreatifs->count();
        
        return view('admin.index_profile', compact(
            'akademiks',
            'leaderships',
            'karakters',
            'kreatifs'));
    }

    public function profile()
    {
        return view('admin.edit_foto_profile', array('user' => Auth::user()));
    }

    // public function update_avatar(Request $request)
    // {
    // 	// Handle the user upload of avatar
    // 	if ($request->hasFile('avatar')) {
    //         $avatar = $request->file('avatar');
    //         $filename = time() .'.'. $avatar->getClientOriginalExtension();
    //         Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/'. $filename));

    //         $user = Auth::user();
    //         $user->avatar = $filename;
    //         $user->save();
    //     }

    //         return redirect()->back();

    // }

    public function editAdminProfile($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_profile', compact('user'));
    }

    public function updateAdminProfile(Request $request, $id)
    {
        User::findOrFail($id)->update($request->all());
        return redirect('/admin/profile');
    }

    public function indexAkademik()
    {
        $akademiks = Akademik::all();
        return view('admin.akademik_index', compact('akademiks'));
    }

    public function createAkademik()
    {
        return view('admin.akademik_create');
    }

    public function storeAkademik(Request $request)
    {
        $request->validate([
            'nama_warga' => 'required',
            'kegiatan' => 'required',
            'waktu' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
            'dokumentasi',
            'nama_penilai',
            'nilai',
        ]);

        Akademik::create($request->all());
        return redirect('/admin/akademik');
    }
    
    public function viewAkademik()
    {
        $akademiks = Akademik::all();
        return view('admin.akademik_index', compact('akademiks'));
    }

    public function editAkademik($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('admin.akademik_edit', compact('akademik'));
    }

    public function detailAkademik($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('admin.akademik_detail', compact('akademik'));
    }
    
    public function updateAkademik(Request $request, $id)
    {
        Akademik::findOrFail($id)->update($request->all());
        return redirect('/admin/akademik');
    }

    public function deleteAkademik($id)
    {
        Akademik::destroy($id);
        return back();
    }

    // public function cetak_pdfAkademik()
    // {
    //     $akademiks = Akademik::all();

    //     $pdf = PDF::loadView('admin.akademik_pdf', compact('akademiks'));
    //     return $pdf->download('laporan-data-akademik.pdf');
    // }

    public function indexLeadership()
    {
        $leaderships = Leadership::all();
        return view('admin.leadership_index', compact('leaderships'));
    }

    public function createLeadership()
    {
        return view('admin.leadership_create');
    }

    public function storeLeadership(Request $request)
    {
        $request->validate([
            'nama_warga' => 'required',
            'kegiatan' => 'required',
            'waktu' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
            'dokumentasi',
            'nama_penilai',
            'nilai',
        ]);

        Leadership::create($request->all());
        return redirect('/admin/leadership');
    }
    
    public function viewLeadership()
    {
        $leaderships = Leadership::all();
        return view('admin.leadership_index', compact('leaderships'));
    }

    public function editLeadership($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('admin.leadership_edit', compact('leadership'));
    }

    public function detailLeadership($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('admin.leadership_detail', compact('leadership'));
    }
    
    public function updateLeadership(Request $request, $id)
    {
        Leadership::findOrFail($id)->update($request->all());
        return redirect('/admin/leadership');
    }

    public function deleteLeadership($id)
    {
        Leadership::destroy($id);
        return back();
    }

    // public function cetak_pdfLeadership()
    // {
    //     $leaderships = Leadership::all();

    //     $pdf = PDF::loadView('admin.leadership_pdf', compact('leaderships'));
    //     return $pdf->download('laporan-data-leadership.pdf');
    // }

    public function indexKarakter()
    {
        $karakters = Karakter::all();
        return view('admin.karakter_index', compact('karakters'));
    }

    public function createKarakter()
    {
        return view('admin.karakter_create');
    }

    public function storeKarakter(Request $request)
    {
        $request->validate([
            'nama_warga' => 'required',
            'kegiatan' => 'required',
            'waktu' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
            'dokumentasi',
            'nama_penilai',
            'nilai',
        ]);

        Karakter::create($request->all());
        return redirect('/admin/karakter');
    }
    
    public function viewKarakter()
    {
        $karakters = Karakter::all();
        return view('admin.karakter_index', compact('karakters'));
    }

    public function editKarakter($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('admin.karakter_edit', compact('karakter'));
    }

    public function detailKarakter($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('admin.karakter_detail', compact('karakter'));
    }
    
    public function updateKarakter(Request $request, $id)
    {
        Karakter::findOrFail($id)->update($request->all());
        return redirect('/admin/karakter');
    }

    public function deleteKarakter($id)
    {
        Karakter::destroy($id);
        return back();
    }

    // public function cetak_pdfKarakter()
    // {
    //     $karakters = Karakter::all();

    //     $pdf = PDF::loadView('admin.karakter_pdf', compact('karakters'));
    //     return $pdf->download('laporan-data-karakter.pdf');
    // }

    public function indexKreatif()
    {
        $kreatifs = Kreatif::all();
        return view('admin.kreatif_index', compact('kreatifs'));
    }

    public function createKreatif()
    {
        return view('admin.kreatif_create');
    }

    public function storeKreatif(Request $request)
    {
        $request->validate([
            'nama_warga' => 'required',
            'kegiatan' => 'required',
            'waktu' => 'required',
            'tempat' => 'required',
            'keterangan' => 'required',
            'dokumentasi',
            'nama_penilai',
            'nilai',
        ]);

        Kreatif::create($request->all());
        return redirect('/admin/kreatif');
    }
    
    public function viewKreatif()
    {
        $kreatifs = Kreatif::all();
        return view('admin.kreatif_index', compact('kreatifs'));
    }

    public function editKreatif($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('admin.kreatif_edit', compact('kreatif'));
    }

    public function detailKreatif($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('admin.kreatif_detail', compact('kreatif'));
    }
    
    public function updateKreatif(Request $request, $id)
    {
        Kreatif::findOrFail($id)->update($request->all());
        return redirect('/admin/kreatif');
    }

    public function deleteKreatif($id)
    {
        Kreatif::destroy($id);
        return back();
    }

    // public function cetak_pdfKreatif()
    // {
    //     $kreatifs = Kreatif::all();

    //     $pdf = PDF::loadView('admin.kreatif_pdf', compact('kreatifs'));
    //     return $pdf->download('laporan-data-kreatif.pdf');
    // }

    public function viewAkademikPenilaian()
    {
        $akademiks = Akademik::all();
        return view('admin.penilaian_akademik_index', compact('akademiks'));
    }

    public function editAkademikPenilaian($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('admin.penilaian_akademik_edit', compact('akademik'));
    }

    public function detailAkademikPenilaian($id)
    {
        $akademik = Akademik::findOrFail($id);
        return view('admin.penilaian_akademik_detail', compact('akademik'));
    }

    public function updateAkademikPenilaian(Request $request, $id)
    {
        Akademik::findOrFail($id)->update($request->all());
        return redirect('/admin/penilaian/akademik');
    }

    // public function cetak_pdfAkademikPenilaian()
    // {
    //     $akademiks = Akademik::all();

    //     $pdf = PDF::loadView('admin.akademik_penilaian_pdf', compact('akademiks'));
    //     return $pdf->download('laporan-data-penilaian-akademik.pdf');
    // }

    public function viewLeadershipPenilaian()
    {
        $leaderships = Leadership::all();
        return view('admin.penilaian_leadership_index', compact('leaderships'));
    }

    public function editLeadershipPenilaian($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('admin.penilaian_leadership_edit', compact('leadership'));
    }

    public function detailLeadershipPenilaian($id)
    {
        $leadership = Leadership::findOrFail($id);
        return view('admin.penilaian_leadership_detail', compact('leadership'));
    }

    public function updateLeadershipPenilaian(Request $request, $id)
    {
        Leadership::findOrFail($id)->update($request->all());
        return redirect('/admin/penilaian/leadership');
    }

    // public function cetak_pdfLeadershipPenilaian()
    // {
    //     $leaderships = Leadership::all();

    //     $pdf = PDF::loadView('admin.leadership_penilaian_pdf', compact('leaderships'));
    //     return $pdf->download('laporan-data-penilaian-leadership.pdf');
    // }

    public function viewKarakterPenilaian()
    {
        $karakters = Karakter::all();
        return view('admin.penilaian_karakter_index', compact('karakters'));
    }

    public function editKarakterPenilaian($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('admin.penilaian_karakter_edit', compact('karakter'));
    }

    public function detailKarakterPenilaian($id)
    {
        $karakter = Karakter::findOrFail($id);
        return view('admin.penilaian_karakter_detail', compact('karakter'));
    }

    public function updateKarakterPenilaian(Request $request, $id)
    {
        Karakter::findOrFail($id)->update($request->all());
        return redirect('/admin/penilaian/karakter');
    }

    // public function cetak_pdfKarakterPenilaian()
    // {
    //     $karakters = Karakter::all();

    //     $pdf = PDF::loadView('admin.karakter_penilaian_pdf', compact('karakters'));
    //     return $pdf->download('laporan-data-penilaian-karakter.pdf');
    // }

    public function viewKreatifPenilaian()
    {
        $kreatifs = Kreatif::all();
        return view('admin.penilaian_kreatif_index', compact('kreatifs'));
    }

    public function editKreatifPenilaian($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('admin.penilaian_kreatif_edit', compact('kreatif'));
    }

    public function detailKreatifPenilaian($id)
    {
        $kreatif = Kreatif::findOrFail($id);
        return view('admin.penilaian_kreatif_detail', compact('kreatif'));
    }

    public function updateKreatifPenilaian(Request $request, $id)
    {
        Kreatif::findOrFail($id)->update($request->all());
        return redirect('/admin/penilaian/kreatif');
    }

    // public function cetak_pdfKreatifPenilaian()
    // {
    //     $kreatifs = Kreatif::all();

    //     $pdf = pdf::loadView('admin.kreatif_penilaian_pdf', compact('kreatifs'));
    //     return $pdf->download('laporan-data-penilaian-kreatif.pdf');
    // }

    public function indexHistory()
    {
        $akademiks = Akademik::all();

        return view('admin.history_index', compact('akademiks'));
    }

    public function viewAkademikHistory()
    {
        $akademiks = Akademik::all();

        return view('admin.history_akademik', compact('akademiks'));
    }

    public function viewLeadershipHistory()
    {
        $leaderships = Leadership::all();
        
        return view('admin.history_leadership', compact('leaderships'));
    }

    public function viewKarakterHistory()
    {
        $karakters = Karakter::all();
        
        return view('admin.history_karakter', compact('karakters'));
    }
    

    public function viewKreatifHistory()
    {
        $kreatifs = Kreatif::all();
        
        return view('admin.history_kreatif', compact('kreatifs'));
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

        return view('admin.dashboard', compact('user', 
                                                'kegiatan', 
                                                'alumni', 
                                                'asrama',
                                                'akademik',
                                                'leadership',
                                                'karakter',
                                                'kreatif'));
    }
}
