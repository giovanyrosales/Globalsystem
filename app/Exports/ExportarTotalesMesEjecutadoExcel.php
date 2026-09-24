<?php

namespace App\Exports; // ajusta al namespace real de tu proyecto

use App\Models\Meses;
use App\Models\ObjEspecifico;
use App\Models\P_Materiales;
use App\Models\P_PresupUnidad;
use App\Models\P_PresupUnidadDetalle;
use App\Models\P_ProyectosAprobados;
use App\Models\P_UnidadMedida;
use App\Models\P_AnioPresupuesto;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Exportador a Excel de "Totales por mes de ejecución" — misma lógica y
 * mismos datos que generarTotalesPdfPresupuestoMesEjecutado(), adaptado
 * a Excel con dos hojas:
 *
 *  - "Detalle": materiales + proyectos aprobados, ordenados por mes de
 *    ejecución (MES EJEC., COD. ESPECÍFICO, NOMBRE, UNI. MEDIDA,
 *    CANTIDAD, TOTAL).
 *  - "Totales por mes": el mismo resumen MES -> CÓDIGO -> TOTAL del PDF,
 *    en formato de fila plana (MES, CÓDIGO, TOTAL) con una fila de
 *    SUBTOTAL al cierre de cada mes.
 *
 * Mismos supuestos que en el PDF:
 *  - P_PresupUnidadDetalle y P_ProyectosAprobados tienen columna id_mes.
 *  - Meses.id está en orden calendario (1 = Enero ... 12 = Diciembre).
 *  - Un material puede repartirse en varios meses si distintas unidades
 *    presupuestarias lo ejecutan en meses diferentes, por eso se agrupa
 *    por (material, mes) y no solo por material.
 *
 * Todos los cálculos se hacen UNA sola vez en sheets() y se comparten
 * entre ambas hojas (sin N+1 queries).
 */
class ExportarTotalesMesEjecutadoExcel implements WithMultipleSheets
{
    protected $anio;

    public function __construct($anio)
    {
        $this->anio = $anio;
    }

