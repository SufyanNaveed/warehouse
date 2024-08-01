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
          <li><a href="<?php echo base_url('ledger'); ?>">
              Ledger
              <?php echo $this->lang->line('customer_header'); ?></a>
          </li>
          <li class="active">Edit Ledger
              <!-- <?php echo $this->lang->line('add_customer_label'); ?> -->
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
                Edit Ledger
                <!-- <?php echo $this->lang->line('add_customer_header'); ?> -->
              </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="col-sm-6">
              <div class="row">
                <form role="form" id="form" method="post" action="<?php echo base_url('ledger/edit') ?>">
                  <div class="panel-body">
                    
                    <div class="form-group">
                      <label   for="title">Title <span style="color:red;">*</span></label>
                      <input type="text" class="form-control" placeholder="Title" id="title" name="title" value="<?php echo $ledger->title; ?>">
                    </div>
                    <div class="form-group">
                      <label  for="group">Group <span style="color:red;">*</span></label>
                      <select class="form-control select2" id="accountgroup" name="accountgroup" required="required">
                        <option value="">--Select--</option>
                        <?php
                          foreach($accountgroup as $row)
                          {
                        ?>
                            <option value="<?= $row->id; ?>" 
                                      <?php 
                                          if($ledger->accountgroup_id == $row->id) {
                                            echo ' selected'; 
                                          }
                                      ?>
                            ><?= $row->group_title?></option>
                        <?php
                          }
                        ?>
                      </select>
                        
                    </div>
                    <div class="form-group">
                      <label   for="opening_balance">Opening Balance</label>
                      <input type="text" class="form-control" placeholder="Opening Balance" id="opening_balance" name="opening_balance" value="<?php echo $ledger->opening_balance; ?>"> 
                    </div>
                    <div class="form-group">
                      <label   for="closing_balance">Closing Balance</label>
                      <input type="text" class="form-control" placeholder="Closing Balance" id="closing_balance" name="closing_balance" value="<?php echo $ledger->closing_balance; ?>"> 
                    </div>
                    <div class="panel-body">
                      <p>
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="ledger_id" value="<?php if(isset($ledger)) echo $ledger->id; ?>">  
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <a href="<?php echo base_url('ledger'); ?>" class="btn btn-default" type="button">Cancel</a>
                      </p>
                    </div>
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