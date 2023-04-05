<script>
    $(document).ready(function() {
        hideAlertValidation();
        $("#input-ncodigoProv").on('input', function (evt) {$(this).val($(this).val().replace(/[^0-9]/g, ''));});
    });


    var frs = true;
    $(document).ready(function() {
        frs = true;
        var tablaProductos = $('#tablaProductos').DataTable( {
            dom: "tp",
            pageLength: 10,
            scrollY: "35vh",
            scrollCollapse: true,
            order: [1, 'asc'],
            columnDefs: [
                {
                    targets: [0],
                    className: "align-middle text-center",
                },
                {
                    targets: [4],
                    className: "align-middle text-center",
                    'render': function(data) {
                        return moment(new Date(data)).format("DD/MM/YYYY");
                    },
                },
                {
                    targets: [3],
                    className: "align-middle text-center",
                },
            ],
        });
    });

    //AGREGAR PRODUCTO - MODAL
    $(document).on('click', '.open_first_modal', function(){
        //ndiv = $(".opSelected").remove();


        hideAlertValidation();
        $.ajax({
            type: "GET",
            url: "{{route('administracion.listaprecios.editar.traerDataAgregarProductoLista')}}",
            success: function(data){
                console.log(data);
                var rProducto = data.dataResponse[0];

                if(frs){
                    for(var i = 0; i < rProducto.nombre.dataProductos.length; i++){
                        $(".seleccion-producto").append("<option value='"+ rProducto.nombre.dataProductos[i].productoId +"' class='opSelected' selected='selected'>"+ rProducto.nombre.dataProductos[i].droga +"</option>");
                    }
                    var selProducto = new SlimSelect({
                        select: '.seleccion-producto',
                        placeholder: 'Seleccione el nombre de la droga...',
                    });

                    for(var i = 0; i < rProducto.detalle.dataPresentaciones.length; i++){
                        $(".seleccion-presentacion").append("<option value='"+ rProducto.detalle.dataPresentaciones[i].presentacionId +"' class='opSelected' selected='selected'>"+ rProducto.detalle.dataPresentaciones[i].presentacion +"</option>");
                    }
                    frs = false;
                }
                var selPresentacion = new SlimSelect({
                    select: '.seleccion-presentacion',
                    placeholder: 'Seleccione la presentación de la droga...',
                });
            },
        });
    });
    //AGREGAR PRODUCTO - SUBMIT DEL FORMULARIO
    $(document).on('submit','#formAgregProducto',function(event){

        event.preventDefault();
        hideAlertValidation();

        if ($('#input-ncosto')[0].valueAsNumber > 0 && $('#input-ncosto')[0].valueAsNumber != "" && $('#input-ncodigoProv')[0].value > '0' &&  $('#input-ncodigoProv')[0].value != "") {
            $.ajax({
                url: '{{route('administracion.listaprecios.editar.ingresarProductoLista')}}',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    if(response.alert == "success"){
                        Swal.fire({
                            title: 'Agregar producto',
                            icon: response.alert,
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });

                        location.reload();
                    }else{
                        Swal.fire({
                            title: 'Agregar producto',
                            icon: response.alert,
                            text: response.message,
                            showConfirmButton: false,
                        });
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    errores = '';
                    $.each( errors, function( key, value ) {
                        errores += value;
                    });
                    Swal.fire({
                        icon: 'error',
                        text: errores,
                        showConfirmButton: true,
                        timer: 1500
                    });
                }
            });

        }else{
            if( $('#input-ncosto')[0].valueAsNumber == 0 || isNaN($('#input-ncosto')[0].valueAsNumber)) {
                $('#input-ncosto-feedback').removeClass('invalid-feedback');
            }
            if( $('#input-ncodigoProv')[0].value == 0 || (isNaN($('#input-ncodigoProv')[0].value) && $('#input-ncodigoProv')[0].value.length == 0)) {
                $('#input-ncodigoProv-feedback').removeClass('invalid-feedback');
            }
        }
    });

    //BORRAR PRODUCTO
    function borrarItemListado(item) {
        var rs = document.getElementById('borrar-' + item).name;

        Swal.fire({
            icon: 'warning',
            title: 'Borrar Producto de Listado',
            text: 'Esta acción quitará el producto del listado del proveedor.',
            confirmButtonText: 'Borrar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#borrar-' + item).submit();
                sleep(20);
                window.location.replace('{{ route('administracion.listaprecios.editar','rs') }}');
            }else if (
                result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelado',
                    'Operación cancelada por usuario, no se quita el producto listado de proveedor',
                    'error'
                )
            }
        });
    };

    //MODIFICAR PRODUCTO - MODAL
    $(document).on('click', '.open_modal', function(){
        hideAlertValidation();
        $.ajax({
            type: "GET",
            url: "{{route('administracion.listaprecios.editar.traerDataModificarProductoLista')}}",
            data: {producto: $(this).val()},
            success: function(data){
                $('#input-droga').val(
                    data.producto.droga +
                    " - " +
                    data.presentacion.forma +
                    ", " +
                    data.presentacion.presentacion
                    );
                    $('#input-listaId').val(data.producto_listaPrecio.id);
                    $('#input-codigoProv').val(data.producto_listaPrecio.codigoProv);
                    $('#input-costo').val(data.producto_listaPrecio.costo);
                },
            });
        });
    //MODIFICAR PRODUCTO - SUBMIT DEL FORMULARIO
    $(document).on('submit','#formModifProducto',function(event){

        event.preventDefault();
        hideAlertValidation();
        if ($('#input-costo')[0].valueAsNumber > 0 && $('#input-costo')[0].valueAsNumber != "" && $('#input-codigoProv')[0].value > '0' &&  $('#input-codigoProv')[0].value != "") {
            $.ajax({
                url: '{{route('administracion.listaprecios.editar.actualizarProductoLista')}}',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    Swal.fire({
                        title: 'Modificar producto',
                        icon: 'success',
                        text: response.success,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.reload();
                },
                error: function(response) {
                    var errors = response.responseJSON;
                    errores = '';
                    $.each( errors, function( key, value ) {
                        errores += value;
                    });
                    Swal.fire({
                        icon: 'error',
                        text: errores,
                        showConfirmButton: true,
                    });
                }

            });
        }else{
            if( $('#input-costo')[0].valueAsNumber == 0 || isNaN($('#input-costo')[0].valueAsNumber)) {
                $('#input-costo-feedback').removeClass('invalid-feedback');
            }
            /*if( $('#input-codigoProv')[0].value == 0 || (isNaN($('#input-codigoProv')[0].value) && $('#input-codigoProv')[0].value.length == 0)) {
                $('#input-codigoProv-feedback').removeClass('invalid-feedback');
            }*/
        }
    });

    function hideAlertValidation(){
        $('#input-codigoProv-feedback').addClass('invalid-feedback');
        $('#input-costo-feedback').addClass('invalid-feedback');
        $('#input-ncodigoProv-feedback').addClass('invalid-feedback');
        $('#input-ncosto-feedback').addClass('invalid-feedback');
    }

</script>
