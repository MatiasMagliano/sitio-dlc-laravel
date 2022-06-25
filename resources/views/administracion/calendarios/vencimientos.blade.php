@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Calendarios importantes</h1>
@stop

@section('content')
    <div class="row">
        @section('plugins.FullCalendar', true)
    {{-- CALENDARIO DE VENCIMIENTOS --}}
    <div class="col-md-6 h-75">
        <div class="card">
            <div class="card-header bg-gradient-danger">
                <h3 class="card-title">
                    <i class="far fa-calendar-alt"></i>
                    Calendario de vencimientos
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn bg-danger btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div id="calendario-vencimientos"></div>
            </div>
        </div>
    </div>

    {{-- CALENDARIO COTIZACIONES --}}
    <div class="col-md-6 h-75">
        <div class="card">
            <div class="card-header bg-gradient-success">
                <h3 class="card-title">
                    <i class="far fa-calendar-alt"></i>
                    Calendario de cotizaciones
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn bg-danger btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div id="calendario-cotizaciones"></div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')
    <script>
        $(document).ready(function() {
            // CALENDARIO DE VENCIMIENTOS
            var calendarEl = document.getElementById('calendario-vencimientos');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: "es",
                themeSystem: "bootstrap",
                dayMaxEventRows: true,
                views: {
                    dayGridMonth: {
                        dayMaxEventRows: 5
                    }
                },
                events: {
                    url: "{{ route('administracion.ajax.obtener.vencimientos') }}",
                    method: "GET",
                    display: "block",
                    failure: function() {
                        Swal.fire('Error', 'No se pudieron cargar las fechas en el calendario',
                            'error');
                    },
                }
            });
            calendar.render();

            // CALENDARIO DE COTIZACIONES
            var calCotiz = document.getElementById('calendario-cotizaciones');
            var cc = new FullCalendar.Calendar(calCotiz, {
                locale: "es",
                themeSystem: "bootstrap",
                dayMaxEventRows: true,
                views: {
                    dayGridMonth: {
                        dayMaxEventRows: 5
                    }
                },
                eventSources: [
                    {
                        url: "{{ route('administracion.ajax.obtener.iniciadas') }}",
                        color: 'yellow',
                        textColor: 'black',
                        display: "block",
                    },
                    {
                        url: "{{ route('administracion.ajax.obtener.presentadas') }}",
                        color: 'pink',
                        textColor: 'black',
                        display: "block",
                    },
                ],
            });
            cc.render();
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
