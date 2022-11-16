{{-- ID DEL CLIENTE --}}
<input type="hidden" name="cliente_id" value="{{$dde->cliente->id}}">

{{-- DATOS BASICOS --}}
<div class="pl-lg-4">
    <h6 class="heading-small text-muted mb-1 mt-2">Datos básicos</h6>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col-2">
            <label for="input-lugar_entrega">Nombre del punto de entrega *</label>
            <input type="text" name="lugar_entrega" id="input-lugar_entrega"
                class="form-control form-control-sm @error('lugar_entrega') is-invalid @enderror"
                placeholder="Nombre completo del cliente o nombre de fantasía" value="{{ old('lugar_entrega', $dde->lugar_entrega) }}"
                autofocus>
            @error('razon_social')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <label for="input-domicilio">Domicilio *</label>
            <input type="text" name="domicilio" id="input-domicilio"
                class="form-control form-control-sm @error('domicilio') is-invalid @enderror"
                placeholder="Nombre completo del cliente o nombre de fantasía" value="{{ old('domicilio', $dde->domicilio) }}"
                autofocus>
            @error('razon_social')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <label for="input-provincia">Provincia *</label>
            <select name="provincia_id" id="input-provincia"
                class="selector-provincia @error('provincia_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
                @foreach ($provincias as $provincia)
                    @if ($provincia->id == old('provincia_id', $dde->provincia->id))
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
                @foreach ($localidades as $localidad_all)
                    @if ($localidad_all->id == old('localidad_id', $dde->localidad->id))
                        <option value="{{ $localidad_all->id }}" selected>{{ $localidad_all->nombre }}</option>
                    @else
                        <option value="{{ $localidad_all->id }}">{{ $localidad_all->nombre }}</option>
                    @endif
                @endforeach
            </select>
            @error('localidad_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row d-flex m-3">

    </div>

</div>

{{-- CONDICIONES y OBSERVACIONES --}}
<div class="pl-lg-4">
    <h6 class="heading-small text-muted mb-1 mt-2">Condiciones y observaciones</h6>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-condiciones">Condiciones</label>
            <textarea name="condiciones" class="form-control @error('condiciones') is-invalid @enderror" id="input-condiciones"
                rows="2">{{ old('condiciones', $dde->condiciones) }}</textarea>
            <small id="input-condiciones" class="form-text text-muted">Datos relevantes como: encargado recepción, Nº
                puerta, piso, edificio, etc.</small>
            @error('condiciones')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col">
            <label for="input-observaciones">Observaciones</label>
            <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror"
                id="input-observaciones" rows="2">{{ old('observaciones', $dde->observaciones) }}</textarea>
            @error('observaciones')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
