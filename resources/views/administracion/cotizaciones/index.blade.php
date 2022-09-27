@extends('adminlte::page')

@section('title', 'Administrar Cotizaciones')

@section('css')
    <style>
        .mobile {
            display: none
        }

        .desktop {
            display: inline
        }

        @media (max-width: 600px) {
            .desktop {
                display: none
            }

            .mobile {
                display: inline
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Últimas cotizaciones</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.cotizaciones.create') }}" role="button" class="btn btn-md btn-success">Crear
                cotización</a>
            &nbsp;
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
<div class="wrapper">
    <div class="card">
        <div class="card-header">
            <div class="desktop">
                @include('administracion.cotizaciones.partials.tabla-desktop')
            </div>

            <div class="mobile">
                @include('administracion.cotizaciones.partials.tabla-mobile')
            </div>
        </div>
    </div>
</div>

{{-- MODAL APROBACIÓN LICITACIÓN --}}
<div class="modal fade" id="modalAprobarCotizacion" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-blue">
                <h5 class="modal-title">Aprobar licitación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="formAprobada" enctype="multipart/form-data">
                @csrf
                {{-- CAUSAS: aprobada/rechazada --}}
                <input type="hidden" name="causa_subida" value="aprobada">
                <div class="modal-body">
                    <p>Deberá consignar la fecha de aprobación. Opcionalmente podrá adjuntar la Orden de Compra provista
                        por el cliente. Esto comformará información respaldatoria a la cotización.</p>
                    <hr>
                    <div class="row">
                        <div class="col form-group">
                            <x-adminlte-input-date name="confirmada" id="confirmada" igroup-size="md" :config="$config"
                                placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off" required>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text bg-dark">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-date>
                        </div>
                        <div class="col custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="archivo"
                                accept=".jpg,.png,.pdf">
                            <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="guardarAprobada" class="btn btn-success">Continuar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL RECHAZO LICITACIÓN --}}
<div class="modal fade" id="modalRechazarCotizacion" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-danger">
                <h5 class="modal-title">Rechazar licitación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="formRechazada" enctype="multipart/form-data">
                @csrf
                {{-- CAUSAS: aprobada/rechazada --}}
                <input type="hidden" name="causa_subida" value="rechazada">
                <div class="modal-body">
                    <p>Deberá consignar la fecha de rechazo y un motivo. Opcionalmente, podrá adjuntar el
                        <strong>pliego
                            procesado</strong> con comparativo de precios, provisto por el cliente. Se adjuntará
                        como
                        información respaldatoria a la cotización rechazada.
                    </p>
                    <div class="form-group">
                        <label for="input-motivo_rechazo">Motivo del rechazo</label>
                        <textarea name="motivo_rechazo" class="form-control @error('motivo_rechazo') is-invalid @enderror"
                            id="input-motivo_rechazo" rows="2" required></textarea>
                        <small id="input-motivo_rechazo" class="form-text text-muted">Datos relevantes como cliente
                            ganador, fuera de término, errores de cotización, etc..</small>
                        @error('motivo_rechazo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col form-group">
                            <x-adminlte-input-date name="rechazada" id="rechazada" igroup-size="md" :config="$config"
                                placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off" required>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text bg-dark">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-date>
                        </div>
                        <div class="col custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="archivo"
                                accept=".jpg,.png,.pdf">
                            <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="guardarRechazada" class="btn btn-success">Continuar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('partials.alerts')
<script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.4/locale/es.js"></script>
<script>
    // Reload manual de la página
    function recargar() {
        setTimeout(function() {
            Swal.fire({
                icon: 'success',
                title: 'Cotización presentada',
                text: 'La cotización quedará en espera de ser aprobada o rechazada.',
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = '{{ route('administracion.cotizaciones.index') }}';
                    return false;
                }
            })
        }, 10000)
    };

    var popupBlockerChecker = {
        check: function(popup_window) {
            var scope = this;
            if (popup_window) {
                if (/chrome/.test(navigator.userAgent.toLowerCase())) {
                    setTimeout(function() {
                        scope.is_popup_blocked(scope, popup_window);
                    }, 200);
                } else {
                    popup_window.onload = function() {
                        scope.is_popup_blocked(scope, popup_window);
                    };
                }
            } else {
                scope.displayError();
            }
        },
        is_popup_blocked: function(scope, popup_window) {
            if ((popup_window.innerHeight > 0) == false) {
                scope.displayError();
            }
        },
        displayError: function() {
            Swal.fire(
                'Información',
                'El navegador no permite la apertura de nuevas ventanas. Habilite los popups para continuar.',
                'info'
            )
        }
    };

    function borrarCotizacion(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Borrar cotización',
            text: 'Su cotización no contiene líneas, esto borrará la referencia en el registro.',
            confirmButtonText: 'Borrar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                debugger;
                $('#borrar-' + id).submit();
                window.location.replace('{{ route('administracion.cotizaciones.index') }}');
            }
        });
    };

    $(document).ready(function() {
        moment.locale('es');
        $('#tabla-cotizaciones').dataTable({
            "order": [0, 'desc'],
        });

        // función para que aparezca el nombre de archivo en el input
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        // se coloca el ACTION en el form: guardarAprobada
        $('#modalAprobarCotizacion').on('show.bs.modal', function(event) {
            $('#formAprobada').attr('action', 'cotizaciones/' + event.relatedTarget.id +
                '/aprobarCotizacion');
        });

        // se coloca el ACTION en el form: guardarRechazada
        $('#modalRechazarCotizacion').on('show.bs.modal', function(event) {
            $('#formRechazada').attr('action', 'cotizaciones/' + event.relatedTarget.id +
                '/rechazarCotizacion');
        });
    });
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión</b> 2.0 (LARAVEL V.8)
</div>
@endsection
