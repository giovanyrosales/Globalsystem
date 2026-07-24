@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
@stop

<style>
    .modal-xl {
        max-width: 90% !important;
    }
</style>

<div class="content-wrapper" style="display: none" id="divcontenedor">

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Crear Presupuesto</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <form class="form-horizontal">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label style="margin: 8px">Año</label>
                                    <div style="margin-left: 6px" class="col-sm-3">
                                        <select class="form-control" id="select-anio">
                                            @foreach($listado as $item)
                                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <section class="content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="tablaDatatable"></div>
                                    </div>
                                </div>
                            </section>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== MODAL BUSCADOR DE MATERIAL ==================== --}}
    <div class="modal fade" id="modalBuscador">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Buscar Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo">
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <div class="input-group mb-3" style="width: 40%;">
                                                <input type="text" class="form-control" autocomplete="off"
                                                       maxlength="100" id="nombre-material"
                                                       placeholder="Nombre del Material a Buscar...">
                                                <span class="input-group-append">
                                                    <button type="button" class="btn btn-info btn-flat"
                                                            onclick="buscarMaterial()">BUSCAR</button>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 20px">
                                            <table class="table" id="matriz-material" data-toggle="table">
                                                <thead>
                                                <tr>
                                                    <th style="width: 20%">RUBRO</th>
                                                    <th style="width: 20%">CUENTA</th>
                                                    <th style="width: 20%">OBJETO ESPE.</th>
                                                    <th style="width: 40%">MATERIAL</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL NUEVO MATERIAL ==================== --}}
    <div class="modal fade" id="modalNuevoMaterial">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Solicitud de Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo-material">
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <h5 style="font-weight: bold; text-align: center">
                                                <i class="fas fa-info"></i>
                                                Se deberá Notificar a Jefatura de Presupuesto que se Necesita el Material que esta Solicitando
                                            </h5>
                                        </div>

                                        <hr>

                                        <div class="form-group" style="margin-top: 15px">
                                            <label>Nombre del Material</label>
                                            <input type="text" class="form-control" autocomplete="off"
                                                   maxlength="300" id="material-nuevo" placeholder="Nombre">
                                        </div>

                                        <div class="form-group">
                                            <label>Costo Estimado:</label>
                                            <input type="number" class="form-control" autocomplete="off"
                                                   id="costo-nuevo" placeholder="0.00">
                                        </div>

                                        <div class="form-group">
                                            <label>Cantidad:</label>
                                            <input type="number" class="form-control" autocomplete="off"
                                                   id="cantidad-nuevo" placeholder="0">
                                        </div>

                                        <div class="form-group">
                                            <label>Periodo (Mínimo 1):</label>
                                            <input type="number" class="form-control" autocomplete="off"
                                                   value="1" id="periodo-nuevo" placeholder="0">
                                        </div>

                                        <div class="form-group">
                                            <label>Unidad de Medida</label>
                                            <select class="form-control" id="select-medida-nuevo">
                                                @foreach($unidad as $sel)
                                                    <option value="{{ $sel->id }}">{{ $sel->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- SELECT MES DE EJECUCIÓN --}}
                                        <div class="form-group">
                                            <label>Mes de Ejecución</label>
                                            <select class="form-control" id="select-mes-nuevo">
                                                <option value="">-- Seleccione un Mes --</option>
                                                @foreach($arrayMeses as $mes)
                                                    <option value="{{ $mes->id }}">{{ $mes->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="verificarNuevoMaterial()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL NUEVO PROYECTO ==================== --}}
    <div class="modal fade" id="modalNuevoProyecto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Solicitud de Proyecto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo-proyecto">
                        <div class="card-body">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group" style="margin-top: 15px">
                                            <label>Descripción</label>
                                            <input type="text" class="form-control" autocomplete="off"
                                                   maxlength="300" id="proyecto-descripcion-nuevo" placeholder="Nombre">
                                        </div>

                                        <div class="form-group">
                                            <label>Monto ($)</label>
                                            <input type="number" class="form-control" autocomplete="off"
                                                   id="proyecto-costo-nuevo">
                                        </div>

                                        {{-- SELECT MES DE EJECUCIÓN --}}
                                        <div class="form-group">
                                            <label>Mes de Ejecución</label>
                                            <select class="form-control" id="select-mes-proyecto-nuevo">
                                                <option value="">-- Seleccione un Mes --</option>
                                                @foreach($arrayMeses as $mes)
                                                    <option value="{{ $mes->id }}">{{ $mes->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="verificarNuevoProyecto()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            openLoading();

            var ruta = "{{ URL::to('/admin/p/contenedor/nuevo/presupuesto') }}";
            $('#tablaDatatable').load(ruta);

            $('#select-medida-nuevo').select2({
                theme: "bootstrap-5",
                dropdownParent: $('#modalNuevoMaterial'),
                language: { noResults: function () { return "Búsqueda no encontrada"; } },
            });

            $('#select-mes-nuevo').select2({
                theme: "bootstrap-5",
                dropdownParent: $('#modalNuevoMaterial'),
                language: { noResults: function () { return "Búsqueda no encontrada"; } },
            });

            $('#select-mes-proyecto-nuevo').select2({
                theme: "bootstrap-5",
                dropdownParent: $('#modalNuevoProyecto'),
                language: { noResults: function () { return "Búsqueda no encontrada"; } },
            });

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        // ===================== UTILIDADES =====================

        function multiplicar(e) {
            var table    = e.parentNode.parentNode;
            var costo    = table.cells[3].children[0];  // col 0=# 1=desc 2=um 3=costo
            var unidades = table.cells[4].children[0];
            var periodo  = table.cells[5].children[0];
            // col 6 = Mes Ejec., col 7 = Total
            var total    = table.cells[7].children[0];

            var boolUnidades = false;
            var boolPeriodo  = false;

            var reglaNumeroEntero     = /^[0-9]\d*$/;
            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

            if (unidades.value.length > 0) {
                if (!unidades.value.match(reglaNumeroDosDecimal)) {
                    modalMensaje('Error', 'Unidades debe ser número Decimal Positivo. Solo se permite 2 Decimales');
                    return;
                }
                if (unidades.value <= 0) {
                    modalMensaje('Error', 'Unidades no debe ser negativo o cero');
                    return;
                }
                if (unidades.value > 1000000) {
                    modalMensaje('Error', 'Unidades máximo 1 millón');
                    return;
                }
                boolUnidades = true;
            }

            if (periodo.value.length > 0) {
                if (!periodo.value.match(reglaNumeroEntero)) {
                    modalMensaje('Error', 'Periodo debe ser número entero');
                    return;
                }
                if (periodo.value <= 0) {
                    modalMensaje('Error', 'Periodo no debe ser negativo o cero');
                    return;
                }
                if (periodo.value > 1000000) {
                    modalMensaje('Error', 'Periodo máximo 1 millón');
                    return;
                }
                boolPeriodo = true;
            }

            if (boolUnidades && boolPeriodo) {
                var valTotal = (costo.value * unidades.value) * periodo.value;
                total.value  = '$' + Number(valTotal).toFixed(2);
            } else {
                total.value = '';
            }
        }

        function borrarFila(elemento) {
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
        }

        function borrarFilaProyecto(elemento) {
            var tabla = elemento.parentNode.parentNode;
            tabla.parentNode.removeChild(tabla);
        }

        function modalMensaje(titulo, mensaje) {
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Aceptar'
            });
        }

        // ===================== CREAR PRESUPUESTO =====================

        function verificar() {
            var sel  = document.getElementById("select-anio");
            var anio = sel.options[sel.selectedIndex].text;

            Swal.fire({
                title: 'Crear Presupuesto?',
                text: "Se creara Presupuesto para el Año " + anio + ", se podrá modificar en la Sección Editar",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Crear'
            }).then((result) => {
                if (result.isConfirmed) {
                    crearPresupuesto();
                }
            });
        }

        function crearPresupuesto() {

            var anio = document.getElementById('select-anio').value;

            if (anio === '') {
                toastr.error('Año de presupuesto es requerido');
                return;
            }

            // Leer arrays del presupuesto base
            // idMaterial[] tiene data-fila y data-desc para mensajes descriptivos
            var idMaterial  = $("input[name='idMaterial[]']").map(function () { return $(this).val(); }).get();
            var filaDesc    = $("input[name='idMaterial[]']").map(function () { return '#' + $(this).attr("data-fila") + ' — ' + $(this).attr("data-desc"); }).get();
            var unidades    = $("input[name='unidades[]']").map(function () { return $(this).val(); }).get();
            var periodo     = $("input[name='periodo[]']").map(function () { return $(this).val(); }).get();

            var reglaNumeroEntero     = /^[0-9]\d*$/;
            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

            // Validar unidades — mensaje descriptivo con código + nombre
            for (var a = 0; a < unidades.length; a++) {
                var datoUnidades = unidades[a];
                if (datoUnidades.length > 0) {
                    if (!datoUnidades.match(reglaNumeroDosDecimal)) {
                        modalMensaje('Presupuesto Base', filaDesc[a] + '\nUnidades debe ser Decimal Positivo. Solo se permite 2 Decimales');
                        return;
                    }
                    if (datoUnidades <= 0) {
                        modalMensaje('Presupuesto Base', filaDesc[a] + '\nUnidades no debe ser negativos o cero');
                        return;
                    }
                    if (datoUnidades > 1000000) {
                        modalMensaje('Presupuesto Base', filaDesc[a] + '\nUnidades máximo 1 millón');
                        return;
                    }
                }
            }

            // Validar periodos — mensaje descriptivo
            for (var b = 0; b < periodo.length; b++) {
                var datoPeriodo = periodo[b];
                if (datoPeriodo.length > 0) {
                    if (!datoPeriodo.match(reglaNumeroEntero)) {
                        modalMensaje('Presupuesto Base', filaDesc[b] + '\nPeriodo ingresado no es válido');
                        return;
                    }
                    if (datoPeriodo <= 0) {
                        modalMensaje('Presupuesto Base', filaDesc[b] + '\nPeriodo no debe ser negativos o cero');
                        return;
                    }
                    if (datoPeriodo > 1000000) {
                        modalMensaje('Presupuesto Base', filaDesc[b] + '\nPeriodo máximo 1 millón');
                        return;
                    }
                }
            }

            let formData = new FormData();

            // -------- NUEVOS MATERIALES --------
            var nRegistro = $('#matrizMateriales >tbody >tr').length;
            if (nRegistro > 0) {

                var descripcion      = $("input[name='descripcionfila[]']").map(function () { return $(this).val(); }).get();
                var costoextra       = $("input[name='costoextrafila[]']").map(function () { return $(this).val(); }).get();
                var cantidadextra    = $("input[name='cantidadextrafila[]']").map(function () { return $(this).val(); }).get();
                var periodoextra     = $("input[name='periodoextrafila[]']").map(function () { return $(this).val(); }).get();
                var unidadmedidafila = $("input[name='unidadmedidafila[]']").map(function () { return $(this).attr("data-infomedida"); }).get();
                var mesextrafila     = $("input[name='mesextrafila[]']").map(function () { return $(this).attr("data-infomes"); }).get();

                for (var c = 0; c < descripcion.length; c++) {
                    var refMat = 'Fila #' + (c + 1) + ' — ' + descripcion[c];
                    if (unidadmedidafila[c] == 0) {
                        modalMensaje('Nuevos Materiales', refMat + '\nNo se encuentra la Unidad de Medida. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (mesextrafila[c] == 0 || mesextrafila[c] === '' || mesextrafila[c] === undefined) {
                        modalMensaje('Nuevos Materiales', refMat + '\nNo se encuentra el Mes de Ejecución. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (descripcion[c] === '') {
                        modalMensaje('Nuevos Materiales', refMat + '\nFalta la descripción del material. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (descripcion[c].length > 300) {
                        modalMensaje('Nuevos Materiales', refMat + '\nLa descripción supera los 300 caracteres. Borrar fila y agregar de nuevo');
                        return;
                    }
                }

                for (var d = 0; d < costoextra.length; d++) {
                    var refMatD = 'Fila #' + (d + 1) + ' — ' + descripcion[d];
                    var datoCostoExtra = costoextra[d];
                    if (datoCostoExtra === '') {
                        modalMensaje('Nuevos Materiales', refMatD + '\nEl Costo es requerido. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (!datoCostoExtra.match(reglaNumeroDosDecimal)) {
                        modalMensaje('Nuevos Materiales', refMatD + '\nEl Costo debe ser Número Decimal Positivo y 2 Decimales Máximo. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (datoCostoExtra <= 0) {
                        modalMensaje('Nuevos Materiales', refMatD + '\nEl Costo no debe ser Negativo o Cero. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (datoCostoExtra > 1000000) {
                        modalMensaje('Nuevos Materiales', refMatD + '\nEl Costo no debe superar 1 millón. Borrar fila y agregar de nuevo');
                        return;
                    }
                }

                for (var t = 0; t < cantidadextra.length; t++) {
                    var refMatT = 'Fila #' + (t + 1) + ' — ' + descripcion[t];
                    var datoCantidadExtra = cantidadextra[t];
                    if (datoCantidadExtra === '') {
                        modalMensaje('Nuevos Materiales', refMatT + '\nLa Cantidad es Requerida. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (!datoCantidadExtra.match(reglaNumeroDosDecimal)) {
                        modalMensaje('Nuevos Materiales', refMatT + '\nLa Cantidad debe ser Número Decimal Positivo y Máximo 2 Decimales. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (datoCantidadExtra <= 0) {
                        modalMensaje('Nuevos Materiales', refMatT + '\nLa Cantidad no debe ser Número negativo o Cero. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (datoCantidadExtra > 1000000) {
                        modalMensaje('Nuevos Materiales', refMatT + '\nLa Cantidad no debe superar 1 millón. Borrar fila y agregar de nuevo');
                        return;
                    }
                }

                for (var e = 0; e < periodoextra.length; e++) {
                    var refMatE = 'Fila #' + (e + 1) + ' — ' + descripcion[e];
                    var datoPeriodoExtra = periodoextra[e];
                    if (datoPeriodoExtra === '') {
                        modalMensaje('Nuevos Materiales', refMatE + '\nEl Periodo es Requerido. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (!datoPeriodoExtra.match(reglaNumeroEntero)) {
                        modalMensaje('Nuevos Materiales', refMatE + '\nEl Periodo debe ser Número Entero. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (datoPeriodoExtra <= 0) {
                        modalMensaje('Nuevos Materiales', refMatE + '\nEl Periodo no debe ser Número Negativo o Cero. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (datoPeriodoExtra > 1000000) {
                        modalMensaje('Nuevos Materiales', refMatE + '\nEl Periodo debe tener máximo 1 millón. Borrar fila y agregar de nuevo');
                        return;
                    }
                }

                for (var p = 0; p < descripcion.length; p++) {
                    formData.append('descripcionfila[]',   descripcion[p]);
                    formData.append('costoextrafila[]',    costoextra[p]);
                    formData.append('cantidadextrafila[]', cantidadextra[p]);
                    formData.append('periodoextrafila[]',  periodoextra[p]);
                    formData.append('unidadmedida[]',      unidadmedidafila[p]);
                    formData.append('mesextrafila[]',      mesextrafila[p]);
                }
            }

            // -------- NUEVOS PROYECTOS --------
            var nRegistroProyecto = $('#matrizProyectos >tbody >tr').length;
            if (nRegistroProyecto > 0) {

                var descripcionProyecto = $("input[name='proyectodescripcionfila[]']").map(function () { return $(this).val(); }).get();
                var costoProyecto       = $("input[name='proyectocostoextrafila[]']").map(function () { return $(this).val(); }).get();
                var mesProyectofila     = $("input[name='mesfilaproyecto[]']").map(function () { return $(this).attr("data-infomes"); }).get();

                for (var pp = 0; pp < descripcionProyecto.length; pp++) {
                    var refPro = 'Fila #' + (pp + 1) + ' — ' + descripcionProyecto[pp];
                    if (descripcionProyecto[pp] === '') {
                        modalMensaje('Nuevo Proyecto', refPro + '\nFalta la descripción. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (descripcionProyecto[pp].length > 300) {
                        modalMensaje('Nuevo Proyecto', refPro + '\nLa descripción supera los 300 caracteres. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (mesProyectofila[pp] == 0 || mesProyectofila[pp] === '' || mesProyectofila[pp] === undefined) {
                        modalMensaje('Nuevo Proyecto', refPro + '\nEl Mes de Ejecución es Requerido. Borrar fila y agregar de nuevo');
                        return;
                    }
                }

                for (var pc = 0; pc < costoProyecto.length; pc++) {
                    var refProC = 'Fila #' + (pc + 1) + ' — ' + descripcionProyecto[pc];
                    var datoCostoExtraPro = costoProyecto[pc];
                    if (datoCostoExtraPro === '') {
                        modalMensaje('Nuevo Proyecto', refProC + '\nEl Costo es requerido. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (!datoCostoExtraPro.match(reglaNumeroDosDecimal)) {
                        modalMensaje('Nuevo Proyecto', refProC + '\nEl Costo debe ser Número Decimal Positivo y 2 Decimales Máximo. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (datoCostoExtraPro <= 0) {
                        modalMensaje('Nuevo Proyecto', refProC + '\nEl Costo no debe ser Negativo o Cero. Borrar fila y agregar de nuevo');
                        return;
                    }
                    if (datoCostoExtraPro > 9000000) {
                        modalMensaje('Nuevo Proyecto', refProC + '\nEl Costo no debe superar 9 millones. Borrar fila y agregar de nuevo');
                        return;
                    }
                }

                for (var pro = 0; pro < descripcionProyecto.length; pro++) {
                    formData.append('descripcionfilaproyecto[]', descripcionProyecto[pro]);
                    formData.append('costoextrafilaproyecto[]',  costoProyecto[pro]);
                    formData.append('mesfilaproyecto[]',         mesProyectofila[pro]);
                }
            }

            // -------- MATERIALES DEL PRESUPUESTO BASE --------
            // mes[] tiene data-fila y data-desc igual que idMaterial[] para mensajes descriptivos
            var mesBase     = $("select[name='mes[]']").map(function () { return $(this).val(); }).get();
            var mesBaseDesc = $("select[name='mes[]']").map(function () { return '#' + $(this).attr("data-fila") + ' — ' + $(this).attr("data-desc"); }).get();

            for (var z = 0; z < unidades.length; z++) {
                if (unidades[z].length > 0 && periodo[z].length > 0) {

                    if (!mesBase[z] || mesBase[z] === '') {
                        modalMensaje('Presupuesto Base', mesBaseDesc[z] + '\nDebe seleccionar el Mes de Ejecución');
                        return;
                    }

                    formData.append('idmaterial[]', idMaterial[z]);
                    formData.append('unidades[]',   unidades[z]);
                    formData.append('periodo[]',    periodo[z]);
                    formData.append('mesbase[]',    mesBase[z]);
                }
            }

            formData.append('anio', anio);

            axios.post(url + '/p/crear/presupuesto/unidad', formData, {})
                .then((response) => {

                    if (response.data.success === 1) {
                        Swal.fire({
                            title: 'Presupuesto ya habia sido creado',
                            text: "Puede modificarlo en la sección Editar",
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            closeOnClickOutside: false,
                            allowOutsideClick: false,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) { location.reload(); }
                        });
                    } else if (response.data.success === 2) {
                        Swal.fire({
                            title: 'Presupuesto creado',
                            text: "Puede modificarlo en la sección Editar",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            closeOnClickOutside: false,
                            allowOutsideClick: false,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) { location.reload(); }
                        });
                    } else {
                        toastr.error('error al crear presupuesto');
                    }

                })
                .catch((error) => {
                    toastr.error('error al crear presupuesto');
                    closeLoading();
                });
        }

        // ===================== BUSCADOR DE MATERIAL =====================

        function modalBuscarMaterial() {
            $('#modalBuscador').modal('show');
        }

        function buscarMaterial() {
            var nombre = document.getElementById("nombre-material").value;

            if (nombre === '') { toastr.error('Nombre Material es Requerido'); return; }
            if (nombre.length < 3) { toastr.error('Mínimo 3 Caracteres para Buscar'); return; }

            openLoading();
            $("#matriz-material tbody tr").remove();

            axios.post(url + '/p/buscar/material/presupuesto', { 'texto': nombre })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        if (response.data.conteo) {
                            let infodetalle = response.data.info;
                            for (var i = 0; i < infodetalle.length; i++) {
                                var markup = "<tr>" +
                                    "<td><input class='form-control' value='" + infodetalle[i].rubro + "' disabled type='text'></td>" +
                                    "<td><input class='form-control' value='" + infodetalle[i].cuenta + "' disabled type='text'></td>" +
                                    "<td><input class='form-control' value='" + infodetalle[i].objeto + "' disabled type='text'></td>" +
                                    "<td><input class='form-control' style='background-color: #b0f2c2' value='" + infodetalle[i].descripcion + "' disabled type='text'></td>" +
                                    "</tr>";
                                $("#matriz-material tbody").append(markup);
                            }
                        } else {
                            toastr.info('Material No Encontrado');
                        }
                    } else {
                        toastr.error('Error al buscar');
                    }
                })
                .catch(() => { toastr.error('Error al buscar'); });
        }

        // ===================== MODAL NUEVO MATERIAL =====================

        function modalNuevaSolicitud() {
            document.getElementById("formulario-nuevo-material").reset();
            $('#select-medida-nuevo').prop('selectedIndex', 0).change();
            $('#select-mes-nuevo').val('').trigger('change');
            $('#modalNuevoMaterial').modal('show');
        }

        function verificarNuevoMaterial() {

            var material = document.getElementById('material-nuevo').value;
            var costo    = document.getElementById('costo-nuevo').value;
            var cantidad = document.getElementById('cantidad-nuevo').value;
            var periodo  = document.getElementById('periodo-nuevo').value;
            var medida   = document.getElementById('select-medida-nuevo').value;
            var mes      = document.getElementById('select-mes-nuevo').value;

            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;
            var reglaNumeroEntero     = /^[0-9]\d*$/;

            if (material === '') { toastr.error('Material es requerido'); return; }
            if (material.length > 300) { toastr.error('Material máximo 300 caracteres'); return; }

            if (costo === '') { toastr.error('Costo es requerido'); return; }
            if (!costo.match(reglaNumeroDosDecimal)) { toastr.error('Costo debe ser número Decimal Positivo. Solo se permite 2 Decimales'); return; }
            if (costo <= 0) { toastr.error('Costo no permite Ceros o negativos'); return; }
            if (costo > 99000000) { toastr.error('Costo máximo 99 millones de límite'); return; }

            if (cantidad === '') { toastr.error('Cantidad es requerido'); return; }
            if (!cantidad.match(reglaNumeroEntero)) { toastr.error('Cantidad debe ser número Entero y No Negativos'); return; }
            if (cantidad <= 0) { toastr.error('Cantidad no permite números negativos y Ceros'); return; }
            if (cantidad > 99000000) { toastr.error('Cantidad máximo 99 millones de límite'); return; }

            if (periodo === '') { toastr.error('Periodo es requerido'); return; }
            if (!periodo.match(reglaNumeroEntero)) { toastr.error('Periodo debe ser número Entero y No Negativos'); return; }
            if (periodo <= 0) { toastr.error('Periodo no debe ser Cero o Negativos'); return; }
            if (periodo > 999) { toastr.error('Periodo máximo 999 veces de límite'); return; }

            if (medida === '') { toastr.error('Unidad Medida es requerido'); return; }
            if (mes === '' || mes === null) { toastr.error('Mes de Ejecución es requerido'); return; }

            var textoMedida = $("#select-medida-nuevo option:selected").text();
            var textoMes    = $("#select-mes-nuevo option:selected").text();

            var markup = "<tr>" +
                "<td><input name='descripcionfila[]' maxlength='300' value='" + material + "' disabled class='form-control' type='text'></td>" +
                "<td><input name='unidadmedidafila[]' value='" + textoMedida + "' class='form-control' disabled data-infomedida='" + medida + "' type='text'/></td>" +
                "<td><input name='costoextrafila[]' value='" + costo + "' disabled class='form-control' type='text'/></td>" +
                "<td><input name='cantidadextrafila[]' value='" + cantidad + "' disabled class='form-control'/></td>" +
                "<td><input name='periodoextrafila[]' value='" + periodo + "' disabled class='form-control'/></td>" +
                "<td><input name='mesextrafila[]' value='" + textoMes + "' disabled class='form-control' data-infomes='" + mes + "' type='text'/></td>" +
                "<td><button type='button' class='btn btn-block btn-danger' onclick='borrarFila(this)'>Borrar</button></td>" +
                "</tr>";

            $("#matrizMateriales tbody").append(markup);
            $('#modalNuevoMaterial').modal('hide');
        }

        // ===================== MODAL NUEVO PROYECTO =====================

        function modalNuevaSolicitudProyecto() {
            document.getElementById("formulario-nuevo-proyecto").reset();
            $('#select-mes-proyecto-nuevo').val('').trigger('change');
            $('#modalNuevoProyecto').modal('show');
        }

        function verificarNuevoProyecto() {

            var descripcion = document.getElementById('proyecto-descripcion-nuevo').value;
            var costo       = document.getElementById('proyecto-costo-nuevo').value;
            var mes         = document.getElementById('select-mes-proyecto-nuevo').value;

            var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

            if (descripcion === '') { toastr.error('Descripción es requerido'); return; }
            if (descripcion.length > 300) { toastr.error('Descripción máximo 300 caracteres'); return; }

            if (costo === '') { toastr.error('Costo es requerido'); return; }
            if (!costo.match(reglaNumeroDosDecimal)) { toastr.error('Costo debe ser número Decimal Positivo. Solo se permite 2 Decimales'); return; }
            if (costo < 0) { toastr.error('Costo no permite números negativos'); return; }
            if (costo > 99000000) { toastr.error('Costo máximo 99 millones de límite'); return; }

            if (mes === '' || mes === null) { toastr.error('Mes de Ejecución es requerido'); return; }

            var textoMes = $("#select-mes-proyecto-nuevo option:selected").text();

            var markup = "<tr>" +
                "<td><input name='proyectodescripcionfila[]' maxlength='300' value='" + descripcion + "' disabled class='form-control' type='text'></td>" +
                "<td><input name='proyectocostoextrafila[]' value='" + costo + "' disabled class='form-control' type='text'/></td>" +
                "<td><input name='mesfilaproyecto[]' value='" + textoMes + "' disabled class='form-control' data-infomes='" + mes + "' type='text'/></td>" +
                "<td><button type='button' class='btn btn-block btn-danger' onclick='borrarFilaProyecto(this)'>Borrar</button></td>" +
                "</tr>";

            $("#matrizProyectos tbody").append(markup);
            $('#modalNuevoProyecto').modal('hide');
        }

        function mostrarBloque() {
            document.getElementById("bloque-codigo").style.display = "block";
        }

        function ocultarBloque() {
            document.getElementById("bloque-codigo").style.display = "none";
        }

    </script>

@endsection
