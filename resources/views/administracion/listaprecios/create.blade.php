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
        .error{
            font-size: 12px;
            color: red;
            text-align: center;
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
                        class="selecion-proveedor form-control-alternative" autocomplete="off">
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
                                <td id="td-codigo">
                                    <input type="text" name="codigo" id="codigo" maxlength="15"  style="height: 30px"  class="form-control" autocomplete="off">
                                    <p class="error">Este campo es necesario</p>
                                </td>

                                <td id="td-producto">
                                    <select name="producto" id="input-producto" class="selecion-producto form-control-alternative" autocomplete="off">
                                        <option data-placeholder="true" value="0"></option>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}">{{ $producto->id }}- {{ $producto->droga }}</option>
                                        @endforeach
                                    </select>
                                    <p class="error">Este campo es necesario</p>
                                </td>

                                <td id="td-presentacion">
                                    <select name="presentacion" id="input-presentacion" class="selecion-presentacion form-control-alternative" autocomplete="off">
                                    <option data-placeholder="true" value="0"></option>
                                        @foreach ($presentaciones as $presentacion)
                                            @if ($presentacion->id == old('presentacion'))
                                                <option value="{{ $presentacion->id }}" selected>{{ $presentacion->id }}- {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                                            @else
                                                <option value="{{ $presentacion->id }}">{{ $presentacion->id }}- {{ $presentacion->forma }}, {{ $presentacion->presentacion }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <p class="error">Este campo es necesario</p>
                                </td>

                                <td id="td-precio">
                                    <input type="number" name="precio_compra" id="precio_compra" style="height: 30px" class="form-control" autocomplete="off">
                                    <p class="error">Este campo es necesario y debe tener formato de precio</p>
                                </td>

                                <td>
                                    <a id="additem" type="button" href="#" class="btn btn-sidebar btn-primary"><i class="fas fa-plus"></i></a>
                                    <a id="clearitem" type="button" href="#" class="btn btn-sidebar btn-danger"><i class="fas fa-eraser"></i></a>
                                </td>
                            </tr>
                        </tbody>  
                    </table>
                </div>

            </div>
        </div>
    </div>


    <div class="card-group">
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
                    
                    <form action="{{ route('administracion.listaprecios.create') }}" method="POST" class="needs-validation" autocomplete="off" novalidate>
                        @csrf
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
                    </form>

                </div>
            </div>
            <div class="overlay"><i class="fas fa-ban text-gray"></i></div>           
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
    <script>
        let nitem = 0;
        var codigosProv = [];
        var productosProv = [];

        $(document).ready(function(){

            window.CSRF_TOKEN = '{{ csrf_token() }}';
            var bandera = 0;
            $('#additem').hide();
            $('#clearitem').hide();
            $('.error').hide();

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
            });
        });

        //Valido registros ingresados para evitar error en insersión 
        $("#additem").click(function(){ 
            if ($('#codigo').val() != '' && $("#input-producto").val() != '' && $('#input-presentacion').val() != '' && $('#precio_compra').val() !=''){
                if(nitem == 0){
                    addregistro();
                }else{
                    var prodProv = $("#input-producto").val() + "|" + $('#input-presentacion').val();
                    var duplica = false;
                    var duplicaCodigo = false;
                    var duplicaMercaderia = false;
                    cantCod = codigosProv.length;
                    for(var c = 0; c < cantCod; c++){
                        if ($('#codigo').val() == codigosProv[c] && prodProv == productosProv[c] ){
                            duplica = true;
                        }else if($('#codigo').val() == codigosProv[c] && prodProv != productosProv[c]){
                            duplicaCodigo = true;
                        }else if ($('#codigo').val() != codigosProv[c] && prodProv != productosProv[c]){
                            duplicaMercaderia = true;
                        }
                    }
                    if (duplica == false && duplicaMercaderia == false && duplicaCodigo == false){
                        addregistro();
                    }else if (duplica == true){
                        event.preventDefault();
                        let advertencia = '<p>El producto que intenta cargar ya fue ingresado recientemente, por favor controle e intente nuevamente</p>';
                        Swal.fire({
                                    icon: 'warning',
                                    title: 'Producto duplicado',
                                    html: advertencia,
                                    confirmButtonText: 'Controlar',
                                    showCancelButton: true,
                                });
                    }else if (duplicaCodigo == true){
                        event.preventDefault();
                        let advertencia = '<p>El código de proveedor que intenta cargar ya fue ingresado en otro producto recientemente, por favor controle e intente nuevamente</p>';
                        Swal.fire({
                                    icon: 'warning',
                                    title: 'Código de proveedor duplicado',
                                    html: advertencia,
                                    confirmButtonText: 'Controlar',
                                    showCancelButton: true,
                                });
                    }else if (duplicaMercaderia == true){
                        event.preventDefault();
                        let advertencia = '<p>El producto y presentación que intenta cargar ya fueron ingresados recientemente, por favor controle e intente nuevamente</p>';
                        Swal.fire({
                                    icon: 'warning',
                                    title: 'Producto duplicado',
                                    html: advertencia,
                                    confirmButtonText: 'Controlar',
                                    showCancelButton: true,
                                });
                    }
                }  
            }else{
                if($('#codigo').val() == ''){
                    $('#td-codigo p').show();
                }else{
                    $('#td-codigo p').hide();
                }
                if ($("#input-producto").val() == ''){
                    $('#td-producto p').show();
                }else{
                    $('#td-producto p').hide();
                }
                if ($('#input-presentacion').val() == ''){
                    $('#td-presentacion p').show();
                }else{
                    $('#td-presentacion p').hide();
                }
                if ($('#precio_compra').val() ==''){
                    $('#td-precio p').show();
                }else{
                    $('#td-precio p').hide();
                }
            }   
    	});

        //Agrego registro al final de la tabla, modifico el contador de filas
        function addregistro() {
            item = nitem;

            codigosProv.push($('#codigo').val());
            productosProv.push($("#input-producto").val() + "|" + $('#input-presentacion').val());
            $("#tabla2 tbody").append("<tr id='row"+item+"'><td>"+$("#codigo").val()+"</td><td>"+$("#input-producto option:selected").html()+
                "</td><td>"+$("#input-presentacion option:selected").html()+"</td><td>"+$("#precio_compra").val()+"</td><td><a type='submit'"+
                "href='#' class='btn btn-sidebar btn-danger' onclick='removeline("+item+")'><i class='fas fa-minus'></i></a></td></tr>");
            nitem++;
        }

        //Remuevo registro y reacomodo la tabla, modifico el contador de filas
        function removeline(id){
            
            var nfila = id;
            for(var r = id; r < nitem; r++){
                nfila = r + 1;
                if(r < nitem - 1){
                    $("#row" + r).children().remove();
                    $("#row" + r).append($("#row" + nfila).children());
                    $("#row" + r + " a").attr("onclick","removeline("+r+")");
                    $("#row" + nfila).children().remove();
                    // Actualizo vectores
                    codigosProv[r] = codigosProv[nfila];
                    productosProv[r] = productosProv[nfila];
                }else{
                    $("#row" + r).remove(); 
                    codigosProv.pop();
                    productosProv.pop();
                }
            }
            nitem = nitem - 1;  
        }

        //Envío por ajax la tabla para insertar en la base de datos
        $("#crearlista-btn").click(function(){
            if(nitem > 0){
                for(var i = 0; i < nitem; i++){
                    $idprov = $("#input-proveedor").val();
                    $codprod = $("#row" + i + " td:eq(" + 0 + ")").text();
                    $idprod = $("#row" + i + " td:eq(" + 1 + ")").text().split("-")[0];
                    $idpres = $("#row" + i + " td:eq(" + 2 + ")").text().split("-")[0];
                    $cost = $("#row" + i + " td:eq(" + 3 + ")").text();

                    $micadena = $idprov + "|" + $codprod + "|" + $idprod + "|" + $idpres + "|" + $cost;
                    var datos = { cadena: $micadena};
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': window.CSRF_TOKEN = '{{ csrf_token() }} '},
                        url: "{{ route('administracion.listaprecios.create') }}",
                        type: "POST",
                        data: datos,
                    }).done(function(resultado) {
                        $("#row" + i).remove();
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
