<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/head'); ?> 

	<div class="row">
		<div class="col-md-12">
			<div class="text-center">
				<?php if(!empty($this->session->flashdata('msg'))){
				   echo $this->session->flashdata('msg');
				} ?>
			</div>
			<legend class="home-heading">Asset Management System</legend>

			<div class="col-md-12" >  
                            <form id="criteria" class="form-horizontal" >
<!--				<legend class="home-heading">ASSET MANAGEMENT SYSTEM</legend>                               -->
				<div class="form-group">
					<div id="formone">
						<div class="col-md-4">
							<select class="form-control selectpicker" name="asm_store" id="asm_store" data-live-search="true" data-live-search-style="startsWith" >
								<option value="">Select Client</option>
								<?php foreach($asm_stores as $ckey=>$cval){ ?>
								<option value="<?php echo $ckey; ?>"><?php echo $cval; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<select class="form-control selectpicker"  name="asm_user" id="asm_user" data-live-search="true" data-live-search-style="startsWith" >
								<option value="">Select User</option>
								<?php foreach($district as $key=>$districtval){ ?>
								<option value="<?php echo $districtval; ?>"><?php echo $districtval; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
                                                    <select  class="form-control selectpicker" name="asm_project" id="asm_project" data-live-search="true" data-live-search-style="startsWith">
								<option  value="">Select Project</option>
								<?php foreach($circle as $key=>$circleval){ ?>
								<option value="<?php echo $circleval; ?>"><?php echo $circleval; ?></option>
								<?php } ?>
							</select>
						</div>                                         
					</div>
				</div>
				<div class="form-group" id="formone-but">
					<div class="col-md-12 text-center"> 
						<button name="search" id="search" value="Submit" type="submit" style="background-color: <?php echo $_SESSION['color_code'];?> !important; border-color: <?php echo $_SESSION['color_code'];?> !important;"><?php if( $lang['serach']['description'] !='' ){ echo $lang['serach']['description']; }else{ echo 'Search'; }  ?></button>
						<button class="exportList" type="submit"  value="Export List" id="export"> <span class="glyphicon glyphicon-download-alt"></span> &nbsp;<?php if( $lang['export_list']['description'] !='' ){ echo $lang['export_list']['description']; }else{ echo 'Export List'; }  ?></button>
                                        </div>
				</div>
			</form>

			</div>
		</div>
	</div>

<div class="row" >
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading home-heading">
                <span>ASSETS LIST</span>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="tbl_asm_report">
                    <thead>
                    <th>Product UIN </th>
                    <th>Assets Code</th>
                    <th>Image</th>
                    <th>User</th>
                    <th>Project</th>
                    <th>checkin</th>
                    <th>checkout</th>
                    <th>Asm Log hours</th>
                    <th>Status</th>
                    </thead>   
                    <tbody>
                        <?php
                             foreach($asm_products as $prodcut){
                        ?>
                        <tr>
                            <td><?php echo $prodcut['ps_mdata_id']; ?></td>   
                            <td><?php echo $prodcut['ps_product_id']; ?></td>   
                            <td><img src="<?php echo $prodcut['ps_product_image']; ?>" width="60" height="60" /></td>   
                            <td><?php echo $prodcut['ps_user_name']; ?></td>   
                            <td><?php echo $prodcut['ps_project_name']; ?></td>   
                            <td><?php echo $prodcut['ps_checkedin']; ?></td>   
                            <td><?php echo $prodcut['ps_checkedout']; ?></td>   
                            <td><?php echo $prodcut['ps_product_loghours']; ?></td>  
                           <td><?php  echo  $prodcut['ps_isused']; ?></td> 
                        </tr> 
                        <?php } ?>  
                    </tbody> 
                </table>
            </div>
        </div>
    </div>
</div>

<!--<div class="row" id="bookmark">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading home-heading">
                <span>ASSETS LIST</span>
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="asset_table12">
                    <thead>
                    <th>Action</th>
                    <th>Assets Code</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>UOM</th>
                    <th>Inspection Type</th>
                    <th>Expected Result</th>
                    <th>Observations</th>
                    <th>Repair</th>
                    <th>Infonet Status</th>
                    <th>Status</th>
                    <th> Add Featured Image</th>
                    </thead>   
                    <tbody>
                    </tbody> 
                </table>
            </div>
        </div>
    </div>
</div>-->

<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>


<script type="text/javascript">
    
function queryData(url,postData){
    $.ajax({
        url:url,
        data:JSON.stringify(postData),
        type:'POST',
        success:function(data){
           alert('ok');
           console.log(data); 
        }

    });
    
}    
    
$(document).ready(function(){
    
   $('#asm_store').change(function(){
       var where={},filter={};
       var postData={};
           postData.table='asm_products'; 
       var store=$(this).val();
       var url = base_url+"asm_api/query_data";
       if(store){
           where.ps_store_id=store;
           //where['ps_user_id!=']=0;
           //where.ps_product_id=store; for testing
           postData['where']=where;
           filter['distinct']='ps_user_id';
           postData['filter']=filter;
           
            $.ajax({
                 url:url,
                 data:postData,
                 type:'POST',
                 success:function(data){
                     if(data.status_code==200){
                        var users=data.data;
                        var liItems='<option value="">Select User</option>';
                        $.each(users,function(index,value){
                            liItems +='<option value="'+index+'">'+value+'</option>';
                        });
                        $("#asm_user").html(liItems)
                       console.log(liItems); 
                     }
                    console.log(data); 
                 },
                 dataType:'json'
          }); 
       }
      
   }); 
   
   
    $('#asm_user').change(function(){
       var where={},filter={};
       var postData={};
           postData.table='asm_products'; 
       var store=$('#asm_store').val();    
       var user=$(this).val();
       var url = base_url+"asm_api/query_data";
       if(user & store){
           where.ps_store_id=store;
           where['ps_user_id']=user;
           //where.ps_product_id=store; for testing
           postData['where']=where;
           filter['distinct']='ps_project_id';
           postData['filter']=filter;
           
            $.ajax({
                 url:url,
                 data:postData,
                 type:'POST',
                 success:function(data){
                     if(data.status_code==200){
                        var users=data.data;
                        var liItems='<option value="">Select User</option>';
                        $.each(users,function(index,value){
                            liItems +='<option value="'+index+'">'+value+'</option>';
                        });
                        $("#asm_project").html(liItems)
                       console.log(liItems); 
                     }
                    console.log(data); 
                 },
                 dataType:'json'
          }); 
       }    
   }); 
   
   
      $('#search').click(function(e){
        e.preventDefault();
        debugger;
       var where={},filter={};
       var postData={};
       var store=user=project='';
           postData.table='asm_products'; 
           store = $('#asm_store').val();    
            user = $('#asm_user').val();
            project=$('#asm_project').val();
            
       var url = base_url+"asm_api/query_data";
       
       if(store ||  (user&&store) || (store && user&& product) ){
           where.ps_store_id=store;
           where['ps_user_id']=user;
           where['ps_project_id']=project;           
           postData['where']=where;
           postData['action']='asm_products';
            $.ajax({
                 url:url,
                 data:postData,
                 type:'POST',
                 success:function(data){
                     if(data.status_code==200){
                        var products=data.data;
                        var row='';
                        $.each(products,function(index,item){
                            row +="<tr>";
                            row +='<td>'+item['ps_mdata_id']+'</td>';
                            row +='<td>'+item['ps_product_id']+'</td>';
                            row +='<td><img src="'+ item['ps_product_image']+ '"width="60" height="60" /></td>';
                            row +='<td>'+item['ps_user_name']+'</td>';
                            row +='<td>'+item['ps_project_name']+'</td>';
                            row +='<td>'+item['ps_checkedin']+'</td>';
                            row +='<td>'+item['ps_checkedout']+'</td>';
                            row +='<td>'+item['ps_product_loghours']+'</td>';
                            row +='<td>'+item['ps_isused']+'</td>';
                            row +="</tr>";
                        });
                       //console.log(row); 
                     }else{
                       row +="<tr><td colspan='9'> No data Found</td></tr>";
                     }
                    $("#tbl_asm_report tbody").html(row); 
                 },
                 dataType:'json'
          }); 
       }    
   }); 
   
   
   
   
   
     
});




</script>