<?php
$this->load->view('admin_panel/common/header', $header_data);
?>
<div class="col-md-10 col-md-offset-1 after-login-forms" id="admin-dashboard">
	<div class="col-md-12 form">
		<div class="row">
			<?php echo $this->session->flashdata('msg'); ?>
		</div>

		<div class="row client-list">
			<h4>Arresto Clients/Customer List</h4>
			<table class="table table-striped table-bordered" id="client-search-table">
				<thead>
					<tr>
						<th>Sr. No.</th>
                                                <th>Customer <br/>Username / Email</th>
                                                <th>Mobile</th>
						<th>Company Name </th>
                                                <th>Company slug </th>
						<th>Address</th>
						<!--<th>City</th>
						<th>Country</th>
						<th>No. of License</th>
						<th>License No.</th>
						<th>License Status</th>-->
                                                <th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
        
                                    <?php
                                      $count=0;
                                      foreach($clients as $client){
                                      $count++;
                                        #echo "<pre>";
                                        #print_r($client);
                                      if($client['uacc_username']=='varun.kaushik'){
                                          continue;
                                      }  
                                     ?>
                                    <tr id="cls-user-<?php echo  $client['customer_id']; ?>" class="">
						<td><?php echo $count; ?></td>
						<td><?php echo $client['uacc_username']."<br/>". $client['uacc_email']; ?></td>
                                                <td><?php echo $client['upro_phone']; ?></td>
                                                <td><?php echo $client['customer_company_name']; ?></td>
                                                <td><?php echo $client['customer_company_slug']; ?></td>
						<td><?php echo $client['customer_address']; ?></td>
                                                
						<?php /*<td><?php echo $client['customer_city']; ?></td>
						<td><?php echo $client['customer_country']; ?></td>
						<td><?php echo $client['customer_licence_no ']; ?></td>
						<td><?php echo $client['customer_licence_count']; ?></td>
						<td><?php echo $client['cutomer_licence_status']; ?></td>
                                                 */?> 

                                                <td class="cls-user-status"><?php echo ($client['activation_flag']==1)?'Active':'Inactive'; ?></td>
						<td>
                                                    <?php 
                                                     $customer_id=$client['customer_id'];
                                                     $action_class= ($client['activation_flag']==1)?'btn-danger':'btn-success';
                                                     $action= ($client['activation_flag']==1)?'Inactive':'Active';
                                                     ?>
<!--                                                    <button  class="btn <?php //echo $action_class; ?> cls-action-status"   data-user-id="<?php //echo $customer_id; ?>"><?php //echo $action; ?></button>-->
                                                    <a href="<?php echo $base_url."/auth_admin/client_edit/".$client['customer_id']; ?>">Edit</a> ||
                                                    <a href="<?php echo $base_url."/auth_admin/client_delete/".$client['customer_id']; ?>">Delete</a>
                                                </td>
					</tr>
			<?php }?>
					
		      </tbody>
			</table>
		</div>
	</div>
</div>
 
 <?php 
 $this->load->view('admin_panel/common/footer_after_login');
?>


<script type="text/javascript">
var BASE_URL = "<?php echo $base_url;?>/";
 $(document).ready(function(){
     
     $('.cls-action-status').click(function(e){
         
        debugger;
        var btn=$(this);
        var action=status= $(this).text();
        
        var  client_id=$(this).attr('data-user-id');
         $.ajax({
             url : BASE_URL+"xhr_controller/ajax_change_client_status/"+client_id+"/"+status,
             method:"GET",
             success:function(respo){
                 if(respo.action_code){
                     if(action =='Active'){
                         btn.text('Inactive');
                         btn.removeClass('btn-success').addClass('btn-danger');
                     }
                     if(action == 'Inactive'){
                         btn.text('Active');
                         btn.removeClass('btn-danger').addClass('btn-success'); 
                     }
                     btn.parents('tr').find('.cls-user-status').text(action);
                     alert(respo.action_message);
                 }
             },
             
             dataType:"json"
             
         });
         
     }).bind('.cls-user-status');
       
 });
 
</script>
