@extends('adminlte::page')

@section('title', 'Administrar Productos')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css" />
    <style>
        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-md-8">
            <h1>Crear nuevo listado de precios de Proveedor</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="{{ route('administracion.listaprecios.index') }}" role="button"
                class="btn btn-md btn-secondary">Volver a Listados de Precios</a>
        </div>
    </div>
@stop

@section('content')
    <form action="{{ route('administracion.productos.store') }}" method="post" class="needs-validation" autocomplete="off" novalidate>
        @csrf
        {{-- NOMBRE DEL PROVEEDOR --}}
        <div class="card">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-8">
                        <h6 class="heading-small text-muted mb-1">SELECCIONAR PROVEEDOR </h6>
                    </div>
                    <div class="col-4 text-right">
                        <button id="crearlista-btn" type="button" class="btn btn-sidebar btn-success"><i class="fas fa-share-square"></i>&nbsp;<span class="hide">Guardar</span></button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">
                    <div class="form-group">
                        <label for="input-proveedor">Razon Social</label>
                        <select name="proveedor" id="input-proveedor"
                            class="selecion-proveedor form-control-alternative @error('proveedor') is-invalid @enderror">
                            <option data-placeholder="true"></option>
                            @foreach ($proveedores as $proveedor)
                                @if ($proveedor->id == old('proveedor'))
                                    <option value="{{ $proveedor->id }}" selected>
                                        {{ $proveedor->cuit }} | {{ $proveedor->razon_social }}
                                    </option>
                                @else
                                    <option value="{{ $proveedor->id }}">
                                        {{ $proveedor->cuit }} | {{ $proveedor->razon_social }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('proveedor')<div class="invalid-feedback">{{$message}}</div>@enderror
                    </div>
                    <div class="form-row d-flex">
                        <form id="newitem" action="#" method="" class="needs-validation" autocomplete="off" novalidate>
                        <table id="tabla1" class="table table-bordered table-responsive-md" width="100%">
                            <thead>
                                <th>Código de Proveedor</th>
                                <th>Droga</th>
                                <th>Forma/Presentacion</th>
                                <th>Costo</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="codigo" id="codigo" maxlength="15" class="form-control
                                            @error('codigo') is-invalid @enderror" value="{{ old('cantidad') }}">
                                        @error('codigo')<div class="invalid-feedback">{{$message}}</div>@enderror
                                    </td>
                                    <td>
                                        <select name="producto" id="input-producto"
                                            class="selecion-producto form-control-alternative @error('producto') is-invalid @enderror">
                                        <option data-placeholder="true"></option>
                                            @foreach ($productos as $producto)
                                                @if ($producto->id == old('producto'))
                                                    <option value="{{ $producto->id }}" selected>{{ $producto->id }}- {{ $producto->droga }}</option>
                                                @else
                                                    <option value="{{ $producto->id }}">{{ $producto->id }}- {{ $producto->droga }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('producto')<div class="invalid-feedback">{{$message}}</div>@enderror
                                    </td>
                                    <td>
                                        <select name="presentacion" id="input-presentacion"
                                            class="selecion-presentacion form-control-alternative @error('presentacion') is-invalid @enderror">
                                        <option data-placeholder="true"></option>
                                            @foreach ($presentaciones as $presentacion)
                                                @if ($presentacion->id == old('presentacion'))
                                                    <option value="{{ $presentacion->id }}" selected>{{ $presentacion->id }}- {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                                                @else
                                                    <option value="{{ $presentacion->id }}">{{ $presentacion->id }}- {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('presentacion')<div class="invalid-feedback">{{$message}}</div>@enderror
                                    </td>
                                    <td>
                                        <input type="number" name="precio_compra" id="precio_compra" class="form-control @error('precio_compra') is-invalid @enderror" value="{{ old('precio_compra') }}">
                                        @error('precio_compra')<div class="invalid-feedback">{{$message}}</div>@enderror
                                    </td>
                                    <td>
                                        <a id="additem" type="button" href="#" class="btn btn-sidebar btn-primary"><i class="fas fa-plus"></i></a>
                                        <a id="clearitem" type="button" href="#" class="btn btn-sidebar btn-danger"><i class="fas fa-eraser"></i></a>
                                    </td>
                                </tr>
                            </tbody>  
                        </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-group">
            {{-- DATOS DE PROVEEDOR --}}
            {{-- <div class="card mr-2">
                <div class="card-header">
                    <div class="row d-flex">
                        <div class="col-8">
                            <h6 class="heading-small text-muted mb-1">PRODUCTOS</h6>
                        </div>
                        <div class="col-4 text-right">
                            <a id="additem" type="button" href="#" class="btn btn-sidebar btn-success"><i class="fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label for="input-nombre">Razón social</label>
                            <select name="proveedor" id="input-nombre"
                                class="selecion-proveedor form-control-alternative @error('proveedor') is-invalid @enderror">
                                <option data-placeholder="true"></option>
                                @foreach ($proveedores as $proveedor)
                                    @if ($proveedor->id == old('proveedor'))
                                        <option value="{{ $proveedor->id }}" selected>{{ $proveedor->razon_social }} -
                                            {{ $proveedor->cuit }}</option>
                                    @else
                                        <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }} -
                                            {{ $proveedor->cuit }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('proveedor')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                </div>
            </div> --}}
 
            {{-- DATOS DE PRESENTACION --}}
            {{-- <div class="card ml-2">
                <div class="card-header">
                    <div class="row d-flex">
                        <div class="col-8">
                            <h6 class="heading-small text-muted mb-1">PRESENTACION</h6>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('administracion.presentaciones.create') }}" class="btn btn-sm btn-info"
                                role="button"><i class="fas fa-plus fa-sm"></i>&nbsp;<span class="hide">presentación</span></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <label for="input-presentacion">Forma farmacéutica y presentación</label>
                            <select name="presentacion" id="input-presentacion"
                                class="selecion-presentacion form-control-alternative @error('presentacion') is-invalid @enderror">
                                <option data-placeholder="true"></option>
                                @foreach ($presentaciones as $presentacion)
                                    @if ($presentacion->id == old('presentacion'))
                                        <option value="{{ $presentacion->id }}" selected>
                                            @if ($presentacion->hospitalario)
                                                <b>H - </b>
                                            @endif
                                            {{ $presentacion->forma }}, {{ $presentacion->presentacion }}
                                            @if ($presentacion->trazabilidad)
                                                <b> - Trazable</b>
                                            @endif
                                        </option>
                                    @else
                                        <option value="{{ $presentacion->id }}">
                                            @if ($presentacion->hospitalario)
                                                <b>H - </b>
                                            @endif
                                            {{ $presentacion->forma }}, {{ $presentacion->presentacion }}
                                            @if ($presentacion->trazabilidad)
                                                <b> - Trazable</b>
                                            @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('presentacion')<div class="invalid-feedback">{{$message}}</div>@enderror
                        </div>
                    </div>
                </div>
            </div> --}}
        </div> 
        <div id="listitems" class="card mt-3">
            <div class="card-header">
                <div class="row d-flex">
                    <div class="col-8">
                        <h6 class="heading-small text-muted mb-1">PRODUCTOS</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">
                    <div class="form-row d-flex">
                        <table id="tabla2" class="table table-bordered table-responsive-md" width="100%">
                            <thead>
                                <th>Código de Proveedor</th>
                                <th>Droga</th>
                                <th>Forma/Presentacion</th>
                                <th>Costo</th>
                                <th>Quitar</th>
                            </thead>
                            <tbody>
                                <tr class="1eritem">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>  
                        </table>
                    </div>
                </div>
            </div>
            <div class="overlay"><i class="fas fa-ban text-gray"></i></div>           
        </div>
    </form>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        var nitem = 0;
        $(document).ready(function(){
            var bandera = 0;
            $('#additem').hide();
            $('#clearitem').hide();


            $("#input-proveedor").change(function() {
                if(bandera == 0){
                    $('#listitems .overlay').remove();
                    $('#additem').show();
                    $('#clearitem').show();
                    bandera++;
                }else{
                    for(var i = 0; i <= nitem; i++){
                        $("#row" + i).remove();
                    }
                    nitem = 0;
                }
                //$('#codigo').val('');
                //$('#precio_compra').val('');
                //$("#input-producto").prop("selectedIndex", 0).val(); 
                //$('#input-presentacion').prop("selectedIndex", 0).val();
            });
        });

        //$('#crearlista-btn').click(function(){
        //    for(var i = 0; i <= )
        //});

        /*$().ready(function() {
    	    $("#newitem").validate({
	    	    rules: {
        			codigo: { required: true, minlength: 2, maxlength: 15},
    	    		producto: { required:true},
		        	presentacion: { presentacion: 2},
	        	    precio_compra: { required:true, minlength: 2},
        		},
		        messages: {
        			codigo: "El campo es obligatorio.",
		        	producto : "El campo es obligatorio.",
        			presentacion : "El campo es obligatorio.",
		        	precio_compra : "El campo Costo es obligatorio y debe tener formato precio",
		        }
        	});
        });*/

        $("#additem").click(function(){
            if($('#codigo').val() != '' && $("#input-producto").val() != '' && $('#input-presentacion').val() != '' && $('#precio_compra').val() !=''){
                item = nitem;
                $("#tabla2 tbody").append("<tr id='row"+item+"'><td>"+$("#codigo").val()+"</td><td>"+$("#input-producto option:selected").html()+
                "</td><td>"+$("#input-presentacion option:selected").html()+"</td><td>"+$("#precio_compra").val()+"</td><td><a type='submit'"+
                "href='#' class='btn btn-sidebar btn-danger' onclick='removeline("+item+")'><i class='fas fa-minus'></i></a></td></tr>");
                nitem++;
            }
    	});

        function removeline(id){
            $("#row" + id).remove();
        }

        $("#crearlista-btn").click(function(){
            if(nitem > 0){
                for(var i = 0; i < nitem; i++){
                    $codprod = $("#row" + i + " td:eq(" + 0 + ")").text();
                    $prod = $("#row" + i + " td:eq(" + 1 + ")").text();
                    $idprod = $prod.split("-")[0];
                    $pres = $("#row" + i + " td:eq(" + 2 + ")").text();
                    $idpres = $pres.split("-")[0];
                    $cost = $("#row" + i + " td:eq(" + 3 + ")").text();
                    alert($codprod + " " + $idprod + " " + $idpres + " " + $cost);
                    var datos = { codigoProv: $codprod,
                        producto_id: $idprod,
                        presentacion_id: $idpres,
                        costo: $cost
                    };
                    $.ajax({
                        url: "{{ route('administracion.listaprecios.create') }}",
                        type: "POST",
                        data: datos,
                    }).done(function(resultado) {
                        $("#row" + i).hide();
                    });
                }
            }
        });

        
        new SlimSelect({
            select: '.selecion-proveedor',
            placeholder: 'Seleccione un proveedor de la lista',
        })

        new SlimSelect({
            select: '.selecion-presentacion',
            placeholder: 'Seleccione una presentación de la lista',
        })
        new SlimSelect({
            select: '.selecion-producto',
            placeholder: 'Seleccione una producto de la lista',
        })
    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
