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
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard --> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('bank_account'); ?>">Bank Account</a></li>
          <li class="active">Add Bank Account</li>
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
              <h3 class="box-title">Add New Bank Account</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                <?php if(isset($bank_account)){ ?>
                <form role="form" id="form" method="post" action="<?php echo base_url('bank_account/edit');?>">
                <?php }else{ ?>
              	<form role="form" id="form" method="post" action="<?php echo base_url('bank_account/add');?>">
              	<?php } ?>
                    <div class="form-group">
                      <label for="account_group_id">Account Group<span class="validation-color">*</span></label>
                      <select class="form-control select2" id="account_group_id" name="account_group_id" style="width: 100%;">
                        <option value="">Select</option>
                        <?php
                          foreach ($account_groups as $row) {
                            if($row->category != "Expense"){
                        ?>
                        <option value="<?=$row->id?>"
                              <?php 
                                  if(isset($ledger)){ 
                                    if($ledger->accountgroup_id == $row->id)
                                      echo " selected";
                                  } 
                              ?>><?=$row->group_title." (".$row->category.")"?></option>
                        <?php
                            }
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_account_group_id"><?php echo form_error('account_group_id'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="account_name">Account Name<span class="validation-color">*</span></label>
                      <input type="text" class="form-control" id="account_name" name="account_name" value="<?php if(isset($bank_account)){ echo $bank_account->account_name;} else {echo set_value('account_name');} ?>">
                      <span class="validation-color" id="err_account_name"><?php echo form_error('account_name'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="type">Account Type<span class="validation-color">*</span></label>
                      <select class="form-control select2" id="type" name="type" style="width: 100%;">
                        <option value="">Select</option>
                        <option value="Savings Account"
                        					<?php 
                                            if(isset($bank_account)){ 
                                              if($bank_account->account_type == "Savings Account")
                                                echo " selected";
                                            } 
                                        ?>>Savings Account</option>
                        <option value="Credit Account"
                        				<?php 
                                            if(isset($bank_account)){ 
                                              if($bank_account->account_type == "Credit Account")
                                                echo " selected";
                                            } 
                                        ?>>Credit Account</option>
                        <option value="Cash Account"
                        				<?php 
                                            if(isset($bank_account)){ 
                                              if($bank_account->account_type == "Cash Accounts")
                                                echo " selected";
                                            } 
                                        ?>>Cash Account</option>
                        <option value="Chequing Account"
                        				<?php 
                                            if(isset($bank_account)){ 
                                              if($bank_account->account_type == "Chequing Account")
                                                echo " selected";
                                            } 
                                        ?>>Chequing Account</option>
                      </select>
                      <span class="validation-color" id="err_type"><?php echo form_error('type'); ?></span>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label for="account_number">Account Number<span class="validation-color">*</span></label>
                      <input type="text" class="form-control" id="account_number" name="account_number" value="<?php if(isset($bank_account)){ echo $bank_account->account_no;} else {echo set_value('account_no');} ?>">
                      <span class="validation-color" id="err_account_number"><?php echo form_error('account_number'); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label for="bank_name">Bank Name<span class="validation-color">*</span></label>
                      <input type="text" class="form-control" id="bank_name" name="bank_name" value="<?php if(isset($bank_account)){ echo $bank_account->bank_name;} else {echo set_value('bank_name');} ?>">
                      <span class="validation-color" id="err_bank_name"><?php echo form_error('bank_name'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="balance">Opening Balance<span class="validation-color">*</span></label>
                      <input type="text" class="form-control" id="balance" name="balance" value="<?php if(isset($bank_account)){ echo $bank_account->opening_balance;} else {echo set_value('opening_balance');} ?>">
                      <span class="validation-color" id="err_balance"><?php echo form_error('balance'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="address">Bank Address<span class="validation-color">*</span></label>
                      <input type="text" class="form-control" id="address" name="address" value="<?php if(isset($bank_account)){ echo $bank_account->bank_address;} else {echo set_value('bank_address');} ?>">
                      <span class="validation-color" id="err_address"><?php echo form_error('address'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="default">Default Account</label>
                      <select class="form-control select2" id="default" name="default" style="width: 100%;">
                        <option value="YES"
                        			<?php 
                                        if(isset($bank_account)){ 
                                          if($bank_account->default_account == "YES")
                                            echo " selected";
                                        } 
                                    ?>>YES</option>
                        <option value="NO"
                        			<?php 
                                        if(isset($bank_account)){ 
                                          if($bank_account->default_account == "NO")
                                            echo " selected";
                                        } 
                                    ?>>NO</option>
                      </select>
                      <span class="validation-color" id="err_default"><?php echo form_error('default'); ?></span>
                    </div>
                </div>
                <div class="col-sm-12">
                  <div class="box-footer">
                  	<input type="hidden" name="bank_account_id" value="<?php if(isset($bank_account)){ echo $bank_account->id;} ?>">
                  	<input type="hidden" name="ledger_id" value="<?php if(isset($bank_account)){ echo $bank_account->ledger_id;} ?>">
                    <button type="submit" id="submit" class="btn btn-info">&nbsp;&nbsp;&nbsp;<!-- Add --><?php echo $this->lang->line('subcategory_add'); ?>&nbsp;&nbsp;&nbsp;</button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('bank_account')"><!-- Cancel --><?php echo $this->lang->line('subcategory_cancel'); ?></span>
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
