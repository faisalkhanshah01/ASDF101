
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
    <script type="text/javascript">
        $(document).ready(function(){
            $("#idProductShow").hide();
            $("#allProductShow").show();
            $("#searchproductbox").hide();
            $("a[name=tab]").click(function(event) {
              event.preventDefault();
                var categoryID = $(this).data("index");
                if(categoryID != ''){
                    $("#idProductShow").show();
                    $("#allProductShow").hide();
                    $("#AllProductShowSearch").hide();
                    $("#searchproductbox").show();
                    $("#searchViewShow1").hide();
                    $("#searchAllView1").show();
                }
                
                
                var search_product = $('#search_product').val();
                $('#search_product').val('');
                $('#search_product1').val('');
                
                var categoryID = $(this).data("index");
                var data = {
                    category:categoryID,
                    category_image:2,
                    search_product:search_product,
                    submit:1
                };
                $.ajax({
                    //url: '<?php //print base_url();?>/infonet_left_menu/category_image',
                    url: '<?php print base_url();?>/infonet_left_menu/get_products_via_ajax',
                    type: 'post',
                   // dataType: "json",
                    data: data,
                    success: function(output) {
                       $('#products_view').html(output);
                      $("img.lazy").lazyload({
                        effect : "fadeIn"
                      });
                       // //location.href=location.href;
                    }
                });  
                return false;
            });
            
            /**************************************search product*********************************************/
            $("#searchViewShow").hide();
            $("#searchAllView").show();
            $('#search_product').keyup(function() {
                $("#searchViewShow").show();
                $("#searchAllView").hide();
                
                var data = {
                    product:$("#search_product").val(),
                    checkProduct:1,
                    serachSubmit:1,
                };
                $.ajax({
                    url: '<?php print base_url()."infonet_left_menu/searchProductall";?>',
                    type: 'post',
                    dataType: "json",
                    data: data,
                    success: function(output) {
                        //alert(output.message);
                        console.log(output.message);
                        $('#search_view').html(output.message);
                        $("img.lazy").lazyload({
                          effect : "fadeIn"
                        });
                    }
                });  
                return false; 
            });
            
            $("#searchViewShow1").hide();
            $("#searchAllView1").show();
            $('#search_product1').keyup(function() {
                $("#searchViewShow1").show();
                $("#searchAllView1").hide();
                var data = {
                    product:$("#search_product1").val(),
                    checkProduct:2,
                    serachSubmit:1,
                };
                $.ajax({
                    url: '<?php print base_url()."infonet_left_menu/searchProductall_1";?>',
                    type: 'post',
                    dataType: "json",
                    data: data,
                    success: function(output) {
                        $('#search_view1').html(output.message);
                        $("img.lazy").lazyload({
                          effect : "fadeIn"
                        });
                    }
                });  
                return false;
            });
            
            
            $(".search_submit").click(function(){
               var data = {
                    product:$("#search_product").val(),
                    serachSubmit:1,
                };
                $.ajax({
                    url: '<?php print base_url()."infonet_left_menu/searchProduct";?>',
                    type: 'post',
                    dataType: "json",
                    data: data,
                    success: function(output) {
                        if(output.responseType == 'success'){
                            // //$data['url'] = "'".base_url()."Infonet_left_menu/client_view_products_details/".$result["speci_file_id"].'/'.$result['product']."'";
                            window.location = '<?php print base_url()."Infonet_left_menu/client_view_products_details?data_type=";?>' + output.speci_file_id + '&product_code=' +output.product;
                        } else{
                            alert(output.message);
                        }   
                    }
                });  
                return false;
            });
            /****************************************search product*******************************************/
            $("img.lazy").lazyload({
                effect : "fadeIn"
            });
            $("#sidebar-wrapper li, #sidebar-wrapper li label").click(function(){
              $("img.lazy").lazyload({
                  effect : "fadeIn"
              });
            });
        });
   </script>
   <!--http://makitweb.com/load-more-results-with-jqueryajax-and-php/-->
   <div id="global_searchAllView">
