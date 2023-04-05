@php
    $lotes = json_decode($producto);
    $lotes = explode(',', $lotes->lotes);
@endphp

@foreach ($lotes as $lote)
    <span class="pl-3 ml-3">{{$lote}}</span> <br>
@endforeach
