<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('index');
        $role = Auth::user()->role;
        $checkrole = explode(',', $role);
        if (in_array('super', $checkrole)) {
            return redirect('/super');
        } elseif (in_array('admin', $checkrole)) {
            return redirect('/admin');
        } elseif (in_array('mentor', $checkrole)) {
            return redirect('/mentor');
        } elseif (in_array('mahasiswa', $checkrole)) {
            return redirect('/mahasiswa');
        } elseif (in_array('alumni', $checkrole)) {
            return redirect('/alumni');
        }
    }
}
