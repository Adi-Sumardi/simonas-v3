<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlumniBusiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AlumniBusinessController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AlumniBusiness::with('alumni:id,name,email')
                ->where('is_active', true);

            // Filter by business type
            if ($request->has('business_type')) {
                $query->where('business_type', $request->business_type);
            }

            // Search
            if ($request->has('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('company_name', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            $businesses = $query->orderBy('created_at', 'desc')->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $businesses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'business_type' => 'required|string|max:100',
                'description' => 'required|string',
                'address' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'website' => 'nullable|url',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email',
                'social_media' => 'nullable|json'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $data = $request->all();
            $data['alumni_id'] = auth()->id();

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $filename = time() . '_' . $logo->getClientOriginalName();
                $logo->move('../public_html/data_photo/business', $filename);
                $data['logo'] = $filename;
            }

            $business = AlumniBusiness::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data bisnis berhasil ditambahkan',
                'data' => $business
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $business = AlumniBusiness::with('alumni:id,name,email')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $business
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $business = AlumniBusiness::findOrFail($id);

            // Verify ownership
            if ($business->alumni_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string|max:255',
                'business_type' => 'required|string|max:100',
                'description' => 'required|string',
                'address' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'website' => 'nullable|url',
                'phone' => 'nullable|string|max:50',
                'email' => 'nullable|email',
                'social_media' => 'nullable|json'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $data = $request->all();

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($business->logo) {
                    $oldLogoPath = '../public_html/data_photo/business/' . $business->logo;
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }

                $logo = $request->file('logo');
                $filename = time() . '_' . $logo->getClientOriginalName();
                $logo->move('../public_html/data_photo/business', $filename);
                $data['logo'] = $filename;
            }

            $business->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data bisnis berhasil diperbarui',
                'data' => $business
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $business = AlumniBusiness::findOrFail($id);

            if ($business->alumni_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            if ($business->logo) {
                $logoPath = '../public_html/data_photo/business/' . $business->logo;
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }

            $business->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data bisnis berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
