<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Simpan file upload (foto bukti/kehadiran) sebagai BLOB langsung di database,
 * bukan di disk. Dipakai bareng dengan kolom `file`, `file_data`, `file_mime`,
 * `file_size` pada tabel terkait.
 */
trait HandlesBlobUpload
{
    protected const ALLOWED_UPLOAD_RULE = 'file|mimes:jpg,jpeg,png,pdf|max:20480'; // 20MB mentah, sebelum dikompres

    protected function binaryExpr(string $binary): \Illuminate\Database\Query\Expression
    {
        return DB::raw("decode('" . bin2hex($binary) . "', 'hex')");
    }

    /**
     * Kompres gambar (resize max 1600px, re-encode JPEG kualitas 75) sebelum
     * disimpan sebagai BLOB. PDF disimpan apa adanya.
     *
     * @return array{data:string,mime:string,size:int,name:string}
     */
    protected function processUpload(UploadedFile $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            $manager = new \Intervention\Image\ImageManager(['driver' => 'gd']);
            $image = $manager->make($file->getRealPath());
            $image->resize(1600, 1600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $encoded = (string) $image->encode('jpg', 75);

            return [
                'data' => $encoded,
                'mime' => 'image/jpeg',
                'size' => strlen($encoded),
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg',
            ];
        }

        $contents = file_get_contents($file->getRealPath());

        return [
            'data' => $contents,
            'mime' => 'application/pdf',
            'size' => strlen($contents),
            'name' => $file->getClientOriginalName(),
        ];
    }
}
