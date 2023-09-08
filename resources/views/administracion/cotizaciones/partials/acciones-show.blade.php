@if ($estado == 1)
    <div class="btn-group" role="group" aria-label="Acciones de direcciones de entrega">
        <button role="button" class="btn btn-link open_modal" data-toggle="modal" data-target="#modalModifProducto" data-toggle="tooltip" data-placement="middle"
            title="Editar producto" value="{{$productoCotizado}}">
            <i class="fas fa-pencil-alt"></i>
        </button>
        <form action="{{ route('administracion.cotizaciones.borrar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $productoCotizado]) }}"
            id="frm-borrar-{{ $productoCotizado }}" method="post" class="d-inline">
            @csrf
            @method('delete')

            <a role="button" class="btn btn-link text-danger" data-toggle="tooltip"
                data-placement="middle" title="Borrar producto"
                onclick="
                        event.preventDefault();
                        let advertencia = 'Esta acción no se puede deshacer';
                        Swal.fire({
                            icon: 'warning',
                            title: '¿Está seguro de eliminar este producto?',
                            html: '<span style=\'color: red; font-weight:800; font-size:1.3em;\'>¡ATENCION!</span><br>'+advertencia,
                            confirmButtonText: 'Borrar',
                            showCancelButton: true,
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#frm-borrar-{{ $productoCotizado }}').submit()
                            }
                        });
                    ">
                <i class="fas fa-trash-alt"></i>
            </a>
        </form>
    </div>
@else
    <p>-</p>
@endif
