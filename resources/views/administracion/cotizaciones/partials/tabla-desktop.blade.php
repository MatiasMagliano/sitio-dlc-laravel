<table class="table table-bordered" width="100%">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Identificador/Usuario</th>
            <th>Cliente</th>
            <th>Cantidad de Líneas</th>
            <th>Importe total</th>
            <th>ESTADO</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cotizaciones as $cotizacion)
            <tr class="{{ $cotizacion->rechazada ? 'table-secondary' : '' }}">
                <td style="vertical-align: middle;">
                    {{ $cotizacion->created_at->format('d/m/Y') }}
                </td>
                <td style="vertical-align: middle;">
                    <strong>{{ $cotizacion->identificador }}</strong>
                    <br>
                    Creado por: {{ $cotizacion->user->name }}
                </td>
                <td style="vertical-align: middle;">
                    {{ $cotizacion->cliente->razon_social }}<br>
                    {{ $cotizacion->cliente->tipo_afip }}: {{ $cotizacion->cliente->afip }}
                </td>
                <td style="vertical-align: middle; text-align:center;">
                    {{ $cotizacion->productos->count() }}
                </td>
                <td style="vertical-align: middle; text-align:center;">
                    @if ($cotizacion->monto_total == 0.00)
                        cotización sin finalizar
                    @else
                        $ {{ number_format($cotizacion->monto_total, 2, ',', '.') }}
                    @endif
                </td>
                {{-- ESTADOS DINAMICOS y ACCIONES DINÁMICAS --}}
                @switch($cotizacion->estado_id)
                    @case(1)
                        {{-- ESTADOS DINAMICOS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-warning">{{ $cotizacion->estado->estado }}</span>
                        </td>

                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}"
                                class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Editar cotización">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('administracion.cotizaciones.destroy', $cotizacion) }}"
                                id="borrar-{{ $cotizacion->id }}" method="post" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="button" class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                    title="Borrar cotización" onclick="borrarCotizacion({{ $cotizacion->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    @break

                    @case(2)
                        {{-- ESTADOS DINAMICOS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-info">{{ $cotizacion->estado->estado }}</span><br>
                            <span>{{ $cotizacion->finalizada->format('d/m/Y') }}</span>
                        </td>

                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <a id="botonPresentar" class="btn btn-sm btn-info"
                                href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                                onclick="recargar()">
                                Presentar
                            </a>
                        </td>
                    @break

                    @case(3)
                        {{-- ESTADOS DINAMICOS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-secondary">{{ $cotizacion->estado->estado }}</span><br>
                            <span>{{ $cotizacion->presentada->format('d/m/Y') }}</span>
                        </td>

                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <div class="btn-group-vertical">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#modalAprobarCotizacion" id="{{ $cotizacion->id }}">Aprobar</button>
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                    data-target="#modalRechazarCotizacion" id="{{ $cotizacion->id }}">Rechazar</button>
                            </div>
                        </td>
                    @break

                    @case(4)
                        {{-- ESTADOS DINAMICOS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-success">{{ $cotizacion->estado->estado }}</span><br>
                            <span>{{ $cotizacion->confirmada->format('d/m/Y') }}</span>
                        </td>

                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
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
                                    <div class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="bottom"
                                        title="No se adjuntó provisión">
                                        Sin archivos
                                    </div>
                                @endif
                            </div>
                        </td>
                    @break

                    @case(5)
                        {{-- ESTADOS DINAMICOS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-danger">{{ $cotizacion->estado->estado }}</span><br>
                            <span>{{ $cotizacion->rechazada->format('d/m/Y') }}</span>
                        </td>

                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <div class="btn-group" role="group" aria-label="Acciones cotizacion rechazada">
                                <a href="{{ route('administracion.cotizaciones.show', ['cotizacione' => $cotizacion]) }}"
                                    class="btn btn-link" data-toggle="tooltip" data-placement="bottom" title="Ver cotización">
                                    <i class="fas fa-search "></i>
                                </a>
                                @if ($cotizacion->archivos()->exists())
                                    <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'rechazo']) }}"
                                        class="btn btn-link" target="_blank">
                                        <i class="fas fa-file-download"></i>
                                    </a>
                                @else
                                    <div class="btn btn-link" data-toggle="tooltip" data-placement="bottom"
                                        title="No se adjuntó comparativo">
                                        <i class="fas fa-file-excel"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                    @break

                    @case(6 || 7)
                        {{-- ESTADOS DINAMICOS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-success">APROBADA</span><br>
                            <span>{{ $cotizacion->confirmada->format('d/m/Y') }}</span>
                        </td>

                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
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
                                    <div class="btn btn-sm btn-default">
                                        Sin archivos
                                    </div>
                                @endif
                            </div>
                        </td>
                    @break

                    @default
                        <td style="vertical-align: middle; text-align:center;">
                            <p>-</p>
                        </td>

                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle;">
                            <small class="form-text text-muted">Sin acciones</small>
                        </td>
                @endswitch
            </tr>
        @endforeach
    </tbody>
</table>
