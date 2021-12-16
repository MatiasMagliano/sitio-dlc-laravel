@csrf

{{-- Campo de nombre --}}
<div class="input-group mb-3">
    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            {{--OJO QUE el isset, ESTÁ DENTRO DEL value del tag--}}
            value="{{ old('nombre') }}@isset($rol){{ $rol->nombre }}@endisset"
            placeholder="{{ __('adminlte::adminlte.role_name') }}" autofocus>

    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-fw fa-user-tag {{ config('adminlte.classes_auth_icon', '') }}"></span>
        </div>
    </div>

    @error('nombre')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

{{-- Campo de descripción --}}
<div class="input-group mb-3">
    <textarea name="descripcion" rows="7" class="form-control @error('descripcion') is-invalid @enderror"
    {{--OJO QUE el isset, ESTÁ DENTRO DEL value del tag--}}
    value="{{ old('descripcion') }}"
    placeholder="{{ __('adminlte::adminlte.role_description') }}">@isset($rol){{ $rol->descripcion }}@endisset</textarea>
    
    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-fw fa-paragraph {{ config('adminlte.classes_auth_icon', '') }}"></span>
        </div>
    </div>

    @error('nombre')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>