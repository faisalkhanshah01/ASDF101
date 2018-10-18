<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
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
	<?php 
	// echo "<pre>";
	// print_r($clientManualData);
	// die();
	?>
	<div class="row">
		<div class="col-md-12">
			<legend class="home-heading">Manage Client Manual Data</legend>
			<span class="pull-right" style="margin-bottom:15px;">
			<a href='<?php echo base_url(); ?>Client_manual_data_controller/insert_client_manual_data' class="btn btn-primary">Insert Client Manual Data</a></span>
			<table id="formonedetails" class="table table-hover table-bordered home_table" >
				<thead>
					<tr>
						<th class="">S. No.</th>
						<th class="">Client Name</th>						
						<th class="">Action</th>
					</tr>
				</thead>
				<tbody>
					
					<?php $count = 1;
						foreach($clientManualData as $values){ 
					?>
					<tr>
						<td class="text-center"><?php echo $count; ?></td>
						<td class="text-center"><?php echo $values['client_name']; ?></td>		
						<td class="text-warning"  style="text-align:center;">
							<a href="<?php echo base_url("Client_manual_data_controller/edit_client_manual_data/".$values['id']); ?>" title="Edit">Edit</a> | 
							<a href="<?php echo base_url("Client_manual_data_controller/delete_client_manual_data/".$values['id']); ?>"  class="delete" title="Delete">Delete </a>
						</td>
					</tr>
					<?php $count++; }?>
										
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
	 
	$('#manage_product_categogy').validate
	({
		rules:
		{
			
			category_name:
			{
				required: true
			},
			product_category_status: 
			{
				required: true
				
			},
		},
			highlight: function(element)
			{
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			
	});
</script>