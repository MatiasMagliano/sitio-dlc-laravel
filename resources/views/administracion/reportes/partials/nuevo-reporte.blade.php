<div class="pl-lg-5 pr-lg-5 border">
    <h6 class="heading-small text-muted mb-1 mt-2">Datos del encabezado</h6>
    <br>

    {{-- PRIMERA LÍNEA. CONTIENE: nombre del reporte y dirigido a --}}
    <div class="form-group row d-flex m-3">
        <div class="form-group col">
            <label for="input-nombre">Nombre del reporte *</label>
            <input type="text" name="nombre" id="input-nombre"
                class="form-control form-control-sm @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group col">
            <div class="form-group col">
                <label for="input-dirigido_a">Dirigido a *</label>
                <input type="text" name="dirigido_a" id="input-dirigido_a"
                    class="form-control form-control-sm @error('dirigido_a') is-invalid @enderror"
                    value="{{ old('dirigido_a') }}">
                @error('dirigido_a')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- SEGUNDA LÍNEA. CONTIENE: campos del ENCABEZADO adicionales personalizados --}}
    <h4 class="pt-3">Encabezado del reporte</h4>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col-2">
            <label for="crear-campo">CAMPOS ENCABEZADO <small class="text-gray">(agrega texto al encabezado del reporte)</small></label><br>
            <div id="crear-campos" class="btn-group" role="group" aria-label="Crear campos adicionales">
                <button type="button" id="btn_crear_campo_encabezado" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> agregar
                </button>
            </div>
        </div>

        <div class="form-group col-10">
            <label for="campo-encabezado">Encabezado por defecto *</label>
            @section('plugins.Summernote', true)
            <div class="div-encabezado" style="width: 100%">
                <textarea name="campo-encabezado" id="campo-encabezado" class="form-control" readonly>{!! html_entity_decode($encabezado) !!}</textarea>
                <div id="wrapper-encabezado" style="width: 100%">
                </div>
            </div>
        </div>
    </div>

    {{-- TERCERA LÍNEA. CONTIENE: reporte de módulo --}}
    <h4 class="pt-5">Cuerpo del reporte</h4>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col">
            <label for="input-reporte">Reporte principal *</label>
            <select name="reporte_id" id="input-reporte"
                class="selector-reporte @error('reporte_id') is-invalid @enderror">
                <option data-placeholder="true"></option>
                @foreach ($reportes as $reporte)
                    <option value="{{ $reporte->id }}">{{ $reporte->nombre }}</option>
                @endforeach
            </select>
            @error('reporte_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row d-flex m-3">
        <div class="form-group col-2">
            <label for="crear-campo">CAMPOS DEL CUERPO <small class="text-gray">(agrega texto al cuerpo del reporte)</small></label><br>
            <div id="crear-campos-adicionales" class="btn-group" role="group" aria-label="Crear campos adicionales al cuerpo del reporte">
                <button type="button" id="btn_crear_campo_reporte" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> agregar
                </button>
            </div>
        </div>

        <div class="form-group col-10">
            <div id="wrapper-campos" style="width: 100%">
            </div>
        </div>
    </div>

    {{-- CUARTA LÍNEA. CONTIENE: listados --}}
    <h4 class="pt-5">Listados anexados</h4>
    <hr>
    <div class="row d-flex m-3">
        <div class="form-group col-2">
            <label for="crear-campo">LISTADOS ANEXADOS <small class="text-gray">(anexa listados al cuerpo del reporte)</small></label><br>
            <div id="crear-listados" class="btn-group" role="group" aria-label="Anexar listados al reporte">
                <button type="button" id="btn_crear_listado" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> agregar
                </button>
            </div>
        </div>

        <div class="form-group col-10">
            <div id="wrapper-listados" style="width: 100%">
            </div>
        </div>
    </div>

    <div class="wrapper">
        {{-- BOTON GUARDAR --}}
        <div class="text-right pb-5 pr-3">
            <button type="submit" class="btn btn-sidebar btn-success">
                <i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span>
            </button>
        </div>
    </div>
</div>
