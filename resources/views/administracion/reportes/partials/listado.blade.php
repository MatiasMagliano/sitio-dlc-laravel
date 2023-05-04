@php
    foreach ($ventas as $item) {
        $linea[] = (array) $item;
    }
    // se generan los datos para el grafico de ventas
    foreach ($linea as $item) {
        $vtas_x[] = $item['mes'];
        $vtas_y[] = $item['ventas'];
    }

    // se generan los datos para el gráfico de torta
    $prds_labels = ['Comunes', 'Hospitalarios', 'Trazables', 'Divisibles'];
    $prds_datos = [0, 0, 0, 0];

    // se generan los datos para el gráfico de líneas
    $crec_mensual = [];

    foreach ($mas_vendidos as $item) {
        $badge[] = (array) $item;
    }

    // variable auxiliar para calcular la tabla
    $crecimiento = 0;
    $mes_anterior = null;
@endphp


<h3 class="mb-3">Estadísticas de ventas en los últimos 12 meses</h3>
<table id="prueba" class="table table-sm table-striped table-bordered" width="100%">
    <thead class=" bg-gradient-lightblue">
        <th class="text-center">MES</th>
        <th class="text-center">VENTAS</th>
        <th class="text-center">CRECIMIENTO</th>
        <th class="text-center">CREC. PORCENTUAL</th>
        <th class="text-center">PROD.COMUNES</th>
        <th class="text-center">HOSPITALARIOS</th>
        <th class="text-center">TRAZABLES</th>
        <th class="text-center">DIVISIBLES</th>
    </thead>
    <tbody>
        @foreach ($linea as $item)
            <tr>
                <td>
                    {{-- MES --}}
                    {{ ucfirst(trans($item['mes'])) }}
                </td>
                <td class="text-center">
                    {{-- VENTAS, se retiene el mes anterior --}}
                    @php
                        if (!is_null($mes_anterior)) {
                            $crecimiento = $item['ventas'] - $mes_anterior;
                        }
                        $mes_anterior = $item['ventas'];
                        $crec_mensual[$loop->index] = $crecimiento;
                    @endphp
                    $ {{ number_format($item['ventas'], 2, ',', '.') }}
                </td>
                <td class="text-center">
                    {{-- CRECIMIENTO --}}
                    @if ($loop->first)
                        -
                    @else
                        @if ($crecimiento < 0)
                            <span class="text-danger">$ {{ number_format($crecimiento, 2, ',', '.') }}</span>
                        @else
                            <span class="text-success">$ {{ number_format($crecimiento, 2, ',', '.') }}</span>
                        @endif
                    @endif
                </td>
                <td class="text-center">
                    {{-- CREC. PORCENTUAL --}}
                    @if ($loop->first)
                        -
                    @else
                        @if ($crecimiento < 0)
                            <span class="text-danger">
                                {{ number_format(($crecimiento * 100) / $mes_anterior, 2, ',', '.') }}%
                            </span>
                        @else
                            <span class="text-success">
                                {{ number_format(($crecimiento * 100) / $mes_anterior, 2, ',', '.') }}%
                            </span>
                        @endif
                    @endif
                </td>
                <td class="text-center">
                    {{-- COMUNES --}}
                    {{ $item['cant_comunes'] }}
                    @php
                        $prds_datos[0] += $item['cant_comunes'];
                    @endphp
                </td>
                <td class="text-center">
                    {{-- HOSPITALARIOS --}}
                    {{ $item['cant_hosp'] }}
                    @php
                        $prds_datos[1] += $item['cant_hosp'];
                    @endphp
                </td>
                <td class="text-center">
                    {{-- TRAZABLES --}}
                    {{ $item['cant_trazable'] }}
                    @php
                        $prds_datos[2] += $item['cant_trazable'];
                    @endphp
                </td>
                <td class="text-center">
                    {{-- DIVISIBLES --}}
                    {{ $item['cant_divisible'] }}
                    @php
                        $prds_datos[3] += $item['cant_divisible'];
                    @endphp
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
<br>

<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <canvas id="grafico_vtas"></canvas>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <canvas id="grafico_crec"></canvas>
            </div>
        </div>
    </div>
    <div class="col">
        <canvas id="grafico_prds"></canvas>
    </div>
</div>

<br>
<br>
<h3 class="mb-3">Los 5 productos más vendidos</h3>
<ul class="list-group">
    @foreach ($badge as $item)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $item['producto'] }}
            <span class="badge badge-success badge-pill">
                {{ $item['cant_productos'] }}
            </span>
        </li>
    @endforeach
</ul>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafico_vtas');
    const cty = document.getElementById('grafico_prds');
    const ctz = document.getElementById('grafico_crec');

    // gráfico de ventas
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($vtas_x),
            datasets: [{
                label: 'Ventas de los últimos 12 meses',
                data: @json($vtas_y),
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Ventas generales, últimos 12 meses'
                }
            }
        }
    });

    // gráfico de líneas unidas, crecimiento mensual
    new Chart(ctz, {
        type: 'line',
        data: {
            labels: @json(array_slice($vtas_x, 1)),
            datasets: [{
                label: 'Crecimiento porcentual',
                data: @json(array_slice($crec_mensual, 1)),
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Crecimiento mensual'
                },
                filler: {
                    propagate: false,
                },
                interaction: {
                    intersect: false,
                }
            }
        }
    });

    // gráfico de torta de productos(comunes, hosp, trazables, divisibles)
    new Chart(cty, {
        type: 'doughnut',
        data: {
            labels: @json($prds_labels),
            datasets: [{
                label: 'Productos',
                data: @json($prds_datos),
                backgroundColor: [
                    'rgb(24, 49, 79)',
                    'rgb(58, 86, 131)',
                    'rgb(39, 8, 160)',
                    'rgb(56, 78, 119)'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Distribución anual de productos'
                }
            }
        }
    });
</script>
