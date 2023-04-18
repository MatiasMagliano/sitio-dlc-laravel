@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after {
            content: "" !important;
        }

        #tablaPreciosSugeridos thead th {
            border-bottom: none;
        }

        #tablaPreciosSugeridos tfoot th {
            border-top: none;
            border-bottom: 1px solid #111;
        }
        #tablaPreciosSugeridos tbody tr td{
            transition: background-color 0.3s;
        }
        .tdhover{
            cursor: pointer;
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dataTables_filter {
            display: none;
        }

        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Agregar producto a cotización</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}" role="button"
                class="btn btn-md btn-secondary">Volver a la cotización</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
    <form action="{{ route('administracion.cotizaciones.guardar.producto', $cotizacion) }}" method="post" class="needs-validation" autocomplete="off" novalidate>
        @csrf
        <input id="cotProv" type="hidden" name="cotizacion_id" value="{{$cotizacion->id}}">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-8">
                        @foreach ($porcentajes as $porcentaje)
                            <h6 class="heading-small text-muted mb-1">Producto para cotización: {{$cotizacion->identificador}} de {{ $porcentaje->razon_social }}</h6>
                        @endforeach
                    </div>
                    <div class="col-4 text-right">
                        <button type="submit" class="btn btn-sidebar btn-success"><i class="fas fa-share-square"></i>&nbsp;<span class="hide">Agregar</span></button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @include('administracion.cotizaciones.partials.form-nuevo-producto')
            </div>
        </div>
    </form>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        var tablaPreciosSugerido;
        var tablaPorcentaje;

        function obtenerDatos(id_producto, id_presentacion, id_cotizacion){
            $cotizacion = id_cotizacion
            let datos = {
                producto_id: id_producto,
                presentacion_id: id_presentacion,
                id: id_cotizacion
            };
            $.ajax({
                Type: 'GET',
                data: datos,
                url: "{{ route('administracion.cotizaciones.agregar.producto.descuentos', ['cotizacion' => $cotizacion]) }}",
            }).done(function (resultado) {
                tablaPreciosSugerido.clear();
                tablaPreciosSugerido.rows.add(resultado).draw();
                selectprecio();
            });
        }

        //Convierte en objeto seleccionador para agregar precio sugerido
        function selectprecio(){
            tdporcentajes = $('#tablaPreciosSugeridos tbody tr td');
            tdporcentajes.hover(
                function () {
                    //debugger;
                    let argument = parseFloat($(this).text());
                    if(argument != '' && $.isNumeric(argument) == true){
                        $(this).addClass('tdhover');
                        $(this).click(function(){
                                //debugger;
                                $('#input-precio').val(argument);
                        });
                    }
                },
                function () {
                    $(this).removeClass('tdhover');
                }
            );
        }

        // SCRIPT DEL SLIMSELECT
        var selProducto = new SlimSelect({
            select: '.seleccion-producto',
            placeholder: 'Seleccione el nombre de la droga y luego su presentación...',
            onChange: (seleccionado) => {
                // DISPARAR EL AJAX Y LLENAR EL DATATABLE CON PRECIOS
                let productoSeleccionado = seleccionado.value;
                let prodPres = productoSeleccionado.split("|");
                let idcotizacion = $('#cotProv').val();
                obtenerDatos(prodPres[0], prodPres[1], idcotizacion);
            }
        });

        // SCRIPT QUE ACTUALIZA EL TOTAL EN EL CAMPO TOTAL
        $('#input-cantidad').on('input', function() {
            if($('#input-precio').val() <= 0 || $('#input-cantidad').val() <= 0){
                $('#input-total').val('N/A');
            }
            else{
                $('#input-total').val('$' + (parseInt($('#input-cantidad').val()) * parseFloat($('#input-precio').val())).toFixed(2));
            }
        });
        $('#input-precio').on('input', function() {
            if($('#input-precio').val() <= 0 || $('#input-cantidad').val() <= 0){
                $('#input-total').val('N/A');
            }
            else{
                $('#input-total').val('$' + (parseInt($('#input-cantidad').val()) * parseFloat($('#input-precio').val())).toFixed(2));
            }
        });

        $(document).ready(function(){
            var idcotizacion = $('#cotProv').val();

            tablaPreciosSugerido = $('#tablaPreciosSugeridos').DataTable({
                "paging": false,
                "info": false,
                "searching": false,
                "select": false,
                "columns": [
                    {data: "razon_social"},
                    {data: "costo_1"},
                    {data: "costo_2"},
                    {data: "costo_3"},
                    {data: "costo_4"},
                    {data: "costo_5"},
                ],
                "columnDefs": [
                    {
                        targets: 1,
                        className: "text-center",
                    },
                    {
                        targets: 2,
                        className: "text-center",
                    },
                    {
                        targets: 3,
                        className: "text-center",
                    },
                    {
                        targets: 4,
                        className: "text-center",
                    },
                    {
                        targets: 5,
                        className: "text-center",
                    },
                ]
            });


            tablaPreciosSugerido.row.add({
                razon_social: '*',
                costo_1: '*',
                costo_2: '*',
                costo_3: '*',
                costo_4: '*',
                costo_5: '*',
            }).draw();
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
