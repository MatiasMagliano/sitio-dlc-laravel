@extends('adminlte::page')
@section('css')
    <style>
        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after {
            content: "" !important;
        }

        #tabla1.dataTable thead th {border-bottom: none;}

        #tabla1.dataTable tfoot th {
            border-top: none;
            border-bottom: 1px solid #111;
        }

        #tabla1 tbody tr td a{
            text-decoration: none;
            color:#333;
        }

        #tabla2.dataTable{overflow: auto;}

        .search input[type=text] {
            font-size: 17px;
            border: none;
            outline: none;
        }
        .search input[type=text] {
            font-size: 17px;
            border: none;
            outline: rgba(0,0,0,0);
        }

        .search button {
            background: #fff;
            font-size: 17px;
            border: none;
            opacity: 0.4;
        }
        .hide{display:none}


    </style>
@endsection

@section('title', 'Administrar Productos')

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Listado de Precios</h1>
        </div>
        <div class="col-md-4 d-flex justify-content-md-end">
            <a href="" role="button" class="btn btn-md btn-success" data-toggle="modal" data-target="#agregarregistro">Crear Lista de Precios</a>
        </div>
    </div>

    <div class="modal fade" id="agregarregistro" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Alta de registro<span id="nombrePresentacion"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <div id="tipoalta" class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" 
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span>Tipo de Alta</span>
                        </button>
                        <div id="dropdown-menu-button" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a class="dropdown-item" href="#"><span>Listado de Productos de Proveedor</span></a>
                          <a class="dropdown-item" href="#"><span>Producto de Poveedores</span></a>
                        </div>
                        


                        <div id="form_add" style="margin-top: 0.5rem">
                            <div id="opt1" style="float:left" >
                                
                                    <label for="search-input">Seleccione el proveedor: </label>
                                    <span class="algolia-autocomplete algolia-autocomplete-left" style="position: relative; display: inline-block; direction: ltr;">
                                        <input type="search" class="form-control ds-input" id="search-input" placeholder="Buscar proveedor..." autocomplete="off" spellcheck="false" role="combobox" 
                                            list="lista_prov" name="search-rs" style="position: relative; vertical-align: top;" dir="auto">
                                        <datalist id="lista_prov">
                                            @foreach ($proveedors as $proveedor)
                                            <option value="{{ $proveedor->razon_social }}"></option>
                                            @endforeach
                                        </datalist>
                                    </span>
                                    <a id="getlistad" href="{{ route('administracion.listaprecios.exportlist') }}" class="btn btn-warning" type="submit">Exportar planilla</a> 
                                    <label for="">Adjuntar Listado: </label>
                            </div>
                            <div id="opt2">2</div> 
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@stop

