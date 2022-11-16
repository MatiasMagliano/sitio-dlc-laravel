<div class="pl-lg-4">
    <h6 class="heading-small text-muted mb-1 mt-2">Datos básicos</h6>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-razon_social">Razón social *</label>
            <input type="text" name="razon_social" id="input-razon_social"
                class="form-control @error('razon_social') is-invalid @enderror"
                placeholder="Nombre completo del cliente o nombre de fantasía" value="{{ old('razon_social', $cliente->razon_social) }}"
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
                class="form-control @error('nombre_corto') is-invalid @enderror" value="{{ old('nombre_corto', $cliente->nombre_corto) }}">
            @error('nombre_corto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col-2">
            <label for="input-tipo_afip"><span class="hide">Tipo *</span> de Inscripción</label>
            <input type="text" name="tipo_afip" id="input-tipo_afip" class="form-control text-uppercase" value="{{old('tipo_afip', $cliente->tipo_afip)}}" readonly>
            @error('tipo_afip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col">
            <label for="input-afip">Número *</label>
            <input type="text" name="afip" id="input-afip"
                class="form-control" value="{{ old('afip', $cliente->afip) }}" readonly>
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
                class="form-control @error('contacto') is-invalid @enderror" value="{{ old('contacto', $cliente->contacto) }}">
            @error('contacto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-telefono">Teléfono *</label>
            <input type="text" name="telefono" id="input-telefono"
                class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $cliente->telefono) }}">
            <small id="input-afip" class="form-text text-muted">Sin guiones, paréntesis u otros caracteres
                especiales.</small>
            @error('telefono')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col">
            <label for="input-email">E-mail *</label>
            <input type="email" name="email" id="input-email"
                class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $cliente->email) }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h6 class="heading-small text-muted mb-1 mt-5">Datos de envío</h6>
    <hr>
    <p>Podrá editar los Puntos de Envío y sus datos en la sección <a href="{{route('administracion.dde.index')}}">Cliente/Direcciones de entrega</a>.</p>


    <div class="card mt-5">
        <div class="card-header">
            <h6 class="heading-small text-muted">Esquema de precios - Descuento en % de hasta dos decimales</h6>
        </div>
        <div class="card-body">
            <div class="row d-flex justify-content-center">
                <div class="form-group col-lg-2 col-md-6 m-3 ">
                    <label for="input-esquema">Descuento 1</label>
                    <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                        name="esquema[]" id="input-esquema" value="{{old('esquema.0', $cliente->esquemaPrecio->porcentaje_1)}}" min="0">
                </div>
                <div class="form-group col-lg-2 col-md-6 m-3">
                    <label for="input-esquema">Descuento 2</label>
                    <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                        name="esquema[]" id="input-esquema" value="{{old('esquema.1', $cliente->esquemaPrecio->porcentaje_2)}}" min="0">
                </div>
                <div class="form-group col-lg-2 col-md-6 m-3">
                    <label for="input-esquema">Descuento 3</label>
                    <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                        name="esquema[]" id="input-esquema" value="{{old('esquema.2', $cliente->esquemaPrecio->porcentaje_3)}}" min="">
                </div>
                <div class="form-group col-lg-2 col-md-6 m-3">
                    <label for="input-esquema">Descuento 4</label>
                    <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                        name="esquema[]" id="input-esquema" value="{{old('esquema.3', $cliente->esquemaPrecio->porcentaje_4)}}" min="0">
                </div>
                <div class="form-group col-lg-2 col-md-6 m-3">
                    <label for="input-esquema">Descuento 5</label>
                    <input class="form-control form-control-sm @error('esquema') is-invalid @enderror" type="number"
                        name="esquema[]" id="input-esquema" value="{{old('esquema.4', $cliente->esquemaPrecio->porcentaje_5)}}" min="0">
                </div>
            </div>
        </div>
    </div>
</div>
