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
          <li class="active"><?php echo "Profit &amp; Loss Report"; ?></li>
        </ol>
      </h5> 
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
      <!-- right column -->
      <?php $this->load->view('suggestion.php'); ?>
        <div class="col-md-12">
          <div class="box">
            <div class="box-body">
              <div class="row hide1">
                <form target="_blank" id="edit-profile" method="post" action="<?php echo base_url('reports/pl_report');?>">
                  <div class="col-md-4">

                    <div class="form-group">
                      <label for="branch"><?php echo "Select Branch"; ?></label>
                      <select class="form-control select2" id="branch" name="branch" style="width: 100%;">
                        <option value="0"><?php echo "All Branch"; ?></option>
                        <?php

                          foreach ($branch as $row) {
                            echo "<option value='$row->branch_id'".set_select('branch_id',$row->branch_id).">$row->branch_name</option>";
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_branch"><?php echo form_error('branch'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="start_date"><?php echo $this->lang->line('reports_start_date'); ?></label>
                      <input type="text" class="form-control datepicker" id="start_date" name="start_date" value="<?php echo date("Y-m-d");  ?>" required="required">
                      <span class="validation-color" id="err_start_date"><?php echo form_error('start_date'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="end_date"><?php echo $this->lang->line('reports_end_date'); ?></label>
                      <input type="text" class="form-control datepicker" id="end_date" name="end_date" value="<?php echo date("Y-m-d");  ?>" required="required">
                      <span class="validation-color" id="err_end_date"><?php echo form_error('end_date'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="box-footer">
                      <input type="submit" class="btn btn-info" id="submit" name="submit" value="<?php echo $this->lang->line('reports_submit'); ?>">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo "Profit & Loss Report"; ?></h3>
              <button type="submit" class="btn btn-sm pull-right" onclick="printDiv('print_pl_report')" style="margin-right: 10px;">
                <i class="fa fa-print"></i>
              </button>
            </div>
            <div class="box-body">
              <div class="print_pl_report">
              <?php 
                $this->load->view('reports/pl_report_print')
              ?>
              </div>
            </div>
           
          </div>
          
        </div>
        
      </div>
      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
  $this->load->view('layout/footer');
?>
<script type="text/javascript">
  $(document).ready(function(){
    
  });
  // $("#hide1").click(function(){
  //   $(".hide1").toggle();
  // });
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
  }
</script>
<script type="text/javascript">
$(document).ready(function() {
  $('.datepicker').datepicker({
      autoclose: true,
      format: "yyyy-mm-dd",
      todayHighlight: true,
      orientation: "auto",
      todayBtn: true,
      todayHighlight: true,  
  });
});
</script>

<script type="text/javascript">
  
</script>
