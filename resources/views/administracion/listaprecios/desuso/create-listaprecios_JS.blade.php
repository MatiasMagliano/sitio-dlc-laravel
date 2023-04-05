<script>
    var nitem = 0;
    var codigosProv = [];
    var productosProv = [];
    var tabla2;

    $(document).ready(function(){

        window.CSRF_TOKEN = '{{ csrf_token() }}';
        var bandera = 0;
        $('#additem').hide();
        $('#clearitem').hide();
        $('#guardarlista-btn').prop('disabled', true);
     
        //Habilita botones para agregar o remover items 
        $("#input-proveedor").change(function() {
            if(bandera == 0){
                $('#listitems .overlay').remove();
                $('#additem').show();
                //$('#clearitem').show();
                bandera++;
            }else{
                for(var i = 0; i <= nitem; i++){
                    $("#row" + i).remove();
                }
                nitem = 0;
            }
        });
    });
////////////////////////// //////////////////////// ////////////
    //Valido registros ingresados para evitar error en insersión 
    $("#additem").click(function(){ 
        if ($('#codigo').val() != '' && $("#input-producto").val() != 0 && $('#input-presentacion').val() != 0 && $('#precio_compra').val() !='' && $('#precio_compra').val() > 0){
            validaRepetido($('#codigo').val(), $("#input-producto").val()+"|"+$('#input-presentacion').val(), $('#precio_compra').val());  
        }else{
            if($('#codigo').val() == ''){
                $('#message-codigo').removeClass('invalid-feedback');
            }else{
                $('#message-codigo').addClass('invalid-feedback');
            }
            if ($("#input-producto").val() == 0){
                $('#message-producto').removeClass('invalid-feedback');
            }else{
                $('#message-producto').addClass('invalid-feedback');
            }
            if ($('#input-presentacion').val() == 0){
                $('#message-presentacion').removeClass('invalid-feedback');
            }else{
                $('#message-presentacion').addClass('invalid-feedback');
            }
            if ($('#precio_compra').val() =='' || $('#precio_compra').val() < 0){
                $('#message-precio').removeClass('invalid-feedback');
            }else{
                $('#message-precio').addClass('invalid-feedback');
            }
        }   
    });

    //Valido que no se ingresen registros repetidos en la lista
    function validaRepetido(addCod, addProd, addPres) {
        nRegistros = codigosProv.length;
        var duplica = false;
        var duplicaCodigo = false;
        var duplicaProducto = false;

        if(nitem == 0){
            hideAlertValidation();
            addline(addCod, addProd, addPres);

        }else{
            for(var c = 0; c < nRegistros; c++){
                if (addCod == codigosProv[c] && addProd == productosProv[c] ){
                    duplica = true;
                }else if(addCod == codigosProv[c]){
                    duplicaCodigo = true;
                }else if (addProd == productosProv[c]){
                    duplicaProducto = true;
                }
            }
            if (duplica == false && duplicaProducto == false && duplicaCodigo == false){
                hideAlertValidation();
                addline(addCod, addProd, addPres);
                
            }else if (duplica == true){
                msjAdvertencia('El producto', 'Registro');
            }else if (duplicaCodigo == true){
                msjAdvertencia('El código de proveedor', 'Código de proveedor');
            }else if (duplicaProducto == true){
                msjAdvertencia('El producto', 'Producto ');
            }
        }
    }
    function msjAdvertencia(mensaje, titulo){
        event.preventDefault();
        let advertencia = '<p>' + mensaje + ' que intenta cargar ya fue ingresado recientemente, por favor controle e intente nuevamente</p>';
        Swal.fire({ 
            icon: 'warning',
            title: titulo + ' duplicado',
            html: advertencia,
            confirmButtonText: 'Controlar',
            //showCancelButton: true,
        });
    }

    //Agrego registro al final de la tabla, modifico el contador de filas
    function addline(addCod, addProd, addPres) {
        $('#guardarlista-btn').prop('disabled', false);
        item = nitem;

        codigosProv.push(addCod);
        productosProv.push(addProd);
        $("#tabla2 tbody").append("<tr id='row"+item+"'><td>"+$("#codigo").val()+"</td><td>"+$("#input-producto option:selected").html()+
            "</td><td>"+$("#input-presentacion option:selected").html()+"</td><td>"+addPres+"</td><td><a type='submit'"+
            "href='#' class='btn btn-link text-danger' onclick='removeline("+item+")'><i class='fas fa-trash-alt'></i></a></td></tr>");
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
        if (nitem == 0) {
            $('#guardarlista-btn').prop('disabled', true);
        }
    }

    //Envío por ajax la tabla para insertar en la base de datos
    $("#guardarlista-btn").click(function(){
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
                }).done(function(response) {
                    Swal.fire({
                        title: 'Agregar Listado',
                        icon: response.alert,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    //debugger;
                    window.location.replace('{{ route('administracion.listaprecios.index') }}');
                });
            }
        }
    });

    function hideAlertValidation(){
        $('#message-codigo').addClass('invalid-feedback');
        $('#message-producto').addClass('invalid-feedback');
        $('#message-presentacion').addClass('invalid-feedback');
        $('#message-precio').addClass('invalid-feedback');
    }
    
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