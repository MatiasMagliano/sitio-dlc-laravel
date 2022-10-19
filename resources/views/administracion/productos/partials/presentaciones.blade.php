@foreach ($producto->presentacion as $presentacion)
    {{$presentacion->forma. ", " .$presentacion->presentacion}} -
    @if ($presentacion->hospitalario === 1 && $presentacion->trazabilidad === 1)
        HOSP y <span style="color: #9e4942;">TRAZ</span>
    @else
        @if ($presentacion->hospitalario === 1)
            HOSP
        @endif

        @if ($presentacion->trazabilidad === 1)
            <span style="color: #9e4942;">TRAZ</span>
        @endif
    @endif
    <br>
@endforeach
