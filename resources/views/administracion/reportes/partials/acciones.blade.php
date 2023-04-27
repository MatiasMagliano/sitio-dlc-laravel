<div class="btn-group" role="group" aria-label="Acciones cotizacion aprobada">
    @switch($documento->tipo_documento)
        @case('reporte')
            <a href="{{route('administracion.reportes.show.reporte', ['documento' => $documento->id])}}" class="btn btn-link"
                data-toggle="tooltip" data-placement="bottom" title="Ejecutar el documento">
                <i class="fas fa-search"></i>
            </a>
            <a href="{{route('administracion.reportes.editar.reporte', ['documento' => $documento->id])}}"
                class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Editar el documento">
                <i class="fas fa-pencil-alt"></i>
            </a>
            @break
        @case('listado')
            <a href="{{route('administracion.reportes.editar.listado', ['documento' => $documento->id])}}"
                class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Editar el documento">
                <i class="fas fa-pencil-alt"></i>
            </a>
            @break
        @default

    @endswitch
    <a href="#"
        class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Borrar el documento">
        <i class="fas fa-trash-alt"></i>
    </a>
</div>
