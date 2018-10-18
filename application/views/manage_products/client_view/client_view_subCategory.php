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
					<?php echo $_SESSION['infornetView']['breadCrum']['mainLink'];?>
				</ol>
			</div>
			
			<div class="col-md-12">
				<div id="client_Infonet" class="panel panel-default">
					<?php if ( $groupID == 11 || $groupID == 10){?>
					<?php }else{?>
						<div class="panel-heading home-heading">
							<span><?php echo $_SESSION['infornetView']['pageTitle']; ?></span>
						</div>
					<?php }?>
					<div class="panel-body">
						<table class="table table-bordered" id="inspection_result_table">
							<tbody>
								<tr>
						<?php 
							if(!empty($categories)){
								$count = 0;
								foreach($categories as $values){
								$image = str_replace("FCPATH/",$base_url,$values['cat_image']);		
						?>
								<div class="col-md-3">
									<div class="img thumbnail" style="position: relative; height: 342px; width: 258px;">
										<a href="<?php echo base_url('Client_view/client_view_subCategory')."/".$values['id'];?>" >
											<div style="background: rgba(255, 255, 255, 1) url('<?php echo $image; ?>') no-repeat scroll 0% 0% / cover; height:250px;"></div>
											<h4 class="text-center" style="font-size:16px;line-height:22px;"><?php echo $values['cat_name']; ?></h4>
										</a> 
									</div>
								</div>
								
						<?php $count++; } }else{ ?>
								<td>
								<h4 class="text-center error">No Data Found</h4></td>
								<?php } ?>
								</tr>
							</body>
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
		
<?php //$this->load->view('includes/footer'); ?>
<?php 
	 $a = $_SESSION['flexi_auth']['group'];
foreach($a as $k=>$v){
	 $name = $v;
		 $group_id = $k;
	}
	 if ( $group_id == 11 || $group_id == 10){?>
		<?php $this->load->view('includes/new_footer'); ?> 
<?php	}else{ ?>
 <?php $this->load->view('includes/footer'); ?> 
<?php	}?>

<?php $this->load->view('includes/scripts'); ?>