{{-- ACCIONES DINÁMICAS --}}
@switch($cotizacion->estado_id)
    @case(1)
        <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}" class="btn btn-link"
            data-toggle="tooltip" data-placement="bottom" title="Editar cotización">
            <i class="fas fa-pencil-alt"></i>
        </a>
        <form action="{{ route('administracion.cotizaciones.destroy', $cotizacion) }}" id="borrar-{{ $cotizacion->id }}"
            method="post" class="d-inline">
            @csrf
            @method('delete')
            <button type="button" class="btn btn-link text-danger" data-toggle="tooltip" data-placement="bottom"
                title="Borrar cotización" onclick="borrarCotizacion({{ $cotizacion->id }})">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    @break

    @case(2)
        <a id="botonPresentar" class="btn btn-sm btn-info"
            href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
            onclick="recargar()">
            Presentar
        </a>
    @break

    @case(3)
        <div class="btn-group-vertical">
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                data-target="#modalAprobarCotizacion" id="{{ $cotizacion->id }}">Aprobar</button>
            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                data-target="#modalRechazarCotizacion" id="{{ $cotizacion->id }}">Rechazar</button>
        </div>
    @break

    @case(4 || 6 || 7)
        <div class="btn-group" role="group" aria-label="Acciones cotizacion aprobada">
            <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}" class="btn btn-link"
                data-toggle="tooltip" data-placement="bottom" title="Ver cotización">
                <i class="fas fa-search "></i>
            </a>
            <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                class="btn btn-link" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Descargar cotización">
                <i class="fas fa-file-download"></i>
            </a>
            @if ($cotizacion->archivos()->exists())
                <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'provision']) }}"
                    class="btn btn-link" target="_blank" data-toggle="tooltip" data-placement="bottom"
                    title="Descargar provisión">
                    <i class="fas fa-arrow-down"></i>
                </a>
            @else
                <div class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="No se adjuntó provisión">
                    <i class="fas fa-times"></i>
                </div>
            @endif
        </div>
    @break

    @case(5)
        <div class="btn-group" role="group" aria-label="Acciones cotizacion rechazada">
            <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}" class="btn btn-link"
                data-toggle="tooltip" data-placement="bottom" title="Ver cotización">
                <i class="fas fa-search "></i>
            </a>
            <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                class="btn btn-link" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Descargar cotización">
                <i class="fas fa-file-download"></i>
            </a>
            @if ($cotizacion->archivos()->exists())
                <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'rechazo']) }}"
                    class="btn btn-link" target="_blank">
                    <i class="fas fa-arrow-down"></i>
                </a>
            @else
                <div class="btn btn-link text-reset" data-toggle="tooltip" data-placement="bottom"
                    title="No se adjuntó comparativo">
                    <i class="fas fa-times"></i>
                </div>
            @endif
        </div>
    @break

    @default
        <p>-</p>
    @break

@endswitch
