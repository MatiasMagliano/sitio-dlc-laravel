@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Administración de productos</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.productos.create') }}" role="button"
                class="btn btn-md btn-success">Crear producto</a>
            &nbsp;
            <a href="{{ route('administracion.lotes.index') }}" role="button"
                class="btn btn-md btn-success">Crear lotes</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <table class="table table-bordered">
        <thead>
            <th></th>
        </thead>
        <tbody>
            @foreach ($productos as $producto) {{-- ITERA SOBRE EL PIVOT --}}
                <tr>
                    <td>
                        @foreach ($producto->productos as $item)
                            {{$item->droga}} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($producto->presentaciones as $item)
                            {{$item->forma}}, {{$item->presentacion}} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($producto->lotes as $item)
                            {{$item->identificador}}, {{$item->fecha_vencimiento->format('d/m/Y')}} <br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        $(document).ready(function() {
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
