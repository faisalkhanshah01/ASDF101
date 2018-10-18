<?php $this->load->view('includes/header'); ?>
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
		<div class="col-md-offset-3 col-md-6">
			<?php if (! empty($message)) { ?>
				<div id="message" class="alert-success">
					<?php echo $message; ?>
				</div>
			<?php } ?>
		</div>
	</div>
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

	<div class="row">
		<div class="col-md-12">
			<legend class="home-heading">Manage Specification</legend>
			<span class="pull-right" style="margin-bottom:15px;">
			<a href='<?php echo base_url(); ?>specification/add_specifications' class="btn btn-primary">Add Specification</a></span>
			<table id="formonedetails" class="table table-hover table-bordered home_table" >
				<thead>
					<tr>
						<th>S. No.</th>
						<th>Specification Type</th>
						<th>Subtype</th>
						<th>Attachment</th>
						<th>Created By</th>						
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
					<?php $count = 1;
						if($specification != ''){
						foreach($specification as $values){ 
					?>
					<tr>
						<td class="text-center"><?php echo $count; ?></td>
						<td class="text-center"><?php 
							if($values['specification_type'] == 'components'){
								$type = 'Assets';
							}elseif($values['specification_type'] == 'sub_assets'){
								$type = 'Sub Assets';
							}elseif($values['specification_type'] == 'products'){
								$type = 'Asset Series';
							}
						
							echo $type; ?></td>
						<td class="text-center">
							<?php 
								if($values['specification_type'] == 'components'){
									foreach($assets_details as $subtype){
										if($subtype['component_id'] == $values['specification_subtype']){
											echo $subtype['component_code'];
										}
									}
								}elseif($values['specification_type'] == 'products'){
									foreach($subassets_details as $subtype){
										if($subtype['product_id'] == $values['specification_subtype']){
											echo $subtype['product_code'];
										}
									}
								}
								elseif($values['specification_type'] == 'sub_assets'){
									foreach($subassets_details as $subtype){
										if($subtype['sub_assets_id'] == $values['specification_subtype']){
											echo $subtype['sub_assets_code'];
										}
									}
								}
							?>
						</td>	
						<td class="text-center"><a href="<?php echo $values['specification_attachment']; ?>">Attachment</a></td>		
						<td class="text-center"><?php echo ucfirst($values['created_by']); ?></td>			
						<td class="text-warning"  style="text-align:center;">
							<a href="<?php echo base_url("specification/edit_specification/".$values['id']); ?>" title="Edit Specification">Edit</a> | 
							<a href="<?php echo base_url("specification/delete_specification/".$values['id']); ?>"  class="delete" title="Delete Specification">Delete </a>
						</td>
					</tr>
						<?php $count++; } }else{?>
					<tr class="text-center">No Data Available</tr>
						<?php } ?>
				</tbody>
			<table>
		</div>
	</div>
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">
    $(document).ready(function(){
		//search table
			$("#formonedetails").DataTable({
		   		   "order":[[ 0,"asc" ]]
		   });
	 });
	 
	 $(document).ready(function(){
		//search table
			$("#type_value_details").DataTable({
		   		   "order":[[ 0,"asc" ]]
		   });
	 });
	
</script>