<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('layout/header');
?>
<script type="text/javascript">
  function delete_id(id)
  {
     if(confirm('Sure To Remove This Record ?'))
     {
        window.location.href='<?php  echo base_url('category/delete/'); ?>'+id;
     }
  }
  $(function() {
      // setTimeout() function will be fired after page is loaded
      // it will wait for 5 sec. and then will fire
      // $("#successMessage").hide() function
      setTimeout(function() {
          $(".message").hide('blind', {}, 500)
      }, 5000);
  });
</script>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
         <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard --><?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li class="active">Cash Flow</li>
        </ol>
      </h5> 
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
      <?php $this->load->view('suggestion.php'); ?>
      <div class="col-md-12">
        <?php 
              if($this->session->flashdata('success') != ''){ 
            ?>
            <div class="alert alert-success message">    
              <p><?php echo $this->session->flashdata('success');?></p>
            </div>
            <?php
              }
            ?>

            <?php 
              if($this->session->flashdata('failure') != ''){ 
            ?>
            <div class="alert alert-danger message">    
              <p><?php echo $this->session->flashdata('failure');?></p>
            </div>
            <?php
              }
            ?>
      </div>
      <!-- right column -->
      <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><!-- List Category -->
                 Cash Flow
              </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row hide1">
              <form target="" id="edit-profile" method="post" action="<?php echo base_url('cash_flow/getCashFlow');?>">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="start_date"><?php echo $this->lang->line('reports_start_date'); ?></label>
                    <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="">
                    <span class="validation-color" id="err_start_date"><?php echo form_error('start_date'); ?></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="end_date"><?php echo $this->lang->line('reports_end_date'); ?></label>
                    <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="<?php echo date("Y-m-d");  ?>">
                    <span class="validation-color" id="err_end_date"><?php echo form_error('end_date'); ?></span>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="box-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="submit" class="btn btn-info btn-flat" id="submit" name="submit" value="<?php echo $this->lang->line('reports_submit'); ?>">
                  </div>
                </div>
              </form>
              
            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
      </div>
       <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"> Cash Flow</h3>
              <!-- <input type="submit" class="pull-right btn btn-info btn-flat" id="pdf" name="submit" value="PDF">
              <input type="submit" class="pull-right btn btn-info btn-flat" id="csv" name="submit" value="CSV"> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="index" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $this->lang->line('product_no'); ?></th>
                  <th>To Account</th>
                  <th>From Account</th>
                  <th>Amount<?php echo '('.$this->session->userdata('symbol').')'; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                  foreach ($data as $row) {
                ?>
                  <tr>
                    <td></td>
                    <td><?php echo $row->to_account; ?></td>
                    <td><?php echo $row->from_account; ?></td>
                    <td><?php echo $row->amount; ?></td>
                  </tr>
                <?php
                  }
                ?>
                </tbody>
                <tfoot>
                <tr>
                  <th><?php echo $this->lang->line('product_no'); ?></th>
                  <th>To Account</th>
                  <th>From Account</th>
                  <th>Amount<?php echo '('.$this->session->userdata('symbol').')'; ?></th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  
  <!-- /.content-wrapper -->
<?php
  $this->load->view('layout/footer');
?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#pdf').click(function(){
      $('form').attr('target','_blank');
    });
    $('#csv').click(function(){
      $('form').attr('target','_blank');
    });
    $('#submit').click(function(){
      $('form').attr('target','');
    });
  });
  $("#hide1").click(function(){
    $(".hide1").toggle();
  });
</script>
