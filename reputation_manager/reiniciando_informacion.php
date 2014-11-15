<?php

include "../cabecera.php";

if(!isset($_SESSION['sess_usu_id'])){
	echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL='.$_SESSION['c_base_location'].'index.php">';
}
		
$variables_metodo = variables_metodo("etiqueta,nombre");
$etiqueta= 		$variables_metodo[0];
$nombre= 		$variables_metodo[1];

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
    <div style="font-size:19px" class="titulo">Reiniciando informaci&oacute;n de la b&uacute;squeda, ahora debe esperar a que el sistema autom&aacute;ticamente vuelva a encontrar coincidencias...</div>
    <div style="font-size:12px; font-style:italic">Por favor espere, no cancele el proceso.</div>
</div>

<form action="dashboard.php" name="cargando" id="cargando" target="_top" method="post">
	<input type="hidden" name="etiqueta" id="etiqueta" value="<?=$etiqueta?>"/>
</form>

<script>
$( document ).ready(function() {	
	$("#cargando").submit();
});
</script>
</body>
</html>