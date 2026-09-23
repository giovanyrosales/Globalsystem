<?php

namespace App\Exports; // ajusta al namespace real de tu proyecto

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Hoja "Detalle": mismo listado plano que ya tenías
 * (COD. ESPECIFICO, NOMBRE, CANTIDAD, TOTAL), recibido ya calculado
 * desde ExportarTotalesExcel::sheets() para no repetir consultas.
 */
class DetalleTotalesPresupuestoSheet implements FromCollection, WithHeadings, WithTitle
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
        return ["COD. ESPECIFICO", "NOMBRE", "CANTIDAD", "TOTAL"];
    }

    public function title(): string
    {
        return 'Detalle';
    }
}
