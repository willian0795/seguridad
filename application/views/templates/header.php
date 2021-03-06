<?php
    $user = $this->session->userdata('usuario');
    if(empty($user)){
        header("Location: ".site_url()."/login");
        exit();
    }
    

    $pos = strpos($user, ".")+1;
    $inicialUser = strtoupper(substr($user,0,1).substr($user, $pos,1));    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/images/logo_min.png">
    <title>SecurityPlus</title>
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.2.1.min.js"></script>
    <!--nestable CSS -->
    <link href="<?php echo base_url(); ?>assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/plugins/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Select plugins css -->
    <link href="<?php echo base_url(); ?>assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="<?php echo base_url(); ?>assets/css/colors/default-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<script>
    var minutos = 15;
    var warning = 10;
    var danger = 3;

    $(document).ready(function() {
        $("#password_val").val("");
        localStorage["ventanas"]++;
        if(hora() >= 60*minutos || localStorage["expira"] == "expirada"){
            cerrar_sesion(0);
        }
    });

    function cambiar_hora_expira(s){
        if(localStorage["expira"] == "expirada"){
            $("#contador").text("Expira: expirada");
        }else{
            s = (60*minutos) - s;
            var secs = s % 60;
            s = (s - secs) / 60;
            var mins = s % 60;
            var hrs = (s - mins) / 60;
            horas = addZ(mins) + ':' + addZ(secs);

            $("#contador").text("Expira: "+horas);
        }
    }

    window.onbeforeunload = function() {
        //localStorage["expira"] = 0;
        localStorage["ventanas"] -= 1;

        if(localStorage["ventanas"] == 0){
            
        }
    }

    function hora(){
        var c = new Date();
        var a = new Date(c.getFullYear(),c.getMonth(),c.getDate(),c.getHours(),c.getMinutes(),c.getSeconds());
        var b = new Date(localStorage["expira"]);
        //La diferencia se da en milisegundos así que debes dividir entre 1000
        var result = ((a-b)/1000);
        cambiar_hora_expira(result);
        return result; // resultado 5;;
    }

    function addZ(n) {
        return (n<10? '0':'') + n;
    }

    var otra = (function(){
        var condicion;
        var moviendo= false;
        document.onmousemove = function(){
            moviendo= true;
        };
        setInterval (function() {
            if (!moviendo || localStorage["expira"] == "expirada") {
                // No ha habido movimiento desde hace un segundo, aquí tu codigo
                condicion = ((60*minutos)-hora())
                if(hora() >= 60*minutos){
                    cerrar_sesion(1000);
                }
                if(localStorage["expira"] == "expirada"){
                    cerrar_sesion(0);
                }
                if(condicion < (warning*60) && condicion > (danger*60)){
                    $("#initial_user").removeClass("text-danger animacion_nueva");
                    $("#initial_user").addClass("text-warning");
                    $("#initial_user").show(0);
                }
                if(condicion <= (danger*60)){
                    $("#initial_user").removeClass("text-warning");
                    $("#initial_user").addClass("text-danger animacion_nueva");
                    $("#initial_user").show(0);
                }

            } else {
                moviendo=false;
                var c = new Date();
                localStorage["expira"] = new Date(c.getFullYear(),c.getMonth(),c.getDate(),c.getHours(),c.getMinutes(),c.getSeconds());
                hora();
                $("#initial_user").hide(0);
            }
       }, 1000); // Cada segundo, pon el valor que quieras.
    })()

    function cerrar_sesion(t){
        $("#congelar").fadeIn(t);
        $("#main-wrapper").fadeOut(t);
        localStorage["expira"] = "expirada";
    }

    function objetoAjax(){
        var xmlhttp = false;
        try {
            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
        return xmlhttp;
    }

    function verificar_usuario2(){       
        var usuario = $("#ususario_val").val();
        var password = $("#password_val").val(); 

        jugador = document.getElementById('jugador');
        
        ajax = objetoAjax();
        ajax.open("POST", "<?php echo site_url(); ?>/login/verificar_usuario", true);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4){
                jugador.value = (ajax.responseText);
                if(jugador.value == "exito"){
                    continuar_sesion();
                }else{
                    swal({ title: "¡Error!", text: "la contraseña no es válida", type: "warning", showConfirmButton: true });
                    $("#password_val").val("");
                }
            }
        } 

        ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
        ajax.send("&usuario="+usuario+"&password="+password)
    }

    function continuar_sesion(){
        $("#congelar").fadeOut(1000);
        $("#main-wrapper").fadeIn(1000);
        var c = new Date();
        localStorage["expira"] = new Date(c.getFullYear(),c.getMonth(),c.getDate(),c.getHours(),c.getMinutes(),c.getSeconds());
    }

    function esEnter(e) {
        if (e.keyCode == 13) {
            $("#btnClickUser").click();
        }
    }

