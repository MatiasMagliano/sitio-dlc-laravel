<script>
    function borrarListado(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Borrar Listado',
            text: 'Esta acción borrará todos los prodcutos del proveedor.',
            confirmButtonText: 'Borrar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#borrar-' + id).submit();
                sleep(20);
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

    /*$("#NuevoListado").on('click', function() {
        $(location).attr('href', 'listaprecios/agregar')
    });*/


    /*$("#ListasSinProductos").on('click', function() {
        $(location).attr('href', 'listaprecios/create')
    });*/

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


        $('#tabla tfoot th').slice(0, 3).each(function(i) {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar" />');
        });
        /*$.ajax({
            type: "GET",
            url: "{{--route('administracion.listaprecios.listasVacias')--}}",
            success: function(data){
                if (data.message == 0){
                    $('#ListasSinProductos').addClass('LockCreate');
                }else{
                    $('#ListasSinProductos').removeClass('LockCreate');
                }
            },
        });*/

        // VARIABLES LOCALES
        var tabla;
        /*tabla = $('#tabla').DataTable({
            "paging": false,
            "info": false,
            "searching": false,
            "select": false,
        });*/

        var tabla = $('#tabla').dataTable({
            orderCellsTop: true,
            fixedHeader: true,
            dom: "rltip",
            /*"processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{-- route('administracion.cotizaciones.ajax') --}}",
                method: "GET"
            },
            "order": [0, 'desc'],*/
            columnDefs: [
                {
                    targets: [1],
                    className: "align-middle text-center",
                    'render': function(data) {
                        return moment(new Date(data)).format("DD/MM/YYYY");
                    },
                },
                {
                    targets: [2],
                    className: "align-middle text-center",
                    'render': function(data) {
                        return moment(new Date(data)).format("DD/MM/YYYY");
                    },
                },
            ],
            "initComplete": function() {
                this.api()
                    .columns([0, 1, 2])
                    .every(function() {
                        var that = this;

                        $('input', this.footer()).on('keyup change clear', function() {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
            },
        });

        /*$('#tabla tfoot th').slice(0, 3).each(function() {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar" />');

            $('input', this).on('keyup change', function(){
                if(tabla.column(i).search() !== this.value){
                    table.column(i).search(this.value).draw();
                }
            });
        });*/
    });


    // SCRIPT DEL SLIMSELECT
    /*var selProducto = new SlimSelect({
        select: '.seleccion-producto',
        placeholder: 'Seleccione el nombre de la droga y luego su presentación...',
    });*/
</script>
