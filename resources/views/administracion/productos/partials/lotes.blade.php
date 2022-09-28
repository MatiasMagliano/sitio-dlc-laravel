@foreach ($presentacion->lote as $lote)
    <span class="text-center"><strong>{{$lote->identificador}}</strong>, {{$lote->fecha_vencimiento->format('d/m/Y')}}</span> <br>
@endforeach
