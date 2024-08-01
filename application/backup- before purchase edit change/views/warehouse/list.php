<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('layout/header');
?>
<script type="text/javascript">
  function delete_id(id)
  {
     if(confirm('Sure To Remove This Record ?'))
     {
        window.location.href='<?php  echo base_url('warehouse/delete/'); ?>'+id;
     }
  }
  setTimeout(function() {
      $('.flash_message').hide('slow');
  }, 3000);
</script>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
         <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> 
                <!-- Dashboard -->
                <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li class="active"><!-- Warehouse -->
                  <?php echo $this->lang->line('warehouse_header'); ?>
                    
          </li>
        </ol>
      </h5> 
    </section>
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="col-md-12">
          <?php if($no_of_branch == 0){ ?>
          <a href="<?php echo base_url('branch/add');?>" name="no_of_branch" class="btn bg-orange margin pull-right" >Add Branch</a>  
          <?php } ?>
        </div>
      </div>      
    </section>
    <section class="content">
      <div class="row">
      <!-- right column -->
        <?php
          $this->load->view('suggestion.php');
        ?>
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
          <?php if ($this->session->flashdata('fail') != ''){ ?>
          <div class="alert alert-warning alert-dismissible flash_message">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <?php echo $this->session->flashdata('fail'); ?>
          </div>
          <?php 
              }
          ?>
          <?php  if ($this->session->flashdata('success') != ''){ ?>
          <div class="alert alert-success alert-dismissible flash_message">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <?php echo $this->session->flashdata('success'); ?>
          </div>
          <?php } ?>
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><!-- List Warehouse -->
                <?php echo $this->lang->line('warehouse_label'); ?>
              </h3>
              <!--<a class="btn btn-sm btn-info pull-right bg-maroon" style="margin-left: 10px" href="<?php echo base_url('assign_warehouse/add'); ?>" title="Assign Warehouse" onclick="">
                  
                Assign Warehouse              
              </a>-->
              <a class="btn btn-sm btn-info pull-right" style="margin-left: 10px" href="<?php echo base_url('warehouse/add');?>" title="Add New Category" onclick="">
                  <!-- Add New Warehouse -->
                <?php echo $this->lang->line('warehouse_btn_new'); ?>
              </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="index" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><!-- No -->
                      <?php echo $this->lang->line('warehouse_label_no'); ?>
                  </th>
                  <th><!-- Warehose Name -->
                      <?php echo $this->lang->line('warehouse_label_wname'); ?>
                  </th>
                  <th><!-- Branch Name -->
                      <?php echo $this->lang->line('warehouse_label_bname'); ?>
                  </th>
                  <th><!-- Warehouse Type -->
                      <?php echo $this->lang->line('warehouse_type_list'); ?>
                  </th>
                  <th><!-- User Name -->
                      <?php echo $this->lang->line('warehouse_label_uname'); ?>
                  </th>
                  <th><!-- Actions -->
                      <?php echo $this->lang->line('warehouse_label_action'); ?>
                  </th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                      foreach ($data as $row) {
                         $id= $row->warehouse_id;
                    ?>
                    <tr>
                      <td></td>
                      <td><?php echo $row->warehouse_name; ?></td>
                      <td><?php echo $row->branch_name; ?></td>
                      <td><?php if($row->primary_warehouse == 1){echo "Primary";}else{ echo "Secondary";} ?></td>
                      <td><?php echo $row->first_name.' '.$row->last_name; ?></td>
                      <td>
                          <a href="<?php echo base_url('warehouse/edit/'); ?><?php echo $id; ?>" title="Edit" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                          
                            <a href="javascript:delete_id(<?php echo $id;?>)" title="Delete" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                          
                    <?php
                      }
                    ?>
                <tfoot>
                <tr>
                  <th><!-- No -->
                      <?php echo $this->lang->line('warehouse_label_no'); ?>
                  </th>
                  <th><!-- Warehose Name -->
                      <?php echo $this->lang->line('warehouse_label_wname'); ?>
                  </th>
                  <th><!-- Branch Name -->
                      <?php echo $this->lang->line('warehouse_label_bname'); ?>
                  </th>
                  <th><!-- Warehouse Type -->
                      <?php echo $this->lang->line('warehouse_type_list'); ?>
                  </th>
                  <th><!-- User Name -->
                      <?php echo $this->lang->line('warehouse_label_uname'); ?>
                  </th>
                  <th><!-- Actions -->
                      <?php echo $this->lang->line('warehouse_label_action'); ?>
                  </th>
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
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
  $this->load->view('layout/footer');
?>
