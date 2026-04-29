<?php

namespace App\Http\Controllers;

use App\Akademik;
use App\Alumni;
use App\Asrama;
use App\Karakter;
use App\Kegiatan;
use App\Kreatif;
use App\Leadership;
use App\Ipk;
use App\User;
use Illuminate\Http\Request;

class SuperController extends Controller
{
    public function index()
    {
        $data_alumni_asgj = Alumni::where('asal_asrama', 'Asrama Sunan Gunung Jati')->count();
        $jumlah_warga_asgj = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Sunan Gunung Jati']])->count();
        $jumlah_warga_pengurus_asgj = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Sunan Gunung Jati'], ['status_warga', 'Pengurus Asrama']])->count();
        $jumlah_warga_percobaan_asgj = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Sunan Gunung Jati'], ['status_warga', 'Warga Percobaan']])->count();
        $jumlah_warga_tetap_asgj = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Sunan Gunung Jati'], ['status_warga', 'Warga Tetap']])->count();

        $data_alumni_asg = Alumni::where('asal_asrama', 'Asrama Sunan Giri')->count();
        $jumlah_warga_asg = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Sunan Giri']])->count();
        $jumlah_warga_pengurus_asg = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Sunan Giri'],['status_warga', 'Pengurus Asrama']])->count();
        $jumlah_warga_percobaan_asg = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Sunan Giri'], ['status_warga', 'Warga Percobaan']])->count();
        $jumlah_warga_tetap_asg = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Sunan Giri'], ['status_warga', 'Warga Tetap']])->count();

        $data_alumni_aws = Alumni::where('asal_asrama', 'Asrama Wali Songo')->count();
        $jumlah_warga_aws = User::where([['role', 'alumni'], ['asrama', 'Asrama Wali Songo']])->count();
        $jumlah_warga_aws = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Wali Songo']])->count();
        $jumlah_warga_pengurus_aws = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Wali Songo'], ['status_warga', 'Pengurus Asrama']])->count();
        $jumlah_warga_percobaan_aws = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Wali Songo'], ['status_warga', 'Warga Percobaan']])->count();
        $jumlah_warga_tetap_aws = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Wali Songo'], ['status_warga', 'Warga Tetap']])->count();

        $data_alumni_aspuri = Alumni::where('asal_asrama', 'Asrama Putri')->count();
        $jumlah_warga_aspuri = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Putri']])->count();
        $jumlah_warga_pengurus_aspuri = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Putri'], ['status_warga', 'Pengurus Asrama']])->count();
        $jumlah_warga_percobaan_aspuri = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Putri'], ['status_warga', 'Warga Percobaan']])->count();
        $jumlah_warga_tetap_aspuri = User::where([['role', 'mahasiswa'], ['asrama', 'Asrama Putri'], ['status_warga', 'Warga Tetap']])->count();

        $data_kegiatan = Kegiatan::orderBy('waktu', 'DESC')->paginate(5);
        return view('super.index', compact(
            'data_kegiatan',
            'data_alumni_asgj',
            'data_alumni_asg',
            'data_alumni_aws',
            'data_alumni_aspuri',
            'jumlah_warga_asgj',
            'jumlah_warga_pengurus_asgj',
            'jumlah_warga_percobaan_asgj',
            'jumlah_warga_tetap_asgj',
            'jumlah_warga_asg',
            'jumlah_warga_pengurus_asg',
            'jumlah_warga_percobaan_asg',
            'jumlah_warga_tetap_asg',
            'jumlah_warga_aws',
            'jumlah_warga_pengurus_aws',
            'jumlah_warga_percobaan_aws',
            'jumlah_warga_tetap_aws',
            'jumlah_warga_aspuri',
            'jumlah_warga_pengurus_aspuri',
            'jumlah_warga_percobaan_aspuri',
            'jumlah_warga_tetap_aspuri',
        ));
    }

    public function dashboard()
    {
        $user = User::all()->count();
        // $user = User::where('role', 'mahasiswa')->count();
        $asgj = User::where('asrama', 'Asrama Sunan Gunung Jati')->where('role', 'mahasiswa')->count();
        $asg = User::where('asrama', 'Asrama Sunan Giri')->where('role', 'mahasiswa')->count();
        $aws = User::where('asrama', 'Asrama Wali Songo')->where('role', 'mahasiswa')->count();
        $dqf = User::where('asrama', 'Asrama Putri')->where('role', 'mahasiswa')->count();
        $kegiatan = Kegiatan::all()->count();
        $alumni = User::where('role', 'alumni')->count();
        $asrama = Asrama::all()->count();
        $akademik = Akademik::all()->count();
        $leadership = Leadership::all()->count();
        $karakter = Karakter::all()->count();
        $kreatif = Kreatif::all()->count();
        $jan = Kegiatan::whereMonth('created_at', '01')->count();
        $feb = Kegiatan::whereMonth('created_at', '02')->count();
        $mar = Kegiatan::whereMonth('created_at', '03')->count();
        $apr = Kegiatan::whereMonth('created_at', '04')->count();
        $mei = Kegiatan::whereMonth('created_at', '05')->count();
        $jun = Kegiatan::whereMonth('created_at', '06')->count();
        $jul = Kegiatan::whereMonth('created_at', '07')->count();
        $agu = Kegiatan::whereMonth('created_at', '08')->count();
        $sep = Kegiatan::whereMonth('created_at', '09')->count();
        $okt = Kegiatan::whereMonth('created_at', '10')->count();
        $nov = Kegiatan::whereMonth('created_at', '11')->count();
        $des = Kegiatan::whereMonth('created_at', '12')->count();

        return view('layouts.dashboard', compact('user',
                                                'asgj',
                                                'asg',
                                                'aws',
                                                'dqf', 
                                                'kegiatan', 
                                                'alumni', 
                                                'asrama',
                                                'akademik',
                                                'leadership',
                                                'karakter',
                                                'kreatif',
                                                'jan',
                                                'feb',
                                                'mar',
                                                'apr',
                                                'mei',
                                                'jun',
                                                'jul',
                                                'agu',
                                                'sep',
                                                'okt',
                                                'nov',
                                                'des'));
    }

