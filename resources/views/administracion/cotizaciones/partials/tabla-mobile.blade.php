<table class="table table-bordered" width="100%">
    <thead>
        <tr>
            <th style="vertical-align: middle; text-align:center;">Datos de cotización</th>
            <th style="vertical-align: middle; text-align:center;">ESTADO</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cotizaciones as $cotizacion)
            <tr class="{{ $cotizacion->rechazada ? 'table-secondary' : '' }}">
                <td style="vertical-align: middle;">
                    <strong style="font-size: 1.2em">{{ $cotizacion->identificador }}</strong> <br>
                    <div class="ml-3">
                        Creada por: {{ $cotizacion->user->name }}, el {{ $cotizacion->created_at->format('d/m/y') }} <br>
                    Cliente: {{ $cotizacion->cliente->razon_social }} <br>
                    Líneas: {{ $cotizacion->productos->count() }} <br>
                    Monto total: @if ($cotizacion->monto_total == 0.00) finalice para determinar. @else $ {{ number_format($cotizacion->monto_total, 2, ',', '.') }} @endif
                    </div>
                </td>
                {{-- ESTADOS DINAMICOS y ACCIONES DINÁMICAS --}}
                @switch($cotizacion->estado_id)
                    @case(1)
                        {{-- ESTADOS DINAMICOS --}}
                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-warning">{{ $cotizacion->estado->estado }}</span> <br>
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
                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-info">{{ $cotizacion->estado->estado }}</span><br>
                            <span>{{ $cotizacion->finalizada->format('d/m/y') }}</span> <br> <br>
                            <a id="botonPresentar" class="btn btn-xs btn-info"
                                href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                                onclick="recargar()">
                                Presentar
                            </a>
                        </td>
                    @break

                    @case(3)
                        {{-- ESTADOS DINAMICOS --}}
                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-secondary">{{ $cotizacion->estado->estado }}</span><br>
                            <span>{{ $cotizacion->presentada->format('d/m/y') }}</span> <br> <br>
                            <div class="btn-group-vertical">
                                <button type="button" class="btn btn-xs btn-primary" data-toggle="modal"
                                    data-target="#modalAprobarCotizacion" id="{{ $cotizacion->id }}">Aprobar</button>
                                <button type="button" class="btn btn-xs btn-danger" data-toggle="modal"
                                    data-target="#modalRechazarCotizacion" id="{{ $cotizacion->id }}">Rechazar</button>
                            </div>
                        </td>
                    @break

                    @case(4)
                        {{-- ESTADOS DINAMICOS --}}
                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-success">{{ $cotizacion->estado->estado }}</span><br>
                            <span>{{ $cotizacion->confirmada->format('d/m/y') }}</span> <br> <br>
                            <div class="btn-group-vertical">
                                <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                                    class="btn btn-xs btn-default" target="_blank">
                                    Cotización
                                </a>
                                @if ($cotizacion->archivos()->exists())
                                    <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'provision']) }}"
                                        class="btn btn-xs btn-default" target="_blank">
                                        Provisión
                                    </a>
                                @else
                                    <div class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="bottom"
                                        title="No se adjuntó provisión">
                                        Sin archivos
                                    </div>
                                @endif
                            </div>
                        </td>
                    @break

                    @case(5)
                        {{-- ESTADOS DINAMICOS --}}
                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-danger">{{ $cotizacion->estado->estado }}</span><br>
                            <span>{{ $cotizacion->rechazada->format('d/m/y') }}</span> <br> <br>
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
                        {{-- ACCIONES DINÁMICAS --}}
                        <td style="vertical-align: middle; text-align:center;">
                            <span class="badge badge-success">APROBADA</span><br>
                            <span>{{ $cotizacion->confirmada->format('d/m/y') }}</span> <br> <br>
                            <div class="btn-group-vertical">
                                <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'cotizacion']) }}"
                                    class="btn btn-xs btn-default" target="_blank">
                                    Cotización
                                </a>
                                @if ($cotizacion->archivos()->exists())
                                    <a href="{{ route('administracion.cotizaciones.descargapdf', ['cotizacion' => $cotizacion, 'doc' => 'provision']) }}"
                                        class="btn btn-xs btn-default" target="_blank">
                                        Provisión
                                    </a>
                                @else
                                    <div class="btn btn-xs btn-default">
                                        Sin archivos
                                    </div>
                                @endif
                            </div>
                        </td>
                    @break

                    @default
                        <td style="vertical-align: middle;">
                            <p>-</p> <br>
                            <small class="form-text text-muted">Sin acciones</small>
                        </td>
                @endswitch
            </tr>
        @endforeach
    </tbody>
</table>
