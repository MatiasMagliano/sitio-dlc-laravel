<script>
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
                        return moment(new Date(data)).format("DD-MM-YYYY HH:mm:ss");
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
        $('#input-ncodigoProv').val('');
        $('#input-ncosto').val('');
        $('#invalid-feedback-ncodigoProv p').remove();
        $('#input-ncodigoProv').removeClass('is-invalid');
        $('#invalid-feedback-ncosto p').remove();
        $('#input-ncosto').removeClass('is-invalid');
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

        var validado = true;

        //Valor de campo Codigo de Proveedor
        $('#invalid-feedback-ncodigoProv p').remove();
        $('#input-ncodigoProv').removeClass('is-invalid');
        if ( $('#input-ncodigoProv').val() == '' || $('#input-ncodigoProv').val() == null || $('#input-ncodigoProv').val() == undefined){
            validado = false;
            $('#input-ncodigoProv').addClass('is-invalid'); 
            $('#invalid-feedback-ncodigoProv').append('<p>El campo codigoprov es incorrecto</p>'); 
        }

        //Valor de campo Costo
        $('#invalid-feedback-ncosto p').remove();
        $('#input-ncosto').removeClass('is-invalid');
        if ( $.isNumeric($('#input-ncosto').val()) == false || $('#input-ncosto').val() <= 0){
            validado = false;
            $('#input-ncosto').addClass('is-invalid'); 
            $('#invalid-feedback-ncosto').append('<p>El campo costo es incorrecto</p>'); 
        }

        if (validado){
            this.submit();
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
        $('#invalid-feedback-costo p').remove();
        $('#input-costo').removeClass('is-invalid');
        $.ajax({
            type: "GET",
            url: "{{route('administracion.listaprecios.editar.traerDataModificarProductoLista')}}",
            data: {producto: $(this).val()},
            success: function(data){
                console.log(data);
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
        var validado = true;

        //Valor de campo Costo
        $('#invalid-feedback-costo p').remove();
        $('#input-costo').removeClass('is-invalid');
        if ( $.isNumeric($('#input-costo').val()) == false || $('#input-costo').val() <= 0){
            validado = false;
            $('#input-costo').addClass('is-invalid'); 
            $('#invalid-feedback-costo').append('<p>El campo costo es incorrecto</p>'); 
        }

        if (validado){
            this.submit();
            /*$.ajax({
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

            });*/
        }
    });

</script>
