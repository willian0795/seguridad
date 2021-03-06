<script type="text/javascript">
    function cambiar_editar(id_rol,nombre_rol,descripcion_rol){
        $("#id_rol").val(id_rol);
        $("#nombre_rol").val(nombre_rol);
        $("#descripcion_rol").val(descripcion_rol);
        $("#id_sistema").val("0").trigger("change.select2");

        $("#ttl_form").removeClass("bg-success");
        $("#ttl_form").addClass("bg-info");
        $("#band").val("edit");
        $("#btnadd").hide(0);
        $("#btnedit").show(0);

        $("#cnt-tabla").hide(0);
        $("#cnt_form").show(0);
        $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar Rol");
    }

    function cambiar_nuevo(){
        $("#nombre_rol").val("");
        $("#descripcion_rol").val("");
        $("#id_sistema").val("0").trigger("change.select2");
         
        $("#band").val("save");

        $("#ttl_form").addClass("bg-success");
        $("#ttl_form").removeClass("bg-info");

        $("#btnadd").show(0);
        $("#btnedit").hide(0);

        $("#cnt-tabla").hide(0);
        $("#cnt_form").show(0);
        tabla_rol_modulo_permiso2();
        $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nuevo Rol");
    }


    function cerrar_mantenimiento(){
        $("#nombre_rol").val("");
        $("#descripcion_rol").val("");
        $("#id_sistema").val("");
        mostrarSistemas();
        

        $("#cnt-tabla").show(0);
        $("#cnt_form").hide(0);
        tablaroles();
    }

    function editar_rol(obj){
        $("#band").val("edit");
        $("#submit").click();
    }

    function eliminar_rol(obj){
        $("#band").val("delete");
        swal({
            title: "¿Está seguro?",
            text: "¡Desea eliminar el registro!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#fc4b6c",
            confirmButtonText: "Sí, deseo eliminar!",
            closeOnConfirm: false
        }, function(){
            $("#submit").click();
        });
    }

    function iniciar(){
        tablaroles();
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

    function tablaroles(){
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }

        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                  document.getElementById("cnt-tabla").innerHTML=xmlhttpB.responseText;
                  $('#myTable').DataTable();
            }
        }

        xmlhttpB.open("GET","<?php echo site_url(); ?>/roles/roles/tablaroles",true);
        xmlhttpB.send();
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /* script de asignacion permisos a rol*/
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    function mostrar_form_permisos(id){
        tabla_rol_modulo_permiso(id);   
    }
    function tabla_rol_modulo_permiso(id){
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }

        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                  document.getElementById("cnt-tabla").innerHTML=xmlhttpB.responseText;
                  $('#myTable').DataTable();
            }
        }

        xmlhttpB.open("GET","<?php echo site_url(); ?>/roles/roles/tabla_rol_modulo_permiso/"+id,true);
        xmlhttpB.send();
    }
    function tabla_rol_modulo_permiso2(id){
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }

        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                  document.getElementById("cnt-tabla-rol").innerHTML=xmlhttpB.responseText;
                  $('#nestable').nestable({
                    group: 1
                });
            }
        }

        xmlhttpB.open("GET","<?php echo site_url(); ?>/roles/roles/tabla_rol/"+id,true);
        xmlhttpB.send();
    }
    function tabla_rol_modulo_permiso3(id,id_rol){
        id+="x"+id_rol;

        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }

        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                  document.getElementById("cnt-tabla-rol").innerHTML=xmlhttpB.responseText;
                  $('#nestable').nestable({
                    group: 1
                });
            }
        }

        xmlhttpB.open("GET","<?php echo site_url(); ?>/roles/roles/tabla_rol_chequed/"+id,true);
        xmlhttpB.send();
    }

    

    function mostrarSistemas(id,id_rol){
        if($("#band").val()=="save"){
            tabla_rol_modulo_permiso2(id);        
        }else{
            tabla_rol_modulo_permiso3(id,id_rol);
        }
    }

    function ultimo_rol(){               
        ajax = objetoAjax();
        ajax.open("POST", "<?php echo site_url(); ?>/roles/roles/ultimo_rol", true);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4){
                $("#id_rol").val(ajax.responseText);
                recorrer2();
            }
        } 
        ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
        ajax.send()
    }

    function eliminar_roles(id){
        id_sistema = $("#id_sistema").val();
        ajax = objetoAjax();
        ajax.open("POST", "<?php echo site_url(); ?>/roles/roles/eliminar_roles", true);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4){
                if(ajax.responseText == "exito"){
                    console.log(ajax.responseText)
                    recorrer2();
                }
            }
        } 
        ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
        ajax.send("&id_rol="+id+"&id_sistema="+id_sistema)
    }

    function recorrer(){        
        var sentinela=0;
        
        if($("#band").val()=="save"){
            if($("#nombre_rol").val()!=""){
                mantto_rol("","save",$("#nombre_rol").val(),$("#descripcion_rol").val());
                ultimo_rol();
                sentinela=1;
            }
        }else if($("#band").val()=="edit"){
            if($("#nombre_rol").val()!=""){
                mantto_rol($("#id_rol").val(),"edit",$("#nombre_rol").val(),$("#descripcion_rol").val());
                sentinela=1;
            }
        }
        
        if(sentinela==1)cerrar_mantenimiento();
    }

    function recorrer2(){
        var grupos_de_inputs = $("#nestable").find(".input-group"); // Recuperando agrupaciones de inputs cada agrupacion
        var query = "";
        var idmodulo, seleccionar, insertar, modificar, eliminar,id = 1;
        for(var i=0; i<grupos_de_inputs.length; i++){
            var inputs = $(grupos_de_inputs[i]).find("input"); // Sacando inputs de 5 en 5. (Cinco por cada agrupación)
            for(j=1; j<=4; j++){
                if($(inputs[j]).val() == 1){
                    query += "\n('*id"+id+"*','"+$("#id_rol").val()+"','"+$(inputs[0]).val()+"','"+j+"','1'),";
                }else{
                    query += "\n('*id"+id+"*','"+$("#id_rol").val()+"','"+$(inputs[0]).val()+"','"+j+"','0'),";
                }
                id++;
            }
        }

        console.log(grupos_de_inputs.length);

        if(query!=""){
            query = "INSERT INTO org_rol_modulo_permiso (id_rol_permiso,id_rol,id_modulo,id_permiso,estado) VALUES"+query.substr(0,(query.length-1))+";";
            guardar_rol_modulo_permiso(query)
        }else{
            if($("#band").val() == "save"){
                swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
            }else if($("#band").val() == "edit"){
                swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
            }
        }
    }

    function guardar_rol_modulo_permiso(query){
        var newName = 'AjaxCall',
        xhr = new XMLHttpRequest();
        xhr.open('POST', "<?php echo site_url(); ?>/roles/roles/guardar_rol_modulo_permiso");
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200 && xhr.responseText !== newName) {
                if(xhr.responseText == "exito"){
                    if($("#band").val() == "save"){
                        swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                    }else if($("#band").val() == "edit"){
                        swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                    }
                }else{
                    swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true }); 
                }  
            }else if (xhr.status !== 200) {
                swal({ title: "Ups! ocurrió un Error", text: "Al parecer la tabla de empresas visitadas no se cargó correctamente por favor recarga la página e intentalo nuevamente", type: "error", showConfirmButton: true });
            }
        };
        xhr.send('name='+newName+"&query="+query);
    }

    function cambiar_check(obj, id_modulo, permiso){
        var id_rol = $("#id_rol").val();
        if( $(obj).prop('checked') ) {
            $(obj).val('1');
            if($("#band").val() == "edit"){
                insertar_rol_individual(id_rol, id_modulo, permiso, 1);
            }
        }else{
            $(obj).val('0');
            if($("#band").val() == "edit"){
                insertar_rol_individual(id_rol, id_modulo, permiso, 0);
            }
        }
    }

    function marcar_check(obj, modulo){
        var labels = $(obj).parent().siblings('label');
        var hijo;
        for(i=0; i<4;i++){
            hijo = $(labels[i]).children('input');
            if( $(obj).prop('checked') ) {
                hijo[0].checked = 1;
                cambiar_check(hijo[0], modulo, (i+1));
            }else{
                hijo[0].checked = 0;
                cambiar_check(hijo[0], modulo, (i+1));
            }
        }

        /*ar grupos_de_inputs = $(obj).parent().parent(); // Recuperando agrupacion de inputs
        var id_modulo = $(grupos_de_inputs).children('input').val(); // Recuperando agrupacion de inputs
        var grupo_de_label = $(grupos_de_inputs).children('label'); // Recuperando agrupacion de inputs
        var query = "";
        var idmodulo, seleccionar, insertar, modificar, eliminar,id = 1;

        var inputs = $(grupo_de_label[0]).find("input"); // Sacando inputs de 5 en 5. (Cinco por cada agrupación)
        var ids = "";
        for(j=1; j<=4; j++){
            query += "\nWHEN '"+$("#id_rol").val()+"' THEN '"+$(inputs[0]).val()+"'";
            if(j == 4){
                ids += $("#id_rol").val();
            }else{
                ids += $("#id_rol").val()+", ";
            }
        }

        query = "UPDATE org_rol_modulo_permiso \n SET estado = CASE id_rol "+query.substr(0,(query.length-1))+" \nWHERE id_rol IN ("+ids+");";

        alert(query)*/
    }

    /*******************************************************
    WHEN <<id>> then <<valor>>


UPDATE empleados
    SET orden = CASE id_empleado
        WHEN 12 THEN 1
        WHEN 254 THEN 4
        WHEN 87 THEN 8
        WHEN 23 THEN 14
    END,
    edad = CASE id_empleado
        WHEN 12 THEN 32
        WHEN 254 THEN 19
        WHEN 87 THEN 43
        WHEN 23 THEN 51
    END
WHERE id_empleado IN (12, 254, 87, 23);
*******************************************************/

    function verificar_eliminacion(id_rol, nombre){        
        var parametros = {
            "idb" : id_rol,
            "nombre" : nombre
        };
        $.ajax({
            data:  parametros, //datos que se envian a traves de ajax
            url:   '<?php echo site_url(); ?>/roles/roles/verificar_usuarios', //archivo que recibe la peticion
            type:  'post', //método de envio
            success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
                if(response == "eliminar"){
                    borrarRol(id_rol);
                }else{
                    $('#myModal').modal('show'); // abrir
                    $("#resultado").html("Para eliminar el rol '"+parametros["nombre"]+"' debes quitarle este rol a los usuarios: <br><br>"+response);
                }
            }
        });
    }

    function borrarRol(id_rol){
        swal({
            title: "¿Está seguro?",
            text: "¡Desea eliminar el registro!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#fc4b6c",
            confirmButtonText: "Sí, deseo eliminar!",
            closeOnConfirm: false
        }, function(){
            mantto_rol(id_rol,"delete","dsf","sdf");
            swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
            cerrar_mantenimiento(); 
        });
    }

    function permisos_a_rol(id_rol_permiso,band,nombre_rol,id_modulo,id_permiso,estado){
            
      var formData = new FormData();
      formData.append("band", band);
      formData.append("id_rol_permiso", id_rol_permiso);
      formData.append("nombre_rol", nombre_rol);
      formData.append("id_modulo", id_modulo);
      formData.append("id_permiso", id_permiso);
      formData.append("estado", estado);

        $.ajax({
            url: "<?php echo site_url(); ?>/roles/roles/gestionar_rol_modulo_permiso",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                 
                if($("#band").val() == "save"){
                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                }else if($("#band").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                }else{
                    swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
                }
                 $("#band").val('save');
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
    }
    function mantto_rol(id_rol,band,nombre_rol,descripcion_rol){
        var formData = new FormData();
        formData.append("band", band);
        formData.append("id_rol", id_rol);
        formData.append("nombre_rol",nombre_rol);
        formData.append("descripcion_rol", descripcion_rol);

        $.ajax({
            url: "<?php echo site_url(); ?>/roles/roles/gestionar_rol",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "fracaso"){
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }else{
                 
            }
        });
    }

    function insertar_rol_individual(id_rol, id_modulo, id_permiso, estado){
        var formData = new FormData();
        formData.append("id_rol", id_rol);
        formData.append("id_modulo",id_modulo);
        formData.append("id_permiso", id_permiso);
        formData.append("estado", estado);

        $.ajax({
            url: "<?php echo site_url(); ?>/roles/roles/insertar_rol_individual",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                $.toast({ heading: '¡Exito!', text: 'Cambio aplicado', position: 'top-left', loaderBg:'#000', icon: 'success', hideAfter: 2000, stack: 6 });
            }else{
                $.toast({ heading: 'Error!', text: 'No se pudo aplicar el cambio', position: 'top-left', loaderBg:'#000', icon: 'error', hideAfter: 2000, stack: 6 });
            }
        });
    }

    function cambiar_rango(id_rango, id_rol_permiso){
        var formData = new FormData();
        formData.append("id_rol_permiso", id_rol_permiso);
        formData.append("id_rango",id_rango);
        $.ajax({
            url: "<?php echo site_url(); ?>/roles/roles/cambiar_rango",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                $.toast({ heading: '¡Exito!', text: 'Cambio aplicado', position: 'top-right', loaderBg:'#000', icon: 'success', hideAfter: 3000, stack: 6 });
            }else{
                $.toast({ heading: 'Error!', text: 'No se pudo aplicar el cambio', position: 'top-right', loaderBg:'#000', icon: 'error', hideAfter: 3000, stack: 6 });
            }
        });
    }

    function combos_rango(id_modulo){
        var id_rol = $("#id_rol").val();
        if(window.XMLHttpRequest){ xmlhttpB=new XMLHttpRequest();
        }else{ xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB"); }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_modulo_rango").innerHTML=xmlhttpB.responseText;
                $("#modal_rango").modal('show');
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/roles/roles/combos_rango?id_modulo="+id_modulo+"&id_rol="+id_rol,true);
        xmlhttpB.send();
    }

    function cerrar_modal(){
        mostrarSistemas($('#id_sistema').val(),$('#id_rol').val())
        $("#modal_rango").modal('hide');
    }

    function stopRKey(evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
    }
    document.onkeypress = stopRKey; 
    

