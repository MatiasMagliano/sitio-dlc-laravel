{{-- MODAL PARA AGREGAR PRODUCTO --}}
<div class="modal fade" id="modalAgregProducto" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="" method="POST" id="formAgregProducto" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PATCH')
                <input type="hidden" name="proveedor_id" id="input-nproveedorId" value="{{ $proveedorItem->id }}">
                <div class="modal-header bg-gradient-blue">
                    <h5 class="modal-title">Agregar producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="input-ndroga">Droga</label>
                        {{-- <input type="text" name="droga" id="input-ndroga" min="0"class="form-control" disabled> --}}
                        <select name="producto_id" id="input-producto_id"
                            class="seleccion-producto col @error('producto') is-invalid @enderror">
                        </select>
                        @error('producto')<div class="invalid-feedback">{{$message}}</div>@enderror

                        <label for="input-npresentacion">Presentacion</label>
                        <select name="presentacion_id" id="input-presentacion_id"
                            class="seleccion-presentacion col @error('producto') is-invalid @enderror">
                            <option data-placeholder="true"></option>
                        </select>
                        @error('producto')<div class="invalid-feedback">{{$message}}</div>@enderror
                    </div>

                    <div class="row d-flex">
                        {{-- Incluir Validaciones --}}
                        <div class="col">
                            <div class="form-group">
                                <label for="input-ncodigoProv">Código de Proveedor *</label>
                                <input type="text" name="codigoProv" id="input-ncodigoProv" class="form-control" required>
                                <div id="input-ncodigoProv-feedback" class="invalid-feedback" style="color: red; font-size: 12px" >* Debe ingresar un dato válido</div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="input-ncosto">Costo *</label>
                                <input type="number" name="costo" id="input-ncosto" min="0" class="form-control" step=".01" required>
                                <div id="input-ncosto-feedback" class="invalid-feedback" style="color: red; font-size: 12px" >* Debe ingresar un importe mayor a 0</div>
                            </div>
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

{{-- MODAL PARA EDITAR PRODUCTO --}}
<div class="modal fade" id="modalModifProducto" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="" method="POST" id="formModifProducto" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PATCH')
                <input type="hidden" name="listaId" id="input-listaId" value="">
                <div class="modal-header bg-gradient-blue">
                    <h5 class="modal-title">Modificar producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="input-droga">Droga</label>
                        <input type="text" name="droga" id="input-droga" min="0"class="form-control" disabled>
                    </div>

                    <div class="row d-flex">
                        {{-- Incluir Validaciones --}}
                        <div class="col">
                            <div class="form-group">
                                <label for="input-codigoProv">Código de Proveedor *</label>
                                <input type="text" name="codigoProv" id="input-codigoProv" class="form-control" required>
                                <div id="input-codigoProv-feedback" class="invalid-feedback" style="color: red; font-size: 12px" >* Debe ingresar un dato válido</div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="input-costo">Costo *</label>
                                <input type="number" name="costo" id="input-costo" min="0" class="form-control" step=".01" required>
                                <div id="input-costo-feedback" class="invalid-feedback" style="color: red; font-size: 12px" >* Debe ingresar un importe mayor a 0</div>
                            </div>
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