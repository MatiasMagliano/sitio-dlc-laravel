{{-- ESTADOS DINAMICOS y ACCIONES DINÁMICAS --}}
@switch($cotizacion->estado_id)
    @case(4)
        <div class="btn-group-vertical">
            <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                class="btn btn-sm btn-default" target="_blank">
                Cotización
            </a>
            @if ($cotizacion->archivos()->exists())
                <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'provision']) }}"
                    class="btn btn-sm btn-default" target="_blank">
                    Provisión
                </a>
            @else
                <div class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom" title="No se adjuntó provisión">
                    Sin archivos
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
            @if ($cotizacion->archivos()->exists())
                <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'rechazo']) }}"
                    class="btn btn-link" target="_blank">
                    <i class="fas fa-file-download"></i>
                </a>
            @else
                <div class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="No se adjuntó comparativo">
                    <i class="fas fa-file-excel"></i>
                </div>
            @endif
        </div>
    @break

    @default
        <p>-</p>

        {{-- ACCIONES DINÁMICAS --}}
        <td style="vertical-align: middle;">
            <small class="form-text text-muted">Sin acciones</small>
    @endswitch
