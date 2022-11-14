@if ($presentacion->hospitalario === 1 && $presentacion->trazabilidad === 1)
    HOSP - {{$presentacion->forma. ", " .$presentacion->presentacion}} - <span style="color: #9e4942;">TRAZ</span>
@else
    @if ($presentacion->hospitalario === 1)
        HOSP -{{$presentacion->forma. ", " .$presentacion->presentacion}}

    @elseif ($presentacion->trazabilidad === 1)
        {{$presentacion->forma. ", " .$presentacion->presentacion}}- <span style="color: #9e4942;">TRAZ</span>

    @else
        {{$presentacion->forma. ", " .$presentacion->presentacion}}
    @endif
@endif

{{-- @foreach ($producto->presentacion as $presentacion)
    @if ($presentacion->hospitalario === 1 && $presentacion->trazabilidad === 1)
        HOSP - {{$presentacion->forma. ", " .$presentacion->presentacion}} - <span style="color: #9e4942;">TRAZ</span>
    @else
        @if ($presentacion->hospitalario === 1)
            HOSP -{{$presentacion->forma. ", " .$presentacion->presentacion}}

        @elseif ($presentacion->trazabilidad === 1)
            {{$presentacion->forma. ", " .$presentacion->presentacion}}- <span style="color: #9e4942;">TRAZ</span>

        @else
            {{$presentacion->forma. ", " .$presentacion->presentacion}}
        @endif
    @endif
    <br>
@endforeach --}}
