<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$p = array('admin','manager');
if(!(in_array($this->session->userdata('type'),$p))){
  redirect('auth');
}
$this->load->view('layout/header');
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard --> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('tax'); ?>"><!-- Tax --> <?php echo $this->lang->line('tax_label'); ?></a></li>
          <li class="active"><!-- Add Tax --> <?php echo $this->lang->line('tax_label_add'); ?></li>
        </ol>
      </h5>    
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
      <!-- right column -->
      <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><!-- Add New Tax --> <?php echo $this->lang->line('tax_label_addnew'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" id="form" method="post" action="<?php echo base_url('tax/addTax');?>">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="tax_type">Tax Type</label>
                    <select id="tax_type" name="tax_type" class="form-control select2">
                      <option value="1">GST</option>
                      <option value="2">Non-GST supplies</option>
                      <option value="3">Nill Rated</option>
                      <option value="4">Exempted</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="tax_name"><!-- Tax Name --> <?php echo $this->lang->line('tax_label_name'); ?> <span class="validation-color">*</span></label>
                    <input type="text" class="form-control" id="tax_name" name="tax_name" value="<?php echo set_value('tax_name'); ?>">
                    <span class="validation-color" id="err_tax_name"><?php echo form_error('tax_name'); ?></span>
                  </div>
                  <div class="form-group">
                    <label for="start_from"><!-- Start From -->  <?php echo $this->lang->line('tax_label_form'); ?><span class="validation-color">*</span></label>
                    <input type="text" class="form-control datepicker" id="start_from" name="start_from" value="<?php echo set_value('start_from'); ?>">
                    <span class="validation-color" id="err_start_from"><?php echo form_error('start_from'); ?></span>
                  </div>
                  <div class="form-group">
                    <label for="registration_number"><!-- Registration Number --> <?php echo $this->lang->line('tax_label_rnumber'); ?></label>
                    <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?php echo set_value('registration_number'); ?>">
                    <span class="validation-color" id="err_registration_number"><?php echo form_error('registration_number'); ?></span>
                  </div>
                  <div class="form-group">
                    <label for="frequency"><!-- Filling Frequency -->  <?php echo $this->lang->line('tax_label_frequency'); ?><span class="validation-color">*</span></label>
                    <select class="form-control select2" id="frequency" name="frequency" style="width: 100%;">
                      <option value="Monthly">Monthly</option>
                      <option value="Quarterly">Quarterly</option>
                      <option value="Half-Yearly">Half-Yearly</option>
                      <option value="Yearly">Yearly</option>
                    </select>
                    <span class="validation-color" id="err_frequency"><?php echo form_error('frequency'); ?></span>
                  </div>
                  <div class="form-group">
                    <label for="description"><!-- Description --> <?php echo $this->lang->line('tax_label_desc'); ?></label>
                    <textarea class="form-control" id="description" name="description"><?php echo set_value('description'); ?></textarea>
                    <span class="validation-color" id="err_registration_number"><?php echo form_error('registration_number'); ?></span>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="frequency"><!-- Tax applies to -->  <?php echo $this->lang->line('tax_label_applies'); ?><span class="validation-color">*</span></label>
                      <div class="input-group">
                        <input type="checkbox" name="sales" id="sales" checked> Sales</li>&nbsp;&nbsp;
                        <input type="checkbox" name="purchase" id="purchase" > Purchase</li>
                      </div>
                    <span class="validation-color" id="err_frequency"><?php echo form_error('frequency'); ?></span>
                  </div>
                  <div class="form-group calculate_on">
                    <label for="calculate_on"><!-- Calculate On --> <?php echo $this->lang->line('tax_label_calculate'); ?><span class="validation-color">*</span></label>
                    <div class="input-group">
                      <input class="form-control text-right" type="text" id="calculate_on" name="calculate_on" value="100">
                      <span class="input-group-addon">%</span>
                    </div>
                    <span style="font-size: 12px;">Calculate on n% of base price.</span>
                    <span class="validation-color" id="err_calculate_on"><?php echo form_error('calculate_on'); ?></span>
                  </div>
                  <div class="form-group sales">
                    <label for="tax_value"><!-- Sales Tax Rate --> <?php echo $this->lang->line('tax_label_salesrate'); ?> <span class="validation-color">*</span></label>
                    <div class="input-group">
                      <input type="text" class="form-control text-right" id="tax_value" name="tax_value" value="<?php echo set_value('tax_value'); ?>">
                      <span class="input-group-addon">%</span>
                    </div>
                    <span class="validation-color" id="err_tax_value"><?php echo form_error('tax_value'); ?></span>
                    <div id="s_gst">
                      <br>IGST &nbsp;: <span id="s_igst"></span>
                      <br>CGST : <span id="s_cgst"></span>
                      <br>SGST : <span id="s_sgst"></span>
                    </div>
                  </div>
                  <div class="form-group purchase">
                    <label for="purchase_tax_value"><!-- Purchase Tax Rate --> <?php echo $this->lang->line('tax_label_purchaserate'); ?> <span class="validation-color">*</span></label>
                    <div class="input-group">
                      <input type="text" class="form-control text-right" id="purchase_tax_value" name="purchase_tax_value" value="<?php echo set_value('purchase_tax_value'); ?>">
                      <span class="input-group-addon">%</span>
                    </div>
                    <span class="validation-color" id="err_purchase_tax_value"><?php echo form_error('purchase_tax_value'); ?></span>
                    <div id="p_gst">
                      <br>IGST &nbsp;: <span id="p_igst"></span>
                      <br>CGST : <span id="p_cgst"></span>
                      <br>SGST : <span id="p_sgst"></span>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="box-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" id="submit" class="btn btn-info">&nbsp;&nbsp;&nbsp;<!-- Add --> <?php echo $this->lang->line('discount_label_add'); ?>&nbsp;&nbsp;&nbsp;</button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('tax')"><!-- Cancel --> <?php echo $this->lang->line('discount_label_cancel'); ?></span>
                  </div>
                </div>
              </form>
            </div>
          </div> <!-- /.box-body -->
        </div><!-- /.box -->
      </div><!--/.col (right) -->
  </section> <!-- /.content -->
</div><!-- /.content-wrapper -->
<script>
  $('.purchase').hide();
</script>
<?php
  $this->load->view('layout/footer');
?>
<script>
  $(document).ready(function(){
    $("#submit").click(function(event){
      var name_regex = /^[a-zA-Z0-9\s@%]+$/;
      var tax_regex = /^\$?[0-9]*([0-9]+)?$/;
      var tax_name = $('#tax_name').val();
      var tax_value = $('#tax_value').val();
      
      $('#purchase').is(":checked");
        if(tax_name==null || tax_name==""){
          $("#err_tax_name").text("Please Enter Tax Name.");
          return false;
        }
        else{
          $("#err_tax_name").text("");
        }
        if (!tax_name.match(name_regex) ) {
          $('#err_tax_name').text(" Please Enter Valid Tax Name");   
          return false;
        }
        else{
          $("#err_tax_name").text("");
        }

//tax name validation complite. 
        if($('#sales').is(":checked")){
          if(tax_value==null || tax_value==""){
            $("#err_tax_value").text("Please Enter Sales Tax Value.");
            return false;
          }
          else{
            $("#err_tax_value").text("");
          }
          if (!tax_value.match(tax_regex) ) {
            $('#err_tax_value').text(" Please Enter Valid Sales Tax value (ex : 12) ");   
            return false;
          }
          else{
            $("#err_tax_value").text("");
          }
        }     
        
//sales tax value validation complite.
         if($('#purchase').is(":checked")){
          if(tax_value==null || tax_value==""){
            $("#err_tax_value").text("Please Enter Purchase Tax Value.");
            return false;
          }
          else{
            $("#err_tax_value").text("");
          }
          if (!tax_value.match(tax_regex) ) {
            $('#err_tax_value').text(" Please Enter Valid Purchase Tax value (ex : 12) ");   
            return false;
          }
          else{
            $("#err_tax_value").text("");
          }
        }
        if(!$('#sales').is(":checked") && !$('#purchase').is(":checked")){
          $('#sales').prop("checked", true);
          $('.sales').show();
          $(".calculate_on").show();
          $("#err_tax_value").text("Please Enter Sales Tax Value1.");
          return false;
        } 
        
//purchase tax value validation complite.
        
    });

    $("#tax_name").on("blur keyup",  function (event){
        var name_regex = /^[a-zA-Z0-9\s@%]+$/;
        var tax_name = $('#tax_name').val();
        if(tax_name==null || tax_name==""){
          $("#err_tax_name").text("Please Enter Tax Name.");
          return false;
        }
        else{
          $("#err_tax_name").text("");
        }
        if (!tax_name.match(name_regex) ) {
          $('#err_tax_name').text(" Please Enter Valid Tax Name");   
          return false;
        }
        else{
          $("#err_tax_name").text("");
        }
        //event.preventDefault();
    });

    $("#tax_value").on("blur keyup",  function (event){
       var tax_value = $('#tax_value').val();
       var tax_regex = /^\$?[0-9]*([0-9]+)?$/;
        if(tax_value==null || tax_value==""){
          $("#err_tax_value").text("Please Enter Sales Tax Value.");
          $('#s_gst').hide();
          return false;
        }
        else{
          $("#err_tax_value").text("");
        }
        if (!tax_value.match(tax_regex) ) {
          $('#s_gst').hide();
          $('#err_tax_value').text(" Please Enter Valid Sales Tax value (ex : 12) ");   
          return false;
        }
        else{
          $("#err_tax_value").text("");
        }
        if(tax_value.match(tax_regex)) {
          $('#s_igst').text(tax_value);
          if(tax_value==null || tax_value==""){
            $('s_gst').hide();
          }
          else{
            $('#s_gst').show();
            $('#s_igst').text(tax_value+"%");
            $('#s_cgst').text((tax_value/2)+"%");
            $('#s_sgst').text((tax_value/2)+"%");
          }
          
        }
    });
    $("#purchase_tax_value").on("blur keyup",  function (event){
       var tax_value = $('#purchase_tax_value').val();
       var tax_regex = /^\$?[0-9]*([0-9]+)?$/;
        if(tax_value==null || tax_value==""){
          $("#err_purchase_tax_value").text("Please Enter Purchase Tax Value.");
          $('#p_gst').hide();
          return false;
        }
        else{
          $("#err_purchase_tax_value").text("");
        }
        if (!tax_value.match(tax_regex) ) {
          $('#p_gst').hide();
          $('#err_purchase_tax_value').text(" Please Enter Valid Purchase Tax value (ex : 12) ");   
          return false;
        }
        else{
          $("#err_purchase_tax_value").text("");
        }
        if(tax_value.match(tax_regex)) {
          $('#p_igst').text(tax_value);
          if(tax_value==null || tax_value==""){
            $('p_gst').hide();
          }
          else{
            $('#p_gst').show();
            $('#p_igst').text(tax_value+"%");
            $('#p_cgst').text((tax_value/2)+"%");
            $('#p_sgst').text((tax_value/2)+"%");
          }
          
        }
    });
    $("#sales").on("change",  function (event){
      if($('#sales').is(":checked")){  
        $(".sales").show();
        $(".s_gst").show();
        $(".calculate_on").show();
      }
      else{
        $(".sales").hide();
        if($('#sales').is(":checked") || $('#sales').is(":checked")){  
          $(".calculate_on").show();
        }
        else{
          $(".calculate_on").hide();
        }
      }
      
    });
    $("#purchase").on("change",  function (event){
      if($('#purchase').is(":checked")){  
        $(".purchase").show();
        $(".p_gst").show();
        $(".calculate_on").show();
      }
      else{
        $(".purchase").hide();
        if($('#purchase').is(":checked") || $('#sales').is(":checked")){ 
          $(".calculate_on").show();
        }
        else{
          $(".calculate_on").hide();
        }
      }
      
    });
    $('#tax_type').on('change',function(event){
      var id = $('#tax_type').val();
      if(id==1){
        $('#tax_value').val('');
        $('#tax_value').removeAttr('readonly','readonly');
        $('#purchase_tax_value').val('');
        $('#purchase_tax_value').removeAttr('readonly','readonly');
        $('#calculate_on').removeAttr('readonly','readonly');
        $('#s_igst').text('');
        $('#s_cgst').text('');
        $('#s_sgst').text('');
        $('#p_igst').text('');
        $('#p_cgst').text('');
        $('#p_sgst').text('');
      }
      else{
        $('.purchase').show();
        $('#tax_value').val('0');
        $('#tax_value').attr('readonly','readonly');
        $('#purchase_tax_value').val('0');
        $('#purchase_tax_value').attr('readonly','readonly');
        $('#calculate_on').attr('readonly','readonly'); 
        $('#s_igst').text('0%');
        $('#s_cgst').text('0%');
        $('#s_sgst').text('0%');
        $('#p_igst').text('0%');
        $('#p_cgst').text('0%');
        $('#p_sgst').text('0%');
      }
    });
}); 
</script>