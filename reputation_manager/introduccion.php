<div id="introduccion" style="width:100%; height:320px; position:absolute; top:80px; z-index:1000;">

    <div style="width:500px; height:200px; margin:50px auto; text-align:center; vertical-align:middle; display:block; background:#FFF;" class="redondeado sombra">
    	
        <!-- /////////////////////////////////////////// -->
        <div id="intruccion0">
            <br /><br />
            <div style="font-size:25px; font-weight:bold; color:#F30;">
            <img src="images/feb-2014/bigdatamachine.png" alt="" />
            </div>            
            <br />
            <a href="javascript:;" onclick="$('#intruccion0').slideToggle(); $('#intruccion1').fadeIn();" style="font-size:20px; font-weight:bold; color:#F25630;"><?=_BDM_INTRO1?> &raquo;</a>
        </div>
        
    	<div id="intruccion1" style="display:none;">
            <br />
            <div style="font-size:25px; font-weight:bold; color:#F25630;">1. </div>
            <div style="font-size:15px; color:#666; padding:0 10px;"><?=_BDM_INTRO2?></div>
            <br />
            <a href="javascript:;" onclick="$('#intruccion1').slideToggle(); $('#intruccion2').fadeIn();" style="font-size:20px; font-weight:bold; color:#F25630;"><?=_BDM_INTRO3?> &raquo;</a>
        </div>
        
        <!-- /////////////////////////////////////////// -->
        
        <div id="intruccion2" style="display:none;">
            <br />
            <div style="font-size:25px; font-weight:bold; color:#F25630;">2. </div>
            <div style="font-size:15px; color:#666; padding:0 10px;"><?=_BDM_INTRO4?> </div>
            <br />
            <a href="javascript:;" onclick="$('#intruccion2').slideToggle(); $('#intruccion3').fadeIn();" style="font-size:20px;  font-weight:bold; color:#F25630;"><?=_BDM_INTRO3?> &raquo;</a>
        </div>
    
    	<!-- /////////////////////////////////////////// -->
        
         <div id="intruccion3" style="display:none;">
            <br />
            <div style="font-size:25px; font-weight:bold; color:#F25630;">3. </div>
            <div style="font-size:15px; color:#666; padding:0 10px;"><?=_BDM_INTRO5?> </div>
            <br />
            <a href="javascript:;" onclick="$('#intruccion3').slideToggle(); $('#intruccion4').fadeIn();" style="font-size:20px;  font-weight:bold; color:#F25630;"><?=_BDM_INTRO3?> &raquo;</a>
        </div>
    
    	<!-- /////////////////////////////////////////// -->
        
        <div id="intruccion4" style="display:none;">
            <br />
            <div style="font-size:25px; font-weight:bold; color:#F25630;">4. <?=_BDM_INTRO6?>!</div>
            <div style="font-size:15px; color:#666; padding:0 10px;"><?=_BDM_INTRO7?></div>
            <div style="font-size:12px; color:#666; padding:8px 10px;"><?=_BDM_INTRO8?> </div>

            <a href="javascript:;" onclick="$('#introduccion').fadeOut();" style="font-size:20px;  font-weight:bold; color:#F25630;"><?=_BDM_INTRO9?>!</a>
        </div>
    
    	<!-- /////////////////////////////////////////// -->
        
    </div>
    
</div>