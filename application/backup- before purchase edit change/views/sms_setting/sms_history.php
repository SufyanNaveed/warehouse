<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('layout/header');
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
         <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('sms_setting'); ?>"><?php echo "SMS Setting" ?></a></li>
          <li class="active">SMS Histroy</li>
        </ol>
      </h5> 
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <?php $this->load->view('suggestion.php'); ?>
        <?php 
          $this->load->view('layout/setting_sidebar');
        ?>
        <div class="col-md-9">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title pull-left">SMS History</h3>
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="log_datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $this->lang->line('product_no'); ?></th>
                  <th>Mobile</th>
                  <th>Message</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                      $id = 1;
                      foreach ($sms_history as $row) {
                    ?>
                    <tr>
                      <td><?php echo $id; $id++; ?></td>
                      <td><?php echo $row->mobile; ?></td>
                      <td><?php echo $row->message; ?></td>
                      
                    <?php
                      }
                    ?>
                <tfoot>
                <tr>
                  <th><?php echo $this->lang->line('product_no'); ?></th>
                  <th>Mobile</th>
                  <th>Message</th>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
  $this->load->view('layout/footer');
?>
