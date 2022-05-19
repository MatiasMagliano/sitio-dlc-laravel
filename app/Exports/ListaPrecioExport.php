<?php

namespace App\Exports;
use App\Models\ListaPrecio;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ListaPrecioExport implements FromQuery, WithHeadings, WithMapping
{
    Use Exportable;
    protected $razonsocial;

    public function __construct(string $razonSocial)
    {
        $this->razonSocial = $razonSocial;
    }

    public function query()
    {
        return  ListaPrecio::select('codigoProv','presentacion','forma','costo')
                ->join('proveedors','lista_precios.proveedor_id','=','proveedors.id')
                ->join('lote_presentacion_producto', 'lista_precios.lpp_id','=','lote_presentacion_producto.id')
                ->join('productos','lote_presentacion_producto.producto_id','=','productos.id')
                ->join('presentacions','lote_presentacion_producto.presentacion_id','=','presentacions.id')
                ->where('proveedors.razon_social', '=', $this->razonSocial)  
                ->orderBy("lista_precios.codigoProv");
    }

    public function headings(): array
    {
        return ['Cod. Prov.','Forma','Producto','Costo'];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        //$fecha = Carbon::parse($row->fecha)->format('d/m/Y H:i:s');

        return [
            $row->codigoProv,
            $row->forma,
            $row->costo
        ];
    }
}



