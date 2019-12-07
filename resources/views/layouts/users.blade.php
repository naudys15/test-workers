<?php
    if(!isset($_SESSION)){
        session_start();
    }
    error_reporting(E_ERROR);
    $type_message = Session::get('type_message');
?>
<!DOCTYPE html>

<html class="no-js" lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Administración</title>
        <meta name="description" content="Sufee Admin - HTML5 Admin Template">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Favicon -->
        <link rel="icon" href="{{asset('images/favicon.png')}}" type="image/x-icon" />
        <!-- Estilos -->
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap-toogle.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

        <!-- Fuentes -->
        <!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'> -->

    </head>

    <body>
        <!-- Left Panel -->
        <aside id="left-panel" class="left-panel">
            <nav class="navbar navbar-expand-sm navbar-default">

                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="./"><img src="{{asset('images/logo.png')}}" alt="Logo"></a>
                    <a class="navbar-brand hidden" href="./"><img src="{{asset('images/favicon.png')}}" alt="Logo"></a>
                </div>

                <div id="main-menu" class="main-menu collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        @if (Session::has('id_us') && Session::get('role_us') == 'admin')
                            <li class="active">
                                <a href="{{route('home.usuarios')}}">
                                    <i class="menu-icon fa fa-user-circle"></i>
                                    Usuarios
                                </a>
                            </li>
                            <li>
                                <a href="{{route('home.reportes')}}">
                                    <i class="menu-icon fa fa-tablet"></i>
                                    Reportes
                                </a>
                            </li>
                        @elseif (Session::has('id_us') && Session::get('role_us') == 'employee')
                            <li class="active">
                                <a href="{{route('home.reportes')}}">
                                    <i class="menu-icon fa fa-tablet"></i>
                                    Reportes
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </aside>
        <!-- Left Panel -->

        <!-- Right Panel -->
        <div id="right-panel" class="right-panel">

            <!-- Header-->
            <header id="header" class="header">

                <div class="header-menu">

                    <div class="col-sm-7">
                        <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    </div>
                    @if (Session::has('id_us'))
                        <div class="col-sm-5">
                            <div class="user-area dropdown float-right">
                                <a href="#" class="dropdown-toggle d-flex align-items-center" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="text-center pr-2">Bienvenido {{Session::get('name_us')}}</div>
                                    <img class="user-avatar rounded-circle" src="{{asset('images/admin.jpg')}}" title="Bienvenido {{$name_user}}">
                                </a>

                                <div class="user-menu dropdown-menu">
                                    <div style="cursor:pointer">
                                        <a class="nav-link" onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Cerrar Sesión</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </header>
            <!-- Header -->
            @yield('breadcrumbs')
            <div class="content mt-3">
                <!-- Mensajes de alerta, para indicar el éxito o el fallo de una operación -->
                @if($type_message == 'exito')
                    <div class="alert alert-success show" role="alert">
                    {{Session::get('message')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                @elseif($type_message == 'error')
                    <div class="alert alert-danger show" role="alert">
                    {{Session::get('message')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>

        <!-- Right Panel -->
        
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>
        <script src="{{asset('js/bootstrap-toogle.min.js')}}"></script>
        <script src="{{asset('js/popper.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#formulario').find('input, textarea, button, select').attr('disabled','disabled');
            //Reinicia la variable de sesión errores_form cada vez que se recarga la página
            //
            $(document).ready(function(){
                $.ajax({
                    url:    "{{route('contar_errores_form',3)}}",
                    type:   "POST",
                    async: true,
                    data: {"_token": "{{ csrf_token() }}"},
                    success: function(data){
                        $('#formulario').find('input, textarea, button, select').attr('disabled',false);
                    }
                });
            });
            $('[data-onload]').each(function() {
                actualiza($(this).attr('data-onload'));

            });
            //Función que realiza las validaciones de los campos de los formularios, y permite mostrar el error, además de habilitar y deshabilitar el botón de envío del formulario
            function actualiza(nomb_var){
                var valor = $('input:radio[name='+nomb_var+']:checked').val();

                if(valor == undefined){
                    valor = $("input[name="+nomb_var+"]").val();
                }
                if(valor == undefined){
                    valor = $("select[name="+nomb_var+"]").val();
                }
                if(valor == undefined){
                    valor = $("textarea[name="+nomb_var+"]").val();
                }

                if(nomb_var == 'seleccion2_7'){
                    valor = $('#seleccion2_7').prop('checked')?'1':'0';
                }
                
                //alert(valor);
                var aux = document.querySelector('#'+nomb_var+'_v');
                aux2 = aux.getAttribute('class');

                $('#'+nomb_var+'_v').removeClass();
                $('#'+nomb_var).attr('disabled',true);
                $('#'+nomb_var+'_v').html('');
                $('#'+nomb_var+'_v').html('<img width="25px" height="25px" src="{{asset("images/cargando.gif")}}">');

                $.ajax({
                    url:    "{{route('validar_formulario')}}",
                    type:   "POST",
                    async: true,
                    data: {"_token": "{{ csrf_token() }}",valor:valor,nom:nomb_var},
                    success: function(data){
                        if(data =='Correcto'){

                            if(aux2 == 'fa fa-remove'){
                                $.ajax({
                                    url:    "{{route('contar_errores_form',1)}}",
                                    type:   "POST",
                                    async: true,
                                    data: {"_token": "{{ csrf_token() }}"},
                                    success: function(data){
                                        if(data == 0){
                                            $('#actualizar').attr("disabled", false); 
                                            $('#enviar').attr("disabled", false);
                                            $('#enviar1').attr("disabled", false);
                                            $('#enviar2').attr("disabled", false);
                                        }
                                    }
                                });
                            }
                            $('#'+nomb_var+'_v').removeClass();
                            $('#'+nomb_var+'_v').addClass('fa fa-check');
                            $('#'+nomb_var+'_v').html(''); 
                            $('#'+nomb_var+'_v').css({'color':'green','font-size':'18px'});
                        }
                        else{
                            if(aux2 != 'fa fa-remove'){
                                $.ajax({
                                    url:    "{{route('contar_errores_form',2)}}",
                                    type:   "POST",
                                    async: true,
                                    data: {"_token": "{{ csrf_token() }}"},
                                    success: function(data){
                                        if(data > 0){
                                            $('#actualizar').attr("disabled", true); 
                                            $('#enviar').attr("disabled", true);
                                            $('#enviar1').attr("disabled", true); 
                                            $('#enviar2').attr("disabled", true);  
                                        }
                                    }
                                });
                            }
                            $('#'+nomb_var+'_v').removeClass();
                            $('#'+nomb_var+'_v').addClass('fa fa-remove');
                            $('#'+nomb_var+'_v').html(' '+data+'<br>'); 
                            $('#'+nomb_var+'_v').css({'color':'red','font-size':'18px'});
                            
                        }
                        if(nomb_var == "password_confirmation" || nomb_var == "contrasena"){
                            if(nomb_var == "contrasena" && $('#password_confirmation_v').length != 0){
                                var valor2 = $("input[name='password_confirmation']").val();
                                nomb_vari = "password_confirmation";
                            }
                            else if(nomb_var == "password_confirmation" && $('#'+nomb_var+'_v').length != 0){
                                var valor2 = $("input[name='contrasena']").val();
                                nomb_vari = nomb_var;
                            }
                            if(valor != ""){
                                if(valor != valor2){
                                    $('#'+nomb_vari+'_v').removeClass();
                                    $('#'+nomb_vari+'_v').addClass('fa fa-remove');
                                    $('#'+nomb_vari+'_v').html(' Las contraseñas no coinciden<br>'); 
                                    $('#'+nomb_vari+'_v').css({'color':'red','font-size':'18px'});
                                    if(aux2 != 'fa fa-remove'){
                                        $.ajax({
                                            url:    "{{route('contar_errores_form',2)}}",
                                            type:   "POST",
                                            async: true,
                                            data: {"_token": "{{ csrf_token() }}"},
                                            success: function(data){
                                                if(data > 0){
                                                    $('#actualizar').attr("disabled", true); 
                                                    $('#enviar').attr("disabled", true); 
                                                    $('#enviar1').attr("disabled", true); 
                                                    $('#enviar2').attr("disabled", true); 
                                                }
                                            }
                                        });
                                    }
                                }
                                else if(valor == valor2){
                                    $('#'+nomb_vari+'_v').removeClass();
                                    $('#'+nomb_vari+'_v').addClass('fa fa-check');
                                    $('#'+nomb_vari+'_v').html(''); 
                                    $('#'+nomb_vari+'_v').css({'color':'green','font-size':'18px'});
                                    if(aux2 == 'fa fa-remove'){
                                        $.ajax({
                                            url:    "{{route('contar_errores_form',1)}}",
                                            type:   "POST",
                                            async: true,
                                            data: {"_token": "{{ csrf_token() }}"},
                                            success: function(data){
                                                if(data == 0){
                                                    $('#actualizar').attr("disabled", false); 
                                                    $('#enviar').attr("disabled", false);
                                                    $('#enviar1').attr("disabled", false);
                                                    $('#enviar2').attr("disabled", false);
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                            else{
                                $('#'+nomb_vari+'_v').removeClass();
                                $('#'+nomb_vari+'_v').addClass('fa fa-remove');
                                $('#'+nomb_vari+'_v').html(' La confirmación de la nueva contraseña no puede estar en blanco<br>'); 
                                $('#'+nomb_vari+'_v').css({'color':'red','font-size':'18px'});
                                if(aux2 != 'fa fa-remove'){
                                    $.ajax({
                                        url:    "{{route('contar_errores_form',2)}}",
                                        type:   "POST",
                                        async: true,
                                        data: {"_token": "{{ csrf_token() }}"},
                                        success: function(data){
                                            if(data > 0){
                                                $('#actualizar').attr("disabled", true); 
                                                $('#enviar').attr("disabled", true); 
                                                $('#enviar1').attr("disabled", true); 
                                                $('#enviar2').attr("disabled", true);
                                            }
                                        }
                                    });
                                }   
                            }

                        }
                    }
                });
                $('#'+nomb_var).attr('disabled',false);
            }
        </script>
        <!-- Additional javascript -->
        @yield('javascript')
    </body>
</html>
