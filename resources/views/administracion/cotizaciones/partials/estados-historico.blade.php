{{-- ESTADOS DINÁMICOS --}}
@switch($cotizacion->estado_id)
    @case("1")
        <span class="badge badge-warning">{{ $cotizacion->estado }}</span>
    @break

    @case("2")
        <span class="badge badge-info">{{ $cotizacion->estado }}</span>
    @break

    @case("3")
        <span class="badge badge-secondary">{{ $cotizacion->estado }}</span>
    @break

    @case("4")
        <span class="badge badge-secondary">{{ $cotizacion->estado }}</span>
    @break

    @case("5")
        <span class="badge badge-secondary">{{ $cotizacion->estado }}</span><br>
    @break

    @case("6" || "7")
        <span class="badge badge-secondary">APROBADA</span><br>
    @break

    @default
        <p>-</p>
    @break
@endswitch
