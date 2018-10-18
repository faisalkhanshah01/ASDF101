<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="<?php echo base_url('admin/auth_admin/dashboard'); ?>">
        <img src="<?php echo base_url(); ?>assets/images/system/arresto-logo.jpg" alt="Site Logo" width="200" title="Go to Dashboard">
      </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li class="<?php if($this->uri->segment(2)=="dashboard.html"){echo 'active';}?>"><a href="<?php echo base_url('admin/auth_admin/dashboard'); ?>" title="Go to Dashboard">Dashboard</a></li>
        <li class="dropdown <?php if(($this->uri->segment(2)=="new_customer_invitation.html") || ($this->uri->segment(2)=="new_customer_approval.html")){echo 'active';}?>">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="View Customer Registration sub menu">Registration <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="<?php if($this->uri->segment(2)=="new_customer_invitation.html"){echo 'active';}?>"><a href="<?php echo base_url('admin/auth_admin/new_customer_invitation'); ?>" title="Go to Customer Registration">New Customer Invite</a></li>
            <li class="<?php if($this->uri->segment(2)=="new_customer_approval.html"){echo 'active';}?>"><a href="<?php echo base_url('admin/auth_admin/manage_client_accounts'); ?>" title="Go to Customer Approval">Registered Clients</a></li>
          </ul>
        </li>
		
        <li class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" style="border: 1px solid  !important;" aria-expanded="false">Admin <b class="caret"></b></a>
                <ul class="dropdown-menu">
                        <li class="dropdown-header">Select Feature to Manage</li>
                        
                         <li>
                           <a href="<?php echo base_url('admin/auth_admin/manage_language');?>" style="border: 1px solid  !important;">Manage Language</a>
                        </li>

                        <li>
                           <a href="<?php echo base_url('admin/auth_admin/manage_client_accounts');?>" style="border: 1px solid  !important;">User Accounts</a>
                        </li>
                        
                        
                        
<!--			<li>
                            <a href="<?php //echo base_url('auth_admin/manage_user_groups');?>" style="background-color:  !important; border: 1px solid  !important;">User Groups</a>			
                        </li>
                        <li>
                          <a href="<?php //echo base_url('auth_admin/manage_privileges');?>" style="background-color:  !important; border: 1px solid  !important;">User Privileges</a>			
                        </li>-->

                        <!--<li class="active">
                                <a href="<?php //echo base_url('auth_admin/list_user_status/active')?>" style="background-color:  !important; border: 1px solid  !important;">Active Users</a>
                        </li>	
                        <li>
                                <a href="<?php //echo base_url('auth_admin/list_user_status/inactive')?>" style="background-color:  !important; border: 1px solid  !important;">Inactive Users</a>
                        </li>	
                        <li>
                                <a href="<?php //echo base_url('auth_admin/delete_unactivated_users')?>" style="background-color:  !important; border: 1px solid  !important;">Unactivated Users</a>
                        </li>-->	

                </ul>		
        </li>
			
        <?php if(($this->session->userdata('admin_account_type') == 1) || ($this->session->userdata('admin_account_type') == 2)) { ?>
          <li class="<?php if($this->uri->segment(2)=="manage_admin.html"){echo 'active';}?>"><a href="<?php echo base_url('admin/manage_admin.html'); ?>" title="Go to Admin">Admin</a></li>
        <?php } ?>

        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#" title="View Account sub menu">Welcome,
            <?php echo $this->flexi_auth->  get_user_identity(); ?>
           
           <?php /* echo $this->session->userdata('admin_first_name').' '.$this->session->userdata('admin_last_name').' ('.$this->session->userdata('admin_role').')'; */?>   
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="<?php if($this->uri->segment(2)=="profile.html"){echo 'active';}?>"><a href="<?php echo base_url('admin/auth_admin/profile'); ?>" title="Go to Profile">Profile</a></li>
            <li class="<?php if($this->uri->segment(2)=="update_password.html"){echo 'active';}?>"><a href="<?php echo base_url('admin/auth_admin/password_change'); ?>" title="Go to Change Password">Change Password</a></li>
            <li><a href="<?php echo base_url('admin/auth/logout'); ?>" title="Logout">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>