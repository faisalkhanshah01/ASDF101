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
				<legend class="home-heading">Product Category List</legend>
				<span class="pull-right" style="margin-bottom:15px;">
				<a href='<?php echo base_url(); ?>ProductCategory_controller/add_product' class="btn btn-primary">Insert Product</a></span>
				<table id="formonedetails" class="table table-hover table-bordered home_table" >
					<thead>
						<tr>
							<th class="">S. No.</th>
							<th class="">Category Name</th>
							<th class="">Parent Category Name</th>
							<th class="">Status</th>						
							<th class="">Action</th>
						</tr>
					</thead>
					<tbody>
						
						<?php 
							if(!empty($product)){
								$count = 1;
								foreach($product as $values){
						?>
						<tr>
							<td class="text-center"><?php echo $count; ?></td>
							<td class="text-center"><?php echo $values['category_name']; ?></td>
							<td class="text-center"><?php echo ($values['catParent_id'] =='0')? 'No Parent' : $values['parent_name']; ?></td>
							<td class="text-center"><?php echo ucfirst($values['status']); ?></td>
							<td class="text-warning"  style="text-align:center;">
								<a href="<?php echo base_url("productCategory_controller/edit_product/".$values['id']); ?>" title="Edit Product">Edit</a> | 
								<a href="<?php echo base_url("productCategory_controller/delete_product/".$values['id']); ?>"  class="delete" title="Delete Product">Delete </a>
							</td>
						</tr>
						<?php $count++; } }else{ ?>
						<tr class="text-center error">
							<td colspan="5"><p>No Data Found</p></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
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