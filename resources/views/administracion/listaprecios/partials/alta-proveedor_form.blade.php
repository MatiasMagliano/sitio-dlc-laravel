<form action="{{ route('administracion.listaprecios.alta.nuevoListadoPrecioProveedor') }}" method="POST" id="formAgregProveedor" class="needs-validation" autocomplete="off" novalidate>
    @csrf
    @method('POST')
    <div class="pl-lg-4">
        
        <div class="row d-flex m-1">
            <div class="col-10">
                <h6 class="heading-small text-muted">Datos básicos del Proveedor</h6>
            </div>
            <div class="col-2 d-flex justify-content-xl-end">
                <button type="submit" id="guardarAprobada" class="btn btn-sm btn-sidebar btn-success">
                    <i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar y continuar</span>
                </button>
            </div>
        </div>
        <hr>
        <div class="row d-flex m-1">
            <div class="form-group col">
                <label for="input-razon_social">Razón social *</label>
                <input type="text" name="razon_social" id="input-razon_social" placeholder="Nombre completo del proveedor o nombre de fantasía" 
                class="form-control @error('razon_social') is-invalid @enderror" value="{{ old('razon_social') }}" required autofocus maxlength="100">
                @error('razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="invalid-feedback-razon_social"></div>
            </div>
        </div>
        <div class="row d-flex m-1">   
            <div class="form-group col">
                <label for="input-cuit">Cuit *</label>
                <input type="text" name="cuit" id="input-cuit" 
                class="form-control @error('cuit') is-invalid @enderror" value="{{ old('cuit') }}" required>
                <small id="input-cuit-tip" class="form-text text-muted">Ingrese solo números.</small>
                @error('cuit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="invalid-feedback-cuit"></div>
            </div>
        </div>
        
        <br>
        <div class="row d-flex m-1">
            <h6 class="heading-small text-muted">Datos de contacto del Proveedor</h6>
        </div>
        <hr>
        <div class="row d-flex m-1">   
            <div class="form-group col">
                <label for="input-email">E-mail de Contacto *</label>
                <input type="email" name="email" id="input-email" 
                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="No posee dirección wb" maxlength="100" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="invalid-feedback-email"></div>
            </div>
        </div>

        <div class="row d-flex m-1">
            <div class="form-group col">
                <label for="input-web">Dirección Web *</label>
                <input type="web" name="web" id="input-web" 
                class="form-control @error('web') is-invalid @enderror" value="{{ old('web') }}" maxlength="100" required>
                @error('web')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="invalid-feedback-web"></div> 
            </div>
        </div>

        <br>
        <div class="row d-flex m-1">
            <h6 class="heading-small text-muted">Datos de ubicación del Proveedor</h6>
        </div>
        <hr>
        <div class="row d-flex m-1">
            <div class="form-group col">
                <label for="input-domicilio">Dirección *</label>
                <input type="domicilio" name="domicilio" id="input-domicilio" value="{{ old('domicilio') }}" 
                class="form-control @error('domicilio') is-invalid @enderror" maxlength="100">
                @error('domicilio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="invalid-feedback-domicilio"></div>    
            </div>
        </div>

        <div class="row d-flex m-1">
            <div class="form-group col-6">
                <label for="input-provincia">Provincia *</label>
                <select name="provincia_id" id="input-provincia" class="selector-provincia @error('provincia_id') is-invalid @enderror">
                    <option data-placeholder="true"></option>
                    @foreach ($provincias as $provincia)
                        @if ($provincia->id == old('provincia_id'))
                            <option value="{{ $provincia->id }}" selected>{{ $provincia->nombre }}</option>
                        @else
                            <option value="{{ $provincia->id }}">{{ $provincia->nombre }}</option>
                        @endif
                    @endforeach
                </select>
                @error('provincia_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="invalid-feedback-provincia"></div>    
            </div>
            <div class="form-group col-6">
                <label for="input-localidad">Localidad *</label>
                <select id="input-localidad" name="localidad_id" class="selector-localidad @error('localidad_id') is-invalid @enderror">
                    <option data-placeholder="true"></option>
                    @foreach ($localidades as $localidad)
                        @if ($localidad->id == old('localidad_id'))
                            <option value="{{ $localidad->id }}" selected>{{ $localidad->nombre }}</option>
                        @else
                            <option value="{{ $localidad->id }}">{{ $localidad->nombre }}</option>
                        @endif
                    @endforeach
                </select>
                @error('localidad_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="invalid-feedback" id="invalid-feedback-localidad"></div>  
            </div>
        </div>
        
        
        {{--<h6 class="heading-small text-muted mb-1 mt-5">Datos de Contacto *</h6>
        <hr>
        <div class="row d-flex m-3">
            <div class="form-group col-12">
                <label for="input-contacto">Persona de contacto *</label>
                <input type="t
                ext" name="contacto" id="input-contacto"
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
        </div>--}}
    
        {{--<h6 class="heading-small text-muted mb-1 mt-5">Datos de envío</h6>
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
                    <div class="form-group col-lg-2 col-md-6 m-3 ">
                        <label for="input-esquema">Descuento 1</label>
                        <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                            name="esquema[]" id="input-esquema" value="0" min="0">
                    </div>
                    <div class="form-group col-lg-2 col-md-6 m-3">
                        <label for="input-esquema">Descuento 2</label>
                        <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                            name="esquema[]" id="input-esquema" value="0" min="0">
                    </div>
                    <div class="form-group col-lg-2 col-md-6 m-3">
                        <label for="input-esquema">Descuento 3</label>
                        <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                            name="esquema[]" id="input-esquema" value="0" min="">
                    </div>
                    <div class="form-group col-lg-2 col-md-6 m-3">
                        <label for="input-esquema">Descuento 4</label>
                        <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                            name="esquema[]" id="input-esquema" value="0" min="0">
                    </div>
                    <div class="form-group col-lg-2 col-md-6 m-3">
                        <label for="input-esquema">Descuento 5</label>
                        <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                            name="esquema[]" id="input-esquema" value="0" min="0">
                    </div>
                </div>
            </div>
            <div id="cuerpo_esquema"></div>
        </div>--}}
    </div>

</form>