    public function peringkat(request $id)
    {
        $user = User::findOrFail($id);
        $akademiks = User::findOrFail($id)->akademiks->count();
        $leaderships = User::findOrFail($id)->leaderships->count();
        $karakters = User::findOrFail($id)->karakters->count();
        $kreatifs = User::findOrFail($id)->kreatifs->count();

        $jumlah = User::findOrFail($id)->akademiks->leaderships->karakters->kreatifs->count();
        
        return view('warga.detail', compact(
            'user',
            'akademiks',
            'leaderships',
            'karakters',
            'kreatifs'));
    }

    public function kegiatanCount()
    {
        $kegiatan = Kegiatan::selectRaw('monthname(created_at) month, count(*) data')
                -> groupBy('month')
                -> orderBy('desc')
                -> get();
    }

    public function reporting()
    {
        return view('super.pages.reporting.index');
    }

    public function reportingFilterDate(Request $request)
    {
        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $data = User::with([
            'akademiks' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('Waktu', [$startDate, $endDate]);
            },
            'leaderships' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('Waktu', [$startDate, $endDate]);
            },
            'karakters' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('Waktu', [$startDate, $endDate]);
            },
            'kreatifs' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('Waktu', [$startDate, $endDate]);
            },
        ])->where('role', 'mahasiswa')->get();
    
        $groupedData = [];
        $asramaOrder = [
            'Asrama Sunan Gunung Jati',
            'Asrama Sunan Giri',
            'Asrama Wali Songo',
            'Asrama Putri'
        ];
        
        foreach ($data as $item) {
            $groupedData[$item->asrama][] = [
                'nama' => $item->name,
                'asrama' => $item->asrama,
                'jumlahAkademik' => $item->akademiks->count(),
                'jumlahLeadership' => $item->leaderships->count(),
                'jumlahKarakter' => $item->karakters->count(),
                'jumlahKreatif' => $item->kreatifs->count(),
            ];
        }
        
        uksort($groupedData, function($a, $b) use ($asramaOrder) {
            return array_search($a, $asramaOrder) - array_search($b, $asramaOrder);
        });
        
        return view('super.pages.reporting.filterDate', compact('groupedData', 'startDate', 'endDate'));
    }
    
    public function eksekutifSummary(Request $request)
    {
        $request->validate([
            'asrama' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $asrama = $request->input('asrama');

        $warga = User::where('asrama', $asrama)
            ->where('role', 'mahasiswa')
            ->get();
        $jumlahWargaAsrama = $warga->count();

        $data = User::with([
            'akademiks' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('Waktu', [$startDate, $endDate]);
            },
            'leaderships' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('Waktu', [$startDate, $endDate]);
            },
            'karakters' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('Waktu', [$startDate, $endDate]);
            },
            'kreatifs' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('Waktu', [$startDate, $endDate]);
            },
        ])->where('role', 'mahasiswa')->where('asrama', $asrama)->get();

        $table1Data = [];
        foreach ($data as $item) {
            $totalKegiatan = $item->akademiks->count() +
                            $item->leaderships->count() +
                            $item->karakters->count() +
                            $item->kreatifs->count();

            $table1Data[] = [
                'nama' => $item->name,
                'jumlahKegiatan' => $totalKegiatan,
                'jumlahAkademik' => $item->akademiks->count(),
                'jumlahLeadership' => $item->leaderships->count(),
                'jumlahKarakter' => $item->karakters->count(),
                'jumlahKreatif' => $item->kreatifs->count(),
            ];
        }

        $table2Data = [];
        foreach ($warga as $user) {
            $ipk = Ipk::where('user_id', $user->id)->value('ip');
            $table2Data[] = [
                'nama' => $user->name,
                'averageIP' => $ipk,
            ];
        }

        $rataRataIpk = $warga->pluck('id')->isNotEmpty() ? Ipk::whereIn('user_id', $warga->pluck('id'))->avg('ip') : 0;

        return view('super.pages.reporting.summary', [
            'asrama' => $asrama,
            'jumlahWargaAsrama' => $jumlahWargaAsrama,
            'table1Data' => $table1Data,
            'table2Data' => $table2Data,
            'rataRataIpk' => $rataRataIpk,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
