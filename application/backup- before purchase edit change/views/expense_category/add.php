<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('layout/header');
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard --> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('category'); ?>">Expense Category</a></li>
          <li class="active">Add Expense Category</li>
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
              <h3 class="box-title">Add Expense Category</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" id="form" method="post" action="<?php echo base_url('expense_category/addExpenseCategory');?>">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="account_group_id">Account Group<span class="validation-color">*</span></label>
                    <select class="form-control select2" id="account_group_id" name="account_group_id" style="width: 100%;">
                      <option value="">Select</option>
                      <?php
                        foreach ($account_groups as $row) {
                          if($row->category == "Expense"){
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
                    <label for="category_name">Name<span class="validation-color">*</span></label>
                    <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo set_value('category_name'); ?>">
                    <span class="validation-color" id="err_category_name"><?php echo form_error('category_name'); ?></span>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="box-footer">
                    <button type="submit" id="submit" class="btn btn-info">&nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;</button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('expense_category')"><!-- Cancel --><?php echo $this->lang->line('category_cancel'); ?></span>
                  </div>
                </div>
              </form>
              </div>
            </div>
            <!-- /.box-body -->
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
<script>
  $(document).ready(function(){
    var category_name_empty = "Please Enter Name.";
    var category_name_invalid = "Please Enter Valid Name";
    var category_name_length = "Please Enter Name Minimun 3 Character";
    $("#submit").click(function(event){
      var name_regex = /^[-a-zA-Z\s]+$/;
      var category_name = $('#category_name').val().trim();
      $('#category_name').val(category_name);
      if(category_name==null || category_name==""){
        $("#err_category_name").text(category_name_empty);
        return false;
      }
      else{
        $("#err_category_name").text("");
      }
      // if (!category_name.match(name_regex) ) {
      //   $('#err_category_name').text(category_name_invalid);   
      //   return false;
      // }
      // else{
      //   $("#err_category_name").text("");
      // }
      if (category_name.length < 3) {
        $('#err_category_name').text(category_name_length);  
        return false;
      }
      else{
        $("#err_category_name").text("");
      }
//category name validation complite.
    });

    $("#category_name").on("blur keyup",  function (event){
      var name_regex = /^[-a-zA-Z\s]+$/;
      var category_name = $('#category_name').val();
        if(category_name==null || category_name==""){
          $("#err_category_name").text(category_name_empty);
          return false;
        }
        else{
          $("#err_category_name").text("");
        }
        // if (!category_name.match(name_regex)) {
        //   $('#err_category_name').text(category_name_invalid);  
        //   return false;
        // }
        // else{
        //   $("#err_category_name").text("");
        // }
        if (category_name.length < 3) {
          $('#err_category_name').text(category_name_length);  
          return false;
        }
        else{
          $("#err_category_name").text("");
        }
    });
   
}); 
</script>