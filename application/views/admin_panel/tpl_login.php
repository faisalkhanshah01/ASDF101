<?php
  $this->load->view('admin_panel/common/header',$header_data);
  $this->load->view($page);
  $this->load->view('admin_panel/common/footer');
?>