<?php $this->load->view('includes/header'); ?>
<script>
    $(document).ready(function(){	
        $(".datepicker").each(function(){
            $(this).datepicker({
                        showOtherMonths:true,
                        dateFormat: 'yy-mm-dd' 
                });
        });  
        
        $("#ajaxdatalogShow").hide();
        $("#alldatalogShow").show();
        $( ".submit" ).click(function() {
            //var r = base_url + "auth_admin/admin_logs_view";
             $("#ajaxdatalogShow").show();
             $("#alldatalogShow").hide();
            
                var toDate = $("#criteria").find("input[name =toDate]").val();
                var fromDate = $("#criteria").find("input[name =fromDate]").val();
                  
		$.ajax({
			type: "POST",
                        //dataType: "json",
			url: base_url + "Auth_admin/admin_log",
			data: {toDate:toDate,fromDate:fromDate},
			success: function(output){
                            $('#logs_view').html(output);
                            $("#kare_logs_view_datatable1").DataTable({
					"order":[[ 0,"asc" ]]
				});
			},
			error: function(){
				alert('Error while request ajax...');
			}
		});	// end of ajax
		return false;
	});
	
	$(".exportList").click(function() {
            var toDate = $("#criteria").find("input[name =toDate]").val();
            var fromDate = $("#criteria").find("input[name =fromDate]").val();
            var url = <?php print "'". base_url()."Manage_kare/exportAnalytics?exportAnalytics=3&toDate='";?>+ toDate + '&fromDate=' + fromDate;
            window.open(url); 
	    return false; 
        });
    });    
</script>
	<?php $this->load->view('includes/head'); ?> 
	 <div class="row">
            <div class="col-md-12" >
                <div class="panel panel-default">
                    <div class="panel-heading home-heading">
                          <span><?php if( $lang["logs_data"]['description'] !='' ){ echo $lang["logs_data"]['description']; }else{ echo 'Logs data'; }  ?></span>
                    </div>
                    <div class="panel-body">
                        <form id="criteria" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3"><?php if( $lang["date"]['description'] !='' ){ echo $lang["date"]['description']; }else{ echo 'Date'; }  ?></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php if( $lang["from"]['description'] !='' ){ echo $lang["from"]['description']; }else{ echo 'From'; }  ?> <i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="text" name="fromDate" value = "<?php if(isset($_REQUEST['fromDate'])) print $_REQUEST['fromDate']; ?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <span class="input-group-addon"><?php if( $lang["to"]['description'] !='' ){ echo $lang["to"]['description']; }else{ echo 'To'; }  ?> <i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="text" name="toDate" value = "<?php if(isset($_REQUEST['toDate'])) print $_REQUEST['toDate']; ?>"/>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3"></label>
                                <div class="col-sm-3">
                                    <button class = "submit btn btn-primary" type="submit"  value="Submit"><span><span><?php if( $lang["update"]['description'] !='' ){ echo $lang["update"]['description']; }else{ echo 'Update'; }  ?></span></span></button>
                                    <button class = "btn btn-success exportList" type="submit"  value="Export List"> <span class="glyphicon glyphicon-download-alt"></span> <?php if( $lang["export_list"]['description'] !='' ){ echo $lang["export_list"]['description']; }else{ echo 'Export List'; }  ?></button>
                                </div>
                            </div>

                        </form>
                    </div>   
                </div>
            </div>
        </div>    

	<div class="row">
            <div class="col-md-12" >
                <div class="panel panel-default">
			<div class="panel-body">	
				<div class="row"  id="alldatalogShow">
					<div class="col-xs-12">
						<table id="kare_logs_view_datatable" class="table table-bordered table-hover tableview">
							<thead>
								<tr>
									<th><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo 'S. No.'; }  ?></th>
									<th><?php if( $lang["group_name"]['description'] !='' ){ echo $lang["group_name"]['description']; }else{ echo 'Group Name'; }  ?></th>
									<th><?php if( $lang["name"]['description'] !='' ){ echo $lang["name"]['description']; }else{ echo 'Name'; }  ?></th>
									<th><?php if( $lang["email_id"]['description'] !='' ){ echo $lang["email_id"]['description']; }else{ echo 'Email Id'; }  ?></th>
									<th><?php if( $lang["event_type"]['description'] !='' ){ echo $lang["event_type"]['description']; }else{ echo 'Event Type'; }  ?></th>
									<th><?php if( $lang["ip_address"]['description'] !='' ){ echo $lang["ip_address"]['description']; }else{ echo 'IP Address'; }  ?></th>
									<th><?php if( $lang["time"]['description'] !='' ){ echo $lang["time"]['description']; }else{ echo 'Time'; }  ?></th>
									<th><?php if( $lang["date"]['description'] !='' ){ echo $lang["date"]['description']; }else{ echo 'Date'; }  ?></th>
								</tr>
							</thead>
							<?php if (!empty($logsData) && is_array($logsData)) { ?>
							<tbody>
								<?php $i =1;
                                                                        foreach ($logsData as $key => $value) { ?>
								<tr>
                                                                    <td><?php echo $i;?></td>
                                                                    <td><?php echo $value['description'];?></td>
                                                                    <td><?php echo $value['name'];?></td>
                                                                    <td><?php echo $value['email'];?></td>
                                                                    <td><?php echo $value['process'];?></td>
                                                                    <td><?php echo $value['ip_address'];?></td>
                                                                    <td><?php echo $value['time'];?></td>
                                                                    <td><?php echo $value['date'];?></td>
                                                                </tr>
								<?php $i++;} ?>
							</tbody>
                                                    <?php } else { ?>
                                                            <tbody>
                                                                    <tr>
                                                                            <td colspan="7" class="highlight_red">
                                                                                    <?php if( $lang["no_logs_data_are_available"]['description'] !='' ){ echo $lang["no_logs_data_are_available"]['description']; }else{ echo 'No Logs Data are available.'; }  ?>
                                                                            </td>
                                                                    </tr>
                                                            </tbody>
                                                    <?php } ?>
						</table>
					</div>
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $("#kare_logs_view_datatable").DataTable({
                                                    "order":[[ 0,"asc" ]]
                                               });
                                           });
                                        </script>
				</div>
                                
                                <div class="row" id="ajaxdatalogShow">
                                    <div class="col-xs-12">
                                         <spam id="logs_view"></spam>
                                    </div>  
                                </div>    
			</div>
                </div>
            </div>
	</div> <!-- end of row -->
	<?php $this->load->view('includes/footer'); ?>
       
<?php $this->load->view('includes/scripts'); ?>