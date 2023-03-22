<div class="pl-lg-4">
    <form action="{{ route('administracion.listaprecios.create') }}" method="POST" class="needs-validation" autocomplete="off" novalidate>
        @csrf
        <div class="form-row d-flex">   
            <table id="tabla2" class="table table-bordered table-responsive-md" width="100%">
                <thead>
                    <th>Código de Proveedor</th>
                    <th>Droga</th>
                    <th>Forma/Presentacion</th>
                    <th>Costo</th>
                    <th>Quitar</th>
                </thead>
                <tbody>
                    <tr class="1eritem">
                    </tr>
                </tbody>  
            </table>
        </div>
    </form>
</div>