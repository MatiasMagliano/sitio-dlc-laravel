<div class="align-middle text-center">
    @if ($producto->hospitalario === 1)
        <strong>HOSPITALARIO</strong>
    @endif
    @if ($producto->trazabilidad === 1)
        <span style="color: red; font-weight:800;">TRAZABLE </span>
    @endif
</div>
