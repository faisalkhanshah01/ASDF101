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
                            <?php echo form_open_multipart(current_url(), 'id="fileupload" class="master_data_form"'); ?>

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
                                          <!-- file upload feature starts-->
                                          <div class="form-group col-md-12">
                                            <div class="row fileupload-buttonbar">
                                                        <div class="col-lg-7">
                                                            <!-- The fileinput-button span is used to style the file input field as button -->
                                                            <span class="btn btn-success fileinput-button">
                                                                <i class="glyphicon glyphicon-plus"></i>
                                                                <span>Add files...</span>
                                                                <input type="file" name="files[]" multiple>
                                                            </span>
                                                            <button type="submit" class="btn btn-primary start">
                                                                <i class="glyphicon glyphicon-upload"></i>
                                                                <span>Start upload</span>
                                                            </button>
                                                            <button type="reset" class="btn btn-warning cancel">
                                                                <i class="glyphicon glyphicon-ban-circle"></i>
                                                                <span>Cancel upload</span>
                                                            </button>
                                                            <!-- <button type="button" class="btn btn-danger delete">
                                                                <i class="glyphicon glyphicon-trash"></i>
                                                                <span>Delete</span>
                                                            </button> -->
                                                            <!-- The global file processing state -->
                                                            <span class="fileupload-process"></span>
                                                        </div>
                                                        <!-- The global progress state -->
                                                        <div class="col-lg-5 fileupload-progress fade">
                                                            <!-- The global progress bar -->
                                                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                                                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                                            </div>
                                                            <!-- The extended global progress state -->
                                                            <div class="progress-extended">&nbsp;</div>
                                                        </div>
                                                    </div>
                                                    <!-- The table listing the files available for upload/download -->
                                                    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
                                          </div>
                                          <!-- file upload feature ends -->

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

    <!-- Code for jquery file upload  -->
    <!-- The template to display files available for upload -->
    <script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-upload fade">
            <td>
                <span class="preview"></span>
            </td>
            <td>
                <p class="name">{%=file.name%}</p>
                <strong class="error text-danger"></strong>
            </td>
            <td>
                <p class="size">Processing...</p>
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
            </td>
            <td>
                {% if (!i && !o.options.autoUpload) { %}
                    <button class="btn btn-primary start" disabled>
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Start</span>
                    </button>
                {% } %}
                {% if (!i) { %}
                    <button class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel</span>
                    </button>
                {% } %}
            </td>
        </tr>
    {% } %}
    </script>
    <!-- The template to display files available for download -->
    <script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
        <tr class="template-download fade">
            <td>
                <span class="preview">
                    {% if (file.thumbnailUrl) { %}
                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                    {% } %}
                </span>
            </td>
            <td>
                <p class="name">
                    {% if (file.url) { %}
                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                    {% } else { %}
                        <span>{%=file.name%}</span>
                    {% } %}
                </p>
                {% if (file.error) { %}
                    <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                {% } %}
            </td>
            <td>
                <span class="size">{%=o.formatFileSize(file.size)%}</span>
            </td>
            <td>
                {% if (file.deleteUrl) { %}
                    <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                    </button>
                    <input type="checkbox" name="delete" value="1" class="toggle">
                {% } else { %}
                    <button class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel</span>
                    </button>
                {% } %}
            </td>
        </tr>
    {% } %}
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
    <script src="<?php echo $includes_dir;?>fileupload/js/vendor/jquery.ui.widget.js"></script>
    <!-- The Templates plugin is included to render the upload/download listings -->
    <script src="https://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <script src="https://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
    <!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- blueimp Gallery script -->
    <script src="https://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
    <script src="<?php echo $includes_dir;?>fileupload/js/jquery.iframe-transport.js"></script>
    <!-- The basic File Upload plugin -->
    <script src="<?php echo $includes_dir;?>fileupload/js/jquery.fileupload.js"></script>
    <!-- The File Upload processing plugin -->
    <script src="<?php echo $includes_dir;?>fileupload/js/jquery.fileupload-process.js"></script>
    <!-- The File Upload image preview & resize plugin -->
    <script src="<?php echo $includes_dir;?>fileupload/js/jquery.fileupload-image.js"></script>
    <!-- The File Upload audio preview plugin -->
    <script src="<?php echo $includes_dir;?>fileupload/js/jquery.fileupload-audio.js"></script>
    <!-- The File Upload video preview plugin -->
    <script src="<?php echo $includes_dir;?>fileupload/js/jquery.fileupload-video.js"></script>
    <!-- The File Upload validation plugin -->
    <script src="<?php echo $includes_dir;?>fileupload/js/jquery.fileupload-validate.js"></script>
    <!-- The File Upload user interface plugin -->
    <script src="<?php echo $includes_dir;?>fileupload/js/jquery.fileupload-ui.js"></script>
    <!-- The main application script -->
    <script src="<?php echo $includes_dir;?>fileupload/js/main.js"></script>
    <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
    <!--[if (gte IE 8)&(lt IE 10)]>
    <script src="<?php echo $includes_dir;?>fileupload/js/cors/jquery.xdr-transport.js"></script>
    <!-- Code for jquery file upload  ends-->
