@extends('adminlte::page')

@section('css')
<style>
    .btn-rm-hover:hover{
        color: white;
        background-color: rgb(160, 0, 0);
        }
        .btn-edt-hover:hover{
            color: white;
            background-color: darkgreen;
        }
</style>    
@endsection

@section('title', 'Administrar usuarios')

@section('content_header')
<div class="row">
    <div class="col-xl-8">
        <h1>Administración de Usuarios y roles</h1>
    </div>
    <div class="col-xl-4 d-flex justify-content-xl-end">
        {{--para engañar al sistema, se hace un formulario por GET con el botón x-adminlte-button--}}
        <x-adminlte-button id="crearUsuarioModal" label="Crear nuevo usuario" data-toggle="modal" data-target="#modalCrearUsuario" class="bg-green"/>
        <form action="{{ route('admin.roles.index') }}" method="get"><x-adminlte-button type="submit" label="Administrar roles" class="bg-gray"/></form>
    </div>
</div>
@stop

{{-- aquí va contenido --}}
@section('content')
    {{--TABLA CLIENTES--}}
    <x-adminlte-card>
        <table id="tabla1" class="table table-bordered display nowrap" style="width: 100%;">
            <thead>
                <th width="25px">#ID</th>
                <th>Nombre</th>
                <th>E-mail</th>
                <th>Roles</th>
                <th>Creado el...</th>
                <th width="100px"><strong>ACCIONES</strong></th>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td class="text-center">{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <div class="form-check form-check-inline" style="display: flex;flex-flow: row wrap; align-items: center;">
                                @foreach ($roles as $rol)
                                    <input type="checkbox" name="{{ $rol->nombre }}" class="form-check-input" id="{{ $rol->nombre }}"
                                        @if(in_array($rol->id, $usuario->roles->pluck('id')->toArray()))
                                            checked
                                        @endif
                                    />
                                    <label for="{{ $rol->nombre }}" class="form-check-label">{{ $rol->nombre }} &nbsp;&nbsp;</label>
                                @endforeach
                            </div>
                        </td>
                        <td>{{ $usuario->created_at->format('d/m/Y h:i A') }}</td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{--El botón modificar no lleva a un modal. Lleva a una vista nueva, para respetar la idiosincrasia de Laravel--}}
                            <a href="{{ route('admin.users.edit', $usuario->id) }}" role="button" class="btn btn-sm btn-default btn-edt-hover mx-1 shadow"><i class="fas fa-lg fa-fw fa-cog"></i></a>
                            
                            {{--se crea este método, porque el borrado en Laravel se hace por POST--}}
                            <a class="btn btn-rm-hover btn-sm btn-light mx-1 shadow"
                                onclick="event.preventDefault();
                                        document.getElementById('form-borrar-usuario-{{ $usuario->id }}').submit();"
                                        role="button">
                                        <i class="fa fa-lg fa-fw fa-trash"></i></a>
                            <form id="form-borrar-usuario-{{ $usuario->id }}"
                                action="{{ route('admin.users.destroy', $usuario->id) }}" method="POST"
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
    
    {{--MODAL CREAR CLIENTE--}}
    <x-adminlte-modal id="modalCrearUsuario" title="Crear usuario" theme="blue" icon="fas fa-user" size='md' v-centered>
        <form action="{{ route('admin.users.store') }}" method="post">
            @include('admin.users.partials.formulario-usuarios', ['band_crear' => true])
            
            {{-- Register button --}}
            <div class="d-flex justify-content-end">
                <x-adminlte-button data-dismiss="modal" theme="secondary" label="Cerrar" style="margin: 0 10px"/>
                <x-adminlte-button type="submit" theme="success" label="Guardar" id="guardarCliente"/>
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
                  $('#modalCrearUsuario').modal('show');
            @endif
        });
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection

