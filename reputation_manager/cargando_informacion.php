<?php

include "../cabecera.php";

if(!isset($_SESSION['sess_usu_id'])){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'index.php">';
}
		
$variables_metodo = variables_metodo("etiqueta,nuevo,estado,modificar");
$etiqueta= 		$variables_metodo[0];
$nuevo= 		$variables_metodo[1];
$estado= 		$variables_metodo[2];
$modificar= 	$variables_metodo[3];

if($estado!="A"){
	echo '
<script>
$( document ).ready(function() {
	$.ajax({
		url: "reputation_manager/ajax_refrescar_etiquetas.php",
		async:true,   
		cache:false,  
		dataType:"html",
		type: "POST", 
		success: function(datos_recibidos) {
			$("#cargando").submit();
		}
	});	
});
</script>
<form action="dashboard.php" name="cargando" id="cargando" target="_top" method="post">
<input type="hidden" name="etiqueta" id="etiqueta" value="'.$etiqueta.'"/>
</form>';

	die();
}

/////////////////////////////////////

$tipo_cargue = date("H");

$sql="SELECT id_categoria FROM ic_etiquetas_categoria WHERE id_etiqueta='".$etiqueta."'";
$result_categorias=$db->Execute($sql);

$categorias="";
$tiene_redes=false;
$url = "admin/lecturas_online/php_leer_redes.php";

while(!$result_categorias->EOF){
	list($id_categoria)=$result_categorias->fields;
	
	if($id_categoria=="2"){
		$tiene_redes = true;
	}
	
	$categorias.=$id_categoria;
	
	$result_categorias->MoveNext();
	
	if(!$result_categorias->EOF){
		$categorias .= ",";	
	}
}
	
if(!$tiene_redes){
	$url = "admin/lecturas_online/php_leer_google.php";
}

?>

<div style="width: 600px; text-align:center; margin:50px auto;">
<style type="text/css">

.container {width: 300px; margin: 0 auto; overflow: hidden;}
.content {width:300px; margin:0 auto; padding-top:50px;}

/* STOP ANIMATION */

.stop {
	-webkit-animation-play-state:paused;
	-moz-animation-play-state:paused;
}


/* Second Loadin Circle */

.circle {
	background-color: rgba(0,0,0,0);
	border:5px solid #F25630;
	opacity:.9;
	border-right:5px solid rgba(0,0,0,0);
	border-left:5px solid rgba(0,0,0,0);
	border-radius:50px;
	box-shadow: 0 0 35px #F25630;
	width:70px;
	height:70px;
	margin:0 auto;
	-moz-animation:spinPulse 1s infinite ease-in-out;
	-webkit-animation:spinPulse 1s infinite linear;
}
.circle1 {
	background-color: rgba(0,0,0,0);
	border:5px solid #F25630;
	opacity:.9;
	border-left:5px solid rgba(0,0,0,0);
	border-right:5px solid rgba(0,0,0,0);
	border-radius:50px;
	box-shadow: 0 0 15px #F25630; 
	width:40px;
	height:40px;
	margin:0 auto;
	position:relative;
	top:-65px;
	-moz-animation:spinoffPulse 1s infinite linear;
	-webkit-animation:spinoffPulse 1s infinite linear;
}

@-moz-keyframes spinPulse {
	0% { -moz-transform:rotate(160deg); opacity:0; box-shadow:0 0 1px #F25630;}
	50% { -moz-transform:rotate(145deg); opacity:1; }
	100% { -moz-transform:rotate(-320deg); opacity:0; }
}
@-moz-keyframes spinoffPulse {
	0% { -moz-transform:rotate(0deg); }
	100% { -moz-transform:rotate(360deg);  }
}
@-webkit-keyframes spinPulse {
	0% { -webkit-transform:rotate(160deg); opacity:0; box-shadow:0 0 1px #F25630; }
	50% { -webkit-transform:rotate(145deg); opacity:1;}
	100% { -webkit-transform:rotate(-320deg); opacity:0; }
}
@-webkit-keyframes spinoffPulse {
	0% { -webkit-transform:rotate(0deg); }
	100% { -webkit-transform:rotate(360deg); }
}

/* Trigger button for javascript */

.trigger, .triggerFull, .triggerBar {
	background: #000000;
	background: -moz-linear-gradient(top, #161616 0%, #000000 100%);
	background: -webkit-linear-gradient(top, #161616 0%,#000000 100%);
	border-left:1px solid #111; border-top:1px solid #111; border-right:1px solid #333; border-bottom:1px solid #333; 
	font-family: Verdana, Geneva, sans-serif;
	font-size: 0.8em;
	text-decoration: none;
	text-transform: lowercase;
	text-align: center;
	color: #fff;
	padding: 10px;
	border-radius: 3px;
	display: block;
	margin: 0 auto;
	width: 140px;
}
		
.trigger:hover, .triggerFull:hover, .triggerBar:hover {
	background: -moz-linear-gradient(top, #202020 0%, #161616 100%);
	background: -webkit-linear-gradient(top, #202020 0%, #161616 100%);
}

</style>
<script>		

$(document).ready(function() {
	$('.circle, .circle1').removeClass('stop');	    
		$('.triggerFull').click(function() {
				$('.circle, .circle1').toggleClass('stop');
		});
});

</script>

<div class="container">
	<div class="content">
    <div class="circle"></div>
    <div class="circle1"></div>
    </div>
</div>
    <div style="font-size:19px" class="titulo">Recopilando informacion de etiquetas y del universo...</div>
    <div style="font-size:12px; font-style:italic">Por favor espere, no cancele el proceso.</div>
</div>
<script>
<?php
	if($modificar==""){
?>

$( document ).ready(function() {
	$.ajax({
		url: '<?=$url?>',
		async:true,   
		cache:false,  
		dataType:"html",
		type: 'POST', 
		data: { etiqueta: "<?=$etiqueta?>", modo: "diario", tipo_cargue: "<?=$tipo_cargue?>", red: "438" },
		success: function(datos_recibidos) {
			
			$.ajax({
				url: 'reputation_manager/ajax_refrescar_etiquetas.php',
				async:true,   
				cache:false,  
				dataType:"html",
				type: 'POST', 
				success: function(datos_recibidos) {
					setTimeout(function () { $("#cargando").submit(); }, 2500 ); 
				}
			});		
		}
	});		
});
<?php
	}else{
?>
	setTimeout(function () { window.location.href="reputation_manager/crear_tags.php?etiqueta=<?=$etiqueta?>&modificar=1"; }, 2500 ); 
<?php	
	}
?>
</script>

<form action="dashboard.php" name="cargando" id="cargando" target="_top" method="post">
	<input type="hidden" name="etiqueta" id="etiqueta" value="<?=$etiqueta?>"/>
    <input type="hidden" name="carga_inicial" id="carga_inicial" value="<?=$categorias?>"/>
    <input type="hidden" name="tipoCargue" id="tipoCargue" value="<?=$tipo_cargue?>"/>
</form>

</body>
</html>