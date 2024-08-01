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
          <li>
              <a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> 
                <?php echo $this->lang->line('header_dashboard'); ?>
              </a>
          </li>
          <li>
              <a href="<?php echo base_url('expense'); ?>">
               Expense 
              </a>
          </li>
          <li class="active">New Expense 
              
          </li>
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
            <div class="box-header with-border">
              <h3 class="box-title">
               New Expense 
                <!-- <?php echo $this->lang->line('add_customer_header'); ?> -->
              </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <form role="form" id="form" method="post" action="<?php echo base_url('expense/add');?>">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="branch" class="control-label">Branch<label style="color:red;">*</label></label>
                      <div class="control">
                          <select class="form-control select2" id="branch" name="branch">
                            <option value="">Select</option>
                            <?php foreach ($branch as $value) {?>
                            <option value="<?php echo $value->branch_id;?>"><?php echo $value->branch_name;?></option>
                            <?php } ?>    
                          </select>
                          <span style="color: red;"><?php echo form_error('branch'); ?></span>
                          <p style="color:#990000;"></p>
                      </div>
                  </div>

                  <div class="form-group to_ledger">
                    <label for="to_ledger" class="control-label">Category<label style="color:red;">*</label></label>
                      <div class="control">
                          <select class="form-control select2" id="to_ledger" name="to_ledger">
                            <option value="">Select</option>  
                            <?php foreach ($to_ledger as $value) {?>
                            <option value="<?php echo $value->id;?>"><?php  echo $value->title;?></option>
                            <?php } ?>
                            </select>
                          <span style="color: red;"><?php echo form_error('to_ledger'); ?></span>
                          <p style="color:#990000;"></p>
                      </div>
                  </div>

                  <div class="form-group from_ledger">
                        <label for="from_ledger" class="control-label">Account<label style="color:red;">*</label></label>
                        <div class="control">
                          <select class="form-control select2" id="from_ledger" name="from_ledger" tabindex="2">
                            <option value="">Select</option>
                            <?php foreach ($from_ledger as $value) {?>
                            <option value="<?php echo $value->id;?>"><?php echo $value->title;?></option>
                            <?php } ?>  
                          </select>
                          <span style="color: red;"><?php echo form_error('from_ledger'); ?></span>
                          <p style="color:#990000;"></p>  
                        </div> 
                  </div>

                  <div class="form-group">
                      <label for="inputEmail3" class="control-label">Date<label style="color:red;">*</label></label>
                      
                       <div class="input-group date">
                         <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                         </div>
                         <div class="control">
                            <input type="text" class="form-control pull-right datepicker" autocomplete="off" name="date" tabindex="2" onblur='chkEmpty("Form","date","Please Select Date");' placeholder="<?php echo $this->lang->line('lbl_addexpense_date');?>">
                         </div>
                            <span style="color: red;"><?php echo form_error('date'); ?></span>
                            <p style="color:#990000;"></p>  
                       </div>
                    
                  </div>

                  <div class="form-group">
                    <label for="inputEmail3" class="control-label">
                      Description
                      
                    </label>
                      
                        <div class="control">
                            <input type="text" class="form-control" name="desc" id="desc" placeholder="<?php echo $this->lang->line('lbl_addexpense_desc');?>" tabindex="2" onblur='chkEmpty("Form","desc","Please Enter Name");'>
                            <span style="color: red;"><?php echo form_error('desc'); ?></span>
                             <p style="color:#990000;"></p>
                          </div> 
                     
                      </div>

                  <div class="form-group">
                    <label for="inputEmail3" class="control-label">
                      Amount
                      
                    <label style="color:red;">*</label></label>
                      
                        <div class="control">
                            <input type="number" class="form-control" name="amount" id="amount" placeholder="<?php echo $this->lang->line('lbl_addexpense_amount');?>" min="0" step=".01" tabindex="2" onblur='chkEmpty("Form","amount","Please Enter Name");'>
                            <span style="color: red;"><?php echo form_error('amount'); ?></span>
                             <p style="color:#990000;"></p>
                          </div> 
                      </div>
                  
                    
                  
                  <div class="form-group">
                    <label for="inputEmail3" class="control-label">
                      Payment Method
                      
                      <label style="color:red;">*</label></label>
                      
                        <div class="control">
                            <select class="form-control select2" id="payment_method_id" name="payment_method_id">
                            <?php foreach ($payment_methods as $value) {?>
                              <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                            <?php } ?>    
                            </select>
                            <span style="color: red;"><?php echo form_error('payment_method_id'); ?></span>
                            <p style="color:#990000;"></p>
                          </div> 
                      
                  </div>

                  <div class="form-group">
                    <label for="inputEmail3" class="control-label">Reference</label>                 
                    <input type="text" class="form-control" name="reference" id="reference" value="" placement="<?php echo $this->lang->line('lbl_addexpense_reference');?>">
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="box-footer">
                    <input type="hidden" name="type" value="E">
                    <button type="submit" id="submit" class="btn btn-info">&nbsp;&nbsp;&nbsp;<!-- Add -->
                        <?php echo $this->lang->line('add_user_btn'); ?>&nbsp;&nbsp;&nbsp;</button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('expense')"><!-- Cancel -->
                      <?php echo $this->lang->line('add_user_btn_cancel'); ?></span>
                  </div>
                </div>
              </form>
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
<script>
  $(document).ready(function(){

    // $(".from_ledger").hide();
    // $(".to_ledger").hide();
    
    // $("#branch").change(function(event){
    //   var branch = $('#branch').val();

    //   if(branch=="")
    //   {
    //     $("#err_branch").text("Please Enter Branch");

    //     $(".from_ledger").hide();
    //     $(".to_ledger").hide();

    //     $('#to_ledger').html('<option value="">Select</option>');
    //     $('#from_ledger').html('<option value="">Select</option>');


    //     $('#branch').focus();
    //     return false;
    //   }
    //   else{

    //     $("#err_branch").text("");

    //     $(".from_ledger").show();
    //     $(".to_ledger").show();

    //     $('#to_ledger').html('<option value="">Select</option>');
    //     $('#from_ledger').html('<option value="">Select</option>');

    //     $.ajax({
    //       url: "<?php echo base_url('ledger/get_ledger_by_branch') ?>/"+branch,
    //       type: "GET",
    //       dataType: "JSON",
    //       success: function(data){
    //         for(i=0;i<data.length;i++){
    //           if(data[i].category == "Expense" || data[i].category == "Liabilities" )
    //           $('#to_ledger').append('<option value="' + data[i].id + '">' + data[i].title + '</option>');
    //         }

    //         for(i=0;i<data.length;i++){
    //           if(data[i].category == "Income" || data[i].category == "Assets" )
    //           $('#from_ledger').append('<option value="' + data[i].id + '">' + data[i].title + '</option>');
    //         }
    //       }
    //     });
    //   }
    // });
    
  }); 
</script>