    public function sheets(): array
    {
        $SIN_MES_ID     = 0;
        $SIN_MES_NOMBRE = 'SIN MES ASIGNADO';

        // ─────────────────────────────────────────────────────────
        // 1) Unidades presupuestarias aprobadas para el año
        // ─────────────────────────────────────────────────────────
        $arrayPresupuestoUni = P_PresupUnidad::where('id_anio', $this->anio)
            ->where('id_estado', 3) // SOLO APROBADOS
            ->orderBy('id', 'ASC')
            ->get();

        $pilaIdPresu = $arrayPresupuestoUni->pluck('id')->all();

        // ─────────────────────────────────────────────────────────
        // 2) Catálogos cargados UNA sola vez e indexados en memoria
        // ─────────────────────────────────────────────────────────
        $objEspecificosById = ObjEspecifico::all()->keyBy('id');
        $unidadMedidasById  = P_UnidadMedida::all()->keyBy('id');
        $materialesById     = P_Materiales::all()->keyBy('id');
        $mesesById          = Meses::orderBy('id', 'ASC')->get()->keyBy('id');

        // ─────────────────────────────────────────────────────────
        // 3) Proyectos aprobados de las unidades del año
        // ─────────────────────────────────────────────────────────
        $listadoProyectoAprobados = P_ProyectosAprobados::whereIn('id_presup_unidad', $pilaIdPresu)
            ->orderBy('descripcion', 'ASC')
            ->get();

        foreach ($listadoProyectoAprobados as $dd) {
            $infoObjeto = $objEspecificosById->get($dd->id_objespeci);

            $dd->codigoobj = $infoObjeto->codigo ?? null;

            $idMes         = $dd->id_mes ?: $SIN_MES_ID;
            $infoMes       = $idMes ? $mesesById->get($idMes) : null;
            $dd->idmesNorm = $idMes;
            $dd->nombreMes = $infoMes->nombre ?? $SIN_MES_NOMBRE;
        }

        // ─────────────────────────────────────────────────────────
        // 4) Detalle de presupuesto de TODAS las unidades del año,
        //    agrupado por (material, mes) — UNA sola consulta.
        // ─────────────────────────────────────────────────────────
        $detalles = P_PresupUnidadDetalle::whereIn('id_presup_unidad', $pilaIdPresu)->get();

        $agrupadoMaterialMes = $detalles->groupBy(function ($d) use ($SIN_MES_ID) {
            return $d->id_material . '-' . ($d->id_mes ?: $SIN_MES_ID);
        });

        $dataArray = [];

        foreach ($agrupadoMaterialMes as $grupo) {
            $primero = $grupo->first();
            $mm      = $materialesById->get($primero->id_material);

            if (!$mm) {
                continue;
            }

            $infoObj          = $objEspecificosById->get($mm->id_objespecifico);
            $infoUnidadMedida = $unidadMedidasById->get($mm->id_unidadmedida);

            $sumacantidad = 0;
            $multiFila    = 0;

            foreach ($grupo as $info) {
                // PERIODO SIEMPRE SERA 1 COMO MÍNIMO
                $multiFila    += ($info->cantidad * $info->precio) * $info->periodo;
                $sumacantidad += ($info->cantidad * $info->periodo);
            }

            if ($sumacantidad <= 0) {
                continue;
            }

            $idMes   = $primero->id_mes ?: $SIN_MES_ID;
            $infoMes = $idMes ? $mesesById->get($idMes) : null;

            $dataArray[] = [
                'idmes'        => $idMes,
                'mes'          => $infoMes->nombre ?? $SIN_MES_NOMBRE,
                'codigo'       => $infoObj->codigo ?? null,
                'descripcion'  => $mm->descripcion,
                'unidadmedida' => $infoUnidadMedida->nombre ?? '',
                'cantidad'     => $sumacantidad,
                'total'        => $multiFila,
            ];
        }

        // Proyectos aprobados también entran, con su propio mes
        foreach ($listadoProyectoAprobados as $lpa) {
            $dataArray[] = [
                'idmes'        => $lpa->idmesNorm,
                'mes'          => $lpa->nombreMes,
                'codigo'       => $lpa->codigoobj,
                'descripcion'  => $lpa->descripcion,
                'unidadmedida' => 'PROYECTO',
                'cantidad'     => 1,
                'total'        => (float) $lpa->costo,
            ];
        }

        // Orden: mes de ejecución, luego código, luego descripción.
        // "SIN MES ASIGNADO" (idmes = 0) queda al final.
        usort($dataArray, function ($a, $b) {
            $aMes = $a['idmes'] == 0 ? PHP_INT_MAX : $a['idmes'];
            $bMes = $b['idmes'] == 0 ? PHP_INT_MAX : $b['idmes'];

            return $aMes <=> $bMes
                ?: $a['codigo'] <=> $b['codigo']
                    ?: $a['descripcion'] <=> $b['descripcion'];
        });

        // ─────────────────────────────────────────────────────────
        // 5) Resumen: MES -> CÓDIGO -> TOTAL (con subtotal por mes),
        //    aplanado a filas para la hoja de Excel.
        // ─────────────────────────────────────────────────────────
        $resumenPlano = [];

        collect($dataArray)
            ->groupBy('idmes')
            ->sortBy(function ($filasDelMes, $idmes) {
                return $idmes == 0 ? PHP_INT_MAX : $idmes;
            })
            ->each(function ($filasDelMes) use (&$resumenPlano) {
                $nombreMes = $filasDelMes->first()['mes'];

                $porCodigo = $filasDelMes
                    ->groupBy('codigo')
                    ->map(function ($filasDelCodigo, $codigo) {
                        return [
                            'codigo' => $codigo,
                            'total'  => $filasDelCodigo->sum('total'),
                        ];
                    })
                    ->sortBy('codigo')
                    ->values();

                foreach ($porCodigo as $rc) {
                    $resumenPlano[] = [
                        'mes'    => $nombreMes,
                        'codigo' => $rc['codigo'],
                        'total'  => $rc['total'],
                    ];
                }

                $resumenPlano[] = [
                    'mes'    => $nombreMes,
                    'codigo' => 'SUBTOTAL',
                    'total'  => $filasDelMes->sum('total'),
                ];
            });

        // ─────────────────────────────────────────────────────────
        // 6) Hojas del Excel
        // ─────────────────────────────────────────────────────────
        return [
            'Detalle'         => new DetalleTotalesMesEjecutadoSheet(collect($dataArray)),
            'Totales por mes' => new ResumenPorMesCodigoSheet(collect($resumenPlano)),
        ];
    }
}
