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
    <h1>INICIO</h1>
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
                    <p>Productos por vencer, a 30 días</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <a href="{{ route('administracion.calendario.vencimientos') }}" class="small-box-footer">Más información
                    <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    {{-- GRAFICOS JS-CHART --}}
@section('plugins.Chartjs', true)
<div class="row">

    {{-- GRAFICO TOP-10 CLIENTES --}}
    <div class="col-md-4">
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
                        style="height: 400px; display: block; width:100%; margin: auto;" width="901"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFICO APROBADAS vs RECHAZADAS --}}
    <div class="col-md-4">
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
                        style="height: 400px; display: block; width:100%; margin: auto;" width="901"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFICO PÉRDIDAS POR VENCIMIENTOS --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-gradient-gray-dark">
                <h3 class="card-title">
                    Pérdidas por vencimientos
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
                    <canvas id="perdida-vencimiento" height="400"
                        style="height: 400px; display: block; width:100%; margin: auto;" width="901"
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
                responsive: true,
                maintainAspectRatio: false,
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
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
            },
        });

        //GRAFICO BARRAS PÉRDIDAS POR VENCIMIENTOS
        const chartjData = JSON.parse(`<?php echo $perdidasPorVencimiento; ?>`);
        const chartPerdida = document.getElementById("perdida-vencimiento").getContext('2d');
        const graficoVencimiento = new Chart(chartPerdida, {
            type: "bar",
            data: {
                labels: chartjData.label,
                datasets: [{
                    label: "Oportunidad de pérdida por venta al costo previo al vencimiento",
                    data: chartjData.dataOportunidad,
                    backgroundColor: "rgba(0, 191, 111, 0.8)",
                    stack: 1
                }, {
                    label: "Perdida por vencimiento",
                    data: chartjData.dataPerdida,
                    backgroundColor: "rgba(221, 191, 111, 0.8)",
                    stack: 1
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    });
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión</b> 2.0 (LARAVEL V.8)
</div>
@endsection
