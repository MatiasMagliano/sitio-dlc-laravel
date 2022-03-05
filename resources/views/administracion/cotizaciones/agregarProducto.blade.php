@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Agregar producto a cotización</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-xl-end">
            <a href="{{ url()->previous() }}" role="button"
                class="btn btn-md btn-secondary">Volver a la cotización</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <form action="{{ route('administracion.cotizaciones.guardar.producto', $cotizacion) }}" method="post" class="needs-validation" autocomplete="off" novalidate>
        @csrf
        <input type="hidden" name="cotizacion_id" value="{{$cotizacion->id}}">
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-8">
                        <h6 class="heading-small text-muted mb-1">Producto para cotización: {{$cotizacion->identificador}}</h6>
                    </div>
                    <div class="col-4 text-right">
                        <button type="submit" class="btn btn-sidebar btn-success"><i class="fas fa-share-square"></i>&nbsp;<span class="hide">Agregar</span></button>
                    </div>
                </div>
            </div>
            <div class="card-body">

                {{-- producto=producto+presentacion. Se retienen los dos ID combinados. Luego el controller los separa --}}
                <div class="form-group">
                    <label for="input-producto">Producto y presentación</label>
                    <select name="producto" id="input-producto"
                        class="selecion-producto  @error('producto') is-invalid @enderror">
                        <option data-placeholder="true"></option>
                        @foreach ($productos as $producto)
                            @foreach ($producto->presentaciones as $presentacion)
                                @if ($presentacion->id == old('producto'))
                                    <option value="{{$producto->id}}|{{$presentacion->id}}" selected>[{{ $producto->droga }}]
                                        {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                                @else
                                    <option value="{{$producto->id}}|{{$presentacion->id}}">[{{ $producto->droga }}]
                                        {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                    @error('producto')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div>
                <div class="row d-flex">
                    <div class="col">
                        {{-- PONER EN EL VALUE EL PRECIO SUGERIDO, ojo con el formato--> debe terminar siendo FLOAT --}}
                        <div class="form-group">
                            <label for="input-precio">Precio</label>
                            <input type="number" name="precio" id="input-precio"
                                class="form-control @error('precio') is-invalid @enderror"
                                value="@if(old('precio')){{old('precio')}}@else{{15.30}}@endif"
                                step=".01">
                                @error('precio')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="input-cantidad">Cantidad</label>
                            <input type="number" name="cantidad" id="input-cantidad"
                                class="form-control @error('cantidad') is-invalid @enderror"
                                value="@if(old('cantidad')){{old('cantidad')}}@else{{0}}@endif">
                                @error('cantidad')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="input-total">Total</label>
                            <input type="text" name="total" id="input-total"
                                class="form-control @error('total') is-invalid @enderror"
                                value="@if(old('total')){{old('total')}}@else{{0}}@endif" disabled>
                                @error('total')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    @include('partials.alerts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        new SlimSelect({
            select: '.selecion-producto',
            placeholder: 'Seleccione el nombre de la droga y luego su presentación...',
        });
    </script>

    <script>
        $('#input-cantidad').on('input', function() {
            $('#input-total').val('$' + (parseInt($('#input-cantidad').val()) * parseFloat($('#input-precio').val())).toFixed(2));
        });

        $('#input-precio').on('input', function() {
            $('#input-total').val('$' + (parseInt($('#input-cantidad').val() * parseFloat($('#input-precio').val()))).toFixed(2));
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
