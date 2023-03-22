<script>
    function borrarListado(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Borrar Listado',
            text: 'Esta acción borrará todos los prodcutos del proveedor.',
            confirmButtonText: 'Borrar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                debugger;
                $('#borrar-' + id).submit();
                window.location.replace('{{ route('administracion.listaprecios.index') }}');
            }else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Operación cancelada por usuario, no se borra el listado de proveedor',
                    'error'
                )
            }
        });
    };

    $("#ListasSinProductos").on('click', function() {
        $(location).attr('href', 'listaprecios/create')
    });

    (function () {
    'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()

    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "{{route('administracion.listaprecios.listasVacias')}}",
            success: function(data){
                if (data.message == 0){
                    $('#ListasSinProductos').addClass('LockCreate');
                }else{
                    $('#ListasSinProductos').removeClass('LockCreate');
                }
            },
        });

        // VARIABLES LOCALES
        var tabla;
        tabla = $('#tabla').DataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "select": false,
        });
    });

    
    // SCRIPT DEL SLIMSELECT
    /*var selProducto = new SlimSelect({
        select: '.seleccion-producto',
        placeholder: 'Seleccione el nombre de la droga y luego su presentación...',
    });*/
</script>