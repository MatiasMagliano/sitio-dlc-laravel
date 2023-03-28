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

    $(document).ready(function() {
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