{{-- aquí va contenido --}}
@section('content')
<div class="card-group mb-12">
    <div class= "col-md-3">
        <div class="card" >
            <div class="card-header" >
                <div id="nav_search" class="search">
                    <input id="searchbar" type="text" placeholder="Seleccionar Proveedor">
                    <button type="submit"><i class="fas fa-search "></i></button>
                </div>
            </div>
            <div class="card-body" >
                <div class="card-body" style="height: 61.55vh;overflow-y: scroll">
                    <table id="tabla1" class="tabla1 " style="cursor:pointer" >
                        <tbody>
                            @foreach ($proveedors as $proveedor)
                                <tr id="{{ $proveedor->id}}" value="{{ $proveedor->razon_social }}">
                                    <td>{{ $proveedor->razon_social }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card " id="ListadoDePrecios">
                {{-- <a class="btn btn-success mt-3 mb-3" href="{{ route('ListaPrecio.excel')}}">
            Exportar</a>
            <form action="{{route('ListaPRecios.import.excel')}}" method="post" enctype="multipart/form-data">
               @csrf
                @if(Session::has('message'))
                <p>{{Session::has('message')}}</p>
                @endif
                <input type="file" name="file">
                <button>Importar Listado</button>
            </form> --}}
            <div class="card-header">
                <h3 class="card-title" id="tituloListadoDePrecios" >
                    Lista de Precios de
                </h3>
                <Form action="" method="post">
                    <button class="">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button class="">
                        <i class="fas fa-file-upload"></i>
                    </button>
                </Form>
            </div>
            <div class="card-body">
                <table id="tabla2" class="display nowrap" style="cursor:pointer">
                    <thead>
                        <th>Cód. Proveedor</th>
                        <th>Producto</th>
                        <th>Presentacion</th>
                        <th>Forma</th>
                        <th>Costo</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>*</td>
                            <td>*</td>
                            <td>*</td>
                            <td>*</td>
                            <td>*</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="overlay"><i class="fas fa-ban text-gray"></i>
        </div>
    </div>
</div>
</div>

@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/localization/methods_pt.js"></script>
    <script>


         $(document).ready(function() {            
            // VARIABLES LOCALES
            var tabla2;

            $('#tabla1 tbody tr').removeClass('selected');
            $razon_social = 'no';
            // Tabla 2
            tabla2 = $('#tabla2').DataTable({
                "scrollY": "57vh",
                "scrollCollapse": true,
                "processing": true,
                "order": [1, "asc"],
                "paging": false,
                "info": false,
                "searching": false,
                "select": false,
                "columns": [
                    {targets: [0], data: 'codigoProv',},
                    {targets: [1], data: 'droga'},
                    {targets: [2], data: 'presentacion'},
                    {targets: [2], data: 'forma'},
                    {targets: [3], data: 'costo'}],
            });

            //Filtrar Proveedor
            $('#searchbar').on('keyup', function(){
                var filter = $('#searchbar').val().toUpperCase();

               $('#tabla1 tbody tr').each(function(){
                   
                    $(this).children("td").each(function(){
                        var tdText = $(this).text().toUpperCase();

                        if(tdText.indexOf(filter) < 0){
                            $(this).addClass("sin");
                        }else{
                            $(this).removeClass("sin");
                        }
                   });

            //Cantidad de <td> en el <tr> seleccionado
                    nTds = $(this).children("td").length
                    
                    nTdsSin = $(this).children(".sin").length
                    
                    if(nTdsSin == nTds){
                        $(this).hide();
                    }else{
                        $(this).show();
                    }
                });         
            });

            //Tomar id Proveedor seleccionado
            $('#tabla1 tbody tr').on('click', function(){
                if($(this).hasClass('selected')){
                    $(this).removeClass('selected');
                }

                $('#ListadoDePrecios .overlay').remove();
                var idSupplier = $(this).attr("id");
                var Supplier = $(this).attr("value");
                
                $('#tituloListadoDePrecios').text('Lotes vigentes para ' + Supplier);     
                
                getListadoProveedor(idSupplier);
            });

            // Llena Tabla2
            function getListadoProveedor(idSupplier) {
                var datos = { proveedor_id: idSupplier};
                $.ajax({
                    url: "{{ route('administracion.listaprecios.actualizarLista') }}",
                    type: "GET",
                    data: datos,
               }).done(function(resultado) {
                    tabla2.clear();
                    tabla2.rows.add(resultado).draw();
               });
            };

            //Abre formulario de Alta
            $('#agregarregistro').on('show.bs.modal', function(event){
                $('#tipoalta button span').text('Tipo de Alta');
                //Se coloca el título del modal
                $('#nombrePresentacion').empty();
                $('#nombrePresentacion').append(event.relatedTarget.value);
                $('#form_add div').hide();
                $('#desc-planilla').hide();
            });

            //Toma Tipo de Alta
            $('#dropdown-menu-button a').on('click', function(){

                var newliporv = 0;

                $('#form_add div').hide();
                var textTipo = $(this).text();

                $('#tipoalta button span').text(textTipo);
                if(textTipo == 'Listado de Productos de Proveedor'){
                    $('#opt1').show();
                    $('#desc-planilla').show();
                }
                if(textTipo == 'Producto de Poveedores'){
                    $('#opt2').show();
                }

            });


            // Exporta listado
            // $(document).on('click', '#getlistad', function(){ 
            //     $RS = $('#search-input').val();
            //     $loc = "{{ route('administracion.listaprecios.exportlist') }}";
            //     window.location=$loc;
            // });

            

            // //function listadoexport(rs) {
            //     $('#getlistadd').on('click', function(){
            //         var rs = $('#search-input').val();
            //         var datos = { razonsocial: rs};
            //         $.ajax({
            //             url: "{{ route('administracion.listaprecios.exportlist') }}",
            //             type: "GET",
            //             data: datos
            //         }).done(function(resultado) {
            //             alert("Listado exportado exitosamente");
            //    });
            //     });
            // };


            //Descarga listado de proveedor
            // $('#getlistado').on('click', function(){
                
            //     pj = $('#search-input').val();

            //     var datos = {razon_social: pj};
            //     $.ajax({
            //         url: "{{ route('administracion.listaprecios.exportlist') }}",
            //         type: "GET",
            //         data: datos,
            //    }).done(function(resultado) {
            //        alert(pj);
            //        alert('Error en exportación de planillacon');
            //    });         
            // });


            // Quita un item de la lista de precios del proveedor
            




        });

    </script>
@endsection

@section('footer')
    <strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
    <div class="float-right d-none d-sm-inline-block">
        <b>Versión</b> 2.0 (LARAVEL V.8)
    </div>
@endsection
