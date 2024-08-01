<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layout/header');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li class="active">Invoice Setup</li>
        </ol>
      </h5>    
    </section>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <?php $this->load->view('suggestion.php'); ?>
        <?php
          if($fail = $this->session->flashdata('fail')){
        ?>
          <div class="col-sm-12">
            <div class="alert alert-success">
              <button class="close" data-dismiss="alert" type="button">×</button>
                <?php echo $fail; ?>
              <div class="alerts-con"></div>
            </div>
          </div>
        <?php
          }
        ?>
        <?php 
          $this->load->view('layout/setting_sidebar');
        ?>
      <!-- right column -->
        
        <div class="col-md-9">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Invoice Setup</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <form role="form" id="form" method="post" action="<?php echo base_url('invoice_setup/add');?>">
                  <div class="col-md-12">
                  <?php foreach ($data as $value) { ?>
                    <div class="col-md-6 table-responsive">
                      <iframe frameborder="0" scrolling="no" width="400" height="300" src="<?php echo base_url('assets/invoice/').$value->name ?>.pdf"></iframe>
                      <br><input type="radio" name="id" <?php if($value->active==1){ echo "checked"; } ?> value="<?php echo $value->id; ?>"> <b style="font-size: 16px;"><?php echo $value->name; ?></b>
                    </div>
                  <?php } ?>
                  </div>
                  <div class="col-sm-12">
                    <div class="box-footer">
                      <button type="submit" id="submit" name="submit" class="btn btn-info btn-flat"><?php echo $this->lang->line('company_setting_submit'); ?></button>
                      <span class="btn btn-default btn-flat" id="cancel" style="margin-left: 2%" onclick="cancel('auth/dashboard')"><?php echo $this->lang->line('company_setting_cancel'); ?></span>
                    </div>
                  </div>
                </form>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php
    $this->load->view('layout/footer');
  ?>