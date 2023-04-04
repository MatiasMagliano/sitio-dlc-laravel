<table id="tabla" class="table table-bordered {{--table-responsive-md--}}" width="100%">
    <thead class="bg-gray">
        <tr>
            <th>Proveedor</th>
            <th>Fecha Alta</th>
            <th>Fecha Último Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tfoot style="display: table-header-group;">
        <tr class="bg-gradient-light">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach ($listaPrecios as $listaPrecio)

            <tr id={{ $listaPrecio->cuit }}>
                <td>{{ strtoupper($listaPrecio->razon_social) }}<br>
                    {{ $listaPrecio->cuit }}
                </td>
                <td>
                    @if(isset($listaPrecio->creado))
                        {{ $listaPrecio->creado }}
                    @else
                        {{ $listaPrecio->alta }}<br>
                        <span class="badge badge-info">Alta de proveedor</span>
                    @endif
                </td>
                <td>
                    @if(isset($listaPrecio->modificado))
                        {{ $listaPrecio->modificado }}
                    @else
                        <h5 class="mt-2"><span class="badge badge-warning">Sin listado de precios</span></h5>
                    @endif
                </td>

                <td style="vertical-align: middle; text-align:center;">
                    @if(isset($listaPrecio->creado))
                        <a href="{{ route('administracion.listaprecios.editar', ['razon_social' => $listaPrecio->razon_social]) }}" class="btn btn-link"
                            data-toggle="tooltip" data-placement="bottom" title="Editar lista de precios">
                            <i class="fas fa-search "></i>
                        </a>
                        <form action="{{ route('administracion.listaprecios.vaciar', ['proveedor_id' => $listaPrecio->proveedor_id]) }}"
                            id="borrar-{{ $listaPrecio->proveedor_id }}" method="post" class="d-inline">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-link text-danger" data-toggle="tooltip" data-placement="bottom"
                                title="Borrar listado" onclick="borrarListado({{ $listaPrecio->proveedor_id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('administracion.listaprecios.editar', ['razon_social' => $listaPrecio->razon_social]) }}" class="btn btn-link text-success"
                            data-toggle="tooltip" data-placement="bottom" title="Agregar lista de precios">
                            <i class="fas fa-plus"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
