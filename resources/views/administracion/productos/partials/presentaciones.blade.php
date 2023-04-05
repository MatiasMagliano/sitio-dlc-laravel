{{ $producto->forma . ', ' . $producto->presentacion }}
@if ($producto->hospitalario === 1)
    - <span class="text-primary">HOSPITALARIO</span>

@elseif ($producto->trazabilidad === 1)
    - <span class="text-warning">TRAZABLE</span>

@elseif ($producto->divisible === 1)
    - <span class="text-danger">HOSPITALARIO</span>
    
@endif
