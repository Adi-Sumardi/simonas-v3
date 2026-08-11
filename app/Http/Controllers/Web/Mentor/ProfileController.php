<?php

namespace App\Http\Controllers\Web\Mentor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $totalMentees = User::where('mentor_id', $user->id)->where('role', 'mahasiswa')->count();

        return Inertia::render('Mentor/Profile', [
            'user' => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'avatar' => $user->avatar,
                'bio'    => $user->bio,
                'no_hp'  => $user->no_hp,
            ],
            'total_mentees' => $totalMentees,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'bio'    => 'nullable|string|max:500',
            'no_hp'  => 'nullable|string|max:20',
        ]);

        Auth::user()->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
            \Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
