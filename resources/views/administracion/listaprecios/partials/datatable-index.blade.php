<table id="tabla" class="table table-bordered {{--table-responsive-md--}}" width="100%">
    <thead class="bg-gray">
        <th>Proveedor</th>
        <th>Alta Listado</th>
        <th>Último Estado</th>
        <th>Acciones</th>
    </thead>
    <tbody>
        @foreach ($listaPrecios as $listaPrecio)
            <tr id={{ $listaPrecio->cuit }}>
                <td>{{ strtoupper($listaPrecio->razon_social) }}<br>
                    {{ $listaPrecio->cuit }}
                </td>
                <td>{{ $listaPrecio->creado }}</td>
                <td class="fechaUpdate">{{ $listaPrecio->modificado }}</td>

                <td style="vertical-align: middle; text-align:center;">
                    <a href="{{ route('administracion.listaprecios.show', $listaPrecio->razon_social) }}"
                        class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                        title="Editar cotización">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <form action="{{ route('administracion.listaprecios.destroy', $listaPrecio->proveedor_id) }}"
                        id="borrar-{{ $listaPrecio->proveedor_id }}" method="post" class="d-inline">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-link text-danger" data-toggle="tooltip" data-placement="bottom"
                            title="Borrar listado" onclick="borrarListado({{ $listaPrecio->proveedor_id }})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>