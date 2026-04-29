<?php

namespace App\Http\Controllers;

use App\Alumni;
use App\Asrama;
use App\AsramaOrganisasi;
use App\AsramaPekerjaan;
use App\AsramaPendidikan;
use App\AsramaPrestasi;
use App\Kegiatan;
use App\AlumniOrganisasi;
use App\AlumniPekerjaan;
use App\AlumniPendidikan;
use App\AlumniPrestasi;
use App\User;
use Carbon\Carbon;
use App\Province;
use App\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportAlumni;
use App\Exports\ExportAlumni;
use App\Exports\CustomExport;
use Illuminate\Support\Facades\DB;
use Validator;

class AlumniController extends Controller
{
    public function index()
    {

        $role = auth()->user()->role;
        if($role ==="super"){
            $layouts =  'super.layouts.master';
        }
        elseif($role ==="alumni"){
            $layouts =  'alumni.layouts.master';
        }
        $alumnis = Alumni::all();
        return view('super.pages.alumni.index', ['alumnis'=>$alumnis,'layouts' => $layouts]);
    }

    public function create()
    {
        $asrama = Asrama::orderBy('tahun_jabatan', 'desc')->get();
        $province = Province::all();
        $regency = Regency::all();
        return view('super.pages.alumni.create', compact('asrama','province','regency'));
    }
    public function getDistricts(Request $request)
    {
        $province = $request->input('province');
        $districts = DB::table('regencies')
            ->where('province_id', $province)
            ->distinct()
            ->get();

        return response()->json($districts);
    }

    public function store(Request $request)
    {

        $data = $request->only(array_keys($request->input()));
        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $compressedImage = Image::make($foto)->encode('jpg', 75);
            $namaFile = time() . '.jpg';
            $lokasiTujuan = public_path('images');
            $compressedImage->save($lokasiTujuan . '/' . $namaFile);
            $data['foto'] = $namaFile;
        }    $data['id_asrama'] = $request->input('id_asrama');

        // // echo "<pre/>";
        // // print_r($data);die;

        // $pendidikan = $data['alumni_academic'];
        // $organisasi = $data['alumni_organization'];
        // $job = $data['alumni_job_history'];
        // $achievement = $data['alumni_achievement'];
        $model = new Alumni;
        $model->fill($data);
        $model->save();
        // $datanya = dd($data);
        $alumni_achievement = json_decode($request->input('alumni_achievement'));
        $job = json_decode($request->input('alumni_job_history'));
        $organisasi = json_decode($request->input('alumni_organization'));
        $academicData = json_decode($request->input('alumni_academic'));
        if (!empty($academicData) && is_iterable($academicData)) {
            foreach ($academicData as $academic) {
                $gelar = $academic->gelar;
                $namaKampus = $academic->nama_kampus;
                $fakultasJurusan = $academic->fakultas_jurusan;
                $tahunAjaran = $academic->tahun_ajaran;
            AlumniPendidikan::create([
                    'id_alumni' => $model->id,
                    'gelar' => $gelar,
                    'nama_kampus' => $namaKampus,
                    'fakultas_jurusan' => $fakultasJurusan,
                    'tahun_ajaran' => $tahunAjaran,
                ]);
            }
        }
        if (!empty($organisasi) && is_iterable($organisasi)) {
            foreach ($organisasi as $org) {
                $nama = $org->nama_organisasi;
                $tipe = $org->type_organization;
                $organisasi_jabatan =$org->organisasi_jabatan;
                $tahun_organisasi=$org->tahun_organisasi;
            AlumniOrganisasi::create([
                        'id_alumni' =>$model->id,
                        'tipe' => $tipe,
                        'nama' => $nama,
                        'organisasi_jabatan' => $organisasi_jabatan,
                        'tahun_organisasi' => $tahun_organisasi,
                    ]);

            }
        }
        if (!empty($alumni_achievement) && is_iterable($alumni_achievement)) {
            foreach ($alumni_achievement as $alumni_achievement) {
                $nama_penghargaan = $alumni_achievement->nama_penghargaan;
                AlumniPrestasi::create([
                    'id_alumni' => $model->id,
                    'nama_penghargaan' => $nama_penghargaan,
                ]);
            }
        }
        if (!empty($job) && is_iterable($job)) {
            foreach ($job as $job) {
                $nama_jabatan = $job->nama_jabatan;
                $bidang_pekerjaan = $job->bidang_pekerjaan;
                $tahun_kerjaan =$job->tahun_kerjaan;
                AlumniPekerjaan::create([
                    'id_alumni' => $model->id,
                    'tempat_pekerjaan' => $nama_jabatan,
                    'bidang_pekerjaan' => $bidang_pekerjaan,
                    'tahun_kerjaan' => $tahun_kerjaan,
                ]);
            }
        }
    //     unset($data['alumni_academic']);
    //     unset($data['alumni_organization']);
    //     unset($data['alumni_job_history']);
    //     unset($data['alumni_achievement']);
    //     unset($data['DataTables_Table_0_length']);
    //     unset($data['DataTables_Table_1_length']);
    //     unset($data['DataTables_Table_2_length']);
    //     unset($data['DataTables_Table_3_length']);