</script>
<style>
.animacion_nueva {
    animation : scales 4.0s ease infinite;
  -webkit-animation: scales 1.9s ease-in infinite alternate;
  -moz-animation: scales 1.9s ease-in infinite alternate;
  animation: scales 1.9s ease-in infinite alternate;
}
@-moz-keyframes scales {
  from {
    -webkit-transform: scale(0.8);
    -moz-transform: scale(0.8);
    transform: scale(0.8);
  }
  to {
    -webkit-transform: scale(1.1);
    -moz-transform: scale(1.1);
    transform: scale(1.1);
  }
}
@-webkit-keyframes scales {
  from {
    -webkit-transform: scale(1.0);
    -moz-transform: scale(1.0);
    transform: scale(1.0);
  }
  to {
    -webkit-transform: scale(1.2);
    -moz-transform: scale(1.2);
    transform: scale(1.2);
  }
}
@-o-keyframes scales {
  from {
    -webkit-transform: scale(1.0);
    -moz-transform: scale(1.0);
    transform: scale(1.0);
  }
  to {
    -webkit-transform: scale(1.2);
    -moz-transform: scale(1.2);
    transform: scale(1.2);
  }
}
@keyframes scales {
  from {
    -webkit-transform: scale(1.0);
    -moz-transform: scale(1.0);
    transform: scale(1.0);
  }
  to {
    -webkit-transform: scale(1.2);
    -moz-transform: scale(1.2);
    transform: scale(1.2);
  }
}

.modal-body {
  max-height:450px;
  overflow-y:scroll;
}

    </style>

<body class="fix-header fix-sidebar card-no-border logo-center" onload="iniciar();">
<?php 
    $id_sistema = 12;
    $sistemas = $this->db->query("SELECT * FROM org_sistema WHERE id_sistema=14");
    if($sistemas->num_rows() > 0){
        foreach ($sistemas->result() as $otro) {
           $id_sistema = $otro->id_sistema;
        }
    }
?>
<input type="hidden" name="jugador" id="jugador">

<div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>

    <!-- ============================================================== -->
    <!-- Icono de cargando página... -->
    <!-- ============================================================== -->
<section id="congelar" style="display: none;">
    <div class="login-register" style="background-image: url(<?php echo base_url()."assets/images/portadas/seguridad7.jpg"; ?>); background-color: rgb(238, 245, 249);" >        
        <div class="login-box card">
            <div class="card-body" style="z-index: 999;">
                <div align="right">
                    <a class="btn" href="<?php echo site_url(); ?>/login" class="btn btn-default" data-toggle="tooltip" title="Ir al login"><span class="fa fa-chevron-left"></span> Volver </a>
                </div>
                <div class="form-group">
                  <div class="col-xs-12 text-center">
                    <div class="user-thumb text-center"> 
                        <h3 class="text-warning"><span class="mdi mdi-information"></span> La sesión ha expirado</h3>
                        <h4 style="font-size: 70px; margin-bottom: 0;" class="text-info mdi mdi-account"></h4>
                        <h4><?php echo ucwords(strtolower($this->session->userdata('nombre_usuario'))); ?></h4>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="ususario_val" id="ususario_val" value="<?php echo $this->session->userdata('usuario') ?>">
                <div class="form-group ">
                  <div class="col-xs-12">
                    <input onkeypress="esEnter(event);" class="form-control" type="password" id="password_val" name="password_val" required="" placeholder="password">
                  </div>
                </div>
                <div class="form-group text-center">
                  <div class="col-xs-12">
                    <button id="btnClickUser" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" onclick="verificar_usuario2()" type="button">Continuar</button>
                  </div>
                </div>
            </div>
          </div>
    </div>
    
