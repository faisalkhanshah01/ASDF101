<?php
$base_url = "http://" .$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ISBFABNETWORKS GUEST RECEIPT</title>
<?php 
$base_url = "http://" .$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
$this->load->helper('date');
//echo "<pre>";
//print_r($data);
?>
<style></style>
</head>

<body>
<div> 
  <!-- UPPERMOST PART -->
  <table  width="100%" cellspacing="0" rules="cols" cellpadding="10" border="" style="border-collapse: collapse;">
    <tr>
        <td align="left"><img style=" background-color:#F5F5F5;" src="http://isbfabnetwork.com/backend/wp-content/uploads/2015/08/fab-network-logo.png" alt="ISB-LOGO" title="ISB" width=""/><br /></td>
    </tr>
  </table>
  <!-- UPPERMOST PART END --> 
  
  <!-- MIDDLE PART -->
  <div>
    <table border="" width="100%" cellspacing="0" cellpadding="5"  rules="cols" style="border-collapse:collapse; padding:0px 10px 0px 10px;">
		<tr  >
			<td colspan="2" align="center"><strong>RECEIPT</strong></td>
		</tr>
		<tr>
			<td align="left" style="padding-left:10px">Mr. Ashish Poddar</td>
		</tr>
		<tr>
			<td align="right" style="padding-right:10px">Receipt No.: FAB/RT/2016/R/07-08</td>    
		</tr>
		
		<tr>
			<td align="right" style="padding-right:10px">Dated: <?php echo date("D").", ".date("d/M/Y");?></td>
		</tr>
    </table>
  </div>
  <!-- MIDDLE PART END --> 
  
  <!-- BOTTOM PART -->
	<div>
		<table border="1" width="100%" cellspacing="0" cellpadding="5">
			<tr>
				<th align="center" width="60%">PARTICULARS</th>
				<th align="center" width="20%"></th>
				<th align="center" width="20%">AMOUNT (`)</th>
			</tr>
			<tr>
				<td align="center" width="60%">Participant Fees for ISB FaB Roundtable August 2016 for: <br/>
					Mr. Ashish Poddar<br/>
					ISB Alumni Member
				</td>
				<td width="20%"></td>
				<td width="20%" align="right" >4,999.00</td>    
			</tr>
			<tr>
				<td align="right" width="60%"><strong>Total</strong></td>
				<td align="right" width="20%"></td>
				<td align="right" width="20%">4,999.00</td>    
			</tr> 
		</table>
	</div>
	<div style="padding-left:10px">(Rupees Four Thousand Nine Hundred and Ninety-NineOnly) </div>
	<div style="padding:50px 10px 0px 10px;"><i>This electronic document does not require any signatures.</i></div>
<!-- BOTTOM PART END -->
</div>
<div style="padding:80px 10px 0px 10px;" align="center">
<hr/>
ISB Alumni Family Business Forum (CIN U20296TG2015NPL099318)<br/>
Registered Office:INDIAN SCHOOL OF BUSINESS, Gachibowli,Hyderabad - 500032,Telangana, INDIAEmail:<br/> info@isbfabnetwork.com

</div>
</body>
</html>
