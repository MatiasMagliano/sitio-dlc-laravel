{{-- MODAL RECHAZO LICITACIÓN --}}
{{-- no lleva ACTION porque se llena con la cotización seleccionada dinamicamente --}}
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
                        <strong>pliego procesado</strong> con comparativo de precios, provisto por el cliente. Se adjuntará
                        como información respaldatoria a la cotización rechazada.
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