<!------------------------------------------Header End-------------------------------------------------------------------------->    
        <?php if(!empty($categories)){?>
            <div class="alert alert-success" role="alert"><?php echo $categories;?> </div>
        <?php }else{?>
                <div class="row" id="searchproductbox">
                    <div class="col-md-6 pull-right">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search_product1" id="search_product1" placeholder="Search for...">
                            <!--<span class="input-group-btn">
                                <button class="btn btn-secondary search_submit" type="button">-->
								<span class="input-group-addon">
                                    <i class="glyphicon glyphicon-search"></i>
							    </span>		
                               <!-- </button>
                            </span>-->
                        </div>
                    </div>
                </div>
                <div class="row" id="AllProductShowSearch">
					<div class="col-md-6 pull-right">
						<div class="input-group">
							<input type="text" class="form-control" name="search_product" id="search_product" placeholder="Search for...">
							<span class="input-group-addon">
									<i class="glyphicon glyphicon-search"></i>
							</span> 
						</div>
					</div>
				</div>
                <br/>
             	<div id="wrapper">
                        <!-- Sidebar -->
                        <!-- /#page-content-wrapper -->
                        <div id="sidebar-wrapper">
                            <?php if(!empty($createTreeView)){print $createTreeView;} ?>
                        </div>
                        <!-- /#sidebar-wrapper -->
                         
                        <!-- Page Content -->
                            <div id="page-content-wrapper">
                                <div class="container-fluid">
                                    <div class="row" id="idProductShow">
                                        <div class="row" id="searchViewShow1">
                                            <div class="col-md-12">
                                                <div id="search_view1"></div>
                                            </div>
                                        </div>
                                        <div id="searchAllView1">
                                                <div class="col-md-12">
                                                    <div id="products_view"></div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row" id="allProductShow">
                                        
                                       <div class="row" id="searchViewShow">
                                            <div class="col-md-12">
                                                <div id="search_view"></div>
                                            </div>
                                        </div>
                                        <div id="searchAllView">
                                            <div class="col-md-12">
                                               <?php if(!empty($category_image) && is_array($category_image)){?>
                                                    <?php $c = 0;
                                                          foreach($category_image as $key => $value){
                                                          //$img = str_replace("FCPATH/",base_url(),$value['imagePath']);
                                                           $image = "'".$value['imagePath']."'";
                                                            $imagePath = strstr($value['imagePath'],".");
                                                            $validextensions = array(".jpeg", ".jpg", ".png" ,".gif");
                                                            if(!empty($image) && (in_array($imagePath, $validextensions))){
                                                                $image =  str_replace("FCPATH/",base_url(),$value['imagePath']);
                                                            }else{
                                                                $image =  base_url().'includes/images/no_image.JPG';
                                                            }   
                                                          //$image = ($value['imagePath']!=NULL)? $img  : base_url().'includes/images/no_image.JPG';
                                                    ?>
                                                        <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                                            
                                                            <!-- <a class="thumbnail"  href="<?php //print base_url("Infonet_left_menu/client_view_products_details").'/'.$value["speci_file_id"].'/'.$value['product_code'];?>">-->
                                                            <a class="thumbnail"  href="<?php print base_url("Infonet_left_menu/client_view_products_details").'?data_type='.$value["table_name"].'&product_code='.$value['product_code'];?>">
																<img class="img-responsive lazy" data-original="<?php print $image;?>" alt="">
                                                             </a>
                                                             <div class="caption">
                                                                <strong><p class="group inner list-group-item-text" style="text-align: center;"><?php print $value['product_code'];?></p></strong>
                                                             </div> 
                                                         </div>
                                                          <?php $c = $c+4;}?>
                                               <?php } else { ?>
                                                   <div class="alert alert-success" role="alert"><?php echo "No Data";?> </div>
                                              <?php }?>
                                            </div>  
                                        </div>    
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /#wrapper -->
        <?php }?>
                    
        </div>
		<div id="global_searchViewShow">
			<div class="row">
				<div class="col-md-12">
					 <div id="global_search_view"></div>
				</div>
			</div>	
		</div>	        
             
<!------------------------------------------Footer End-------------------------------------------------------------------------->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery_lazyload/1.9.7/jquery.lazyload.js"></script>
