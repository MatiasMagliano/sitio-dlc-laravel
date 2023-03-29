<table id="tablaProductos" class="table table-responsive-md table-bordered table-condensed" width="100%">
    <thead>
        <th>Código</th>
        <th>Droga</th>
        <th>Forma y Presentación</th>
        <th>Costo</th>
        <th>Ultima Actualización</th>
        <th>Acciones</th>
    </thead>
    <tbody>
        @foreach ($listaPrecios as $listaPrecio)
            <tr>
                @if(isset($listaPrecio->updated_at))
                    <td style="vertical-align: middle;">{{ $listaPrecio->codigoProv }}</td>
                    <td style="vertical-align: middle;">{{ $listaPrecio->droga }}</td>
                    <td style="vertical-align: middle;">{{ $listaPrecio->detalle }}</td>
                    <td style="vertical-align: middle;">$ {{ $listaPrecio->costo }}</td>
                    <td style="vertical-align: middle;">{{ $listaPrecio->updated_at }}</td>
                    <td style="vertical-align: middle; text-align:center;">
                        <button role="button" class="btn btn-link open_modal" data-toggle="modal" data-target="#modalModifProducto" data-toggle="tooltip" data-placement="middle"
                            title="Editar producto" value="{{ $listaPrecio->listaId }}">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <form action="{{ route('administracion.listaprecios.editar.quitarProductoLista', [$listaPrecio->razon_social, $listaPrecio->listaId]) }}"
                            id="borrar-{{ $listaPrecio->listaId }}" name="{{ $listaPrecio->razon_social }}" method="post" class="d-inline">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-link text-danger" data-toggle="tooltip" data-placement="bottom"
                                title="Borrar listado" onclick="borrarItemListado({{ $listaPrecio->listaId }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>