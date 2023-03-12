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
                <td style="vertical-align: middle;">{{ $listaPrecio->codigoProv }}</td>
                <td style="vertical-align: middle;">{{ $listaPrecio->droga }}</td>
                <td style="vertical-align: middle;">{{ $listaPrecio->forma }} | {{ $listaPrecio->presentacion }}</td>
                <td style="vertical-align: middle;">$ {{ $listaPrecio->costo }}</td>
                <td style="vertical-align: middle;">{{ $listaPrecio->updated_at }}</td>
                <td style="vertical-align: middle; text-align:center;">
                    {{--<a href="{{ route('administracion.listaprecios.editar.lista', $listaPrecio->cuit, $listaPrecio->listaId) }}"
                        class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                        title="Editar item">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <form action="{{ route('administracion.listaprecios.destroy', $proveedorItem->itemId) }}"
                        id="borrar-{{$listaPrecio->listaId}}" method="post" class="d-inline">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-link"
                        data-id ="{{ $listaPrecio->listaId }}" data-action="{{ route('administracion.listaprecios.borrar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $cotizado]) }}"
                        data-toggle="tooltip" data-placement="bottom" title="Borrar item"
                            onclick="borrarItemListado({{ $listaPrecio->id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                    --}}
                    <a href="{{-- route('administracion.listaprecios.edit', $listaPrecio->razon_social) --}}"
                        class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                        title="Editar cotización">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <form action="{{ route('administracion.listaprecios.show.itemDestroy', [$listaPrecio->razon_social, $listaPrecio->listaId]) }}"
                        id="borrar-{{ $listaPrecio->listaId }}" name="{{ $listaPrecio->razon_social }}" method="post" class="d-inline">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-link text-danger" data-toggle="tooltip" data-placement="bottom"
                            title="Borrar listado" onclick="borrarItemListado({{ $listaPrecio->listaId }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>