<link href="{{ asset('css/cssacordeon.css') }}" type="text/css" rel="stylesheet" />

<section class="col-12" id="bloquecontenedor" style="display: none">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <form class="form-vertical">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Presupuesto Año: {{ $preanio }}</label>
                            </div>
                            <div class="form-group">
                                @if($estado == 1)
                                    <label>Estado: En Desarrollo</label>
                                @elseif($estado == 2)
                                    <label>Estado: En Revisión</label>
                                @else
                                    <label>Estado: <span class="badge bg-success">Presupuesto Aprobado</span></label>
                                @endif
                            </div>
                        </div>

                        <div style="margin-left: 20px">
                            <label style="color: darkgreen; font-size: 20px; font-family: arial">Total: ${{ $totalvalor }}</label>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex p-0">

                                    <button type="button" onclick="modalBuscarMaterial()" class="btn btn-default btn-sm"
                                            style="margin-bottom: 5px; margin-top: 5px; background: #E5E7E9">
                                        <i class="fas fa-search"></i>
                                        Buscar Material
                                    </button>

                                    <ul class="nav nav-pills ml-auto p-2">
                                        <li class="nav-item"><a class="nav-link active" href="#tab_1" onclick="mostrarBloque()" data-toggle="tab">Base Presupuesto</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#tab_2" onclick="ocultarBloque()" data-toggle="tab">Nuevos Materiales</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#tab_3" onclick="ocultarBloque()" data-toggle="tab">Proyectos</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content">

                                        {{-- ==================== TAB 1 - BASE PRESUPUESTO ==================== --}}
                                        <div class="tab-pane active" id="tab_1">
                                            <div>
                                                <form>
                                                    <div class="card-body">

                                                        @php $filaBase = 0; @endphp

                                                        @foreach($rubro as $item)

                                                            <div class="accordion-group" data-behavior="accordion">
                                                                <label class="accordion-header"
                                                                       style="background-color: #c5c6c8; color: black !important;">
                                                                    {{ $item->codigo }} - {{ $item->nombre }}
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    ${{ $item->sumarubro }}
                                                                </label>

                                                                <div class="accordion-body">

                                                                    @foreach($item->cuenta as $cc)

                                                                        <div class="accordion-group" data-behavior="accordion" data-multiple="true">
                                                                            <p class="accordion-header"
                                                                               style="background-color: #b0c2f2; color: black !important;">
                                                                                {{ $cc->codigo }} - {{ $cc->nombre }}
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                ${{ $cc->sumaobjetototal }}
                                                                            </p>

                                                                            <div class="accordion-body">
                                                                                <div class="accordion-group" data-behavior="accordion" data-multiple="true">

                                                                                    @foreach($cc->objeto as $obj)

                                                                                        <p class="accordion-header"
                                                                                           style="background-color: #b0f2c2; color: black !important;">
                                                                                            {{ $obj->codigo }} | {{ $obj->nombre }}
                                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                            ${{ $obj->sumaobjeto }}
                                                                                        </p>

                                                                                        <div class="accordion-body">
                                                                                            <table data-toggle="table">
                                                                                                <thead>
                                                                                                <tr>
                                                                                                    <th style="width: 4%;  text-align: center">#</th>
                                                                                                    <th style="width: 22%; text-align: center">Descripción</th>
                                                                                                    <th style="width: 12%; text-align: center">U/M</th>
                                                                                                    <th style="width: 10%; text-align: center">Costo ($)</th>
                                                                                                    <th style="width: 10%; text-align: center">Unidades</th>
                                                                                                    <th style="width: 10%; text-align: center">Periodo</th>
                                                                                                    <th style="width: 17%; text-align: center">Mes Ejec.</th>
                                                                                                    <th style="width: 15%; text-align: center">Total</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>

                                                                                                @foreach($obj->material as $mm)
                                                                                                    @php $filaBase++; @endphp
                                                                                                    <tr>
                                                                                                        {{-- # fila global --}}
                                                                                                        <td style="text-align: center; vertical-align: middle;">
                                                                                                            <span class="badge badge-secondary">{{ $filaBase }}</span>
                                                                                                        </td>

                                                                                                        <td>
                                                                                                            <input type="hidden" name="idmaterial[]"
                                                                                                                   value="{{ $mm->id }}"
                                                                                                                   data-fila="{{ $filaBase }}"
                                                                                                                   data-desc="{{ $obj->codigo }} | {{ $mm->descripcion }}">
                                                                                                            <input value="{{ $mm->descripcion }}" disabled class="form-control" type="text">
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <input value="{{ $mm->unimedida }}" disabled class="form-control" type="text">
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <input value="{{ $mm->precio }}" disabled class="form-control" style="max-width: 150px">
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <input value="{{ $mm->cantidad }}" name="unidades[]" class="form-control"
                                                                                                                   type="number" onchange="multiplicar(this)" maxlength="6"
                                                                                                                   style="max-width: 180px"
                                                                                                                   onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;">
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <input value="{{ $mm->periodo }}" name="periodo[]" class="form-control"
                                                                                                                   min="1" type="number" onchange="multiplicar(this)" maxlength="6"
                                                                                                                   style="max-width: 180px"
                                                                                                                   onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;">
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            {{-- Select mes — preselecciona el mes guardado ($mm->id_mes) --}}
                                                                                                            <select name="mes[]"
                                                                                                                    class="form-control select-mes-base"
                                                                                                                    style="min-width: 130px"
                                                                                                                    data-fila="{{ $filaBase }}"
                                                                                                                    data-desc="{{ $obj->codigo }} | {{ $mm->descripcion }}">
                                                                                                                <option value="">-- Mes --</option>
                                                                                                                @foreach($arrayMeses as $mes)
                                                                                                                    <option value="{{ $mes->id }}"
                                                                                                                        {{ isset($mm->id_mes) && $mm->id_mes == $mes->id ? 'selected' : '' }}>
                                                                                                                        {{ $mes->nombre }}
                                                                                                                    </option>
                                                                                                                @endforeach
                                                                                                            </select>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <input value="{{ $mm->total }}" disabled name="total[]"
                                                                                                                   class="form-control" type="text" style="max-width: 180px">
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                @endforeach

                                                                                                {{-- PROYECTOS APROBADOS (filas naranjas, no editables) --}}
                                                                                                @foreach($listadoProyectoAprobados as $lpa)
                                                                                                    @if($obj->codigo == $lpa->codigoobj)
                                                                                                        <tr style="background-color: #FAD7A0;">
                                                                                                            <td style="background-color: #FAD7A0; text-align:center; vertical-align:middle;">
                                                                                                                <span class="badge" style="background:#e59866; color:white;">PA</span>
                                                                                                            </td>
                                                                                                            <td style="background-color: #FAD7A0;">
                                                                                                                <input value="{{ $lpa->descripcion }}" style="background-color: #FAD7A0; color: black; font-weight: bold" type="text" disabled class="form-control">
                                                                                                            </td>
                                                                                                            <td><input value="" disabled class="form-control" style="background-color: #FAD7A0;" type="text"></td>
                                                                                                            <td style="background-color: #FAD7A0;">
                                                                                                                <input value="{{ $lpa->costoFormat }}" disabled style="background-color: #FAD7A0; color: black; font-weight: bold; max-width: 150px" type="text" class="form-control">
                                                                                                            </td>
                                                                                                            <td style="background-color: #FAD7A0;"><input value="" disabled style="background-color: #FAD7A0; max-width: 180px" type="text" class="form-control"></td>
                                                                                                            <td style="background-color: #FAD7A0;"><input value="" disabled style="background-color: #FAD7A0; max-width: 180px" type="text" class="form-control"></td>
                                                                                                            <td style="background-color: #FAD7A0;"><input value="" disabled style="background-color: #FAD7A0; max-width: 130px" type="text" class="form-control"></td>
                                                                                                            <td style="background-color: #FAD7A0;">
                                                                                                                <input value="{{ $lpa->costoFormat }}" style="background-color: #FAD7A0; color: black; font-weight: bold; max-width: 180px" type="text" disabled class="form-control">
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                @endforeach

                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>

                                                                                    @endforeach
                                                                                    {{-- fin foreach objetos --}}

                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    @endforeach
                                                                    {{-- fin foreach cuenta --}}

                                                                </div>
                                                            </div>

                                                            @if($loop->last)
                                                                <script>
                                                                    setTimeout(function () {
                                                                        mostrarContenedor();
                                                                        closeLoading();
                                                                    }, 1000);
                                                                </script>
                                                            @endif

                                                        @endforeach
                                                        {{-- fin foreach rubro --}}

                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        {{-- ==================== TAB 2 - NUEVOS MATERIALES ==================== --}}
                                        <div class="tab-pane" id="tab_2">
                                            <form>
                                                <div class="card-body">

                                                    <table class="table" id="matrizMateriales" style="border: 80px" data-toggle="table">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 25%; text-align: center">Descripción</th>
                                                            <th style="width: 15%; text-align: left">Unidad de Medida</th>
                                                            <th style="width: 12%; text-align: center">Costo ($)</th>
                                                            <th style="width: 10%; text-align: center">Cantidad</th>
                                                            <th style="width: 10%; text-align: center">Periodo</th>
                                                            <th style="width: 15%; text-align: center">Mes Ejec.</th>
                                                            <th style="width: 13%; text-align: center">Opciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="myTbodyMateriales">

                                                        @foreach($listado as $ll)
                                                            <tr>
                                                                <td><input name="descripcionfila[]" disabled value="{{ $ll->descripcion }}" maxlength="300" class="form-control" type="text"></td>
                                                                <td><input name="unidadmedidafila[]" disabled value="{{ $ll->unidadmedida }}" data-infomedida="{{ $ll->id_unidadmedida }}" class="form-control" type="text"></td>
                                                                <td><input name="costoextrafila[]" disabled value="{{ $ll->costo }}" class="form-control" type="number"></td>
                                                                <td><input name="cantidadextrafila[]" disabled value="{{ $ll->cantidad }}" class="form-control" type="number"></td>
                                                                <td><input name="periodoextrafila[]" disabled value="{{ $ll->periodo }}" class="form-control" type="number"></td>
                                                                {{-- Mes guardado del material extra --}}
                                                                <td>
                                                                    <input name="mesextrafila[]"
                                                                           value="{{ isset($ll->nombreMes) ? $ll->nombreMes : '' }}"
                                                                           disabled class="form-control"
                                                                           data-infomes="{{ isset($ll->id_mes) ? $ll->id_mes : '' }}"
                                                                           type="text"/>
                                                                </td>
                                                                <td>
                                                                    @if($estado == 1)
                                                                        <button type="button" class="btn btn-block btn-danger" onclick="borrarFila(this)">Borrar</button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        </tbody>
                                                    </table>

                                                    @if($estado == 1)
                                                        <br>
                                                        <button type="button" class="btn btn-block btn-success" onclick="modalNuevaSolicitud()">
                                                            Agregar Solicitud de Material
                                                        </button>
                                                        <br>
                                                    @endif

                                                </div>
                                            </form>
                                        </div>

                                        {{-- ==================== TAB 3 - PROYECTOS ==================== --}}
                                        <div class="tab-pane" id="tab_3">

                                            {{-- Proyectos Pendientes --}}
                                            <form>
                                                <div class="card-body">
                                                    <h3>Proyectos Pendientes</h3>
                                                    <table class="table" id="matrizProyectos" style="border: 80px" data-toggle="table">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 35%; text-align: center">Descripción</th>
                                                            <th style="width: 20%; text-align: center">Costo</th>
                                                            <th style="width: 30%; text-align: center">Mes Ejec.</th>
                                                            <th style="width: 15%; text-align: center">Opciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @foreach($listadoProyecto as $lp)
                                                            <tr>
                                                                <td><input name="proyectodescripcionfila[]" disabled value="{{ $lp->descripcion }}" maxlength="300" class="form-control" type="text"></td>
                                                                <td><input name="proyectocostoextrafila[]" disabled value="{{ $lp->costo }}" class="form-control" type="number"></td>
                                                                {{-- Mes guardado del proyecto --}}
                                                                <td>
                                                                    <input name="mesfilaproyecto[]"
                                                                           value="{{ isset($lp->nombreMes) ? $lp->nombreMes : '' }}"
                                                                           disabled class="form-control"
                                                                           data-infomes="{{ isset($lp->id_mes) ? $lp->id_mes : '' }}"
                                                                           type="text"/>
                                                                </td>
                                                                <td>
                                                                    @if($estado == 1)
                                                                        <button type="button" class="btn btn-block btn-danger" onclick="borrarFilaProyecto(this)">Borrar</button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        </tbody>
                                                    </table>

                                                    @if($estado == 1)
                                                        <br>
                                                        <button type="button" class="btn btn-block btn-success" onclick="modalNuevaSolicitudProyecto()">
                                                            Agregar Solicitud de Proyecto
                                                        </button>
                                                        <br>
                                                    @endif

                                                </div>
                                            </form>

                                            {{-- Proyectos Aprobados --}}
                                            <br>
                                            <hr>
                                            <form>
                                                <div class="card-body">
                                                    <h3>Proyectos Aprobados</h3>
                                                    <table class="table" id="matrizProyectosAprobados" style="border: 80px" data-toggle="table">
                                                        <thead>
                                                        <tr>
                                                            <th style="width: 15%; text-align: center">Descripción</th>
                                                            <th style="width: 10%; text-align: center">Costo</th>
                                                            <th style="width: 12%; text-align: center">Obj. Específico</th>
                                                            <th style="width: 12%; text-align: center">Fuente Recursos</th>
                                                            <th style="width: 12%; text-align: center">Línea Trabajo</th>
                                                            <th style="width: 12%; text-align: center">Área Gestión</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($listadoProyectoAprobados as $lp)
                                                            <tr>
                                                                <td><input disabled value="{{ $lp->descripcion }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->costoFormat }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->objeto }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->fuenterecurso }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->lineatrabajo }}" class="form-control"></td>
                                                                <td><input disabled value="{{ $lp->areagestion }}" class="form-control"></td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </form>

                                        </div>
                                        {{-- fin tab-content --}}

                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($estado == 1)
                            <div class="card-footer">
                                <button type="button" onclick="verificar()" class="btn btn-success float-right">Guardar</button>
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="btn-group-vertical" id="bloque-codigo" style="width: 175px !important;">
                <label style="margin-left: 5px">Tipo según Color</label>
                <button type="button" class="btn btn-info" style="background: #c5c6c8; color: black !important; font-weight: bold">RUBRO</button>
                <button type="button" class="btn btn-info" style="background: #b0c2f2; color: black !important; font-weight: bold">CUENTA</button>
                <button type="button" class="btn btn-info" style="background: #b0f2c2; color: black !important; font-weight: bold">OBJETO ESPECÍFICO</button>
            </div>
        </div>

    </div>
</section>


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
                                        <input type="text" class="form-control" autocomplete="off" maxlength="300" id="material-nuevo" placeholder="Nombre">
                                    </div>

                                    <div class="form-group">
                                        <label>Costo Estimado:</label>
                                        <input type="number" class="form-control" autocomplete="off" id="costo-nuevo" placeholder="0.00">
                                    </div>

                                    <div class="form-group">
                                        <label>Cantidad:</label>
                                        <input type="number" class="form-control" autocomplete="off" id="cantidad-nuevo" placeholder="0">
                                    </div>

                                    <div class="form-group">
                                        <label>Periodo (Mínimo 1):</label>
                                        <input type="number" class="form-control" autocomplete="off" id="periodo-nuevo">
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


<script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>

<script>

    $(document).ready(function () {
        $('[data-behavior=accordion]').simpleAccordion({cbOpen: accOpen, cbClose: accClose});

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
    });

    function accClose(e, $this) { $this.find('span').fadeIn(200); }
    function accOpen(e, $this)  { $this.find('span').fadeOut(200); }

    function mostrarContenedor() {
        document.getElementById("bloquecontenedor").style.display = "block";
    }

    // ===================== MULTIPLICAR =====================
    // Columnas: 0=# 1=desc 2=um 3=costo 4=unidades 5=periodo 6=mes 7=total
    function multiplicar(e) {
        var table    = e.parentNode.parentNode;
        var costo    = table.cells[3].children[0];
        var unidades = table.cells[4].children[0];
        var periodo  = table.cells[5].children[0];
        var total    = table.cells[7].children[0];

        var boolUnidades = false;
        var boolPeriodo  = false;

        var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;
        var reglaNumeroEntero     = /^[0-9]\d*$/;

        if (unidades.value.length > 0) {
            if (!unidades.value.match(reglaNumeroDosDecimal)) { modalMensaje('Error', 'Unidades debe ser número Decimal Positivo. Solo se permite 2 Decimales'); return; }
            if (unidades.value <= 0) { modalMensaje('Error', 'Unidades no debe ser negativo o cero'); return; }
            if (unidades.value > 1000000) { modalMensaje('Error', 'Unidades máximo 1 millón'); return; }
            boolUnidades = true;
        }

        if (periodo.value.length > 0) {
            if (!periodo.value.match(reglaNumeroEntero)) { modalMensaje('Error', 'Periodo debe ser número entero'); return; }
            if (periodo.value <= 0) { modalMensaje('Error', 'Periodo no debe ser negativo o cero'); return; }
            if (periodo.value > 1000000) { modalMensaje('Error', 'Periodo máximo 1 millón'); return; }
            boolPeriodo = true;
        }

        if (boolUnidades && boolPeriodo) {
            var valTotal = (costo.value * unidades.value) * periodo.value;
            total.value  = '$' + Number(valTotal).toFixed(2);
        } else {
            total.value = '';
        }
    }

    // ===================== EDITAR PRESUPUESTO =====================
    function editarPresupuesto() {

        var idmaterial  = $("input[name='idmaterial[]']").map(function () { return $(this).val(); }).get();
        var filaDesc    = $("input[name='idmaterial[]']").map(function () { return '#' + $(this).attr("data-fila") + ' — ' + $(this).attr("data-desc"); }).get();
        var unidades    = $("input[name='unidades[]']").map(function () { return $(this).val(); }).get();
        var periodo     = $("input[name='periodo[]']").map(function () { return $(this).val(); }).get();

        var reglaNumeroEntero     = /^[0-9]\d*$/;
        var reglaNumeroDosDecimal = /^([0-9]+\.?[0-9]{0,2})$/;

        // Validar unidades
        for (var a = 0; a < unidades.length; a++) {
            var datoUnidades = unidades[a];
            if (datoUnidades.length > 0) {
                if (!datoUnidades.match(reglaNumeroDosDecimal)) { modalMensaje('Presupuesto Base', filaDesc[a] + '\nUnidades debe ser Decimal Positivo. Solo se permite 2 Decimales'); return; }
                if (datoUnidades <= 0) { modalMensaje('Presupuesto Base', filaDesc[a] + '\nUnidades no debe ser negativos o cero'); return; }
                if (datoUnidades > 1000000) { modalMensaje('Presupuesto Base', filaDesc[a] + '\nUnidades máximo 1 millón'); return; }
            }
        }

        // Validar periodos
        for (var b = 0; b < periodo.length; b++) {
            var datoPeriodo = periodo[b];
            if (datoPeriodo.length > 0) {
                if (!datoPeriodo.match(reglaNumeroEntero)) { modalMensaje('Presupuesto Base', filaDesc[b] + '\nPeriodo ingresado no es válido'); return; }
                if (datoPeriodo <= 0) { modalMensaje('Presupuesto Base', filaDesc[b] + '\nPeriodo no debe ser negativos o cero'); return; }
                if (datoPeriodo > 1000000) { modalMensaje('Presupuesto Base', filaDesc[b] + '\nPeriodo máximo 1 millón'); return; }
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
                if (unidadmedidafila[c] == 0) { modalMensaje('Nuevos Materiales', refMat + '\nNo se encuentra la Unidad de Medida. Borrar fila y agregar de nuevo'); return; }
                if (!mesextrafila[c] || mesextrafila[c] === '' || mesextrafila[c] == 0) { modalMensaje('Nuevos Materiales', refMat + '\nEl Mes de Ejecución es requerido. Borrar fila y agregar de nuevo'); return; }
                if (descripcion[c] === '') { modalMensaje('Nuevos Materiales', refMat + '\nFalta la descripción. Borrar fila y agregar de nuevo'); return; }
                if (descripcion[c].length > 300) { modalMensaje('Nuevos Materiales', refMat + '\nLa descripción supera los 300 caracteres. Borrar fila y agregar de nuevo'); return; }
            }

            for (var d = 0; d < costoextra.length; d++) {
                var refMatD = 'Fila #' + (d + 1) + ' — ' + descripcion[d];
                if (costoextra[d] === '') { modalMensaje('Nuevos Materiales', refMatD + '\nEl Costo es requerido. Borrar fila y agregar de nuevo'); return; }
                if (!costoextra[d].match(reglaNumeroDosDecimal)) { modalMensaje('Nuevos Materiales', refMatD + '\nEl Costo debe ser Número Decimal Positivo y 2 Decimales Máximo. Borrar fila y agregar de nuevo'); return; }
                if (costoextra[d] <= 0) { modalMensaje('Nuevos Materiales', refMatD + '\nEl Costo no debe ser Negativo o Cero. Borrar fila y agregar de nuevo'); return; }
                if (costoextra[d] > 1000000) { modalMensaje('Nuevos Materiales', refMatD + '\nEl Costo no debe superar 1 millón. Borrar fila y agregar de nuevo'); return; }
            }

            for (var t = 0; t < cantidadextra.length; t++) {
                var refMatT = 'Fila #' + (t + 1) + ' — ' + descripcion[t];
                if (cantidadextra[t] === '') { modalMensaje('Nuevos Materiales', refMatT + '\nLa Cantidad es Requerida. Borrar fila y agregar de nuevo'); return; }
                if (!cantidadextra[t].match(reglaNumeroDosDecimal)) { modalMensaje('Nuevos Materiales', refMatT + '\nLa Cantidad debe ser Número Decimal Positivo y Máximo 2 Decimales. Borrar fila y agregar de nuevo'); return; }
                if (cantidadextra[t] <= 0) { modalMensaje('Nuevos Materiales', refMatT + '\nLa Cantidad no debe ser Número negativo o Cero. Borrar fila y agregar de nuevo'); return; }
                if (cantidadextra[t] > 1000000) { modalMensaje('Nuevos Materiales', refMatT + '\nLa Cantidad no debe superar 1 millón. Borrar fila y agregar de nuevo'); return; }
            }

            for (var e = 0; e < periodoextra.length; e++) {
                var refMatE = 'Fila #' + (e + 1) + ' — ' + descripcion[e];
                if (periodoextra[e] === '') { modalMensaje('Nuevos Materiales', refMatE + '\nEl Periodo es Requerido. Borrar fila y agregar de nuevo'); return; }
                if (!periodoextra[e].match(reglaNumeroEntero)) { modalMensaje('Nuevos Materiales', refMatE + '\nEl Periodo debe ser Número Entero. Borrar fila y agregar de nuevo'); return; }
                if (periodoextra[e] <= 0) { modalMensaje('Nuevos Materiales', refMatE + '\nEl Periodo no debe ser Número Negativo o Cero. Borrar fila y agregar de nuevo'); return; }
                if (periodoextra[e] > 1000000) { modalMensaje('Nuevos Materiales', refMatE + '\nEl Periodo debe tener máximo 1 millón. Borrar fila y agregar de nuevo'); return; }
            }

            for (var mate = 0; mate < descripcion.length; mate++) {
                formData.append('descripcionfila[]',   descripcion[mate]);
                formData.append('costoextrafila[]',    costoextra[mate]);
                formData.append('cantidadextrafila[]', cantidadextra[mate]);
                formData.append('periodoextrafila[]',  periodoextra[mate]);
                formData.append('unidadmedida[]',      unidadmedidafila[mate]);
                formData.append('mesextrafila[]',      mesextrafila[mate]);
            }
        }

        // -------- PROYECTOS --------
        var nRegistroProyecto = $('#matrizProyectos >tbody >tr').length;
        if (nRegistroProyecto > 0) {

            var descripcionProyecto = $("input[name='proyectodescripcionfila[]']").map(function () { return $(this).val(); }).get();
            var costoProyecto       = $("input[name='proyectocostoextrafila[]']").map(function () { return $(this).val(); }).get();
            var mesProyectofila     = $("input[name='mesfilaproyecto[]']").map(function () { return $(this).attr("data-infomes"); }).get();

            for (var pp = 0; pp < descripcionProyecto.length; pp++) {
                var refPro = 'Fila #' + (pp + 1) + ' — ' + descripcionProyecto[pp];
                if (descripcionProyecto[pp] === '') { modalMensaje('Nuevo Proyecto', refPro + '\nFalta la descripción. Borrar fila y agregar de nuevo'); return; }
                if (descripcionProyecto[pp].length > 300) { modalMensaje('Nuevo Proyecto', refPro + '\nLa descripción supera los 300 caracteres. Borrar fila y agregar de nuevo'); return; }
                if (!mesProyectofila[pp] || mesProyectofila[pp] === '' || mesProyectofila[pp] == 0) { modalMensaje('Nuevo Proyecto', refPro + '\nEl Mes de Ejecución es Requerido. Borrar fila y agregar de nuevo'); return; }
            }

            for (var pc = 0; pc < costoProyecto.length; pc++) {
                var refProC = 'Fila #' + (pc + 1) + ' — ' + descripcionProyecto[pc];
                if (costoProyecto[pc] === '') { modalMensaje('Nuevo Proyecto', refProC + '\nEl Costo es requerido. Borrar fila y agregar de nuevo'); return; }
                if (!costoProyecto[pc].match(reglaNumeroDosDecimal)) { modalMensaje('Nuevo Proyecto', refProC + '\nEl Costo debe ser Número Decimal Positivo y 2 Decimales Máximo. Borrar fila y agregar de nuevo'); return; }
                if (costoProyecto[pc] <= 0) { modalMensaje('Nuevo Proyecto', refProC + '\nEl Costo no debe ser Negativo o Cero. Borrar fila y agregar de nuevo'); return; }
                if (costoProyecto[pc] > 9000000) { modalMensaje('Nuevo Proyecto', refProC + '\nEl Costo no debe superar 9 millones. Borrar fila y agregar de nuevo'); return; }
            }

            for (var pro = 0; pro < descripcionProyecto.length; pro++) {
                formData.append('descripcionfilaproyecto[]', descripcionProyecto[pro]);
                formData.append('costoextrafilaproyecto[]',  costoProyecto[pro]);
                formData.append('mesfilaproyecto[]',         mesProyectofila[pro]);
            }
        }

        // -------- MATERIALES BASE (con mes) --------
        var mesBase     = $("select[name='mes[]']").map(function () { return $(this).val(); }).get();
        var mesBaseDesc = $("select[name='mes[]']").map(function () { return '#' + $(this).attr("data-fila") + ' — ' + $(this).attr("data-desc"); }).get();

        for (var z = 0; z < unidades.length; z++) {
            if (unidades[z].length > 0 && periodo[z].length > 0) {

                if (!mesBase[z] || mesBase[z] === '') {
                    modalMensaje('Presupuesto Base', mesBaseDesc[z] + '\nDebe seleccionar el Mes de Ejecución');
                    return;
                }

                formData.append('idmaterial[]', idmaterial[z]);
                formData.append('unidades[]',   unidades[z]);
                formData.append('periodo[]',    periodo[z]);
                formData.append('mesbase[]',    mesBase[z]);
            }
        }

        var idpresupuesto = {{ $idpresupuesto }};
        formData.append('idpresupuesto', idpresupuesto);

        axios.post(url + '/p/editar/presupuesto/editar', formData, {})
            .then((response) => {

                if (response.data.success === 1) {
                    Swal.fire({ title: 'Información', text: "El presupuesto esta en Revisión. No se puede editar", icon: 'info', confirmButtonColor: '#28a745', allowOutsideClick: false, confirmButtonText: 'Aceptar' })
                        .then((r) => { if (r.isConfirmed) location.reload(); });
                } else if (response.data.success === 2) {
                    Swal.fire({ title: 'Información', text: "El presupuesto esta Aprobado. No se puede editar", icon: 'info', confirmButtonColor: '#28a745', allowOutsideClick: false, confirmButtonText: 'Aceptar' })
                        .then((r) => { if (r.isConfirmed) location.reload(); });
                } else if (response.data.success === 3) {
                    Swal.fire({ title: 'Presupuesto Actualizado', text: "", icon: 'success', confirmButtonColor: '#28a745', allowOutsideClick: false, confirmButtonText: 'Aceptar' })
                        .then((r) => { if (r.isConfirmed) location.reload(); });
                } else {
                    toastr.error('error al actualizar');
                }
            })
            .catch(() => {
                toastr.error('error al registrar');
                closeLoading();
            });
    }

    function modalMensaje(titulo, mensaje) {
        Swal.fire({ title: titulo, text: mensaje, icon: 'info', confirmButtonColor: '#28a745', confirmButtonText: 'Aceptar' });
    }

</script>
