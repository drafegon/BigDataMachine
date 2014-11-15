function nuevoAjax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo */
	var xmlhttp=false; 
	try 
	{ 
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP"); 
	}
	catch(e)
	{ 
		try
		{ 
			// Creacion del objet AJAX para IE 
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
		} 
		catch(E) { xmlhttp=false; }
	}
	if (!xmlhttp && typeof XMLHttpRequest!="undefined") { xmlhttp=new XMLHttpRequest(); } 

	return xmlhttp; 
}

//------------------------------------------------------------------------------------------------------------

function calificar(calificacion,coincidencia){
	var dirimg = "images/feb-2014/";
	
	var posi="";
	var nega="";
	var neutro="";
	var calificacion_posi="posi-i.fw.png";
	var calificacion_nega="nega-i.fw.png";
	var calificacion_neut="neut-i.fw.png";
	
	var posiActual=document.getElementById("P_"+coincidencia).alt;
	var negaActual=document.getElementById("N_"+coincidencia).alt;
	var neutroActual=document.getElementById("NE_"+coincidencia).alt;
		
	if(calificacion=="P"){
		
		nega="N";	
		neutro="N";	
		document.getElementById("N_"+coincidencia).alt="nega-i";
		document.getElementById("NE_"+coincidencia).alt="neut-i";
		
		if(/posi-i/.test(posiActual)){
			posi="S";
			calificacion_posi="posi-a.fw.png";	
			document.getElementById("P_"+coincidencia).alt="posi-a";	
		}else{
			posi="N";
			document.getElementById("P_"+coincidencia).alt="posi-i";
		}
	}else if(calificacion=="N"){
		
		posi="N";	
		neutro="N";	
		document.getElementById("P_"+coincidencia).alt="posi-i";
		document.getElementById("NE_"+coincidencia).alt="neut-i";
		
		if(/nega-i/.test(negaActual)){
			nega="S";
			calificacion_nega="nega-a.fw.png";	
			document.getElementById("N_"+coincidencia).alt="nega-a";
		}else{
			nega="N";
			document.getElementById("N_"+coincidencia).alt="nega-i";	
		}
	}else if(calificacion=="NE"){
		
		nega="N";	
		posi="N";	
		document.getElementById("P_"+coincidencia).alt="posi-i";
		document.getElementById("NE_"+coincidencia).alt="neut-i";
		
		if(/neut-i/.test(neutroActual)){
			neutro="S";
			calificacion_neut="neut-a.fw.png";	
			document.getElementById("NE_"+coincidencia).alt="neut-a";	
		}else{
			neutro="N";
			document.getElementById("NE_"+coincidencia).alt="neut-i";	
		}	
	}
		
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	document.getElementById("P_"+coincidencia).src=dirimg+calificacion_posi;
	document.getElementById("N_"+coincidencia).src=dirimg+calificacion_nega;
	document.getElementById("NE_"+coincidencia).src=dirimg+calificacion_neut;
	
	
	$.ajax({
		url: 'reputation_manager/ajax_calificar.php',
		async:true,   
		cache:false,  
		dataType:"html",
		type: 'POST', 
		data: { positivo: posi, negativo: nega, neutra: neutro, id: coincidencia },
			success: function(datos_recibidos) {				
				
		}
	});
}

//------------------------------------------------------------------------------------------------------------

