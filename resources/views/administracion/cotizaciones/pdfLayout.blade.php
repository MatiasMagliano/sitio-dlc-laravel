<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilosPDF.css') }}">
    <style>
        /* .page-break {
            page-break-after: always;
        } */
    </style>
    <title>Cotización {{$cotizacion->identificador}}</title>
</head>
    <header>
        <table class="table" style="padding-left: 2em;padding-right: 2em;">
            <tr>
                <th scope="col" style="text-align: left;padding: 1em 3em"><span>Droguería de la Ciudad</span></th>
                <th scope="col" style="padding: 1em 3em;"><span>COTIZACION</span></th>
            </tr>
            <tr style="font-size:11px;margin:0;padding: 0;">
                <td style="padding: 0;border-top-style:none">
                    <table>
                        <tr>
                            <td style="text-align: left; width: 23%;border-top-style:none">
                                <ul class="list-group">
                                    <li class="list-group-item list-data">Razón Social:</li>
                                    <li class="list-group-item list-data">Domicilio:</li>
                                    <li class="list-group-item list-data"><br></li>
                                    <li class="list-group-item list-data">Condición frente al IVA:</li>
                                    <li class="list-group-item list-data"><br></li>
                                    <li class="list-group-item list-data"></li>
                                </ul>            
                            </td>
                            <td style="text-align: left;border-top-style:none"> 
                                <ul class="list-group">
                                    <li class="list-group-item list-data">MARTIN SEGURA E HIJOS S.A.</li>
                                    <li class="list-group-item list-data">Raymundo Montenegro 2654 - CÓRDOBA</li>
                                    <li class="list-group-item list-data"><br></li>
                                    <li class="list-group-item list-data">Responsable Inscripto</li>
                                    <li class="list-group-item list-data"><br></li>
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
                                    <li class="list-group-item list-data">CUIT:</li>
                                    <li class="list-group-item list-data">Ingresos Brutos:</li>
                                    <li class="list-group-item list-data">Inicio de Actividad:</li>
                                    <li class="list-group-item list-data"></li>
                                </ul>            
                            </td>
                            <td style="text-align: left;border-top-style:none">
                                <ul class="list-group">
                                    <li class="list-group-item list-data"><strong>{{$cotizacion->identificador}}</strong></li>
                                    <li class="list-group-item list-data">{{\Carbon\Carbon::now()->format('d/m/Y')}}</li>
                                    <li class="list-group-item list-data">30716556643</li>
                                    <li class="list-group-item list-data">Exento</li>
                                    <li class="list-group-item list-data">22/05/2001</li>
                                    <li class="list-group-item list-data"></li>
                                </ul>            
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr style="font-size:11px;margin:0;border: 1px solid">
                <td style="padding: 0;border-top-style:none">
                    <table style="width: 100%;">
                        <tr>
                            <td style="text-align: left; width: 21%;border-top-style:none">
                                <ul class="list-group">
                                    <li class="list-group-item list-data-header" style="">Señor(es):</li>
                                    <li class="list-group-item list-data-header">Domicilio:</li>
                                    <li class="list-group-item list-data-header"><br></li>
                                    <li class="list-group-item list-data-header">Codigo Postal:</li>
                                    <li class="list-group-item list-data-header">Dir. entrega:</li>
                                    
                                </ul>            
                            </td>
                            <td style="text-align: left; width: 35%;border-top-style:none"> 
                                <ul class="list-group">
                                    <li class="list-group-item list-data-header">{{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('razon_social')->get('0')}}</li>
                                    <li class="list-group-item list-data-header">
                                        {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('domicilio')->get('0')}},
                                        {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('nombre')->get('0')}},
                                        {{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('nombre_completo')->get('0')}}
                                    </li>
                                    <li class="list-group-item list-data-header"><br></li>
                                    <li class="list-group-item list-data-header">{{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('lugar_entrega')->get('0')}}</li>
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
                                    <li class="list-group-item list-data-header">{{$cotizacion->cliente->tipo_afip}}:</li>
                                    <li class="list-group-item list-data-header">IVA Resp. Inscripto:</li>
                                    <li class="list-group-item list-data-header"><br></li>
                                </ul>            
                            </td>
                            <td style="text-align: left; width: 18%;border-top-style:none">
                                <ul class="list-group">
                                    <li class="list-group-item list-data-header">{{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('cliente_id')->get('0')}}</li>
                                    <li class="list-group-item list-data-header">{{$cotizacion->cliente->afip}}</li>
                                    <li class="list-group-item list-data-header"><br></li>
                                    <li class="list-group-item list-data-header"><br></li>
                                </ul>            
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <table class="table table-sm" style="font-size: 11px;font-weight: normal;padding: 0em 4em;">
            <tr>
                <td style="border-top-style:none;">
                    <ul class="list-group;">
                        <li class="list-group-item list-data-footer">
                            <div class="">
                                <span class=""><strong>Condiciones*</strong></span>
                            </div>
                            <p class="">
                                <br>{{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('condiciones')->get('0')}}
                            </p>
                            <small class="text-muted">*Provistas por el cliente.</small>
                        </li>
                        <li class="list-group-item list-data-footer">
                            <div class="">
                                <span class="">Observaciones</span>
                            </div>
                            <p class="">
                                <br>{{$cotizacion->direccionEntrega($cotizacion->dde_id)->pluck('observaciones')->get('0')}}
                            </p>
                        </li>
                    </ul>
                    <p class="text-right">Documento PDF generado por: {{auth()->user()->name}}</p>
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
                        <th scope="row">{{++$i}}</td>
                        <td>{{--Producto: producto+presentacion--}}
                            {{$cotizado->producto->droga}}, {{$presentaciones[$cotizado->presentacion_id-1]->forma}} {{$presentaciones[$cotizado->presentacion_id-1]->presentacion}}
                        </td>
                        <td style="text-align: right;padding-right: 2em">
                            {{$cotizado->cantidad}}
                        </td>
                        <td style="text-align: right;padding-right: 2em">
                            $ {{$cotizado->precio}}
                        </td>
                        <td style="text-align: right;">
                            $ {{$cotizado->total}}
                        </td>
                    </tr>
                @endforeach 
                <br>
                <tr style="border-top: 1px solid">
                    <td colspan="3" style="border-top-style:none; text-align: center;"></td>
                    <td style="text-align: right;border-top-style:none;border-left: 1px solid;border-bottom: 1px solid">
                        <ul class="list-group">
                            <li class="list-group-item list-data"><br></li>
                            <li class="list-group-item list-data">Neto:</li>
                            <li class="list-group-item list-data">IVA:</li>
                            <li class="list-group-item list-data">Total:</li>
                        </ul> 
                    </td>
                    <td style="border-top-style:none;border-bottom: 1px solid;border-right: 1px solid; text-align: right;">
                        <ul class="list-group">
                            <li class="list-group-item list-data"><br></li>
                            <li class="list-group-item list-data">$ {{$cotizacion->productos->sum('total')}}</li>
                            <li class="list-group-item list-data">$ 0</li>
                            <li class="list-group-item list-data">$ {{$cotizacion->productos->sum('total')}}</li>
                        </ul> 
                    </td>
                </tr>
            </tbody>
          </table>
    </div>

    <script type="text/php">
        

    </script>
</body>
</html>
