<div class="row">
    <div class="col">
        <span class="text-muted">IDENTIFICADOR</span>
        <div class="pl-3">
            <strong>{{$cotizacion->identificador}}</strong>
        </div>
    </div>
    <div class="col">
        <span class="text-muted">FECHA DE INICIO</span>
        <div class="pl-3">
            <strong>{{$cotizacion->created_at->format('d/m/Y')}}</strong>
        </div>
    </div>
    <div class="col">
        <span class="text-muted">VENDEDOR</span>
        <div class="pl-3">
            <strong>{{$cotizacion->user->name}}</strong>
        </div>
    </div>
    <div class="col">
        <span class="text-muted">LÍNEAS</span>
        <div class="pl-3">
            <strong>{{$cotizacion->productos->count()}}</strong>
        </div>
    </div>
    <div class="col">
        <span class="text-muted">UNIDADES</span>
        <div class="pl-3">
            <strong>{{$cotizacion->productos->sum('cantidad')}}</strong>
        </div>
    </div>
    <div class="col">
        <span class="text-muted">IMPORTE TOTAL</span>
        <div class="pl-3">
            <strong>$ {{number_format($cotizacion->productos->sum('total'), 2, ',', '.')}}</strong>
        </div>
    </div>
    <div class="col">
        <span class="text-muted">ESTADO</span>
        <div class="pl-3">
            @switch($cotizacion->estado_id)
                @case(1)
                    <span class="text-fuchsia">{{$cotizacion->estado->estado}}</span>
                    @break
                @case(2)
                    <span class="text-success">{{$cotizacion->estado->estado}}</span>
                    @break
                @case(3)
                    <span class="text-secondary">{{$cotizacion->estado->estado}}</span>
                    @break
                @case(4)
                    <span class="text-success">{{$cotizacion->estado->estado}}</span>
                    @break
                @case(5)
                    <span class="text-danger">{{$cotizacion->estado->estado}}</span>
                    @break
                @default
            @endswitch
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col">
        <span class="text-muted">CLIENTE</span>
        <div class="pl-3">
            <strong>{{$cotizacion->cliente->razon_social}}</strong>
            <br>
            <span style="text-transform: uppercase;">{{$cotizacion->cliente->tipo_afip}}</span>: {{$cotizacion->cliente->afip}}
        </div>
    </div>
    <div class="col">
        <span class="text-muted">LUGAR DE ENTREGA</span>
        <div class="pl-3">
            <strong>{{$cotizacion->dde->lugar_entrega}}</strong>
            <br>
            {{$cotizacion->dde->domicilio}} <br>
            {{$cotizacion->dde->localidad->nombre}}, {{$cotizacion->dde->provincia->nombre}}
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col">
        <span class="text-muted">PLAZO DE ENTREGA</span>
    <div class="pl-3">
        <span style="text-transform: uppercase;" class="text-danger">{{$cotizacion->plazo_entrega}}</span>
    </div>
</div>
