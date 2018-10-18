<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/head'); ?> 
	<div class="row">
            <div class="col-md-12" >
                <div class="panel panel-default">
                         <div class="panel-heading home-heading">
                                <span><?php if( $lang["password_change"]['description'] !='' ){ echo $lang["password_change"]['description']; }else{ echo 'Password Change'; }  ?></span>
                          </div>
			<div class="panel-body">	
				<div class="row"  id="alldatalogShow">
					<div class="col-xs-12">
						<table id="kare_password_change_datatable" class="table table-bordered table-hover tableview">
							<thead>
								<tr>
									<th><?php if( $lang["s_no"]['description'] !='' ){ echo $lang["s_no"]['description']; }else{ echo 'S. No.'; }  ?></th>
									<th><?php if( $lang["name"]['description'] !='' ){ echo $lang["name"]['description']; }else{ echo 'Name'; }  ?></th>
									<th><?php if( $lang["email_id"]['description'] !='' ){ echo $lang["email_id"]['description']; }else{ echo 'Email Id'; }  ?></th>
									<th><?php if( $lang["group_user"]['description'] !='' ){ echo $lang["group_user"]['description']; }else{ echo 'Group User'; }  ?></th>
									<th><?php if( $lang["email_status"]['description'] !='' ){ echo $lang["email_status"]['description']; }else{ echo 'Email Status'; }  ?></th>
									<th><?php if( $lang["action"]['description'] !='' ){ echo $lang["action"]['description']; }else{ echo 'Action'; }  ?></th>
								</tr>
							</thead>
							<?php if (!empty($users) && is_array($users)) {?>
							<tbody>
								<?php $i =1;
                                                                        foreach ($users as $key => $value) { 
                                                                            $admin = $value[$this->flexi_auth->db_column('user_group', 'name')];
                                                                            if($admin != 'Master Admin'){
                                                                          ?>
								<tr>
                                                                    <td><?php echo $i;?></td>
                                                                    <td>
                                                                        <?php echo $value['upro_first_name'].' '.$value['upro_last_name'];?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="<?php echo $base_url;?>auth_admin/userView?userID=<?php print $value['uacc_id'];?>">
                                                                            <?php echo $value[$this->flexi_auth->db_column('user_acc', 'email')];?>
                                                                        </a>
                                                                    </td>
                                                                    <td class="align_ctr">
                                                                            <?php echo $value[$this->flexi_auth->db_column('user_group', 'name')];?>
                                                                    </td>
                                                                    <td align="center">
                                                                        <?php echo ($value[$this->flexi_auth->db_column('user_acc', 'active')] == 1) ? 'Active' : 'Inactive';?>
                                                                    </td>
                                                                    <td class="text-center"><a href="<?php echo $base_url;?>auth_admin/passwordChange?userID=<?php print $value['uacc_id'];?>"><i class="glyphicon glyphicon-pencil"></i>
                                                                   </a></td>
                                                                </tr>
                                                                        <?php }$i++;} ?>
							</tbody>
                                                    <?php } else { ?>
                                                            <tbody>
                                                                    <tr>
                                                                            <td colspan="7" class="highlight_red">
                                                                                    No Data are available.
                                                                            </td>
                                                                    </tr>
                                                            </tbody>
                                                    <?php } ?>
						</table>
					</div>
				</div>
			</div>
                </div>
            </div>
	</div> <!-- end of row -->
	<?php $this->load->view('includes/footer'); ?>
          <script type="text/javascript">
            $(document).ready(function(){
                $("#kare_password_change_datatable").DataTable({
                    "order":[[ 0,"asc" ]]
               });
           });
        </script>   
<?php $this->load->view('includes/scripts'); ?>