function marcar(coincidencia, marca){
	var estado_marca=marca;	
	var imgi = "images/feb-2014/ranking-i.fw.png";
	var imga = "images/feb-2014/ranking-a.fw.png";
	if(estado_marca=="1"){
		if(/ranking-a/.test(document.getElementById("marca1_"+coincidencia).alt)){
			document.getElementById("marca1_"+coincidencia).src=imgi;
			document.getElementById("marca2_"+coincidencia).src=imgi;
			document.getElementById("marca3_"+coincidencia).src=imgi;
			
			document.getElementById("marca1_"+coincidencia).alt="ranking-i";	
			document.getElementById("marca2_"+coincidencia).alt="ranking-i";	
			document.getElementById("marca3_"+coincidencia).alt="ranking-i";	
			
			estado_marca = "0";
		}else{
			document.getElementById("marca1_"+coincidencia).src=imga;
			document.getElementById("marca2_"+coincidencia).src=imgi;
			document.getElementById("marca3_"+coincidencia).src=imgi;
			
			document.getElementById("marca1_"+coincidencia).alt="ranking-a";	
			document.getElementById("marca2_"+coincidencia).alt="ranking-i";	
			document.getElementById("marca3_"+coincidencia).alt="ranking-i";
		}		
	}else if(estado_marca=="2"){
		document.getElementById("marca1_"+coincidencia).src=imga;
		document.getElementById("marca2_"+coincidencia).src=imga;
		document.getElementById("marca3_"+coincidencia).src=imgi;
		
		document.getElementById("marca1_"+coincidencia).alt="ranking-a";	
		document.getElementById("marca2_"+coincidencia).alt="ranking-a";	
		document.getElementById("marca3_"+coincidencia).alt="ranking-i";
	}else if(estado_marca=="3"){
		document.getElementById("marca1_"+coincidencia).src=imga;
		document.getElementById("marca2_"+coincidencia).src=imga;
		document.getElementById("marca3_"+coincidencia).src=imga;
		
		document.getElementById("marca1_"+coincidencia).alt="ranking-a";	
		document.getElementById("marca2_"+coincidencia).alt="ranking-a";	
		document.getElementById("marca3_"+coincidencia).alt="ranking-a";
	}
			
	$.ajax({
      url: 'reputation_manager/ajax_calificar.php',
	  async:true,   
	  cache:false,  
	  dataType:"html",
	  type: 'POST', 
	  data: { marca: estado_marca, id: coincidencia },
      success: function(datos_recibidos) {
		if(estado_marca=="1"){
			document.getElementById("marca1_"+coincidencia).src=imga;
			document.getElementById("marca2_"+coincidencia).src=imgi;
			document.getElementById("marca3_"+coincidencia).src=imgi;
		}else if(estado_marca=="2"){
			document.getElementById("marca1_"+coincidencia).src=imga;
			document.getElementById("marca2_"+coincidencia).src=imga;
			document.getElementById("marca3_"+coincidencia).src=imgi;
		}else if(estado_marca=="3"){
			document.getElementById("marca1_"+coincidencia).src=imga;
			document.getElementById("marca2_"+coincidencia).src=imga;
			document.getElementById("marca3_"+coincidencia).src=imga;
		}
      }
   });
}

//------------------------------------------------------------------------------------------------------------

function mostrarTablaIntervenidos(coincidencia){
	
	var tabla_detalle = document.getElementById("tabla_intervenidos_"+coincidencia);
	if(tabla_detalle.style.display=="none"){
		tabla_detalle.style.display="inline";
	}else{
		tabla_detalle.style.display="none";
	}
}

//------------------------------------------------------------------------------------------------------------

