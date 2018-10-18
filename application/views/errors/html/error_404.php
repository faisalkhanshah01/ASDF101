<?php defined('BASEPATH') OR exit('No direct script access allowed');
$ins= new CI_Controller();
$CI = &get_instance();
$CI->load->helper('url');


?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>


<?PHP /*
<style type="text/css">
::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }
::-webkit-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
 



</head>
<body>

<div id="container">
        <h1><?php echo $heading; ?></h1>
        <?php echo $message; ?>
</div>
  	
</body>
</html>
 */?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>404 - Page Not Found</title>
  <link rel="stylesheet" href='http://192.168.1.3/Mysites/site/assets/bootstrap/css/bootstrap.css' />
  <link rel="stylesheet" href='http://192.168.1.3/Mysites/site/assets/css/custom.css?ver=1.03' />
  <link rel="shortcut icon" href='http://192.168.1.3/Mysites/site/assets/images/system/favicon.ico' type="image/x-icon">
  <link rel="icon" href='http://192.168.1.3/Mysites/site/assets/images/system/favicon.ico' type="image/x-icon">
  <meta property="og:title" content='404 - Page Not Found' />
  <meta property="og:image" content='http://192.168.1.3/Mysites/site/assets/images/system/arresto-logo.jpg'>
  <meta property="og:description" content="Arresto Site">
</head>
<body class="before-login">
  <div class="container-fluid" id="before-login-layout">
      <div class="row">
<!--      <div class="col-md-12" id="header-before-login">
        <div class="logo">
          <img src='http://192.168.1.3/Mysites/site/assets/images/system/arresto-logo.jpg' width="300" >
        </div>
      </div>-->
        <div class="col-md-12 before-login-forms page-404" id="login">
            <div class="img-404">
              <h1>404 - Page Not Found</h1>
              <p><a href="http://192.168.1.3/Mysites/site/admin/login.html">Click here </a> to go homepage.</p>
              <img src='http://192.168.1.3/Mysites/site/assets/images/system/404.jpg' alt="404 Image" width="768">
            </div>
        </div>     
        <div class="col-md-8 col-md-offset-2" id="footer-before-login">
        <hr>
        <div class="col-md-6 col-md-offset-3">
          <a href="#">Terms</a>
          <a href="#">Privacy policy</a>
          <a href="#">Help</a>
          <p>Copyrights &copy; Reserved - 2017</p>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src='http://192.168.1.3/Mysites/site/assets/bootstrap/js/bootstrap.min.js'></script>
  <script src='http://192.168.1.3/Mysites/site/assets/js/custom.js?ver=1.01'></script>
</body>
</html>


