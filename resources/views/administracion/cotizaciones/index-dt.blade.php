@extends('adminlte::page')

@section('title', 'Administrar Cotizaciones')

@section('css')
    <style>
        .texto-header {
            padding: 20px;
            height: 90px;
            overflow-y: auto;
            /*font-size: 14px;*/
            font-weight: 500;
            color: #000000;
        }

        .texto-header::-webkit-scrollbar {
            width: 5px;
            background-color: #282828;
        }

        .texto-header::-webkit-scrollbar-thumb {
            background-color: #3bd136;
        }

        @media (max-width: 600px) {
            .hide {
                display: none;
            }
        }
    </style>
@endsection

@section('content_header')
    <div class="row">
        <div class="col-xl-8">
            <h1>Últimas cotizaciones</h1>
        </div>
        <div class="col-xl-4 d-flex justify-content-xl-end">
            <a href="{{ route('administracion.cotizaciones.create') }}" role="button" class="btn btn-md btn-success">Crear
                cotización</a>
            &nbsp;
            <a href="{{ route('home') }}" role="button" class="btn btn-md btn-secondary">Volver</a>
        </div>
    </div>
@endsection

{{-- aquí va contenido --}}
@section('content')
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    @section('plugins.TempusDominusBs4', true)
    <div class="card">
        <div class="card-header bg-gray-light">
            <div class="texto-header">
                <h5>Dinámica de cotizaciones</h5>
                <p>
                    Los términos de búsqueda se realizan en los campos debajo de cada columna habilitada.
                </p>
                <p>
                    El orden de promoción es el siguiente: CREACIÓN -> EDICIÓN -> FINALIZACIÓN -> PRESENTACIÓN -> APROBACIÓN o RECHAZO.
                </p>
            </div>
        </div>
        <div class="card-body">
            <div class="processing">
                <table id="tabla-cotizaciones" class="table table-bordered table-responsive-md" width="100%">
                    <thead>
                        <tr class="bg-gray">
                            <th>F. modificación</th>
                            <th>Identificador</th>
                            <th>Cliente</th>
                            <th>Vendedor/F. creación</th>
                            <th>Plazo de entrega</th>
                            <th>ESTADO</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot style="display: table-header-group;">
                        <tr class=" bg-gradient-light">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL APROBACIÓN LICITACIÓN --}}
    @include('administracion.cotizaciones.partials.modal-aprobacion-licitacion')

    {{-- MODAL RECHAZO LICITACIÓN --}}
    @include('administracion.cotizaciones.partials.modal-rachazo-licitacion')
@endsection

@section('js')
    @include('partials.alerts')
    <script type="text/javascript" src="{{ asset('js/datatables-spanish.js') }}" defer></script>
    <script>
        // Reload manual de la página
        function recargar() {
            setTimeout(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Cotización presentada',
                    text: 'La cotización quedará en espera de ser aprobada o rechazada.',
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.location.href = '{{ route('administracion.cotizaciones.index') }}';
                        return false;
                    }
                })
            }, 1000)
        };

        function borrarCotizacion(id) {
            let advertencia =
                'Se eliminará esta cotización y todos sus productos asociados. Esta acción no se puede deshacer.';
            Swal.fire({
                icon: 'warning',
                title: 'Borrar cotización',
                html: '<span style=\'color: red; font-weight:800; font-size:1.3em;\'>¡ATENCION!</span><br>' +
                    advertencia,
                confirmButtonText: 'Borrar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#borrar-' + id).submit();
                }
            });
        };

        function selectAll(){
            var items=document.getElementsByName('lineasAprobadas[]');
            for(var i=0; i<items.length; i++){
                if(items[i].type=='checkbox')
                    items[i].checked=true;
            }
        }

        function UnSelectAll(){
            var items=document.getElementsByName('lineasAprobadas[]');
            for(var i=0; i<items.length; i++){
                if(items[i].type=='checkbox')
                    items[i].checked=false;
            }
        }

        $(document).ready(function() {
            moment.locale('es');

            $('#tabla-cotizaciones tfoot th').slice(1, 4).each(function() {
                $(this).html('<input type="text" class="form-control" placeholder="Buscar" />');
            });

            $('#tabla-cotizaciones').dataTable({
                "dom": "rltip",
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: "{{ route('administracion.cotizaciones.ajax') }}",
                    method: "GET"
                },
                "order": [0, 'desc'],
                "columnDefs": [{
                        targets: [0],
                        name: "fecha-modificacion",
                        className: "align-middle text-center",
                        'render': function(data) {
                            return moment(new Date(data)).format("DD/MM/YYYY");
                        },
                    },
                    {
                        targets: [1],
                        name: "identificador",
                        className: "align-middle text-center font-weight-bold",
                    },
                    {
                        targets: [2],
                        name: "cliente",
                        className: "align-middle",
                    },
                    {
                        targets: [3],
                        name: "usuario",
                        className: "align-middle",
                    },
                    {
                        targets: [4],
                        name: "plazo_entrega",
                        className: "align-middle",
                    },
                    {
                        targets: [5],
                        name: "estado",
                        className: "align-middle text-center",
                        width: 100
                    },
                    {
                        targets: [6],
                        name: "acciones",
                        className: "align-middle text-center",
                        orderable: false,
                    },
                ],
                "initComplete": function() {
                    this.api()
                        .columns([1, 2, 3])
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

            // función para que aparezca el nombre de archivo en el input
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });

            // se coloca en el ACTION el id de cotización seleccionada a APROBAR
            $('#modalAprobarCotizacion').on('show.bs.modal', function(event) {
                $('#formAprobada').attr('action', 'cotizaciones/' + event.relatedTarget.id +
                    '/aprobarCotizacion');

                $('#lineasCotizadas').empty();
                let datos = {
                    cotizacion_id: event.relatedTarget.id,
                };

                $.ajax({
                    url: "{{route('administracion.cotizaciones.ajax.obtener')}}",
                    type: "GET",
                    data: datos,
                })
                .done(function(resultado) {
                    let linea = 1;
                    $.each(resultado, function(index){
                        $('#lineasCotizadas').append(
                            "<div class='form-check'>"
                            +"<input class='form-check-input' name='lineasAprobadas[]' type='checkbox' value="+ resultado[index].cotizado_id +" id='defaultCheck1' checked>"
                            +"<label class='form-check-label' for='defaultCheck1'>Línea "+ linea +", "+resultado[index].droga +" - "+ resultado[index].forma +" "+ resultado[index].presentacion +"</label>"
                            +"</div>"
                        );
                        linea = linea + 1;
                    });
                });
            });

            // se coloca en el ACTION el id de cotización seleccionada a RECHAZADA
            $('#modalRechazarCotizacion').on('show.bs.modal', function(event) {
                $('#formRechazada').attr('action', 'cotizaciones/' + event.relatedTarget.id +
                    '/rechazarCotizacion');
            });
        });
    </script>
@endsection

@section('footer')
<strong>AUSI - ESCMB - UNC - <a href="https://mb.unc.edu.ar/" target="_blank">mb.unc.edu.ar</a></strong>
<div class="float-right d-none d-sm-inline-block">
    <b>Versión de software 2.8</b> (PHP: v{{phpversion()}} | LARAVEL: v.{{App::VERSION()}})
</div>
@endsection
