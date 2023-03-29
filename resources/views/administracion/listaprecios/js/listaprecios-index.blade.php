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
            fixedHeader: true
            /*"dom": "rltip",
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{-- route('administracion.cotizaciones.ajax') --}}",
                method: "GET"
            },
            "order": [0, 'desc'],
            "columnDefs": [{
                    targets: [0],
                    name: "razon_social",
                    className: "align-middle text-center",
                    'render': function(data) {
                        return moment(new Date(data)).format("DD/MM/YYYY");
                    },
                },
                {
                    targets: [1],
                    name: "alta",
                    className: "align-middle text-center",
                    'render': function(data) {
                        return moment(new Date(data)).format("DD/MM/YYYY");
                    },
                },
                {
                    targets: [2],
                    name: "modificado",
                    className: "align-middle text-center",
                    'render': function(data) {
                        return moment(new Date(data)).format("DD/MM/YYYY");
                    },
                },
                /*{
                    targets: [3],
                    name: "usuario",
                    className: "align-middle",
                },
                {
                    targets: [4],
                    name: "estado",
                    className: "align-middle text-center",
                    width: 100
                },
                {
                    targets: [3],
                    name: "acciones",
                    className: "align-middle text-center",
                    orderable: false,
                },
            ],
            "initComplete": function() {
                this.api()
                    .columns([1, 2, 3, 4])
                    .every(function() {
                        var that = this;

                        $('input', this.footer()).on('keyup change clear', function() {
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });
            },*/
        });
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
    });

    
    // SCRIPT DEL SLIMSELECT
    /*var selProducto = new SlimSelect({
        select: '.seleccion-producto',
        placeholder: 'Seleccione el nombre de la droga y luego su presentación...',
    });*/
</script>