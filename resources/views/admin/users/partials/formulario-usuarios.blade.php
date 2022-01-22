@csrf

{{-- Campo de nombre --}}
<div class="input-group mb-3">
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            {{--OJO QUE el isset, ESTÁ DENTRO DEL value del tag--}}
            value="{{ old('name') }}@isset($editusuario){{ $editusuario->name }}@endisset"
            placeholder="{{ __('adminlte::adminlte.full_name') }}" autofocus>

    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-user {{ config('adminlte.classes_auth_icon', '') }}"></span>
        </div>
    </div>

    @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

{{-- Campo de E-mail --}}
<div class="input-group mb-3">
    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}@isset($editusuario){{ $editusuario->email }}@endisset"
            placeholder="{{ __('adminlte::adminlte.email') }}">

    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
        </div>
    </div>

    @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

{{-- Campo de contraseña, sólo se muestra cuando la bandera crear está en true --}}
@isset($band_crear)
<div class="input-group mb-3">
    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            placeholder="{{ __('adminlte::adminlte.password') }}">

    <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
        </div>
    </div>

    @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
@endisset

{{-- Selección de roles --}}
<x-adminlte-card title="Slección de roles" theme="secondary">
    @foreach ($roles as $rol)
        <div class="form-check">
                <input type="checkbox" name="roles[]" class="form-check-input" value="{{ $rol->id }}"
                id="{{ $rol->nombre }}"
            @isset($editusuario)
                @if(in_array($rol->id, $editusuario->roles->pluck('id')->toArray()))
                    checked
                @endif
            @endisset/>
            <label for="{{ $rol->nombre }}" class="form-check-label">{{ $rol->nombre }}</label>
        </div>
    @endforeach
</x-adminlte-card>