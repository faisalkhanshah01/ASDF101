<?php $this->load->view('includes/header'); ?>

	<?php $this->load->view('includes/head_infonet'); ?>
	
		<div id="global_searchAllView">
			<div class="row">
				<div class="col-md-12 data_on_demand">
					<section  style="margin: 0;padding: 0;position: relative; margin-left:-16px;">
						<div class="container">
							<div class="row">
								<div class="col-lg-12">
									   <img src="<?php echo base_url().'includes/'; ?>images/slider.png" title=""  width="100%"/>
								</div>
							</div>
						</div>
					</section>
				   <!-- <div class="col-md-12 text-center">
							<h4 class='elegantshadow'><em> Experience the digital and structured approach of information access with KARAM Infonet.</em></h4>
					</div>-->
				   <br/>
					<section>
						<div class="container">
							<div class="row">
								<div class="col-md-12" style="margin-left:-12px;">
									<p><strong>Karam Infonet</strong> is a platform for you to enlighten with organization’s products portfolio and drives you towards to overcome the challenges for accessing real time information from a single window.</p>
									<br/>
									<p>Product technical information can be accessed in following ways: </p>
								</div>
							</div>
						</div>    
					</section>

					<div class="tab-img-data">
						<ul id = "myTab" class = "nav nav-tabs">
						<li class = "col-12 col-md-4 col-sm-6" title="Online Portal"><a href = "#online-portal" data-toggle = "tab"><img class="img-responsive"  style="border: hidden;" id="image-3" src="<?php echo base_url().'includes/'; ?>images/image_6.png"></a></li>
							<li class = "col-12 col-md-4 col-sm-6" title="Data On Demand"><a href = "#data-on-demand" data-toggle = "tab"><img class="img-responsive" id="image-1" src="<?php echo base_url().'includes/'; ?>images/image_4.png"></a></li>
							<li class = "col-12 col-md-4 col-sm-6" title="Mobile Application"><a href = "#mobile-app" data-toggle = "tab"><img class="img-responsive" style="border: hidden;" id="image-2" src="<?php echo base_url().'includes/'; ?>images/image_5.png"></a></li>
						</ul>
					</div>
					<div id = "myTabContent" class = "tab-content">
						<div class="tab-pane fade" id = "data-on-demand">
							<span class="glyphicon glyphicon-remove pull-right" title="Close"></span>
							<p>Data-on-Demand helps to access information on real time basis with just sending a text message. Just you need to send an SMS with a keyword in +91 874 498 0222 and in response of keyword would receive downloading link of data instantly.<a href="http://karam.in/kare/infonet_details/data_on_demand" title="Read More"> Read More</a></p>
						</div> 
						<div class="tab-pane fade" id = "mobile-app">
							<span class="glyphicon glyphicon-remove pull-right" title="Close"></span>
							<p>To ease out the product technical information access from your mobile at any time and from anywhere, all you need to download the Mobile App from Google Store. Start using the services after login with your Karam Infonet individual credentials i.e. User Name and Password.</p>
						</div>
						<div class="tab-pane fade" id = "online-portal">
							<span class="glyphicon glyphicon-remove pull-right" title="Close"></span>
							<p>KARAM Infonet online portal is a platform where product related technical information is readily available in real time with easy access to user.<br/><br/>Product Technical information can be accessed by logging with your individual credentials i.e. User Name and Password from online portal <a href="http://karam.in/kare/infonet_details/about_us" target="_blank">karam.in/kare/Infonet</a>.<br><br/>In this portal, product’s technical information is organized in a structured hierarchical way with product categories under tab Product Portfolio. You can easily find all information in a single page related to a particular Item without navigating into multiple pages. Apart from this, search tab in top left corner will help you to find anything related to a particular product i.e. product picture, technical data sheet, certificate, test reports, presentations etc.</p>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				$(".glyphicon-remove").on('click', function() {
					$('.tab-pane').removeClass('active')
									.removeClass('in');
					$('.tab-img-data ul li').removeClass('active');
				});
			</script>
		</div>
		<div id="global_searchViewShow">
			<div class="row">
				<div class="col-md-12">
					 <div id="global_search_view"></div>
				</div>
			</div>	
		</div>
		
<?php $this->load->view('includes/new_footer'); ?>
<?php $this->load->view('includes/scripts_new'); ?>