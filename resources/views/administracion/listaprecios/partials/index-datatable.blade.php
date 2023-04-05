<table id="tabla" class="table table-bordered {{--table-responsive-md--}}" width="100%">
    <thead class="bg-gray">
        <tr>
            <th>Proveedor</th>
            <th>Alta</th>
            <th>Último Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    {{--<tfoot style="display: table-header-group;">
        <tr class="bg-gradient-light">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>--}}
    <tbody>
        @foreach ($listaPrecios as $listaPrecio)

            <tr id={{ $listaPrecio->cuit }}>
                <td class="col-7">
                    {{-- $listaPrecio->cuit }}, {{ strtoupper($listaPrecio->razon_social) --}}
                    <div class="row">
                        <div class="col-8">
                            {{ strtoupper($listaPrecio->razon_social) }}<br>{{ $listaPrecio->cuit }}
                        </div>
                        @if($listaPrecio->prods == 0)
                            <div class="col-4">
                                <h5 class="mt-2"><span class="badge badge-warning">Nuevo Proveedor</span></h5>
                            </div>
                        @endif
                    </div>

                </td>
                <td class="col-2">
                    {{ $listaPrecio->creado }}
                </td>
                <td class="fechaUpdate">
                    @if(isset($listaPrecio->modificado))
                        {{ $listaPrecio->modificado }}
                    @else
                        <h5 class="mt-2"><span class="badge badge-warning">Sin listado de precios</span></h5>
                    @endif
                </td>

                <td style="vertical-align: middle; text-align:center;">
                    @if($listaPrecio->prods > 0)
                        <a href="{{ route('administracion.listaprecios.editar', ['razon_social' => $listaPrecio->razon_social]) }}" class="btn btn-link"
                            data-toggle="tooltip" data-placement="bottom" title="Editar lista de precios">
                            <i class="fas fa-search "></i>
                        </a>
                        @if($listaPrecio->actives == 0)
                            <form action="{{ route('administracion.listaprecios.volverListado', ['proveedor_id' => $listaPrecio->proveedorId]) }}"
                                id="restaurar-{{ $listaPrecio->proveedorId }}" method="post" class="d-inline">
                                @csrf
                                @method('get')
                                <button type="button" class="btn btn-sm btn-link text-warning" data-toggle="tooltip" data-placement="bottom"
                                    title="Restaurar ultimo borrado" onclick="restaurarListado({{ $listaPrecio->proveedorId }})">
                                    <i class="fas fa-undo-alt"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('administracion.listaprecios.vaciar', ['proveedor_id' => $listaPrecio->proveedorId]) }}"
                                id="borrar-{{ $listaPrecio->proveedorId }}" method="post" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" class="btn btn-sm btn-link text-danger" data-toggle="tooltip" data-placement="bottom"
                                    title="Borrar listado" onclick="borrarListado({{ $listaPrecio->proveedorId }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif

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
