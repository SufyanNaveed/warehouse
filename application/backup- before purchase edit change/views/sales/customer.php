<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4><!-- <?php echo $this->lang->line('product_hsn_sac_lookup'); ?> -->
          Add New Customer
        </h4>
      </div>
      <div class="modal-body">
        <div class="control-group">                     
          <div class="controls">
            <div class="tabbable">
              <div class="box-body">
                <div class="row">
                  <form>

                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="email"> Email 
                             <?php //echo $this->lang->line('add_customer_name'); ?>
                           <span class="validation-color">*</span>
                          </label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
                        <span class="validation-color" id="err_email"><?php echo form_error('email'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="customer_first_name"> First Name 
                             <?php //echo $this->lang->line('add_customer_name'); ?>
                           <span class="validation-color">*</span>
                          </label>
                        <input type="text" class="form-control" id="customer_first_name" name="customer_first_name" value="<?php echo set_value('customer_first_name'); ?>">
                        <span class="validation-color" id="err_customer_first_name"><?php echo form_error('customer_first_name'); ?></span>
                    </div>
                    <div class="form-group">
                        <label for="customer_last_name">Last Name
                             <?php // echo $this->lang->line('add_customer_name'); ?>
                           <span class="validation-color">*</span>
                          </label>
                        <input type="text" class="form-control" id="customer_last_name" name="customer_last_name" value="<?php echo set_value('customer_last_name'); ?>">
                        <span class="validation-color" id="err_customer_last_name"><?php echo form_error('customer_last_name'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="company">Company</label>
                      <input type="text" class="form-control" id="company" name="company" value="<?php echo set_value('company'); ?>">
                      <span class="validation-color" id="err_company"><?php echo form_error('company'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="gstid">GSTIN</label>
                      <input type="text" class="form-control" id="gstid" name="gstid" value="<?php echo set_value('gstid'); ?>">
                      <span class="validation-color" id="err_gstid"><?php echo form_error('gstid'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="birth_date">Birth Date</label>
                      <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" value="<?php echo set_value('birth_date'); ?>">
                      <span class="validation-color" id="err_birth_date"><?php echo form_error('birth_date'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="mobile"><!-- Mobile --> 
                          <?php echo $this->lang->line('add_biller_mobile'); ?> 
                      </label>
                      <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo set_value('mobile'); ?>" required>
                      <span class="validation-color" id="err_mobile"><?php echo form_error('mobile'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <?php
                        $country=$this->db->get('countries')->result();
                    ?>
                    <div class="form-group">
                      <label for="country"><!-- Country --> 
                        <?php echo $this->lang->line('biller_lable_country'); ?>
                        <span class="validation-color">*</span>
                      </label>
                      <select class="form-control select2" id="country1" name="country1" style="width: 100%;">
                        <option value=""><?php echo $this->lang->line('add_biller_select'); ?></option>
                        <?php
                          foreach ($country as  $key) {
                        ?>
                        <option value='<?php echo $key->id ?>' <?php if($key->id == 101){ echo "selected";}?>>
                            <?php echo $key->name; ?>
                        </option>
                        <?php
                          }
                        ?>
                      </select>
                      <span class="validation-color" id="err_country1"><?php echo form_error('country'); ?></span>
                    </div>
                      <?php
                          /*$country=$this->db->get('states')->where('s.country_id')->result();*/
                          $country= $this->db->select('s.*')
                          ->from('states s')
                          ->join('countries c','c.id = s.country_id')
                          ->where('s.country_id',101)
                          ->get()
                          ->result();
                      ?>
                    <div class="form-group">
                      <label for="state"><!-- State --> 
                        <?php echo $this->lang->line('add_biller_state'); ?> 
                        <span class="validation-color">*</span>
                      </label>
                      <select class="form-control select2" id="state1" name="state1" style="width: 100%;">
                        <option value=""><!-- Select -->
                          <?php echo $this->lang->line('add_biller_select'); ?>
                        </option>
                            <?php
                              foreach ($country as  $key) {
                            ?>
                            <option value='<?php echo $key->id ?>' <?php if($key->id == 101){ echo "selected";}?>>
                                <?php echo $key->name; ?>
                            </option>
                            <?php
                              }
                            ?>
                      </select>
                      <span class="validation-color" id="err_state1"><?php echo form_error('state'); ?></span>
                    </div>
                    <?php
                        $this->db->select('c.*')
                           ->from('cities c')
                           ->join('states s','s.id = c.state_id')
                           ->where('c.state_id',12)
                           ->get()
                           ->result();
                      ?>
                    <div class="form-group">
                      <label for="city"><!-- City --> 
                        <?php echo $this->lang->line('biller_lable_city'); ?> 
                        <span class="validation-color">*</span>
                      </label>
                      <select class="form-control select2" id="city1" name="city1" style="width: 100%;">
                        <option value=""><!-- Select -->
                          <?php echo $this->lang->line('add_biller_select'); ?>
                        </option>
                      </select>
                      <span class="validation-color" id="err_city1"><?php echo form_error('city'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="address1">
                          <?php echo $this->lang->line('add_biller_address'); ?> 
                          <span class="validation-color">*</span>
                      </label>
                      <textarea class="form-control" id="address1" rows="4" name="address1"><?php echo set_value('address'); ?></textarea>
                      <span class="validation-color" id="err_address1"><?php echo form_error('address1'); ?></span>
                    </div>
                    <div class="form-group">
                      <label for="cust_postal_code">Postal Code 
                          <?php echo $this->lang->line('add_biller_postal_code'); ?> 
                          <span class="validation-color">*</span>
                      </label>
                      <input type="text" class="form-control" id="cust_postal_code" name="cust_postal_code" value="<?php echo set_value('cust_postal_code'); ?>">
                      <span class="validation-color" id="err_cust_postal_code"><?php echo form_error('cust_postal_code'); ?></span>
                    </div>

                    
                  </div>
                
                <div class="col-sm-12">
                  <div class="box-footer">
                    <input type="hidden" name="role_id" id="role_id" value="<?=$role_id?>">
                    <button type="submit" id="btn_submit" class="btn btn-info" class="close"  data-dismiss="modal">&nbsp;&nbsp;&nbsp;<!-- Add -->
                        <?php echo $this->lang->line('add_user_btn'); ?>&nbsp;&nbsp;&nbsp;</button>
                    <!-- <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('customer')">
                      <?php echo $this->lang->line('add_user_btn_cancel'); ?></span> -->
                  </div>
                </div>
              </form>
              </div>
          <!-- /.box-body -->
              </div>
                <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('product_close'); ?></button>
                </div> -->
            </div>
          </div>
        </div> <!-- /controls -->       
      </div> <!-- /control-group --> 
    </div>
  </div>
</div>

<script>
    $('#country1').change(function(){
      var id = $(this).val();
      $('#state1').html('<option value="">Select</option>');
      $('#city1').html('<option value="">Select</option>');
      $.ajax({
          url: "<?php echo base_url('customer/getState') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('#state1').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
          }
        });
    });
</script>
<script>
    $('#state1').change(function(){
      var id = $(this).val();
      $('#city1').html('<option value="">Select</option>');
      $.ajax({
          url: "<?php echo base_url('customer/getCity') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('#city1').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
          }
        });
    });
</script>
<script>
  $(document).ready(function(){
    $("#btn_submit").click(function(event){
      var name_regex    = /^[a-zA-Z\s]+$/;
      var mobile_regex  = /^[6-9][0-9]{9}$/; 
      var customer_email= $('#email').val();
      var first_name    = $('#customer_first_name').val();
      var last_name     = $('#customer_last_name').val();
      var address       = $('#address1').val();
      var city          = $('#city1').val();
      var state         = $('#state1').val();
      var country       = $('#country1').val();
      var mobile        = $('#mobile').val();
      var postal_code   = $('#cust_postal_code').val();
      var birth_date    = $('#birth_date').val();


      if(customer_first_name==null || customer_first_name==""){
        $("#err_customer_first_name").text("Please Enter Customer Name.");
        return false;
      }
      else{
        $("#err_customer_first_name").text("");
      }

      if(customer_last_name==null || customer_last_name==""){
        $("#err_customer_last_name").text("Please Enter Email.");
        return false;
      }
      else{
        $("#err_customer_last_name").text("");
      }

      if(customer_email==null || customer_email==""){
        $("#err_customer_email").text("Please Enter Email.");
        return false;
      }
      else{
        $("#err_customer_email").text("");
      }
      // if (!customer_name.match(name_regex) ) {
      //   $('#err_customer_name').text(" Please Enter Valid Customer Name ");   
      //   return false;
      // }
      // else{
      //   $("#err_customer_name").text("");
      // }

      if(address==null || address==""){
        $("#err_address1").text(" Please Enter Address");
        return false;
      }
      else{
        $("#err_address1").text("");
      }

      if(country==null || country==""){
        $("#err_country1").text("Please Select Country ");
        return false;
      }
      else{
        $("#err_country1").text("");
      }
//country validation complite.
      
      if(state==null || state==""){
        $("#err_state1").text("Please Select State ");
        return false;
      }
      else{
        $("#err_state1").text("");
      }
//state validation complite.
        
      if(city==null || city==""){
        $("#err_city1").text("Please Select City ");
        return false;
      }
      else{
        $("#err_city1").text("");
      }
//city validation complite.
        
      if(mobile==null || mobile==""){
        $("#err_mobile").text("Please Enter Mobile.");
        return false;
      }
      else{
        $("#err_mobile").text("");
      }

      if(birth_date==null || birth_date==""){
        $("#err_birth_date").text("Please Enter Birth Date.");
        return false;
      }
      else{
        $("#err_birth_date").text("");
      }

      if(postal_code==null || postal_code==""){
        $("#err_custom_postal_code").text("Please Enter Postal Code.");
        return false;
      }
      else{
        $("#err_custom_postal_code").text("");
      }

      $.ajax({
        url:"<?php echo base_url('user/add_user_ajax') ?>",
        dataType : 'JSON',
        method : 'POST',
        data:{
            'first_name':$('#customer_first_name').val(),
            'last_name':$('#customer_last_name').val(),
            'company':$('#company').val(),
            'gstid':$('#gstid').val(),
            'address':$("#address1").val(),
            'country_id':$("#country1").val(),
            'state_id':$("#state1").val(),
            'city_id':$("#city1").val(),
            'birth_date':$("#birth_date").val(),
            'postal_code':$("#cust_postal_code").val(),
            'phone':$('#mobile').val(),            
            'role_id':$('#role_id').val(),
            'email':$('#email').val()
        },
        success : function(result){
          var customer_details = result['customer_details'];
          var data = result['data'];
          $('#country').text('');
          $('#state').text('');
          $('#city').text('');
          $('#err_country').text('');
          $('#err_state').text('');
          $('#err_city').text('');

          for(a=0;a<result['country'].length;a++){
            $('#country').append('<option value="' + result['country'][a].id + '">' + result['country'][a].name+'</option>');
          }
          for(a=0;a<result['state'].length;a++){
            $('#state').append('<option value="' + result['state'][a].id + '">' + result['state'][a].name+'</option>');
          }
          for(a=0;a<result['city'].length;a++){
            $('#city').append('<option value="' + result['city'][a].id + '">' + result['city'][a].name+'</option>');
          }

          $('#customer').html('');
          $('#customer').append('<option value="">Select</option>');
          for(i=0;i<data.length;i++){
            $('#customer').append('<option value="' + data[i].id + '">' + data[i].first_name+" "+data[i].last_name + '</option>');
          }
          $('#customer').val(result['id']).attr("selected","selected");

          $('#shipping_country_id').val(customer_details.country_id);

          $('#country').val(customer_details.country_id).attr("selected","selected");
          $('#state').val(customer_details.state_id).attr("selected","selected");
          $('#city').val(customer_details.city_id).attr("selected","selected");
          $('#address').val(customer_details.address);
          $('#postal_code').val(customer_details.postal_code);

          $('#billing_address').text(customer_details.address);
          $('#billing_city').text(customer_details.city_name);
          $('#billing_state').text(customer_details.state_name);
          $('#billing_country').text(customer_details.country_name);
          $('#billing_postal_code').text(customer_details.postal_code);

          $('#shipping-address').text(customer_details.address);
          $('#shipping-city').text(customer_details.city_name);
          $('#shipping-state').text(customer_details.state_name);
          $('#shipping-country').text(customer_details.country_name);
          $('#shipping-postal_code').text(customer_details.postal_code);

          
          $('#shipping_state_id').val(customer_details.state_id);

          $('.billing').show();
          $('.product_listing').show();
          
          $('#customer_name').val('');
          $('#gstin').val('');
          $("#address1").val('');
          $("#country1").val('');
          $("#state1").val('');
          $("#city1").val('');
          $('#mobile').val('');
          

        }
      });
    });

    $("#customer_first_name").on("blur keyup",  function (event){
        var name_regex = /^[a-zA-Z\s]+$/;
        var customer_first_name = $('#customer_first_name').val();
        if(customer_first_name==null || customer_first_name==""){
          $("#err_customer_first_name").text("Please Enter First Name.");
          return false;
        }
        else{
          $("#err_customer_first_name").text("");
        }
    });

    $("#customer_last_name").on("blur keyup",  function (event){
        var name_regex = /^[a-zA-Z\s]+$/;
        var customer_last_name = $('#customer_last_name').val();
        if(customer_last_name==null || customer_last_name==""){
          $("#err_customer_last_name").text("Please Enter Last Name.");
          return false;
        }
        else{
          $("#err_customer_last_name").text("");
        }
    });
    
    $("#address1").on("blur keyup",  function (event){
      var address = $('#address1').val();
      if(address==null || address==""){
        $("#err_address1").text(" Please Enter Address");
        return false;
      }
      else{
        $("#err_address1").text("");
      } 
    });

    $("#city1").change(function(event){
        var city = $('#city1').val();
        if(city==null || city==""){
          $("#err_city1").text("Please Select City ");
          return false;
        }
        else{
          $("#err_city1").text("");
        }
    });
    
    $("#state1").change(function(event){
        var state = $('#state1').val();
        if(state==null || state==""){
          $("#err_state1").text("Please Select State ");
          return false;
        }
        else{
          $("#err_state1").text("");
        }
    });
    
    $("#country1").change(function(event){
        var country = $('#country1').val();
        if(country==null || country==""){
          $("#err_country1").text("Please Select Country");
          return false;
        }
        else{
          $("#err_country1").text("");
        }
    });
    
    $("#mobile").on("blur keyup",  function (event){
        var mobile_regex = /^[6-9][0-9]{9}$/;
        var mobile = $('#mobile').val();
        $('#mobile').val(mobile);
        if(mobile==null || mobile==""){
          $("#err_mobile").text("Please Enter Mobile.");
          return false;
        }
        else{
          $("#err_mobile").text("");
        }
        // if (!mobile.match(mobile_regex) ) {
        //   $('#err_mobile').text(" Please Enter Valid Mobile ");   
        //   return false;
        // }
        // else{
        //   $("#err_mobile").text("");
        // }
    });  
    
    $("#cust_postal_code").on("blur keyup",  function (event){
        var postal_code = $('#cust_postal_code').val();
        $('#cust_postal_code').val(postal_code);
        if(postal_code==null || postal_code==""){
          $("#err_cust_postal_code").text("Please Enter Postal Code.");
          return false;
        }
        else{
          $("#err_cust_postal_code").text("");
        }
    });  
}); 
</script>