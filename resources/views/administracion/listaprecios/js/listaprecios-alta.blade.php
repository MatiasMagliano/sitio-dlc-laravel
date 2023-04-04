<script>
    function getLocalidades(provinciaSeleccionada) {
        let datos = {
            provincia_id: provinciaSeleccionada.value,
        };

        $.ajax({
            url: "{{ route('administracion.clientes.ajax.obtenerLocalidades') }}",
            type: "GET",
            data: datos,
        }).done(function(resultado) {
            localidad.setData(resultado);
        });
    }

    //AGREGAR PROVEEDOR - SUBMIT DEL FORMULARIO
    $(document).on('submit','#formAgregProveedor',function(event){

        event.preventDefault();
        hideAlertValidation();
        if ($('#input-razon_social')[0].value != "" && $('#input-cuit')[0].value != "" && $('#input-email')[0].value != "" && $('#input-web')[0].value != "" &&  $('#input-domicilio')[0].value != "" && $('#input-provincia')[0].value != "" && $('#input-localidad')[0].value != "") {
            
            hideAlertValidation();
            $.ajax({
                url: '{{route('administracion.listaprecios.alta.nuevoListadoPrecioProveedor')}}',
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    $razon_social = response.message;
                    Swal.fire({
                        title: 'Agregando Listado de proveedor',
                        icon: response.alert,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.replace('{{ route('administracion.listaprecios.index') }}');
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
            if($('#input-razon_social')[0].value == "") {
                $('#input-razon_social-feedback').removeClass('invalid-feedback');
            }
            if($('#input-cuit')[0].value == "") {
                $('#input-cuit-feedback').removeClass('invalid-feedback');
            }
            if($('#input-razon_social')[0].value == "") {
                $('#input-razon_social-feedback').removeClass('invalid-feedback');
            }
            if($('#input-cuit')[0].value == "") {
                $('#input-cuit-feedback').removeClass('invalid-feedback');
            }
            if($('#input-email')[0].value == "") {
                $('#input-email-feedback').removeClass('invalid-feedback');
            }
            if($('#input-web')[0].value == "") {
                $('#input-web-feedback').removeClass('invalid-feedback');
            }
            if($('#input-domicilio')[0].value == "") {
                $('#input-domicilio-feedback').removeClass('invalid-feedback');
            }
            if($('#input-provincia')[0].value == "") {
                $('#input-provincia-feedback').removeClass('invalid-feedback');
            }
            if($('#input-localidad')[0].value == "") {
                $('#input-localidad-feedback').removeClass('invalid-feedback');
            }
            
            /*if( $('#input-ncosto')[0].valueAsNumber == 0 || isNaN($('#input-ncosto')[0].valueAsNumber)) {
                $('#input-ncosto-feedback').removeClass('invalid-feedback');
            }
            if( $('#input-ncodigoProv')[0].value == 0 || (isNaN($('#input-ncodigoProv')[0].value) && $('#input-ncodigoProv')[0].value.length == 0)) {
                $('#input-ncodigoProv-feedback').removeClass('invalid-feedback');
            }*/
        }
    });

    $(document).ready(function() {
        hideAlertValidation();
        $RS = '';
        $('#input-cuit').inputmask("9{2}-9{8}-9{1}");
        $('#input-telefono').inputmask({
            "mask": "09{3}-9{7}"
        });
    });

    function hideAlertValidation(){
        $('#input-razon_social-feedback').addClass('invalid-feedback');
        $('#input-cuit-feedback').addClass('invalid-feedback');
        $('#input-email-feedback').addClass('invalid-feedback');
        $('#input-web-feedback').addClass('invalid-feedback');
        $('#input-domicilio-feedback').addClass('invalid-feedback');
        $('#input-provincia-feedback').addClass('invalid-feedback');
        $('#input-domicilio-feedback').addClass('invalid-feedback');provincia

    }

    var provincia = new SlimSelect({
        select: '.selector-provincia',
        placeholder: 'Seleccione una provincia',
        onChange: (info) => {
            getLocalidades(info);
        }
    });
    var localidad = new SlimSelect({
        select: '.selector-localidad',
        placeholder: 'Seleccione una localidad',
    });
</script>