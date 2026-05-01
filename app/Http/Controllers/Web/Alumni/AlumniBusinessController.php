<?php

namespace App\Http\Controllers\Web\Alumni;

use App\Http\Controllers\Controller;
use App\Models\AlumniBusiness;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumniBusinessController extends Controller
{
    public function index(Request $request)
    {
        $businesses = AlumniBusiness::with('alumni:id,name,avatar')
            ->where('is_active', true)
            ->when($request->search, fn($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('company_name', 'like', "%$s%")
                  ->orWhere('business_type', 'like', "%$s%")
                  ->orWhere('description', 'like', "%$s%");
            }))
            ->when($request->type, fn($q, $t) => $q->where('business_type', $t))
            ->latest()
            ->paginate(12)
            ->through(fn($b) => [
                'id'            => $b->id,
                'company_name'  => $b->company_name,
                'business_type' => $b->business_type,
                'description'   => $b->description,
                'logo'          => $b->logo,
                'address'       => $b->address,
                'website'       => $b->website,
                'phone'         => $b->phone,
                'email'         => $b->email,
                'social_media'  => $b->social_media,
                'owner'         => $b->alumni ? [
                    'id'     => $b->alumni->id,
                    'name'   => $b->alumni->name,
                    'avatar' => $b->alumni->avatar ?? null,
                ] : null,
            ]);

        return Inertia::render('Alumni/Bisnis', [
            'businesses' => $businesses,
            'filter'     => ['search' => $request->search, 'type' => $request->type],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'  => 'required|string|max:200',
            'business_type' => 'required|string|max:100',
            'description'   => 'required|string|max:2000',
            'address'       => 'nullable|string|max:500',
            'website'       => 'nullable|url|max:255',
            'phone'         => 'nullable|string|max:30',
            'email'         => 'nullable|email|max:200',
        ]);

        AlumniBusiness::updateOrCreate(
            ['alumni_id' => $request->user()->id],
            $validated
        );

        return back()->with('success', 'Bisnis berhasil didaftarkan!');
    }
}
