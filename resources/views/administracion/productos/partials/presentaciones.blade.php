{{ $producto->forma . ', ' . $producto->presentacion }}
@if ($producto->hospitalario === 1)
    - <span class="text-primary">HOSPITALARIO</span>
@endif
@if ($producto->trazabilidad === 1)
    - <span class="text-warning">TRAZABLE</span>
@endif
@if ($producto->divisible === 1)
    - <span class="text-danger">DIVISIBLE</span>
@endif
