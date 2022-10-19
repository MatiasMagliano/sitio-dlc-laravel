@foreach ($producto->lote as $lote)
    <span class="text-center">{{$lote->identificador}}</span> <br>
@endforeach
