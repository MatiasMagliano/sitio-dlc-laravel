<div class="pl-lg-4">
    <h6 class="heading-small text-muted mb-1 mt-2">Datos básicos</h6>
    <br>
    <input type="checkbox" id="sin_esquema" name="sin_esquema" {{ old('sin_esquema') == 1 ? 'checked' : '' }}>
    <label for="sin_esquema">Cliente sin esquema de precios</label>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-razon_social">Razón social *</label>
            <input type="text" name="razon_social" id="input-razon_social"
                class="form-control @error('razon_social') is-invalid @enderror"
                placeholder="Nombre completo del cliente o nombre de fantasía" value="{{ old('razon_social') }}"
                autofocus>
            @error('razon_social')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row d-flex m-3">
        <div class="form-group col-2">
            <label for="input-nombre_corto">Nombre corto *</label>
            <input type="text" name="nombre_corto" id="input-nombre_corto"
                class="form-control @error('nombre_corto') is-invalid @enderror" value="{{ old('nombre_corto') }}">
            @error('nombre_corto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-2">
            <label for="input-tipo_afip"><span class="hide">Tipo *</span> de Inscripción</label>
            <select name="tipo_afip" id="input-tipo_afip" class="form-control @error('tipo_afip') is-invalid @enderror">
                <option>Seleccione una opción</option>
                <option value="cuil" {{ old('tipo_afip') == 'cuil' ? 'selected' : '' }}>CUIL</option>
                <option value="cuit" {{ old('tipo_afip') == 'cuit' ? 'selected' : '' }}>CUIT</option>
            </select>
            @error('tipo_afip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col">
            <label for="input-afip">Número *</label>
            <input type="text" name="afip" id="input-afip"
                class="form-control @error('afip') is-invalid @enderror" value="{{ old('afip') }}">
            <small id="input-afip-tip" class="form-text text-muted">Ingrese solo números.</small>
            @error('afip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h6 class="heading-small text-muted mb-1 mt-5">Datos de Contacto *</h6>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col-12">
            <label for="input-contacto">Persona de contacto *</label>
            <input type="text" name="contacto" id="input-contacto"
                class="form-control @error('contacto') is-invalid @enderror" value="{{ old('contacto') }}">
            @error('contacto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-telefono">Teléfono *</label>
            <input type="text" name="telefono" id="input-telefono"
                class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
            <small id="input-afip" class="form-text text-muted">Sin guiones, paréntesis u otros caracteres
                especiales.</small>
            {{-- <div class="invalid-feedback" id="invalid-feedback-telefono"></div> --}}
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col">
            <label for="input-email">E-mail *</label>
            <input type="email" name="email" id="input-email"
                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h6 class="heading-small text-muted mb-1 mt-5">Datos de envío</h6>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-lugar_entrega">Lugar de entrega *</label>
            <input type="lugar_entrega" name="lugar_entrega" id="input-lugar_entrega"
                class="form-control form-control-sm @error('lugar_entrega') is-invalid @enderror"
                value="{{ old('lugar_entrega') }}">
            @error('lugar_entrega')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row d-flex m-3">
        <div class="form-group col-md-7">
            <label for="input-domicilio">Dirección *</label>
            <input type="domicilio" name="domicilio" id="input-domicilio"
                class="form-control form-control-sm @error('domicilio') is-invalid @enderror"
                value="{{ old('domicilio') }}">
            @error('domicilio')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <label for="input-provincia">Provincia *</label>
            <select name="provincia_id" id="input-provincia"
                class="selector-provincia @error('provincia_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
                @foreach ($provincias as $provincia)
                    @if ($provincia->id == old('provincia_id'))
                        <option value="{{ $provincia->id }}" selected>{{ $provincia->nombre }}</option>
                    @else
                        <option value="{{ $provincia->id }}">{{ $provincia->nombre }}</option>
                    @endif
                @endforeach
            </select>
            @error('provincia_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col">
            <label for="input-localidad">Localidad *</label>
            <select id="input-localidad" name="localidad_id"
                class="selector-localidad @error('localidad_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
                @foreach ($localidades as $localidad)
                    @if ($localidad->id == old('localidad_id'))
                        <option value="{{ $localidad->id }}" selected>{{ $localidad->nombre }}</option>
                    @else
                        <option value="{{ $localidad->id }}">{{ $localidad->nombre }}</option>
                    @endif
                @endforeach
            </select>
            @error('localidad_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-condiciones">Condiciones</label>
            <textarea name="condiciones" class="form-control @error('condiciones') is-invalid @enderror" id="input-condiciones"
                rows="2">{{ old('condiciones') }}</textarea>
            <small id="input-condiciones" class="form-text text-muted">Datos relevantes como: encargado recepción, Nº
                puerta, piso, edificio, etc.</small>
            @error('condiciones')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col">
            <label for="input-observaciones">Observaciones</label>
            <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror"
                id="input-observaciones" rows="2">{{ old('observaciones') }}</textarea>
            @error('observaciones')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>


    <div class="card mt-5">
        <div class="card-header">
            <h6 class="heading-small text-muted">Esquema de precios - Descuento en % de hasta dos decimales</h6>
        </div>
        <div class="card-body">
            <div class="row d-flex justify-content-center">

                <div id="descuento_1" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 1
                        <input name="esquema_1"
                        class="form-control @error('esquema_1') is-invalid @enderror"
                        value="{{ old('esquema_1', 0) }}" min="0" max="99" >
                    </label>
                    @error('esquema_1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div id="descuento_2" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 2
                        <input name="esquema_2" 
                        class="form-control @error('esquema_2') is-invalid @enderror"
                        value="{{ old('esquema_2', 0) }}" min="0" max="99">
                    </label>
                    @error('esquema_2')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div id="descuento_3" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 3
                        <input name="esquema_3" 
                        class="form-control @error('esquema_3') is-invalid @enderror"
                        value="{{ old('esquema_3', 0) }}" min="0" max="99">
                    </label>
                    @error('esquema_3')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div id="descuento_4" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 4
                        <input name="esquema_4" 
                        class="form-control @error('esquema_4') is-invalid @enderror"
                        value="{{ old('esquema_4', 0) }}" min="0" max="99">
                    </label>
                    @error('esquema_4')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div id="descuento_5" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 5
                        <input name="esquema_5" 
                        class="form-control @error('esquema_5') is-invalid @enderror"
                        value="{{ old('esquema_5', 0) }}" min="0" max="99">
                    </label>
                    @error('esquema_5')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
        </div>
        <div id="cuerpo_esquema"></div>
    </div>
</div>
