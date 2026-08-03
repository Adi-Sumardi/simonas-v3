<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function show(Request $request): Response|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();

        if ($user->onboarding_completed_at !== null) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Onboarding', [
            'user' => array_merge($user->only([
                'name', 'no_hp', 'universitas', 'fakultas', 'prodi', 'semester', 'angkatan',
                'alamat', 'provinsi', 'kota', 'kecamatan', 'nama_ayah', 'nama_ibu', 'no_telp', 'avatar',
            ]), ['role' => $user->role]),
        ]);
    }

    public function store(Request $request)
    {
        $isMahasiswa = $request->user()->role === 'mahasiswa';

        $data = $request->validate([
            'no_hp'       => 'required|string|max:20',
            'universitas' => $isMahasiswa ? 'required|string|max:255' : 'nullable|string|max:255',
            'fakultas'    => 'nullable|string|max:255',
            'prodi'       => $isMahasiswa ? 'required|string|max:255' : 'nullable|string|max:255',
            'semester'    => $isMahasiswa ? 'required|integer|min:1|max:14' : 'nullable|integer|min:1|max:14',
            'alamat'      => 'required|string|max:500',
            'provinsi'    => 'nullable|string|max:255',
            'kota'        => 'nullable|string|max:255',
            'kecamatan'   => 'nullable|string|max:255',
            'nama_ayah'   => 'nullable|string|max:255',
            'nama_ibu'    => 'nullable|string|max:255',
            'no_telp'     => 'nullable|string|max:20',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $data['onboarding_completed_at'] = now();

        $user->update($data);

        return redirect()->route('dashboard')
            ->with('success', 'Profil kamu sudah lengkap. Selamat datang di SIMONAS!')
            ->with('show_tour', true);
    }
}
