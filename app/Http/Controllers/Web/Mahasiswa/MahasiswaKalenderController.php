<?php

namespace App\Http\Controllers\Web\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MahasiswaKalenderController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $today  = now()->format('Y-m-d');

        $events = UserEvent::where('user_id', $user->id)
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->map(fn($e) => [
                'id'        => $e->id,
                'title'     => $e->title,
                'date'      => $e->date->format('Y-m-d'),
                'time'      => $e->time ?? '00:00',
                'type'      => $e->type,
                'color'     => $e->color,
                'desc'      => $e->desc,
                'recurring' => $e->recurring,
            ]);

        $upcoming = $events->filter(fn($e) => $e['date'] >= $today)
            ->take(5)
            ->values();

        return Inertia::render('Mahasiswa/Kalender', [
            'events'   => $events->values(),
            'upcoming' => $upcoming,
            'today'    => $today,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'date'      => 'required|date',
            'time'      => 'nullable|date_format:H:i',
            'type'      => 'required|string|max:50',
            'color'     => 'nullable|string|max:20',
            'desc'      => 'nullable|string|max:500',
            'recurring' => 'boolean',
        ]);

        $event = UserEvent::create(array_merge($data, [
            'user_id' => Auth::id(),
            'color'   => $data['color'] ?? '#6366f1',
        ]));

        return back()->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, UserEvent $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);

        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'date'      => 'required|date',
            'time'      => 'nullable|date_format:H:i',
            'type'      => 'required|string|max:50',
            'color'     => 'nullable|string|max:20',
            'desc'      => 'nullable|string|max:500',
            'recurring' => 'boolean',
        ]);

        $event->update($data);

        return back()->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(UserEvent $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $event->delete();

        return back()->with('success', 'Kegiatan dihapus.');
    }
}
