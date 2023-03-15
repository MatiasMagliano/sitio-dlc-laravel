<table class="table table-responsive-md" width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Razón Social</th>
            <th>CUIT</th>
            <th>Dirección</th>
            <th>Alta</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($proveedor as $proveedorItem)
            <tr>
                <td style="vertical-align: middle;">{{ $proveedorItem->id }}</td>
                <td class="RazonSocial" style="vertical-align: middle;">{{ $proveedorItem->razon_social }}</td>
                <td style="vertical-align: middle;">{{ $proveedorItem->cuit }}</td>
                <td style="vertical-align: middle;">{{ $proveedorItem->direccion }}</td>
                <td style="vertical-align: middle;">{{ $proveedorItem->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>