{{$producto->droga}} <br>
@if ($producto->deleted_at != null)
    <span class='text-muted text-xs'>(eliminado el: {{$producto->deleted_at->format('d/m/Y')}})</span> <br>
    <form action="{{route('administracion.productos.restaurar', $producto->idProducto)}}" method="POST" class="d-inline">
        @csrf
        <a class="btn btn-xs btn-link" onclick="restaurar({{$producto->idProducto}}, event)">restaurar</a>
    </form>
@endif
