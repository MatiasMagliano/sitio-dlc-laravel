<div class="align-middle text-center">
    @if ($presentacion->hospitalario === 1 && $presentacion->trazabilidad === 1)
        <strong>HOSPITALARIO</strong> y <span style="color: red; font-weight:800;">TRAZABLE </span>
    @else
        @if ($presentacion->hospitalario === 1)
            <strong>HOSPITALARIO</strong>
        @endif

        @if ($presentacion->trazabilidad === 1)
            <span style="color: red; font-weight:800;">TRAZABLE </span>
        @endif
    @endif
</div>
