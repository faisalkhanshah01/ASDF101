<?php $this->load->view('includes/header_infonet_new'); ?>
<?php //$this->load->view('includes/head'); ?>
<?php
		$groupId = $_SESSION['flexi_auth']['group'];
		foreach($groupId as $k=>$v){
			$name = $v;
			$groupID = $k;
		}
		
	?>
	<?php 	if ( $groupID == 11 || $groupID == 10){
				$this->load->view('includes/head_infonet');
			}else{ 
				$this->load->view('includes/head'); 
			}
	?>
<div class="row">
    <div class="container">
        <div class="col-md-12" style="background-color: #e5e5e5;">
            <div class="col-md-6" style="margin-top:21px">
                    <span >Product Specification Details : <strong>
                        <?php  if(!empty($parant_product) && is_array($parant_product)){
                                if($product_name == $parant_product['product_code']){
                                    print $product_name;
                                }
                        }
                        ?>
                   </strong> </span>
            </div>
            <!--<div class="col-md-4" style="margin-top:11px;">
				<div class="col-lg-8 col-md-4 col-xs-6 thumb pull-right">
					<a class="thumbnail"  href="#">
						<?php /*if(!empty($parant_product) && is_array($parant_product)){
							$image = "'".$parant_product['imagepath']."'";
							$imagePath = strstr($parant_product['imagepath'],".");
							$validextensions = array(".jpeg", ".jpg", ".png" ,".gif");
							if(!empty($image) && (in_array($imagePath, $validextensions))){
								$image =  str_replace("FCPATH/",base_url(),$parant_product['imagepath']);
							}else{
								$image =  base_url().'includes/images/no_image.JPG';
							}*/   
					   ?>   
						<img class="img-responsive" src="<?php //print $image;?>" alt="">
						<?php //}?>
					</a> 
				</div>
            </div> -->
			<div class="pull-right" style="margin-top:16px;">
				<a href="<?php print base_url()."infonet_left_menu/menus_category";?>" class="btn btn-default" role="button" id="addressSearch">
					<i class="fa fa-arrow-left" style="margin-top: 10px;" aria-hidden="true"></i>
				</a>
			</div>
        </div>
    </div>     
</div>
<br/>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table id="view_productdetails_table" class="table table-hover table-bordered" >
                    <tbody>
                        <?php if(!empty($products_detail) && is_array($products_detail)){
                            foreach ($products_detail as $key => $value){?>
                        <tr>
                            <td class="col-md-4 alert-warning text-center">
                                <label for="" class="control-label alert-warning"><?php print $value['title'];?></label>
                            </td>
                            <td class="col-md-8" colspan="2">
                                <label class="control-label col-md-2"></label>
                                <div class="col-md-10 text-center"> 
                                    <?php if(!empty($value['url'])){?>
                                        <a class="alert-success" target="_blank" href="<?php print $value['url'];?>">
                                            <span class="glyphicon glyphicon-eye-open" title="view data" aria-hidden="true"></span> 
                                        </a>
                                    <?php }else{?>
                                            <p>
                                                <span class="glyphicon glyphicon-eye-close alert-danger" title="no data" aria-hidden="true"></span>
                                            </p>
					
                                  <?php  }?>
                                </div>
                            </td>
                        </tr>
                        <?php }
                        }?>
		    </tbody>
		</table>
	    </div>
	</div>			
    </div>
</div>

<?php 
	   // $kdvalue = array_values($this->session->flexi_auth['group']);
		$kdkey = array_keys($this->session->flexi_auth['group']);
		if(($kdkey[0] == 10) || ($kdkey[0] == 11)){ 
	?><br/>
		<?php $this->load->view('includes/new_footer'); ?>
		<?php }else{?>
			<?php $this->load->view('includes/footer'); ?>
		<?php }?>
<?php $this->load->view('includes/scripts_new'); ?>