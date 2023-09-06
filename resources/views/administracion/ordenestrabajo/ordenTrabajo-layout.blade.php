<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body {
            margin: 0cm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
        }
    </style>
    <title>Orden de trabajo {{ $ordentrabajo->cotizacion->identificador }}</title>
</head>

<body class="d-flex flex-column">
    <div class="card">
        <div class="card-header">
            <p class="text-right">Córdoba, {{ \Carbon\Carbon::now()->isoFormat('LL') }}</p>
            <div class="contenedor-encabezado text-center">
                <h1>Droguería de la Ciudad</h1>
                <br>
                <h3>Orden de Trabajo - PICKING LIST</h3>
            </div>
            <hr>
            @include('administracion.ordenestrabajo.partials.datos_cotizacion', [
                'cotizacion' => $ordentrabajo->cotizacion,
                'ordentrabajo' => $ordentrabajo
            ])
        </div>
        <div class="card-body">
            <table class="table bordeless">
                <thead>
                    <th>Códigos de proveedores</th>
                    <th>Proveedores disponibles</th>
                    <th>Descripción de producto</th>
                    <th>Identificador de lotes</th>
                    <th>Cantidad a seleccionar</th>
                </thead>
                <tbody>
                    @foreach ($prod_ordentrabajo as $producto => $datos)
                        <tr>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
