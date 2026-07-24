<link href="{{ asset('css/cssacordeon.css') }}" type="text/css" rel="stylesheet" />

<div class="col-12" id="bloquecontenedor" style="display: none">
    <div class="card">
        <div class="card-header d-flex p-0">
            <h3 class="card-title p-3"></h3>

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
                                        </label>

                                        <div class="accordion-body">

                                            @foreach($item->cuenta as $cc)

                                                <div class="accordion-group" data-behavior="accordion" data-multiple="true">
                                                    <p class="accordion-header"
                                                       style="background-color: #b0c2f2; color: black !important;">
                                                        {{ $cc->codigo }} - {{ $cc->nombre }}
                                                    </p>

                                                    <div class="accordion-body">
                                                        <div class="accordion-group" data-behavior="accordion" data-multiple="true">

                                                            @foreach($cc->objeto as $obj)

                                                                <p class="accordion-header"
                                                                   style="background-color: #b0f2c2; color: black !important;">
                                                                    {{ $obj->codigo }} | {{ $obj->nombre }}
                                                                </p>

                                                                <div class="accordion-body">
                                                                    <table data-toggle="table">
                                                                        <thead>
                                                                        <tr>
                                                                            <th style="width: 4%;  text-align: center">#</th>
                                                                            <th style="width: 23%; text-align: center">Descripción</th>
                                                                            <th style="width: 13%; text-align: center">U/M</th>
                                                                            <th style="width: 10%; text-align: center">Costo</th>
                                                                            <th style="width: 10%; text-align: center">Unidades</th>
                                                                            <th style="width: 10%; text-align: center">Periodo</th>
                                                                            <th style="width: 17%; text-align: center">Mes Ejecución</th>
                                                                            <th style="width: 13%; text-align: center">Total</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        @foreach($obj->material as $mm)
                                                                            @php $filaBase++; @endphp
                                                                            <tr>
                                                                                {{-- Número de fila global visible --}}
                                                                                <td style="text-align: center; vertical-align: middle;">
                                                                                    <span class="badge badge-secondary">{{ $filaBase }}</span>
                                                                                </td>

                                                                                <td>
                                                                                    {{-- data-fila y data-desc para mensajes de error descriptivos en JS --}}
                                                                                    <input type="hidden"
                                                                                           name="idMaterial[]"
                                                                                           value="{{ $mm->id }}"
                                                                                           data-fila="{{ $filaBase }}"
                                                                                           data-desc="{{ $obj->codigo }} | {{ $mm->descripcion }}">
                                                                                    <input value="{{ $mm->descripcion }}" disabled class="form-control" type="text">
                                                                                </td>
                                                                                <td>
                                                                                    <input value="{{ $mm->unimedida }}" disabled class="form-control" type="text">
                                                                                </td>
                                                                                <td>
                                                                                    <input value="{{ $mm->costo }}" disabled class="form-control" style="max-width: 150px">
                                                                                </td>
                                                                                <td>
                                                                                    <input name="unidades[]" class="form-control" min="1" type="number"
                                                                                           onchange="multiplicar(this)" maxlength="6" style="max-width: 180px"
                                                                                           onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;">
                                                                                </td>
                                                                                <td>
                                                                                    <input name="periodo[]" class="form-control" min="1" type="number"
                                                                                           onchange="multiplicar(this)" maxlength="6" style="max-width: 180px"
                                                                                           onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;">
                                                                                </td>
                                                                                <td>
                                                                                    {{-- data-fila y data-desc en el select para que JS pueda leerlos --}}
                                                                                    <select name="mes[]"
                                                                                            class="form-control select-mes-base"
                                                                                            style="min-width: 130px"
                                                                                            data-fila="{{ $filaBase }}"
                                                                                            data-desc="{{ $obj->codigo }} | {{ $mm->descripcion }}">
                                                                                        <option value="">-- Mes --</option>
                                                                                        @foreach($arrayMeses as $mes)
                                                                                            <option value="{{ $mes->id }}">{{ $mes->nombre }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>
                                                                                <td>
                                                                                    <input name="total[]" disabled class="form-control" type="text" style="max-width: 180px">
                                                                                </td>
                                                                            </tr>
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
                                    <th style="width: 12%; text-align: center">Costo</th>
                                    <th style="width: 10%; text-align: center">Cantidad</th>
                                    <th style="width: 10%; text-align: center">Periodo</th>
                                    <th style="width: 15%; text-align: center">Mes Ejec.</th>
                                    <th style="width: 13%; text-align: center">Opciones</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <br>
                            <button type="button" class="btn btn-block btn-success" onclick="modalNuevaSolicitud()">
                                Agregar Solicitud de Material
                            </button>
                            <br>

                        </div>
                    </form>
                </div>

                {{-- ==================== TAB 3 - PROYECTOS ==================== --}}
                <div class="tab-pane" id="tab_3">
                    <form>
                        <div class="card-body">

                            <table class="table" id="matrizProyectos" style="border: 80px" data-toggle="table">
                                <thead>
                                <tr>
                                    <th style="width: 40%; text-align: center">Descripción</th>
                                    <th style="width: 20%; text-align: center">Monto ($)</th>
                                    <th style="width: 25%; text-align: center">Mes Ejec.</th>
                                    <th style="width: 15%; text-align: center">Opciones</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <br>
                            <button type="button" class="btn btn-block btn-success" onclick="modalNuevaSolicitudProyecto()">
                                Agregar Solicitud de Proyecto
                            </button>
                            <br>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="card-footer">
        <div class="btn-group-vertical" id="bloque-codigo" style="width: 175px !important;">
            <label style="margin-left: 5px">Tipo según Color</label>
            <button type="button" class="btn btn-info"
                    style="background: #c5c6c8; color: black !important; font-weight: bold">RUBRO</button>
            <button type="button" class="btn btn-info"
                    style="background: #b0c2f2; color: black !important; font-weight: bold">CUENTA</button>
            <button type="button" class="btn btn-info"
                    style="background: #b0f2c2; color: black !important; font-weight: bold">OBJETO ESPECÍFICO</button>
        </div>

        <button type="button" onclick="verificar()"
                style="font-weight: bold; background-color: #28a745; color: white !important;"
                class="button button-rounded button-pill button-small float-right">Guardar</button>
    </div>

</div>


<script src="{{ asset('js/jquery.simpleaccordion.js') }}"></script>

<script>

    $(document).ready(function () {
        $('[data-behavior=accordion]').simpleAccordion({cbOpen: accOpen, cbClose: accClose});
    });

    function accClose(e, $this) {
        $this.find('span').fadeIn(200);
    }

    function accOpen(e, $this) {
        $this.find('span').fadeOut(200);
    }

    function mostrarContenedor() {
        document.getElementById("bloquecontenedor").style.display = "block";
    }

</script>
