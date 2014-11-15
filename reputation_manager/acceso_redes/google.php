<?php
$pagina_google = 1;
$cx="006978470473032883699%3Asuxpyuseuxk";
$key="AIzaSyDHJCGxfKb_EPhlpB7__67gB-GDPVDduX4";

$lang = "";
$language = "";


$count_paginas = 1;


for($i=0;$i<$count_paginas;$i++){
	$url = "https://www.googleapis.com/customsearch/v1";
	$url .= "?q=coca+cola+more:pagemap";	
	$url .= "&cx=".$cx."";
	$url .= "&key=".$key."";
	$url .= "&alt=json";
	$url .= "&num=10";
	$url .= "&searchType=image";
	//$url .= "&siteSearchFilter=i"; 
	$url .= "&dateRestrict=h23";
	//$url .= "&sort=date";
	$url .= "&start=".$pagina_google."".$lang."";
	$accesos_google++;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER, "https://www.googleapis.com/");
	$body = curl_exec($ch);
	curl_close($ch);
	
	$json = json_decode($body,true);
	
	echo "<pre>";
	var_dump( $json );
	echo "</pre>";
	/*if(isset($json["error"])){
		foreach($json["error"]["errors"] as $p){
			$lineas .= "-".$p["message"]."<br>";
		}
	}else{
		if($json["searchInformation"]["totalResults"]!="0"){
			foreach($json["items"] as $p){				
			
				$title = addslashes(strip_tags(html_entity_decode(utf8_decode($p["title"]))));
				$descriptions = addslashes(strip_tags(html_entity_decode(utf8_decode($p["snippet"]))));

			}
		}else{
			$i = $count_paginas;
		}
	}*/
	
	$pagina_google = $pagina_google + 10;
}
?>