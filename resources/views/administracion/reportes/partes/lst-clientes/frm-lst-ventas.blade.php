<div class="card">
    <div class="card-body">
        <div class="form-group">
            <label for="input-reporte">Ventas por: *</label>
            <select class="form-control" name="tipo_reporte" id="input-reporte">
                <option value="ventas_concretadas" selected>Ventas concretadas</option>
                <option value="ventas_rechazadas" selected>Ventas rechazadas</option>
            </select>
        </div>

        <div class="form-group">
            <label for="input-orden">Ordenado por: *</label>
            <select class="form-control" name="orden_listado" id="input-orden">
                <option value="cotizacions.confirmada" selected>Fecha de aprobación</option>
                <option value="cotizacions.identificador">Identificador</option>
                <option value="clientes.razon_social">Cliente</option>
                <option value="cotizacions.monto_total">Monto total facturado</option>
            </select>
        </div>

        {{-- RANGO DE FECHAS --}}
        <h6 class="text-bold">Rango absoluto de fechas</h6>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="desde">Desde *</label>
                    <x-adminlte-input-date name="input-desde" id="desde" igroup-size="md" :config="$config_desde" autocomplete="off" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="hasta">Hasta *</label>
                    <x-adminlte-input-date name="input-hasta" id="hasta" igroup-size="md" :config="$config_hasta" autocomplete="off" required>
                        <x-slot name="appendSlot">
                            <div class="input-group-text">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-sidebar btn-success">Generar</button>
        <a href="" class="btn btn-sidebar btn-info" role="button">Descargar</a>
    </div>
</div>
