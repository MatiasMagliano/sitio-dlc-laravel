<div class="pl-lg-5 pr-lg-5 border">
    <h6 class="heading-small text-muted mb-1 mt-2">Datos del encabezado</h6>
    <br>

    <div class="form-group d-flex m-3">
        <div class="form-group col">
            <label for="input-nombre">Nombre del reporte *</label>
            <input type="text" name="nombre" id="input-nombre"
                class="form-control form-control-sm @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-reporte">Reporte de módulo <small class="small gray">(Origen de datos)</small> *</label>
            <select name="reporte_id" id="input-reporte"
                class="selector-reporte @error('reporte_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
                @foreach ($modulos as $modulo)
                    <option value="{{ $modulo->value }}">{{ $modulo->text }}</option>
                @endforeach
            </select>
            @error('reporte_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <div class="form-group col">
                <label for="input-dirigido">Dirigido a *</label>
                <input type="text" name="dirigido" id="input-dirigido"
                    class="form-control form-control-sm @error('dirigido') is-invalid @enderror" value="{{ old('dirigido') }}">
                @error('dirigido')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row d-flex m-3">
        <div class="form-group col-3">
            <label for="input-submodulo">Reporte/Listado *</label><br>
            <select name="submodulo_id" id="input-submodulo"
                class="form-select @error('submodulo_id') is-invalid @enderror" size="{{ count($submodulos) }}">

                @foreach ($submodulos as $submodulo)
                    <option value="{{ $submodulo->value }}">{{ $submodulo->text }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-9">
            <div class="row">
                <div class="form-group col">
                    <label for="crear-campo">Campos adicionales del reporte *</label><br>
                    <div id="crear-campos" class="btn-group" role="group" aria-label="Crear campos adicionales">
                        <button type="button" class="btn btn-sm btn-success">Crear campo</button>
                        <button type="button" class="btn btn-sm btn-secondary">Cancelar</button>
                    </div>
                </div>
            </div>
            <div class="row d-flex">
                <div class="col">
                    @section('plugins.Summernote', true)
                    <x-adminlte-text-editor name="teBasic" label="Campo adicional 1" />
                </div>
            </div>
        </div>
    </div>
</div>
