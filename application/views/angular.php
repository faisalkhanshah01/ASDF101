<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
	<?php $this->load->view('includes/head'); ?> 
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>

<div ng-app="myApp" ng-controller="myCtrl" ng-init="component={}">

<div class="row">
  			
		<div class="col-md-offset-1 col-md-6">
			<form class="form-horizontal" method="post" >
				<legend>ADD COMPONENTS</legend>
				<div class="form-group">
					<label for="email" class="col-md-4 control-label">componentCode</label>
					<div class="col-md-8">
						<input type="text" class="form-control" id="product_code" name="product_code" 
                        ng-model="component.component_code"
                       />
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-md-4 control-label">Description</label>
					<div class="col-md-8">
                    					<textarea id="product_description" name="product_description"  class="form-control tooltip_trigger"  ng-model="component.component_description"></textarea>
                    
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-md-4 control-label">Upload Image</label>
					<div class="col-md-6">
						<input type="file" class="form-control" id="product_image" name="product_image" />
                    </div>    
                    <div class="col-md-2">    
                        <span><img src="{{ base_url+component.component_image }}" width="80" height="80"/></span>
					</div>
                   
				</div>

				<div class="form-group">
					<label for="email" class="col-md-4 control-label">UOM</label>
					<div class="col-md-8">
					<input type="text" id="product_uom" name="product_uom" class="form-control tooltip_trigger"   ng-model="component.component_uom" />
					</div>
				</div>
         <div class="form-group">
					<label for="email" class="col-md-4 control-label">Type of Inspection</label>
					<div class="col-md-8">
					<select  id="product_inspectiontype" name="product_inspectiontype"  class="form-control tooltip_trigger">
                     <option value="">Inspection Type</option>
                     <option>Visual</option>
                     <option>Manual</option>
                    </select>
					</div>
		</div>
                
                
 				<div class="form-group">
					<label for="email" class="col-md-4 control-label">Expected Result</label>
					<div class="col-md-8">
                    <select  id="product_expectedresult" name="product_expectedresult" class="form-control tooltip_trigger">
                     <option value="">Expected Result</option>
                     <option>To be Fixed</option>
                     <option>To be Repair</option>
                     <option>To be Replaced</option>
                    </select>
					</div>
				</div> 
                 
				<div class="form-group">
					<label for="email" class="col-md-4 control-label">Excerpt</label>
					<div class="col-md-8">
					<textarea id="product_excerpt" name="product_excerpt"  class="form-control tooltip_trigger" ></textarea>
					</div>
				</div>
                
				<div class="form-group">
					<label for="email" class="col-md-4 control-label">Repair</label>
					<div class="col-md-8">
                    <span class="col-md-3">
					<input  type="radio" id="product_repair" name="product_repair" value="Yes" <?php echo set_checkbox('product_repair','Yes',true);?> />Yes
                    </span>
                    <input type="radio" id="product_repair" name="product_repair" value="No"  <?php echo set_checkbox('product_repair','No');?>/>No
                    
                      <?php echo form_error('product_repair'); ?>  
					</div>
				</div>              

				<div class="form-group">
					<div class="col-md-offset-4 col-md-8">
						<input type="submit" name="save_product" class="btn btn-primary" id="submit" value="SAVE" />
					</div>
				</div>
                  
			<!--</form>-->
			</form>
		</div>
        
	</div>





<table class="table table-bordered">
    <thead>
    <tr>
    <td>S.No</td><td>Component Code</td><td>Description</td><td>Image</td><td>UOM</td>
    <td>Inspection Type</td><td>Expected Result</td><td>Excerpt</td><td>Repair</td> 
    <td>Action</td><td> Aadd Featured Image</td>
    </tr>
    </thead>   
    <tbody>
<tr ng-repeat='x in components'>
<td> {{ $index+1 }} </td>
<td> {{ x.component_code }} </td>
<td> {{ x.component_description }}</td>
<td><img src="{{ base_url+x.component_image }}" width="80" height="80"/></td>
<td> {{ x.component_uom }}</td>
<td> {{ x.component_inspectiontype }}</td>
<td> {{ x.component_expectedresult }}</td>
<td> {{ x.component_excerpt }}</td>
<td> {{ x.component_repair }}</td>
<td><button ng-click="editComponent(x.component_id)">Edit</button></td>
</tr>

</tbody>

</table>

</div>

<script>
var app = angular.module("myApp", []);
app.controller("myCtrl", function($scope,$http) {
	$http.get("http://192.168.1.3/Mysites/kare/api_controller/components/")
	.then(function(response){
		//alert( typeof response.data);
		$scope.components=response.data;
		$scope.base_url='http://192.168.1.3/Mysites/kare/uploads/images/components/'
		});
		
	$scope.editComponent=function(component_id){
		 alert(component_id);
	    $http.get("http://192.168.1.3/Mysites/kare/api_controller/components/"+component_id)
		.then(function(response){
			var x=response.data;
			$scope.component=x[0];
			console.log($scope.component);
		 });	
	}	
		
});
</script>

</body>
</html> 