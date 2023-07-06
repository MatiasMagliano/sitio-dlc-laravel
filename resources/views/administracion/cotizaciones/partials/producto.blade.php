@if ($producto->no_aprobado)
    <span class="text-danger">(LÍNEA NO APROBADA)</span><br>
@endif
{{ $producto->droga }}, {{$producto->presentacion}}
@if ($producto->hospitalario === 1)
    - <span class="text-primary">HOSPITALARIO</span>

@elseif ($producto->trazabilidad === 1)
    - <span class="text-warning">TRAZABLE</span>

@elseif ($producto->divisible === 1)
    - <span class="text-danger">DIVISIBLE</span>

@endif
