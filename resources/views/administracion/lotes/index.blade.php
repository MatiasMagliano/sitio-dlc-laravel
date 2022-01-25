@extends('adminlte::page')
@section('css')
    <style>
        tabla1.dataTable thead th {
            border-bottom: none;
        }

        tabla1.dataTable tfoot th {
            border-top: none;
            border-bottom: 1px solid  #111;
        }

        #datosLoteActivo {
            height: 50vh;
        }

        #datosLoteInactivo {
            display: none;
        }
    </style>
@endsection

@section('title', 'Administrar Lotes')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administrar/agregar lotes a productos</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.productos.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Seleccione un producto" icon="fas fa-search">
                <table id="tabla1" class="tabla1" style="width: 100%">
                    <thead>
                        <th>Droga</th>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>
                                    <input type="text" id="idProducto" class="idProducto" idProducto="{{ $producto->id }}" hidden>
                                    {{ $producto->droga }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-adminlte-card>
        </div>
        <div class="col-md-6" id="datosLote">
            <x-adminlte-card title="Lotes vigentes" icon="fas fa-plus">
                <table id="tabla2" class="display nowrap" style="width: 100%">
                    <thead>
                        <th>Nº</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Vencimiento</th>
                    </thead>
                </table>
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            var tabla1 = $('#tabla1').DataTable({
                responsive: true,
                dom: 'Pfrtip',

                "scrollY": "50vh",
                "scrollCollapse": true,
                "paging": false,
                "select": true
            });

            $('#tabla1 tbody').on('click', 'td', function(){
                var idProducto = tabla1.row(this).data();
                alert( 'El id del producto es: '+idProducto[0] );
                var datos = {idProducto: idProducto};

                // Se genera la segunda tabla con la característica destroy, para poder recargarla con los nuevos datos
                $('#tabla2').DataTable({
                    "processing": true,
                    "order": [[0, "desc"]],
                    "paging": false,
                    "info": false,
                    "searching": false,
                    "ajax": {
                        "data": datos,
                        "dataSrc": "",
                        "url": "{{ route('administracion.lotes.buscarLotes') }}",
                        "type": "GET",
                    },
                    "columns": [
                        {data: 'identificador', name: 'Nº'},
                        {data: 'precioCompra', name: 'Precio'},
                        {data: 'cantidad', name: 'Cantidad'},
                        {data: 'hasta', name: 'Vencimiento'}
                    ],
                    "destroy": true
                });

                //alert( 'El id del producto es: '+idProducto );
            });
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
