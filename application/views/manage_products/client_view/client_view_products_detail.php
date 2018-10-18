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
					<?php //echo $_SESSION['infornetView']['breadCrum']['mainLinkNew'];?>
				</ol>
			</div>
			
			<div class="col-md-12">			
				<div class="panel panel-default">
					<div class="panel-heading has-success">
					<?php 
						if(!empty($table_name)){
							if($table_name =='components'){
								$table = 'Assets';
							}elseif($table_name =='sub_assets'){
								$table = 'Sub Assets';
							}elseif($table_name =='products'){
								$table = 'Assets Series';
							}
						}else{
							$table = ' ';
						}
					?>
						<span>Product Specification Details : <strong><?php echo $table; ?></strong> : <strong><?php echo $data_name; ?></strong> </span>
					</div>
					<div class="panel-body">
						<table id="view_productdetails_table" class="table table-hover table-bordered home_table" >
							<tbody>
							<?php
								if(!empty($product_specification)){	
								$file_data = json_decode($product_specification['file'], true);
								foreach($file_data as $value){
							?>	
								<tr>
									<td class="col-md-4 alert-warning text-center">
										<label for="" class="control-label alert-warning"><?php echo $value['title']; ?></label>
									</td>
									<td class="col-md-8" colspan="2">
										<label class="control-label col-md-2"></label>
										<div class="col-md-10 text-center">
										<?php if(!empty($value['url'])){ ?>
											<a class="alert-success" target="_blank" href="<?php echo $value['url']; ?>"><span class="glyphicon glyphicon-eye-open" title="view data" aria-hidden="true"></span>
											</a>
										<?php }else{ ?>
											<p><span class="glyphicon glyphicon-eye-close alert-danger" title="no data" aria-hidden="true"></span></p>
										<?php } ?>
										</div>
									</td>
								</tr>
								<?php } }else{ ?>
								<tr>
									<td colspan="2" class="alert-warning text-center">
										<label for="" class="control-label alert-warning">No Data Found</label>
									</td>
								</tr>
							<?php  }  ?>
							</tbody>
						</table>
					</div>
				</div>			
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
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>