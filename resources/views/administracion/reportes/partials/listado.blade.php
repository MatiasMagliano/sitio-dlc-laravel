@php
    foreach ($ventas as $item) {
        $linea[] = (array) $item;
    }
    $mes_anterior = null;

    foreach ($mas_vendidos as $item) {
        $badge[] = (array) $item;
    }
@endphp

<h3 class="mb-3">Estadísticas de ventas en los últimos 12 meses</h2>
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
                                {{ number_format($crecimiento * 100 / $mes_anterior, 2, ',', '.') }}%
                            </span>
                        @else
                            <span class="text-success">
                                {{ number_format($crecimiento * 100 / $mes_anterior, 2, ',', '.') }}%
                            </span>
                        @endif
                    @endif
                </td>
                <td class="text-center">
                    {{-- COMUNES --}}
                    {{ $item['cant_comunes'] }}
                </td>
                <td class="text-center">
                    {{-- HOSPITALARIOS --}}
                    {{ $item['cant_hosp'] }}
                </td>
                <td class="text-center">
                    {{-- TRAZABLES --}}
                    {{ $item['cant_trazable'] }}
                </td>
                <td class="text-center">
                    {{-- DIVISIBLES --}}
                    {{ $item['cant_divisible'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
<br>
<h3 class="mb-3">Los 5 productos más vendidos</h2>
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
