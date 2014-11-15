<?php
include "../cabecera.php";

$sql="SELECT n_id,l_lang,n_titulo,n_des,n_text,n_fecha,n_imagen
	  FROM ic_noticias WHERE l_lang='".$_SESSION['idioma']."' AND n_categoria='6' ORDER BY n_fecha DESC LIMIT 0,15";
$result_all=$db->Execute($sql);

/////////////////////////////////////////////////////

$sql="SELECT mensaje,fecha,automatico FROM ic_mensajes WHERE id_usuario='".$_SESSION['bdm_user']['us_id']."' ORDER BY fecha DESC LIMIT 0,50";
$result_msg=$db->Execute($sql);
	
?>

<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;"><?=_REPUTA_NOTAS?></div>
<br />
<div style="margin: 0 50px 10px 50px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" width="49%">  
    
    <div class="mas_titulo" style="margin: 0 0 10px 0; color:#FA5225; font-size:20px;"><?=_BDM_HITOS?></div>  
   	<div style="width: 100%; border-bottom: 1px solid #ccc;"></div>
    <br />         
              
<?php  
	while(!$result_all->EOF){
		list($n_id,$l_lang,$n_titulo,$n_des,$n_text,$n_fecha,$n_imagen)=select_format($result_all->fields);
?>
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td>(<?=date('d/m/Y',strtotime($n_fecha))?>) <h2 style="color: #666"><?=utf8_decode($n_titulo)?></h2></td>
                </tr>
                <tr>
                  <td align="center"><img src="http://www.bigdatamachine.net/<?=($n_imagen=="")?"images/spacer.gif":$n_imagen?>" alt="<?=$n_titulo?>" width="300"/></td>
                </tr>                
                <tr>
                  <td><?=$n_des?></td>
                </tr>
                <tr>
                  <td><a href="http://www.bigdatamachine.net/novedades.php/nota/<?=$n_id?>/<?=$n_titulo?>" target="_blank"><?=_LEER_MAS?></a></td>
                </tr>
              </table>    
              <br />    
              <div style="width: 100%; border-bottom: 1px solid #ccc;"></div>
              <br />
              <?php 
		$result_all->MoveNext();
	}
?>
	</td>
    <td></td>
    <td valign="top" width="49%">
<div class="mas_titulo" style="margin: 0 0 10px 0; color:#FA5225; font-size:20px;"><?=_BDM_MSGBDM?></div>  
   	<div style="width: 100%; border-bottom: 1px solid #ccc;"></div>
    <br />         
              
<?php  
	while(!$result_msg->EOF){
		list($mensaje,$fecha,$automatico)=select_format($result_msg->fields);
?>
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td>
                  <?php if($automatico=="N"){ ?>
                  <span style="color:#F30; font-size:16px;">(<?=date('d/m/Y',strtotime($fecha))?>) <?=$mensaje?></span>
                  <?php }else{ ?>
                  (<?=date('d/m/Y',strtotime($fecha))?>) <?=$mensaje?>
                  <?php } ?>
                  </td>
                </tr>                
              </table>    
              <br />    
              <div style="width: 100%; border-bottom: 1px solid #ccc;"></div>
              <br />
              <?php 
		$result_msg->MoveNext();
	}
?>
    </td>
  </tr>
</table>

</div>