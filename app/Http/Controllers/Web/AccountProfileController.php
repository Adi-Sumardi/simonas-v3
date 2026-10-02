<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/**
 * Generic account profile for roles without a dedicated profile page
 * (admin, super). Mahasiswa/pengurus, mentor and alumni keep their own.
 */
class AccountProfileController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return Inertia::render('Account/Profile', [
            'user' => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'avatar' => $user->avatar,
                'bio'    => $user->bio,
                'no_hp'  => $user->no_hp,
                'roles'  => $user->getRoleNames()->values(),
                'last_login_at' => $user->last_login_at?->format('d M Y H:i'),
            ],
        ]);
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'bio'   => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        /** @var User $user */
        $user = $request->user();

        // The `avatar` accessor returns a full URL; the stored value is the disk path.
        $old = $user->getRawOriginal('avatar');
        if ($old && ! str_starts_with($old, 'http') && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        $user->update(['avatar' => $request->file('avatar')->store('avatars', 'public')]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
