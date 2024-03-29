<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        body{
            margin: 0cm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            display: block;
        }

        .nueva-linea{
            break-before: always;
            break-after: auto;
        }

        .contenedor-encabezado{
            background-color: #eee;
            font-family: Arial, Helvetica, sans-serif;
            border-radius: 2%;
        }

        .contenedor-encabezado .titulo{
            font-size: 25px;
            font-weight: 700;
        }

        .contenedor-encabezado .subtitulo{
            font-size: 20px;
            font-weight: 500;
        }

        .encabezado{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
        }
    </style>
    <title>Cotización {{$cotizacion->identificador}}</title>
</head>
<body class="d-flex flex-column">
    <div class="card">
        <div class="card-header">
            <p class="text-right">Córdoba, {{\Carbon\Carbon::now()}}</p>
            <div class="contenedor-encabezado text-center">
                <span class="titulo">Droguería de la Ciudad - COTIZACION</span>
                <br>
                <span class="subtitulo">{{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('lugar_entrega')->get('0')}}</span>
            </div>
            <hr>
            <span style="font-size: 17px">Cotización: <strong>{{$cotizacion->identificador}}</strong></span> <br>
            <ul class="list-group">
                <li class="list-group-item py-1">
                    <strong>Cliente</strong>: {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('razon_social')->get('0')}}
                </li>
                <li class="list-group-item  py-1">
                    <strong>Lugar de entrega</strong>: {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('lugar_entrega')->get('0')}}
                </li>
                <li class="list-group-item  py-1">
                    <strong>Domicilio</strong>: {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('domicilio')->get('0')}},
                        {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('nombre')->get('0')}},
                        {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('nombre_completo')->get('0')}}
                </li>
                <li class="list-group-item py-1">
                    <strong>Correo electrónico</strong>: {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('email')->get('0')}}
                </li>
            </ul>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="encabezado">Fecha</th>
                        <th class="encabezado">Cliente</th>
                        <th class="encabezado">Prod. cotizados</th>
                        <th class="encabezado">Unidades</th>
                        <th class="encabezado">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="vertical-align: middle;">
                            {{$cotizacion->created_at->format('d/m/Y')}}<br>{{$cotizacion->identificador}}
                        </td>
                        <td>
                            {{$cotizacion->cliente->razon_social}}<br>
                            {{$cotizacion->cliente->tipo_afip}}: {{$cotizacion->cliente->afip}}
                        </td>
                        <td style="vertical-align: middle; text-align: center;">{{$cotizacion->productos->count()}}</td>
                        <td style="vertical-align: middle; text-align: center;">{{$cotizacion->productos->sum('cantidad')}}</td>
                        <td style="vertical-align: middle; text-align: center;">$ {{$cotizacion->productos->sum('total')}}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center" style="font-size: 15px;">
                <span>Detalle</span>
            </div>
            <br>
            <table id="tabla-detalle" class="table table-sm" data-show-print="true" width="100%">
                <thead>
                    <th scope="col">Línea</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Precio unitario</th>
                    <th scope="col">Subtotal</th>
                </thead>
                <tbody>
                    @php $i = 0; /*variable contadora del Nº Orden*/@endphp
                    @foreach ($cotizacion->productos as $cotizado)
                        <tr>
                            <th scope="row" class="text-center">{{++$i}}</td>
                            <td>{{--Producto: producto+presentacion--}}
                                {{$cotizado->producto->droga}}, {{$presentaciones[$cotizado->presentacion_id-1]->forma}} {{$presentaciones[$cotizado->presentacion_id-1]->presentacion}}
                            </td>
                            <td class="text-center">
                                {{$cotizado->cantidad}}
                            </td>
                            <td class="text-center">
                                $ {{$cotizado->precio}}
                            </td>
                            <td class="text-right">
                                $ {{$cotizado->total}}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" style="background-color: #ccc; text-align: center;"> IMPORTE TOTAL</td>
                        <td style="background-color: #ccc; text-align: right;">$ {{$cotizacion->productos->sum('total')}}</td>
                    </tr>
                </tbody>
            </table>
            <ul class="list-group nueva-linea">
                <li class="list-group-item">
                    @php
                        //$montoLetras = new NumberFormatter('es_AR', NumberFormatter::SPELLOUT);
                        $total = $cotizacion->productos->sum('total');
                        $total = explode('.', $total);
                        // SE DETERMINA SI EL TOTAL TIENE CENTAVOS O NO (porque el explode detecta los ceros)
                        if(sizeof($total)>1)
                        {
                            $letras = 'En letras: '. Str::ucfirst($total[0]). ' pesos, con ' .Str::ucfirst($total[1]). ' centavos.';
                        }
                        else
                        {
                            $letras = 'En letras: '. Str::ucfirst($total[0]). ' pesos, con cero centavos.';
                        }
                    @endphp
                    <strong style="font-size: 11px">{{$letras}}</strong>
                    <p>ACLARACIÓN: La presente cotización, no incluye precios con IVA.</p>
                </li>
            </ul>
        </div>
        <div class="card-footer" style="background-color: #fff;">
            {{-- PIE DE PÁGINA --}}
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <span class="encabezado mb-1">Condiciones*</span>
                    </div>
                    <p class="mb-1">
                        {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('condiciones')->get('0')}}
                    </p>
                    <small class="text-muted">*Provistas por el cliente.</small>
                </li>

                <li class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <span class="encabezado mb-1">Observaciones</span>
                    </div>
                    <p class="mb-1">
                        {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('observaciones')->get('0')}}
                    </p>
                </li>
            </ul>

            <p class=" text-right">Documento PDF generado por: {{auth()->user()->name}}</p>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
