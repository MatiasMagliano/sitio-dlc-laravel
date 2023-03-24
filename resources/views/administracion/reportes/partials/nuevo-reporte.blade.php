<div class="pl-lg-4">
    <h6 class="heading-small text-muted mb-1 mt-2">Crear nuevo reporte</h6>
    <br>

    <div class="form-group d-flex m-3">
        <div class="form-group col">
            <label for="input-nombre">Nombre del reporte *</label>
            <input type="nombre" name="nombre" id="input-nombre"
                class="form-control form-control-sm @error('nombre') is-invalid @enderror"
                value="{{ old('nombre') }}">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-reporte">Reporte de módulo *</label>
            <select name="reporte_id" id="input-reporte"
                class="selector-reporte @error('reporte_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
                @foreach ($reportes as $reporte)
                    <option value="{{ $reporte->value }}">{{ $reporte->text }}</option>
                @endforeach
            </select>
            @error('reporte_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <label for="input-usuario">Dirigido a *</label>
            <select name="usuario_id" id="input-usuario"
                class="selector-usuario @error('usuario_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
            @error('usuario_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
