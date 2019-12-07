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
                        <li><a href="{{route('home')}}">Reportes</a>
                        </li>
                        <?php if ($reporte == null) { ?>
                            <li class="active">Crear reporte
                            </li>
                        <?php } else { ?>
                            <li class="active">Editar reporte
                            </li>
                        <?php } ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body w-50 m-auto">
                        <form  
                            <?php if ($reporte != null) {?>
                                action="{{route('reportes.update', $reporte->id)}}"
                                method="PUT"
                            <?php } elseif ($reporte == null) { ?>
                                action="{{route('reportes.store')}}"
                                method="POST"
                            <?php } ?> id="formulario">
                            <div class="form-group">
                            <input type="text" class="form-control" onChange='actualiza("nombre");' <?php if ($reporte == null) { ?> data-onload='nombre' <?php } ?> name="nombre" id="nombre" value="<?php if ($reporte != null ) { echo $reporte->name; } else { echo ''; }?>" placeholder="Nombre">
                                <div class="w-100 text-center"><span id="nombre_v"></span></div>
                            </div>
                            <div class="form-group">
                            <input type="text" class="form-control" onChange='actualiza("descripcion");' <?php if ($reporte == null) { ?> data-onload='descripcion' <?php } ?> name="descripcion" id="descripcion" value="<?php if ($reporte != null ) { echo $reporte->description; } else { echo ''; }?>" placeholder="Descripción">
                                <div class="w-100 text-center"><span id="descripcion_v"></span></div>
                            </div>
                            <div class="form-group">
                                <button id="enviar" class="form-control btn btn-primary" <?php if ($reporte != null) { ?>onclick="confirmar('PUT', {{$reporte->id}})" <?php } else { ?> onclick="confirmar('POST', 0)" <?php } ?> type="button">
                                    <i class="fa fa-check"></i>  Enviar
                                </button>
                                <!-- <button id="enviar" class="form-control btn btn-primary" type="submit">
                                    <i class="fa fa-check"></i>  Enviar
                                </button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        var midata;
        function confirmar(type_method, id)
        {
            if (confirm('¿Deseas enviar el formulario?')) {
                // var form = $('#formulario')[0];
                // midata = new FormData(form);
                var midata = new FormData();
                midata.append('name', $('#nombre').val());
                midata.append('description', $('#descripcion').val());
                midata.append('user_id', '<?= Session::get('id_us') ?>');
                let url_report = "";
                if (type_method == 'POST') {
                    url_report = "{{route('reportes.store')}}";
                } else if (type_method == 'PUT') {
                    type_method = "POST";
                    url_report = "{{route('reportes.actualizar',($reporte != null)?$reporte->id:'')}}";
                }
                $.ajax({
                    url:    url_report,
                    type:   type_method,
                    contentType: false,
                    data: midata,  // mandamos el objeto formdata que se igualo a la variable data
                    processData: false,
                    cache: false,
                    async: true,
                    success: function(data){
                        console.log(data);
                        if(data.status == 200){
                            alert(data.msj);
                            cargarTabla();
                        }
                    }
                });
            }
        }
    </script>
@endsection
