<?php

namespace App\Exports; // ajusta al namespace real de tu proyecto

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Hoja "Detalle": materiales + proyectos aprobados ordenados por mes de
 * ejecución. Recibe los datos ya calculados desde
 * ExportarTotalesMesEjecutadoExcel::sheets() para no repetir consultas.
 */
class DetalleTotalesMesEjecutadoSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
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

    public function map($fila): array
    {
        return [
            $fila['mes'],
            $fila['codigo'],
            $fila['descripcion'],
            $fila['unidadmedida'],
            $fila['cantidad'],
            $fila['total'],
        ];
    }

    public function headings(): array
    {
        return ["MES EJEC.", "COD. ESPECÍFICO", "NOMBRE", "UNI. MEDIDA", "CANTIDAD", "TOTAL"];
    }

    public function title(): string
    {
        return 'Detalle';
    }
}
