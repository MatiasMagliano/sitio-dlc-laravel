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

        #datosLote {
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
        <div class="col-md-4">
            <x-adminlte-card title="Seleccione un producto" icon="fas fa-search">
                <table id="tabla1" class="" style="width: 100%">
                    <thead>
                        <th>Droga</th>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr>
                                <td>
                                    <input type="text" id="id_producto" id_producto="{{ $producto->id }}" hidden>
                                    {{ $producto->droga }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-adminlte-card>
        </div>
        <div class="col-md-4" id="datosLote">
            <x-adminlte-card title="Lotes vigentes" icon="fas fa-plus">
            </x-adminlte-card>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
            var tabla = $('#tabla1').DataTable({
                responsive: true,
                dom: 'Pfrtip',

                "scrollY": "50vh",
                "scrollCollapse": true,
                "paging": false
            });

            $('#tabla1 tbody').on('click', 'tr', function(){
                var dato = tabla.row( this ).data();
                $('#id_producto').val(dato);
                alert( 'You clicked on '+dato[0]+'\'s row' );
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
