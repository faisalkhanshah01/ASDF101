<style type="text/css">
.clientImage{    
	display: inline-block;
    width: 21px;
    border-radius: 50%;
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
}
</style>

<div id="wrapper">
	<!-- Navigation -->
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation"> 
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
		<img src="<?php echo $includes_dir ?>images/karam_logo.png" title="Karam.in" alt="Karam.in" height="50" style="background-color:#fff;">
		<a class="navbar-brand" href="<?php echo base_url('auth'); ?>"> Admin Panel</a>
	</div>
	<!-- Top Menu Items -->
	<?php 
		$html='';
		if($this->session->userdata('userImage')){
			$fullName = $this->session->userdata('firstName').' '.$this->session->userdata('lastName');		
			$html	=	'<img title="'.$fullName.'" class="clientImage" src="'.$base_url.'uploads/images/users/'.$this->session->userdata('userImage').'" alt="'.$fullName.'" >';
		}
	?>
	<ul class="nav navbar-right top-nav">
		<li class="dropdown">
			<!--<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i>  Welcome <?php echo "Admin"; ?><b class="caret"></b></a>-->
			<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $html;?>  Welcome <?php echo "Admin"; ?><b class="caret"></b></a>
			<ul class="dropdown-menu">
				<li>
					<a href="<?php echo base_url();?>profile"><i class="fa fa-fw fa-user"></i> Profile</a>
				</li>
				<li>
					<a href="<?php echo base_url();?>editprofile"><i class="fa fa-fw fa-table"></i>edit profile </a></li>
				<li> 
				<li>
					<a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
				</li>
				<li>
					<a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
				</li>

				<li class="divider"></li>
					<li> <a href="<?php echo base_url('auth/logout');?>"><i class="fa fa-fw fa-table"></i>Log Out</a></li>
				</li>
			</ul>
		</li>
	</ul>
	<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->

	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav side-nav">
			<li> <a href="<?php echo $base_url;?>auth_admin/"><i class="glyphicon glyphicon-home"></i> Dashboard</a> </li>

			<li>
				<a href="javascript:;" data-toggle="collapse" data-target="#user-submenu">
				<i class="glyphicon glyphicon-user"></i> Manage Users <i class="fa fa-fw fa-caret-down"></i></a>
				<ul id="user-submenu" class="collapse">
					<li>
						<a href="<?php echo base_url("auth_admin/register_account"); ?>">Create User Accounts</a>
					</li>
					<li>
						<a href="<?php echo base_url("auth_admin/manage_user_accounts"); ?>">Manage User Accounts</a>
					</li>
					<li>
						<a href="<?php echo base_url("auth_admin/manage_user_groups"); ?>"> Manage User Groups</a>
					</li>
					<li>
						<a href="<?php echo base_url("auth_admin/manage_privileges"); ?>">Manage User Privileges</a>
					</li>
					<!--<li>
					<a href="#">List Active Users</a>
					</li>
					<li>
					<a href="#">List Inactive Users</a>
					</li>
					<li>
					<a href="#">List Unactivated Users</a>
					</li>
					<li>
					<a href="<?php //echo base_url("auth_admin/failed_login_users"); ?>">List Failed Logins</a>
					</li>-->
				</ul>
			</li>
			<li>
				<a href="javascript:;" data-toggle="collapse" data-target="#continent-submenu">
				<i class="glyphicon glyphicon-book"></i> Continents & Countries <i class="fa fa-fw fa-caret-down"></i></a>
				<ul id="continent-submenu" class="collapse">
					<li>
						<a href="<?php echo base_url('continent');?>">Add Continents & Countries</a>
					</li>
					<li>
						<a href="<?php echo base_url('continent/list_continents'); ?>">List Contients</a>
					</li>
					<li>
						<a href="<?php echo base_url('continent/list_countries'); ?>">List Countries</a>
					</li>
				</ul>
			</li>


			<li>
				<a href="javascript:;" data-toggle="collapse" data-target="#page-submenu">
				<i class="glyphicon glyphicon-book"></i> Manage Static Pages  <i class="fa fa-fw fa-caret-down"></i></a>
				<ul id="page-submenu" class="collapse">
					<li>
						<a href="<?php echo base_url('page');?>">Create Page</a>
					</li>
					<li>
						<a href="<?php echo base_url('page/list_pages'); ?>">List Pages</a>
					</li>
				</ul>
			</li>
			
			<li>
<a href="javascript:;" data-toggle="collapse" data-target="#video-submenu"><i class="glyphicon glyphicon-facetime-video"></i> Manage Videos  <i class="fa fa-fw fa-caret-down"></i></a>
<ul id="video-submenu" class="collapse">
<li>
<a href="<?php echo base_url('video'); ?>">Create Video</a>
</li>
<li>
<a href="<?php echo base_url('video/list_videos'); ?>">List Videos</a>
</li>
</ul>
</li>

<li>
<a href="javascript:;" data-toggle="collapse" data-target="#travelQuiz-submenu"><i class="glyphicon glyphicon-book"></i> Manage Travel Quiz  <i class="fa fa-fw fa-caret-down"></i></a>
<ul id="travelQuiz-submenu" class="collapse">
<li>
<a href="<?php echo base_url('travelquiz'); ?>">Create Travel Quiz</a>
</li>
<li>
<a href="<?php echo base_url('travelquiz/list_travelQuiz'); ?>">List Travel Quiz</a>
</li>
</ul>
</li>

<li>
<a href="javascript:;" data-toggle="collapse" data-target="#travelpoll-submenu"><i class="glyphicon glyphicon-book"></i> Manage Poll  <i class="fa fa-fw fa-caret-down"></i></a>
<ul id="travelpoll-submenu" class="collapse">
<li>
<a href="<?php echo base_url('poll'); ?>">Create Poll</a>
</li>
<li>
<a href="<?php echo base_url('poll/list_poll'); ?>">List Poll</a>
</li>
</ul>
</li>

<li>
<a href="javascript:;" data-toggle="collapse" data-target="#travelwidgets-submenu"><i class="glyphicon glyphicon-book"></i> Manage widgets<i class="fa fa-fw fa-caret-down"></i></a>
<ul id="travelwidgets-submenu" class="collapse">
<li>
<a href="<?php echo base_url('widgets'); ?>">Hotel</a>                                
</li>
</ul>
</li>


<li> <a href="<?php echo base_url("auth/logout");?>"><i class="fa fa-fw fa-table"></i>Log Out</a> </li>
			
			
		</ul>
	</div>
<!-- /.navbar-collapse --> 
</nav>
