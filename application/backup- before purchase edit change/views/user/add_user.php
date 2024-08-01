<?php
	$this->load->view('layout/header');
?>

<div class="content-wrapper">
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('user'); ?>"><?php echo "User"; ?></a></li>
          <li class="active"><?php echo "Add User"; //echo $this->lang->line('purchase_add_purchase'); ?></li>
        </ol>
      </h5>    
    </section>

    <!-- Main content -->
    <section class="content">
      	<div class="row">
      		<?php $this->load->view('suggestion.php'); ?>
	        <?php $this->load->view('layout/user_sidebar'); ?>
	        <!-- /.col -->
	        <div class="col-md-9">
	        	<div class="box box-solid">
		            <div class="box-header with-border">
		            	<h3 class="box-title">Add User</h3>
		            </div>
		            <form role="form" action="<?=base_url('user/add_user')?>" method="POST">
		              	<div class="box-body">
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  	<label for="name">Role</label>
					                  	<select class="form-control select2" name="role_id" id="role_id" style="width: 100%;" required>
						                	<option value="">Select</option>
						                	<?php 
						                      	foreach ($roles as $row) {
						                    ?>
							                <option value="<?=$row->id?>"><?=ucfirst($row->name)?></option>
							                <?php
							                	}
							                ?>
					                	</select>
					                	<span class="validation-color" id="err_role_id"><?php echo form_error('role_id'); ?></span>
					                </div>		
			              		</div>
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  	<label for="email">Email</label>
					                  	<input type="text" class="form-control" id="email" name="email" placeholder="Email" value="" required="required">
					                  	<span class="validation-color" id="err_email"><?php echo form_error('email'); ?></span>
					                </div>		                
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="first_name">First Name</label>
					                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="" required>
					                  <span class="validation-color" id="err_first_name"><?php echo form_error('first_name'); ?></span>
					                </div>		                
			              		</div>
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="last_name">Last Name</label>
					                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="" required>
					                  <span class="validation-color" id="err_last_name"><?php echo form_error('last_name'); ?></span>
					                </div>		                
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="password">Password</label>
					                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php if(isset($user->password)) echo $user->password;?>" required>
					                </div>		                
					                <span class="validation-color" id="err_password"><?php echo form_error('password'); ?></span>
			              		</div>
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="password_confirm">Confirm Password</label>
					                  <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm Password" value="" required>
					                  <span class="validation-color" id="err_password_confirm"><?php echo form_error('password_confirm'); ?></span>
					                </div>		                
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="birth_date">Birth date</label>
					                  <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" placeholder="1991-06-28" value="" autocomplete="off"  required>
					                  <span class="validation-color" id="err_birth_date"><?php echo form_error('birth_date'); ?></span>
					                </div>		                
			              		</div>
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="phone">Phone</label>
					                  <input type="text" class="form-control" id="phone" name="phone" placeholder="10 Digit Mobile number" value="" required>
					                  <span class="validation-color" id="err_phone"><?php echo form_error('phone'); ?></span>
					                </div>		                
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  	<label for="country_id">Country</label>
					                  	<select class="form-control select2" id="country_id" name="country_id" style="width: 100%;">
					                        <option value="">Select</option>
					                        <?php
					                          	foreach ($country as  $key) {
					                        ?>
					                        <option value='<?php echo $key->id ?>' 
					                          	<?php 
						                            // if(isset($user->country_id)){
						                            //   if($key->id == $user->country_id){
						                            //     echo "selected";
						                            //   }
						                            // } 
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
			              		</div>
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  	<label for="state_id">State</label>
					                  	<select class="form-control select2" id="state_id" name="state_id" style="width: 100%;">
					                        <option value="">Select</option>
					                    </select>
					                    <span class="validation-color" id="err_state_id"><?php echo form_error('state_id'); ?></span>
					                </div>		                
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-4">
			              			<div class="form-group">
					                  	<label for="birth_date">City</label>
					                  	<select class="form-control select2" id="city_id" name="city_id" style="width: 100%;">
					                        <option value="">Select</option>
					                    </select>
					                    <span class="validation-color" id="err_city_id"><?php echo form_error('city_id'); ?></span>
					                </div>		                
			              		</div>
			              		<div class="col-md-4">
			              			<div class="form-group">
					                  <label for="address">Address</label>
					                  <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="" required>
					                  <span class="validation-color" id="err_address"><?php echo form_error('address'); ?></span>
					                </div>		                
			              		</div>
			              		<div class="col-md-4">
			              			<div class="form-group">
					                  <label for="postal_code">Postal Code</label>
					                  <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Postal Code" value="" required>
					                  <span class="validation-color" id="err_postal_code"><?php echo form_error('postal_code'); ?></span>
					                </div>		                
			              		</div>
			              	</div>
		              	</div>
		              	<div class="box-footer">
			                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
			                <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('user/users')"><?php echo $this->lang->line('branch_label_cancel'); ?></span>
			            </div>
		            </form>
		        </div> 	 
	        </div>
	        <!-- /.col -->
      	</div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

<?php 
	$this->load->view('layout/footer');
?>
<script>
    $('#country_id').change(function(){
      	var id 		= $(this).val();

      	$('#state_id').html('<option value="">Select</option>');
      	$('#city_id').html('<option value="">Select</option>');

      	$.ajax({
          	url: "<?php echo base_url('customer/getState') ?>/"+id,
          	type: "GET",
          	dataType: "JSON",
          	success: function(data){
            	for(i=0;i<data.length;i++){
              		$('#state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            	}
          	}
        });
    });

    $('#state_id').change(function(){
      	var id 			= $(this).val();
      	var country_id 	= $('#country_id').val();

      	$('#city_id').html('<option value="">Select</option>');

      	$.ajax({
          	url: "<?php echo base_url('customer/getCity') ?>/"+id,
          	type: "GET",
          	dataType: "JSON",
          	success: function(data){
	            for(i=0;i<data.length;i++){
              		$('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            	}
          	}
        });
    });
</script>