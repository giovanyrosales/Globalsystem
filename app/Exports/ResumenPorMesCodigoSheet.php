<?php

namespace App\Exports; // ajusta al namespace real de tu proyecto

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Hoja "Totales por mes": el mismo resumen MES -> CÓDIGO -> TOTAL de la
 * segunda tabla del PDF, en formato de fila plana (una fila por código
 * dentro de cada mes, más una fila de SUBTOTAL al cierre de cada mes).
 */
class ResumenPorMesCodigoSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
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
            $fila['total'],
        ];
    }

    public function headings(): array
    {
        return ["MES", "CÓDIGO", "TOTAL"];
    }

    public function title(): string
    {
        return 'Totales por mes';
    }
}
