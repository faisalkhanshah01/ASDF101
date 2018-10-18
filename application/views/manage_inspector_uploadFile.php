<?php $this->load->view('includes/header'); ?>
	<?php $this->load->view('includes/head'); ?>
	
	<div class="row" class="msg-display">
		<div class="col-md-12">
			<?php 	if (!empty($this->session->flashdata('msg'))||isset($msg)) { ?>
				<p>
			<?php	echo $this->session->flashdata('msg'); 
				if(isset($msg)) echo $msg;
				echo validation_errors(); ?>
				</p>
			<?php } ?>
		</div>
	</div>
	<?php if(!empty($assignInspector_list)){?>
	<!-- Form Section Start-->
	<div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading home-heading">
                            <span>Documents upload</span>
                            <span class="btn-group pull-right">
                                <?php print $assignInspector_list['inspector_name'].' : '.$assignInspector_list['inspectorID'];?>
                            </span>
                    </div>
                    <div class="panel-body">
                            <div class="form-group">
                                <div class="col-md-12">
                                     
                                    <table class="table table-hover table-bordered home_table">
                                        <tbody>
                                            <?php if(!empty($assignInspector_list['inspector_name'])){?>
                                            <tr id = "<?php print $assignInspector_list['inspectorID'];?>" >
                                                <td colspan="9">
                                                <form class="form-horizontal" role="form" name="product_edit" id="product_edit"  method="post" enctype="multipart/form-data">

                                                            <div class="form-group">
                                                                    <div class="col-md-12">
                                                                            <table id="upload_specification_table" class="table table-hover table-bordered home_table">
                                                                                    <tbody>
                                                                                        <?php
                                                                                            $arr = array('WAH', 'Installation', 'Medical Certificate','Inspection');
                                                                                            $arraydiff = $assignInspector_list['file_title'];
                                                                                            foreach($arr as $i => $v){
                                                                                        ?>
                                                                                        <tr>

                                                                                            <td class="col-md-4 alert-warning text-center">
                                                                                                    <input type='hidden' class='form-control' name='inspectorID' value="<?php print $assignInspector_list['inspectorID'];?>"/>
                                                                                                    <label for="" class="control-label alert-warning"><?php echo $arr[$i]; ?></label>
                                                                                                    <input type='hidden' class='form-control' name='upload[<?php echo $i; ?>][title]' id='title<?php echo $i; ?>' value="<?php echo $arr[$i]; ?>"/>
                                                                                            </td>

                                                                                                <td class="col-md-8" colspan="2">
                                                                                                        <!--<label for="" class="control-label alert-warning col-md-2 ">
                                                                                                            url
                                                                                                        </label>-->
                                                                                                        <div class="col-md-10">
                                                                                                                <input class='form-control' type="file" name="upload[<?php echo $i; ?>][url]" />
                                                                                                        </div>
                                                                                                </td>
                                                                                                <td class="col-md-8" colspan="2">
                                                                                                    <?php if(!empty($arraydiff)){?>
                                                                                                        <?php foreach($arraydiff as $dk => $dv){?>
                                                                                                            <?php if($dv['title'] == $v){?>    
                                                                                                                    <label for="" class="control-label alert-warning col-md-2 ">
                                                                                                                         <a href="<?php print $dv['url'];?>" target="_blank"><span class="glyphicon glyphicon-eye-open"></span></a>
                                                                                                                     </label>
                                                                                                                <?php }?>
                                                                                                        <?php }?>
                                                                                                        <?php if(@$arraydiff[$i]['title'] != $v){?>
                                                                                                            <label for="" class="control-label alert-warning col-md-2 ">
                                                                                                                N/A
                                                                                                            </label>
                                                                                                        <?php }?>
                                                                                                    <?php }else{?>
                                                                                                        <label for="" class="control-label alert-warning col-md-2 ">
                                                                                                            N/A
                                                                                                        </label>
                                                                                                   <?php  }?>
                                                                                                </td>

                                                                                        </tr>
                                                                                        <?php } ?>
                                                                                            <tr>
                                                                                                    <td class="col-md-4 alert-warning text-center"></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                            </table>
                                                                            <div class="form-group">
                                                                                <div class="col-md-6 col-md-offset-5">
                                                                                        <button class="btn btn-primary"  name="add_multiSpeci" id="add_multiSpeci" value="add_multiSpeci" type="submit">Upload Doc.</button>
                                                                                </div>
                                                                            </div>
                                                                    </div> 
                                                            </div>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php }?>    
                                        </tbody>    
                                    </table> 
                                   
                                </div>                
                            </div>
                    </div>
                </div>
            </div>
	</div>
	<!-- Form Section END-->
	  <?php }?>	
<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>
