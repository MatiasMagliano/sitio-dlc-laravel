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
                <h1 class="display-4">Droguería de la Ciudad</h1>
                <br>
                <h3>Orden de Trabajo - PICKING LIST</h3>
            </div>
            <hr>
            <div class="container">
                @include('administracion.ordenestrabajo.partials.datos_cotizacion', [
                    'cotizacion' => $ordentrabajo->cotizacion,
                ])
            </div>
        </div>
        <div class="card-body">
            <table class="table bordeless">
                <thead>
                    <th>Código de proveedor</th>
                    <th>Descripción de producto</th>
                    <th>Proveedor</th>
                    <th>Lotes seleccionados</th>
                    <th>Cantidad</th>
                </thead>
                <tbody>
                    @foreach ($ordentrabajo->productos as $producto)
                        <tr>
                            <td>
                                @php
                                    $cod_proveedor = DB::select('SELECT lp.codigoProv FROM lista_precios lp WHERE lp.producto_id = ? AND lp.presentacion_id = ?', [$producto->producto_id, $producto->presentacion_id]);
                                    dd($cod_proveedor);
                                @endphp
                            </td>
                            <td>la</td>
                            <td>la</td>
                            <td>
                                @php
                                    $lotes = json_decode($producto->lotes, true);
                                @endphp
                                @if (count($lotes) > 1)
                                    @foreach ($lotes as $lote)
                                        @foreach ($lote as $clave => $valor)
                                            <strong>{{ $clave }}</strong>: {{ $valor }} <br>
                                        @endforeach
                                        <hr>
                                    @endforeach
                                @else
                                    @foreach ($lotes as $lote)
                                        @foreach ($lote as $clave => $valor)
                                            <strong>{{ $clave }}</strong>: {{ $valor }} <br>
                                        @endforeach
                                    @endforeach
                                @endif
                            </td>
                            <td></td>
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
