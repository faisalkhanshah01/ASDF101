<?php 
	$CI =& get_instance();
	$CI->load->library('sma');
	$this->data['lang']  =  $CI->sma->get_lang_level('first');
        $kdkey =NULL;

    if( isset($this->session->flexi_auth['group'])){ $kdkey = array_keys($this->session->flexi_auth['group']); }	
	if( ( isset($kdkey[0]) && (  $kdkey[0] == 10) || ($kdkey[0] == 11))){ 
?>
<div class="overflow_wrapper">
    <footer>
      <div class="row">
        <div class="col-md-12">
          <ul>
            <li>Copyrights &copy; KARAM <?php echo date("Y"); ?></li>
            <li>
              <a href="mailto:karam@karam.in"><span class="glyphicon glyphicon-envelope"></span> karam@karam.in</a>
            </li>
            <li>
              <a href="callto:18001037085"><span class="glyphicon glyphicon-earphone"></span> 1800 103 7085</a>
            </li>
            <li class="lst-ftr-li"><?php if( $lang["powered_by_arresto"]['description'] !='' ){ echo $lang["powered_by_arresto"]['description']; }else{ echo "Powered by : ARRESTO"; }  ?> </li>
          </ul>
        </div>
      </div>              
    </footer>
  </div>
<?php }else{ ?>
<div class="overflow_wrapper">
	<footer class="footer">
			<div class="row">
        <div class="col-md-12">
          <ul>
            <li class="lst-ftr-li"><?php if( $this->data['lang']["powered_by_arresto"]['description'] !='' ){ echo $this->data['lang']["powered_by_arresto"]['description']; }else{ echo "Powered by : ARRESTO"; }  ?></li>
          </ul>
        </div>
      </div>
		</footer>
</div>

		</div><!--  Container started in header.php and ending here  -->
<?php }?>		
		

