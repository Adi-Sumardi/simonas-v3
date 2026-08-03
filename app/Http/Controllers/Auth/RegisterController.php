<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class RegisterController extends Controller
{
    public function showRegistrationForm(): InertiaResponse
    {
        $asramas = \App\Models\Asrama::orderBy('nama_asrama')
            ->pluck('nama_asrama')
            ->map(fn($name) => ['value' => $name, 'label' => $name])
            ->values();

        return Inertia::render('Auth/Register', [
            'asramas' => $asramas,
        ]);
    }

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
    public function redirectTo()
    {
        return '/dashboard';
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
            'no_induk' => ['required', 'string', 'max:50', 'unique:users,no_induk'],
            'asrama' => ['required', 'string', 'exists:asramas,nama_asrama'],
            'tgl_masuk' => ['required', 'date'],
            'role' => ['required', 'string', 'in:mahasiswa,mentor,alumni'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'no_induk' => $data['no_induk'],
            'asrama' => $data['asrama'],
            'tgl_masuk' => $data['tgl_masuk'],
            'role' => $data['role'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
