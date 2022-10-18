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
@endsection

@section('content')
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    @section('plugins.TempusDominusBs4', true)
    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                <table id="tabla-cotizaciones" class="table table-bordered table-responsive-md display" style="width:100%">
                    <thead>
                        <tr class="bg-gray">
                            <th>Fecha creación</th>
                            <th>Fecha modificación</th>
                            <th>Identificador</th>
                            <th>Cliente</th>
                            <th>ESTADO</th>
                            <th>ACCIONES <br><span class="small">Ver | cotización | provisión</span></th>
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
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>

    <script>
        $(document).ready(function() {
            $('#tabla-cotizaciones').DataTable({
                "serverSide": true,
                "ajax": {
                    url: "{{ route('administracion.cotizaciones.ajax.historico') }}",
                    method: "GET"
                },
                "columnDefs": [
                    {
                        'targets': [0],
                        'class': 'align-middle text-center',
                        'render': function (data) {
                            return moment(new Date(data)).format("DD/MM/YYYY");
                        }
                    },
                    {
                        'targets': [1],
                        'class': 'align-middle text-center',
                        'render': function (data) {
                            return moment(new Date(data)).format("DD/MM/YYYY");
                        }
                    },
                    {
                        'targets': [2],
                        'class': 'align-middle text-center',
                    },
                    {
                        'targets': [3],
                        'class': 'align-middle',
                    },
                    {
                        'targets': [4],
                        'class': 'align-middle text-center',
                    },

                    {
                        'targets': [5],
                        'class': 'align-middle text-center',
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
