<div class="btn-group" role="group" aria-label="Acciones de direcciones de entrega">
    <a href="{{ route('administracion.dde.edit', ['dde' => $dde]) }}"
        class="btn btn-link" data-toggle="tooltip" data-placement="middle"
        title="Editar dirección de entrega">
        <i class="fas fa-pencil-alt"></i>
    </a>
    <form action="{{ route('administracion.dde.destroy', $dde) }}"
        id="frm-borrar-{{ $dde }}" method="post" class="d-inline">

        @csrf
        @method('delete')
        <a role="button" class="btn btn-link text-danger" data-toggle="tooltip"
            data-placement="middle" title="Borrar dirección de entrega"
            onclick="
                    event.preventDefault();

                    let advertencia = 'Borrar una dirección de entrega implica archivarla. La mismo no se presentará en ningún listado, excepto en el histórico de cotizaciones. Sin embargo todas las cotizaciones en estado pendientes, serán descartadas inmediatamente';
                    Swal.fire({
                        icon: 'warning',
                        title: '¿Está seguro de eliminar este punto de entrega?',
                        html: '<p style=color: red; font-wieght:800; font-size:1.3em;>¡ATENCION!</p>' + advertencia,
                        confirmButtonText: 'Borrar',
                        showCancelButton: true,
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#frm-borrar-{{ $dde }}').submit()
                        }
                    });
                ">
            <i class="fas fa-trash-alt"></i>
        </a>
    </form>
</div>
