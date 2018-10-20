<?php $this->load->view('includes/header'); ?>
<!-- Navigation -->
<?php $this->load->view('includes/head'); ?>

<!-- Pdm content starts here -->
<div class="row" class="msg-display">
    <div class="col-md-12">
        <?php if (!empty($this->session->flashdata('msg')) || isset($msg)) { ?>
            <p>
                <?php
                echo $this->session->flashdata('msg');
                if (isset($msg))
                    echo $msg;
                echo validation_errors();
                ?>
            </p>
        <?php } ?>
    </div>
</div>
<?php if ($group_id != 9) { ?>
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist" style="margin-bottom:23px;">
                <li role="presentation" class="active"><a data-target="#mdata_form" aria-controls="home" role="tab" data-toggle="tab"><?php
                        if ($lang["add_site_id_data"]['description'] != '') {
                            echo $lang["add_site_id_data"]['description'];
                        } else {
                            echo 'PERIODIC MAINTENANCE';
                        }
                        ?></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="mdata_form">
                            <?php echo form_open_multipart(current_url(), 'class="master_data_form"'); ?>
                    <div class="col-md-12">
                        <legend  class="home-heading"><?php
                            if ($lang["add_site_id_data"]['description'] != '') {
                                echo $lang["add_site_id_data"]['description'];
                            } else {
                                echo 'INSERT PERIODIC MAINTENANCE';
                            }
                            ?>
                        <a class="btn btn-info" id="btn_addstep" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a>
                        </legend>
                           <div class="form-group col-md-4">
                            <label for="assets" class="control-label"><?php
                            if ($lang["job_card_no"]['description'] != '') {
                                echo $lang["job_card_no"]['description'];
                            } else {
                                echo 'SELECT ASSET';
                            }
                            ?></label>

                            <select id="pdm_asset" name="pdm_asset"  class="form-control" required>
                            <option value="">Select Asset Id</option>
                            <?php
                            foreach($components as $key=>$component){?>
                                    <option value="<?php echo $component['component_id'];  ?>"><?php echo $component['component_code'];val;  ?></option>
                            <?php } ?>
                            </select>
                            <?php echo form_error('jc_number'); ?>

                        </div>
                    </div>

                    <div class="sections">

                        <div class="row section">
                         <div class="col-md-12">
                         <div class="panel" id="panel-1">
                             <div class="panel-head">
                                 <legend class="home-heading">Step No.1 <i id="close-1" onclick="collapsebox(this)" class="green-text pull-right glyphicon glyphicon-plus"></i></legend>
                             </div>
                            <div class="panel-body" style="display:none;">
                            <div class="col-md-6">
                                <div class="form-group col-md-12">
                                    <label for="group" class="control-label">PDM Step Name</label>
                                    <input type="text" class="form-control" name="pdm_step[]" id="pdm_step"  required/>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="group" class="control-label">Process</label>
                                    <textarea class="form-control" name="pdm_process[]" id="pdm_process" required></textarea>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="group" class="control-label">Observation</label>
                                    <select class="form-control" name="pdm_observations[]" id="pdm_observation"  required>
                                        <?php foreach($observations as $key=>$val){?>
                                                <option value="<?php echo $key;  ?>"><?php echo $val;  ?></option>
                                        <?php } ?>
                                    </select>

                                </div>

                                <div class="form-group col-md-12">
                                    <label for="group" class="control-label">Expected Results</label>
                                    <select class="form-control" name="pdm_expresults[]" id="pdm_expresults"   >
                                        <?php foreach($expresults as $key=>$val){?>
                                                <option value="<?php echo $key;  ?>"><?php echo $val;  ?></option>
                                        <?php } ?>
                                    </select>

                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group col-md-12">
                                    <label for="group" class="control-label">Upload Document</label>
                                    <input type="file" class="form-control" name="pdm_document[]" id="pdm_document"  />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="group" class="control-label">Upload Media</label>
                                    <input type="file" id="pdm_media" class="form-control" name="pdm_media[]" id="pdm_media" />
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="group" class="control-label">Upload Image</label>
                                    <input type="file" id="group" class="form-control" name="pdm_image[]" id="pdm_image"/>
                                </div>

                            </div>
                            </div>

                        </div>
                      </div>
                     </div>
                        <!-- /.section -->

                    </div>
                    <!-- /.sections -->




                    <div class="form-group" >
                        <div class="col-md-offset-4 col-md-8" style="margin-top:30px;">
                            <input type="hidden" name="master_id"  id="master_id" value="" />
                            <input type="submit" name="submit_siteID" class="btn btn-primary" id="submit_mdata" value="SAVE PERIODIC MAINTENANCE DATA" />
                        </div>
                    </div>
                    <!--</form>-->
                <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <!--/.tab-content -->
    </div>
    <!--/.row -->
   <?php } ?>

<?php $this->load->view('includes/scripts'); ?>
    <script type="text/javascript">
    var secCount=0;
    $(document).on('click','#btn_addstep',function(){
        // debugger;
        secCount++;
        var template= $('.section:first').clone();

          var section= template.clone().find(":input").each(function(index,ele){
                    var newid=$(this).attr('id')+secCount;
                    $(this).prev('label').attr('for',newid);
                    this.id=newid;
           }).end();
           var stepadd = section.html().replace('Step No.1','Step No.'+(secCount+1)).replace('panel-1','panel-'+(secCount+1)).replace('close-1','close-'+(secCount+1));
           section = $('<div class="row section">'+stepadd+'</div>');
         section.appendTo('.sections');

    });
    function collapsebox(a){
      let id = $(a).attr('id').split('-')[1];
      let panelbody = $("#panel-"+id).find('.panel-body');
      if(panelbody.css('display')=='none'){
      panelbody.css('display','block');
      $("#close-"+id).attr('class','red-text pull-right glyphicon glyphicon-minus');
      }
      else{
        panelbody.css('display','none');
        $("#close-"+id).attr('class','green-text pull-right glyphicon glyphicon-plus');
      }

    }
    </script>