</section>
<div id="main-wrapper" style="display: block;">
        <!-- ============================================================== -->
        <!-- Barra superior -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo site_url(); ?>">
                        <!-- Logo icono --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="<?php echo base_url(); ?>assets/images/logo_min.png" height='45px' alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="<?php echo base_url(); ?>assets/images/logo_min.png" height='45px' alt="homepage" class="light-logo" />
                        </b>
                        <!--Fin Logo icon -->
                        <!-- Logo text --><span>
                         <!-- dark Logo text -->
                         <img onclick="detener();" src="<?php echo base_url(); ?>assets/images/logo_text.png" height='30px;' alt="homepage" class="dark-logo" />
                         <!-- Light Logo text -->    
                         <img onclick="detener();" src="<?php echo base_url(); ?>assets/images/logo_text.png" style="margin-left: 10px; margin-top: 10px;"  height='30px;' class="light-logo" alt="homepage" /></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- Fin Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-dark waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item"> <a id="clic" class="nav-link sidebartoggler hidden-sm-down text-dark waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item"> <a id="initial_user" style="display: none;" class="nav-link waves-effect waves-dark" href="javascript:void(0)"><span id="contador"></span></a> </li>
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="round round-success bg-inverse"><?php echo $inicialUser; ?></span></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-text">
                                                <h4><?php echo $this->session->userdata('nombre_usuario'); ?></h4>

                                                <p align="right"><a href="#!" class="btn btn-rounded btn-info waves-effect waves-light">Activo</a></p>
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#" onclick="cerrar_sesion(1000);"><i class="fa fa-lock"></i> Bloquear sesión</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?php echo site_url(); ?>/cerrar_sesion"><i class="fa fa-power-off"></i> Salir</a></li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Profile -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap text-center">MENÚ</li>
                        <li class="nav-devider" style="margin:5px;"></li>

                        <li> <a class="has-arrow waves-effect waves-dark" href="#!" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu">Sistemas MTPS</span></a>
                            <ul aria-expanded="false" class="collapse" style="padding-left: 20px;">
                                <li><a href="<?php echo site_url(); ?>/sistemas"><span class="mdi mdi-label"></span> Sistemas</a></li>
                                <li><a href="<?php echo site_url(); ?>/modulos"><span class="mdi mdi-label"></span> Módulos</a></li>
                            </ul>
                        </li>

                        <li> <a class="has-arrow waves-effect waves-dark" href="#!" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Usuarios</span></a>
                            <ul aria-expanded="false" class="collapse" style="padding-left: 20px;">
                                <li><a href="<?php echo site_url(); ?>/usuarios"><span class="mdi mdi-label"></span> Usuarios</a></li>
                            </ul>
                        </li>

                        <li> <a class="has-arrow waves-effect waves-dark" href="#!" aria-expanded="false"><i class="mdi mdi-treasure-chest"></i><span class="hide-menu">Roles</span></a>
                            <ul aria-expanded="false" class="collapse" style="padding-left: 20px;">
                                <li><a href="<?php echo site_url(); ?>/roles/roles"><span class="mdi mdi-label"></span> Roles</a></li>
                            </ul>
                        </li>

                        <li> <a class="has-arrow waves-effect waves-dark" href="#!" aria-expanded="false"><i class="mdi mdi-history"></i><span class="hide-menu">Bitácora</span></a>
                            <ul aria-expanded="false" class="collapse" style="padding-left: 20px;">
                                <li><a href="<?php echo site_url(); ?>/bitacora/bitacora"><span class="mdi mdi-label"></span> Bitácora</a></li>
                            </ul>
                        </li>
                            
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item--><a href="<?php echo site_url(); ?>" class="link" data-toggle="tooltip" title="Ir a Inicio"><i class="mdi mdi-home"></i></a>
                <!-- item--><a href="http://www.mtps.gob.sv/" target="blank" class="link" data-toggle="tooltip" title="Web MTPS"><i class="mdi mdi-web"></i></a>
                <!-- item--><a href="<?php echo site_url(); ?>/cerrar_sesion" class="link" data-toggle="tooltip" title="Salir"><i class="mdi mdi-power"></i></a> </div>
            <!-- End Bottom points-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
