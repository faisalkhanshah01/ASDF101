<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title><?=$title?></title>
  <link rel="stylesheet" href='<?php echo base_url("assets/bootstrap/css/bootstrap.css"); ?>' />
  <link rel="stylesheet" href='<?php echo base_url("assets/css/bootstrap-datetimepicker.min.css"); ?>' />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" />
  <link rel="stylesheet" href='<?php echo base_url("assets/css/custom.css?ver=1.02"); ?>' />
  <link rel="shortcut icon" href='<?php echo base_url("assets/images/system/favicon.ico"); ?>' type="image/x-icon">
  <link rel="icon" href='<?php echo base_url("assets/images/system/favicon.ico"); ?>' type="image/x-icon">
  <meta property="og:title" content='<?=$title?>' />
  <meta property="og:image" content='<?php echo base_url("assets/images/system/arresto-logo.jpg"); ?>'>
  <meta property="og:description" content="Arresto Site">
</head>
<body id="after">
  <div class="container-fluid" id="after-login-layout">
    <div class="row">
      <header>
        <?php $this->load->view('admin/common/navigation'); ?>
      </header>
      <div class="container-fluid content-section">
        <div class="row">