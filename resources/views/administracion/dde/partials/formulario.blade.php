<h6 class="heading-small text-muted mb-1 mt-1">Datos de envío</h6>
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
