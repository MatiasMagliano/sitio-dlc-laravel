
<div class="pl-lg-4">
    <div class="form-group">
        <label for="input-proveedor">Razon Social</label>
        <select name="proveedor" id="input-proveedor"
            class="selecion-proveedor form-control-alternative" autocomplete="off">
            <option data-placeholder="true"></option>
            @foreach ($proveedores as $proveedor)
                @if ($proveedor->id == old('proveedor'))
                    <option value="{{ $proveedor->id }}" selected>
                        {{ $proveedor->cuit }} | {{ $proveedor->razon_social }}
                    </option>
                @else
                    <option value="{{ $proveedor->id }}">
                        {{ $proveedor->cuit }} | {{ $proveedor->razon_social }}
                    </option>
                @endif
            @endforeach
        </select>
        @error('proveedor')<div class="invalid-feedback">{{$message}}</div>@enderror
    </div>

    <div class="form-row d-flex">
        <table id="tabla1" class="table table-bordered table-responsive-md" width="100%">
            <thead>
                <th>Código de Proveedor *</th>
                <th>Droga *</th>
                <th>Forma/Presentacion *</th>
                <th>Costo *</th>
                <th>Acciones</th>
            </thead>

            <tbody>
                <tr>
                    <td id="td-codigo">
                        <input type="text" name="codigo" id="codigo" maxlength="15"  style="height: 30px"  class="form-control" autocomplete="off">
                        {{-- <div class="error">Este campo es necesario</div> --}}
                        <div id="message-codigo" class="invalid-feedback error">* Este campo es necesario</div>
                    </td>

                    <td id="td-producto">
                        <select name="producto" id="input-producto" class="selecion-producto form-control-alternative" autocomplete="off">
                            <option data-placeholder="true" value="0"></option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}">{{ $producto->id }}- {{ $producto->droga }}</option>
                            @endforeach
                        </select>
                        {{-- <p class="error">Este campo es necesario</p> --}}
                        <div id="message-producto" class="invalid-feedback error">* Este campo es necesario</div>
                    </td>

                    <td id="td-presentacion">
                        <select name="presentacion" id="input-presentacion" class="selecion-presentacion form-control-alternative" autocomplete="off">
                        <option data-placeholder="true" value="0"></option>
                            @foreach ($presentaciones as $presentacion)
                                @if ($presentacion->id == old('presentacion'))
                                    <option value="{{ $presentacion->id }}" selected>{{ $presentacion->id }}- {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                                @else
                                    <option value="{{ $presentacion->id }}">{{ $presentacion->id }}- {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                                @endif
                            @endforeach
                        </select>
                        {{-- <p class="error">Este campo es necesario</p> --}}
                        <div id="message-presentacion" class="invalid-feedback error">* Este campo es necesario</div>
                    </td>

                    <td id="td-precio">
                        <input type="number" name="precio_compra" id="precio_compra" style="height: 30px" class="form-control" autocomplete="off">
                        {{-- <p class="error">Este campo es necesario y debe tener formato de precio</p> --}}
                        <div id="message-precio" class="invalid-feedback error">* Este campo es necesario</div>
                    </td>

                    <td>
                        <a id="additem" type="button" href="#" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Agregar Producto</a>
                    </td>
                </tr>
            </tbody>  
        </table>
    </div>
</div>