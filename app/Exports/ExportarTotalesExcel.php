<?php

namespace App\Exports;

use App\Models\ObjEspecifico;
use App\Models\P_Materiales;
use App\Models\P_PresupUnidad;
use App\Models\P_PresupUnidadDetalle;
use App\Models\P_ProyectosAprobados;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/**
* Exportador de "Totales de presupuesto" a Excel.
 *
 * Mismo dato/lógica que el reporte en PDF, adaptado a Excel:
 *  - Hoja "Detalle": el mismo listado plano de materiales + proyectos
*    aprobados que ya tenías (COD. ESPECIFICO, NOMBRE, CANTIDAD, TOTAL).
 *  - Hoja "Totales por código": el mismo resumen agrupado por código
*    presupuestario que se agregó al PDF (CÓDIGO, TOTAL).
 *
 * OPTIMIZACIONES / CORRECCIONES respecto a la versión original:
 *
 *  1. Se eliminaron los N+1 queries: ObjEspecifico se consulta UNA sola
*     vez y se indexa en memoria (keyBy) en vez de hacer ->where()->first()
*     dentro de cada foreach (por cada proyecto y por cada material).
 *
 *  2. P_PresupUnidadDetalle se consulta UNA sola vez con whereIn() y se
*     agrupa por id_material, en vez de una consulta por cada combinación
*     material x unidad presupuestaria (antes: materiales * unidades
    *     presupuestarias consultas).
 *
 *  3. BUG CORREGIDO: $listadoProyectoAprobados traía TODOS los proyectos
*     aprobados de TODOS los años (no filtraba por año/unidades
*     presupuestarias del año solicitado). Ahora se filtra con
*     whereIn('id_presup_unidad', $pilaIdPresu), igual que en el PDF.
 *
 *  4. Todos los cálculos pesados se hacen UNA sola vez en sheets() y se
*     comparten entre ambas hojas (no se repite el trabajo por hoja).
 */
class ExportarTotalesExcel implements WithMultipleSheets
{
    protected $anio;

    public function __construct($anio)
    {
        $this->anio = $anio;
    }

    public function sheets(): array
    {
        // ─────────────────────────────────────────────────────────
        // 1) Unidades presupuestarias aprobadas para el año
        // ─────────────────────────────────────────────────────────
        $arrayPresupuestoUni = P_PresupUnidad::where('id_anio', $this->anio)
            ->where('id_estado', 3) // SOLO APROBADOS
            ->orderBy('id', 'ASC')
            ->get();

        $pilaIdPresu = $arrayPresupuestoUni->pluck('id')->all();

        // ─────────────────────────────────────────────────────────
        // 2) Proyectos aprobados de ESAS unidades (antes traía todos)
        // ─────────────────────────────────────────────────────────
        $listadoProyectoAprobados = P_ProyectosAprobados::whereIn('id_presup_unidad', $pilaIdPresu)
            ->orderBy('descripcion', 'ASC')
            ->get();

        // ─────────────────────────────────────────────────────────
        // 3) Catálogos cargados UNA sola vez e indexados en memoria
        // ─────────────────────────────────────────────────────────
        $objEspecificosById = ObjEspecifico::all()->keyBy('id');

        foreach ($listadoProyectoAprobados as $dd) {
            $infoObjeto  = $objEspecificosById->get($dd->id_objespeci);
            $infoFuenteR = $objEspecificosById->get($dd->id_fuenter);
            $infoLinea   = $objEspecificosById->get($dd->id_lineatrabajo);
            $infoArea    = $objEspecificosById->get($dd->id_areagestion);

            $dd->codigoobj     = $infoObjeto->codigo ?? null;
            $dd->objeto        = $infoObjeto ? ($infoObjeto->codigo . " - " . $infoObjeto->nombre) : '';
            $dd->fuenterecurso = $infoFuenteR ? ($infoFuenteR->codigo . " - " . $infoFuenteR->nombre) : '';
            $dd->lineatrabajo  = $infoLinea ? ($infoLinea->codigo . " - " . $infoLinea->nombre) : '';
            $dd->areagestion   = $infoArea ? ($infoArea->codigo . " - " . $infoArea->nombre) : '';

            $dd->costoFormat = $dd->costo;
        }

        // ─────────────────────────────────────────────────────────
        // 4) Totales por material (sin consultar la BD dentro del loop)
        // ─────────────────────────────────────────────────────────
        $materiales = P_Materiales::orderBy('descripcion')->get();

        $detallesByMaterial = P_PresupUnidadDetalle::whereIn('id_presup_unidad', $pilaIdPresu)
            ->get()
            ->groupBy('id_material');

        $dataArray = [];

        foreach ($materiales as $mm) {
            $infoObj = $objEspecificosById->get($mm->id_objespecifico);

            $detalles     = $detallesByMaterial->get($mm->id, collect());
            $sumacantidad = 0;
            $multiFila    = 0;

            foreach ($detalles as $info) {
                // PERIODO SIEMPRE SERA 1 COMO MÍNIMO
                $multiFila    += ($info->cantidad * $info->precio) * $info->periodo;
                $sumacantidad += ($info->cantidad * $info->periodo);
            }

            if ($sumacantidad > 0) {
                $dataArray[] = [
                    'codigo'       => $infoObj->codigo ?? null,
                    'descripcion'  => $mm->descripcion,
                    'sumacantidad' => $sumacantidad,
                    'total'        => $multiFila,
                ];
            }
        }

        foreach ($listadoProyectoAprobados as $lpa) {
            $dataArray[] = [
                'codigo'       => $lpa->codigoobj,
                'descripcion'  => $lpa->descripcion,
                'sumacantidad' => 'PROYECTO',
                'total'        => $lpa->costoFormat,
            ];
        }

        usort($dataArray, function ($a, $b) {
            return $a['codigo'] <=> $b['codigo'] ?: $a['descripcion'] <=> $b['descripcion'];
        });

        // ─────────────────────────────────────────────────────────
        // 5) Resumen por código presupuestario (misma tabla que se
        //    agregó al PDF): suma el TOTAL de todas las filas que
        //    comparten el mismo código (incluye materiales y proyectos).
        // ─────────────────────────────────────────────────────────
        $resumenPorCodigo = collect($dataArray)
            ->groupBy('codigo')
            ->map(function ($filas, $codigo) {
                return [
                    'codigo' => $codigo,
                    'total'  => $filas->sum('total'),
                ];
            })
            ->sortBy('codigo')
            ->values();

        // ─────────────────────────────────────────────────────────
        // 6) Hojas del Excel
        // ─────────────────────────────────────────────────────────
        return [
            'Detalle'            => new DetalleTotalesPresupuestoSheet(collect($dataArray)),
            'Totales por código' => new ResumenPorCodigoPresupuestoSheet($resumenPorCodigo),
        ];
    }
}
