<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Akademik;
use App\Models\Karakter;
use App\Models\Kreatif;
use App\Models\Leadership;
use Illuminate\Http\Response;

class FileController extends Controller
{
    private const TABLES = [
        'akademiks'   => Akademik::class,
        'leaderships' => Leadership::class,
        'karakters'   => Karakter::class,
        'kreatifs'    => Kreatif::class,
    ];

    /**
     * Stream bukti-kegiatan (foto) yang tersimpan sebagai BLOB di database.
     * Hanya bisa diakses oleh pemilik record atau role admin/mentor/super.
     */
    public function show(string $table, int $id): Response
    {
        abort_unless(isset(self::TABLES[$table]), 404);

        $record = self::TABLES[$table]::findOrFail($id);

        $user = auth()->user();
        $isOwner = $record->user_id === $user->id;
        $isStaff = in_array($user->role, ['super', 'admin', 'mentor'], true);
        abort_unless($isOwner || $isStaff, 403);

        $data = $record->file_data;
        abort_if(! $data, 404);

        return response($data, 200, [
            'Content-Type'        => $record->file_mime ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . addslashes($record->file ?? 'file') . '"',
            'Cache-Control'       => 'private, max-age=86400',
        ]);
    }
}
