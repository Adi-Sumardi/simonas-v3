<?php

namespace App\Http\Controllers;

use App\FormAkses;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(Request $request)
    {
        $forms = FormAkses::all();
        return view('akses.form_index', compact('forms'));
    }

    public function create()
    {
        return view('akses.form_index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'no_wa' => 'required',
            'asrama' => 'required',
            'status' => 'required',
        ]);

        FormAkses::create($request->all());
        return redirect('/');
    }
    
    public function view(Request $request)
    {
        $forms = FormAkses::all();
        return view('akses.form_index', compact('forms'));
    }
    

    public function delete($id)
    {
        FormAkses::destroy($id);
        return back();
    }

    // public function cetak_pdfRequest()
    // {
    //     $forms = FormAkses::all();

    //     $pdf = PDF::loadView('akses.request_pdf', compact('forms'));
    //     return $pdf->download('laporan-data-request-akun.pdf');
    // }
}
