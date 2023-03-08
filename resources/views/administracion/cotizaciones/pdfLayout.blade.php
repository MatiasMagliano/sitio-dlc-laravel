<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="{{ public_path('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ public_path('css/estilosPDF.css') }}">
        <title>Cotización {{ $cotizacion->identificador }}</title>
    </head>

    <body>
        <header>
            <table class="table" style="padding-left: 2em;padding-right: 2em;">
                <tr>
                    <th scope="col" style="text-align: left;padding: 1em 3em"><span>Droguería de la Ciudad</span></th>

                    <th scope="col" style="padding: 1em 3em;">
                        <div class="row">
                            <div class="col">
                                <span style="text-align:right">COTIZACION</span>
                            </div>
                            @if ($cotizacion->confirmada)
                                <div class="col">
                                    <span style="text-align:right; color:darkgreen">COTIZACION APROBADA</span>
                                </div>
                            @endif
                            @if ($cotizacion->rechazada)
                                <div class="col">
                                    <span style="text-align:right; color:darkred">COTIZACION RECHAZADA</span>
                                </div>
                            @endif
                        </div>
                    </th>
                </tr>

                {{-- DATOS DEL EMISOR --}}
                <tr style="font-size:11px;margin:0;padding: 0;">
                    <td style="padding: 0;border-top-style:none">
                        <table>
                            <tr>
                                <td style="text-align: left; width: 23%;border-top-style:none">
                                    <ul class="list-group">
                                        <li class="list-group-item list-data">Razón Social:</li>
                                        <li class="list-group-item list-data">Domicilio:</li>
                                        <li class="list-group-item list-data">CUIT:</li>
                                        <li class="list-group-item list-data">Ingresos Brutos:</li>
                                        <li class="list-group-item list-data">Inicio de Actividad:</li>
                                        <li class="list-group-item list-data"></li>
                                    </ul>
                                </td>
                                <td style="text-align: left;border-top-style:none">
                                    <ul class="list-group">
                                        <li class="list-group-item list-data">MARTIN SEGURA E HIJOS S.A.</li>
                                        <li class="list-group-item list-data">Raymundo Montenegro 2654 - CÓRDOBA</li>
                                        <li class="list-group-item list-data">30-71655664-3</li>
                                        <li class="list-group-item list-data">285006678</li>
                                        <li class="list-group-item list-data">22/05/2001</li>
                                        <li class="list-group-item list-data"></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="padding: 0;border-top-style:none">
                        <table>
                            <tr>
                                <td style="text-align: left;border-top-style:none">
                                    <ul class="list-group">
                                        <li class="list-group-item list-data">N° de cotización:</li>
                                        <li class="list-group-item list-data">Fecha emisión:</li>
                                        <li class="list-group-item list-data"></li>
                                        <li class="list-group-item list-data"></li>
                                        <li class="list-group-item list-data"></li>
                                        <li class="list-group-item list-data"></li>
                                    </ul>
                                </td>
                                <td style="text-align: left;border-top-style:none">
                                    <ul class="list-group">
                                        <li class="list-group-item list-data">
                                            <strong>{{ $cotizacion->identificador }}</strong></li>
                                        <li class="list-group-item list-data">{{ \Carbon\Carbon::now()->format('d/m/Y') }}
                                        </li>
                                        <li class="list-group-item list-data"></li>
                                        <li class="list-group-item list-data"></li>
                                        <li class="list-group-item list-data"></li>
                                        <li class="list-group-item list-data"></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- DATOS DEL CLIENTE --}}
                <tr style="font-size:11px;margin:0;border: 1px solid">
                    <td style="padding: 0;border-top-style:none">
                        <table style="width: 100%;">
                            <tr>
                                <td style="text-align: left; width: 21%;border-top-style:none">
                                    <ul class="list-group">
                                        <li class="list-group-item list-data-header" style="">Señor(es):</li>
                                        <li class="list-group-item list-data-header">Lugar de entrega:</li>
                                        <li class="list-group-item list-data-header"></li>
                                        <li class="list-group-item list-data-header"></li>
                                        <li class="list-group-item list-data-header"></li>
                                    </ul>
                                </td>
                                <td style="text-align: left; width: 35%;border-top-style:none">
                                    <ul class="list-group">
                                        <li class="list-group-item list-data-header">
                                            {{ $cotizacion->cliente->razon_social }}</li>
                                        <li class="list-group-item list-data-header">{{ $cotizacion->dde->lugar_entrega }}
                                        </li>
                                        <li class="list-group-item list-data-header">
                                            {{ $cotizacion->dde->domicilio }},
                                            {{ $cotizacion->dde->localidad->nombre }},
                                            {{ $cotizacion->dde->provincia->nombre }}
                                        </li>
                                        <li class="list-group-item list-data-header"></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="padding: 0;border-top-style:none">
                        <table style="width: 100%;">
                            <tr>
                                <td style="text-align: left; width: 19%;border-top-style:none">
                                    <ul class="list-group">
                                        <li class="list-group-item list-data-header">Cliente N°:</li>
                                        <li class="list-group-item list-data-header text-uppercase">
                                            {{ $cotizacion->cliente->tipo_afip }}:</li>
                                        <li class="list-group-item list-data-header">Tel/fax:</li>
                                        <li class="list-group-item list-data-header"></li>
                                        <li class="list-group-item list-data-header"></li>
                                    </ul>
                                </td>
                                <td style="text-align: left; width: 18%;border-top-style:none">
                                    <ul class="list-group">
                                        <li class="list-group-item list-data-header">{{ $cotizacion->cliente->id }}</li>
                                        <li class="list-group-item list-data-header">{{ $cotizacion->cliente->afip }}</li>
                                        <li class="list-group-item list-data-header">{{ $cotizacion->cliente->telefono }}
                                        </li>
                                        <li class="list-group-item list-data-header"></li>
                                        <li class="list-group-item list-data-header"></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </header>

        <footer>
            <table class="table table-sm" style="font-size: 11px; font-weight: normal;">
                <tr>
                    <td style="border-top-style:none;" class="text-left">
                        Droguería de la Ciudad
                    </td>
                    <td style="border-top-style:none;" class="text-right">
                        Documento PDF generado por: {{ auth()->user()->name }}
                    </td>
                </tr>
            </table>
        </footer>

        <div class="container" style="width:100%;padding: 0;font-size:11px;">
            <table class="table table-sm" style="">
                <thead>
                    <tr style="border-bottom: 1px solid">
                        <th scope="col" style="border-top-style:none;border-bottom-style:none;">Item</th>
                        <th scope="col" style="border-top-style:none;border-bottom-style:none;">Producto</th>
                        <th scope="col" style="border-top-style:none;border-bottom-style:none;">Cantidad</th>
                        <th scope="col" style="border-top-style:none;border-bottom-style:none;">Precio Unit.</th>
                        <th scope="col" style="border-top-style:none;border-bottom-style:none;">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 0; /*variable contadora del Nº Orden*/@endphp
                    @foreach ($cotizacion->productos as $cotizado)
                        <tr>
                            <th scope="row">{{ ++$i }}</td>
                            <td>{{-- Producto: producto+presentacion --}}
                                {{ $cotizado->producto->droga }},
                                {{ $presentaciones[$cotizado->presentacion_id - 1]->forma }}
                                {{ $presentaciones[$cotizado->presentacion_id - 1]->presentacion }}
                            </td>
                            <td class="text-center">
                                {{ $cotizado->cantidad }}
                            </td>
                            <td style="text-align: left; padding-left: 2em">
                                $ {{ number_format($cotizado->precio, 2, ',', '.') }}
                            </td>
                            <td style="text-align: left; padding-left: 2em">
                                $ {{ number_format($cotizado->total, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <br>
                    <tr style="border-top: 1px solid">
                        <td colspan="3" style="border-top-style:none; text-align: center;"></td>
                        <td
                            style="text-align: right;border-top-style:none;border-left: 1px solid;border-bottom: 1px solid">
                            <ul class="list-group">
                                <li class="list-group-item list-data"><br></li>
                                <li class="list-group-item list-data">Neto:</li>
                                <li class="list-group-item list-data">IVA:</li>
                                <li class="list-group-item list-data">Total:</li>
                            </ul>
                        </td>
                        <td
                            style="border-top-style:none;border-bottom: 1px solid;border-right: 1px solid; text-align: right;">
                            <ul class="list-group">
                                <li class="list-group-item list-data"><br></li>
                                <li class="list-group-item list-data">$
                                    {{ number_format($cotizacion->productos->sum('total'), 2, ',', '.') }}</li>
                                <li class="list-group-item list-data">$ 0</li>
                                <li class="list-group-item list-data">$
                                    {{ number_format($cotizacion->productos->sum('total'), 2, ',', '.') }}</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <section class="container mt-3">
            @php
                $montoLetras = new NumberFormatter('es_AR', NumberFormatter::SPELLOUT);
                $total = explode('.', $cotizacion->productos->sum('total'));

                // SE DETERMINA SI EL TOTAL TIENE CENTAVOS O NO (porque el explode detecta los ceros)
                if (sizeof($total) > 1) {
                    $letras = 'En letras: son ' . $montoLetras->format($total[0]) . ' pesos, con ' . $montoLetras->format($total[1]) . ' centavos.';
                } else {
                    $letras = 'En letras: son ' . $montoLetras->format($total[0]) . ' pesos, con cero centavos.';
                }
            @endphp
            <strong style="font-size: 11px">{{ $letras }}</strong>
            <p>La presente cotización, no incluye precios con IVA.</p>

            <table class="table table-sm" style="font-size: 11px; font-weight: normal;" class="p-3">
                <tr>
                    <td style="border-top-style:none;">
                        <ul class="list-group">
                            <li class="list-group-item list-data-footer">
                                <div class="">
                                    <span class=""><strong>Condiciones*</strong></span>
                                </div>
                                <p class="">
                                    <br>{{ $cotizacion->dde->condiciones }}
                                </p>
                                <small class="text-muted">*Provistas por el cliente.</small>
                            </li>
                            <li class="list-group-item list-data-footer">
                                <div class="">
                                    <span class="">Observaciones</span>
                                </div>
                                <p class="">
                                    <br>{{ $cotizacion->dde->observaciones }}
                                </p>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </section>
    </body>
</html>
