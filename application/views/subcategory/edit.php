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
          <li><a href="<?php echo base_url('category'); ?>"> Subcategory </a></li>
          <li class="active"><!-- Edit Subcategory --> <?php echo $this->lang->line('subcategory_lable_edit'); ?></li>
        </ol>
      </h5>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
      <!-- right column -->
      <?php $this->load->view('suggestion.php'); ?>
        <?php
          $this->load->view('layout/product_sidebar');
        ?>
        <div class="col-sm-9">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><!-- Edit Subcategory --> <?php echo $this->lang->line('subcategory_lable_edit'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-sm-6">
                <form role="form" id="form" method="post" action="<?php echo base_url('subcategory/editSubcategory');?>">
                  <?php foreach($data as $row){?>
                    <div class="form-group">
                      <label for="category"><!-- Select Category --> <?php echo $this->lang->line('product_select'); ?> <span class="validation-color">*</span></label>
                      <select class="form-control select2" id="category" name="category" style="width: 100%;">
                        <option value="">Select</option>
                        <?php
                          foreach ($category as  $key) {
                        ?>
                          <option value='<?php echo $key->category_id ?>' <?php if($key->category_id == $row->category_id){echo "selected";} ?>><?php echo $key->category_name ?></option>
                        <?php
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_category"><?php echo form_error('category'); ?></span>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label for="subcategory_name"><!-- Subcategory Name --> <?php echo $this->lang->line('product_name'); ?> <span class="validation-color">*</span></label>
                      <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" value="<?php echo $row->sub_category_name; ?>">
                      <input type="hidden" name="id" value="<?php echo $row->sub_category_id;?>">
                      <span class="validation-color" id="err_subcategory_name"><?php echo form_error('subcategory_name'); ?></span>
                    </div>
                  <div class="col-sm-12">
                  <div class="box-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" id="submit" class="btn btn-info"><!-- Update --> <?php echo $this->lang->line('subcategory_update'); ?></button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('subcategory')"><!-- Cancel --> <?php echo $this->lang->line('subcategory_cancel'); ?></span>
                  </div>
                </div>
                <?php } ?>
              </form>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><!-- Edit Subcategory --> <?php echo $this->lang->line('subcategory_lable_edit'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="col-sm-6">
                <form role="form" id="form" method="post" action="<?php echo base_url('subcategory/editSubcategory');?>">
                  <?php foreach($data as $row){?>
                    <div class="form-group">
                      <label for="category"><!-- Select Category --> <?php echo $this->lang->line('product_select'); ?> <span class="validation-color">*</span></label>
                      <select class="form-control select2" id="category" name="category" style="width: 100%;">
                        <option value="">Select</option>
                        <?php
                          foreach ($category as  $key) {
                        ?>
                          <option value='<?php echo $key->category_id ?>' <?php if($key->category_id == $row->category_id){echo "selected";} ?>><?php echo $key->category_name ?></option>
                        <?php
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_category"><?php echo form_error('category'); ?></span>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                      <label for="subcategory_name"><!-- Subcategory Name --> <?php echo $this->lang->line('product_name'); ?> <span class="validation-color">*</span></label>
                      <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" value="<?php echo $row->sub_category_name; ?>">
                      <input type="hidden" name="id" value="<?php echo $row->sub_category_id;?>">
                      <span class="validation-color" id="err_subcategory_name"><?php echo form_error('subcategory_name'); ?></span>
                    </div>
                  <div class="col-sm-12">
                  <div class="box-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" id="submit" class="btn btn-info"><!-- Update --> <?php echo $this->lang->line('subcategory_update'); ?></button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('subcategory')"><!-- Cancel --> <?php echo $this->lang->line('subcategory_cancel'); ?></span>
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
    var subcategory_name_empty = "Please Enter Category.";
    var subcategory_name_invalid = "Please Enter Valid Subategory Name";
    var subcategory_name_length = "Please Enter Subcategory Name Minimun 3 Character";
    var category_select = "Please Select Category."
    $("#submit").click(function(event){
      var name_regex = /^[-a-zA-Z\s]+$/;
      var subcategory_name = $('#subcategory_name').val().trim();
      var category = $('#category').val();

      if(category == "" || category == null){
        $('#err_category').text(category_select);
      }
      else{
        $('#err_category').text("");
      }
//select category validation copmlite.

      $('#subcategory_name').val(subcategory_name);
      if(subcategory_name==null || subcategory_name==""){
        $("#err_subcategory_name").text(subcategory_name_empty);
        return false;
      }
      else{
        $("#err_subcategory_name").text("");
      }
      if (!subcategory_name.match(name_regex) ) {
        $('#err_subcategory_name').text(subcategory_name_invalid);   
        return false;
      }
      else{
        $("#err_subcategory_name").text("");
      }
      if (subcategory_name.length < 3) {
        $('#err_subcategory_name').text(subcategory_name_length);  
        return false;
      }
      else{
        $("#err_subcategory_name").text("");
      }
//subcategory name validation complite.
    });

    $("#subcategory_name").on("blur keyup",  function (event){
      var name_regex = /^[-a-zA-Z\s]+$/;
      var subcategory_name = $('#subcategory_name').val();
      if(subcategory_name==null || subcategory_name==""){
        $("#err_subcategory_name").text(subcategory_name_empty);
        return false;
      }
      else{
        $("#err_subcategory_name").text("");
      }
      if (!subcategory_name.match(name_regex) ) {
        $('#err_subcategory_name').text(subcategory_name_invalid);   
        return false;
      }
      else{
        $("#err_subcategory_name").text("");
      }
      if (subcategory_name.length < 3) {
        $('#err_subcategory_name').text(subcategory_name_length);  
        return false;
      }
      else{
        $("#err_subcategory_name").text("");
      }
    });
    $('#category').change(function(){
      var category = $('#category').val();
      if(category == "" || category == null){
        $('#err_category').text(category_select);
      }
      else{
        $('#err_category').text("");
      }
    });
}); 
</script>