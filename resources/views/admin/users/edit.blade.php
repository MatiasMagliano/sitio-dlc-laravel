@extends('adminlte::page')

@section('title', 'Administrar usuarios')

@section('content_header')
<div class="row">
    <div class="col-md-10">
        <h1>Administración de Usuarios | Editar usuario</h1>
    </div>
    <div class="col-md-2 d-flex justify-content-end">
        <a href="{{ route('admin.users.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
    </div>
</div>
@stop

@section('content')
    {{--En este caso, no se usa el tag x-adminslte-card, si no que se desmembra en sus clases componente--}}
    <div class="row justify-content-md-center">
        <div class="card col-md-4 offset-md-1">
            <div class="card-header">
                <h3 class="card-title">Editar cliente</h3>
            </div>
            <form action="{{ route('admin.users.update', $editusuario->id) }}" method="POST">
                <div class="card-body">
                    @method('PATCH')
                    @include('admin.users.partials.formulario-usuarios')
                </div>
                <div class="card-footer">
                    {{-- Register button --}}
                    <div class="d-flex justify-content-end">
                        <x-adminlte-button type="submit" theme="success" label="Guardar"/>
                    </div>
                </div>
            </form>
            <div class="card-footer">
                @if (count($errors)>0)
                    <div class="callout callout-warning mr-auto">
                        <p>Por favor, rellene los campos requeridos por el formulario.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection

