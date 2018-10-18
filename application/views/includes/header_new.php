<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="copyright" content="Copyright <?php echo date('Y');?> Flash On Mind, All Copyrights Reserved"/>
<meta name="designer" content="sachintyagi - Flash On Mind : shakti.singh@flashonmind .com"/> 
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="">
<meta name="author" content="">
	
	<script type="text/javascript" src="<?php echo $includes_dir;?>js/jquery.min.js"></script>
	<link rel="stylesheet" href="<?php echo $includes_dir;?>css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo $includes_dir;?>css/dataTables.min.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="<?php echo $includes_dir;?>css/custom.css">
	 <link rel="stylesheet" href="<?php echo base_url().'includes/'; ?>css/style23.css">
	<!-- <link rel="stylesheet" href="<?php //echo $includes_dir;?>css/structure.css?v=1.0">-->
<title>Kare	</title>
<script type="text/javascript">
	var base_url = '<?php echo $base_url; ?>';
</script>
<style>
.infonet_header, .data_on_demand li{
	margin: 5px 0px 5px 0px;
	
}

.copyrights{
	margin-top: 15px;
}

.call-mail{
	color: #ff0200;
}

.infonet_header .header_image{
	height: 32px;
    margin-top: 5%;
}

.infonet_header .search_icon{
	margin-top:8%;
}

.footer-img img{
	vertical-align: middle;
    height: 30px;
}

.infonet_header_search_form{
	width:550px; 
	margin-bottom:5px; 
	margin-top:-18px;
	margin-left:45%
}
.input_search{	
width:51%;
}

.footer strong{
	color: #333;
}
.data_on_demand strong{
	color: #FF0000;
}
.data_on_demand p{
	font-weight:600;
}
#about_us p{
	font-weight:200;
}
.data_on_demand{
	font-size: 16px;
}
</style>
</head>
<div id="loading" style="display:none"><center><h2><font color="white">Please wait file is getting ready for download..</font></h2></center></div>
<body>
	
	<div class="container"> <!--  Container starting here and ending in footer.php  -->
			<div class="row">
				<div class="col-md-12">
					<!--<div class="col-md-6 ">
						<img src="<?php echo $includes_dir ?>images/karam-kare-logo.png" title="kare logo" style="padding-bottom: 20px; padding-top: 20px;" width="250"/>
					</div>-->
					<?php
						$group = array_keys($this->session->flexi_auth['group']);
						if(empty($group[0])){
					?>
							<div style="float:right;padding-top:40px;">
									<a href="<?php echo $base_url;?>Infonet" class="btn btn-primary" role="button">Switch login Infonet</a> 
							</div>
					<?php }?>	
					<?php 
					   // $kdvalue = array_values($this->session->flexi_auth['group']);
						$kdkey = array_keys($this->session->flexi_auth['group']);
						if(($kdkey[0] == 10) || ($kdkey[0] == 11)){ 
					?>
							<div class="col-md-4">
									<img class="header_image" src="<?php echo $includes_dir ?>images/Karam_infonet_logo.jpg" title="Karam infonet logo" style="padding-bottom: 20px; padding-top: 20px;" width="250"/>
							</div>
							<div class="col-md-4">
								<div class="col-md-12 search_icon">
									<form action="<?php echo base_url('infonets/search/search'); ?>" method="get" name="search" >
											<div class="col-md-12 infonet_header_search_form" >
											<input type="text" id="search_value" name="search_value" class="input_search" placeholder="Type keyword for search"/>
											<button type="submit" id="search_infonet" name="search_infonet"><i class="glyphicon glyphicon-search"></i></button>
											</div>
											<?php
												 $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
												$_SESSION['current_url'] = $actual_link;
											?>
									</form>
								</div>
							</div>
					<?php }else{ ?>
							<div class="col-md-4">
									<img class="header_image" src="<?php echo $includes_dir ?>images/karam-kare-logo.png" title="Karam kare logo" style="padding-bottom: 20px; padding-top: 20px;" width="250"/>
							</div>
					<?php }?>

					<div class="col-md-4">
					<?php if($this->session->userdata('userImage')){
						$fullName = $this->session->userdata('firstName').' '.$this->session->userdata('lastName');
					?>
						<a class="navbar-brand pull-right hidden-xs" href="<?php echo $base_url;?>auth_public/update_account"> <img title="<?php echo $fullName; ?>" class="userImage" src="<?php echo $base_url;?>uploads/images/users/<?php echo $this->session->userdata('userImage'); ?>" width="80" height='70' alt="<?php echo $fullName; ?>" ></a>
					<?php }else if($this->session->userdata('firstName') !=''){ 
						$fullName = $this->session->userdata('firstName').' '.$this->session->userdata('lastName');
					?>
						<a id="popover" class="navbar-brand pull-right hidden-xs" href="<?php echo $base_url;?>auth_public/update_account"> <img title="<?php echo $fullName; ?>" class="userImage" src="<?php echo $base_url;?>uploads/images/users/defult_image.jpg" width="80" height='70' alt="<?php echo $fullName; ?>" ></a>
						
				<?php	} ?>
					</div>
				</div>
			</div>
			