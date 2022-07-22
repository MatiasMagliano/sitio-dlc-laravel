@extends('adminlte::page')

@section('title', 'Administrar Cotizaciones')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de productos</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.productos.create') }}" role="button"
                class="btn btn-md btn-success">Crear producto</a>
            &nbsp;
            <a href="{{ route('administracion.lotes.index') }}" role="button"
                class="btn btn-md btn-success">Crear lotes</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('administracion.productos.busqueda')}}" method="GET" role="search">
                @csrf
                <div class="form-inline row d-flex justify-content-end">
                    <div class="input-group col-md-3">
                        <input type="text" class="form-control" name="termino" id="termino"
                            placeholder="Buscar por droga" aria-label="Búsqueda por droga o presentación" aria-describedby="boton-busqueda">
                        <div class="input-group-append" id="boton-busqueda">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                            <a href="{{route('administracion.productos.index')}}" role="button" class="btn btn-secondary">Limpiar</a>
                        </div>
                    </div>
                </div>
            </form>
            <br>
            <table class="table table-bordered table-responsive-md"  width="100%">
                <thead>
                    <th>Droga</th>
                    <th>Presentación</th>
                    <th>HOSP/TRAZ</th>
                    <th>Lotes</th>
                    <th>Proveedores</th>
                    <th>Existencia</th>
                    <th>Cotizado</th>
                    <th>Disponible</th>
                </thead>
                <tbody>
                    @foreach ($productos as $producto) {{-- ITERA SOBRE TODOS LOS PRODUCTOS --}}
                        @foreach ($producto->presentaciones($producto->id) as $presentacion)
                            <tr>
                                <td style="vertical-align: middle;">
                                    {{$producto->droga}}
                                </td>
                                <td style="vertical-align: middle;">
                                    {{$presentacion->forma}}, {{$presentacion->presentacion}}
                                </td>
                                <td style="vertical-align: middle;">
                                    @if ($presentacion->hospitalario)
                                        <strong>HOSPITALARIO</strong>
                                    @endif
                                    @if ($presentacion->trazabilidad)
                                        {{--MÉTODO NO IMPLEMENTADO TODAVÍA--}}
                                        <span style="color: red; font-weight:800;">TRAZABLE </span>
                                        <a href="{{route('administracion.trazabilidad.show', $producto->id)}}"
                                            class="btn btn-sm btn-link" role="button"><i class="fas fa-search "></i></a>
                                    @endif
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    {{-- SE REEMPLAZA POR UN BOTÓN AJAX --}}
                                    <a role="button" id="{{$producto->id}}|{{$presentacion->id}}"
                                        class="btn btn-link"
                                        data-toggle="modal" data-target="#modalVerLotes">
                                        <i class="fas fa-search "></i>
                                    </a>
                                </td>
                                <td>
                                    @foreach ($producto->proveedores($producto->id, $presentacion->id) as $proveedor)
                                        {{$proveedor->razon_social}} <br>
                                    @endforeach
                                </td>
                                @foreach ($producto->deposito($producto->id, $presentacion->id) as $deposito)
                                    <td class="text-center" style="vertical-align: middle;">{{$deposito->existencia}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{$deposito->cotizacion}}</td>
                                    <td class="text-center" style="vertical-align: middle;">{{$deposito->disponible}}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <br>
            <div class="d-flex justify-content-end">
                <div class="pagination-wrapper">
                    {{ $productos->links() }}
                </div>
            </div>
        </div>
    </div>

    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    {{-- MODAL VER LOTES VIGENTES --}}
    @section('plugins.TempusDominusBs4', true)
    <div class="modal fade" id="modalVerLotes" tabindex="-1"
        aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lotes vigentes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pre-scrollable">
                    <table id="tablaLotes" class="table table-bordered">
                        <thead>
                            <th>Identificador</th>
                            <th>Precio compra</th>
                            <th>Cantidad</th>
                            <th>F. Compra</th>
                            <th>F. Vencimiento</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            var tablaLotes;
            tablaLotes = $('#tablaLotes').DataTable({
                "processing": true,
                "order": [
                    [4, "asc"]
                ],
                "paging": false,
                "info": false,
                "searching": false,
                "select": false,
                "columns": [
                    {
                        targets: [0],
                        data: 'identificador',
                    },
                    {
                        targets: [1],
                        data: 'precio_compra',
                    },

                    {
                        targets: [2],
                        data: 'cantidad',
                    },
                    {
                        targets: [3],
                        data: 'fecha_compra',
                    },
                    {
                        targets: [4],
                        data: 'fecha_vencimiento',
                    }
                ],
                "columnDefs": [
                    {
                        targets: 0,
                        className: "text-left",
                    },
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
                        type: 'datetime',
                        //render: $.fn.dataTable.render.moment(data, 'DD/MM/YYYY'),
                        render: function (data) {
                            return moment(new Date(data)).format('DD/MM/YYYY');
                        },
                        className: "text-center",
                    },
                    {
                        targets: 4,
                        type: 'datetime',
                        //render: $.fn.dataTable.render.moment(data, 'DD/MM/YYYY'),
                        render: function (data) {
                            return moment(new Date(data)).format('DD/MM/YYYY');
                        },
                        className: "text-center",
                    },
                ],
                "rowCallback": function(row, data, index) {
                    if (data.eliminado != null) {
                        $('td', row).css('background-color', 'Red');
                    }
                },
            });

            function getLotes(idProducto, idPresentacion) {
                var datos = {
                    producto_id: idProducto,
                    presentacion_id: idPresentacion
                };

                $.ajax({
                    url: "{{ route('administracion.lotes.ajax.obtener') }}",
                    type: "GET",
                    data: datos,
                }).done(function(resultado) {
                    tablaLotes.clear();
                    tablaLotes.rows.add(resultado).draw();
                });
            }

            $('#modalVerLotes').on('show.bs.modal', function(event){
                let temporal = event.relatedTarget.id;
                let aux = temporal.split('|');
                getLotes(aux[0], aux[1]);
            })
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
