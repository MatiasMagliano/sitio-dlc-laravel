<div class="row">
    <div class="col text-center">{{$producto->existencia}}</div>
    <div class="col text-center">{{$producto->cotizacion}}</div>
    <div class="col text-center">{{$producto->existencia - $producto->cotizacion}}</div>
</div>
