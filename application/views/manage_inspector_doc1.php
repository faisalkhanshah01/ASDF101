<?php $this->load->view('includes/header'); ?> 
<script type="text/javascript">
    $(document).ready(function() {
	    /*$("tr[title=slidewebUrlEditView]").hide();
		// toggles the edit view  
		$(".action .edit").click(function() {
		  var selectedID = $(this).parent().parent().attr("id");
		  $("tr[title=slidewebUrlEditView]").not("tr[title=slidewebUrlEditView][id="+selectedID+"]").hide();
		  $("tr[title=slidewebUrlEditView][id="+selectedID+"]").toggle();
		  return false;
		});*/
		
		$("#kare_manageInspector_doc_datatable").DataTable({
			"order":[[ 0,"asc" ]]
	   });
    });    
  </script>
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?>  
  
	<div class="row">
		<div class="col-md-12" >
			 <div class="panel panel-default">
				<div class="panel-heading home-heading">
					<span>Inspectors Doc. List</span>
				</div>
				<div class="panel-body">
				<!--<table id="inspector_table" class="table table-bordered table-hover">-->
				<table id="kare_manageInspector_doc_datatable" class="table table-bordered table-hover">
				<thead>
					<tr>
                                            <th><center>Inspector Id</center></th>
                                             <th><center>Inspector Names</center></th>
                                             <th><center>inspector_jobCard</center></th>
                                             <th><center>Action</center></th>
					</tr>
				</thead>
                                <?php if(!empty($assignInspector_list) && is_array($assignInspector_list)){?>
                                    <tbody>
                                        <?php foreach ($assignInspector_list as $key =>$value){
                                            if(!empty($value['inspector_name'])){?>
                                        <tr id = "<?php print $value['inspectorID'];?>">
                                          
                                                <td><center><?php echo $value['inspectorID']; ?></center></td>
                                                <td>
                                                    <center><?php echo $value['inspector_name']; ?>&nbsp&nbsp
                                                       <?php if(!empty($value['display_url']) && (count($value['display_url']) == 4)){?>

                                                        <?php }else{?>
                                                            <a href="#" data-toggle="tooltip" data-placement="right" title="<?php $r = implode(" , ",$value['display_title']);print $r;?>">
                                                               <i class="glyphicon glyphicon-alert" style="color:#ff0000;"></i>
                                                            </a>
                                                        <?php }?>
                                                    </center>
                                                </td>
                                                
                                                <td><center><?php echo $value['inspector_jobCard']; ?></center></td>
                                                <td align="center" class="action">
                                                    <a style="margin-right:5px;" href="<?php echo base_url("manage_kare/edit_doc?inspectorID=".$value['inspectorID']); ?>" class="edit" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
                                                </td>
                                        </tr>
                                        <?php }}?>
                                    </tbody>
                                <?php  }?>   
				</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->  
<?php $this->load->view('includes/footer'); ?> 

<!-- Scripts -->  
<?php $this->load->view('includes/scripts'); ?> 
