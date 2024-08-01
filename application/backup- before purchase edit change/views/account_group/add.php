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
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> 
                <!-- Dashboard -->
                <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('accountgroup'); ?>">
               Account Group 
              <!-- <?php echo $this->lang->line('customer_header'); ?> --></a>
          </li>
          <li class="active">New Account Group
             <!--  <?php echo $this->lang->line('add_customer_label'); ?> -->
          </li>
        </ol>
      </h5>    
    </section>
      <section class="content">
      <div class="row">
      <!-- right column -->
        <?php $this->load->view('suggestion.php'); ?>
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">
                New Account Group
                <!-- <?php echo $this->lang->line('add_customer_header'); ?> -->
              </h3>
            </div>
            <!-- /.box-header -->
            <?php echo $this->session->flashdata('success');?>
             <!-- <?php echo validation_errors();?> -->
              <?php if(isset($error)) { echo $error; }?>
            <div class="box-body">
              <div class="col-sm-6">
                <div class="row">
                  <?php if(isset($account_group)){ ?>
                  <form class="form-horizontal" role="form" method="post" action="<?=base_url('account_group/edit')?>">
                  <?php }else{ ?>
                  <form class="form-horizontal" role="form" method="post" action="<?=base_url('account_group/add')?>">
                  <?php } ?>                  
                    <div class="panel-body">
                      <div class="form-group">
                        <label   for="group_title">Group Title <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" placeholder="Group Title" id="group_title" name="group_title" value="<?php if(isset($account_group)){ echo $account_group->group_title;} else {echo set_value('group_title');} ?>">
                        <span class="validation-color" id="err_group_title"><?php echo form_error('group_title'); ?></span>
                      </div>
                      <div class="form-group">
                        <label  for="category">Category</label>
                        <select class="form-control select2" id="category" name="category">
                          <option value="Assets" 
                                      <?php 
                                          if(isset($account_group)){ 
                                            if($account_group->category == "Assets")
                                              echo " selected";
                                          } 
                                      ?>>Assets</option>
                          <option value="Liabilities" 
                                      <?php 
                                          if(isset($account_group)){ 
                                            if($account_group->category == "Liabilities")
                                              echo " selected";
                                          } 
                                      ?>>Liabilities</option>
                          <option value="Income"
                                      <?php 
                                          if(isset($account_group)){ 
                                            if($account_group->category == "Income")
                                              echo " selected";
                                          } 
                                      ?>>Income</option>
                          <option value="Expense"
                                      <?php 
                                          if(isset($account_group)){ 
                                            if($account_group->category == "Expense")
                                              echo " selected";
                                          } 
                                      ?>>Expense</option>
                        </select>
                        <span class="validation-color" id="err_category"><?php echo form_error('category'); ?></span>
                      </div>
                      <div class="form-group">
                        <label   for="opening_balance">Opening Balance Amount</label>
                        <input type="text" class="form-control" placeholder="Opening Balance Amount" id="opening_balance" name="opening_balance" value="<?php if(isset($account_group)){ echo $account_group->opening_balance;} else {echo set_value('opening_balance');} ?>">
                        <span class="validation-color" id="err_opening_balance"><?php echo form_error('opening_balance'); ?></span>
                      </div>
                    </div>
                    <div class="panel-body">
                  	  <input type="hidden" name="account_group_id" value="<?php if(isset($account_group)){ echo $account_group->id;}?>">
                      <button class="btn btn-primary" type="submit">Submit</button>
                      <a href="<?php echo base_url('account_group'); ?>" class="btn btn-default" type="button">Cancel</a>
                    </div>
                  </form>
                </div>
              </div>
        <!-- page end-->
            </div>
          </div>
        </div>
      </div>
        </section>
        <!--body wrapper end-->
  </div>
<?php
  $this->load->view('layout/footer');
?>