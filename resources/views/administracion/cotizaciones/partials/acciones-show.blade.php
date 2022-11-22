<div class="btn-group" role="group" aria-label="Acciones de direcciones de entrega">
    <button role="button" class="btn btn-link open_modal" data-toggle="modal" data-target="#modalModifProducto" data-toggle="tooltip" data-placement="middle"
        title="Editar producto" value="{{$cotizacion}}">
        <i class="fas fa-pencil-alt"></i>
    </button>
    <form action="{{ route('administracion.cotizaciones.borrar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $productoCotizado]) }}"
        id="frm-borrar-{{ $cotizacion }}" method="post" class="d-inline">
        @csrf
        @method('delete')

        <a role="button" class="btn btn-link text-danger" data-toggle="tooltip"
            data-placement="middle" title="Borrar producto"
            onclick="
                    event.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: '¿Está seguro de eliminar este producto?',
                        html: '<span style=\'color: red; font-weight:800; font-size:1.3em;\'>¡ATENCION!</span>',
                        confirmButtonText: 'Borrar',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#frm-borrar-{{ $cotizacion }}').submit()
                        }
                    });
                ">
            <i class="fas fa-trash-alt"></i>
        </a>
    </form>
</div>
