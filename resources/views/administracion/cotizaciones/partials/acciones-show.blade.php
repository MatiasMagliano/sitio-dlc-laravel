<div class="btn-group" role="group" aria-label="Acciones de direcciones de entrega">
    <a href="{{ route('administracion.cotizaciones.editar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $cotizado]) }}"
        class="btn btn-link" data-toggle="tooltip" data-placement="middle"
        title="Editar producto">
        <i class="fas fa-pencil-alt"></i>
    </a>
    <form action="{{ route('administracion.cotizaciones.borrar.producto', ['cotizacion' => $cotizacion, 'productoCotizado' => $cotizado]) }}"
        id="frm-borrar-{{ $cotizacion->id }}" method="post" class="d-inline">

        @csrf
        @method('delete')
        <a role="button" class="btn btn-link text-danger" data-toggle="tooltip"
            data-placement="middle" title="Borrar producto"
            onclick="
                    event.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: '¿Está seguro de eliminar este punto de entrega?',
                        html: '<p style=color: red; font-wieght:800; font-size:1.3em;>¡ATENCION!</p>',
                        confirmButtonText: 'Borrar',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#frm-borrar-{{ $cotizacion->id }}').submit()
                        }
                    });
                ">
            <i class="fas fa-trash-alt"></i>
        </a>
    </form>
</div>
