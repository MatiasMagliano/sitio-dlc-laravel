@foreach ($presentacion->dcc as $dcc)
    <div class="row">
        <div class="col text-center">{{$dcc->existencia}}</div>
        <div class="col text-center">{{$dcc->cotizacion}}</div>
        <div class="col text-center">{{$dcc->existencia - $dcc->cotizacion}}</div>
    </div>
@endforeach
