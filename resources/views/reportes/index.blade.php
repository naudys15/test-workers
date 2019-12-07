@extends('layouts.users')
@section('breadcrumbs')
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Reportes</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Listado de reportes
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="modalReporte" tabindex="-1" role="dialog" aria-labelledby="modalReporteCentradoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalReporteTitulo"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="formularioNuevo">
                    <div class="form-group">
                        <input required type="text" class="form-control" onChange='actualiza("nombre");' name="nombre" id="nombre" placeholder="Nombre">
                        <div class="w-100 text-center"><span id="nombre_v"></span></div>
                    </div>
                    <div class="form-group">
                        <input required type="text" class="form-control" onChange='actualiza("descripcion");' name="descripcion" id="descripcion" placeholder="Descripción">
                        <div class="w-100 text-center"><span id="descripcion_v"></span></div>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary rounded" id="guardarReporte" >Guardar</button>
                    <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-body">
                        @if (Session::has('id_us') && Session::get('role_us') == 'employee')
                            <button type="button" class="btn btn-primary rounded" id="nuevoReporte">
                                <i class="fa fa-plus-circle"></i> Nuevo reporte
                            </button>
                            <!-- <div class="pull-left">
                                <a href="{{route('reportes.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Nuevo reporte</a>
                            </div> -->
                        @endif
                        <br>
                        <hr>
                        <div style="overflow-y:scroll">
                            <table id="lookup" class="table w-100" id="tabla_reportes">  
                            <thead bgcolor="#eeeeee" align="center">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th class="text-center"> Opciones </th> 
                                </tr>
                                </thead>
                                <tbody id="lookup-tbody">
                                    @foreach ($reportes as $report)
                                        <tr>
                                            <td class="text-center">{{$report->id}}</td>
                                            <td class="text-center">{{$report->name}}</td>
                                            <td class="text-center">{{$report->description}}</td>
                                            <td class="d-flex justify-content-center">
                                                <!-- <a href="{{route('reportes.edit',$report->id)}}" class="pr-2">
                                                    <button class="btn btn-warning" >
                                                        <i class="fa fa-pencil"></i>&nbsp;Editar
                                                    </button>
                                                </a> -->
                                                <button type="button" onclick="editarReporte('<?=$report->id?>')" class="btn btn-warning rounded" name="Editar">
                                                    <i class="fa fa-pencil"></i> Editar
                                                </button>&nbsp;
                                                <button type="button" onclick="confirmarBorrar('¿Estás seguro que deseas eliminar el reporte?','<?= $report->id ?>')" class="btn btn-danger rounded" name="Eliminar">
                                                    <i class="fa fa-trash"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($reportes) == 0)
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                No hay reportes para mostrar
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="m-auto d-flex justify-content-center">
                            {{$reportes->appends($_GET)->links()}}
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        var midata, id_report;
        function confirmarAgregarEditar(type_method, id)
        {
            var message = '';
            if (type_method == 'POST') {
                message = '¿Deseas guardar el reporte?'; 
            } else {
                message = '¿Deseas actualizar el reporte?'; 
            }
            if (confirm(message)) {
                // var form = $('#formulario')[0];
                // midata = new FormData(form);
                var midata = new FormData();
                midata.append('name', $('#nombre').val());
                midata.append('description', $('#descripcion').val());
                midata.append('user_id', '<?= Session::get('id_us') ?>');
                if (type_method == 'POST') {
                    url_report = "{{route('reportes.store')}}";
                } else if (type_method == 'PUT') {
                    type_method = "POST";
                    url_report = '/reportes/actualizar/'+id;
                }
                $.ajax({
                    url:    url_report,
                    type:   type_method,
                    contentType: false,
                    data: midata,  
                    processData: false,
                    cache: false,
                    async: true,
                    success: function(data){
                        console.log(data);
                        if(data.status == 200){
                            alert(data.msj);
                            $('#modalReporte').modal('hide');
                            cargarTabla();
                        }
                    }
                });
            }
        }
        function confirmarBorrar(mensaje, id)
        {
            if (confirm(mensaje)) {
                let url_delete = "{{route('reportes.borrar')}}";
                $.ajax({
                    url:    url_delete,
                    type:   "DELETE",
                    async: true,
                    data: {"_token": "{{ csrf_token() }}", 'id': id},
                    success: function(data){
                        if(data.status == 200){
                            alert(data.msj);
                            cargarTabla();
                        }
                    }
                });
            }
        }
        function editarReporte(id)
        {
            $.ajax({
                url     : '/reportes/obtener/'+id,
                method  : 'GET',
                success : function(response){
                    let datos = response.data;
                    $('#nombre').val(datos.name);
                    $('#descripcion').val(datos.description);
                    $('#nombre_v').html('');
                    $('#descripcion_v').html('');
                    $('#nombre_v').removeClass('fa fa-check fa-remove');
                    $('#descripcion_v').removeClass('fa fa-check fa-remove');
                    $('#modalReporte').modal('show');
                    $('#modalReporteTitulo').html('Editar reporte');
                    $('#guardarReporte').attr('onclick', 'confirmarAgregarEditar("PUT", '+id+')');
                }
            });
        }
        function cargarTabla()
        {
            $.ajax({
                url     : "{{ route('reportes.index')}}",
                method  : 'GET',
                success : function(response){
                    let lista = response.data;
                    var htmlCode = '';
                    $.each(lista, function(index, item){
                        htmlCode += 
                            `<tr>
                                <td class="text-center">`+item.id+`</td>
                                <td class="text-center">`+item.name+`</td>
                                <td class="text-center">`+item.description+`</td>
                                <td class="d-flex justify-content-center">
                                    <button type="button" onclick="editarReporte(`+item.id+`)" class="btn btn-warning rounded" name="Editar">
                                        <i class="fa fa-pencil"></i> Editar
                                    </button>&nbsp;
                                    <button type="button" onclick="confirmarBorrar('¿Estás seguro que deseas eliminar el reporte?',`+item.id+`)" class="btn btn-danger rounded" name="Eliminar">
                                        <i class="fa fa-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>`;
                    });
                    if (lista == '') {
                        htmlCode += `<tr>
                                        <td colspan="4" class="text-center">
                                            No hay reportes para mostrar
                                        </td>
                                    </tr>`;
                    }
                    $('#lookup-tbody').html(htmlCode);
                }
            });
        }
        $(document).ready(function()
        {
            $('#nuevoReporte').on('click', function()
            {
                $('#nombre').val('');
                $('#descripcion').val('');
                $('#nombre_v').html('');
                $('#descripcion_v').html('');
                $('#nombre_v').removeClass('fa fa-check fa-remove');
                $('#descripcion_v').removeClass('fa fa-check fa-remove');
                $('#modalReporte').modal('show');
                $('#modalReporteTitulo').html('Nuevo reporte');
                $('#guardarReporte').attr('onclick', 'confirmarAgregarEditar("POST", 0)');
            });
        });
    </script>
@endsection
