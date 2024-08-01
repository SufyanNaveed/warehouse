<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('layout/header');
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard -->
                <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('warehouse'); ?>"><!-- Warehouse -->
                  <?php echo $this->lang->line('warehouse_header'); ?></a></li>
          <li class="active"><!-- Edit Warehouse -->
            <?php echo $this->lang->line('edit_warehouse_header'); ?>
          </li>
        </ol>
      </h5>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
      <!-- right column -->
      <?php $this->load->view('suggestion.php'); ?>
      <div class="col-md-3">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>
              
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                  <li><a href="<?=base_url('company_setting')?>"><i class="fa fa-sliders"></i> Company Setting</a></li>
                  <li><a href="<?=base_url('location')?>"><i class="fa fa-map-marker"></i> Location</a></li>
                  <li><a href="<?=base_url('branch')?>"><i class="fa fa-dot-circle-o"></i> Branch</a></li>
                  <li><a href="<?=base_url('discount')?>"><i class="fa fa-percent"></i> Discount</a></li>
                  <li class="active"><a href="<?=base_url('warehouse')?>"><i class="fa fa-building-o"></i> Warehouse</a></li>
                  <li><a href="<?=base_url('email_setup')?>"><i class="fa fa-envelope-o"></i> Email Setup</a></li>
                  <li><a href="<?=base_url('sms_setting')?>"><i class="fa fa-commenting-o"></i> SMS Setting</a></li>
                  <li><a href="<?=base_url('invoice_setup')?>"><i class="fa fa-bars"></i> Invoice Setup</a></li>
                  <li><a href="<?=base_url('payment_method')?>"><i class="fa fa-credit-card"></i> Payment Method</a></li>
                  <li><a href="<?=base_url('expense_category')?>"><i class="fa fa-usd"></i> Expense Category</a></li>
                  <li><a href="<?=base_url('currency')?>"><i class="fa fa-gg-circle"></i> Currency</a></li>
                </ul>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      <div class="col-md-9">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><!-- Edit Warehouse -->
            <?php echo $this->lang->line('edit_warehouse_header'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-sm-6">
                <form role="form" id="form" method="post" action="<?php echo base_url('warehouse/editWarehouse');?>">
                  <?php foreach($data as $row){?>
                    <div class="form-group">
                      <label for="branch"><!-- Select Branch --> 
                        <?php echo $this->lang->line('add_biller_select_branch'); ?> <span class="validation-color">*</span></label>
                      <select class="form-control select2" id="branch" name="branch" style="width: 100%;">
                        <option value=""><!-- Select -->
                        <?php echo $this->lang->line('add_biller_select'); ?></option>
                        <?php
                          foreach ($branch as $key) {
                        ?>
                            <option value='<?php echo $key->branch_id ?>' <?php if($key->branch_id == $row->branch_id){echo "selected";} ?>><?php echo $key->branch_name ?></option>
                        <?php
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_branch_id"><?php echo form_error('branch'); ?></span>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label for="warehouse_name"><!-- Warehouse Name -->
                        <?php echo $this->lang->line('add_warehouse_name'); ?> <span class="validation-color">*</span></label>
                      <input type="text" class="form-control" id="warehouse_name" name="warehouse_name" value="<?php echo $row->warehouse_name; ?>">
                      <input type="hidden" name="id" value="<?php echo $row->warehouse_id;?>">
                      <span class="validation-color" id="err_warehouse_name"><?php echo form_error('warehouse_name'); ?></span>
                    </div>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="primary_warehouse" 
                          <?php 
                            if($row->primary_warehouse==1){
                              echo 'checked disabled';
                            }
/*
                            if($warehouse_count==1){
                              echo ' disabled';
                            }*/

                          ?>
                        > Primary
                      </label>
                        <?php 
                            if($row->primary_warehouse==1){
                              echo '<br/>(<span style="color:red">*</span> To change the primary warehouse to secondary you need to select other warehouse as primary )';
                            }
                        ?>                  
                        <br/>
                       (<span style="color: red;">*</span> Only 1 Primary warehouse is allowed. Making this warehouse primary, other warehouse will become automatically secondary.)
                    </div>
                  </div>
                <div class="col-sm-12">
                  <div class="box-footer">
                    <button type="submit" id="submit" class="btn btn-info"><!-- Update --><?php echo $this->lang->line('edit_biller_btn'); ?></button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('warehouse')"><!-- Cancel -->
                      <?php echo $this->lang->line('add_user_btn_cancel'); ?></span>
                  </div>
                </div>
                  <?php } ?>
                </form>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
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
<script>
  $(document).ready(function(){
    $("#submit").click(function(event){
      var name_regex = /^[-\sa-zA-Z0-9 ]+$/;
      var branch_id = $('#branch').val();
      var warehouse_name = $('#warehouse_name').val();


        if(branch_id==""){
          $("#err_branch_id").text("Please Select Branch.");
          return false;
        }
        else{
          $("#err_branch_id").text("");
        }
//branch id validation complite.

        if(warehouse_name==null || warehouse_name==""){
          $("#err_warehouse_name").text("Please Enter Warehouse Name.");
          return false;
        }
        else{
          $("#err_warehouse_name").text("");
        }
        if (!warehouse_name.match(name_regex) ) {
          $('#err_warehouse_name').text(" Please Enter Valid Warehouse Name  ");   
          return false;
        }
        else{
          $("#err_warehouse_name").text("");
        }
//warehouse name validation complite.
        
    });

    $("#branch").change(function(event){
        var branch_id = $('#branch').val();
        if(branch_id==""){
          $("#err_branch_id").text(" Please Select Branch.");
          return false;
        }
        else{
          $("#err_branch_id").text("");
        }
    });

    $("#warehouse_name").on("blur keyup", function(){
        var name_regex = /^[-\sa-zA-Z0-9 ]+$/;
        var warehouse_name = $('#warehouse_name').val();
        if(warehouse_name==null || warehouse_name==""){
          $("#err_warehouse_name").text("Please Enter Warehouse Name.");
          return false;
        }
        else{
          $("#err_warehouse_name").text("");
        }
        if (!warehouse_name.match(name_regex) ) {
          $('#err_warehouse_name').text(" Please Enter Valid Warehouse Name  ");   
          return false;
        }
        else{
          $("#err_warehouse_name").text("");
        }
    });
   
}); 
</script>
