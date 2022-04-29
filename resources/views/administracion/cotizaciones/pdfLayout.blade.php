<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('js/bootstrap.min.css') }}">
    <style>
        body{
            margin: 1cm;
            padding: 10px;
            min-height: 100vh;
            display: flex;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
    <title>Cotización para {{$cotizacion->cliente->razon_social}}</title>
</head>
<body>
    <div class="text-center">
        <h1>Droguería de la Ciudad</h1>
        <h2>{{$cotizacion->direccionEntrega($cotizacion->dde_id)->get('lugar_entrega')}}</h2>
    </div>
    <p class="justify-content-end">Córdoba, {{\Carbon\Carbon::now()->format('d/m/Y')}}</p>

    <script src="{{ asset('js/jquery.slim.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
