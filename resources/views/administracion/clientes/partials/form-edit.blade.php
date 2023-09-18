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
            <div class="invalid-feedback" id="invalid-feedback-telefono"></div>
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
                <div id="descuento_1" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 1
                        <input type="number" name="esquema_1" min="0" max="99"
                            class="form-control form-control-sm @error('esquema_1') is-invalid @enderror"
                            value="{{old('esquema_1', $cliente->esquemaPrecio->porcentaje_1)}}">
                    </label>
                </div>
                <div id="descuento_2" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 2
                        <input type="number" name="esquema_2" min="0" max="99"
                            class="form-control form-control-sm @error('esquema_2') is-invalid @enderror"
                            value="{{old('esquema_2', $cliente->esquemaPrecio->porcentaje_2)}}">
                    </label>
                </div>
                <div id="descuento_3" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 3
                        <input type="number" name="esquema_3" min="0" max="99"
                            class="form-control form-control-sm @error('esquema_3') is-invalid @enderror"
                            value="{{old('esquema_3', $cliente->esquemaPrecio->porcentaje_3)}}">
                    </label>
                </div>
                <div id="descuento_4" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 4
                        <input type="number" name="esquema_4" min="0" max="99"
                            class="form-control form-control-sm @error('esquema_4') is-invalid @enderror"
                            value="{{old('esquema_4', $cliente->esquemaPrecio->porcentaje_4)}}">
                    </label>
                </div>
                <div id="descuento_5" class="form-group col-lg-2 col-md-6 m-3 ">
                    <label>Descuento 5
                        <input type="number" name="esquema_5" min="0" max="99"
                            class="form-control form-control-sm @error('esquema_5') is-invalid @enderror"
                            value="{{old('esquema_5', $cliente->esquemaPrecio->porcentaje_5)}}">
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
