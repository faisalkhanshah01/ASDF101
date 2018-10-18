<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<style type="text/css" >
.home-heading {
    background-color: #ffffff !important;
    color: #060606 !important;
    padding-left: 15px;
    font-weight: bold;
    font-size: 17px;
    padding: 10px 15px;
    text-transform: uppercase;
}

th {
    background-color: #ffffff ;
    color: 333333 ;
	text-align: center;
	
}
.bgheadercol{ background-color: #b7d557 ; color:#333333; }
.bgheaderwhtcol{ background-color: #ffffff !important ; color:#333333 !important ; }
.td_padding{ padding:4px; }
.td_ppadding{ padding:20px; }
table {
	border-collapse: collapse !important;
	text-align: left;
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	font-size:12px;     /*ravi kumar */
}
</style>
<?php $this->load->view('includes/header_mobile'); ?>
<div class="row" style="background-color: #FFFFFF; color: #333333; ">
	<div class="table-responsive">
			<table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:1px solid #333333;" >
			
			<tr>
				<td colspan="1" style="clear:both;padding:10px;">
							<img src="<?=base_url()?>includes/images/kratos_logo.png" style="max-height:50px;" alt="Header Image" align="middle" >
				</td>
				<td colspan="2" style="clear:both;font-size:16px; font-weight: bold;padding:10px;background-color:#90CB32; text-align:center;">
							EC DECLARATION <BR/>OF CONFORMITY
				</td>				
			</tr>

			<tr>
				<td colspan="3" align="center" style="">
                  <div style="padding:20px !important;max-with:200px; float:left; text-align:left;"  >
				  <strong>KRATOS SAFETY</strong><BR/>
				  689 Chemin du Buclay<BR/>
				  Liew-dit la Muriere<BR/>
				  38540 Heyrieux
				  </div>
				  <BR style="clear:both;">
				  <div style="padding:20px !important;float:left; text-align:left;"  >
				  Declare that the Personal Protective Equipment(PPE) againt falls from new height described below.
				  </div>
				</td>					
			</tr>
			<tr>
				<td colspan="3"  align="center"> 
						<table width="100%" border="1" cellspacing="5" cellpadding="5" >
						<tr>
							<th  align="center" valign="middel" class="bgheadercol td_padding">
							   <strong>Ref</strong>
							</th>	
							<th  align="center" valign="middel" class="bgheadercol td_padding">
							   <strong>Serial Number</strong>
							</th>
							<th  align="center" valign="middel" class="bgheadercol td_padding">
							   <strong>Batch Number</strong>
							</th>	
							<th  align="center" valign="middel" class="bgheadercol td_padding">
							   <strong>Date of <BR/>Manufacture</strong>
							</th>
							<th  align="center" valign="middel" class="bgheadercol td_padding" width="24%">
							   <strong>Description</strong>
							</th>
							<th  align="center" valign="middel" class="bgheadercol td_padding">
							   <strong>&nbsp;Standard&nbsp;</strong>
							</th>
							<th  align="center" valign="middel" class="bgheadercol td_padding">
							   <strong>EC Type Examination Certificate N<sup>&copy;</sup></strong>
							</th>							
						</tr>
                       <?php if( !empty($product_values) &&  is_array($product_values)){ 
						     foreach($product_values AS $key=>$val){
						   ?>
						<tr>
							<td  align="center" valign="top" class="td_padding" >
							  <?php echo $ref_id; ?>
							</td>	
							<td  align="center" valign="top" class="td_padding" >
							   <?php echo $serial_id; ?>
							</td>
							<td  align="center" valign="top" class="td_padding" >
							   <?php echo $batch_no; ?>
							</td>	
							<td  align="center" valign="top" class="td_padding" >
							   <?php echo $manufacture_date; ?>
							</td>
							<td  valign="top" class="td_padding" >
							    <?php  if( $type == 'asset_series' ) { echo $val['product_description']; }else if( $type == 'assets' ){ echo $val['component_description'];} ?>
							</td>
							<td  align="center" valign="top" class="td_padding" >
							    <?php echo $val['standardName']; ?>
							</td>
							<td  align="center" valign="top" class="td_padding" >
							    <?php echo $val['ec_type_certificate_text']; ?>
							</td>							
						</tr>
						<tr>
							<td valign="top" colspan="100%">
								<div class="row" style="padding:0px; margin:0px;">
									<div class="col-sm-12 td_ppadding">
									  <p>Compile with provisions of Directive 89/686/EEC, as well as with national standards transposing harmonized standards.</p>
									  <p>Is indentical to the PPE which has been the subject of the corresponding EC type-examination certificate issued by:</p>
									  <div style="text-align:center;" ><?php echo $val['notifiedName']; ?></div>
									  <p>Shall be subject to the procedure to in Article 11B of Directive 89/686/EEC, under the supervision of notified body:</p>
									  <div style="text-align:center;" ><?php echo $val['articleName']; ?></div>
									  
									</div>
								</div>
							</td>	
									
						</tr>
						<?php }}else{ ?>
						<tr>
							<td  align="center" valign="top" colspan="100%" class="td_padding" >
							  <p class="pull-left" >Compile with provisions of Directive 89/686/EEC, as well as with national standards transposing harmonized standards.</p><BR style="clear:both;" ><BR style="clear:both;" >
							  <p class="pull-left" >Is indentical to the PPE which has been the subject of the corresponding EC type-examination certificate issued by:</p><BR style="clear:both;" ><BR style="clear:both;" >
							  <p class="pull-left" >Shall be subject to the procedure to in Article 11B of Directive 89/686/EEC, under the supervision of notified body:</p><BR style="clear:both;" ><BR style="clear:both;" >
							</td>	
									
						</tr>
						<?php } ?>
						</table>
				</td>					
			</tr>
			
			<tr style="border-top: 1px solid #333333;" >
				<td colspan="100%" align="center">
				 <div class="row" >
						 <div class="col-sm-2" style="padding:10px;">
							Date at Heyrieux, the <?php echo $todate =  date("d/m/Y");?>
						 </div>	
						 <div class="col-sm-8" style="padding:10px;" >
							&nbsp;
						 </div>
						 <div class="col-sm-2" style="padding:10px;">
						   J P COUDERT<BR/> Director<BR/><img src="<?=base_url()?>includes/images/jpCoudert.png" alt="Header Image">
						 </div>						 
				 </div>
				</td>				
			</tr>


			<tr style="border-top: 1px solid #333333;" >
				<td colspan="100%" align="center"  valign="middle"  >
				 <div class="row" >
						 <div class="col-sm-2" style="padding:10px;">
							<img src="<?=base_url()?>includes/images/pdf_bottom_left.png" alt="Header Image">
						 </div>	
						 <div class="col-sm-8" style="padding:10px;" >
							Phone: +33 (00)4 72 48 78 27<BR/>FAX: +33 (00)4 72 48 58 32<BR/>VAT: FR 21 530	336 833<BR/>Siret: 530 336 833 00021
						 </div>
						 <div class="col-sm-2" style="padding:10px;">
						   <img src="<?=base_url()?>includes/images/pdf_bottom_right.png" alt="Header Image">
						 </div>						 
				 </div>
				</td>				
			</tr>
			
		</table>
		<BR style="clear:both;" ><BR style="clear:both;" >

	</div>	  
</div>