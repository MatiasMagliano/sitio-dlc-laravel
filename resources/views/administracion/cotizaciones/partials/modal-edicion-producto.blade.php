{{-- MODAL EDICIÓN DE PRODUCTO --}}
<div class="modal fade" id="modalModifProducto" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="formModifProducto" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <input type="hidden" name="prodCotiz_id" id="input-prodCotiz" value="">
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
                        <div class="col">
                            <div class="form-group">
                                <label for="input-precio">Precio *</label>
                                <input type="number" name="precio" id="input-precio" min="0"
                                    class="form-control @error('precio') is-invalid @enderror"
                                    value="@if(old('precio')){{old('precio')}}@else{{0}}@endif"
                                    step=".01">
                                    @error('precio')<div class="invalid-feedback">{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="input-cantidad">Cantidad *</label>
                                <input type="number" name="cantidad" id="input-cantidad" min="0"
                                    class="form-control @error('cantidad') is-invalid @enderror"
                                    value="@if(old('cantidad')){{old('cantidad')}}@else{{0}}@endif">
                                    @error('cantidad')<div class="invalid-feedback">{{$message}}</div>@enderror
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="input-total">Total</label>
                                <input type="text" name="total" id="input-total"
                                    class="form-control @error('total') is-invalid @enderror"
                                    value="@if(old('total')){{old('total')}}@else{{0}}@endif" disabled>
                                    @error('total')<div class="invalid-feedback">{{$message}}</div>@enderror
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
