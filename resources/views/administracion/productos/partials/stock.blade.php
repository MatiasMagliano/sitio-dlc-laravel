@foreach ($producto->dcc as $dcc)
    <div class="row">
        <div class="col text-center">{{$dcc->existencia}}</div>
        <div class="col text-center">{{$dcc->cotizacion}}</div>
        <div class="col text-center">{{$dcc->disponible}}</div>
    </div>
@endforeach
