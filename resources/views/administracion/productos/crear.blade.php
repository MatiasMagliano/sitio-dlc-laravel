@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('content_header')
    <div class="row">
        <div class="col-md-10">
            <h1>Administración de productos | Crear producto</h1>
        </div>
        <div class="col-md-2 d-flex justify-content-end">
            <a href="{{ route('administracion.productos.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('administracion.productos.store') }}" method="post">
        @csrf

        {{-- Cuerpo del formulario --}}
        <div class="card row">
            <div class="col-md-6 offset-md-3">
                <div id="accordion" class="justify-content-md-center">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h4>
                                <a class="accordion-toggle" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne" role="button">
                                    <i class="indicator fas fa-chevron-down float-right"></i>
                                    Datos del producto
                                </a>
                            </h4>
                        </div>

                        {{-- Campos del producto --}}
                        <div id="collapseOne" class="collapse show bg-gray-light" aria-labelledby="headingOne"
                            data-parent="#accordion">
                            <div class="card-body">
                                {{-- Campo nombre/droga --}}
                                <label for="droga-input" class="label">{{ __('formularios.drug_name') }}</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="droga-input" id="droga-input"
                                        class="form-control @error('droga-input') is-invalid @enderror"
                                        value="{{ old('droga-input') }}@isset($editproducto){{ $editproducto->droga }}@endisset"
                                        autofocus>
                                    <div class="input-group-append">
                                        <a href="" class="btn btn-primary" role="button" data-toggle="modal"
                                            data-target="#modalBuscarProducto">
                                            <i class="fas fa-search"></i>
                                            Buscar
                                        </a>
                                    </div>

                                    @error('droga-input')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                {{-- Campos de Precio y vencimiento --}}
                                <div class="row">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="precio"
                                            class="label">{{ __('formularios.drug_price') }}</label>
                                        <input type="text" name="precio"
                                            class="form-control @error('precio') is-invalid @enderror"
                                            value="{{ old('precio') }}@isset($editproducto){{ $editproducto->precio }}@endisset">

                                        @error('precio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                        @section('plugins.TempusDominusBs4', true)
                                        <x-adminlte-input-date name="vencimiento" label="Vencimiento" igroup-size="md"
                                            :config="$config" placeholder="Seleccione una fecha de vencimiento">
                                            <x-slot name="appendSlot">
                                                <div class="input-group-text bg-dark">
                                                    <i class="fas fa-calendar"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input-date>
                                        @error('vencimiento')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                             </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h4>
                                <a class="collapsed" data-toggle="collapse" data-target="#collapseTwo"
                                    aria-expanded="false" aria-controls="collapseTwo" role="button">
                                    <i class="indicator fas fa-chevron-left float-right"></i>
                                    Datos de la presentación
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="collapse bg-gray-light" aria-labelledby="headingTwo"
                            data-parent="#accordion">
                            <div class="card-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad
                                squid. 3
                                wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                laborum
                                eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee
                                nulla
                                assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                                nesciunt
                                sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                                farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them
                                accusamus
                                labore sustainable VHS.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h4>
                                <a class="collapsed" data-toggle="collapse" data-target="#collapseThree"
                                    aria-expanded="false" aria-controls="collapseThree" role="button">
                                    <i class="indicator fas fa-chevron-left float-right"></i>
                                    Datos del proveedor
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="collapse" bg-gray-light aria-labelledby="headingThree"
                            data-parent="#accordion">
                            <div class="card-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad
                                squid. 3
                                wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt
                                laborum
                                eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee
                                nulla
                                assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred
                                nesciunt
                                sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer
                                farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them
                                accusamus
                                labore sustainable VHS.
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</form>

{{-- MODAL BUSCAR PRODUCTO --}}
<x-adminlte-modal id="modalBuscarProducto" title="Buscar un producto" theme="blue" icon="fas fa-search" size='md'>
    <form action="javascript:buscarProducto();">
        <div class="input-group">
            <input type="search" name="droga" id="droga" class="form-control" placeholder="Droga o nombre genérico..."
                aria-label="Droga o nombre genérico..." aria-describedby="basic-addon2" autofocus>
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header bg-light">
            <h6>Resultados</h6>
        </div>
        <div class="card-body" id="resultados">No hay resultados...</div>
    </div>
</x-adminlte-modal>
@endsection

@section('js')
<script>
    function seleccionar(item) {
        $('#modalBuscarProducto').modal('hide');
        $('#droga-input').val(item);
    };

    function toggleChevron(e) {
        $(e.target)
            .prev('.card-header')
            .find("i.indicator")
            .toggleClass('fas fa-chevron-down fas fa-chevron-left');
    }
    $('#accordion').on('hidden.bs.collapse', toggleChevron);
    $('#accordion').on('shown.bs.collapse', toggleChevron);

    function buscarProducto() {
        var divResultado = document.getElementById("resultados");
        var datos = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            droga: $("#droga").val()
        };

        $.ajax({
            type: "get",
            url: "/administracion/buscar",
            data: datos,
            enconde: true,
            success: function(respuesta) {
                var content = "";
                $.each(respuesta, function(index, item) {
                    var insertoFuncion = 'seleccionar(\"'+ item.droga +'\")';
                    content += "<p>" + item.droga + "<i onClick=\'"+ insertoFuncion +"\' class='fas fa-sign-in-alt float-right' style='cursor: pointer;'></i></p>";
                });
                divResultado.innerHTML = content;
            },
            error: function(respuesta) {
                console.error("Error:", respuesta)
            }
        });
    }
</script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión</b> 2.0 (LARAVEL V.8)
</div>
@endsection
