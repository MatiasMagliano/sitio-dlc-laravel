<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="4">
                <h5>COTIZACIÓN "{{$cotizacion->identificador}}"</h5>
                <span>{{$ordentrabajo->estado->estado}}</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">
                Fecha de creación: <br>
                <span class="pl-2">{{$ordentrabajo->en_produccion->isoFormat('dddd LL')}}</span>
            </td>
            <td colspan="2">
                <strong>Plazo de entrega</strong>: <br>
                <strong class="pl-2">{{$ordentrabajo->plazo_entrega->isoFormat('dddd LL')}} ({{$ordentrabajo->plazo_entrega->diffForHumans()}})</strong>
            </td>
        </tr>
        <tr>
            <td class="text-muted">
                FECHA DE INICIO <br>
                <strong class="pl-2">{{$cotizacion->created_at->format('d/m/Y')}}</strong>
            </td>
            <td class="text-muted">
                VENDEDOR <br>
                <strong class="pl-2">{{$cotizacion->user->name}}</strong>
            </td>
            <td class="text-muted">LÍNEAS (aprobadas) <br>
                <strong class="pl-2 ">{{$cant_aprob}}</strong>
            </td>
            <td class="text-muted">
                UNIDADES <br>
                <strong class="pl-2">{{$cotizacion->productos->sum('cantidad')}}</strong>
            </td>
        </tr>
        <tr>
            <td class="text-muted">
                IMPORTE TOTAL <br>
                <strong class="pl-2">$ {{number_format($cotizacion->productos->sum('total'), 2, ',', '.')}}</strong>
            </td>
            <td class="text-muted">
                ESTADO <br>

                @switch($cotizacion->estado_id)
                    @case(1)
                        <span class="pl-2 text-fuchsia">{{$cotizacion->estado->estado}}</span>
                        @break
                    @case(2)
                        <span class="pl-2 text-success">{{$cotizacion->estado->estado}}</span>
                        @break
                    @case(3)
                        <span class="pl-2 text-secondary">{{$cotizacion->estado->estado}}</span>
                        @break
                    @case(4)
                        <span class="pl-2 text-success">{{$cotizacion->estado->estado}}</span>
                        @break
                    @case(5)
                        <span class="pl-2 text-danger">{{$cotizacion->estado->estado}}</span>
                        @break
                    @default
                @endswitch
            </td>
            <td class="text-muted">
                CLIENTE <br>
                <strong>{{$cotizacion->cliente->razon_social}}</strong><br>
                <span class="pl-2" style="text-transform: uppercase;">{{$cotizacion->cliente->tipo_afip}}</span>: {{$cotizacion->cliente->afip}}
            </td>
            <td class="text-muted">
                LUGAR DE ENTREGA <br>
                <strong class="pl-2">{{$cotizacion->dde->lugar_entrega}}</strong><br>
                <span class="pl-2">{{$cotizacion->dde->domicilio}}</span> <br>
                <span class="pl-2">{{$cotizacion->dde->localidad->nombre}}, {{$cotizacion->dde->provincia->nombre}}</span>
            </td>
        </tr>
    </tbody>
</table>
