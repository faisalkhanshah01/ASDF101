<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 
	
	<div class="row" class="msg-display">
		<div class="col-md-12">
			<?php 
			// echo "<pre>"; print_r( $_SERVER );  echo "</pre>";
			if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo $this->session->flashdata('msg'); 
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
			
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>LANGUAGE  LEVEL LIST</span>
				</div>
				<div class="panel-body">
					<legend >
						<a href="<?php echo $base_url;?>language_controller/language_level_add"><button class="btn btn-danger" type="button" ="right"> Add Language Level</button></a>&#160;&#160;
						<a href="<?php echo $base_url;?>language_controller/import_lang_level_list"><button class="btn btn-danger" type="button" ="right"> Import File</button></a>&#160;&#160;
						<a href="<?php echo $base_url;?>language_controller/smaple_import_lang_excel"><button class="btn btn-danger" type="button" ="right">Sample Import File</button></a>&#160;&#160;
						<!--<a href="javascript:void(0);" id="download_level_excel" ><button class="btn btn-danger" type="button" ="right"> Export Language Level </button></a>&#160;&#160; !-->
					</legend>
					<table id="table_lang_list"class="table table-bordered table-hover">
					<thead>
					<tr><th>Action</th><th>Level</th><th>Group</th><th>English</th><th>French</th><th>Arabic</th></tr>
					</thead>
					<tbody>
					</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<!-- Footer -->
<?php $this->load->view('includes/footer'); ?>
<!-- Scripts -->
<?php $this->load->view('includes/scripts'); ?>