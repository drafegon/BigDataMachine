<?php  

	//$codigopais     = "AR";
	$maxresultados  = "50";
	$tipo           = "video";
	
	$fecha = date('Y-m-d H:i:s');
	$formato = strtotime ( '-12 hour' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'Y-m-j' , $formato );
	$nuevahora = date ( 'H' , $formato );
	
	$fechadespues     = "".$nuevafecha."T".$nuevahora.":00:00Z";
	//$fechadespues   = "".$nuevafecha."T00:00:00Z";
	
	
	$url  = "https://www.googleapis.com/youtube/v3/search?";
	$url .= "key=AIzaSyAxt1pSd6qneIn-msK3rgFKx4AzS4ePgew";
	$url .= "&q=coca+cola";
	$url .= "&part=snippet";
	//$url .= "&regionCode=".$codigopais;
	$url .= "&maxResults=".$maxresultados;
	$url .= "&type=".$tipo;
	$url .= "&safeSearch=moderate";
	//$url .= "&publishedAfter=".$fechadespues;
	//$url .= "&publishedBefor=".$fechaantes;
		 
		 
		 echo $url."<br><br>";
		 
	$stream_opts = array(
		'http'=>array(
			'method'=>"GET",
			'timeout'=>"60",
		)      
	);
	
	$context = stream_context_create($stream_opts);
	$result = file_get_contents($url, false, $context);
	
	$json = json_decode($result,true);
	
	echo "<pre>";
	var_dump($json);
	echo "</pre>";
	/*if(isset($json["error"])){
		foreach($json["error"]["errors"] as $p){
			echo "-".$p["message"]."<br>";
		}
	}else{			
		foreach($json["items"] as $p){	
			$title = addslashes(strip_tags(html_entity_decode(utf8_decode($p["snippet"]["title"]))));
			$descriptions = addslashes(strip_tags(html_entity_decode(utf8_decode($p["snippet"]["description"]))));
			$idvideo = addslashes(strip_tags(html_entity_decode(utf8_decode($p["id"]["videoId"])))); 
			
			echo $title."<br>";
			echo $descriptions."<br>";
			echo $idvideo."<br>";
		}
	}*/
		
?>