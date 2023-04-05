@if ($producto->hospitalario === 1 && $producto->trazabilidad === 1)
    <span style="color: #425b9e;">HOSPITALARIO</span> - {{$producto->forma. ", " .$producto->presentacion}} - <span style="color: #9e4942;">TRAZABLE</span>
@else
    @if ($producto->hospitalario === 1)
        <span style="color: #425b9e;">HOSPITALARIO</span> - {{$producto->forma. ", " .$producto->presentacion}}

    @elseif ($producto->trazabilidad === 1)
        {{$producto->forma. ", " .$producto->presentacion}} - <span style="color: #9e4942;">TRAZABLE</span>

    @else
        {{$producto->forma. ", " .$producto->presentacion}}
    @endif
@endif
