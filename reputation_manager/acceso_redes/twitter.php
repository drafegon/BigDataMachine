<?php
session_start();

//header ('Content-type: text/html; charset=utf-8');
$count=0;

$where_nueva2 = "";

if($etiqueta_nueva!=""){
	$where_nueva2 = " AND id = '".$etiqueta_nueva."'";
}

//----------------------------------------------------------

require_once('../../admin/twitteroauth/twitteroauth.php');

function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth("k4DiUszy9xrNTZc39kV2FkJ7b", "DDAzp6jN3ceY5hFVbD2bmLJK2zNa8cX3pcF03SflWkFp2Rwnty", $oauth_token, $oauth_token_secret);
  return $connection;
}
$connection = getConnectionWithAccessToken("1704935276-D7fhMCwcctTOETSzPI1o1ZfRETsyw9j698dn2KD", "9xdpnQq4Gtw8VpktaXRVaLKa3iE8XABHVonEBSzrpxNw2");
//$connection = getConnectionWithAccessToken("157208628-XRVQpFHYDB7ddymW6K2TJH0CvNmkBIf8dJwWlTZ4", "QzjhT2thVygnzJnaUwALiLYKD281bjvprG4AFxnAbsgdQ");
	/////////////////////////////////////////////////////
	 
	////////////////////////////////////////////////////////////
						
	//$queryTwitter = 'search/tweets.json?q=zuluaga%20lang:es&count=100&include_entities=1&result_type=recent';	
	//queryTwitter = 'search/tweets.json?q=zuluaga%20lang:es&count=100&include_entities=1&result_type=recent';	
	//echo $queryTwitter;
	
	$queryTwitter  = "search/tweets.json"; 
	$queryTwitter .= "?q=Las%20Violetas";
	//$queryTwitter .= "?q=coca cola filter:images";
	//$queryTwitter .= "&geocode=-38.959409,-64.248047,1000mi";
	//$queryTwitter .= "&mode=photos";
	//$queryTwitter .= "&track=photo";
	$queryTwitter .= "&result_type=recent";
	$queryTwitter .= "&count=100";	
	//$queryTwitter .= "&include_rts=1";	
	//$queryTwitter .= "&include_entities=1";
	echo $queryTwitter;	 
	
	$contenido = $connection->get($queryTwitter);
	
//$contenido = html_entity_decode  ($contenido);
//$contenido = htmlspecialchars_decode ($contenido);
//$contenido = htmlentities  ($contenido);
//$contenido = htmlspecialchars  ($contenido);
//$contenido = utf8_decode ($contenido);

	//$contenido = htmlentities($contenido, ENT_COMPAT, 'UTF-8');
	
	$jsonFinal = json_encode($contenido);	
	$jsonFinal = json_decode($jsonFinal);
	
	//$jsonFinal = htmlentities($jsonFinal, ENT_COMPAT, 'UTF-8');
	
	echo "<pre>";
	utf8_encode(var_dump($jsonFinal));
	echo "</pre>";
	
	/*
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	echo "<br>";
	echo "<hr>";
	echo "<hr>";
	echo "<br>";*/
	/*
	
	$queryTwitter  = "search/tweets.json";
	$queryTwitter .= "?q=zuluaga";
	$queryTwitter .= "&result_type=recent";
	$queryTwitter .= "&count=100";	
	//$queryTwitter .= "&until=2013-11-29";	
	//$queryTwitter .= "&until=2013-11-29";	
	
	echo $queryTwitter;
	
	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";
		
	////////////////////////////////////////////////////////////
						
	$queryTwitter = "search/tweets.json?max_id=406279374380752895&q=zuluaga&count=100&include_entities=1&result_type=recent";	
	echo $queryTwitter;
	
	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	
	////////////////////////////////////////////////////////////
						
	$queryTwitter = "search/tweets.json?max_id=406238149363261440&q=zuluaga&count=100&include_entities=1&result_type=recent";	
	echo $queryTwitter;
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	
	////////////////////////////////////////////////////////////
						
	$queryTwitter = "search/tweets.json?max_id=406212398438428671&q=zuluaga&count=100&include_entities=1&result_type=recent";	
	echo $queryTwitter;

	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	
	////////////////////////////////////////////////////////////
						
	$queryTwitter = "search/tweets.json?max_id=406183978974969855&q=zuluaga&count=100&include_entities=1&result_type=recent";	
	echo $queryTwitter;

	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	
	////////////////////////////////////////////////////////////
						
	$queryTwitter = "search/tweets.json?max_id=406156968613916671&q=zuluaga&count=100&include_entities=1&result_type=recent";	
	echo $queryTwitter;

	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	
	////////////////////////////////////////////////////////////
						
	$queryTwitter = "search/tweets.json?max_id=406131241620996095&q=zuluaga&count=100&include_entities=1&result_type=recent";	
	echo $queryTwitter;

	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	
	////////////////////////////////////////////////////////////
						
	$queryTwitter = "search/tweets.json?max_id=406088413301837823&q=zuluaga&count=100&include_entities=1&result_type=recent";	
	echo $queryTwitter;

	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";
	
	////////////////////////////////////////////////////////////
						
	$queryTwitter = "search/tweets.json?max_id=406029015750041600&q=zuluaga&count=100&include_entities=1&result_type=recent";	
	echo $queryTwitter;

	echo "<br>";
	$contenido = $connection->get($queryTwitter);
	$jsonFinal = json_encode($contenido);
	$jsonFinal = json_decode($jsonFinal, true);
	
	echo count($jsonFinal["statuses"]);
	echo "<br>";
	echo "<br>";
	
	foreach($jsonFinal["statuses"] as $p){
		echo $p["created_at"];
echo "<br>";
echo $p["id"];
		echo "<br>";
		echo $p["text"];
		echo "<br>";
		echo "<br>";
	}
	
	echo "<br>";
	echo "<br>";
	var_dump( $jsonFinal["search_metadata"] );
	echo "<br>";
	echo "<hr>";
	echo "<br>";*/
	
?>