    return redirect('/super-alumni-asrama');
    }

    public function detail($id)
    {
        $alumni = Alumni::findOrFail($id);
        $Asramanya = Alumni::find($id)->value('id_asrama');
        $Asrama = $alumni->asrama;
        $AlumniPrestasi = $alumni->prestasi;
        $AlumniPendidikan = $alumni->pendidikan;
        $AlumniOrganisasi = $alumni->organisasi;
        $AlumniPekerjaan = $alumni->pekerjaan;

        return view('super.pages.alumni.detail', compact('alumni','Asrama','AlumniPrestasi','AlumniPendidikan','AlumniOrganisasi','AlumniPekerjaan'));
    }

    public function export()
    {
        return Excel::download(new CustomExport(), 'alumni_data.xlsx');
    }
    public function IndexPekerjaan()
    {
        return view('super.pages.master.pekerjaan');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $namaFile = $file->getClientOriginalName();
        $file->move('DataAlumni', $namaFile);
        Excel::import(new ImportAlumni, public_path('/DataAlumni/'.$namaFile));
        return redirect('/super-alumni-asrama');
    }

    public function indexAlumni()
    {
        $tahunIni = Carbon::now()->year;
        $users = Alumni::with('asrama')->get();
        $datanya = Alumni::all();
        $alumniWithProvince  =Alumni::with('province')->get();;
        $jumlahtotal = Alumni::all()->count();
        $jumlahtotalTahunIni = Alumni::whereYear('created_at', $tahunIni)->count();
        $alumni = Alumni::with('province');
        $AlumniPendidikan= AlumniPendidikan::all();
        // $Asramanya = Alumni::find($id)->value('id_asrama');
        // $Asrama = Asrama::find($Asramanya);
        // $AlumniPrestasi = $alumni->prestasi;
        // $AlumniPendidikan = $alumni->pendidikan;
        // $AlumniOrganisasi = $alumni->organisasi;
        // $AlumniPekerjaan = $alumni->pekerjaan;
        $ASGJ = Alumni::where('id_asrama', '2')->count();
        $ASG = Alumni::where('id_asrama', '3')->count();
        $AWS = Alumni::where('id_asrama', '4')->count();
        $ASPURI = Alumni::where('id_asrama', '1')->count();

        return view('alumni.index', compact('datanya','alumniWithProvince','users','AlumniPendidikan','ASGJ','ASG','AWS','ASPURI','jumlahtotal','jumlahtotalTahunIni'));
    }

    public function viewAlumni()
    {
        $alumnis= Alumni::all();
        $users = User::where('role', 'alumni')->orderBy('name', 'asc')->get();
        return view('alumni.alumni_index', compact('alumnis'));
    }

    public function edit($id)
    {
        $alumni = Alumni::findOrFail($id);
        return view('alumni.edit', compact('alumni'));
    }

    public function alumniDetail($id)
    {
        $user = User::findOrFail($id);
        return view('alumni.alumni_detail', compact('user'));
    }

    public function update(Request $request, $id)
    {
        Alumni::findOrFail($id)->update($request->all());
        return redirect('/alumni');
    }

    public function delete($id)
    {
        Alumni::destroy($id);
        return back();
    }

    public function viewKegiatan()
    {
        $kegiatans = Kegiatan::orderBy('waktu', 'desc')->get();

        return view('alumni.kegiatan_index', compact('kegiatans'));
    }

    public function kegiatanAsgj()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Sunan Gunung Jati')->orderBy('waktu', 'desc')->get();
        return view('alumni.kegiatan_asgj', compact('kegiatans'));
    }

    public function kegiatanAsg()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Sunan Giri')->orderBy('waktu', 'desc')->get();
        return view('alumni.kegiatan_asg', compact('kegiatans'));
    }

    public function kegiatanAws()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Wali Songo')->orderBy('waktu', 'desc')->get();
        return view('alumni.kegiatan_aws', compact('kegiatans'));
    }

    public function kegiatanDqf()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Asrama Putri')->orderBy('waktu', 'desc')->get();
        return view('alumni.kegiatan_dqf', compact('kegiatans'));
    }

    public function kegiatanAsrama()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'Direktorat Keasramaan')->orderBy('waktu', 'desc')->get();
        return view('alumni.kegiatan_asrama', compact('kegiatans'));
    }

    public function kegiatanYapi()
    {
        $kegiatans = Kegiatan::where('penyelenggara', 'YAPI')->orderBy('waktu', 'desc')->get();
        return view('alumni.kegiatan_yapi', compact('kegiatans'));
    }

    public function detailKegiatan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('alumni.kegiatan_detail', compact('kegiatan'));
    }

    public function viewAlumniProfile()
    {
        Auth::user()->id;
        $akademiks = User::find(auth()->id())->akademiks->count();
        $leaderships = User::find(auth()->id())->leaderships->count();
        $karakters = User::find(auth()->id())->karakters->count();
        $kreatifs = User::find(auth()->id())->kreatifs->count();

        return view('alumni.profile_index', compact(
            'akademiks',
            'leaderships',
            'karakters',
            'kreatifs'));
        return view('alumni.profile_index');
    }

    public function editAlumniProfile($id)
    {
        $user = User::findOrFail($id);
        return view('alumni.profile_edit', compact('user'));
    }

    public function updateAlumniProfile(Request $request, $id)
    {
        User::findOrFail($id)->update($request->all());
        return redirect('/alumni/profile/data');
    }

    public function viewAsrama()
    {
        $asramas = Asrama::orderBy('tahun_jabatan', 'desc')->get();
        return view('alumni.asrama_index', compact('asramas'));
    }

    public function asgj()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Sunan Gunung Jati')->orderBy('tahun_jabatan', 'desc')->get();
        return view('alumni.asrama_asgj', compact('asramas'));
    }

    public function asg()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Sunan Giri')->orderBy('tahun_jabatan', 'desc')->get();
        return view('alumni.asrama_asg', compact('asramas'));
    }

    public function aws()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Wali Songo')->orderBy('tahun_jabatan', 'desc')->get();
        return view('alumni.asrama_aws', compact('asramas'));
    }

    public function dqf()
    {
        $asramas = Asrama::where('nama_asrama', 'Asrama Putri')->orderBy('tahun_jabatan', 'desc')->get();
        return view('alumni.asrama_dqf', compact('asramas'));
    }

    public function viewWarga()
    {
        $users = User::where('role','mahasiswa')->orderBy('name','asc')->get();
        return view('alumni.warga_index', compact('users'));
    }

    public function wargaAsgj()
    {
        $users = User::where('asrama', 'Asrama Sunan Gunung Jati')->where('role', 'mahasiswa')->get();
        return view('alumni.warga_asgj', compact('users'));
    }

    public function wargaAsg()
    {
        $users = User::where('asrama', 'Asrama Sunan Giri')->where('role', 'mahasiswa')->get();
        return view('alumni.warga_asg', compact('users'));
    }

    public function wargaAws()
    {
        $users = User::where('asrama', 'Asrama Wali Songo')->where('role', 'mahasiswa')->get();
        return view('alumni.warga_aws', compact('users'));
    }

    public function wargaDqf()
    {
        $users = User::where('asrama', 'Asrama Putri')->where('role', 'mahasiswa')->get();
        return view('alumni.warga_dqf', compact('users'));
    }

    public function detailWarga($id)
    {
        $user = User::findOrFail($id);
        return view('alumni.warga_detail', compact('user'));
    }

    public function profile()
    {
        return view('alumni.foto_edit', array('user' => Auth::user()));
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
}
