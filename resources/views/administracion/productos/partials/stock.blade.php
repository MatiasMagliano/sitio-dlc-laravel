@foreach ($presentacion->dcc as $dcc)
    <div class="row">
        <div class="col">{{$dcc->existencia}}</div>
        <div class="col">{{$dcc->cotizacion}}</div>
        <div class="col">{{$dcc->disponible}}</div>
    </div>
@endforeach
