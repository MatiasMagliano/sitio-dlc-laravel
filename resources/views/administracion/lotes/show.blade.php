@extends('adminlte::page')

@section('title', 'Detalle de lotes')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Detalle del lote {{ $lote->identificador }}</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            {{-- para engañar al sistema, se hace un formulario por GET solamente con el botón x-adminlte-button --}}
            <form action="{{ route('administracion.productos.index') }}" method="get">
                <x-adminlte-button type="submit" label="Volver" class="bg-gray" />
            </form>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
<h3><strong>DROGA:</strong> {{ $producto->droga }}</h3>
<hr>
<p><strong>Lote:</strong> {{ $lote->identificador }}</p>
<p><strong>Proveedor/es:</strong><br>
    @foreach ($producto->proveedores as $proveedor)
        - {{ $proveedor->razonSocial }}<br>
    @endforeach
</p>
<p><strong>Precio:</strong> ${{ $lote->precioCompra }}</p>
<p><strong>Cantidad:</strong> por ahora no tiene</p>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
