<div class="text-center">
    <div class="btn-group" role="group" aria-label="Acciones de producto">
        <form action="{{route('administracion.productos.edit', ['producto' => $producto->idProducto, 'presentacion' => $producto->idPresentacion])}}" method="get">
            @csrf
            <button class="btn btn-link" data-toggle="tooltip" title='Editar producto'>
                <i class="fas fa-pencil-alt"></i>
            </button>
        </form>
        <form action="{{route('administracion.productos.destroy', ['producto' => $producto->idProducto])}}" method="POST" class="in-line" id="borrar-{{ $producto->idProducto }}">
            @csrf
            @method('delete')
            <button class="btn btn-link text-danger" data-toggle="tooltip" title='Borrar producto' onclick="borrarProducto({{$producto->idProducto}}, event);">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    </div>
</div>
