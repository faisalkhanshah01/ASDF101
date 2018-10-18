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
	<div id="global_searchAllView">
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
				<legend class="home-heading">Category List</legend>
				<span class="pull-right" style="margin-bottom:15px;">
				<a href='<?php echo base_url(); ?>category_controller/add_category' class="btn btn-primary">Insert Category</a></span>
				<table id="formonedetails" class="table table-hover table-bordered home_table" >
					<thead>
						<tr>
							<th class="">S. No.</th>
							<th class="">Category Name</th>
							<th class="">Parent Category</th>
							<th class="">Status</th>
							<th class="">Action</th>
						</tr>
					</thead>
					<tbody>	
					<?php
						if(!empty($categories)){
						$count = 1;
						foreach($categories as $category){ ?>	
						<tr>
							<td><?php echo $count; ?></td>
							<td><?php echo $category['cat_name']; ?></td> 
							<td><?php echo isset($category['cat_parentname'])? $category['cat_parentname']:'<span class="text-warning">No Parent</span>'; ?></td>
							<td><?php echo ucfirst($category['cat_status']); ?></td>
							<td class="text-warning"  style="text-align:center;">
								<a href="<?php echo base_url("category_controller/edit_category/".$category['id']); ?>">Edit</a> | 
								<a href="<?php echo base_url("category_controller/delete_category/".$category['id']); ?>"  class="delete" title="Delete Category">Delete </a>
							</td>
						</tr>
						<?php $count++; } } ?>
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
	<?php 
	   // $kdvalue = array_values($this->session->flexi_auth['group']);
		$kdkey = array_keys($this->session->flexi_auth['group']);
		if(($kdkey[0] == 10) || ($kdkey[0] == 11)){ 
	?><br/>
		<?php $this->load->view('includes/new_footer'); ?>
		<?php }else{?>
			<?php $this->load->view('includes/footer'); ?>
		<?php }?>
<?php $this->load->view('includes/scripts'); ?>
<script type="text/javascript">
    $(document).ready(function(){
		//search table
			$("#formonedetails").DataTable({
		   		   "order":[[ 0,"asc" ]]
		   });
	 });
	 
	
</script>