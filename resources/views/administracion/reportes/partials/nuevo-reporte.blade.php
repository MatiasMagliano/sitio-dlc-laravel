<input type="hidden" name="tipo_documento" value="{{ $documento->tipo_documento }}">

{{-- CONTIENE: opción de agregar campos de encabezado --}}
<div class="row d-flex pt-3">
    <div class="form-group col-2">
        <label for="btn_crear_campo_encabezado">
            Campos adicionales de encabezado
            <br>
            <small class="text-gray">
                (agrega hasta {{ $max_encabezados }} campos de texto al encabezado del reporte)
            </small>
        </label>
        <a id="btn_crear_campo_encabezado" class="btn btn-sm btn-success" href="javascript:void(0);">
            <i class="fas fa-plus"></i> agregar
        </a>
    </div>

    <div class="form-group col-10">
        <div class="div-encabezado" style="width: 100%">
            <div id="wrapper-encabezado" style="width: 100%">
                @foreach (old('campo_encabezado', []) as $encabezado)
                    <button type="button" class="btn btn-sm btn-danger remove_button">
                        <i class="fas fa-minus"></i>
                    </button>&nbsp;&nbsp;&nbsp;
                    <label for="campo_encabezado">Encabezado adicional {{ $loop->index + 1 }}*</label>
                    <textarea name="campo_encabezado[]" class="form-control campo-encabezado" onfocus="inicializarSummernote()">
                        {!! html_entity_decode($encabezado) !!}
                    </textarea>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- CONTIENE: selector del reporte --}}
<hr>
<div class="row d-flex pt-3">
    <div class="form-group col">
        <label for="input-reporte">Reporte principal *</label>
        <select name="reporte" class="selector-reporte @error('reporte') is-invalid @enderror">
            <option data-placeholder="true"></option>
            @foreach ($reportes as $reporte)
                @if (old('reporte'))
                    <option value="{{ $reporte->id }}" selected>{{ $reporte->nombre }}</option>campo
                @else
                    <option value="{{ $reporte->id }}">{{ $reporte->nombre }}</option>
                @endif
            @endforeach
        </select>
        @error('reporte')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- CONTIENE: opción de agregar campos de texto adicionales al cuerpo --}}
<hr>
<div class="row d-flex pt-3">
    <div class="form-group col-2">
        <label for="btn_crear_campo_reporte">
            Campos adicionales del cuerpo
            <br>
            <small class="text-gray">
                (agrega {{ $max_texto_cuerpo }} campos de texto al cuerpo del reporte)
            </small>
        </label>
        <button type="button" id="btn_crear_campo_reporte" class="btn btn-sm btn-success">
            <i class="fas fa-plus"></i> agregar
        </button>
    </div>

    <div class="form-group col-10">
    </div>
</div>

{{-- CUARTA LÍNEA. CONTIENE: listados --}}
<hr>
<div class="row d-flex pt-3">
    <div class="form-group col-2">
        <label for="crear-campo">
            Listados anexos
            <br>
            <small class="text-gray">
                (anexa hasta {{ $max_encabezados }} listados al cuerpo del reporte)
            </small>
        </label>
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
