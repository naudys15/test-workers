@extends('layouts.users')
@section('breadcrumbs')
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Usuarios</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li><a href="{{route('home.usuarios')}}">Usuarios</a>
                        </li>
                        <?php if ($usuario == null) { ?>
                            <li class="active">Crear usuario
                            </li>
                        <?php } else { ?>
                            <li class="active">Editar usuario
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
                            <?php if ($usuario != null) {?>
                                action="{{route('usuarios.update', $usuario->id)}}"
                                method="PUT"
                            <?php } elseif ($usuario == null) { ?>
                                action="{{route('usuarios.store')}}"
                                method="POST"
                            <?php } ?> id="formulario">
                            <div class="form-group">
                            <input type="text" class="form-control" onChange='actualiza("nombre");' <?php if ($usuario == null) { ?> data-onload='nombre' <?php } ?> name="nombre" id="nombre" value="<?php if ($usuario != null ) { echo $usuario->name; } else { echo ''; }?>" placeholder="Nombre">
                                <div class="w-100 text-center"><span id="nombre_v"></span></div>
                            </div>
                            <div class="form-group">
                            <input type="email" class="form-control" onChange='actualiza("email");' <?php if ($usuario == null) { ?> data-onload='email' <?php } ?> name="email" id="email" value="<?php if ($usuario != null ) { echo $usuario->email; } else { echo ''; }?>" placeholder="Correo electrónico">
                                <div class="w-100 text-center"><span id="email_v"></span></div>
                            </div>
                            <div class="form-group">
                                <select name="tipo_usuario" class="form-control" onChange='actualiza("tipo_usuario");' <?php if ($usuario == null) { ?> data-onload='tipo_usuario' <?php } ?> id="tipo_usuario">
                                    <option value="-1">Seleccione una opción</option>
                                    <option value="1" <?php if ($usuario != null && $usuario->roles[0]->name == 'admin') { echo 'selected'; } ?>>Administrador</option>
                                    <option value="2" <?php if ($usuario != null && $usuario->roles[0]->name == 'employee') { echo 'selected'; } ?>>Empleado</option>
                                </select>
                                <div class="w-100 text-center"><span id="tipo_usuario_v"></span></div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" onChange='actualiza("contrasena");' data-onload='contrasena' name="contrasena" id="contrasena" placeholder="Contraseña">
                                <div class="w-100 text-center"><span id="contrasena_v"></span></div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" onChange='actualiza("password_confirmation");' name="password_confirmation" id="password_confirmation" placeholder="Confirmar Contraseña">
                                <div class="w-100 text-center"><span id="password_confirmation_v"></span></div>
                            </div>
                            <div class="form-group">
                                <button id="enviar" class="form-control btn btn-primary" <?php if ($usuario != null) { ?>onclick="confirmar('PUT', {{$usuario->id}})" <?php } else { ?> onclick="confirmar('POST', 0)" <?php } ?> type="button">
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
                midata.append('email', $('#email').val());
                midata.append('password', $('#contrasena').val());
                midata.append('role_user', $('#tipo_usuario').val());
                let url_user = "";
                if (type_method == 'POST') {
                    url_user = "{{route('usuarios.store')}}";
                } else if (type_method == 'PUT') {
                    type_method = "POST";
                    url_user = "{{route('usuarios.actualizar',($usuario != null)?$usuario->id:'')}}";
                }
                $.ajax({
                    url:    url_user,
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
