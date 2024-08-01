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
                <?php echo $this->lang->line('header_dashboard'); ?></a>
          </li>
          <li><a href="<?php echo base_url('biller'); ?>">
              <!-- Supplier -->
              <?php echo $this->lang->line('supplier_header'); ?>
          </a></li>
          <li class="active"><!-- Add Supplier -->
            <?php echo $this->lang->line('customer_edit'); ?>
          </li>
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
              <h3 class="box-title"><!-- Add New Supplier -->
                <?php echo $this->lang->line('edit_customer_header'); ?>

              </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <form role="form" id="form" method="post" action="<?php echo base_url('customer/edit');?>">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="supplier_name">
                           <?php echo $this->lang->line('add_customer_cname'); ?>
                         <span class="validation-color">*</span>
                      </label>
                      <div class="row">
                        <div class="col-md-6">
                          <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo set_value('first_name',$data->first_name); ?>">    
                          <span class="validation-color" id="err_first_name"><?php echo form_error('first_name'); ?></span>
                        </div>
                        <div class="col-md-6">
                          <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo set_value('last_name',$data->last_name); ?>">
                          <span class="validation-color" id="err_last_name"><?php echo form_error('last_name'); ?></span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="company">
                          <?php echo $this->lang->line('add_customer_compname'); ?> 
                      </label>
                      <input type="text" class="form-control" id="company" name="company" value="<?php echo set_value('company',$data->company); ?>">
                      <span class="validation-color" id="err_company"><?php echo form_error('company'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="gstregtype">Gst Registration Type
                            <!-- <?php echo $this->lang->line('biller_lable_country'); ?> --> <span class="validation-color">*</span>
                          </label>
                        <select class="form-control select2" id="gstregtype" name="gstregtype" style="width: 100%;">
                          <option value="">
                            <!-- Select -->
                            <?php echo $this->lang->line('add_biller_select'); ?>    
                          </option>
                            <option 
                              <?php
                                if(isset($gstregtype)){
                                  if($gstregtype == "Registered"){
                                    echo ' selected';
                                  }
                                }
                                if($data->gst_registration_type == "Registered"){
                                  echo ' selected';
                                }
                              ?>
                              >Registered</option>
                            <option
                              <?php
                                if(isset($gstregtype)){
                                  if($gstregtype == "Unregistered"){
                                    echo ' selected';
                                  }
                                }
                                if($data->gst_registration_type == "Unregistered"){
                                  echo ' selected';
                                }
                              ?>
                              >Unregistered</option>
                            <option
                              <?php
                                if(isset($gstregtype)){
                                  if($gstregtype == "Composition Scheme"){
                                    echo ' selected';
                                  }
                                }
                                if($data->gst_registration_type == "Composition Scheme"){
                                  echo ' selected';
                                }
                              ?>
                              >Composition Scheme</option>
                            <option
                              <?php
                                if(isset($gstregtype)){
                                  if($gstregtype == "Input Service Distributor"){
                                    echo ' selected';
                                  }
                                }
                                if($data->gst_registration_type == "Input Service Distributor"){
                                  echo ' selected';
                                }
                              ?>
                              >Input Service Distributor</option>
                            <option
                              <?php
                                if(isset($gstregtype)){
                                  if($gstregtype == "E-Commerece Operator"){
                                    echo ' selected';
                                  }
                                }
                                if($data->gst_registration_type == "E-Commerece Operator"){
                                  echo ' selected';
                                }
                              ?>
                              >E-Commerece Operator</option>
                        </select>
                        <span class="validation-color" id="err_gstregtype"><?php echo form_error('gstregtype'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="gstid"><!-- GSTIN --> 
                          <?php echo $this->lang->line('add_biller_gst'); ?> 
                      </label>
                      <input type="text" class="form-control" id="gstid" name="gstid" value="<?php echo set_value('gstid',$data->gstid); ?>">
                      <span style="font-size: 14px; color:blue">Ex. 24XXXXXXXXXX2Z2</span>
                    </div>
                    
                    <div class="form-group">
                      <label for="phone"><!-- Mobile --> 
                          <?php echo $this->lang->line('add_supplier_phone'); ?> 
                          <span class="validation-color">*</span>
                      </label>
                      <input type="text" class="form-control" id="phone" name="phone" value="<?php echo set_value('phone',$data->phone); ?>">
                      <span class="validation-color" id="err_phone"><?php echo form_error('phone'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="email"><!-- email --> 
                           <?php echo $this->lang->line('biller_lable_email'); ?> 
                           <span class="validation-color">*</span>
                      </label>
                      <input type="text" class="form-control" id="email" name="email" value="<?php echo set_value('email',$data->email); ?>">
                      <span class="validation-color" id="err_email"><?php echo form_error('email'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="country_id"><!-- Country --> 
                          <?php echo $this->lang->line('biller_lable_country'); ?> <span class="validation-color">*</span>
                      </label>
                      <select class="form-control select2" id="country_id" name="country_id" style="width: 100%;">
                        <option value="">
                            <!-- Select -->
                          <?php echo $this->lang->line('add_biller_select'); ?> 
                      </option>
                        <?php
                          foreach ($country as  $key) {
                        ?>
                        <option 
                          value='<?php echo $key->id ?>' 
                          <?php 
                            if(isset($data->country_id)){
                              if($key->id == $data->country_id){
                                echo " selected";
                              }
                            } 

                            if($data->country_id == $key->id)
                              echo ' selected';
                          ?>
                        >
                        <?php echo $key->name; ?>
                        </option>
                        <?php
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_country_id"><?php echo form_error('country_id'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="state_id">
                          <?php echo $this->lang->line('add_biller_state'); ?> 
                          <span class="validation-color">*</span>
                      </label>
                      <select class="form-control select2" id="state_id" name="state_id" style="width: 100%;">
                        <option value=""><?php echo $this->lang->line('add_biller_select'); ?></option>
                        <?php
                            foreach ($state as  $key) {
                        ?>
                        <option value='<?php echo $key->id ?>' 
                            <?php 
                              if(isset($state_id)){
                                if($key->id == $state_id){
                                  echo "selected";
                                }
                              }
                              if($data->state_id == $key->id){
                                echo ' selected';
                              }
                            ?>
                        >
                        <?php echo $key->name; ?>
                        </option>
                        <?php
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_state_id"><?php echo form_error('state_id'); ?></span>
                    </div>
                    
                    <div class="form-group">
                      <label for="city_id">
                          <?php echo $this->lang->line('biller_lable_city'); ?> 
                          <span class="validation-color">*</span>
                      </label>
                      <select class="form-control select2" id="city_id" name="city_id" style="width: 100%;">
                        <option value=""><?php echo $this->lang->line('add_biller_select'); ?></option>
                        <?php
                            foreach ($city as  $key) {
                        ?>
                        <option value='<?php echo $key->id ?>' 
                            <?php 
                              if(isset($city_id)){
                                if($key->id == $city_id){
                                  echo "selected";
                                }
                              }
                              if($data->city_id == $key->id){
                                echo ' selected';
                              }
                            ?>
                        >
                        <?php echo $key->name; ?>
                        </option>
                        <?php
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_city_id"><?php echo form_error('city_id'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="address"><!-- Address --> 
                          <?php echo $this->lang->line('add_biller_address'); ?> 
                          <span class="validation-color">*</span>
                      </label>
                      <textarea class="form-control" id="address" rows="4" name="address"><?php echo set_value('address',$data->address); ?></textarea>
                      <span class="validation-color" id="err_address"><?php echo form_error('address'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="postal_code"><!-- Postal Code -->
                          <?php echo $this->lang->line('add_customer_code'); ?> 
                            <span class="validation-color">*</span>
                      </label>
                      <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo set_value('postal_code',$data->postal_code); ?>">
                      <span class="validation-color" id="err_postal_code"><?php echo form_error('postal_code'); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="box-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <input type="hidden" id="role_id" name="role_id" value="21">
                    <input type="hidden" name="user_id" id="user_id" value="<?=$data->id?>"> 
                    
                    <button type="submit" id="submit" class="btn btn-info">&nbsp;&nbsp;&nbsp;
                      <!-- Add -->
                        <?php echo $this->lang->line('add_user_btn'); ?>&nbsp;&nbsp;&nbsp;</button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('customer')"><!-- Cancel -->
                      <?php echo $this->lang->line('add_user_btn_cancel'); ?></span>
                  </div>
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
<script>
    $('#country_id').change(function(){
      var id = $(this).val();
      $('#state_id').html('<option value="">Select</option>');
      $('#state_code').val('');
      $('#city').html('<option value="">Select</option>');
      $.ajax({
          url: "<?php echo base_url('supplier/getState') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('#state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
          }
        });
    });
</script>
<script>
    $('#state_id').change(function(){
      var id = $(this).val();
      var country = $('#country_id').val();
      $('#city_id').html('<option value="">Select</option>');
      $('#state_code').val('');
      $.ajax({
          url: "<?php echo base_url('supplier/getCity') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
          }
        });
      $.ajax({
          url: "<?php echo base_url('biller/getStateCode') ?>/"+id+'/'+country,
          type: "GET",
          dataType: "TEXT",
          success: function(data){
            $('#state_code').val(data);
          }
        });
    });
</script>
<script>
  $(document).ready(function(){
    $("#submit").click(function(event){
      var name_regex    = /^[a-zA-Z\s]+$/;
      var sname_regex   = /^[-a-zA-Z\s]+$/;
      var phone_regex   = /^[6-9][0-9]{9}$/; 
      var postal_regex  = /^[1-9][0-9]{5}$/
      //indian phone number  /^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1}){0,1}98(\s){0,1}(\-){0,1}(\s){0,1}[1-9]{1}[0-9]{7}$/
      var email_regex   = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      var first_name    = $('#first_name').val();
      var last_name     = $('#last_name').val();
      var company       = $('#company').val();
      var address       = $('#address').val();
      var country       = $('#country_id').val();
      var state         = $('#state_id').val();
      var city          = $('#city_id').val();
      var postal_code   = $('#postal_code').val();
      var phone         = $('#phone').val();
      var email         = $('#email').val();
      var gstregtype         = $('#gstregtype').val();




        if(first_name==null || first_name==""){
          $("#err_first_name").text("Please Enter First Name");
          return false;
        }
        else{
          $("#err_first_name").text("");
        }
        // if (!first_name.match(name_regex) ) {
        //   $('#err_first_name').text(" Please Enter Valid First Name ");   
        //   return false;
        // }
        // else{
        //   $("#err_first_name").text("");
        // }

        if(last_name==null || last_name==""){
          $("#err_last_name").text("Please Enter Last Name");
          return false;
        }
        else{
          $("#err_last_name").text("");
        }
        // if (!last_name.match(name_regex) ) {
        //   $('#err_last_name').text(" Please Enter Valid Last Name ");   
        //   return false;
        // }
        // else{
        //   $("#err_last_name").text("");
        // }
        
        //customer name validation complite.

        if(company==null || company==""){
          $("#err_company").text("Please Enter Company Name.");
          return false;
        }
        else{
          $("#err_company").text("");
        }
        // if (!company.match(sname_regex) ) {
        //   $('#err_company').text(" Please Enter Valid Company Name ");   
        //   return false;
        // }
        // else{
        //   $("#err_company").text("");
        // }
//company name validation complite.

        if(address==null || address==""){
          $("#err_address").text(" Please Enter Address");
          return false;
        }
        else{
          $("#err_address").text("");
        }

        if(gstregtype==null || gstregtype==""){
          $("#err_gstregtype").text("Please select the GST Registration type");
          return false;
        }
        else{
          $("#err_gstregtype").text("");
        }
//Address validation complite.
        
        if(country==null || country==""){
          $("#err_country").text("Please Select Country ");
          return false;
        }
        else{
          $("#err_country").text("");
        }
//country validation complite.
      
        if(state==null || state==""){
          $("#err_state").text("Please Select State ");
          return false;
        }
        else{
          $("#err_state").text("");
        }
//state validation complite.
        
         if(city==null || city==""){
          $("#err_city").text("Please Select City ");
          return false;
        }
        else{
          $("#err_city").text("");
        }
//city validation complite.

        if(postal_code==null || postal_code==""){
          $("#err_postal_code").text("Please Enter Postal Code.");
          return false;
        }
        else{
          $("#err_postal_code").text("");
        }
        if (!postal_code.match(postal_regex) ) {
          $('#err_postal_code').text(" Please Enter Valid Postal Code ");   
          return false;
        }
        else{
          $("#err_postal_code").text("");
        }
//postal code validation complite.
        
        if(phone==null || phone==""){
          $("#err_phone").text("Please Enter Mobile.");
          return false;
        }
        else{
          $("#err_phone").text("");
        }
        // if (!phone.match(phone_regex) ) {
        //   $('#err_phone').text(" Please Enter Valid Mobile ");   
        //   return false;
        // }
        // else{
        //   $("#err_phone").text("");
        // }
//phone validation complite.
        
        if(email==null || email==""){
          $("#err_email").text("Please Enter Email.");
          return false;
        }
        else{
          $("#err_email").text("");
        }
        if (!email.match(email_regex) ) {
          $('#err_email').text(" Please Enter Valid Email Address ");   
          return false;
        }
        else{
          $("#err_email").text("");
        }
//email validation complite.

    });

    $("#first_name").on("blur keyup",  function (event){
        var name_regex = /^[a-zA-Z\s]+$/;
        var first_name = $('#first_name').val();

        if(first_name==null || first_name==""){
          $("#err_first_name").text("Please Enter First Name");
          return false;
        }
        else{
          $("#err_first_name").text("");
        }
        // if (!supplier_name.match(name_regex) ) {
        //   $('#err_supplier_name').text(" Please Enter Valid Supplier Name ");   
        //   return false;
        // }
        // else{
        //   $("#err_supplier_name").text("");
        // }
    });
    $("#last_name").on("blur keyup",  function (event){
        // var name_regex = /^[a-zA-Z\s]+$/;
        var last_name = $('#last_name').val();

        if(last_name==null || last_name==""){
          $("#err_last_name").text("Please Enter Last Name");
          return false;
        }
        else{
          $("#err_last_name").text("");
        }
        // if (!supplier_name.match(name_regex) ) {
        //   $('#err_supplier_name').text(" Please Enter Valid Supplier Name ");   
        //   return false;
        // }
        // else{
        //   $("#err_supplier_name").text("");
        // }
    });
    $("#company").on("blur keyup",  function (event){
        var sname_regex = /^[-a-zA-Z\s]+$/;
        var company = $('#company').val();
      
        if(company==null || company==""){
          $("#err_company").text("Please Enter Company Name.");
          return false;
        }
        else{
          $("#err_company").text("");
        }
        // if (!company_name.match(sname_regex) ) {
        //   $('#err_company_name').text(" Please Enter Valid Company Name ");   
        //   return false;
        // }
        // else{
        //   $("#err_company_name").text("");
        // }
    });
    $("#address").on("blur keyup",  function (event){
        var address = $('#address').val();
        if(address==null || address==""){
          $("#err_address").text(" Please Enter Address");
          return false;
        }
        else{
          $("#err_address").text("");
        }
    });
    $("#city_id").change(function(event){
        var city = $('#city_id').val();
        
        if(city==null || city==""){
          $("#err_city_id").text("Please Select City ");
          return false;
        }
        else{
          $("#err_city_id").text("");
        }
    });
    $("#state_id").change(function(event){
        var state = $('#state_id').val();
        $('#state_id').val(state);
        if(state==null || state==""){
          $("#err_state_id").text("Please Select State ");
          return false;
        }
        else{
          $("#err_state_id").text("");
        }
    });
    $("#postal_code").on("blur keyup",  function (event){
        var postal_regex = /^[1-9][0-9]{5}$/
        var postal_code = $('#postal_code').val();
        $('#postal_code').val(postal_code);
        if(postal_code==null || postal_code==""){
          $("#err_postal_code").text("Please Enter Postal Code.");
          return false;
        }
        else{
          $("#err_postal_code").text("");
        }
        // if (!postal_code.match(postal_regex) ) {
        //   $('#err_postal_code').text(" Please Enter Valid Postal Code ");   
        //   return false;
        // }
        // else{
        //   $("#err_postal_code").text("");
        // }
    });
    $("#country_id").change(function(event){
        var country = $('#country_id').val();
        
        if(country_id==null || country_id==""){
          $("#err_country_id").text("Please Select Country");
          return false;
        }
        else{
          $("#err_country_id").text("");
        }
    });
    $("#gstregtype").change(function(event){
        var gstregtype = $('#gstregtype').val();
        
        if(gstregtype==null || gstregtype==""){
          $("#err_gstregtype").text("Please Select GST Registration Type");
          return false;
        }
        else{
          $("#err_gstregtype").text("");
        }
    });
    $("#phone").on("blur keyup",  function (event){
        var phone_regex = /^[6-9][0-9]{9}$/;
        var phone = $('#phone').val();
        
        if(phone==null || phone==""){
          $("#err_phone").text("Please Enter Mobile.");
          return false;
        }
        else{
          $("#err_phone").text("");
        }
        // if (!phone.match(phone_regex) ) {
        //   $('#err_phone').text(" Please Enter Valid Mobile ");   
        //   return false;
        // }
        // else{
        //   $("#err_phone").text("");
        // }
    });
    $("#email").on("blur keyup",  function (event){
        var email_regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var email = $('#email').val();
        $('#email').val(email);
        if(email==null || email==""){
          $("#err_email").text("Please Enter Email.");
          return false;
        }
        else{
          $("#err_email").text("");
        }
        if (!email.match(email_regex) ) {
          $('#err_email').text(" Please Enter Valid Email Address ");   
          return false;
        }
        else{
          $("#err_email").text("");
        }
    });
   
}); 
</script>