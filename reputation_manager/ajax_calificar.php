<?php
session_start();

$path = "../adodb/adodb.inc.php";
include "../admin/var.php";
include "../conexion.php";
include "../admin/funciones.php";
include("../admin/funciones_start_session.php");

if(isset($_POST['positivo']) && isset($_POST['negativo']) && isset($_POST['neutra']) && isset($_POST['id'])){

	$sql="UPDATE ic_rss_coincidencias 
	      SET positivo='".$_POST['positivo']."',  
		      negativo='".$_POST['negativo']."', 
			  neutro='".$_POST['neutra']."', 
			  modo_califica = 'M'
	      WHERE id='".$_POST['id']."'";
	$result=$db->Execute($sql);	
	
	$sql="SELECT id_etiqueta FROM ic_rss_coincidencias WHERE id='".$_POST['id']."'";
	$result=$db->Execute($sql);	
	list($id_etiqueta)=$result->fields;
	
	///////////////////////////////////////////////////////////
	
	$sql="SELECT COUNT(*) FROM ic_rss_coincidencias WHERE positivo='S' AND id_etiqueta='".$id_etiqueta."'";
	$result=$db->Execute($sql);	
	list($totalposi)=$result->fields;
	$sql="UPDATE ic_etiquetas SET calificacion_posi='".$totalposi."' WHERE id='".$id_etiqueta."'";
	$result=$db->Execute($sql);	
			
	$sql="SELECT COUNT(*) FROM ic_rss_coincidencias WHERE negativo='S' AND id_etiqueta='".$id_etiqueta."'";
	$result=$db->Execute($sql);	
	list($totalnega)=$result->fields;
	$sql="UPDATE ic_etiquetas SET calificacion_nega='".$totalnega."' WHERE id='".$id_etiqueta."'";
	$result=$db->Execute($sql);	
	
	$sql="SELECT COUNT(*) FROM ic_rss_coincidencias WHERE neutro='S' AND id_etiqueta='".$id_etiqueta."'";
	$result=$db->Execute($sql);	
	list($totalneu)=$result->fields;
	$sql="UPDATE ic_etiquetas SET calificacion_neutra='".$totalneu."' WHERE id='".$id_etiqueta."'";
	$result=$db->Execute($sql);	
	
	echo "OK";
}

//----------------------------------

if(isset($_POST['marca']) && isset($_POST['id'])){

	$sql="UPDATE ic_rss_coincidencias SET marcada='".$_POST['marca']."' WHERE id='".$_POST['id']."'";
	$result=$db->Execute($sql);	
	
	echo "OK";
}

//----------------------------------

if(isset($_POST['intervenida']) && isset($_POST['id'])){

	$sql="UPDATE ic_rss_coincidencias SET intervenido='".$_POST['intervenida']."', detalle_intervenido='".utf8_decode($_POST['detalle_intervenida'])."' WHERE id='".$_POST['id']."'";
	$result=$db->Execute($sql);	
	
	echo "OK";
}

//----------------------------------

if(isset($_POST['bloqueada']) && isset($_POST['id'])){

	$sql="UPDATE ic_rss_coincidencias SET bloqueada='".$_POST['bloqueada']."' WHERE id='".$_POST['id']."'";
	$result=$db->Execute($sql);	
	
	
	$sql="SELECT id_etiqueta,id_rss,link FROM ic_rss_coincidencias WHERE id='".$_POST['id']."'";
	$result=$db->Execute($sql);	
	list($id_etiqueta,$id_rss,$link)=$result->fields;

	if($_POST['bloqueada']=="N"){
		$sql="UPDATE ic_etiquetas SET descartar_url=REPLACE(descartar_url,'".$link.",','') WHERE id='".$id_etiqueta."'";
		$result=$db->Execute($sql);	
	}else{
		
		$sql="SELECT id FROM ic_etiquetas WHERE descartar_url LIKE '%".$link."%' AND id='".$id_etiqueta."'";
		$result=$db->Execute($sql);	
		
		if($result->EOF){
			$sql="UPDATE ic_etiquetas SET descartar_url=CONCAT(descartar_url,'".$link.",') WHERE id='".$id_etiqueta."'";
			$result=$db->Execute($sql);	
		}
	}
	
	echo "OK";
}

//----------------------------------

if(isset($_POST['eliminar']) && isset($_POST['id'])){
	
	$id=explode(",",str_replace(",X","",$_POST['id']));
	$etiqueta=$_POST['etiqueta'];
	$rss=explode(",",str_replace(",X","",$_POST['rss']));
	$carg=explode(",",str_replace(",X","",$_POST['carg']));
	$cate=explode(",",str_replace(",X","",$_POST['cate']));
	$usua=$_POST['usua'];
	
	for($i=0;$i<count($id);$i++){
		$stmt = "SELECT `eliminarRegistro`('".$id[$i]."','".$etiqueta."','".$rss[$i]."','".$carg[$i]."','".$cate[$i]."','".$usua."') AS `eliminarRegistro`;";
		$rs = $db->Execute($stmt);
	}

	echo "OK";
}

//----------------------------------

if(isset($_POST['new_dir']) && isset($_POST['text'])){

	$result3=insert_bd_format("id_usuario,nombre,fecha_creacion,total_registros", 
							  "ic_directorios", 
							  array($_SESSION['sess_usu_grupo'],$_POST['text'],date('Y-m-d'),"0"), 
							  $db);
							  
	$sql="SELECT id FROM ic_directorios WHERE id_usuario='".$_SESSION['sess_usu_grupo']."' AND nombre='".$_POST['text']."'";
	$result=$db->Execute($sql);
	list($id)=$result->fields;
	
	// Precarga de directorios
	$_SESSION['bdm_directorios']   = cargar_directorios( $_SESSION['sess_usu_grupo'], $db );
	
	echo $id;
}

