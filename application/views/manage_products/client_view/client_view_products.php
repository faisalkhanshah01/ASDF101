<?php $this->load->view('includes/header'); ?>
	<?php //$this->load->view('includes/head'); ?>
	<?php	//$this->load->view('includes/head');	?>
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
	<style>
		.viewProductsSeriesWidht{
			
		}
	</style>
	<div class="row" class="msg-display">
		<div class="col-md-12 text-center">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo '<span style="color:red"><strong>'.$this->session->flashdata('msg').'</strong></span>';
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
	<div id="global_searchAllView">
		<div class="row">
			<div class="col-md-12">
				<ol class="breadcrumb">

					<?php echo $_SESSION['infornetView']['breadCrum']['mainLink'];?>
				</ol>
			</div>

		<div id="client_Infonet" class="col-md-12">
			<?php if(!empty($products)){ ?>
				
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									<div class="panel-heading home-heading">
										<span>Assets <i class="pull-right more-less glyphicon glyphicon-minus"></i></span>
									</div>
								</a>
							</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
							<div class="panel-body">
								<?php	if(isset($products['assets'])){ ?>
								<table class="table viewProducts" id="viewProducts">
									<thead>
										<tr>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<?php
											foreach($products['assets'] as $values){
												$image = (!empty($values['url']))? str_replace("FCPATH/",$base_url,$values['url']) : $base_url.'/includes/images/no_image.JPG';
											?>
											<tr class="col-md-3">
												<td class='col-md-3'>
													<div class="img thumbnail">
														<a href="<?php echo base_url('Client_view/client_view_products_details')."/components/".$values['name'];?>" >
															<div style="background: rgba(0, 0, 0, 0) url('<?php echo $image; ?>') no-repeat scroll 0% 0% / cover; height:250px;"></div>
															<!--<h5 class="text-center"><?php echo $values['name']; ?></h5>-->
																<h5 class="text-center" style="font-size:16px;line-height:22px;color:#ffffff;" ><?php echo $values['name']; ?></h5>
																
														</a> 
													</div>
												</td>
											</tr>
										<?php 
											} ?>
									</tbody>
								</table>
								<?php } ?>
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									<div class="panel-heading home-heading">
										<span>Asset Series <i class="pull-right more-less glyphicon glyphicon-plus"></i></span>
									</div> 
								</a>
							</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
							<div class="panel-body">
								<?php if(isset($products['assets_series'])){ ?>
									<table class="table  dataTable no-footer viewProductsSeriesWidht" id="viewProductsSeries" role="grid" style="width: 1040px;">
										<thead>
											<tr>
												<td></td>
											</tr>
										</thead>
										<tbody>
										   <?php
												foreach($products['assets_series'] as $values){
													$image = (!empty($values['url']))? str_replace("FCPATH/",$base_url,$values['url']) : $base_url.'/includes/images/no_image.JPG';	
											?>
													<tr class="col-md-3">
														<td class='col-md-3'>
															<div class="img thumbnail">
																<a href="<?php echo base_url('Client_view/client_view_products_details')."/products/".$values['name'];?>" >
																	<div style="background: rgba(0, 0, 0, 0) url('<?php echo $image; ?>') no-repeat scroll 0% 0% / cover; height:250px;"></div>
																	<h5 class="text-center" style="font-size:16px;line-height:22px;background: rgba(0, 0, 0, 0)" ><?php echo $values['name']; ?></h5>
																</a> 
															</div>
														</td>
													</tr>
										<?php 	} ?>
										</tbody>
									</table>
								<?php } ?>
							</div>
						</div>
					</div>	 
				</div>
		<?php	}else{ ?>
				<div class="panel panel-default">
					<div class="panel-body">
						<h4 class="text-center error">No Data Found</h4>
					</div>
				</div>		
			<?php } ?>
			
			</div>
		</div>
	</div>
	<div id="global_searchViewShow">
		<div class="row">
			<div class="col-md-12">
				 <div id="global_search_view"></div>
			</div>
		</div>	
	</div>		
		
<?php $this->load->view('includes/new_footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">
    $(document).ready(function(){
		//search table
			$("#viewProducts").DataTable({
		   		   "ordering": false,
				   "paging": false,
				   "info":     false
		   });
		   $("#viewProductsSeries").DataTable({
		   		   "ordering": false,
				   "paging": false,
				   "info":     false
		   });
	});
function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('glyphicon-plus glyphicon-minus');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);
</script>