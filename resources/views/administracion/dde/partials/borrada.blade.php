@if ($borrada)
    <form action="{{route('administracion.dde.restaurar', $dde)}}" method="post" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-xs btn-danger">restaurar</button>
    </form>
@endif