function intervenida(coincidencia){
	var ajax=nuevoAjax();
	
	var intervenida = document.getElementById("valor_intervenida_"+coincidencia).value;
	var detalle_intervenida = document.getElementById("detalle_intervenido_"+coincidencia).value;
	var estado_intervenida="";
	
	if(intervenida=="S" && detalle_intervenida==""){
		estado_intervenida="N";
	}else if(detalle_intervenida!=""){
		estado_intervenida="S";
	}
	
	ajax.open("POST", "reputation_manager/ajax_calificar.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("intervenida="+estado_intervenida+"&detalle_intervenida="+detalle_intervenida+"&id="+coincidencia);
	
	if (ajax.readyState==4)
	{
		if(ajax.responseText)
		{
			//alert("Intervencion Registrada");
			if(estado_intervenida=="S"){
				document.getElementById("I_"+coincidencia).src="images/intervenidos.jpg";
				document.getElementById("valor_intervenida_"+coincidencia).value="S";
			}else{
				document.getElementById("I_"+coincidencia).src="images/no_intervenidos.jpg";
				document.getElementById("valor_intervenida_"+coincidencia).value="N";
			}	
			
			document.getElementById("tabla_intervenidos_"+coincidencia).style.display="none";
		}else{
			alert("Error");
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function bloqueada(coincidencia, mostrarAviso){
	var bloqueo = document.getElementById("valor_bloqueo_"+coincidencia).value;
	var estado_bloqueo="";
	
	if(bloqueo=="S"){
		estado_bloqueo="N";
	}else{
		estado_bloqueo="S";
	}
	
	if(estado_bloqueo=="S"){
		document.getElementById("valor_bloqueo_"+coincidencia).value="S";
		document.getElementById("imgbloq_"+coincidencia).src="images/feb-2014/bloq-a.fw.png";
	}else{
		document.getElementById("valor_bloqueo_"+coincidencia).value="N";
		document.getElementById("imgbloq_"+coincidencia).src="images/feb-2014/bloq-i.fw.png";
	}	
	
	$.ajax({
	  url: 'reputation_manager/ajax_calificar.php',
	  async:true,   
	  cache:false,  
	  dataType:"html",
	  type: 'POST', 
	  data: { bloqueada: estado_bloqueo, id: coincidencia },
	  success: function(datos_recibidos) {
		  //alert(datos_recibidos);
	  }
	});	
}

//------------------------------------------------------------------------------------------------------------

function eliminar(coincidencia, mostrarAviso){	
	
	if(coincidencia!=""){
		var seguro = confirm("Esta seguro que desea eliminar el resultado?");
		if(seguro){
			$("#elemento"+coincidencia).fadeOut();
			
			var id_registro=$("#id_"+coincidencia).val();
			var id_etiqueta=$("#etiqueta").val();
			var id_rss=$("#rss_"+coincidencia).val();
			var cargue=$("#cargue_"+coincidencia).val();
			var categoria=$("#categ_"+coincidencia).val();
			var usuario_cliente=$("#usuario_cliente").val();
			
			$.ajax({
				url: 'reputation_manager/ajax_calificar.php',
				async:true,   
				cache:false,  
				dataType:"html",
				type: 'POST', 
				data: { eliminar: 'S', id: id_registro, etiqueta:id_etiqueta, rss:id_rss, carg:cargue, cate:categoria, usua:usuario_cliente  },
				success: function(datos_recibidos) {	
					refrescar_pantalla1(document.getElementById("etiqueta").value);		
				}
			});
		}
	}else{
		var inicio = document.getElementById("inicio").value;
		var fin = document.getElementById("final").value;

		var id_etiqueta=$("#etiqueta").val();
		var usuario_cliente=$("#usuario_cliente").val();
		
		var id_rss="";
		var cargue="";
		var categoria="";
		var id_registro="";
				
		for(var i=inicio; i<=fin; i++){			
			if(document.getElementById("seleccion_"+i)){				
				if(document.getElementById("seleccion_"+i).checked==true){					
					id_registro += $("#seleccion_"+i).val() + ",";
					
					id_rss+=$("#rss_"+$("#seleccion_"+i).val()).val() + ",";
					cargue+=$("#cargue_"+$("#seleccion_"+i).val()).val() + ",";
					categoria+=$("#categ_"+$("#seleccion_"+i).val()).val() + ",";
				}
			}
		}
		
		if(id_registro!=""){
			id_registro += "X";
			id_rss += "X";
			cargue += "X";
			categoria += "X";
					
			var seguro = confirm("Esta seguro que desea eliminar los resultados?");
			
			if(seguro){
				var seleccionados = id_registro.split(",");
				
				for(var i=0; i<=seleccionados.length; i++){			
					if(document.getElementById("elemento"+seleccionados[i])){				
							$("#elemento"+seleccionados[i]).remove();						
					}
				}
		
				$.ajax({
					url: 'reputation_manager/ajax_calificar.php',
					async:true,   
					cache:false,  
					dataType:"html",
					type: 'POST', 
					data: { eliminar: 'S', id: id_registro, etiqueta:id_etiqueta, rss:id_rss, carg:cargue, cate:categoria, usua:usuario_cliente },
					success: function(datos_recibidos) {
						refrescar_pantalla1(document.getElementById("etiqueta").value);
					}
				});
			}
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function marcasMultiples(parametro){
	var inicio = document.getElementById("inicio").value;
	var final = document.getElementById("final").value;
	
	if(parametro!=""){		
		for(i=inicio; i<=final; i++){
			if(document.getElementById("seleccion_"+i)){
				if(document.getElementById("seleccion_"+i).checked==true){
					
					var seleccionados = document.getElementById("seleccion_"+i).value;
					
					if(parametro==1){
						marcar(seleccionados, false);
					}
					
					if(parametro==2){
						bloqueada(seleccionados, false);
					}
					
					if(parametro==3){
						eliminar(seleccionados, false);
					}
					
					if(parametro==4){
						calificar('P',seleccionados);
					}
					
					if(parametro==5){
						calificar('NE',seleccionados);
					}
					
					if(parametro==6){
						calificar('N',seleccionados);
					}
				}
			}
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function marcasMultiplesEnviar(){
	var inicio = document.getElementById("inicio").value;
	var fin = document.getElementById("final").value;
	var seleccionados = "";	
	
	for(var i=inicio; i<=fin; i++){
		
		if(document.getElementById("seleccion_"+i)){
			
			if(document.getElementById("seleccion_"+i).checked==true){
				
				seleccionados += document.getElementById("seleccion_"+i).value + ",";
			}
		}
	}
	
	if(seleccionados!=""){
		$("#coincidenciasenviar").val(seleccionados);
		$("#enviara").submit();
	}
}

//------------------------------------------------------------------------------------------------------------

function marcaCompartir(redSocial){
	var inicio = document.getElementById("inicio").value;
	var fin = document.getElementById("final").value;
	var seleccionados = "";	
	
	for(var i=inicio; i<=fin; i++){
		
		if(document.getElementById("seleccion_"+i)){
			
			if(document.getElementById("seleccion_"+i).checked==true){
				
				seleccionados += document.getElementById("seleccion_"+i).value;
				break;
			}
		}
	}
	
	if(seleccionados!=""){
		if(redSocial=="facebook"){
			var links = document.getElementById("lin_"+seleccionados).value;
			window.open('https://www.facebook.com/sharer/sharer.php?u='+links+'&display=popup','Facebook','height=400,width=600');
		}
		if(redSocial=="twitter"){
			var links = document.getElementById("lin_"+seleccionados).value;
			var titulo = document.getElementById("tit_"+seleccionados).value;
			window.open('https://twitter.com/intent/tweet?text='+titulo+'&url='+links+'.twitter&related=','Facebook','height=400,width=600');
		}
	}else{
		alert("Debe seleccionar un comentario para compartir");
	}
}

//------------------------------------------------------------------------------------------------------------

function directorioMultiples(directorio){
	var inicio = document.getElementById("inicio").value;
	var fin = document.getElementById("final").value;
	
	if(directorio!=""){
		var seleccionados = "";
		
		for(var i=inicio; i<=fin; i++){			
			if(document.getElementById("seleccion_"+i)){				
				if(document.getElementById("seleccion_"+i).checked==true){					
					seleccionados += document.getElementById("seleccion_"+i).value+",";  
				}
			}
		}
			
		asignarDirectorios(seleccionados, directorio);
	}
}

//------------------------------------------------------------------------------------------------------------

function asignarDirectorios(seleccionados, directorio){
	$.ajax({
		url: 'reputation_manager/ajax_calificar.php',
		async:false,   
		cache:false,  
		dataType:"html",
		type: 'POST', 
		data: { asig_dir: "S", ids: seleccionados, dir: directorio },
		success: function(datos_recibidos) {
			var registros = seleccionados.split(",");
			
			for(var i=0; i<registros.length; i++){
				if(registros[i]>0){						
					if(/NO/.test(datos_recibidos)){
						$("#dirteca"+registros[i]).html("");
					}else if(!/NO/.test(datos_recibidos)){
						$("#dirteca"+registros[i]).html(datos_recibidos);						
					}else{
						alert("Error");
					}
				}
			}
		}
	});			
}

//------------------------------------------------------------------------------------------------------------

function adicionarDirectorio(){
	var dir = $("#new_dir").val();
	if(dir!=""){
		var ajax=nuevoAjax();
		ajax.open("POST", "reputation_manager/ajax_calificar.php?", false);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("new_dir=S&text="+dir);
		
		if (ajax.readyState==4)
		{
			if(ajax.responseText)
			{
				$("#listDir").append("<div id='dir"+ajax.responseText+"' class='droppableDir'>"+
				                     "<div class='columna imgDir'><a href='javascript:;' onclick=\"directorioMultiples('"+ajax.responseText+"');\" style=\"color: #fff;\">"+dir+"</a></div>" +
									 "<div class='columna' style='float: right;'><a href='javascript:;' onclick=\"eliminarDirectorio('"+ajax.responseText+"');\"><img src='images/menos.fw.png' alt='deldir'/></a></div>" +
                    	   			 "<div class='separadorCasillas0'></div>"+
									 "</div>");
						   
				$("#new_dir").val("");
			}else{
				alert("Error");
				$("#new_dir").val("");
			}
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function eliminarDirectorio(id){
	var seguro = confirm("Esta seguro que desea eliminar este directorio?");
	
	if(seguro){
		if(id!=""){
			var ajax=nuevoAjax();
			ajax.open("POST", "reputation_manager/ajax_calificar.php?", false);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.send("del_dir=S&id="+id);
			
			if (ajax.readyState==4)
			{
				if(ajax.responseText)
				{
					$("#dir"+id).remove();	
					window.location.reload();   
				}else{
					alert("Error");
				}
			}
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function seleccionar(cuantas){
	var inicio = document.getElementById("inicio").value;
	var fin = document.getElementById("final").value;
	
	for(var i=inicio; i<=fin; i++){
		if(document.getElementById("seleccion_"+i)){				
			if(cuantas){
				document.getElementById("seleccion_"+i).checked=true;
			}
			if(!cuantas){
				document.getElementById("seleccion_"+i).checked=false;
			}
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function mostrarContenido(categoria,idItem){
	var id1 = "#tit_"+idItem;
	var id2 = "#cont_"+idItem;
	var id3 = "#lin_"+idItem;
	var id4 = "#rss_"+idItem;
	var id5 = "#id_"+idItem;
	var id6 = "#source_"+idItem;
	var id7 = "#feed_"+idItem;
	var id8 = "#media_"+idItem;
	
	var titulo = $(id1).val();
	var contenido = $(id2).val();
	var links = $(id3).val();
	var id_rss = $(id4).val();
	var id = $(id5).val();
	var source = $(id6).val();
	var detalle_intervenido = $(id7).val();
	var media = $(id8).val();
	
	if(categoria=="2" || /plus.google.com/.test(links)){
		document.getElementById("paginaWeb").style.display="none";
		document.getElementById("linkContenido").style.display="none";
		document.getElementById("miniContenido").style.display="inline";
		
		var html =  '<br><div style="background: #f4f4f4; padding: 20px;" class="redondeado">'+
					'<span class="titulo">'+titulo+'</span>'+
					'<br /><br />'+
					'<div style="color:#999999; font-size: 14px;">'+contenido+'</div>'+
					'<br />'+
					'<div><a href="'+links+'" target="_blank">'+links+'</a></div>'+
					'<div>'+media+'</div>'+
					'</div>';
		
		if(detalle_intervenido!=""){
			html += '<br><div style="background: #f4f4f4; padding:10px;" class="redondeado">'+
					'<div style="color:#999999; font-size: 14px;"><b>Feedback:</b> '+detalle_intervenido+'</div>'+
			        '</div>';
		}
		
		if(id_rss=="438"){	
			var contestar =	'<br><a href="javascript:;" onclick="responderTwitter(\''+id+'\',\''+source+'\',\'validar\');">Responder (Twitter)</a>'+
			                '<div id="mensajetw"></div>'+
							'<div id="alertaTw" onclick="$(this).fadeOut();"></div>';
							
			html = html + contestar;	
		}
		
		$("#miniContenido").html(html);
	}else{
		document.getElementById("miniContenido").style.display="none";
		document.getElementById("paginaWeb").style.display="inline";
		document.getElementById("linkContenido").style.display="inline";
		
		$("#linkContenido").html('<div><a href="'+links+'" target="_blank">Ir al sitio web</a></div><br>');
		
		document.getElementById("paginaWeb").src=links;
	}
}

//------------------------------------------------------------------------------------------------------------

function responderTwitter(id,source,funcion){
	
	$.ajax({
		url: 'reputation_manager/ajax_contestar_mensaje.php',
		async:true,   
		cache:false,  
		dataType:"html",
		type: 'POST', 
		data: { validar: 1},
		success: function(datos_recibidos) {
			if(/OK/.test(datos_recibidos)){
				var casilla = '<br><div id="area_respuesta" style="display: none; text-align: right;">'+
							  '<textarea id="textTwitter" name="textTwitter" style="width: 97%; height: 50px;"></textarea>'+
							  '<br><a href="javascript:;" onclick="javascript:pubTwitter(\''+id+'\',\''+source+'\',\'contestar\');" style="margin: 5px 0;">Responder &raquo;</a></div>';
							  
				$("#mensajetw").html(casilla);
				$("#area_respuesta").fadeIn();
			}else{
				$("#alertaTw").html(datos_recibidos);
				$("#alertaTw").fadeIn();
			}
		}
	});	
}

//------------------------------------------------------------------------------------------------------------

function pubTwitter(idElemento, sourceFuente, funcion){
	var mens = $("#textTwitter").val();
	
	$.ajax({
		url: 'reputation_manager/ajax_contestar_mensaje.php',
		async:true,   
		cache:false,  
		dataType:"html",
		type: 'POST', 
		data: { validar: 0, mensaje: mens, id: idElemento, source: sourceFuente},
		success: function(datos_recibidos) {
				
			if(/OK/.test(datos_recibidos)){
				$("#alertaTw").html("Mensaje enviado al usuario.");
				$("#alertaTw").fadeIn();
				
				setTimeout('$("#alertaTw").fadeOut();', 3000);
			}else{
				alert("Error: "+datos_recibidos);
			}
		}
	});
}

//------------------------------------------------------------------------------------------------------------

function reset_fechas(){
	document.getElementById("dia1").value="";
	document.getElementById("mes1").value="";
	document.getElementById("ano1").value="";
	
	document.getElementById("dia2").value="";
	document.getElementById("mes2").value="";
	document.getElementById("ano2").value="";
}

//------------------------------------------------------------------------------------------------------------

function mybigdateca(){

	if(document.getElementById("mybigdateca").style.display!="none"){
		$("#mybigdateca").fadeOut();	
	}else{
		$("#aplicaciones").fadeOut();
		$("#filtros_form").fadeOut();
		$("#mybigdateca").fadeIn();	
	}
}

//------------------------------------------------------------------------------------------------------------

function aplicaciones(){

	if(document.getElementById("aplicaciones").style.display!="none"){
		$("#aplicaciones").fadeOut();	
	}else{
		$("#mybigdateca").fadeOut();
		$("#filtros_form").fadeOut();
		$("#aplicaciones").fadeIn();	
	}
}

//------------------------------------------------------------------------------------------------------------

function filtros_form(){

	if(document.getElementById("filtros_form").style.display!="none"){
		$("#filtros_form").fadeOut();	
	}else{
		$("#mybigdateca").fadeOut();
		$("#aplicaciones").fadeOut();
		$("#filtros_form").fadeIn();	
	}
}

//------------------------------------------------------------------------------------------------------------

function eliminarUsuarioCuenta(id){
	var seguro = confirm("Esta seguro que desea eliminar el usuario? Si lo elimina no podrá invitarlo de nuevo con el mismo email.");
	
	if(seguro){
		var ajax=nuevoAjax();
		
		ajax.open("POST", "reputation_manager/ajax_calificar.php?", false);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("usuarios_invitados=true&us_id="+id+"&eliminar=true");
		
		if (ajax.readyState==4)
		{
			if(ajax.responseText)
			{
				window.location.reload();
			}else{
				alert("Error");
			}
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function eliminarInvitacion(id){
	var seguro = confirm("Esta seguro que desea eliminar la invitacion?.");
	
	if(seguro){
		var ajax=nuevoAjax();
		
		ajax.open("POST", "reputation_manager/ajax_calificar.php?", false);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		ajax.send("usuarios_invitados=true&invitacion="+id+"&eliminarInvitacion=true");
		
		if (ajax.readyState==4)
		{
			if(ajax.responseText)
			{
				window.location.reload();
			}else{
				alert("Error");
			}
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function permisoUsuarioCuenta(permiso,id){

	var ajax=nuevoAjax();
	
	ajax.open("POST", "reputation_manager/ajax_calificar.php?", false);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.send("usuarios_invitados=true&permiso="+permiso+"&us_id="+id);
	
	if (ajax.readyState==4)
	{
		if(ajax.responseText){
			window.location.reload();
		}else{
			alert("Error");
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function seleccionarPaises(){
	for(var i=0; i<300; i++){
		if(document.getElementById("paises_"+""+i+"")){
			document.getElementById("paises_"+""+i+"").checked = false;
		}
	}
}

//------------------------------------------------------------------------------------------------------------

function seleccionarUno(seleccion){
	for(var i=0; i<300; i++){
		if(document.getElementById("paises_"+""+i+"") && seleccion!=i){
			document.getElementById("paises_"+""+i+"").checked = false;
		}
	}
	
	if(document.getElementById("paises_"+""+seleccion+"").checked === false){
		document.getElementById("todospaises").checked = true;
	}else{
		document.getElementById("todospaises").checked = false;
	}
}

//------------------------------------------------------------------------------------------------------------

function seleccionarIdioma(seleccion){
		
	if(seleccion=="all"){
		document.getElementById("idioes").checked = false;
		document.getElementById("idioen").checked = false;
	}else{
		document.getElementById("idioall").checked = false;
	}
}

//------------------------------------------------------------------------------------------------------------

function verificarRedes(tw,fb){
	
	$.ajax({
		url: 'reputation_manager/ajax_verifica_redes.php',
		async:true,   
		cache:false,  
		dataType:"html",
		type: 'POST', 
		data: { existeFb: fb, existeTw: tw },
			success: function(datos_recibidos) {
				
			if(/REFRESCAR/.test(datos_recibidos)){
				window.location.reload();
				return;
			}
		}
	});
}

//------------------------------------------------------------------------------------------------------------

function refrescar_contenidos(etiquetaActual){
	$.ajax({
      url: 'reputation_manager/ajax_control_pantalla.php',
	  async:true,   
	  cache:false,  
	  dataType:"html",
	  type: 'POST', 
	  data: { funcion: "select", etiqueta: etiquetaActual },
      success: function(datos_recibidos) {
		 if(!/NO/.test(datos_recibidos)){
			 
			 datos_recibidos = datos_recibidos.split("|");
			 
			 var contenido = $("#htmlSelect").html();
			 
			 $("#htmlSelect").hide();
			 $("#htmlSelect").html(datos_recibidos[0]);
			 $("#htmlSelect").fadeIn();
			 
			 if(frames.canvas.document.getElementById("aviso_nuevos")){
				 $("#canvas").contents().find("#aviso_nuevos").fadeIn();
			 }	
			 
			construirMenu(etiquetaActual);	 
		 }
      }
   });	
     
   setTimeout(function () { refrescar_contenidos(etiquetaActual); }, 1800000);
}

//------------------------------------------------------------------------------------------------------------

function refrescar_pantalla1(etiquetaActual){
	$.ajax({
      url: 'reputation_manager/ajax_control_pantalla.php',
	  async:true,   
	  cache:false,  
	  dataType:"html",
	  type: 'POST', 
	  data: { etiqueta: etiquetaActual },
      success: function(datos_recibidos) {
		 if(!/NO/.test(datos_recibidos)){
			 
			datos_recibidos = datos_recibidos.split("|");
			
			$('#htmlSelect', window.parent.document).hide();
			$("#htmlSelect", window.parent.document).html(datos_recibidos[0]);
			$('#htmlSelect', window.parent.document).fadeIn();
			
			if(document.getElementById("cantidadNuevos")){
				$('#cantidadNuevos').hide();
				$("#cantidadNuevos").html(datos_recibidos[1]);
				$('#cantidadNuevos').fadeIn();		 
			}
			
			construirMenu(etiquetaActual);	
		 }
      }
   });		   
}

//------------------------------------------------------------------------------------------------------------

function refrescar_tiemporeal(usuario_session_enviado, etiquetaActual){
	//INICIO LA BUSQUEDA EN LAS REDES SOCIALES.
	$.ajax({
      url: 'admin/lecturas_bigdata/php_leer_tw_fb.php',
	  async:true,   
	  cache:false,  
	  dataType:"html",
	  type: 'POST', 
	  data: { usuario_sesion: usuario_session_enviado },
      success: function(datos_recibidos) {
		 if(!/0/.test(datos_recibidos)){
			$.ajax({
			  url: 'reputation_manager/ajax_control_pantalla.php',
			  async:true,   
			  cache:false,  
			  dataType:"html",
			  type: 'POST', 
			  data: { funcion: "select", etiqueta: etiquetaActual },
			  success: function(datos_recibidos) {
				 if(!/NO/.test(datos_recibidos)){
					 
					 datos_recibidos = datos_recibidos.split("|");
					 
					 var contenido = $("#htmlSelect").html();
					 
					 $("#htmlSelect").hide();
					 $("#htmlSelect").html(datos_recibidos[0]);
					 $("#htmlSelect").fadeIn();
												 
					 if(frames.canvas.document.getElementById("aviso_nuevos")){
						 $("#canvas").contents().find("#aviso_nuevos").fadeIn();
					 }	
					 
					 construirMenu(etiquetaActual);						 			 
				 }
			  }
		   });	
		 }
      }
   });	 
   
   setTimeout(function () { refrescar_tiemporeal(usuario_session_enviado, etiquetaActual); }, 70000);   
}

//------------------------------------------------------------------------------------------------------------

function refrescar_busquedas(sitiosweb,etiquetaEnviada,tipoCargue){
	sitiosweb = sitiosweb.split(",");
	var urlredes = "";
	var urlgoogle = "";
		
	for(i=0; i<sitiosweb.length; i++){
		
		if(sitiosweb[i]=="2"){
			urlredes = "admin/lecturas_online/php_leer_redes.php";
		}
		
		if(sitiosweb[i]=="4"){
			urlgoogle = "admin/lecturas_online/php_leer_google.php";
		}
	}
	setTimeout(function() {
		if(urlredes!=""){
			llarmarrefresco(urlredes,etiquetaEnviada,tipoCargue,"433");	
			
			setTimeout(function() {		
				if(sitiosweb.length>1 && urlgoogle!=""){
					llarmarrefresco(urlredes,etiquetaEnviada,tipoCargue,"439");	
				}
			},3000);		
		}
			
		setTimeout(function() {		
			if(sitiosweb.length>1 && urlgoogle!=""){
				llarmarrefresco(urlgoogle,etiquetaEnviada,tipoCargue,"");	
			}
		},5000);
	},3000);
	
	
}

//------------------------------------------------------------------------------------------------------------

function llarmarrefresco(url,etiquetaEnviada,tipoCargue,codigosRedes){
	$.ajax({
		url: url,
		async:true,   
		cache:false,  
		dataType:"html",
		type: 'POST', 
		data: { etiqueta: etiquetaEnviada, modo: "diario", tipo_cargue: tipoCargue, red: codigosRedes },
		success: function(datos_recibidos) {			 
			$.ajax({
			  url: 'reputation_manager/ajax_control_pantalla.php',
			  async:true,   
			  cache:false,  
			  dataType:"html",
			  type: 'POST', 
			  data: { funcion: "select", etiqueta: etiquetaEnviada },
			  success: function(datos_recibidos) {
				 if(!/NO/.test(datos_recibidos)){
					 
					 datos_recibidos = datos_recibidos.split("|");
					 
					 var contenido = $("#htmlSelect").html();
					 
					 $("#htmlSelect").hide();
					 $("#htmlSelect").html(datos_recibidos[0]);
					 $("#htmlSelect").fadeIn();
					
					 construirMenu(etiquetaEnviada);	
					
					 if(frames.canvas.document.getElementById("aviso_nuevos")){
						 $("#canvas").contents().find("#aviso_nuevos").fadeIn();
					 }				 				 
				 }
			  }
		   });		
		}
		});	
}

//------------------------------------------------------------------------------------------------------------

														 
function masRegistros(etiquetaD,semana_seleccionadaD,buscarFuenteD,fecha_desdeD,fecha_hastaD,
                      tipo_seleccionadaD,accion_seleccionadaD,fecha_seleccionadaD,webD,rss_idD){
	
	var pagD = $("#paginaActual").val();
	var final = $("#final").val();
	$("#elementosPag"+pagD).fadeIn();
	
	if ($('#noMas'+pagD).length){	
		$("#moreResults").fadeOut();
	}else{	
		$.ajax({
			url: 'reputation_manager/ajax_paginacion.php',
			async:true,   
			cache:false,  
			dataType:"html",
			type: 'POST', 
			data: { pag: (parseInt(pagD)+1), etiqueta: etiquetaD, buscarFuente: buscarFuenteD, fecha_seleccionada: fecha_seleccionadaD, web: webD, semana_seleccionada: semana_seleccionadaD, fecha_desde: fecha_desdeD, fecha_hasta: fecha_hastaD, tipo_seleccionada: tipo_seleccionadaD, accion_seleccionada: accion_seleccionadaD, rss_id: rss_idD},
			success: function(datos_recibidos) {
									
				$("#elementosPag"+pagD).fadeOut();
				
				setTimeout(function () { 
					$("#elementosPag"+pagD).html(datos_recibidos);
					$("#elementosPag"+pagD).fadeIn();
				}, 300);
				
				$("#final").val(20*(parseInt(pagD)+1));		
				$("#paginaActual").val(parseInt(pagD)+1);
			}
		});
	}
}