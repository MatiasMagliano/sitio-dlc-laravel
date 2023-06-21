{{-- MODAL APROBACIÓN LICITACIÓN --}}
{{-- no lleva ACTION porque se llena con la cotización seleccionada dinamicamente --}}
<div class="modal fade" id="modalAprobarCotizacion" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
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
                    <p>Deberá consignar la fecha de aprobación. Podrá seleccionar as líneas que efectivamente fueron aprobadas.
                        Opcionalmente podrá adjuntar la Orden de Compra (provisión) del cliente.
                        Esto comformará información respaldatoria a la cotización.</p>
                    <hr>
                    {{-- SECCION FECHA APROBACIÓN y INPUT FILE PARA PROVISIÓN --}}
                    <div class="row">
                        <div class="col form-group">
                            <label for="confirmada">Fecha de aprobación*</label>
                            <x-adminlte-input-date name="confirmada" id="confirmada" igroup-size="md" :config="$config"
                                placeholder="{{ __('formularios.date_placeholder') }}" autocomplete="off" required>
                                <x-slot name="appendSlot">
                                    <div class="input-group-text bg-dark">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input-date>
                        </div>
                        <div class="col from-group">
                            <label for="seccionArchivo">Archivo de provisión</label><br>
                            <div class="col custom-file" id="seccionArchivo">
                                <input type="file" class="custom-file-input" id="archivo" name="archivo"
                                    accept=".jpg,.png,.pdf">
                                <label class="custom-file-label" for="archivo">Seleccionar archivo</label>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN SELECCIÓN DE ITEMS FINALMENTE APROBADOS --}}
                    <label for="lineasAprobadas">Ítems cotizados*</label>
                    <div class="row" id="lineasAprobadas">
                        <div class="col">
                            <a class="btn-link" onclick="selectAll()" style="cursor: pointer;">Todas</a>
                            <a class="btn-link" onclick="UnSelectAll()" style="cursor: pointer;">| Ninguna</a>
                        </div>
                    </div>
                    <div class="row">
                        {{-- lista con los items de la cotización seleccionada --}}
                        <div id="lineasCotizadas" class="col form-group pre-scrollable bg-gradient-light"></div>
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
