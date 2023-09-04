@extends('adminlte::page')

@section('title', 'Dashboard')

@section('css')
    <style>
        .contenedor-grafico {
            position: relative;
            margin: auto;
            height: 40vh;
            width: auto;
        }
        .px-10 {
            padding: 1% 20%;
        }
    </style>
@endsection

@section('content_header')
    <h1>INICIO</h1>
    <p>Bienvenido, <strong>{{ Auth::user()->name }}</strong>. Ingresa al menú de la izquierda para comenzar.</p>
@endsection

@section('content')
    <div class="container-fluid px-10 py-5 overflow-hidden">
        <div class="row justify-content-center px-5 mx-auto">
            <div class="col-sm-6 text-center block py-1">
                <div class="small-box bg-gradient-warning">
                    <div class="inner">
                        <h3>{{ $cantCotiz[0]->cantidad }}</h3>
                        <p>Cotizaciones pendientes de finalizar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <a href="{{ route('administracion.cotizaciones.index') }}" class="small-box-footer">Más
                        información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-sm-6 text-center block py-1">
                <div class="small-box bg-gradient-info">
                    <div class="inner">
                        <h3>{{ $compras[0]->comprar }}</h3>
                        <p>Productos por comprar</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <a href="{{ route('administracion.ordentrabajo.index') }}" class="small-box-footer">Más
                        información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row justify-content-center px-5 mx-auto">
            <div class="col-sm-6 text-center block py-1">
                <div class="small-box bg-gradient-success">
                    <div class="inner">
                        <h3>{{ $cantOT[0]->cantidad }}</h3>
                        <p>Órdenes de trabajo en producción</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <a href="{{ route('administracion.ordentrabajo.index') }}" class="small-box-footer">Más
                        información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-sm-6 text-center block py-1">
                <div class="small-box bg-gradient-danger">
                    <div class="inner">
                        <h3>{{ $vencimientos[0]->proximos }}</h3>
                        <p>Productos por vencer, a 30 días</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <a href="{{ route('administracion.calendario.vencimientos') }}" class="small-box-footer">Más
                        información
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('partials.alerts')

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
