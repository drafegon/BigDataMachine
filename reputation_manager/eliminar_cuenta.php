<?php
include "../cabecera.php";
?>

<div class="mas_titulo" style="margin: 10px 0 10px 50px; color:#FA5225; font-size:35px;"><?=_REPUTA_BAJAUSU?></div>
<br />
<div style="margin: 10px 0 10px 50px; font-size:18px;">
	<?=_REPUTA_BAJAUSUMSG?>
    <br><br>
    <form action="" method="post" target="_top">
	<input type="hidden" name="us_id" value="<?=$_SESSION['sess_usu_id']?>" />
    <input type="hidden" name="funcion" value="baja_usuario" />
    <input type="submit" name="guardar" value="<?=_REPUTA_BAJACUE?>" />
    </form>
</div>
