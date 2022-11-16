{{-- producto=producto+presentacion. Se retienen los dos ID combinados. Luego el controller los separa --}}
<label for="input-producto">Producto y presentación</label>
<div class="form-group row">
    <select name="producto" id="input-producto"
        class="seleccion-producto col @error('producto') is-invalid @enderror">
        <option data-placeholder="true"></option>
        {{-- se separa el producto y la presentación para que quede seleccionado en el slimselect --}}
        @php
            $prod_pres = explode('|', @old('producto'));
        @endphp
        @foreach ($productos as $producto)
            @foreach ($producto->presentacion as $presentacion)
                @if ($producto->id == $prod_pres[0] && $presentacion->id == $prod_pres[1])
                    <option value="{{$producto->id}}|{{$presentacion->id}}" selected>{{ $producto->droga }} -
                        {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                @else
                    <option value="{{$producto->id}}|{{$presentacion->id}}">{{ $producto->droga }} -
                        {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                @endif
            @endforeach
        @endforeach
    </select>
    @error('producto')<div class="invalid-feedback">{{$message}}</div>@enderror
</div>
<hr>
{{-- SELECCIONAR Y SUGERIR LOS PRECIOS --> debe terminar siendo FLOAT --}}
<div class="card mx-auto" style="width: 80%;">
    <div class="card-body">
        <h5 class="card-title">Precios sugeridos</h5>
        <br>
        <table id="tablaPreciosSugeridos" class="table table-responsive-md table-bordered table-condensed" width="100%">
            <thead>
                <th>Proveedor</th>
                @if ($porcentaje->porcentaje_1 != 0)
                    <th>Desct. al {{$porcentaje->porcentaje_1}}%</th>
                @else
                    <th>SIN DESCUENTO</th>
                @endif
                @if ($porcentaje->porcentaje_2 != 0)
                    <th>Desct. al {{$porcentaje->porcentaje_2}}%</th>
                @else
                    <th>SIN DESCUENTO</th>
                @endif
                @if ($porcentaje->porcentaje_3 != 0)
                    <th>Desct. al {{$porcentaje->porcentaje_3}}%</th>
                @else
                    <th>SIN DESCUENTO</th>
                @endif
                @if ($porcentaje->porcentaje_4 != 0)
                    <th>Desct. al {{$porcentaje->porcentaje_4}}%</th>
                @else
                    <th>SIN DESCUENTO</th>
                @endif
                @if ($porcentaje->porcentaje_5 != 0)
                    <th>Desct. al {{$porcentaje->porcentaje_5}}%</th>
                @else
                    <th>SIN DESCUENTO</th>
                @endif
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<hr>
<div class="row d-flex">
    <div class="col">
        <div class="form-group">
            <label for="input-precio">Precio</label>
            <input type="number" name="precio" id="input-precio" min="0"
                class="form-control @error('precio') is-invalid @enderror"
                value="@if(old('precio')){{old('precio')}}@endif"
                step=".01">
                @error('precio')<div class="invalid-feedback">{{$message}}</div>@enderror
        </div>
    </div>

    <div class="col">
        <div class="form-group">
            <label for="input-cantidad">Cantidad</label>
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
