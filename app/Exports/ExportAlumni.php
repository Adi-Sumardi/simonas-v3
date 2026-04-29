<?php

namespace App\Exports;

use App\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportAlumni implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Alumni::all();
    }
}
