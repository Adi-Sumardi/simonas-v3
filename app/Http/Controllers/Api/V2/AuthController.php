<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            Log::info('Login attempt details:', [
                'email' => $request->email,
                'password_length' => strlen($request->password),
                'headers' => $request->headers->all()
            ]);

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $user = User::where('email', $request->email)
                       ->whereIn('role', ['mahasiswa', 'super'])
                       ->first();
            
            Log::info('User found:', [
                'exists' => $user ? 'yes' : 'no',
                'role' => $user ? $user->role : 'none',
            ]);

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email atau password salah'
                ], 401);
            }

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Login error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat login: ' . $e->getMessage()
            ], 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            // Ambil user dengan data lengkap
            $user = User::select([
                'id', 'name', 'email', 'role', 'avatar', 'asrama', 
                'no_telp', 'alamat_sekarang', 'pekerjaan',
                'universitas', 'fakultas', 'prodi', 'angkatan',
                'tgl_seminar', 'tgl_skripsi', 'tgl_wisuda',
                'nik', 'alamat', 'provinsi', 'kota', 'kecamatan',
                'kode_pos', 'asal_sekolah', 'tgl_lahir',
                'prestasi', 'organisasi', 'nama_ayah', 'nama_ibu',
                'status_warga', 'tgl_masuk', 'tgl_keluar'
            ])->find(auth()->id());

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Tambahkan log untuk debugging
            \Log::info('Profile data:', $user->toArray());

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Hapus token yang digunakan untuk request ini
            $request->user()->currentAccessToken()->delete();
            
            // Hapus semua token (optional)
            // $request->user()->tokens()->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout'
            ], 500);
        }
    }

    private function normalizePhoneNumber($phone)
    {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Jika dimulai dengan '62', hapus '62'
        if (substr($phone, 0, 2) === '62') {
            $phone = substr($phone, 2);
        }
        
        // Jika dimulai dengan '0', hapus '0'
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }
        
        Log::info('Phone number normalization:', [
            'input' => $phone,
            'normalized' => $phone
        ]);
        
        return $phone;
    }

    public function checkPhone(Request $request)
    {
        try {
            $cleanNumber = $this->normalizePhoneNumber($request->phone);
            
            Log::info('Phone check attempt:', [
                'original_phone' => $request->phone,
                'clean_number' => $cleanNumber
            ]);

            // Cek dengan berbagai kemungkinan format
            $user = User::where(function($query) use ($cleanNumber) {
                $query->where('no_telp', 'LIKE', '%' . $cleanNumber)
                      ->orWhere('no_telp', 'LIKE', '0' . $cleanNumber)
                      ->orWhere('no_telp', 'LIKE', '62' . $cleanNumber)
                      ->orWhere('no_telp', 'LIKE', '620' . $cleanNumber);
            })
            ->whereIn('role', ['mahasiswa', 'super'])
            ->first();
            
            if ($user) {
                Log::info('User found with phone:', [
                    'user_id' => $user->id,
                    'stored_phone' => $user->no_telp
                ]);
            }
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor WhatsApp tidak terdaftar sebagai mahasiswa'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Nomor WhatsApp terdaftar'
            ]);

        } catch (\Exception $e) {
            Log::error('Phone check error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memeriksa nomor'
            ], 500);
        }
    }

    public function loginWithPhone(Request $request)
    {
        try {
            $cleanNumber = $this->normalizePhoneNumber($request->phone);
            
            Log::info('Phone login attempt:', [
                'original_phone' => $request->phone,
                'clean_number' => $cleanNumber
            ]);

            // Cek dengan berbagai kemungkinan format
            $user = User::where(function($query) use ($cleanNumber) {
                $query->where('no_telp', 'LIKE', '%' . $cleanNumber)
                      ->orWhere('no_telp', 'LIKE', '0' . $cleanNumber)
                      ->orWhere('no_telp', 'LIKE', '62' . $cleanNumber)
                      ->orWhere('no_telp', 'LIKE', '620' . $cleanNumber);
            })
            ->whereIn('role', ['mahasiswa', 'super'])
            ->first();
            
            Log::info('User query result:', [
                'found' => $user ? 'yes' : 'no',
                'user_details' => $user ? [
                    'id' => $user->id,
                    'no_telp' => $user->no_telp,
                    'role' => $user->role
                ] : null
            ]);
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor WhatsApp tidak terdaftar'
                ], 401);
            }

            try {
                // Generate token
                $token = $user->createToken('auth-token')->plainTextToken;
                
                Log::info('Token generated successfully for user:', [
                    'user_id' => $user->id,
                    'token_length' => strlen($token)
                ]);

                return response()->json([
                    'status' => 'success',
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'no_telp' => $user->no_telp,
                        'role' => $user->role,
                    ]
                ]);

            } catch (\Exception $tokenError) {
                Log::error('Token generation error:', [
                    'error' => $tokenError->getMessage(),
                    'user_id' => $user->id
                ]);
                
                throw $tokenError;
            }

        } catch (\Exception $e) {
            Log::error('Phone login error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat login: ' . $e->getMessage()
            ], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            Log::info('Register attempt:', $request->all());

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'no_telp' => 'required|string',
                'asrama' => 'required|string|in:Asrama Sunan Giri,Asrama Sunan Gunung Jati,Asrama Wali Songo,Asrama Putri',
                'tgl_masuk' => 'required|date',
                'tgl_keluar' => 'required|date|after:tgl_masuk',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telp' => $request->no_telp,
                'asrama' => $request->asrama,
                'tgl_masuk' => $request->tgl_masuk,
                'tgl_keluar' => $request->tgl_keluar,
                'role' => 'mahasiswa',
            ]);
            $user->assignRole('mahasiswa');

            Log::info('User registered successfully:', ['user_id' => $user->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            Log::error('Registration error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
                'no_telp' => 'nullable|string|max:255',
                'universitas' => 'nullable|string|max:255',
                'fakultas' => 'nullable|string|max:255',
                'prodi' => 'nullable|string|max:255',
                'asrama' => 'nullable|string|max:255',
                'alamat_sekarang' => 'nullable|string|max:255',
                'tgl_masuk' => 'nullable|string|max:255',
                'tgl_keluar' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = auth()->user();
            $user->update($request->only([
                'name', 'email', 'no_telp', 'universitas', 'fakultas', 
                'prodi', 'asrama', 'alamat_sekarang', 
                'tgl_masuk', 'tgl_keluar'
            ]));

            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating profile: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memperbarui profil'
            ], 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        try {
            $user = auth()->user();
            Log::info('Starting avatar update for user:', [
                'user_id' => $user->id,
                'has_file' => $request->hasFile('avatar')
            ]);
            
            if ($request->hasFile('avatar')) {
                $validator = Validator::make($request->all(), [
                    'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
                ]);

                if ($validator->fails()) {
                    Log::error('Avatar validation failed:', $validator->errors()->toArray());
                    return response()->json([
                        'status' => 'error',
                        'message' => $validator->errors()->first()
                    ], 422);
                }

                // Cek dan buat direktori jika belum ada
                $uploadPath = '../public_html/data_photo';
                if (!file_exists($uploadPath)) {
                    Log::info('Creating directory:', ['path' => $uploadPath]);
                    mkdir($uploadPath, 0755, true);
                }

                // Hapus avatar lama jika ada
                if ($user->avatar) {
                    $oldPath = $uploadPath . '/' . $user->avatar;
                    Log::info('Checking old avatar:', ['path' => $oldPath]);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                        Log::info('Old avatar deleted');
                    }
                }

                $avatar = $request->file('avatar');
                $filename = time() . '_' . $avatar->getClientOriginalName();
                
                Log::info('Attempting to save new avatar:', [
                    'filename' => $filename,
                    'upload_path' => $uploadPath,
                    'full_path' => $uploadPath . '/' . $filename
                ]);
                
                // Simpan file baru
                $avatar->move($uploadPath, $filename);
                
                // Update database
                $user->avatar = $filename;
                $user->save();

                Log::info('Avatar updated successfully');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Avatar berhasil diperbarui',
                    'data' => [
                        'avatar' => $filename,
                        'avatar_url' => url('/data_photo/' . $filename)
                    ]
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada file yang diunggah'
            ], 422);

        } catch (\Exception $e) {
            Log::error('Avatar update error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupload avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkExistingUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'no_telp' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $emailExists = User::where('email', $request->email)->exists();
            $phoneExists = User::where('no_telp', 'LIKE', '%' . $this->normalizePhoneNumber($request->no_telp))->exists();

            if ($emailExists || $phoneExists) {
                return response()->json([
                    'status' => 'exists',
                    'message' => $emailExists ? 'Email sudah terdaftar' : 'Nomor WhatsApp sudah terdaftar'
                ]);
            }

            return response()->json([
                'status' => 'available',
                'message' => 'Email dan nomor WhatsApp tersedia'
            ]);

        } catch (\Exception $e) {
            Log::error('Check existing user error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memeriksa data'
            ], 500);
        }
    }
}