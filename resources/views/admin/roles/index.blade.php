@extends('adminlte::page')

@section('title', 'Administrar roles de usuario')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Administración roles</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            {{--para engañar al sistema, se hace un formulario por GET con el botón x-adminlte-button--}}
            <x-adminlte-button id="crearRolModal" label="Crear nuevo rol" data-toggle="modal" data-target="#modalCrearRol" class="bg-green"/>
            <a href="{{ route('admin.users.index') }}" role="button" class="btn btn-md btn-secondary">Volver a usuarios</a>
        </div>
    </div>
@stop

@section('content')
{{-- aquí va contenido --}}
    <x-adminlte-card>
        {{--TABLA DE ROLES--}}
        <table id="tabla1" class="table table-bordered display" style="width: 100%;">
            <thead>
                <th width="25px">#ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Actualizado</th>
                <th width="100px"><strong>ACCIONES</strong></th>
            </thead>
            <tbody>
                @foreach ($roles as $rol)
                    <tr>
                        <td class="text-center">{{ $rol->id }}</td>
                        <td>{{ $rol->nombre }}</td>
                        <td>{{ $rol->descripcion }}</td>
                        <td>{{ $rol->updated_at->format('d/m/Y h:i A') }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{--El botón modificar no lleva a un modal. Lleva a una vista nueva, para respetar la idiosincrasia de Laravel--}}
                            <a href="{{ route('admin.roles.edit', $rol->id) }}" role="button" class="btn btn-sm btn-default mx-1 shadow"><i class="fa fa-lg fa-fw fa-pen"></i></a>

                            {{--se crea este método, porque el borrado en Laravel se hace por POST--}}
                            <a class="btn btn-sm btn-default mx-1 shadow"
                                onclick="event.preventDefault();
                                        document.getElementById('form-borrar-rol-{{ $rol->id }}').submit();"
                                        role="button">
                                        <i class="fa fa-lg fa-fw fa-trash"></i></a>
                            <form id="form-borrar-rol-{{ $rol->id }}"
                                action="{{ route('admin.roles.destroy', $rol->id) }}" method="POST"
                                style="display: none">
                                @csrf
                                @method("DELETE")
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-adminlte-card>

    {{--MODAL CREAR ROL--}}
    <x-adminlte-modal id="modalCrearRol" title="Crear un rol" theme="blue" icon="fas fa-user-tag" size='md' v-centered>
        <form action="{{ route('admin.roles.store') }}" method="post">
            @include('admin.roles.partials.formulario-roles')

            {{-- Register button --}}
            <div class="d-flex justify-content-end">
                <x-adminlte-button data-dismiss="modal" theme="secondary" label="Cerrar" style="margin: 0 10px"/>
                <x-adminlte-button type="submit" theme="success" label="Guardar" id="guardarRol"/>
            </div>
        </form>

        {{--zona de muestra de errores--}}
        <x-slot name="footerSlot">
            @if (count($errors)>0)
                <div class="callout callout-warning mr-auto">
                    <p>Por favor, rellene los campos requeridos por el formulario.</p>
                </div>
            @endif
        </x-slot>
    </x-adminlte-modal>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    @include('partials.alerts')
    <script>
        $(document).ready(function() {
            // el datatable es responsivo y oculta columnas de acuerdo al ancho de la pantalla
            $('#tabla1').DataTable({
                responsive: {
                    details: {
                        renderer: function ( api, rowIdx, columns ) {
                            var data = $.map( columns, function ( col, i ) {
                                return col.hidden ?
                                    '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                        '<td>'+col.title+':'+'</td> '+
                                        '<td>'+col.data+'</td>'+
                                    '</tr>' :
                                    '';
                            } ).join('');

                            return data ?
                                $('<table/>').append( data ) :
                                false;
                        }
                    }
                }
            });

            // MANTENER EL MODAL DE CREAR CLIENTES ABIERTO SI HAY ERRORES
            @if(count($errors)>0)
                  $('#modalCrearRol').modal('show');
            @endif
        });
    </script>
@endsection


@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
    </div>
@endsection
