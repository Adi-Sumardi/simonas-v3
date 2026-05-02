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
                'id'             => $e->id,
                'title'          => $e->title,
                'date'           => $e->date->format('Y-m-d'),
                'time'           => $e->time ?? '00:00',
                'type'           => $e->type,
                'color'          => $e->color,
                'desc'           => $e->desc,
                'recurring'      => $e->recurring,
                'is_mandatory'   => $e->is_mandatory,
                'excluded_dates' => $e->excluded_dates ?? [],
                'completed_at_dates' => $e->completed_at_dates ?? [],
            ]);

        $upcoming = $events->filter(fn($e) => $e['date'] >= $today && $e['type'] !== 'shalat')
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
        if ($event->is_mandatory) {
            return back()->with('error', 'Kegiatan wajib tidak dapat diubah.');
        }

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

    public function destroy(Request $request, UserEvent $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        if ($event->is_mandatory) {
            return back()->with('error', 'Kegiatan wajib tidak dapat dihapus.');
        }

        // Prevent deletion of shalat events
        if ($event->type === 'shalat') {
            return back()->with('error', 'Jadwal sholat tidak dapat dihapus.');
        }

        $excludeDate = $request->input('exclude_date');

        if ($excludeDate && $event->recurring) {
            $excluded = $event->excluded_dates ?? [];
            if (!in_array($excludeDate, $excluded)) {
                $excluded[] = $excludeDate;
                $event->update(['excluded_dates' => $excluded]);
            }
            return back()->with('success', 'Jadwal pada tanggal tersebut telah dihapus.');
        }

        $event->delete();
        return back()->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function toggleComplete(Request $request, UserEvent $event)
    {
        abort_if($event->user_id !== Auth::id(), 403);
        $date = $request->input('date');
        if (!$date) return back();

        $completed = $event->completed_at_dates ?? [];
        $details = $event->completed_at_details ?? [];

        if (in_array($date, $completed)) {
            $completed = array_diff($completed, [$date]);
            unset($details[$date]);
        } else {
            $completed[] = $date;
            $details[$date] = now()->toDateTimeString();
        }

        $event->update([
            'completed_at_dates'   => array_values($completed),
            'completed_at_details' => $details,
        ]);

        return back();
    }
}
