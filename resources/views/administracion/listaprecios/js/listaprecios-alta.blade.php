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

    //AGREGAR PRODUCTO - SUBMIT DEL FORMULARIO
    $(document).on('submit','#formAgregProveedor',function(event){

        event.preventDefault();
        //hideAlertValidation();

        //if ($('#input-ncosto')[0].valueAsNumber > 0 && $('#input-ncosto')[0].valueAsNumber != "" && $('#input-ncodigoProv')[0].value > '0' &&  $('#input-ncodigoProv')[0].value != "") {
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
                        title: 'Agregar producto',
                        icon: response.alert,
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
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
        /*}else{
            if( $('#input-ncosto')[0].valueAsNumber == 0 || isNaN($('#input-ncosto')[0].valueAsNumber)) {
                $('#input-ncosto-feedback').removeClass('invalid-feedback');
            }
            if( $('#input-ncodigoProv')[0].value == 0 || (isNaN($('#input-ncodigoProv')[0].value) && $('#input-ncodigoProv')[0].value.length == 0)) {
                $('#input-ncodigoProv-feedback').removeClass('invalid-feedback');
            }
        }*/
    });


    $(document).ready(function() {
        $RS = '';
        $('#input-afip').inputmask("9{2}-9{8}-9{1}");
        $('#input-telefono').inputmask({
            "mask": "09{3}-9{7}"
        });
    });

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