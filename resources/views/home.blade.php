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
    </style>
@endsection

@section('content_header')
    <h1>Tablero inicial</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-gradient-success">
                <div class="inner">
                    <h3>{{ $cantOT[0]->cantidad }}</h3>
                    <p>Órdenes de trabajo en producción</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tools"></i>
                </div>
                <a href="{{ route('administracion.ordentrabajo.index') }}" class="small-box-footer">Más información <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-gradient-info">
                <div class="inner">
                    <h3>{{ $compras[0]->comprar }}</h3>
                    <p>Productos por comprar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <a href="{{ route('administracion.ordentrabajo.index') }}" class="small-box-footer">Más inforamción <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-gradient-warning">
                <div class="inner">
                    <h3>{{ $cantCotiz[0]->cantidad }}</h3>
                    <p>Cotizaciones pendientes de finalizar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-edit"></i>
                </div>
                <a href="{{ route('administracion.cotizaciones.index') }}" class="small-box-footer">Más información <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-gradient-danger">
                <div class="inner">
                    <h3>{{ $vencimientos[0]->proximos }}</h3>
                    <p>Productos a vencer en los proximos 30 días</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ route('administracion.calendario.vencimientos') }}" class="small-box-footer">Más información
                    <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    {{-- GRAFICO DE COTIZACIONES POR CLIENTE --}}
@section('plugins.Chartjs', true)
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-gradient-gray-dark">
                <h3 class="card-title">
                    Top-10 cotizaciones por cliente
                </h3>
            </div>
            <div class="card-body">
                <div class="chart" id="revenue-chart" style="position: relative; height: 400px;">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="top-ten-clientes" height="400"
                        style="height: 400px; display: block; width: 901px;" width="901"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-gradient-gray-dark">
                <h3 class="card-title">
                    Cotizaciones aprobadas vs rechazadas
                </h3>
            </div>
            <div class="card-body">
                <div class="chart" id="revenue-chart" style="position: relative; height: 400px;">
                    <div class="chartjs-size-monitor">
                        <div class="chartjs-size-monitor-expand">
                            <div class=""></div>
                        </div>
                        <div class="chartjs-size-monitor-shrink">
                            <div class=""></div>
                        </div>
                    </div>
                    <canvas id="cantidad-aprobRechaz" height="400"
                        style="height: 400px; display: block; width: 901px;" width="901"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('partials.alerts')
<script>
    $(document).ready(function() {

        //GRAFICO COTIZACIONES POR CLIENTES
        const charData = JSON.parse(`<?php echo $maxCotizaciones; ?>`);
        const ctx = document.getElementById("top-ten-clientes").getContext('2d');
        const grafico = new Chart(ctx, {
            type: "bar",
            data: {
                labels: charData.label,
                datasets: [{
                    label: "Cantidad de cotizaciones",
                    data: charData.data,
                    backgroundColor: "rgba(221, 191, 111, 0.8)",
                }],
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: true,
                        grid: {
                            display: true,
                            color: "rgba(255,99,132,0.2)"
                        },
                    }
                },
            },
        });

        //GRAFICO DONUT CANTIDAD DE COTIZ APROBADAS Y RECHAZADAS
        const chartData = JSON.parse(`<?php echo $cotizAprobRechaz; ?>`);
        const chartCotizs = document.getElementById("cantidad-aprobRechaz").getContext('2d');
        const graficoCantidades = new Chart(chartCotizs, {
            type: "doughnut",
            data: {
                labels: chartData.label,
                datasets: [{
                    data: chartData.data,
                    backgroundColor: [
                        "rgb(101, 247, 140)",
                        "rgb(54, 162, 235)",
                    ],
                    hoverOffset: 4
                }],
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
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
