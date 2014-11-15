<?php
include "../cabecera.php";

$nombre = obtenerDescripcion("valor,paquete", " AND parametro='NOMBRE_PAQUETE' ", "ic_cuenta_usuarios", $db);
$valor = obtenerDescripcion("valor", " AND parametro='COSTO' ", "ic_cuenta_usuarios", $db);
?>
<div style="margin: 10px 0 20px 50px;">
<div class="titulo2">Soluciones</div>
<br /> 
<h2 >Paquete Actual <?=$nombre->fields[0]?> (USD $<?=$valor->fields[0]?>)</h2>
<br />  <br />         
<div class="price_container">
    <div class="monthly">
        <div class="plan">
            <div class="benefit">
                Compare los Planes
            </div>
            <div id="choice" class="gold">
              <h1>Moderna</h1>
              <div class="blanko">	
                <h3 class="RobotoCondensed">
                  <span>U$S</span> 29.00</h3>
                <p>-</p>
                </div>
            </div>
        
          <div id="choice">
                <h1>Futura
                </h1>
            <div class="blanko">
              <h3 class="RobotoCondensed">
                  <span>U$S</span> 149.00</h3>
                <p>-</p>
            </div>
            </div>
        
          <div id="choice">
                <h1>Empresarial</h1>
            <div class="blanko">
              <h3 class="RobotoCondensed">
                  Consulte
              </h3>
                <p>
                 <span style="font-weight: bold; font-size: 14px;">011- 4126 -2950</span><br/>	 
                </p>
            </div>
            </div>

        </div>
       <!--   -->
      <div class="bigdateca">
            <div class="benefit">
            </div>
            <div class="clasica">
			<?php if($nombre->fields[1]=="0" || $nombre->fields[1]=="-1" || $nombre->fields[1]=="1" || $nombre->fields[1]=="11"){ ?>
            <a href="redireccionpago.php?user=true&paquete=1" class="btn_small" target="_self">Pagar</a>
			<?php } ?>
            </div>
            <div class="clasica">
			<?php if($nombre->fields[1]=="0" || $nombre->fields[1]=="-1" || $nombre->fields[1]=="1" || $nombre->fields[1]=="11" || $nombre->fields[1]=="2" || $nombre->fields[1]=="22"){ ?>
            <a href="redireccionpago.php?user=true&paquete=2" class="btn_small" target="_self">Pagar</a>
			<?php } ?>
            </div>
            <div class="clasica"><a href="secciones/ayuda.php" class="btn_small" target="_self">Contactenos</a></div>
        </div>
        <div  class="users">
            <div class="benefit">
                B&uacute;squedas            
            </div>
          <div class="clasica">
                3
            </div>
            <div class="clasica">
                5
            </div>
            <div class="clasica">Customizable</div>

        </div>
        <div class="views">
            <div  class="benefit">
                Precisi&oacute;n de b&uacute;squeda                         
            </div>
          <div class="clasica">2</div>
            <div class="clasica">3</div>
            <div class="clasica">Customizable</div>
        </div>
        <div class="exports">
            <div class="benefit">
                Monitoreo REDES SOCIALES
          </div>
          <div class="clasica">Ilimitados</div>
            <div class="clasica">Ilimitados</div>
            <div class="clasica">Ilimitados</div>
        </div>
        <div class="alerts">
            <div class="benefit">
                BIG DATA P&Uacute;BLICO
            </div>
          <div class="clasica">1000</div>
            <div class="clasica">7000</div>
            <div class="clasica">Customizable</div>
        </div>
        <!--  Nuvas filas -->
        <div class="premiumsearch">
            <div class="benefit">
            Alarmas personalizadas                        
            </div>
          <div class="noPlus">-</div>
          <div class="clasica"><p align="center"><img src="images/ok.png" width="16" height="12" alt="tilde" /></p></div>
          <div class="clasica"><p align="center"><img src="images/ok.png" width="16" height="12" alt="tilde" /></p></div>
        </div>
        <div class="premiumsearch">
          <div class="benefit">
                Carpetas inteligentes    
          </div>
          <div class="noPlus">-</div>
          <div class="clasica">
            <p align="center"><img src="images/ok.png" width="16" height="12" alt="tilde" /></p>
          </div>
          <div class="clasica"><p align="center"><img src="images/ok.png" width="16" height="12" alt="tilde" /></p></div>
        </div>
        <div class="premiumsearch">
            <div class="benefit">
                Auto gesti&oacute;n de resultados
            </div>
            <div class="noPlus">-</div>
            <div class="clasica"><p align="center"><img src="images/ok.png" width="16" height="12" alt="tilde" /></p></div>
            <div class="clasica"><p align="center"><img src="images/ok.png" width="16" height="12" alt="tilde" /></p></div>
        </div>
        <div class="premiumsearch">
            <div class="benefit">
              Resultado de Datos Exportables
          </div>
            <div class="noPlus">-</div>
            <div class="clasica"><p align="center"><img src="images/ok.png" width="16" height="12" alt="tilde" /></p></div>
            <div class="clasica"><p align="center"><img src="images/ok.png" width="16" height="12" alt="tilde" /></p></div>
        </div>
        <div class="premiumsearch">
            <div class="benefit">
              M&uacute;ltiples Usuarios
          </div>
            <div class="clasica">5</div>
            <div class="clasica">Ilimitados</div>
            <div class="clasica">Ilimitados</div>
        </div>     

    </div>

</div>
</div>