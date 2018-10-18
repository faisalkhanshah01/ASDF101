<!-- JS Includes -->
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
-->
<script src="<?php echo $includes_dir;?>js/jquery.min.js"></script>
<script src="<?php echo $includes_dir;?>js/jquery-ui.js"></script>
<script src="<?php echo $includes_dir;?>js/bootstrap.min.js"></script>
<script src="<?php echo $includes_dir;?>js/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir;?>js/jquery.validate.js"></script>
<script src="<?php echo $includes_dir;?>js/custom.js"></script>
<script src="<?php echo $includes_dir;?>js/search.js"></script>
<script src="<?php echo $includes_dir;?>js/specification.js"></script>
<script src="<?php echo $includes_dir;?>js/productedit.js"></script>
<script type="text/javascript">
 		   $(document).ready(function () {
				$('#popover').popover();
		   })
</script>
<script type="text/javascript">

	

        // form validation 
	$(document).ready(function(e) 
	{
		
		$(document).on('mouseenter','.client_popup',function(){
			$( this ).popover( "toggle" );
		});
		
		   $("#inspector_table").DataTable({
		   		   "order":[[ 0,"asc" ]]
				    
		   });

			$("#componentForm").validate({
					rules:{
						email:{
							},
						password:{
							required:true
							},
							field1:{
							  required :true
							}			
						}
				 });
				 
	});	

     
		$(document).ready(function(e) {
				$("#components_table").dataTable();
				$("#approved_table").dataTable();
				$("#rejected_table").dataTable();
				$(".assign_site_table").dataTable();
				
				$("#inspection_result_table").dataTable();
				$("#siteidMissingTable").dataTable();
				
				/* Limit the fetching of records from server side. Done by Shakti Singh */	
				var oMasterTable = $('#master_data_table').dataTable({
					'bProcessing': true, 
					'bServerSide': true, 
					'sAjaxSource': '<?php echo base_url('SiteId_kare/angular') ?>?action=getMasterDataAjax'
				});
				//$("#sub_assets_table").dataTable();
				var oSubAssetsTable = $('#sub_assets_table').dataTable({
					'bProcessing': true, 
					'bServerSide': true, 
					'sAjaxSource': '<?php echo base_url('SiteId_kare/angular') ?>?action=getsubAssetDataAjax'
				});
				
				var oSiteIDTable = $('#siteID_master_table').dataTable({
					'bProcessing': true, 
					'bServerSide': true, 
					'sAjaxSource': '<?php echo base_url('SiteId_kare/angular') ?>?action=getSiteIdAjax'
				});
				
				var oSMSTable = $('#sms_component_table').dataTable({
					'bProcessing': true, 
					'bServerSide': true, 
					'sAjaxSource': '<?php echo base_url('SiteId_kare/angular') ?>?action=getSmsComponentAjax'
				});
				
				var oAssetTable = $('#asset_table').dataTable({
					'bProcessing': true, 
					'bServerSide': true, 
					'sAjaxSource': '<?php echo base_url('SiteId_kare/angular') ?>?action=getAssetAjax'
				});
				
				var oClientTable = $('#table_client_list').dataTable({
					'bProcessing': true, 
					'bServerSide': true, 
					'sAjaxSource': '<?php echo base_url('SiteId_kare/angular') ?>?action=getClientAjax'
				});
				
				var oAssestSeriesTable = $('#assetSeries_table').dataTable({
					'bProcessing': true, 
					'bServerSide': true, 
					'sAjaxSource': '<?php echo base_url('SiteId_kare/angular') ?>?action=getAssetSeriesAjax'
				});

				var oClientTable = $('#table_lang_list').dataTable({
					'bProcessing': true, 
					'bServerSide': true, 
					'sAjaxSource': '<?php echo base_url('Language_controller/angular') ?>?action=getLanguageAjax'
				});
		
				// change the row background color :
				$("#master_table tr").dblclick(function(){
				    if(!$(this).prop('selected')){
					  $(this).prop('selected',true);
					  $(this).css("background-color","#dff0d8"); 
				    }else{
						$(this).prop('selected',false);
						$(this).css("background-color","white");
					}	 	 
				});
			});



 		   $(document).ready(function () {
			   	var url = window.location;
        		$('ul.nav a[href="'+ url +'"]').parent().addClass('active');
        		$('ul.nav a').filter(function() {
             		return this.href == url;
        			}).parent().addClass('active');	
    		});
			
			
	$(document).ready(function(e) {
		 
		 $("#search_assetSeries").keyup(function(e){
			 var query=$(this).val();
			 if(1){
				 var searchStr="search="+query;
				 $.get("<?php echo base_url('manage_kare/ajax_get_assetSeries') ?>",searchStr,function(data){
					// alert(data);
					 $(".search-container").html(data);	 
				 });
			 }
		 })
		
		
		$("#search_tool").keyup(function(e){
			 var query=$(this).val();
			 if(1){
				 var searchStr="search="+query;
				 $.get("<?php echo base_url('manage_kare/ajax_get_components') ?>",searchStr,function(data){
					// alert(data);
					 $(".search-container").html(data);	 
				 });
			 }
		})
		  
		 
		$(".search-container").on("click",":checkbox",function(){
			if(this.checked){
				$(this).parent("p").css("background-color","#9FF");
			} else{
				$(this).parent("p").css("background-color","white"); 
			}	 
		});
		 
		 
		$("#com_sel_btn").click(function(){
			// get all the selected values 
			var component_list=[];
			$("input:hidden").each(function(){
				component_list.push($(this).val());				       	
			});
			
			$("div#sel_component input:checked").each(function(index,ele){

				component=$(this).val();
				if(component_list.indexOf(component)!=-1){
					alert( component + " already in list");
					
				}else{
					$("<p class='bg-success'>"+component+'<span class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component" name="product_components[]" value="'+component+'"/>'
					+"</p>").appendTo("#product_component");
				}
				$(this).prop("checked",false);
				$(this).parent("p").css("background-color","#FFF");
			   
			});
		});
			
		$("#product_component").on("click",".glyphicon-trash",function(){
			$(this).parent("p").remove();
		});
		 
		 
		 
		var counter = 0;
		var con = 0;
		var component_list=[]; 
		$("#com_sel_btn_master").click(function(){
			if(con == 0 && $('#product_component_master').data('name') == 'mdata_edit'){
				var no_of_values =  $('#product_component_master').attr('rel');
				counter = parseInt(no_of_values) + 1;
				con++;
			}

			// get all the selected values 
			var component_list=[]; 
			$("input:hidden").each(function(){
				component_list.push($(this).val());
			});
			
			$("div#sel_component input:checked").each(function(index,ele){
			
				var parentID = $(this).parent("p").attr("id");
				component=$(this).val();
				product_component_selected=$(this).attr('rel');
				if(component_list.indexOf(component)!=-1){
					alert( component + " already in list");
				}else{
					var compo =  $.parseJSON(product_component_selected);
				
					$("<div id='pro"+counter+"' class='bg-success product_main'><b>"+component+'</b><span data-singh="'+parentID+'" rel="'+counter+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component" name="product_components[]" value="'+component+'"/>'
					+"</div></p>").appendTo("#product_component_master");
					
					counter++;
				}
			
				$(this).prop("checked",false);
				$(this).parent("div").css("background-color","#FFF");
			});
		});
		 
		 
		$("#product_component_master").on("click",".glyphicon-trash",function(){

			var parentIDS = $(this).data("singh");
			$("p#"+parentIDS).css("background-color","#FFFFFF");

			var rel = $(this).attr('rel');
			$(this).parent("div").remove();
			if ( $(".bg-success").parents("#product_component_master").length < 1 ) { 
				$('#product_component_master').html('');
				con = 0;
				counter = 0;
				$('#product_component_master').attr('rel',0);
			}
		});

		 
		/* Search Site ID for Inspector */
			$("#search_tool_siteID_inspector").keyup(function(e){
				var query=$(this).val();
				var jobCard = $('#jc_number').val();
				var sms_number = $('#sms_number').val();
				if(jobCard != '' && sms_number!=''){
					if(query ==''){
						var value = 'blank';
					}else{
						var value = query;
					}
					$.get("<?php echo base_url('SiteId_kare/ajax_search_get_siteID') ?>",
						{ 'jobCard': jobCard, 'sms_number': sms_number, 'search' : value },
						function(data){
						$(".search-jobCardNo").html(data);	 
					});
				}
			})
		
			/*
			$(".search-jobCardNo").on("click",":checkbox",function(){
				if(this.checked){
					is).parent("p").css("background-color","#9FF");
				} else{
					$(this).parent("p").css("background-color","white"); 
				}	 
			});
			*/
			
		
		var jobCard_list=[];
		$("#com_sel_btn_jobCard").click(function(){
				jobCard_list=[];
			    $("input.job:hidden").each(function(){
					jobCard_list.push($(this).val());				       	
				});
				
			  $("div#sel_jobCardNo input:checked").each(function(index,ele){
			    var jobCard_key=$(this).val();
				var jobCard_value=$(this).attr('rel');
				if(jobCard_list.indexOf(jobCard_key)!=-1){
					alert( jobCard_value + " already in list");
				}else{
				$("<p class='bg-success'>"+jobCard_value+'<span rel="'+jobCard_value+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component job" name="site_id[]" value="'+jobCard_key+'"/>'
					+"</p>").appendTo("#selected_jobCardNo");
				}
				$(this).prop("checked",false);
				$(this).parent("p").css("background-color","#FFF");
			   });
		 });
		 
		 $("#selected_jobCardNo").on("click",".glyphicon-trash",function(){
			var ids = $(this).attr("rel");
			
			jobCard_list = jQuery.grep(jobCard_list, function( n ) {
			  return ( n !== ids );
			});
			$(this).parent("p").remove();
		});
		
/* assign client changes 9-1-1991 start 
*
*
*
*/
		
		var clientUser_list=[];
		$("#com_sel_btn_clientUser").click(function(){
				clientUser_list=[];
			    $("input.job:hidden").each(function(){
					clientUser_list.push($(this).val());				       	
				});
				
			  $("div#sel_clientUser input:checked").each(function(index,ele){
			    var clientUser_key=$(this).val();
				var clientUser_value=$(this).attr('rel');
				if(clientUser_list.indexOf(clientUser_key)!=-1){
					alert( clientUser_value + " already in list");
				}else{
				$("<p class='bg-success'>"+clientUser_value+'<span rel="'+clientUser_value+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component job" name="clientUser[]" value="'+clientUser_key+'"/>'
					+"</p>").appendTo("#selected_clientUser");
				}
				$(this).prop("checked",false);
				$(this).parent("p").css("background-color","#FFF");
			   });
		 });
		 
		 $("#selected_clientUser").on("click",".glyphicon-trash",function(){
			var ids = $(this).attr("rel");
			
			clientUser_list = jQuery.grep(clientUser_list, function( n ) {
			  return ( n !== ids );
			});
			$(this).parent("p").remove();
		});
		
	/* client list START*/
				 var client_list=[];
			$("#com_sel_btn_client").click(function(){
			    // get all the selected values 
				 client_list=[];
			    $("input.ins:hidden").each(function(){
					client_list.push($(this).val());				       	
				});
				
				
			  $("div#sel_client input:checked").each(function(index,ele){
					var client_key=$(this).val();
					var client_value=$(this).attr('rel');
						//alert(client_value);
					if(client_list.indexOf(client_key)!=-1){
						alert( client_value + " already in list");	
					}else{
					$("<p class='bg-success'>"+client_value+'<span rel="'+client_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
						+'<input type="hidden" class="sel-component ins" name="client_ids[]" value="'+client_key+'"/>'
						+"</p>").appendTo("#selected_client");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
			   });
		 });
		
		$("#selected_client").on("click",".glyphicon-trash",function(){
			var ids = $(this).attr("rel");
			
			client_list = jQuery.grep(client_list, function( n ) {
			  return ( n !== ids );
			});
			$(this).parent("p").remove();
		});
		
/* assign client changes 9-1-1991 end 
*
*
*
*/
		/* Search Inspector */
		$("#search_tool_inspector").keyup(function(e){
			var query=$(this).val();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get("<?php echo base_url('Manage_kare/ajax_search_get_inspector') ?>",
					{ 'search' : value },
					function(data){
					$("#sel_inspector").html(data);	 
				});
		})
			
			
		 var inspector_list=[];
		 $("#com_sel_btn_inspector").click(function(){
			    // get all the selected values 
				 inspector_list=[];
			    $("input.ins:hidden").each(function(){
					inspector_list.push($(this).val());				       	
				});
				
				
			  $("div#sel_inspector input:checked").each(function(index,ele){
					var inspector_key=$(this).val();
					var inspector_value=$(this).attr('rel');
						//alert(inspector_value);
					if(inspector_list.indexOf(inspector_key)!=-1){
						alert( inspector_value + " already in list");	
					}else{
					$("<p class='bg-success'>"+inspector_value+'<span rel="'+inspector_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
						+'<input type="hidden" class="sel-component ins" name="inspector_ids[]" value="'+inspector_key+'"/>'
						+"</p>").appendTo("#selected_inspector");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
			   });
		 });
		
		$("#selected_inspector").on("click",".glyphicon-trash",function(){
			var ids = $(this).attr("rel");
			
			inspector_list = jQuery.grep(inspector_list, function( n ) {
			  return ( n !== ids );
			});
			$(this).parent("p").remove();
		});
		
		
		
		/* Validation for master data form submission */
		
		$('.master_data_form').submit(function(e) { 
		
			var error = false;
			$('#error_display').html('');
			var jobCard = $('#product_jobcard').val();
			var sms = $('#mdata_sms').val();
			
			if(jobCard == '' && sms == ''){
				$('#error_display').html('<div  class=" alert alert-danger">'+
										'<p style="text-align:center; text-transform:capitalize;">'+
										'Job Card and SMS numbers cannot be blank.'+
										'</p>'+
									'</div>');
				$('#product_jobcard').focus();
				error = true;
			}else if(jobCard == '' && sms != ''){
				$('#error_display').html('<div  class=" alert alert-danger">'+
										'<p style="text-align:center; text-transform:capitalize;">'+
										'Job Card number cannot be blank.'+
										'</p>'+
									'</div>');
				$('#product_jobcard').focus();
				error = true;
			}else if(jobCard != '' && sms == ''){
				$('#error_display').html('<div  class=" alert alert-danger">'+
										'<p style="text-align:center; text-transform:capitalize;">'+
										'SMS number cannot be blank.'+
										'</p>'+
									'</div>');
				$('#mdata_sms').focus();
				error = true;
			}
			if(error){
				e.preventDefault();
			}
		});

		$(document).on('blur','#mdata_sms',function(){
			var jobCard = $('#product_jobcard').val();
			var sms = $('#mdata_sms').val();
			if(jobCard != '' && sms != ''){
				jQuery.ajax({
							url 	: "<?php echo base_url('manage_kare/valid_jobSMS_from_ajax') ?>",
							type	: "POST",
							data	:  {jobCard : jobCard, sms : sms},
							success	: function(res)
							{
								if(res == "NO"){
									$('#error_display').html('<div  class=" alert alert-danger">'+
										'<p style="text-align:center; text-transform:capitalize;">'+
										'Same SMS number with Same Job Card available. Please choose a different SMS number.'+
										'</p>'+
									'</div>');
									$('#mdata_sms').focus();
								}else{
									$('#error_display').html('');
								}
							}
						}); // end of ajax
			}else{
				$('#error_display').html('<div  class=" alert alert-danger">'+
										'<p style="text-align:center; text-transform:capitalize;">'+
										'Job Card and SMS numbers cannot be blank.'+
										'</p>'+
									'</div>');
				$('#mdata_jobCard').focus();
				$('#mdata_sms').focus();
			}			
		});
		
		
		/* 
			* SMS COMPONENT
		*/
		
		$(document).on('change','#jc_number',function () {
                    var selJobcard = $(this).val(); 
					var target = $(this).attr('rel');
					if(target =='siteID'){
						var urls = base_url+"SiteId_kare/ajax_get_sms";
					}else if(target =='inspector_jc'){
						var urls = base_url+"SiteId_kare/ajax_get_sms_inspector";
					}else{
						var urls = base_url+"sms_controller/ajax_get_sms";
					}
					if(selJobcard != '')
					{
						$.ajax({
							url: urls,
							type: "POST",
							data: "jobcard="+selJobcard,
							success: function(data) {
								if(target =='siteID'){
									var res = data.split("#");
										$('.sms_number').html(res[0]);
										$('#master_id').val(res[1]);
								}else if(target =='inspector_jc'){
									$('.sms_number').html(data);
								}else{
									$('.sms_number').html(data);
								}
								
								$('#series_name').html('<option value=""> - Select Asset Series - </option>');
								$('#item_code').html('<option value=""> - Select Asset - </option>');
							}
						});
					}else{
						$('.sms_number').html('<option value=""> - Select SMS Number - </option>');
						$('#series_name').html('<option value=""> - Select Asset Series - </option>');
						$('#item_code').html('<option value=""> - Select Asset - </option>');
						$('#sel_jobCardNo').html('');
						
					}
		});
		
		$(document).on('change','#sms_number',function () {
			var jobCards = $('#jc_number').val();
			var sms_number = $(this).val();
			var target = $(this).attr('rel');
			
			if(sms_number != '' && (target == 'inspector' || target == 'sms_component_series')){
				$.ajax({
					url: base_url+"sms_controller/ajax_get_series_from_sms",
					type: "POST",
					data: "sms_number="+sms_number+'&jobCards='+jobCards+'&target='+target,
					success: function(data) {
						if(target =='sms_component_series'){
							$('#series_name').html(data);
							$('#item_code').html('<option value=""> - Select Asset - </option>');
						}else if(target == 'inspector'){
							$('#sel_jobCardNo').html(data);
						}
					}
				});
			}else{
				if(target =='inspector'){
					$('#sel_jobCardNo').html('');
				}else if(target == 'sms_component_series'){
					$('#series_name').html('<option value=""> - Select Asset Series - </option>');
					$('#item_code').html('<option value=""> - Select Asset - </option>');
				}
			}
		});
		
		$(document).on('change','#series_name',function () {	
					var series = $(this).val();
					var target = $(this).attr('rel');
					if(series != '' && (target == 'series_item'))
					{
						$.ajax({
							url: base_url+"sms_controller/ajax_get_asset_from_assetSeries",
							type: "POST",
							data: '&series='+series+'&target='+target,
							success: function(data) {
								$('#item_code').html(data);
							}
						});
					}else{
						$('#item_code').html('<option value=""> - Select Asset - </option>');
					}
		});
		
		/* SMS COMPONENT BY SEARCH */
		/*
		$(document).on('click','#search_sms_component',function (e) {
			e.preventDefault();
			var jobCards 	= $('#jc_number').val();
			var sms_number 	= $('#sms_number').val();
			var series 		= $('#series_name').val();
			var item_code 	= $('#item_code').val();
			var status 		= $('#status').val();
			
			if(jobCards=='' && sms_number=='' && series=='' && item_code=='' && status ==''){
				alert('Please select atleast Job Card to search the list.');
				return false;
			}
			
			if(jobCards != '')
			{
				$.ajax({
					url: base_url+"sms_controller/ajax_search_smsComponent",
					type: "POST",
					data: {
							'jobCards'		: jobCards,
							'sms_number' 	: sms_number,
							'series'		: series,
							'item_code'		: item_code,
							'status'		: status
					},
					success: function(data) {
							$('#sms_result').html(data);
							$("#sms_table").dataTable();
							
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						 alert(errorThrown);
					 }
				});
			}else{
				alert("NO Value");
				return false;
			}
		});
		*/
		
		$(document).on('click','.removeData',function(e){
			e.preventDefault();
			if(confirm("Do you want to delete them?")) {
				this.click;
				   var id = $(this).data("id");
				   var rel = $(this).data("rel");
					$("[data-rel='" + rel + "']").remove();
					$("[data-id='" + id + "']").remove();
			}
			else
			{
			   return false;
			}
		});
	
		
		/* *********** Search Sub Assets *********************** */
		$("#search_tool_sub_assets").keyup(function(e){
			var query=$(this).val();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get("<?php echo base_url('subassets_kare/ajax_get_subAssets') ?>",
					{ 'search' : value },
					function(data){
					$(".search-subAssets").html(data);	 
				});
		});
		

		//$(".search-subAssets").on("click",":checkbox",function(){
		$(document).on("click", ".search-subAssets",":checkbox",function(){
		//alert("hello sdfs");
			if(this.checked){
				$(this).parent("p").css("background-color","#9FF");
			} else{
				$(this).parent("p").css("background-color","white"); 
			}
		});
		
		/* Multiple Sub Assets Selection */
		var subAssets_list=[];
		
		//$("#com_sel_btn_subAssets").click(function(){
		$(document).on("click", "#com_sel_btn_subAssets", function(){
			    // get all the selected values
			    $("input.subAssets:hidden").each(function(){
					subAssets_list.push($(this).val());				       	
				});

				$("div#sel_subAssets input:checked").each(function(index,ele){
					subAssets_key=$(this).val();
					var subAssets_Value=$(this).attr('rel');
					if(subAssets_list.indexOf(subAssets_key)!=-1){
						alert( subAssets_key + " already in list");	
					}else{
					$("<p class='bg-success'>"+subAssets_key+'<span rel="'+subAssets_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
						+'<input type="hidden" class="sel-component subAssets" name="sub_assets[]" value="'+subAssets_key+'"/>'
						+"</p>").appendTo("#selected_subAssets");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
					
			   });
		});
		
	/* productedit select multiple subassets Start*/ 
		
		$(document).on("click", "#com_sel_btn_subAssets_productedit", function(){
			    // get all the selected values
			    $("input.subAssets:hidden").each(function(){
					subAssets_list.push($(this).val());				       	
				});

				$("div#sel_subAssets_productedit input:checked").each(function(index,ele){
					subAssets_key=$(this).val();
					var subAssets_Value=$(this).attr('rel');
					if(subAssets_list.indexOf(subAssets_key)!=-1){
						alert( subAssets_Value + " already in list");	
					}else{
					$("<p class='bg-success'>"+subAssets_Value+'<span rel="'+subAssets_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
						+'<input type="hidden" class="sel-component subAssets" name="sub_assets[]" value="'+subAssets_key+'"/>'
						+"</p>").appendTo("#selected_subAssets");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
					
			   });   
		});
		
		
		
	/* productedit select multiple subassets END*/ 
		
		$(document).on("click","#selected_subAssets .glyphicon-trash",function(){
			var ids = $(this).attr("rel");
			subAssets_list = jQuery.grep(subAssets_list, function( n ) {
			   return ( n !== ids );
			});
			$(this).parent("p").remove();
			$(this).parent("p").css("background-color","#9FF");
		});
		/* End of Sub Assets */

		
		/* Assets/COMPONENT for product category 13-12-2016  Start */
		var assets_list=[];
		$("#com_sel_btn_assets").click(function(){
			    // get all the selected values 
				
			    $("input.asset:hidden").each(function(){
					assets_list.push($(this).val());				       	
				});
				
				
				$("div#sel_assets input:checked").each(function(index,ele){
					var assets_key=$(this).val();
					if(assets_list.indexOf(assets_key)!=-1){
						alert( assets_key + " already in list");	
					}else{
					$("<p class='bg-success'>"+assets_key+'<span rel="'+assets_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
						+'<input type="hidden" class="sel-component asset" name="assets[]" value="'+assets_key+'"/>'
						+"</p>").appendTo("#selected_assets");
					}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#9FF");
			   });
		});

		$(document).on("click","#selected_assets .glyphicon-trash",function(){
			var ids = $(this).attr("rel");
			assets_list = jQuery.grep(assets_list, function( n ) {
			  return ( n !== ids );
			});
			
			$(this).parent("p").remove();
			
		});

		$(document).on("click",".search-Assets :checkbox",function(){
			if(this.checked){
				$(this).parent("p").css("background-color","#9FF");
			} else{
				$(this).parent("p").css("background-color","white"); 
			}
		});
		/* Assets/COMPONENT for product category 13-12-2016 End */

		/* Assets Series/product for product category 13-12-2016  Start */

		var assetSeries_list=[];
		$("#com_sel_btn_assets_series").click(function(){
			// get all the selected values
			assetSeries_list=[];
			$("input.assetSeries:hidden").each(function(){
				assetSeries_list.push($(this).val());			       	
			});
			
			$("div#sel_assets_series input:checked").each(function(index,ele){
				var assets_series_key=$(this).val();
				if(assetSeries_list.indexOf(assets_series_key)!=-1){
					alert( assets_series_key + " already in list");
				}else{
					$("<p class='bg-success'>"+assets_series_key+'<span rel="'+assets_series_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component assetSeries" name="assets_series[]" value="'+assets_series_key+'"/>'
					+"</p>").appendTo("#selected_assets_series");
				}
				$(this).prop("checked",false);
				$(this).parent("p").css("background-color","#FFF");
		   });
		});
		$(document).on("click","#selected_assets_series .glyphicon-trash",function(){
			var ids = $(this).attr("rel");
				assetSeries_list = jQuery.grep(assetSeries_list, function( n ) {
				return ( n !== ids );
			});
			$(this).parent("p").remove();
		});
		/* Assets Series/product for product category 13-12-2016  End */
		
		
		
		/* Expected result Start */
		var expectedResult_list=[];
		//$("#com_sel_btn_expectedResult").click(function(){
		$(document).on("click", "#com_sel_btn_expectedResult", function(){
			// get all the selected values
			$("input.ins:hidden").each(function(){
				expectedResult_list.push($(this).val());			       	
			});
			
			$("div#sel_expectedResult input:checked").each(function(index,ele){
				var expectedResult_key=$(this).val();
				var expectedResult_Value=$(this).attr('rel');
				if(expectedResult_list.indexOf(expectedResult_key)!=-1){
					alert( expectedResult_Value + " is already in selected list");	
				}else{
				$("<p class='bg-success'>"+expectedResult_Value+'<span rel="'+expectedResult_key+'" class="pull-right text-danger glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component ins" name="expectedResult[]" value="'+expectedResult_key+'"/>'
					+"</p>").appendTo("#selected_expectedResult");
				}
				$(this).prop("checked",false);
				$(this).parent("p").css("background-color","#FFF");
		   });
		});
		
		$(document).on("click","#selected_expectedResult .glyphicon-trash",function(){
			var ids = $(this).attr("rel");
			
			expectedResult_list = jQuery.grep(expectedResult_list, function( n ) {
			  return ( n !== ids );
			});
			$(this).parent("p").remove();
		});
		
		
		
		/* Expected result Start */
		
		var observations_list=[];
		//$("#com_sel_btn_observation").click(function(){
		$(document).on("click", "#com_sel_btn_observation", function(){
			// get all the selected values
			$("input.ins:hidden").each(function(){
				observations_list.push($(this).val());			       	
			});
			
			
			$("div#sel_observation input:checked").each(function(index,ele){
				var observation_key=$(this).val();
				var observation_Value=$(this).attr('rel');
				if(observations_list.indexOf(observation_key)!=-1){
					alert(observation_Value + " is already in selected list");
				}else{
				$("<p class='bg-success'>"+observation_Value+'<span rel="'+observation_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component ins" name="observation[]" value="'+observation_key+'"/>'
					+"</p>").appendTo("#selected_observation");
				}
				$(this).prop("checked",false);
				$(this).parent("p").css("background-color","#FFF");
		   });
		});
		
		//$("#selected_observation").on("click",".glyphicon-trash",function(){
		$(document).on("click","#selected_observation .glyphicon-trash",function(){
			var ids = $(this).attr("rel");
			
			observations_list = jQuery.grep(observations_list, function( n ) {
			  return ( n !== ids );
			});
			$(this).parent("p").remove();
		});
		
		
	});		/* end of document . ready function */
		
		
		
	function isNumber(evt) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if ((iKeyCode < 48 || iKeyCode > 57)){
			alert("Only numeric values are allowed");
			return false;
		}else{
			return true;
		}
	}
	
	function updateDue() {
		var total = parseInt(document.getElementById("component_no").value);
		var val2 = parseInt(document.getElementById("component_lines").value);
		if(!isNaN(total) && !isNaN(val2)){
			var ansD = document.getElementById("component_result");
			ansD.value = total / val2;
		}else{
			var ansD = document.getElementById("component_result");
			ansD.value = '';
		}
		
		
	}
	
	function myFunction() {
		if (confirm("Do you want to Delete it!") == true) {
			return true;
		} else {
			return false;
		}
	}
	
	$(document).ready(function(){
		
		$(document).on('focus','.datepicker',function(){
		
			$(this).datepicker({
				changeMonth:true,
				changeYear:true,
				dateFormat: 'dd/mm/yy',
			});
			
		});
		
		
		/* Search Jobcard in Assign Inspector */
		$("#search_tool_jobcard_inspector").keyup(function(e){
			var query=$(this).val();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get("<?php echo base_url('SiteId_kare/ajax_search_get_jobCard') ?>",
					{'search' : value },
					function(data){
					$("#jc_number").html(data);
					$(".sms_number").html('<option value=""> - Select SMS Number - </option>');
					$('#sel_jobCardNo').html('');
				});
		})
	});
	
	
			
			
</script>
</body>
</html>