<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Akademik;
use App\Models\Karakter;
use App\Models\KegiatanAttendance;
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
        'kegiatan_attendances' => KegiatanAttendance::class,
    ];

    // Kolom BLOB non-default per tabel (default: file/file_data/file_mime/file_size).
    // Beberapa tabel punya lebih dari satu slot foto (mis. selfie & lokasi).
    private const FIELD_PREFIXES = [
        'kegiatan_attendances' => ['selfie', 'lokasi'],
    ];

    /**
     * Stream bukti-kegiatan (foto) yang tersimpan sebagai BLOB di database.
     * Hanya bisa diakses oleh pemilik record atau role admin/mentor/super.
     */
    public function show(string $table, int $id, ?string $slot = null): Response
    {
        abort_unless(isset(self::TABLES[$table]), 404);

        $record = self::TABLES[$table]::findOrFail($id);

        $user = auth()->user();
        $isOwner = $record->user_id === $user->id;
        $isStaff = in_array($user->role, ['super', 'admin', 'mentor'], true);
        abort_unless($isOwner || $isStaff, 403);

        $prefixes = self::FIELD_PREFIXES[$table] ?? null;
        if ($prefixes) {
            abort_unless($slot && in_array($slot, $prefixes, true), 404);
            $fileField = "file_{$slot}";
            $dataField = "file_{$slot}_data";
            $mimeField = "file_{$slot}_mime";
        } else {
            $fileField = 'file';
            $dataField = 'file_data';
            $mimeField = 'file_mime';
        }

        $data = $record->{$dataField};
        abort_if(! $data, 404);

        return response($data, 200, [
            'Content-Type'        => $record->{$mimeField} ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . addslashes($record->{$fileField} ?? 'file') . '"',
            'Cache-Control'       => 'private, max-age=86400',
        ]);
    }
}
