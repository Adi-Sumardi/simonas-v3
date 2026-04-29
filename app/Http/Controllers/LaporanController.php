<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Akademik;
use App\Leadership;
use App\Karakter;
use App\Kreatif;
use App\User;
use DB;

class LaporanController extends Controller
{
    public function index()
    {

        $dt_akademiks = Akademik::count();
        $dt_leaderships = Leadership::count();
        $dt_karakters = Karakter::count();
        $dt_kreatifs = Kreatif::count();

        $dt_ipk = User::select(
            'users.name',
            'users.asrama',
            'users.universitas',
            'users.avatar',
            DB::raw('AVG(ipks.ip) as rata_rata_ip')
        )
            ->join('ipks', 'users.id', '=', 'ipks.user_id')
            ->groupBy('users.id', 'users.name', 'users.asrama', 'users.universitas', 'users.avatar')
            ->orderBy('rata_rata_ip', 'desc')
            ->take(3)
            ->get();

        $dt_kegiatan = User::select(
                'users.name',
                'users.asrama',
                'users.avatar',
                DB::raw('COUNT(all_activities.user_id) as total_kegiatan')
            )
            ->join(DB::raw('( 
                SELECT user_id FROM akademiks
                UNION ALL
                SELECT user_id FROM leaderships
                UNION ALL
                SELECT user_id FROM kreatifs
                UNION ALL
                SELECT user_id FROM karakters
            ) as all_activities'), 'users.id', '=', 'all_activities.user_id')
            ->groupBy('users.id', 'users.name', 'users.asrama', 'users.avatar')
            ->orderBy('total_kegiatan', 'desc')
            ->take(3) // Membatasi hasil menjadi 3 data
            ->get();

        return view('super.pages.laporan.index', compact(
            'dt_akademiks',
            'dt_leaderships',
            'dt_karakters',
            'dt_kreatifs',
            'dt_ipk',
            'dt_kegiatan'
        ));
    }
}
