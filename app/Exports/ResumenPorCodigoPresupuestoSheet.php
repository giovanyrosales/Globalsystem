<?php

namespace App\Exports; // ajusta al namespace real de tu proyecto

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Hoja "Totales por código": el mismo resumen agrupado por código
 * presupuestario que se agregó a la segunda tabla del PDF
 * (CÓDIGO, TOTAL), recibido ya calculado y agrupado.
 */
class ResumenPorCodigoPresupuestoSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ["CÓDIGO", "TOTAL"];
    }

    public function title(): string
    {
        return 'Totales por código';
    }
}
