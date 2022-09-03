@extends('adminlte::page')

@section('css')
    <style>
        .mobile {
            display: none
        }

        .desktop {
            display: inline
        }

        @media (max-width: 600px) {
            .desktop {
                display: none
            }

            .mobile {
                display: inline
            }
        }
    </style>
@endsection

@section('title', 'Histórico de Cotizaciones')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Histórico de Cotizaciones</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ url()->previous() }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

@section('content')
    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                <table id="tabla-cotizaciones" class="table table-bordered table-hover display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Creación</th>
                            <th>Última modificación</th>
                            <th>Identificador</th>
                            <th>Usuario/Fecha de creación</th>
                            <th>Cliente</th>
                            <th>ESTADO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('js')
    @include('partials.alerts')
    <script>
        $(document).ready(function() {
            $('#tabla-cotizaciones').DataTable({
                "serverSide": true,
                "ajax": {
                    url: "{{ route('administracion.cotizaciones.historicoCotizaciones') }}",
                    method: "get"
                },
                "columnDefs": [
                    {
                        'targets': [0],
                        'orderable': false
                    },
                    {
                        'targets': [1],
                        'orderable': false
                    },
                ],
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
