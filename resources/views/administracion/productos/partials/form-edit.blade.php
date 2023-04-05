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
                <label for="input-droga">Droga *</label>
                <input type="text" name="droga" id="input-droga"
                    class="form-control @error('droga') is-invalid @enderror"
                    placeholder="Nombre genérico o compuesto principal representativo"
                    value="{{ old('droga', $producto->droga) }}" autofocus>
                    @error('droga')<div class="invalid-feedback">{{$message}}</div>@enderror
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
                    <h6 class="heading-small text-muted mb-1">PRESENTACION</h6>
                </div>
                <div class="col-4 text-right">
                    <a href="{{route('administracion.presentaciones.create')}}" class="btn btn-sm btn-success">Nueva presentación</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="pl-lg-4">
                <div class="form-group">
                    <label for="input-presentacion">Forma farmacéutica y presentación *</label>
                    <select name="presentacion" id="input-presentacion"
                        class="selecion-presentacion form-control-alternative @error('presentacion') is-invalid @enderror">
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
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal"
                        data-target="#modalAgregarroveedor"><i class="fas fa-plus"></i></button>
                    <a href="{{route('administracion.proveedores.create')}}" class="btn btn-sm btn-success">Nuevo proveedor</a>
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
