/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){	
			/* *********** search_tool_expected_result *********************** */
			$("#search_tool_expected_result").keyup(function(e){
				var query=$(this).val();
					if(query ==''){
						var value = 'blank';
					}else{
						var value = query;
					}
					$.get(base_url+"/manage_kare/ajax_get_expected_result",
						{ 'search' : value },
						function(data){
						$(".search-expectedResult").html(data);	 
					});
			});
		});	
		$(document).on("click", ".search-expectedResult",":checkbox",function(){
			if(this.checked){
				$(this).parent("p").css("background-color","#9FF");
			} else{
				$(this).parent("p").css("background-color","white"); 
			}
		});
			
			//$("#com_sel_btn_subAssets").click(function(){
		$(document).on("click", "#com_sel_btn_expectedResult", function(){
				// get all the selected values
				$("input.subresult:hidden").each(function(){
					sub_result_list.push($(this).val());				       	
				});

				$("div#sel_expectedResult input:checked").each(function(index,ele){
					
					 subResult_key = $(this).val();
					var subResult_Value = $(this).attr('rel');
					
					/*if(sub_result_list.indexOf(subResult_key)!=-1){
						alert( subResult_key + " already in list");	
					}else{
						alert(subResult_Value);*/
						$("<p class='bg-success'>"+subResult_Value+'<span rel="'+subResult_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
						+'<input type="hidden" class="sel-component sub_result" name="expectedResult[]" value="'+subResult_key+'"/>'
						+"</p>").appendTo("#selected_expectedResult");
					//}
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
					
			   });
		});


$(document).ready(function(){	
			$("#search_tool_observations").keyup(function(e){
				var query=$(this).val();
					if(query ==''){
						var value = 'blank';
					}else{
						var value = query;
					}
					$.get( base_url+"/manage_kare/ajax_get_observations",
						{ 'search' : value },
						function(data){
						$(".search-observation").html(data);	 
					});
			});
			
			$(document).on("click", ".search-observation",":checkbox",function(){
				if(this.checked){
					$(this).parent("p").css("background-color","#9FF");
				} else{
					$(this).parent("p").css("background-color","white"); 
				}
			});
			
			$(document).on("click", "#com_sel_btn_observation", function(){
				$("input.subobservation:hidden").each(function(){
					sub_observation_list.push($(this).val());				       	
				});

				$("div#sel_observation input:checked").each(function(index,ele){
					 subObservation_key = $(this).val();
					var subObservation_Value = $(this).attr('rel');
					
					$("<p class='bg-success'>"+subObservation_Value+'<span rel="'+subObservation_key+'" class="pull-right text-danger  glyphicon glyphicon-trash"></span>'
					+'<input type="hidden" class="sel-component subobservation" name="observation[]" value="'+subObservation_key+'"/>'
					+"</p>").appendTo("#selected_observation");
					
					$(this).prop("checked",false);
					$(this).parent("p").css("background-color","#FFF");
					
			   });
			});
		});