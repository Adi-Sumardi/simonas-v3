<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo()
    {
        // $role = Auth::user()->role;

        // switch($role) {
        //     case 'super':
        //         return '/super';
        //     break;
        //     case 'admin':
        //         return '/admin';
        //     break;
        //     case 'mentor':
        //         return '/mentor';
        //     break;
        //     case 'mahasiswa':
        //         return '/mahasiswa';
        //     break;
        //     case 'alumni':
        //         return '/alumni';
        //     break;
        // }

        if(auth()->user()->role == 'super') {
            return '/super';
        } elseif (auth()->user()->role == 'admin') {
            return '/admin';
        } elseif (auth()->user()->role == 'mentor') {
            return '/mentor';
        } elseif (auth()->user()->role == 'mahasiswa') {
            return '/mahasiswa-biodata';
        } elseif (auth()->user()->role == 'alumni') {
            return '/alumni';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'asrama' => ['required'],
            'tgl_masuk' => ['required'],
            'no_telp' => ['required'],
            'role' => ['required'],
            'captcha' => ['required', 'captcha'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_telp' => $data['no_telp'],
            'asrama' => $data['asrama'],
            'tgl_masuk' => $data['tgl_masuk'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'captcha' => $data['captcha'],
        ]);
    }
}
