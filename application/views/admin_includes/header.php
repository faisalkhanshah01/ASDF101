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
	<link rel="stylesheet" href="<?php echo $includes_dir;?>css/custom_admin.css">
        
	<link rel="stylesheet" href="<?php echo base_url().'includes/'; ?>css/style23.css">
	
	<!-- <link rel="stylesheet" href="<?php //echo $includes_dir;?>css/structure.css?v=1.0">-->
<title>Kare	</title>
<script type="text/javascript">
	var base_url = '<?php echo $base_url; ?>';
</script>

</head>

<body>
	
	<div class="container-fluid"> <!--  Container starting here and ending in footer.php  -->
			<div class="row">
				<div class="col-md-10">
					<!--<div class="col-md-6 ">
						<img src="<?php //echo $includes_dir ?>images/karam-kare-logo.png" title="kare logo" style="padding-bottom: 20px; padding-top: 20px;" width="250"/>
					</div>-->
					<?php	
                                        if( isset($this->session->flexi_auth['group'])){ $group = array_keys($this->session->flexi_auth['group']); }	
					?>

                                        <img class="header_image" src="<?php echo $includes_dir ?>images/system/arresto-logo.jpg" title="Arresto logo" style="padding-bottom: 20px; padding-top: 20px;" width="250"/>                       
                                                            
                                    <?php /*                        
					<div class="col-md-2 pull-right">	
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
                                     * 
                                     */?>
                                                                                                                          
				</div>
			</div> 
			