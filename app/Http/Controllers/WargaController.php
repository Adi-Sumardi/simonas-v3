<?php

namespace App\Http\Controllers;

use App\Models\Akademik;
use App\Models\Karakter;
use App\Models\Kreatif;
use App\Models\Leadership;
use App\Models\Komponen;
use App\Models\User;
use App\Models\Ipk;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WargaController extends Controller
{

    public function index()
    {
        $users = User::with('akademiks', 'leaderships', 'karakters', 'kreatifs')->where('role', 'mahasiswa')->get();

        return view('super.pages.warga.index', compact('users'));
    }

    public function getDataByDateFilter(Request $request, $userId)
    {
        $namaasrama = "";
        if ($userId == "all") {
            $users = User::where('role', 'mahasiswa')->pluck('id');
        } elseif ($userId == "super-warga-aspuri") {
            $namaasrama = "Asrama Putri";
            $users = User::where([['asrama', 'Asrama Putri'], ['role', 'mahasiswa']])->pluck('id');
        } elseif ($userId == "super-warga-asg") {
            $namaasrama = "Asrama Sunan Giri";
            $users = User::where([['asrama', 'Asrama Sunan Giri'], ['role', 'mahasiswa']])->pluck('id');
        } elseif ($userId == "super-warga-asgj") {
            $namaasrama = "Asrama Sunan Gunung Jati";
            $users = User::where([['asrama', 'Asrama Sunan Gunung Jati'], ['role', 'mahasiswa']])->pluck('id');
        } elseif ($userId == "super-warga-aws") {
            $namaasrama = "Asrama Wali Songo";
            $users = User::where([['asrama', 'Asrama Wali Songo'], ['role', 'mahasiswa']])->pluck('id');
        } else {
            $user = User::where([['id', $userId], ['role', 'mahasiswa']])->first();
            $users = $user ? [$user->id] : [];
        }

        $dateFilter = $request->input('date_filter');
        $akademiksQuery = Akademik::whereIn('user_id', $users);
        $leadershipsQuery = Leadership::whereIn('user_id', $users);
        $karaktersQuery = Karakter::whereIn('user_id', $users);
        $kreatifsQuery = Kreatif::whereIn('user_id', $users);

        switch ($dateFilter) {
            case 'today':
                $akademiksQuery->whereDate('waktu', Carbon::today());
                $leadershipsQuery->whereDate('waktu', Carbon::today());
                $karaktersQuery->whereDate('waktu', Carbon::today());
                $kreatifsQuery->whereDate('waktu', Carbon::today());
                break;
            case 'yesterday':
                $akademiksQuery->whereDate('waktu', Carbon::yesterday());
                $leadershipsQuery->whereDate('waktu', Carbon::yesterday());
                $karaktersQuery->whereDate('waktu', Carbon::yesterday());
                $kreatifsQuery->whereDate('waktu', Carbon::yesterday());
                break;
            case 'this_week':
                $akademiksQuery->whereBetween('waktu', [Carbon::now()->startOfWeek(), Carbon::now()->endofWeek()]);
                $leadershipsQuery->whereBetween('waktu', [Carbon::now()->startOfWeek(), Carbon::now()->endofWeek()]);
                $karaktersQuery->whereBetween('waktu', [Carbon::now()->startOfWeek(), Carbon::now()->endofWeek()]);
                $kreatifsQuery->whereBetween('waktu', [Carbon::now()->startOfWeek(), Carbon::now()->endofWeek()]);
                break;
            case 'last_week':
                $akademiksQuery->whereBetween('waktu', [Carbon::now()->subWeek(), Carbon::now()]);
                $leadershipsQuery->whereBetween('waktu', [Carbon::now()->subWeek(), Carbon::now()]);
                $karaktersQuery->whereBetween('waktu', [Carbon::now()->subWeek(), Carbon::now()]);
                $kreatifsQuery->whereBetween('waktu', [Carbon::now()->subWeek(), Carbon::now()]);
                break;
            case 'this_month':
                $akademiksQuery->whereMonth('waktu', Carbon::now()->month);
                $leadershipsQuery->whereMonth('waktu', Carbon::now()->month);
                $karaktersQuery->whereMonth('waktu', Carbon::now()->month);
                $kreatifsQuery->whereMonth('waktu', Carbon::now()->month);
                break;
            case 'last_month':
                $akademiksQuery->whereMonth('waktu', Carbon::now()->subMonth()->month);
                $leadershipsQuery->whereMonth('waktu', Carbon::now()->subMonth()->month);
                $karaktersQuery->whereMonth('waktu', Carbon::now()->subMonth()->month);
                $kreatifsQuery->whereMonth('waktu', Carbon::now()->subMonth()->month);
                break;
            case 'this_year':
                $akademiksQuery->whereyear('waktu', Carbon::now()->year);
                $leadershipsQuery->whereyear('waktu', Carbon::now()->year);
                $karaktersQuery->whereyear('waktu', Carbon::now()->year);
                $kreatifsQuery->whereyear('waktu', Carbon::now()->year);
                break;
            case 'last_year':
                $akademiksQuery->whereyear('waktu', Carbon::now()->subYear()->year);
                $leadershipsQuery->whereyear('waktu', Carbon::now()->subYear()->year);
                $karaktersQuery->whereyear('waktu', Carbon::now()->subYear()->year);
                $kreatifsQuery->whereyear('waktu', Carbon::now()->subYear()->year);
                break;

        }
        $userIdsByDate = array_merge(
            $akademiksQuery->pluck('user_id')->toArray(),
            $leadershipsQuery->pluck('user_id')->toArray(),
            $karaktersQuery->pluck('user_id')->toArray(),
            $kreatifsQuery->pluck('user_id')->toArray()
        );
        $userIdsByDate = array_unique($userIdsByDate);
        $usersByDate = User::with(['ipks'])
        ->whereIn('id', $userIdsByDate)
        ->select(
            'id',
            'asrama',
            'status_warga',
            'universitas',
            'fakultas',
            'name',
            DB::raw('(COALESCE((SELECT SUM(nl.nilai) FROM akademiks nl WHERE nl.user_id = users.id), 0) + COALESCE((SELECT COUNT(*) FROM leaderships nl WHERE nl.user_id = users.id), 0) + COALESCE((SELECT COUNT(*) FROM karakters nl WHERE nl.user_id = users.id), 0) + COALESCE((SELECT COUNT(*) FROM kreatifs nl WHERE nl.user_id = users.id), 0)) AS total_kegiatan'),
            DB::raw('(SELECT semester FROM ipks WHERE user_id = users.id ORDER BY created_at DESC LIMIT 1) AS semester'),
            DB::raw('(SELECT ip FROM ipks WHERE user_id = users.id ORDER BY created_at DESC LIMIT 1) AS ipk')
        )
        ->get();

            $akademiks = $akademiksQuery->get();
            $leaderships = $leadershipsQuery->get();
            $karakters = $karaktersQuery->get();
            $kreatifs = $kreatifsQuery->get();
            $data = [
                'akademiks'=>$akademiks,
                'kreatifs'=>$kreatifs,
                'karakters'=>$karakters,
                'leaderships'=>$leaderships,
                'usernya'=>$usersByDate,
            ];
            return response()->json($data);
    }

    public function indexMentor()
    {
        $users = User::where('role', 'mentor')->paginate(10);
        return view('super.pages.mentor.index', compact('users'));
    }

    public function asgj()
    {
        $users = User::where('asrama', 'Asrama Sunan Gunung Jati')->where('role', 'mahasiswa')->get();
        foreach ($users as $user) {
            $averageIP = $user->ipks
                ->filter(function ($ipk) {
                    return is_numeric($ipk->ip);
                })
                ->avg('ip');

            // Menambahkan properti baru ke objek $user
            $user->average_ip = $averageIP;
        }
        return view('super.pages.warga.asgj', compact('users'));
    }

    public function asg()
    {
        $users = User::where('asrama', 'Asrama Sunan Giri')->where('role', 'mahasiswa')->get();
        foreach ($users as $user) {
            $averageIP = $user->ipks
                ->filter(function ($ipk) {
                    return is_numeric($ipk->ip);
                })
                ->avg('ip');

            // Menambahkan properti baru ke objek $user
            $user->average_ip = $averageIP;
        }
        return view('super.pages.warga.asg', compact('users'));
    }

    public function aws()
    {
        $users = User::where('asrama', 'Asrama Wali Songo')->where('role', 'mahasiswa')->get();
        foreach ($users as $user) {
            $averageIP = $user->ipks
                ->filter(function ($ipk) {
                    return is_numeric($ipk->ip);
                })
                ->avg('ip');

            // Menambahkan properti baru ke objek $user
            $user->average_ip = $averageIP;
        }
        return view('super.pages.warga.aws', compact('users'));
    }

    public function aspuri()
    {
        $users = User::where('asrama', 'Asrama Putri')->where('role', 'mahasiswa')->get();
        foreach ($users as $user) {
            $averageIP = $user->ipks
                ->filter(function ($ipk) {
                    return is_numeric($ipk->ip);
                })
                ->avg('ip');

            // Menambahkan properti baru ke objek $user
            $user->average_ip = $averageIP;
        }
        return view('super.pages.warga.dqf', compact('users'));
    }

    public function detail(Request $request ,$id)
    {
        $user = User::findOrFail($id);

        $data_akademiks = User::findOrFail($id)->akademiks;
        $data_leaderships = User::findOrFail($id)->leaderships;
        $data_karakters = User::findOrFail($id)->karakters;
        $data_kreatifs = User::findOrFail($id)->kreatifs;


        $date = $request->date_filter;
        $userId = $id;
        $akademiksQuery = Akademik::where('user_id', $userId);
        $leadershipsQuery = Leadership::where('user_id', $userId);
        $karaktersQuery = Karakter::where('user_id', $userId);
        $kreatifsQuery = Kreatif::where('user_id', $userId);


        switch($date){
            case 'today':
                $akademiksQuery->whereDate('waktu', Carbon::today());
                $leadershipsQuery->whereDate('waktu', Carbon::today());
                $karaktersQuery->whereDate('waktu', Carbon::today());
                $kreatifsQuery->whereDate('waktu', Carbon::today());
                break;
            case 'yesterday':
                $akademiksQuery->whereDate('waktu', Carbon::yesterday());
                $leadershipsQuery->whereDate('waktu', Carbon::yesterday());
                $karaktersQuery->whereDate('waktu', Carbon::yesterday());
                $kreatifsQuery->whereDate('waktu', Carbon::yesterday());
                break;
            case 'this_week':
                $akademiksQuery->whereBetween('waktu', [Carbon::now()->startOfWeek(), Carbon::now()->endofWeek()]);
                $leadershipsQuery->whereBetween('waktu', [Carbon::now()->startOfWeek(), Carbon::now()->endofWeek()]);
                $karaktersQuery->whereBetween('waktu', [Carbon::now()->startOfWeek(), Carbon::now()->endofWeek()]);
                $kreatifsQuery->whereBetween('waktu', [Carbon::now()->startOfWeek(), Carbon::now()->endofWeek()]);
                break;
            case 'last_week':
                $akademiksQuery->whereBetween('waktu', [Carbon::now()->subWeek(), Carbon::now()]);
                $leadershipsQuery->whereBetween('waktu', [Carbon::now()->subWeek(), Carbon::now()]);
                $karaktersQuery->whereBetween('waktu', [Carbon::now()->subWeek(), Carbon::now()]);
                $kreatifsQuery->whereBetween('waktu', [Carbon::now()->subWeek(), Carbon::now()]);
                break;
            case 'this_month':
                $akademiksQuery->whereMonth('waktu', Carbon::now()->month);
                $leadershipsQuery->whereMonth('waktu', Carbon::now()->month);
                $karaktersQuery->whereMonth('waktu', Carbon::now()->month);
                $kreatifsQuery->whereMonth('waktu', Carbon::now()->month);
                break;
            case 'last_month':
                $akademiksQuery->whereMonth('waktu', Carbon::now()->subMonth()->month);
                $leadershipsQuery->whereMonth('waktu', Carbon::now()->subMonth()->month);
                $karaktersQuery->whereMonth('waktu', Carbon::now()->subMonth()->month);
                $kreatifsQuery->whereMonth('waktu', Carbon::now()->subMonth()->month);
                break;
            case 'this_year':
                $akademiksQuery->whereyear('waktu', Carbon::now()->year);
                $leadershipsQuery->whereyear('waktu', Carbon::now()->year);
                $karaktersQuery->whereyear('waktu', Carbon::now()->year);
                $kreatifsQuery->whereyear('waktu', Carbon::now()->year);
                break;
            case 'last_year':
                $akademiksQuery->whereyear('waktu', Carbon::now()->subYear()->year);
                $leadershipsQuery->whereyear('waktu', Carbon::now()->subYear()->year);
                $karaktersQuery->whereyear('waktu', Carbon::now()->subYear()->year);
                $kreatifsQuery->whereyear('waktu', Carbon::now()->subYear()->year);
                break;
        }

        $ipks = User::findOrFail($id)->ipks;
        $data_ipks = User::findOrFail($id)->ipks->avg('ip');
        $akademiks = $akademiksQuery->get();
        $leaderships = $leadershipsQuery->get();
        $karakters = $karaktersQuery->get();
        $kreatifs = $kreatifsQuery->get();
        $komponen = Komponen::all();

        return view('super.pages.warga.detail')
        ->with('id', $id)
        ->with('user', $user)
        ->with('komponen', $komponen)
        ->with('ipks', $ipks)
        ->with('data_ipks', $data_ipks)
        ->with('akademiks', $akademiks)
        ->with('leaderships', $leaderships)
        ->with('karakters', $karakters)
        ->with('kreatifs', $kreatifs);
    }

    public function detailMentor($id)
    {
        $user = User::findOrFail($id);
        $akademiks = User::findOrFail($id)->akademiks->count();
        $leaderships = User::findOrFail($id)->leaderships->count();
        $karakters = User::findOrFail($id)->karakters->count();
        $kreatifs = User::findOrFail($id)->kreatifs->count();

        $data_akademiks = User::findOrFail($id)->akademiks;
        $data_leaderships = User::findOrFail($id)->leaderships;
        $data_karakters = User::findOrFail($id)->karakters;
        $data_kreatifs = User::findOrFail($id)->kreatifs;

        $kom1_akademiks = User::findOrFail($id)->akademiks->where('komponen', 'Mendapatkan nilai (prestasi) akademik');
        $kom2_akademiks = User::findOrFail($id)->akademiks->where('komponen', 'Mengikuti kegiatan mentoring');
        $kom3_akademiks = User::findOrFail($id)->akademiks->where('komponen', 'Mengikuti forum akademik');
        $kom4_akademiks = User::findOrFail($id)->akademiks->where('komponen', 'Membaca buku atau artikel dll');
        $kom5_akademiks = User::findOrFail($id)->akademiks->where('komponen', 'Memanfaatkan TIK untuk pengembangan diri');
        $kom6_akademiks = User::findOrFail($id)->akademiks->where('komponen', 'Menulis makalah, artikel dll');
        $kom7_akademiks = User::findOrFail($id)->akademiks->where('komponen', 'Menyampaikan gagasan, presentasi, moderator');
        $kom8_akademiks = User::findOrFail($id)->akademiks->where('komponen', 'Memberikan kontribusi (mengajar, melatih,membimbing)');

        $kom1_akademiks_count = User::findOrFail($id)->akademiks->where('komponen', 'Mendapatkan nilai (prestasi) akademik')->count();
        $kom2_akademiks_count = User::findOrFail($id)->akademiks->where('komponen', 'Mengikuti kegiatan mentoring')->count();
        $kom3_akademiks_count = User::findOrFail($id)->akademiks->where('komponen', 'Mengikuti forum akademik')->count();
        $kom4_akademiks_count = User::findOrFail($id)->akademiks->where('komponen', 'Membaca buku atau artikel dll')->count();
        $kom5_akademiks_count = User::findOrFail($id)->akademiks->where('komponen', 'Memanfaatkan TIK untuk pengembangan diri')->count();
        $kom6_akademiks_count = User::findOrFail($id)->akademiks->where('komponen', 'Menulis makalah, artikel dll')->count();
        $kom7_akademiks_count = User::findOrFail($id)->akademiks->where('komponen', 'Menyampaikan gagasan, presentasi, moderator')->count();
        $kom8_akademiks_count = User::findOrFail($id)->akademiks->where('komponen', 'Memberikan kontribusi (mengajar, melatih,membimbing)')->count();

        $kom1_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Mengikuti pelatihan kepemimpinan');
        $kom2_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Mengikuti kegiatan mentoring');
        $kom3_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Melaksanakan tugas kepanitiaan (mandat)');
        $kom4_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Melakukan tugas sebagai pengurus organisasi');
        $kom5_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Menjadi peserta atau memimpin rapat');
        $kom6_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Mengikuti diskusi atau debat penyelesaian masalah');
        $kom7_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Menulis surat, proposal kegiatan, laporan dll');
        $kom8_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Memberikan kontribusi baik harta, tenaga, waktu');
        $kom9_leaderships = User::findOrFail($id)->leaderships->where('komponen', 'Menyampaikan gagasan baik lisan atau tulisan');

        $kom1_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Mengikuti pelatihan kepemimpinan')->count();
        $kom2_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Mengikuti kegiatan mentoring')->count();
        $kom3_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Melaksanakan tugas kepanitiaan (mandat)')->count();
        $kom4_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Melakukan tugas sebagai pengurus organisasi')->count();
        $kom5_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Menjadi peserta atau memimpin rapat')->count();
        $kom6_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Mengikuti diskusi atau debat penyelesaian masalah')->count();
        $kom7_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Menulis surat, proposal kegiatan, laporan dll')->count();
        $kom8_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Memberikan kontribusi baik harta, tenaga, waktu')->count();
        $kom9_leaderships_count = User::findOrFail($id)->leaderships->where('komponen', 'Menyampaikan gagasan baik lisan atau tulisan')->count();

        $kom1_karakters = User::findOrFail($id)->karakters->where('komponen', 'Membaca Al Quran, hafalan, hadits pilihan');
        $kom2_karakters = User::findOrFail($id)->karakters->where('komponen', 'Mengikuti kegiatan mentoring');
        $kom3_karakters = User::findOrFail($id)->karakters->where('komponen', 'Mengikuti kajian, membaca buku atau ceramah agama');
        $kom4_karakters = User::findOrFail($id)->karakters->where('komponen', 'Menjadi imam shalat jamaah atau memimpin doa');
        $kom5_karakters = User::findOrFail($id)->karakters->where('komponen', 'Mengamalkan ibadah harian; shalat, puasa, zakat, dll');
        $kom6_karakters = User::findOrFail($id)->karakters->where('komponen', 'Menyampaikan dakwah, kultum, baik lisan, tulisan');
        $kom7_karakters = User::findOrFail($id)->karakters->where('komponen', 'Memelihara kebersihan (kamar, lingkungan, dll)');
        $kom8_karakters = User::findOrFail($id)->karakters->where('komponen', 'Mengajar pengajian, TPA, TPQ, dll');
        $kom9_karakters = User::findOrFail($id)->karakters->where('komponen', 'Memelihara silaturahmi dan menolong sesama');

        $kom1_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Membaca Al Quran, hafalan, hadits pilihan')->count();
        $kom2_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Mengikuti kegiatan mentoring')->count();
        $kom3_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Mengikuti kajian, membaca buku atau ceramah agama')->count();
        $kom4_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Menjadi imam shalat jamaah atau memimpin doa')->count();
        $kom5_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Mengamalkan ibadah harian; shalat, puasa, zakat, dll')->count();
        $kom6_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Menyampaikan dakwah, kultum, baik lisan, tulisan')->count();
        $kom7_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Memelihara kebersihan (kamar, lingkungan, dll)')->count();
        $kom8_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Mengajar pengajian, TPA, TPQ, dll')->count();
        $kom9_karakters_count = User::findOrFail($id)->karakters->where('komponen', 'Memelihara silaturahmi dan menolong sesama')->count();

        $kom1_kreatifs = User::findOrFail($id)->kreatifs->where('komponen', 'Mengikuti pelatihan kreativitas dan kewirausahaan');
        $kom2_kreatifs = User::findOrFail($id)->kreatifs->where('komponen', 'Mengikuti kegiatan mentoring');
        $kom3_kreatifs = User::findOrFail($id)->kreatifs->where('komponen', 'Membaca buku, majalah, internet dll terkait kewirausahaan');
        $kom4_kreatifs = User::findOrFail($id)->kreatifs->where('komponen', 'Mengikuti forum ceramah atau diskusi kewirausahaan');
        $kom5_kreatifs = User::findOrFail($id)->kreatifs->where('komponen', 'Melakukan tugas dalam kegiatan usaha asrama');
        $kom6_kreatifs = User::findOrFail($id)->kreatifs->where('komponen', 'Menulis proposal usaha');
        $kom7_kreatifs = User::findOrFail($id)->kreatifs->where('komponen', 'Menghasilkan karya kreatif (video, grafis, dll)');
        $kom8_kreatifs = User::findOrFail($id)->kreatifs->where('komponen', 'Memiliki keberanian untuk memulai usaha');

        $kom1_kreatifs_count = User::findOrFail($id)->kreatifs->where('komponen', 'Mengikuti pelatihan kreativitas dan kewirausahaan')->count();
        $kom2_kreatifs_count = User::findOrFail($id)->kreatifs->where('komponen', 'Mengikuti kegiatan mentoring')->count();
        $kom3_kreatifs_count = User::findOrFail($id)->kreatifs->where('komponen', 'Membaca buku, majalah, internet dll terkait kewirausahaan')->count();
        $kom4_kreatifs_count = User::findOrFail($id)->kreatifs->where('komponen', 'Mengikuti forum ceramah atau diskusi kewirausahaan')->count();
        $kom5_kreatifs_count = User::findOrFail($id)->kreatifs->where('komponen', 'Melakukan tugas dalam kegiatan usaha asrama')->count();
        $kom6_kreatifs_count = User::findOrFail($id)->kreatifs->where('komponen', 'Menulis proposal usaha')->count();
        $kom7_kreatifs_count = User::findOrFail($id)->kreatifs->where('komponen', 'Menghasilkan karya kreatif (video, grafis, dll)')->count();
        $kom8_kreatifs_count = User::findOrFail($id)->kreatifs->where('komponen', 'Memiliki keberanian untuk memulai usaha')->count();

        $ipks = User::findOrFail($id)->ipks;
        $data_ipks = User::findOrFail($id)->ipks->avg('ip');

        // Menghitung bulan sebelumnya
        $today = Carbon::now();
        $lastMonth = $today->copy()->subMonth();

        // Mengambil tanggal awal dan akhir bulan
        $startDate = $lastMonth->startOfMonth();
        $endDate = $lastMonth->endOfMonth();

        $jumlahAkademikBulanSebelumnya = User::findOrFail($id)->akademiks
            ->where('created_at', [$startDate, $endDate])
            ->count();

        $jumlahLeadershipBulanSebelumnya = User::findOrFail($id)->leaderships
            ->where('created_at', [$startDate, $endDate])
            ->count();

        $jumlahKarakterBulanSebelumnya = User::findOrFail($id)->karakters
            ->where('created_at', [$startDate, $endDate])
            ->count();

        $jumlahKreatifBulanSebelumnya = User::findOrFail($id)->kreatifs
            ->where('created_at', [$startDate, $endDate])
            ->count();

        return view('super.pages.mentor.detail', compact(
            'lastMonth',
            'jumlahAkademikBulanSebelumnya',
            'jumlahLeadershipBulanSebelumnya',
            'jumlahKarakterBulanSebelumnya',
            'jumlahKreatifBulanSebelumnya',
            'user',
            'ipks',
            'data_ipks',
            'akademiks',
            'leaderships',
            'karakters',
            'kreatifs',
            'data_akademiks',
            'kom1_akademiks',
            'kom2_akademiks',
            'kom3_akademiks',
            'kom4_akademiks',
            'kom5_akademiks',
            'kom6_akademiks',
            'kom7_akademiks',
            'kom8_akademiks',
            'kom1_akademiks_count',
            'kom2_akademiks_count',
            'kom3_akademiks_count',
            'kom4_akademiks_count',
            'kom5_akademiks_count',
            'kom6_akademiks_count',
            'kom7_akademiks_count',
            'kom8_akademiks_count',
            'data_leaderships',
            'kom1_leaderships',
            'kom2_leaderships',
            'kom3_leaderships',
            'kom4_leaderships',
            'kom5_leaderships',
            'kom6_leaderships',
            'kom7_leaderships',
            'kom8_leaderships',
            'kom9_leaderships',
            'kom1_leaderships_count',
            'kom2_leaderships_count',
            'kom3_leaderships_count',
            'kom4_leaderships_count',
            'kom5_leaderships_count',
            'kom6_leaderships_count',
            'kom7_leaderships_count',
            'kom8_leaderships_count',
            'kom9_leaderships_count',
            'data_karakters',
            'kom1_karakters',
            'kom2_karakters',
            'kom3_karakters',
            'kom4_karakters',
            'kom5_karakters',
            'kom6_karakters',
            'kom7_karakters',
            'kom8_karakters',
            'kom9_karakters',
            'kom1_karakters_count',
            'kom2_karakters_count',
            'kom3_karakters_count',
            'kom4_karakters_count',
            'kom5_karakters_count',
            'kom6_karakters_count',
            'kom7_karakters_count',
            'kom8_karakters_count',
            'kom9_karakters_count',
            'data_kreatifs',
            'kom1_kreatifs',
            'kom2_kreatifs',
            'kom3_kreatifs',
            'kom4_kreatifs',
            'kom5_kreatifs',
            'kom6_kreatifs',
            'kom7_kreatifs',
            'kom8_kreatifs',
            'kom1_kreatifs_count',
            'kom2_kreatifs_count',
            'kom3_kreatifs_count',
            'kom4_kreatifs_count',
            'kom5_kreatifs_count',
            'kom6_kreatifs_count',
            'kom7_kreatifs_count',
            'kom8_kreatifs_count',
        ));
    }

}
