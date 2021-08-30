<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Administrador de ventas</title>
    <link rel="stylesheet" type="text/css" href="bootstrap-5.1.0-dist/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-5.1.0-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/mystyles.css">
    <script type="text/javascript" src="js/generales.js"></script>
</head>
<body>
<?php include('navbar.php') ?>

<div class="container margin-top-20" id="alertas"></div>

<form class="container" id="formUpload">
  <fieldset>
    <legend>Procesar documento</legend>
    <div class="mb-3">
      <label for="formFile" class="form-label">Selecciona un documento Excel</label>
      <input class="form-control" type="file" id="formFile" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" name="file">
    </div>
    <button type="submit" class="btn btn-primary" id="btnSubmit">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="btnLoading"></span>
        Procesar
    </button>
  </fieldset>
</form>
<div class="container" style="margin-top: 50px; margin-bottom: 50px;" id="resultado">
    <h3>Información encontrada</h3>
    <h3><small class="text-muted" id="full_date"></small></h3>
    <div class="row">
        <div class="col-4" style="border-right: solid 1px #dbdbdb;" id="bombas">
            <!--<div class="row">
                <div class="col-6"><p class="lead">R1</p></div>
                <div class="col-6" style="text-align:right;">27,559.87</div>
            </div>
            <div class="row">
                <div class="col-6"><p class="lead">R2</p></div>
                <div class="col-6" style="text-align:right;">25,880.67</div>
            </div>-->
        </div>
        <div class="col-8" id="clientes">
            <!--<div class="row">
                <div class="col-8">
                    <p class="lead is-invalid">anali concepcion vela gutierrez <div class="invalid-feedback">
                        Este cliente no se encuentra en la Base de Datos, favor de verificarlo
                    </div></p>
                </div>
                <div class="col-4" style="text-align:right;">2,200.00</div>
            </div>
            <div class="row">
                <div class="col-8"><p class="lead">municipio de teotitlan de flores m</p></div>
                <div class="col-4" style="text-align:right;">3,800.00</div>
            </div>-->
        </div>
    </div>
    <div class="col-12">
        <button type="button" class="btn btn-success" id="btnSaveInfo">GUARDAR LA INFORMACIÓN</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Clientes Registrados</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group list-group-flush" id="modalClientes"></ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

</body>
<script type="text/javascript">
    function createAlertError(message){
            var wrapper = document.createElement('div');
            wrapper.innerHTML = '<div class="alert alert-danger alert-dismissible" role="alert"><strong>Error:</strong> ' + message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            $("#alertas").html(wrapper)
        }
    function showClientes(){
        //$("#modalClientes")
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
          keyboard: false
        });
        myModal.show();
    }
    $(document).ready(function (e) {
        var clientes = {}
        var info_to_save = {'bombas': {}, 'clientes': {}};
        $("#btnLoading").fadeOut();
        $("#resultado").fadeOut();

        $.ajax({
            url: "procesos/getclientes.php",
            type: "GET",
            contentType: false,
            cache: false,
            processData:false,
            success: function(strData){
                clientes = JSON.parse(strData);
                $.each(clientes, function(nombre, id){
                    $("#modalClientes").append('<li class="list-group-item">'+nombre+'</li>');
                });
            },
            error: function(e) {
                //$("#err").html(e).fadeIn();
                console.log('Ocurrio un error '+e)
            }
        });

        $("#formUpload").on('submit',(function(e) {
            e.preventDefault();
            $.ajax({
                url: "procesos/uploadxlsx.php",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend : function(){
                    $("#btnSubmit").prop("disabled", true);
                    $("#btnLoading").fadeIn();
                    $("#alertas").html('');
                    $("#bombas").html('<h4 class="display-6" style="text-align:center;">Bombas</h4>');
                    $("#clientes").html('<h4 class="display-6" style="text-align:center;">Clientes</h4>');
                    $("#resultado").fadeOut();
                },
                success: function(strData){
                    data = JSON.parse(strData);
                    var info_to_save = {'bombas': {}, 'clientes': {}};
                    $("#btnLoading").fadeOut();
                    $("#btnSubmit").prop("disabled", false);
                    //$("#formUpload")[0].reset();
                    if(typeof data['status'] != 'undefined' && data['status'] == 'error'){
                        createAlertError( data['message'] );
                    } else if(typeof data['error'] != 'undefined'){
                        createAlertError( data['error'] );
                    } else if( typeof data['full_date'] == 'object' ){
                        createAlertError( data['full_date']['error'] );
                    } else if( typeof data['totales']['error'] != 'undefined' ){
                        createAlertError( data['totales']['error'] );
                    } else{
                        console.log(clientes)
                        $("#full_date").html( data['full_date'] );

                        // Agrego la información de las Bombas
                        jQuery.each( data['totales'], function(bomba, val){
                            row = $('<div class="row">'+
                                '<div class="col-6"><p class="lead">'+bomba+'</p></div>'+
                                '<div class="col-6" style="text-align:right;">'+to_pesos(val)+'</div>'+
                            +'</div>');
                            $("#bombas").append( row );
                        } );

                        info_to_save['bombas'] = data['totales'];
                        info_to_save['full_date'] = data['full_date'];

                        // Agrego la información de los Clientes
                        jQuery.each( data['clientes'], function(cliente, val){
                            if(typeof clientes[cliente] == "undefined" ){
                                console.error("No se encontro el cliente "+cliente);
                                row = $('<div class="row">'+
                                    '<div class="col-8"><p class="lead is-invalid">'+cliente+' <a href="javascript:showClientes();" class="btn btn-link">Ver clientes registrados</a><div class="invalid-feedback">Este cliente no se encuentra en la Base de Datos, favor de verificarlo</div></p></div>'+
                                    '<div class="col-4" style="text-align:right;">'+to_pesos(val)+'</div>'+
                                +'</div>');
                            } else {
                                info_to_save['clientes'][ clientes[ cliente ] ] = val;
                                row = $('<div class="row">'+
                                    '<div class="col-8"><p class="lead">'+cliente+'</p></div>'+
                                    '<div class="col-4" style="text-align:right;">'+to_pesos(val)+'</div>'+
                                +'</div>');
                            }
                            $("#clientes").append( row );
                        } );
                        $("#resultado").fadeIn();

                        $("#btnSaveInfo").click(function(){
                            $.ajax({
                                url: 'procesos/guardainfo.php',
                                type: 'POST',
                                data: {
                                    'info_to_save': JSON.stringify( info_to_save )
                                },
                                cache: false,
                                success: function(data){
                                    console.log(data);
                                }
                            })
                        });
                    }
                },
                error: function(e) {
                    //$("#err").html(e).fadeIn();
                    console.log('Ocurrio un error '+e)
                }
            });
        }));
    });
</script>
</html>

<?php
//phpinfo();

//header("Content-Type: application/x-json");
#passthru ( "/usr/bin/python /home/roman/script_poncho/lee_excel.py '20210818120500'" );
#passthru ( "C:\\Users\\roman\\AppData\\Local\\Programs\\Python\\Python38-32\\python  C:\\xampp\\htdocs\\sistemaponcho\\lee_excel.py \"20210818120500\"" );
?>
