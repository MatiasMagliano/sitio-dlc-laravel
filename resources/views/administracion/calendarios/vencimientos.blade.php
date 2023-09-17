@extends('adminlte::page')

@section('title', 'Dashboard')

@section('css')
    <style>
        .fc-more-popover {
            max-height: 45%;
            overflow-y: auto;
        }
        .refer{
            padding: 1px 10px;
            border-radius: 5px;
        }
    </style>
@endsection

@section('content_header')
    <h1>Calendarios importantes</h1>
    <small>Referencia:</small> 
    <small class="refer" style="background: green">Aprobadas</small> | 
    <small class="refer" style="background: red">Rechazadas</small> | 
    <small class="refer" style="background: yellow">Cotizando</small> | 
    <small class="refer" style="background: pink">Presentadas</small> 
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
                    <button type="button" class="btn bg-success btn-sm" data-card-widget="collapse">
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
            dayMaxEventRows: 4,
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek"
            },
            eventDidMount: function(info) {
                $(info.el).tooltip({
                    title: info.event.extendedProps.description,
                    container: 'body',
                    delay: {
                        "show": 50,
                        "hide": 50
                    }
                });
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
            dayMaxEventRows: 3,
            eventSources: [{
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
                {
                    url: "{{ route('administracion.ajax.obtener.confirmadas') }}",
                    color: 'green',
                    textColor: 'black',
                    display: "block",
                },
                {
                    url: "{{ route('administracion.ajax.obtener.rechazadas') }}",
                    color: 'red',
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
    <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
</div>
@endsection
