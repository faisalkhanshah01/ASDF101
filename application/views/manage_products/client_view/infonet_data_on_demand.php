<?php $this->load->view('includes/header_infonet_new'); ?>
<?php //$this->load->view('includes/head_infonet'); ?>	
<?php	//$this->load->view('includes/head');	?>
	<?php
		$groupId = $_SESSION['flexi_auth']['group'];
		foreach($groupId as $k=>$v){
			$name = $v;
			$groupID = $k;
		}
		
	?>
	<?php 	if ( $groupID == 11 || $groupID == 10){
				$this->load->view('includes/head_infonet');
			}else{ 
				$this->load->view('includes/head'); 
			}
	?>
	<div id="global_searchAllView">
		<div class="row data_on_demand">			
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading home-heading">
						<span>Data on Demand</span>
					</div>
					<div class="panel-body">
						<div class="col-md-12">
							<p><strong>Data-on-Demand</strong> helps to access information on real time basis with just sending a text message. There are times when :</p>
							<div>
								<ul>
									<li>Internet is not working.</li>
									<li>Laptop is not working / available.</li>
									<li>File size are big to share in Email with clients.</li>
									<li>Uploading data in third party file sharing apps take a long time.</li>
									<li>Due to Different time zones data, may be required at odd hours.</li>
									<li>Client is urgently needed information.</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12">
							<p><strong>Advantage</strong>
							<div>
								<ul>
									<li>Any time access to data</li>
									<li>Up dated information.</li>
									<li>Information from a single source</li>
								</ul>
							</div>
						</div>
						
						<div class="col-md-12">
							<p><strong>Process</strong></p>
							<p>Data on Demand helps to overcome these challenges. All you have to do is follow some simple instructions as below :</p>
							<div>
								<ul>
									<li>Register yourself by sending a text message << HELLO DATA YOUR NAME REGION >>to +91874 498 0222 for example: - << HELLO DATA DHANIRAM CO >>and send to +91874 498 0222.</li>
									<li>After receiving your message, the administrator will accept your request and update your number in the server and you shall be notified with following acknowledgement message.</li>
									<li> << You are successfully subscribed to Data on Demand >> .</li>
									
									<li>
										Now you are ready to use Data on demand. You can use the syntax of key words in the table below to send a request message. Please use a space after every word.
									</li>
									
									<table class="table table-bordered text-center">
										<thead>
											<tr>
												<th>Keyword</th>
												<th>Information</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>C</td>
												<td>Certificates of Product</td>
											</tr>
											<tr>
												<td>TR</td>
												<td>Test Report of Product</td>
											</tr>
											<tr>
												<td>TD</td>
												<td>Technical Data Sheet</td>
											</tr>
											<tr>
												<td>FV</td>
												<td>Youtube link of Product Films</td>
											</tr>
											<tr>
												<td>FD</td>
												<td>Downloadable link of Product Films</td>
											</tr>
											<tr>
												<td>U</td>
												<td>User Manual</td>
											</tr>
											<tr>
												<td>MENU</td>
												<td>MENU of Keywords</td>
											</tr>										
										</tbody>								
									</table>
									
									<li>Example: To get the link of technical data sheet for Item PN14, Type TD PN 14 in your message box and send to 874 498 0222. You will be prompted with a downloading link of Technical Data Sheet of Product PN 14.</li>
									<li>In case you do not receive a reply, kindly check the key word or the phone number to which the text message has been sent. You may contact dhanirams@karam.in for any query which remains unresolved.</li>
								</ul>
							</div>
						</div>
						
						<div class="col-md-12">
							<p><strong>Conclusion:</strong></p>
							<p>Enjoy the gift of Knowledge.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="global_searchViewShow">
		<div class="row">
			<div class="col-md-12">
				 <div id="global_search_view"></div>
			</div>
		</div>	
	</div>	
		
<?php $this->load->view('includes/new_footer');//$this->load->view('includes/infonet_footer'); ?>
<?php $this->load->view('includes/scripts_new'); ?>