$(document).ready(function() {
	
	$(document).on('change','.select_type',function(){
		var type_name = $(this).val();
		if(type_name !=""){
			$.ajax({
				type: "POST",
				url: base_url + "specification/get_selected_data",
				data: {type_name: type_name},
				success: function(res){
					$('#display_value').html(res);
					$("#type_value_details").DataTable({
						"order":[[ 0,"asc" ]]
					});
				},
				error: function(){
					alert('Error while request ajax...');
				}
			});	// end of ajax
			
		} // end of if statememnt
		else{
			$('#display_value').html('');
		}
	});

// Search for Asset, Sub-Asset And Asset Series for Knowledge Database.
	
		/* *********** Search Assets *********************** */
		$(document).on("keyup","#search_tool_for_multiple_assets", function(e){
			var query=$(this).val();
			// alert(base_url);
			// die();
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get(base_url+"ProductCategory_controller/ajax_get_multiple_assets",
					{ 'search' : value },
					function(data){					
					$(".search_multiple_assets").html(data);	 
				});
		});
		
		
		/* *********** Search Assets END*********************** */
		
		/* *********** Search Assets *********************** */
		$(document).on("keyup","#search_tool_for_multiple_assetsSeries", function(e){
			var query=$(this).val();
			
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get(base_url+"ProductCategory_controller/ajax_get_multiple_assetsSeries",
					{ 'search' : value },
					function(data){					
					$(".search-AssetsSeries").html(data);	 
				});
		});
		
		
		/* *********** Search Assets END*********************** */
// Search for Asset, Sub-Asset And Asset Series for Knowledge Database. END
		
		
// Search for Asset, Sub-Asset And Asset Series for product edit START
		/* *********** Search Assets *********************** */
		$(document).on("keyup","#search__multiple_assets", function(e){
			var query=$(this).val();
			
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get(base_url+"ProductCategory_controller/ajax_multiple_assets",
					{ 'search' : value },
					function(data){					
					$(".multiple_assets").html(data);	 
				});
		});
		
		/* *********** Search Assets END*********************** */
		
		
		/* *********** Search Sub Assets *********************** */
		$(document).on("keyup","#search_tool_subAssets", function(e){
			var query=$(this).val();
			
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get(base_url+"ProductCategory_controller/ajax_multiple_subassets",
					{ 'search' : value },
					function(data){					
					$(".search_subassets").html(data);	 
				});
		});
		
		/* *********** Search Sub Assets END*********************** */
		
		/* *********** Search Assets Series*********************** */
		$(document).on("keyup","#search_multiple_assetsSeries", function(e){
			var query=$(this).val();
			
				if(query ==''){
					var value = 'blank';
				}else{
					var value = query;
				}
				$.get(base_url+"ProductCategory_controller/ajax_multiple_assetsSeries",
					{ 'search' : value },
					function(data){					
					$(".search_assetsSeries").html(data);	 
				});
		});
		
		/* *********** Search Assets Series END*********************** */
	

}); // end of document.ready