</script>
<style type="text/css">
    /* second level */
    #nestable > ol > li:hover > .dd-handle {
        background: #f1f2f3;
        font-size: 15px;
        border-radius: 5px;
        box-sizing: border-box;
        transition: all 150ms ease;
    }
    #nestable > ol > li > ol > li:hover > .dd-handle {
        background: #f6fde6;
        font-size: 15px;
        border-radius: 5px;
        box-sizing: border-box;
        transition: all 150ms ease;
    }
    #nestable > ol > li > ol > li > ol > li:hover > .dd-handle {
        background: #ffeefe;
        font-size: 15px;
        border-radius: 5px;
        box-sizing: border-box;
        transition: all 150ms ease;
    }
    .rombo .contenido{
        width: 15px;
        height: 15px;
        -moz-border-radius: 50%; 
        -webkit-border-radius: 50%; 
        border-radius: 50%;
        background: #4679BD;
    }
    .rombo {
      padding: 0px;
      float:left;
    }
    .rombo .contenido .texto {
      border-radius:0px;
     font-family: Verdana;
      color:white;
        position: relative;
      left: 25%;
      padding:0px;
      top: 3%;
      font-size: 10px;
      text-transform:uppercase;
    }
</style>

<!-- ============================================================== -->
<!-- Inicio de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Gestión de Roles del MTPS</h3>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin TITULO de la página de sección -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Inicio del CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
        <div class="row">

            <!-- ============================================================== -->
            <!-- Inicio del FORMULARIO de gestión -->
            <!-- ============================================================== -->
            <div class="col-lg-1"></div>
            <div class="col-lg-10" id="cnt_form" style="display: none;">
                <div class="card">
                    <div class="card-header bg-success2" id="ttl_form">
                        <div class="card-actions text-white">
                            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                        </div>
                        <h4 class="card-title m-b-0 text-white">Listado de Roles</h4>
                    </div>
                    <div class="card-body b-t">

                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40')); ?>
                            <input type="hidden" id="band" name="band" value="save" placeholder="band">
                            <input type="hidden" id="id_rol" name="id_rol" placeholder="id_rol">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre_rol" class="font-weight-bold">Nombre del Rol: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nombre_rol" name="nombre_rol" required="" placeholder="Nombre del Rol" data-validation-required-message="Este campo es requerido">
                                       <div class="help-block"></div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="descripcion_rol" class="font-weight-bold">Descripción del rol :<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="descripcion_rol" name="descripcion_rol" required="" placeholder="Descripción del rol" data-validation-required-message="Este campo es requerido">
                                        <div class="help-block"></div> </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="form-group col-lg-12">   
                                <label for="id_sistema" class="font-weight-bold">Seleccione Sistema :<span class="text-danger">*</span></label>                         
                                    <select id="id_sistema" name="id_sistema" class="select2" onchange="mostrarSistemas(this.value,$('#id_rol').val())" style="width: 100%">
                                        <option value="0">[Elija el sistema]</option>
                                        <?php 
                                            $sistemas = $this->db->get("org_sistema");
                                            if($sistemas->num_rows() > 0){
                                                foreach ($sistemas->result() as $fila) {              
                                                   echo '<option class="m-l-50" value="'.$fila->id_sistema.'">'.$fila->nombre_sistema.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                    </div>
                                </div>
                               <div>
                                    <div id="cnt-tabla-rol"></div>
                                </div>

                            <button id="submit" type="submit" style="display: none;"></button>
                            <div align="right" id="btnadd">
                                <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-delete"></i> Limpiar</button>
                                <button type="button" onclick="recorrer()" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-plus"></i> Guardar</button>
                            </div>
                            <div align="right" id="btnedit" style="display: none;">
                                <button type="button" class="btn waves-effect waves-light btn-info" onclick="cerrar_mantenimiento();"><i class="mdi mdi-pencil"></i> Editar</button>
                            </div>

                        <?php echo form_close(); ?>
                    </div>

                </div>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-12" id="cnt-tabla">
            </div>

        </div>

    </div>
</div>


<!-- sample modal content -->
<div id="modal_rango" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Seleccione los rangos del módulo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div id="cnt_modulo_rango"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect" onclick="cerrar_modal();">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- sample modal content -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">¡El rol ya está asignado!</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <p id="resultado"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script>

$(function(){
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        

    });


    
});

</script>

