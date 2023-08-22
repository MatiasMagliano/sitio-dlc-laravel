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

    function restaurarListado(id) {
        Swal.fire({
            icon: 'info',
            title: 'Restaurar Listado',
            text: 'Esta acción volverá atras al último estado del listado del proveedor.',
            confirmButtonText: 'Restaurar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#restaurar-' + id).submit();
                sleep(20);
            }else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Operación cancelada por usuario, no se restaura el listado de proveedor',
                    'error'
                )
            }
        });
    };

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
        //let table = new DataTable('#tabla');
        var tabla = $('#tabla').DataTable( {
            dom: "tp",
            pageLength: 10,
            scrollCollapse: true,
            order: [0, 'asc'],
            columnDefs: [
                {
                    targets: [0],
                    className: "align-middle",
                },
                {
                    targets: [1],
                    className: "align-middle text-center",
                    'render': function(data) {
                        return moment(new Date(data)).format("DD/MM/YYYY HH:mm:ss");
                    },
                },
                {
                    targets: [2],
                    className: "align-middle text-center",
                    'render': function(data) {
                        return moment(new Date(data)).format("DD/MM/YYYY HH:mm:ss");
                    },
                },
                {
                    targets: [3],
                    className: "align-middle text-center",
                }
            ],
        });

        /*var tabla = $('#tabla').dataTable({
            orderCellsTop: true,
            fixedHeader: true,
            dom: "rltip",
            /*"processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{-- route('administracion.cotizaciones.ajax') --}}",
                method: "GET"
            },
            "order": [0, 'desc'],
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
        });*/

        $('#tabla tfoot th').slice(0, 3).each(function(i) {
            $(this).html('<input type="text" class="form-control rs-' + i + '" placeholder="Buscar" />');

            $('.rs-' + i, this).on('keyup change', function(){
                var title = this.value.toUpperCase();
                $("#tabla tr").find('td:eq(' + i + ')').each(function () {

                    //obtenemos el codigo de la celda
                    codigo = $(this).html();
                    var exist = codigo.includes(title);
                    if(!exist){
                        $(this).parent().hide();
                    }else{
                        $(this).parent().show();
                    }
                });
                /*if(tabla.column(i).search() !== this.value){
                    table.column(i).search(this.value).draw();
                }*/
            });
        });

        /*$('#tabla tfoot th').slice(0, 3).each(function() {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar" />');

            $('input', this).on('keyup change', function(){
                if(tabla.column(i).search() !== this.value){
                    table.column(i).search(this.value).draw();
                }
            });
        });*/
        //$('#tabla_filter label').append("<button type='button' class='btn btn-sm btn-link text-primary' title='Mas filtros'><i class='fas fa-solid fa-filter'></i></button>");
    });
</script>
