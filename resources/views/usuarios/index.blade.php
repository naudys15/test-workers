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
                        <li class="active">Listado de usuarios
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <!-- Modal -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioCentradoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUsuarioTitulo"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="formularioNuevo">
                    <div class="form-group">
                        <input type="text" class="form-control" onChange='actualiza("nombre");' name="nombre" id="nombre" placeholder="Nombre">
                        <div class="w-100 text-center"><span id="nombre_v"></span></div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" onChange='actualiza("email");' name="email" id="email" placeholder="Correo electrónico">
                        <div class="w-100 text-center"><span id="email_v"></span></div>
                    </div>
                    <div class="form-group">
                        <select name="tipo_usuario" class="form-control" onChange='actualiza("tipo_usuario");' id="tipo_usuario">
                            <option value="-1">Seleccione una opción</option>
                            <option value="1">Administrador</option>
                            <option value="2">Empleado</option>
                        </select>
                        <div class="w-100 text-center"><span id="tipo_usuario_v"></span></div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" onChange='actualiza("contrasena");' name="contrasena" id="contrasena" placeholder="Contraseña">
                        <div class="w-100 text-center"><span id="contrasena_v"></span></div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" onChange='actualiza("password_confirmation");' name="password_confirmation" id="password_confirmation" placeholder="Confirmar Contraseña">
                        <div class="w-100 text-center"><span id="password_confirmation_v"></span></div>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary rounded" id="guardarUsuario" >Guardar</button>
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
                        <button type="button" class="btn btn-primary rounded" id="nuevoUsuario">
                            <i class="fa fa-plus-circle"></i> Nuevo usuario
                        </button>
                        <!-- <div class="pull-left">
                            <a href="{{route('usuarios.create')}}" class="btn btn-primary"><i class="fa fa-user-circle"></i> Nuevo usuario</a>
                        </div> -->
                        <br>
                        <hr>
                        <div style="overflow-y:scroll">
                            <table id="lookup" class="table w-100" id="tabla_usuarios">  
                            <thead bgcolor="#eeeeee" align="center">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th class="text-center"> Opciones </th> 
                                </tr>
                                </thead>
                                <tbody id="lookup-tbody">
                                    @foreach ($usuarios as $user)
                                        <tr>
                                            <td class="text-center">{{$user->id}}</td>
                                            <td class="text-center">{{$user->name}}</td>
                                            <td class="text-center">{{$user->email}}</td>
                                            <td class="text-center">{{$user->roles[0]->name}}</td>
                                            <td class="d-flex justify-content-center">
                                                <!-- <a href="{{route('usuarios.edit',$user->id)}}" class="pr-2">
                                                    <button class="btn btn-warning" >
                                                        <i class="fa fa-pencil"></i>&nbsp;Editar
                                                    </button>
                                                </a> -->
                                                <button type="button" onclick="editarUsuario('<?=$user->id?>')" class="btn btn-warning rounded" name="Editar">
                                                    <i class="fa fa-pencil"></i> Editar
                                                </button>&nbsp;
                                                <button type="button" onclick="confirmarBorrar('¿Estás seguro que deseas eliminar el usuario?','<?= $user->id ?>')" class="btn btn-danger rounded" name="Eliminar">
                                                    <i class="fa fa-trash"></i> Eliminar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if (count($usuarios) == 0)
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No hay usuarios para mostrar
                                            </td>
                                            
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="m-auto d-flex justify-content-center">
                            {{$usuarios->appends($_GET)->links()}}
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
    <script>
        var midata, id_usuario;
        function confirmarAgregarEditar(type_method, id)
        {
            var message = '';
            if (type_method == 'POST') {
                message = '¿Deseas guardar el usuario?'; 
            } else {
                message = '¿Deseas actualizar el usuario?'; 
            }
            if (confirm(message)) {
                // var form = $('#formulario')[0];
                // midata = new FormData(form);
                var midata = new FormData();
                midata.append('name', $('#nombre').val());
                midata.append('email', $('#email').val());
                midata.append('password', $('#contrasena').val());
                midata.append('role_user', $('#tipo_usuario').val());
                if (type_method == 'POST') {
                    url_report = "{{route('usuarios.store')}}";
                } else if (type_method == 'PUT') {
                    type_method = "POST";
                    url_report = '/usuarios/actualizar/'+id;
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
                            $('#modalUsuario').modal('hide');
                            cargarTabla();
                        }
                    }
                });
            }
        }
        function confirmarBorrar(mensaje, id)
        {
            if (confirm(mensaje)) {
                let url_delete = "{{route('usuarios.borrar')}}";
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
        function editarUsuario(id)
        {
            $.ajax({
                url     : '/usuarios/obtener/'+id,
                method  : 'GET',
                success : function(response){
                    let datos = response.data;
                    console.log(datos);
                    $('#nombre').val(datos.name);
                    $('#email').val(datos.email);
                    $('#tipo_usuario').val(datos.roles[0].id);
                    $('#contrasena').val('');
                    $('#password_confirmation').val('');    
                    $('#nombre_v').html('');
                    $('#email_v').html('');
                    $('#tipo_usuario_v').html('');
                    $('#contrasena_v').html('');
                    $('#password_confirmation_v').html('');
                    $('#nombre_v').removeClass('fa fa-check fa-remove');
                    $('#email_v').removeClass('fa fa-check fa-remove');
                    $('#tipo_usuario_v').removeClass('fa fa-check fa-remove');
                    $('#contrasena_v').removeClass('fa fa-check fa-remove');
                    $('#password_confirmation_v').removeClass('fa fa-check fa-remove');
                    $('#modalUsuario').modal('show');
                    $('#modalUsuarioTitulo').html('Editar usuario');
                    $('#guardarUsuario').attr('onclick', 'confirmarAgregarEditar("PUT", '+id+')');
                }
            });
        }
        function cargarTabla()
        {
            $.ajax({
                url     : "{{ route('usuarios.index')}}",
                method  : 'GET',
                success : function(response){
                    let lista = response.data;
                    var htmlCode = '';
                    $.each(lista, function(index, item){
                        htmlCode += 
                            `<tr>
                                <td class="text-center">`+item.id+`</td>
                                <td class="text-center">`+item.name+`</td>
                                <td class="text-center">`+item.email+`</td>
                                <td class="text-center">`+item.roles[0].name+`</td>
                                <td class="d-flex justify-content-center">
                                    <button type="button" onclick="editarUsuario(`+item.id+`)" class="btn btn-warning rounded" name="Editar">
                                        <i class="fa fa-pencil"></i> Editar
                                    </button>&nbsp;
                                    <button type="button" onclick="confirmarBorrar('¿Estás seguro que deseas eliminar el usuario?',`+item.id+`)" class="btn btn-danger rounded" name="Eliminar">
                                        <i class="fa fa-trash"></i> Eliminar
                                    </button>
                                </td>
                            </tr>`;
                    });
                    $('#lookup-tbody').html(htmlCode);
                }
            });
        }
        $(document).ready(function()
        {
            $('#nuevoUsuario').on('click', function()
            {
                $('#nombre').val('');
                $('#email').val('');
                $('#tipo_usuario').val('');
                $('#contrasena').val('');
                $('#password_confirmation').val('');
                $('#nombre_v').html('');
                $('#email_v').html('');
                $('#tipo_usuario_v').html('');
                $('#contrasena_v').html('');
                $('#password_confirmation_v').html('');
                $('#nombre_v').removeClass('fa fa-check fa-remove');
                $('#email_v').removeClass('fa fa-check fa-remove');
                $('#tipo_usuario_v').removeClass('fa fa-check fa-remove');
                $('#contrasena_v').removeClass('fa fa-check fa-remove');
                $('#password_confirmation_v').removeClass('fa fa-check fa-remove');
                $('#modalUsuario').modal('show');
                $('#modalUsuarioTitulo').html('Nuevo usuario');
                $('#guardarUsuario').attr('onclick', 'confirmarAgregarEditar("POST", 0)');
            });
        });
    </script>
@endsection
