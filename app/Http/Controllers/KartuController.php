<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class KartuController extends Controller
{
    public function index()
    {
        $users = User::where('asrama', 'Asrama Sunan Gunung Jati')->where('role', 'mahasiswa')->paginate(6);
        return view('kartu.index', compact('users'));
    }

    public function asgj()
    {
        $users = User::where('asrama', 'Asrama Sunan Gunung Jati')->where('role', 'mahasiswa')->paginate(6);
        return view('kartu.asgj', compact('users'));
    }

    public function asg()
    {
        $users = User::where('asrama', 'Asrama Sunan Giri')->where('role', 'mahasiswa')->paginate(6);
        return view('kartu.asg', compact('users'));
    }

    public function aws()
    {
        $users = User::where('asrama', 'Asrama Wali Songo')->where('role', 'mahasiswa')->paginate(6);
        return view('kartu.aws', compact('users'));
    }

    public function dqf()
    {
        $users = User::where('asrama', 'Asrama Putri')->where('role', 'mahasiswa')->paginate(6);
        return view('kartu.dqf', compact('users'));
    }

    public function detail($id)
    {
        $user = User::findOrFail($id);
        $akademiks = User::findOrFail($id)->akademiks->count();
        $leaderships = User::findOrFail($id)->leaderships->count();
        $karakters = User::findOrFail($id)->karakters->count();
        $kreatifs = User::findOrFail($id)->kreatifs->count();
        
        return view('kartu.detail', compact(
            'user',
            'akademiks',
            'leaderships',
            'karakters',
            'kreatifs'));
    }
}
