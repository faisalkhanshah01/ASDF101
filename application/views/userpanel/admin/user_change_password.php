<?php $this->load->view('includes/header'); ?> 
	<!-- Navigation -->
 <script>
    $(document).ready(function(){
        $( ".submit" ).click(function() {
                var password = $("#criteria").find("input[name =new_password]").val();
                var confirmPassword = $("#criteria").find("input[name =confirm_new_password]").val();

                if (password != confirmPassword){
                    alert("Passwords do not match!");
                }else{
                    var new_password = password;
                }
                //var userID = $("#criteria").find("input[name =userID]").val();
                var userID = <?php print $_REQUEST['userID'];?>;
                var emailID = $("#criteria").find("input[name =emailID]").val();
                //var new_password = $("#criteria").find("input[name =new_password]").val();
		$.ajax({
			url: base_url + "Auth_admin/passwordChange",
			type: 'post',
                        dataType: "json",
                        data: {userID:userID,emailID:emailID,new_password:new_password,submit:1},
			success: function(output){
                            if(output.responseType == 'success'){
                                alert(output.message);
                                window.location = "<?php echo $base_url;?>Auth_admin/password_change";
                            }else{
                                alert(output.message);
                                location.href=location.href;
                            }
			}
		});	// end of ajax
		return false;
	});
     
    });    
</script>
	<?php $this->load->view('includes/head'); ?>
	<div class="row">
		<div class="col-md-12">
			
			<div class="panel panel-default">
                                <div class="panel-heading home-heading clearfix">
                                        <span class="panel-title pull-left" style="padding-top: 7.5px;">New Password Create</span>
                                        <div class="btn-group pull-right">
                                            <a href="<?php echo $base_url;?>auth_admin/password_change" class="btn btn-default btn-sm">Back</a>
                                        </div>
                                </div>
				<div class="panel-body">
					<div class="col-md-12 ">
					  <form id="criteria" class="form-horizontal">
                             <!-- <div class="form-group">
								<label for="new_password" class="col-md-4 control-label">User ID:</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="userID" name="userID" value="<?php $res = (!empty($userDetail['uacc_id']) && ($userDetail['uacc_id'] == $userDetail['userID']))?$userDetail['uacc_id']:''; print $res;?>" readonly="readonly"/>
								</div>
							</div>-->
                                                <div class="form-group">
							<label for="new_password" class="col-md-4 control-label">Email ID:</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="emailID" name="emailID" value="<?php $res = !empty($userDetail['uacc_email'])?$userDetail['uacc_email']:''; print $res;?>" readonly="readonly"/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<small>
									Password length must be more than <?php echo $this->flexi_auth->min_password_length(); ?> characters in length.<br/>
									Only alpha-numeric, @, dashes, underscores, periods and comma characters are allowed.
								</small>
							</div>
						</div>
						<div class="form-group">
							<label for="new_password" class="col-md-4 control-label">New Password:</label>
							<div class="col-md-8">
								<input type="password" class="form-control" id="new_password" name="new_password" value="" required/>
							</div>
						</div>
						<div class="form-group">
							<label for="confirm_new_password" class="col-md-4 control-label">Confirm New Password:</label>
							<div class="col-md-8">
								<input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" value="" required/>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-4 col-md-8">
								<input type="submit" class="submit" name="change_password" id="submit" value="Submit" class="btn btn-primary"/>
							</div>
						</div>
                                          </form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/scripts'); ?>