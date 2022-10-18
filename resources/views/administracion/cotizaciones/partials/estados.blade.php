{{-- ESTADOS DINÁMICOS --}}
@switch($cotizacion->estado_id)
    @case(1)
        <span class="badge badge-warning">{{ $cotizacion->estado->estado }}</span>
    @break

    @case(2)
        <span class="badge badge-info">{{ $cotizacion->estado->estado }}</span>
    @break

    @case(3)
        <span class="badge badge-secondary">{{ $cotizacion->estado->estado }}</span>
    @break

    @case(4)
        <span class="badge badge-success">{{ $cotizacion->estado->estado }}</span>
    @break

    @case(5)
        <span class="badge badge-danger">{{ $cotizacion->estado->estado }}</span><br>
    @break

    @case(6 || 7)
        <span class="badge badge-success">APROBADA</span><br>
    @break

    @default
        <p>-</p>
    @break

@endswitch
