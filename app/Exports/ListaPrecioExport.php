<?php

namespace App\Exports;

use App\Models\ListaPrecio;
use Maatwebsite\Excel\Concerns\FromCollection;


class ListaPrecioExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ListaPrecio::all();
    }
}
