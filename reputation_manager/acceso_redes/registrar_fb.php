<?php
	session_start();
	
	$path = "../../adodb/adodb.inc.php";
	include ("../var.php");
	include ("../../conexion.php");
	include ("../funciones.php");
	
	$sql="SELECT us_login FROM ic_usuarios WHERE us_login='".$_POST['fb_id']."'";
	$result=$db->Execute($sql);	
	
	if(!$result->EOF){
	//Inicio de sesion, cuando el usuario ya existe
		$sql="SELECT 
				usu.us_id, usu.us_nombre, usu.us_email, grus.gru_id, gru.gru_titulo, usu.us_tipo, gru.us_imagen, usu.us_imagen
			FROM 
				ic_usuarios usu, ic_usu_gru grus, ic_grupo gru
			WHERE
				usu.us_login='".$_POST['fb_id']."' AND
				grus.id=usu.us_id AND
				gru.gru_id=grus.gru_id AND
				usu.us_estado='1'
			LIMIT 0,1";
		$result=$db->Execute($sql);
		list($usuario_id,$usuario_nom,$usuario_email,$usuario_gruid,$usuario_grutitulo,$tipo_usuario,$imagen_grupo,$imagen_usuario)=select_format($result->fields);
		
		$_SESSION['sess_usu_id']=$usuario_id;		
		$_SESSION['sess_nombre']=$usuario_nom;
		$_SESSION['sess_usu_email']=$usuario_email;
		$_SESSION['sess_usu_grupo']=$usuario_gruid;	
		$_SESSION['sess_usu_gru_nombre']=$usuario_grutitulo;
		$_SESSION['sess_usu_tipo']=$tipo_usuario;
		$_SESSION['sess_usu_img']=$imagen_usuario;
		$_SESSION['sess_usu_img_grupo']=$imagen_grupo;
		
		echo "OK-0";
	}else{
	//Registro e Inicio de sesion, cuando el usuario no existe, y despues del registro hace el login automatico
		$idio_pais = explode("_",$_POST['fb_pais']);
		$idio_pais[0] = "_".strtolower($idio_pais[0]);
		
		$result1=insert_bd_format("us_nombre,us_pais,us_email,us_login,us_pass,us_estado,us_imagen,us_idioma,us_tipo,us_fch_crea", 
								  "ic_usuarios", 
								  array($_POST['fb_nombre'],$idio_pais[1],$_POST['fb_mail'],$_POST['fb_id'],rand(),"1","https://graph.facebook.com/".$_POST['fb_id']."/picture?type=large",$idio_pais[0],"ADM_ETI",date('Y-m-d')), 
								  $db);
		
		////////////////////////////////////////////////
		
		$sql1_1="SELECT us_id FROM ic_usuarios WHERE us_email='".$_POST['fb_mail']."' "; 	
		$result1_1=$db->Execute($sql1_1);
		list($id_nuevo)=select_format($result1_1->fields);
		
		$sql="INSERT INTO ic_grupo (gru_titulo,gru_conten,gru_creacion) VALUES('".$_POST['fb_nombre']."','".$_POST['fb_nombre']." - ".$_POST['fb_id']."','".date('Y-m-d')."')"; 	
		$result2=$db->Execute($sql);
		
		////////////////////////////////////////////
		
		//Creacion de directorios de trabajo por defecto
		$sql="INSERT INTO ic_directorios (id_usuario,nombre,fecha_creacion,total_registros) VALUES('".$id_nuevo."','"._REPUTA_FAV."','".date('Y-m-d')."','0')"; 	
		$result0=$db->Execute($sql);
		$sql="INSERT INTO ic_directorios (id_usuario,nombre,fecha_creacion,total_registros) VALUES('".$id_nuevo."','"._REPUTA_PEN."','".date('Y-m-d')."','0')";
		$result0=$db->Execute($sql);
		
		////////////////////////////////////////////
		
		$sql1_2="SELECT gru_id FROM ic_grupo WHERE gru_titulo='".$_POST['fb_name']."' "; 	
		$result1_2=$db->Execute($sql1_2);
		list($id_nuevo_grupo)=select_format($result1_2->fields);
		
		$sql="INSERT INTO ic_usu_gru (gru_id,id) VALUES('".$id_nuevo_grupo."','".$id_nuevo."')"; 	
		$result2=$db->Execute($sql);
		
		//////////////////////////////////////////////
		
		$sql="SELECT 
				usu.us_id, usu.us_nombre, usu.us_email, grus.gru_id, gru.gru_titulo, usu.us_tipo, gru.us_imagen, usu.us_imagen
			FROM 
				ic_usuarios usu, ic_usu_gru grus, ic_grupo gru
			WHERE
				usu.us_login='".$_POST['fb_id']."' AND
				grus.id=usu.us_id AND
				gru.gru_id=grus.gru_id AND
				usu.us_estado='1'
			LIMIT 0,1";
		$result=$db->Execute($sql);
		list($usuario_id,$usuario_nom,$usuario_email,$usuario_gruid,$usuario_grutitulo,$tipo_usuario,$imagen_grupo,$imagen_usuario)=select_format($result->fields);
		
		$_SESSION['sess_usu_id']=$usuario_id;		
		$_SESSION['sess_nombre']=$usuario_nom;
		$_SESSION['sess_usu_email']=$usuario_email;
		$_SESSION['sess_usu_grupo']=$usuario_gruid;	
		$_SESSION['sess_usu_gru_nombre']=$usuario_grutitulo;
		$_SESSION['sess_usu_tipo']=$tipo_usuario;
		$_SESSION['sess_usu_img']=$imagen_usuario;
		$_SESSION['sess_usu_img_grupo']=$imagen_grupo;
			  
		echo "OK-1";
	}
?>