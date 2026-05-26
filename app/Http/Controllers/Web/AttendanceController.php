<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    // ─── Mentor/Admin Methods ──────────────────────────────────────────

    public function index()
    {
        $user = Auth::user();
        abort_if(!in_array($user->role, ['mentor', 'admin', 'super']), 403);

        $sessions = AttendanceSession::where('created_by', $user->id)
            ->withCount('attendances')
            ->latest()
            ->get();

        return Inertia::render('Mentor/Attendance/Index', [
            'sessions' => $sessions,
        ]);
    }

    public function storeSession(Request $request)
    {
        $user = Auth::user();
        abort_if(!in_array($user->role, ['mentor', 'admin', 'super']), 403);

        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'location_name' => 'nullable|string|max:255',
            'lat'           => 'nullable|numeric',
            'lng'           => 'nullable|numeric',
            'radius_meters' => 'integer|min:10',
            'duration_mins' => 'required|integer|min:1',
        ]);

        $session = AttendanceSession::create([
            'created_by'    => $user->id,
            'title'         => $data['title'],
            'location_name' => $data['location_name'],
            'lat'           => $data['lat'],
            'lng'           => $data['lng'],
            'radius_meters' => $data['radius_meters'] ?? 50,
            'token'         => Str::random(40),
            'expires_at'    => now()->addMinutes($data['duration_mins']),
            'is_active'     => true,
        ]);

        return redirect()->route('mentor.attendance.show', $session->id)
            ->with('success', 'Sesi absensi berhasil dibuat.');
    }

    public function showSession(AttendanceSession $session)
    {
        $user = Auth::user();
        abort_if($session->created_by !== $user->id && !in_array($user->role, ['admin', 'super']), 403);

        $session->load('attendances.user');

        return Inertia::render('Mentor/Attendance/Show', [
            'session' => $session,
        ]);
    }

    public function destroySession(AttendanceSession $session)
    {
        $user = Auth::user();
        abort_if($session->created_by !== $user->id && !in_array($user->role, ['admin', 'super']), 403);

        $session->delete();

        return redirect()->route('mentor.attendance.index')
            ->with('success', 'Sesi absensi dihapus.');
    }

    // ─── Mahasiswa Methods ─────────────────────────────────────────────

    public function scanner()
    {
        return Inertia::render('Mahasiswa/Attendance/Scanner');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'lat'   => 'nullable|numeric',
            'lng'   => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $session = AttendanceSession::where('token', $request->token)->first();

        if (!$session) {
            return back()->with('error', 'QR Code tidak valid atau kadaluarsa.');
        }

        if (!$session->is_active || ($session->expires_at && now()->greaterThan($session->expires_at))) {
            return back()->with('error', 'Sesi absensi sudah ditutup atau kadaluarsa.');
        }

        // Check if already scanned
        $existing = Attendance::where('user_id', $user->id)->where('session_token', $session->token)->first();
        if ($existing) {
            return back()->with('error', 'Kamu sudah melakukan presensi untuk sesi ini.');
        }

        // Location verification
        if ($session->lat && $session->lng) {
            if (!$request->lat || !$request->lng) {
                return back()->with('error', 'Gagal mendapatkan lokasi kamu. Pastikan GPS aktif.');
            }

            $distance = $this->calculateDistance($session->lat, $session->lng, $request->lat, $request->lng);

            if ($distance > $session->radius_meters) {
                return back()->with('error', 'Kamu berada di luar radius lokasi presensi (Jarak: ' . round($distance) . 'm).');
            }
        }

        Attendance::create([
            'user_id'       => $user->id,
            'activity_name' => $session->title,
            'location_name' => $session->location_name,
            'lat'           => $request->lat,
            'lng'           => $request->lng,
            'status'        => 'present',
            'session_token' => $session->token,
            'scanned_at'    => now(),
        ]);

        // Add activity log automatically for Mahasiswa
        if ($user->role === 'mahasiswa') {
            \App\Models\Akademik::create([
                'user_id' => $user->id,
                'kegiatan' => 'Kehadiran: ' . $session->title,
                'keterangan' => 'Absensi via QR Code di ' . ($session->location_name ?? 'Lokasi'),
                'waktu' => now(),
                'nilai' => 100,
                'semester' => 'Ganjil', // Default
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Presensi berhasil dicatat!');
    }

    /**
     * Calculate distance between two points in meters using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
