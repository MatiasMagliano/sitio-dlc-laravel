<td>
    @foreach ($datos['PROVEEDORES'] as $proveedor)
        {{ $proveedor['COD_PROV'] }}
        @if (!$loop->last)
            <br>
        @endif
    @endforeach
</td>
<td>
    @foreach ($datos['PROVEEDORES'] as $proveedor)
        {{ $proveedor['PROVEEDOR'] }}
        @if (!$loop->last)
            <br>
        @endif
    @endforeach
</td>
<td>{{$producto}}</td>
<td>
    @foreach ($datos['LOTES'] as $lote)
        {{ $lote['identificador'] }}
        @if (!$loop->last)
            <br>
        @endif
    @endforeach
</td>
<td>
    @foreach ($datos['LOTES'] as $lote)
        {{ $lote['cantidad'] }}
        @if (!$loop->last)
            <br>
        @endif
    @endforeach
</td>
