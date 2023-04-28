@php
    foreach ($ventas as $item) {
        $linea[] = (array) $item;
    }
    $mes_anterior = null;
@endphp

<table class="table table-sm table-striped table-bordered" width="100%">
    <thead>
        <th class="text-center">MES</th>
        <th class="text-center">VENTAS</th>
        <th class="text-center">CRECIMIENTO</th>
        <th class="text-center">CREC. PORCENTUAL</th>
        <th class="text-center">PROD.COMUNES</th>
        <th class="text-center">HOSPITALARIOS</th>
        <th class="text-center">TRAZABLES</th>
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
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            </tr>
        @endforeach
    </tbody>
</table>