//----------------------------------

if(isset($_POST['del_dir']) && isset($_POST['id'])){
							  
	$sql="DELETE FROM ic_directorios WHERE id='".$_POST['id']."'";
	$result=$db->Execute($sql);
	
	$sql="DELETE FROM ic_concidencia_directorio WHERE id_directorio='".$_POST['id']."'";
	$result=$db->Execute($sql);
			
	// Precarga de directorios
	$_SESSION['bdm_directorios']   = cargar_directorios( $_SESSION['sess_usu_grupo'], $db );
	
	echo "OK";
}

//----------------------------------

if(isset($_POST['asig_dir']) && isset($_POST['ids']) && isset($_POST['dir'])){

	$items = explode(",",$_POST['ids']);
	$nombre_dir = "NO";
	$asigno = false;
	
	for($i=0;$i<count($items);$i++){
		if(trim($items[$i])!=""){
			$sql="SELECT id_coincidencia FROM ic_concidencia_directorio WHERE id_coincidencia='".$items[$i]."' AND id_directorio='".$_POST['dir']."' ";
			$result=$db->Execute($sql);
			
			if(!$result->EOF){
				$sql="DELETE FROM ic_concidencia_directorio WHERE id_coincidencia='".$items[$i]."' AND id_directorio='".$_POST['dir']."'";
				$result=$db->Execute($sql);
			}else{
				$sql="DELETE FROM ic_concidencia_directorio WHERE id_coincidencia='".$items[$i]."'";
				$result=$db->Execute($sql);
				
				$result3=insert_bd_format("id_directorio,id_coincidencia,fecha", 
										  "ic_concidencia_directorio", 
										  array($_POST['dir'],$items[$i],date('Y-m-d')), 
										  $db);	
				
				$asigno = true;
			}
		}
	}
	
	if($asigno){
		$sql="SELECT nombre FROM ic_directorios WHERE id='".$_POST['dir']."'";
		$result_dir=$db->Execute($sql);
		list($nombre_dir)=select_format($result_dir->fields);	
	}
	
	echo $nombre_dir;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['usuarios_invitados'])){
	if(isset($_POST['us_id']) && isset($_POST['eliminar'])){
		
		$sql1_1="SELECT us_nombre FROM ic_usuarios WHERE us_id='".$_POST['us_id']."' "; 	
		$result1_1=$db->Execute($sql1_1);
		list($us_nombre)=select_format($result1_1->fields);
		
		//--------------------------------------------------
		
		$sql="INSERT INTO ic_grupo (gru_titulo,gru_conten,gru_creacion,ultimo_acceso) 
		      VALUES('".$us_nombre."','".$us_nombre."','".date('Y-m-d')."','".date('Y-m-d')."')"; 	
		$result2=$db->Execute($sql);
		
		$sql1_2="SELECT gru_id FROM ic_grupo WHERE gru_titulo='".$us_nombre."' AND gru_creacion='".date('Y-m-d')."'"; 	
		$result1_2=$db->Execute($sql1_2);
		list($id_nuevo_grupo)=select_format($result1_2->fields);
		
		////////////////////////////////////////////////////////////////////////////////////
		
		//Registro la cuenta free para el usuario
		$sql="INSERT INTO ic_cuenta_usuarios (parametro,valor,id_grupo_usuario,paquete,nativo)
			  SELECT a.parametro,a.valor,'".$id_nuevo_grupo."' AS grupo,a.paquete,'S' AS nativo
			  FROM ic_paquetes_servicios a WHERE a.paquete = '0';";
		$result_cuenta=$db->Execute($sql);
		
		$sql="INSERT INTO ic_cuenta_usuarios (parametro,valor,id_grupo_usuario,paquete,nativo) 
		      VALUES ('FECHA_CIERRE','".date('Y-m-d',strtotime(''.date('Y-m-d').' +30 day'))."',
			          '".$id_nuevo_grupo."','0','S')";
		$result_cuenta=$db->Execute($sql);
		$sql="INSERT INTO ic_cuenta_usuarios (parametro,valor,id_grupo_usuario,paquete,nativo) 
		      VALUES ('ACUMULADO_UNIVERSO','0',
			          '".$id_nuevo_grupo."','0','S')";
		$result_cuenta=$db->Execute($sql);
		
		////////////////////////////////////////////////////////////////////////////////////
		
		$sql="UPDATE ic_usu_gru SET gru_id='".$id_nuevo_grupo."' WHERE id='".$_POST['us_id']."';"; 	
		$result=$db->Execute($sql);
		$sql="UPDATE ic_usuarios SET us_tipo='ADM_ETI' WHERE us_id='".$_POST['us_id']."';"; 	
		$result=$db->Execute($sql);
		
		echo "OK";
		
	}elseif(isset($_POST['us_id']) && isset($_POST['permiso'])){
		$sql="UPDATE ic_usuarios SET us_tipo='".$_POST['permiso']."' WHERE us_id='".$_POST['us_id']."';"; 	
		$result=$db->Execute($sql);
		
		echo "OK";
	}elseif(isset($_POST['invitacion']) && isset($_POST['eliminarInvitacion'])){
		$sql="DELETE FROM ic_invitaciones_usuarios WHERE id_invitacion='".$_POST['invitacion']."';"; 	
		$result=$db->Execute($sql);
		
		echo "OK";
	}
}
?>