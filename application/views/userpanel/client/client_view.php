<?php $this->load->view('includes/header'); ?> 
<!-- Navigation -->
<?php $this->load->view('includes/head'); ?> 

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

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading home-heading">
                <span><?php if ($lang["manage_client_dealer"]['description'] != '') {
    echo $lang["manage_client_dealer"]['description'];
} else {
    echo "MANAGE CLIENT / DEALER";
} ?></span>
            </div>
            <div class="panel-body">
                <legend >
                    <a href="<?php echo $base_url; ?>client_kare/client_add" style="float:left;" ><button class="btn btn-danger" type="button" > <?php if ($lang["add_single_client"]['description'] != '') {
    echo $lang["add_single_client"]['description'];
} else {
    echo "Add Single Client";
} ?></button> &nbsp; </a>
                    <a href="<?php echo $base_url; ?>client_kare/add_client_excel" style="float:left;" ><button class="btn btn-danger" type="button" ><?php if (
        $lang["import_client_excel"]['description'] != '') {
    echo $lang["import_client_excel"]['description'];
} else {
    echo "Import Client Excel";
}
?></button> &nbsp;</a>
                    <div style="float:left;" >
<?php echo form_open_multipart($base_url . 'client_kare/download_clientExcel', 'class="form-horizontal"'); ?><button class="btn btn-danger" type="submit" > <?php if ($lang["export_client_excel"]['description'] != '') {
    echo $lang["export_client_excel"]['description'];
} else {
    echo "Export Client Excel";
} ?></button><?php echo form_close(); ?>
                    </div>
                    <a href="<?php echo base_url("uploads/sampleFile/SampleClientFile.xlsx"); ?>" style="float:right;" >&nbsp;<button class="btn btn-danger" type="button" > <?php if ($lang["download_sample_client_excel"]['description'] != '') {
    echo $lang["download_sample_client_excel"]['description'];
} else {
    echo "Download Sample Client Excel";
} ?></button></a>
                </legend>
                <BR/>
                <table id="table_client_list" class="table table-bordered table-hover">
                    <thead>
                            <tr><th><?php if ($lang["action"]['description'] != '') {
                                echo $lang["action"]['description'];
                            } else {
                                echo "Action";
                            } ?></th>
                           <th><?php if ($lang["name"]['description'] != '') {
                                echo $lang["name"]['description'];
                            } else {
                                echo "Name";
                            } ?></th>
                           <th><?php if ($lang["district"]['description'] != '') {
                                echo $lang["district"]['description'];
                            } else {
                                echo "District";
                            } ?></th>
                           <th><?php if ($lang["circle"]['description'] != '') {
                                echo $lang["circle"]['description'];
                            } else {
                                echo "Circle";
                            } ?></th>
                           <th><?php if ($lang["contact_person"]['description'] != '') {
                                echo $lang["contact_person"]['description'];
                            } else {
                                echo "Contact Person";
                            } ?></th>
                           <th><?php if ($lang["contact"]['description'] != '') {
                                echo $lang["contact"]['description'];
                            } else {
                                echo "Contact No";
                            } ?> </th>
                           <th><?php if ($lang["contact_email"]['description'] != '') {
                                echo $lang["contact_email"]['description'];
                            } else {
                                echo "Contact Email";
                            } ?> </th>
                           <th><?php if ($lang["client_type"]['description'] != '') {
                                echo $lang["client_type"]['description'];
                            } else {
                                echo "Client Type";
                            } ?></th>
                           <th><?php if ($lang["status"]['description'] != '') {
                                echo $lang["status"]['description'];
                            } else {
                                echo "Status";
                            } ?></th>
                           </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<?php $this->load->view('includes/footer'); ?>
<!-- Scripts -->
<?php $this->load->view('includes/scripts'); ?>