<table class="table table-responsive-md" width="100%">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Dirigido a</th>
            <th>Fecha de creación</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{$documento->nombre_documento}}
            </td>
            <td class="align-middle">
                <span class="badge badge-success">{{Str::upper($documento->tipo_documento)}}</span>
            </td>
            <td class="aling-middle">
                Sr/a: {{$documento->dirigido_a}}
            </td>
            <td>
                {{$documento->created_at->format('d/m/Y')}}
            </td>
        </tr>
    </tbody>
</table>
