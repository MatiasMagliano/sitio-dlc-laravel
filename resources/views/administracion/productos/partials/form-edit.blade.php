{{-- NOMBRE DE LA DROGA --}}
<div class="card">
    <div class="card-header">
        <div class="row d-flex">
            <div class="col-8">
                <h6 class="heading-small text-muted mb-1">NOMBRE DEL PRODUCTO</h6>
            </div>
            <div class="col-4 text-right">
                <button type="submit" class="btn btn-sidebar btn-success"><i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span></button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="pl-lg-4">
            <div class="form-group">
                {{-- Antigua droga id --}}
                <label for="input-antigua_droga">
                    <input id="input-antigua_droga" name="antigua_drogaid" value="{{ $producto->id }}" hidden>
                </label>
                {{-- Antigua droga --}}
                <label for="input-antigua_droga">
                    <input id="input-antigua_droga" name="antigua_droga" value="{{ $producto->droga }}" hidden>
                </label>
                <label>Droga *
                    <input type="text" name="droga" id="droga" class="form-control" placeholder="Nombre genérico o compuesto principal representativo" value="{{ old('droga', $producto->droga) }}" autofocus>
                    <div class="invalid-feedback" id="invalid-feedback-droga"></div>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="card-group">
    {{-- DATOS DE PRESENTACION --}}
    <div class="card mr-2">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h6 class="heading-small text-muted mb-1" id="header-edit-prod-pres">EDITAR PRESENTACION</h6>
                </div>
                <div class="col-4 text-right">
                    <label>Nueva presentación 
                        <input type="checkbox" id="crear_editar" name="crear_editar" {{old('crear_editar') == 1 ? 'checked' : ''}}>
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="pl-lg-4">
                <div class="form-group" id="cambio-presentacion">
                    {{-- Antigua presentacion --}}
                    <label for="input-antigua_presentacion">
                        <input id="input-antigua_presentacion" name="antigua_presentacion" value="{{$presentacion->id}}" hidden>
                    </label>
                    
                    <label>Forma farmacéutica y presentación *
                        <select name="presentacion" id="input-presentacion"class="selecion-presentacion form-control-alternative @error('presentacion') is-invalid @enderror">
                            <option data-placeholder="true"></option>
                            @foreach ($presentaciones as $presentacion_all)
                                @if ($presentacion_all->id == old('presentacion', $presentacion->id))
                                    <option value="{{ $presentacion_all->id }}" selected>
                                        @if ($presentacion_all->hospitalario)
                                            <b>H - </b>
                                        @endif
                                        {{ $presentacion_all->forma }}, {{ $presentacion_all->presentacion }}
                                        @if ($presentacion_all->trazabilidad)
                                            <b> - Trazable</b>
                                        @endif
                                    </option>
                                @else
                                    <option value="{{ $presentacion_all->id }}">
                                        @if ($presentacion_all->hospitalario)
                                            <b>H - </b>
                                        @endif
                                        {{ $presentacion_all->forma }}, {{ $presentacion_all->presentacion }}
                                        @if ($presentacion_all->trazabilidad)
                                            <b> - Trazable</b>
                                        @endif
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('presentacion')<div class="invalid-feedback">{{$message}}</div>@enderror
                    </label>
                </div>
                <div class="form-group" id="nueva-presentacion" hidden>
                    
                    <div class="row d-flex m-3">
                        <div class="form-group col">
                            <label>Forma *
                                <input type="text" name="nuevaforma" id="nuevaforma" class="form-control" placeholder="Nombre completo de la forma" value="{{ old('forma') }}" autofocus>
                                <div class="invalid-feedback" id="invalid-feedback-nuevaforma"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="row d-flex m-3">
                        <div class="form-group col">
                            <label>Presentacion *
                                <input type="text" name="nuevapresentacion" id="nuevapresentacion" class="form-control" placeholder="Nombre completo de la presentacion" value="{{ old('nuevapresentacion') }}" autofocus>
                                <div class="invalid-feedback" id="invalid-feedback-nuevapresentacion"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row d-flex m-3">
                        <div class="form-group col">
                            <label>
                                <input type="checkbox" id="checkboxTrazable" name="checkboxTrazable" value="1">Trazable
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" id="checkboxDivisible" name="checkboxDivisible" value="1">Divisible
                            </label>
                            <br>
                            <label>
                                <input type="checkbox" id="checkboxHospitalario" name="checkboxHospitalario" value="1">Hospitalario
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DATOS DE PROVEEDOR --}}
    <div class="card ml-2">
        <div class="card-header">
            <div class="row d-flex">
                <div class="col-8">
                    <h6 class="heading-small text-muted mb-1">PROVEEDOR/ES ({{$proveedor->count()}})</h6>
                </div>
                <div class="col-4 text-right">
                    {{-- <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                        data-target="#modalAgregarroveedor"><i class="fas fa-plus"></i></button> --}}
                    <a href="{{route('administracion.listaprecios.index')}}" class="btn btn-sm btn-success">Administrar en listados de proveedores</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="pl-lg-4">
               <table id="tablaProveedores" class="display nowrap">
                    <thead>
                        <th>Razón social</th>
                        <th>Cuit</th>
                        <th>Contacto</th>
                    </thead>
                    <tbody>
                    </tbody>
               </table>
            </div>
        </div>
    </div>
</div>
