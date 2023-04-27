@extends('adminlte::page')

@section('title', 'Crear documento reporte o listado')

@section('css')
    <style>
        .texto-header {
            padding: 0 20px;
            height: 90px;
            overflow-y: auto;
            /*font-size: 14px;*/
            font-weight: 500;
            color: #000000;
        }

        .texto-header::-webkit-scrollbar {
            width: 5px;
            background-color: #282828;
        }

        .texto-header::-webkit-scrollbar-thumb {
            background-color: #3bd136;
        }

        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Crear documento</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.reportes.index') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
    <form action="{{ route('administracion.reportes.store') }}" method="post" enctype=multipart/form-data
        class="needs-validation" autocomplete="off" novalidate>
        @csrf

        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
        <div class="card">
            <div class="card-header">
                <div class="texto-header">
                    <h5>Nuevo reporte o listado</h5>
                    <p>En esta sección ud. podrá crear y guardar un reporte o un listado de su elección.
                        <span class="text-danger">
                            Tenga a bien editar detenidamente esta sección, ya que no podrá modificarla posteriormente
                        </span>.
                    </p>
                    <p>El cuerpo y los formatos de reporte o listados se podrán modificar en la
                        <a href="{{ route('administracion.reportes.index') }}">
                            administración de documentos
                        </a>. En el caso del primero, la
                        posibilidad de agregar varios módulos o listados,
                        incluyendo campos de texto que podrá personalizar.
                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col">
                        <label for="tipo_documento">Tipo de documento *</label>
                        <select name="tipo_documento" id="input-documento"
                            class="form-control @error('nombre_documento') is-invalid @enderror">
                            <option selected disabled>Seleccione un tipo de documento...</option>
                            <option value="reporte" {{ old('tipo_documento') == 'reporte' ? 'selected' : '' }}>
                                Reporte</option>
                            <option value="listado" {{ old('tipo_documento') == 'listado' ? 'selected' : '' }}>
                                Listado</option>
                        </select>
                        @error('tipo_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- PRIMERA LÍNEA. CONTIENE: nombre del reporte y dirigido a --}}
                <div class="row">
                    <div class="form-group col">
                        <label for="input-nombre">Nombre del documento *</label>
                        <input type="text" name="nombre_documento" id="input-nombre"
                            class="form-control form-control-sm @error('nombre_documento') is-invalid @enderror"
                            value="{{ old('nombre') }}">
                        @error('nombre_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col">
                        <label for="input-dirigido_a">Dirigido a *</label>
                        <input type="text" name="dirigido_a" id="input-dirigido_a"
                            class="form-control form-control-sm @error('dirigido_a') is-invalid @enderror"
                            value="{{ old('dirigido_a') }}">
                        @error('dirigido_a')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- SEGUNDA LÍNEA. CONTIENE: encabezado general del documento --}}
            @section('plugins.Summernote', true)
            <div class="row pt-3">
                <div class="form-group col">
                    <label for="encabezado">Encabezado del documento *</label>
                    <textarea name="encabezado" class="form-control campo-encabezado">{!! html_entity_decode($encabezado) !!}</textarea>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-sidebar btn-success">
                <i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span>
            </button>
        </div>
    </div>
</form>
@endsection

@section('js')
@include('partials.alerts')
<script type="text/javascript">
    $(document).ready(function() {
        
        // activación del summernote del encabezado
        $('.campo-encabezado').summernote({
            focus: false,
            disableResizeEditor: true,
        });
    });
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión de software 2.8</b> (PHP: v{{ phpversion() }} | LARAVEL: v.{{ App::VERSION() }})
</div>
@endsection
