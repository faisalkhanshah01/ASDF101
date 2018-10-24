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
                        <!-- <a class="btn btn-info" id="btn_addstep" href="javascript:void(0);" /><i class="glyphicon glyphicon-plus"></i></a> -->
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

                    <div class="">

                        <div class="row ">
                         <div class="col-md-12">
                            <!-- start -->
                            <!-- Bootstrap 3 panel list. -->
                            <ul id="draggablePanelList" class="sections list-unstyled">
                                <li class="panel panel-info section" id="panel-1">
                                    <div class="panel-heading">
                                      <span class="step-label">Step No.1</span>
                                      <div class="pull-right">
                                      <i class="glyphicon glyphicon-trash"></i>&nbsp;    
                                      <i id="clone-1" onclick="clonebox(this)" class="mr10 cloneicon glyphicon glyphicon-duplicate"></i>
                                      <i id="close-1" onclick="collapsebox(this)" class="collapseicon pull-right glyphicon glyphicon-chevron-up"></i>
                                      </div>
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
                                              <label for="group" class="control-label">Upload Image(*multiple - jpg/png/jpeg)</label>
                                              <input type="file" id="group" class="form-control" name="pdm_image[]" id="pdm_image" multiple="1"/>
                                          </div>
                                          <div class="form-group col-md-12">
                                              <label for="group" class="control-label">Upload Document (*multiple - pdg/doc/ppt)</label>
                                              <input type="file" class="form-control" name="pdm_document[]" id="pdm_document"  multiple="" />
                                          </div>
                                          <div class="form-group col-md-12">
                                              <label for="group" class="control-label">Upload Media (Video Url )</label>
                                              <input type="text" id="pdm_media" class="form-control" name="pdm_media[]" id="pdm_media" />
                                          </div>
                                        

                                      </div>
                                    </div>
                                </li>
                            </ul>
                            <!-- end -->
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
    // $(document).on('click','#btn_addstep',function(){
    //    var p = new Promise(function(resolve,reject){
    //         secCount++;
    //         var template= $('.section:first').clone();
    //           var section= template.clone().find(":input").each(function(index,ele){
    //                     var newid=$(this).attr('id')+secCount;
    //                     $(this).prev('label').attr('for',newid);
    //                     this.id=newid;
    //            }).end();
    //           $('<i class="red-text pull-right glyphicon glyphicon-trash" style="margin: 0px 5px;"></i>').insertAfter(section.find('.home-heading .step-label'));
    //             section.appendTo('.sections');
    //           resolve();
    //     });
    //
    //     p.then(function(){
    //             updateSectionLabel();
    //     });
    //
    // });
    //new code start
    function clonebox(b){
              let cloneid = (parseInt($(b).attr('id').split('-')[1])-1);
              var p = new Promise(function(resolve,reject){
              secCount++;
              var template= $('.section:eq('+cloneid+')').clone();
                var section= template.clone().find(":input").each(function(index,ele){
                          var newid=$(this).attr('id')+secCount;
                          $(this).prev('label').attr('for',newid);
                          this.id=newid;
                 }).end();
                $('<i class="red-text pull-right glyphicon glyphicon-trash" style="margin: 0px 5px;"></i>').insertAfter(section.find('.home-heading .step-label'));
                  section.appendTo('.sections');
                resolve();
          });

          p.then(function(){
                  updateSectionLabel();
          });
    }
    //new code ends

    function updateSectionLabel(){
        $('.sections .section').each(function(index,ele){
               $(this).attr("id","panel-"+(index+1));
               $(this).find('.panel-heading .step-label').html("Step No : "+(index+1));
               $(this).find('.collapseicon').attr("id","close-"+(index+1));
               $(this).find('.cloneicon').attr("id","clone-"+(index+1));
           });
    }

    $(document).on('click','.panel-heading .glyphicon-trash',function(){
           $(this).parents('.section').remove();
            updateSectionLabel();
    });

    function collapsebox(a){

      let id = $(a).attr('id').split('-')[1];
      let panelbody = $("#panel-"+id).find('.panel-body');
      if(panelbody.css('display')=='none'){
            panelbody.css('display','block');
            $("#close-"+id).attr('class','collapseicon pull-right glyphicon glyphicon-chevron-down');
      }
      else{
        panelbody.css('display','none');
        $("#close-"+id).attr('class','collapseicon pull-right glyphicon glyphicon-chevron-up');
      }

    }

    jQuery(function($) {
    var panelList = $('#draggablePanelList');

    panelList.sortable({
        // Only make the .panel-heading child elements support dragging.
        // Omit this to make then entire <li>...</li> draggable.
        handle: '.panel-heading',
        update: function() {
            debugger;
            $('.panel', panelList).each(function(index, elem) {
                 var $listItem = $(elem),
                     newIndex = $listItem.index();
                     $(this).attr("id","panel-"+(index+1));
                     $(this).find('.panel-heading .step-label').html("Step No : "+(index+1));
                     $(this).find('.collapseicon').attr("id","close-"+(index+1));
                     $(this).find('.cloneicon').attr("id","clone-"+(index+1));

                 // Persist the new indices.
            });
        }
    });
});
    </script>
