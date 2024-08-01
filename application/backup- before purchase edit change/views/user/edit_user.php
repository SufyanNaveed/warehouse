<?php
	$this->load->view('layout/header');
?>

<div class="content-wrapper">
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('user'); ?>"><?php echo "User"; ?></a></li>
          <li class="active"><?php echo "Edit User"; //echo $this->lang->line('purchase_add_purchase'); ?></li>
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
	        	<?php 
		          	if($this->session->flashdata('success') != ''){ 
		        ?>
		        <div class="alert alert-success message">    
		          	<p><?php echo $this->session->flashdata('success');?></p>
		        </div>
		        <?php
		          	}
		        ?>

		        <?php 
		          if($this->session->flashdata('failure') != ''){ 
		        ?>
		        <div class="alert alert-danger message">    
		          <p><?php echo $this->session->flashdata('failure');?></p>
		        </div>
		        <?php
		          }
		        ?>
	        	<div class="box box-solid">
		            <div class="box-header with-border">
		            	<h3 class="box-title">Edit User</h3>
		            </div>
		            <form role="form" action="<?=base_url('user/edit_user')?>" method="POST">
		              	<div class="box-body">
		              		<div class="row">
						        <div class="col-md-12">
						          <!-- Custom Tabs -->
						          	<div class="nav-tabs-custom">
							            <ul class="nav nav-tabs">
							              	<li class="active"><a href="#personal" data-toggle="tab">Personal</a></li>
							              	<li><a href="#business" data-toggle="tab">Business</a></li>
							              	<li><a href="#password" data-toggle="tab">Change Password</a></li>
							              	<li><a href="#warehouse" data-toggle="tab">Warehouse</a></li>
							            </ul>
							            <div class="tab-content">
							              	<div class="tab-pane active" id="personal">
							              		<div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  	<label for="name">Role</label>
										                  	<select class="form-control select2" name="role_id" id="role_id" style="width: 100%;" required>
											                	<option value="">Select</option>
											                	<?php 
											                      	foreach ($roles as $row) {
											                    ?>
												                <option value="<?=$row->id?>"
												                	<?php 
												                		if($user->group_id == $row->id)
												                			echo "selected";
												                	?>
												                	><?=ucfirst($row->name)?></option>
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
										                  <input type="text" class="form-control" id="email" name="email" placeholder="First Name" value="<?=$user->email?>" readonly required>
										                  <span class="validation-color" id="err_email"><?php echo form_error('email'); ?></span>
										                </div>		                
								              		</div>
								              	</div>
							              		<div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  <label for="first_name">First Name</label>
										                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?=$user->first_name?>" required>
										                  <span class="validation-color" id="err_first_name"><?php echo form_error('first_name'); ?></span>
										                </div>		                
								              		</div>
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  <label for="last_name">Last Name</label>
										                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?=$user->last_name?>" required>
										                  <span class="validation-color" id="err_last_name"><?php echo form_error('last_name'); ?></span>
										                </div>		                
								              		</div>
								              	</div>
								                <div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  <label for="birth_date">Birth date</label>
										                  <input type="text" class="form-control datepicker" id="birth_date" name="birth_date" placeholder="1991-06-28" value="<?=$user->birth_date?>" autocomplete="off"  required>
										                  <span class="validation-color" id="err_birth_date"><?php echo form_error('birth_date'); ?></span>
										                </div>		                
								              		</div>
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  <label for="phone">Phone</label>
										                  <input type="text" class="form-control" id="phone" name="phone" placeholder="10 Digit Mobile number" value="<?=$user->phone?>" required>
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
											                            if(isset($user->country_id)){
											                              if($key->id == $user->country_id){
											                                echo "selected";
											                              }
											                            } 
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
										                        <?php
										                          	foreach ($state as  $key) {
										                        ?>
										                        <option value='<?php echo $key->id ?>' 
										                          	<?php 
											                            if(isset($user->state_id)){
											                              if($key->id == $user->state_id){
											                                echo "selected";
											                              }
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
								              		</div>
								              	</div>
								              	<div class="row">
								              		<div class="col-md-4">
								              			<div class="form-group">
										                  	<label for="birth_date">City</label>
										                  	<select class="form-control select2" id="city_id" name="city_id" style="width: 100%;">
										                        <option value="">Select</option>
										                        <?php
										                          	foreach ($city as  $key) {
										                        ?>
										                        <option value='<?php echo $key->id ?>' 
										                          	<?php 
											                            if(isset($user->city_id)){
											                              if($key->id == $user->city_id){
											                                echo "selected";
											                              }
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
								              		</div>
								              		<div class="col-md-4">
								              			<div class="form-group">
										                  <label for="address">Address</label>
										                  <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?=$user->address?>" required>
										                  <span class="validation-color" id="err_address"><?php echo form_error('address'); ?></span>
										                </div>		                
								              		</div>
								              		<div class="col-md-4">
								              			<div class="form-group">
										                  <label for="postal_code">Postal Code</label>
										                  <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Postal Code" value="<?=$user->postal_code?>" required>
										                  <span class="validation-color" id="err_postal_code"><?php echo form_error('postal_code'); ?></span>
										                </div>		                
								              		</div>
								              	</div>
							              	</div>
							              	<!-- /.tab-pane -->
							              	<div class="tab-pane" id="business">
								                <div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  <label for="company">Company Name</label>
										                  <input type="text" class="form-control" id="company" name="company" placeholder="Company Name" value="<?=$user->company?>">
										                  <span class="validation-color" id="err_company"><?php echo form_error('company'); ?></span>
										                </div>		                
								              		</div>
								              	</div>
								              	<div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  	<label for="gst_registration_type">GST Registration Type</label>
										                  	<select class="form-control select2" id="gst_registration_type" name="gst_registration_type" style="width: 100%;">
									                          	<option value="Registered" 
									                          			<?php if($user->gst_registration_type == "Registered") 
									                          						echo "selected"
									                          			?>>
									                          			Registered
									                          	</option>
									                          	<option value="Unregistered"
									                          			<?php if($user->gst_registration_type == "Unregistered") 
									                          						echo "selected"
									                          			?>>
									                          			Unregistered
									                          	</option>
									                          	<option value="Composition Scheme"
									                          			<?php if($user->gst_registration_type == "Composition Scheme") 
									                          						echo "selected"
									                          			?>>
									                          			Composition Scheme
									                          	</option>
									                          	<option value="Input Service Distributor"
									                          			<?php if($user->gst_registration_type == "Input Service Distributor") 
									                          						echo "selected"
									                          			?>>
									                          			Input Service Distributor
									                          	</option>
									                          	<option value="E-Commerece Operator"
									                          			<?php if($user->gst_registration_type == "E-Commerece Operator") 
									                          						echo "selected"
									                          			?>>E-Commerece Operator
									                          	</option>
									                      	</select>
										                  	<span class="validation-color" id="err_gst_registration_type"><?php echo form_error('gst_registration_type'); ?></span>
										                </div>		                
								              		</div>
								              	</div>
								              	<div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  <label for="gstid">GSTIN (GST No.)</label>
										                  <input type="text" class="form-control" id="gstid" name="gstid" placeholder="GSTIN (GST No.)" value="<?=$user->gstid?>">
										                  <span class="validation-color" id="err_gstid"><?php echo form_error('gstid'); ?></span>
										                </div>		                
								              		</div>
								              	</div>
							              	</div>
							              	<!-- /.tab-pane -->
							              	<div class="tab-pane" id="password">
								                <div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  <label for="password">Password</label>
										                  <input type="password" class="form-control" id="password" name="password" placeholder="********" value="">
										                </div>		                
										                <span class="validation-color" id="err_password"><?php echo form_error('password'); ?></span>
								              		</div>
								              	</div>
								              	<div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  <label for="password_confirm">Confirm Password</label>
										                  <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="********" value="">
										                  <span class="validation-color" id="err_password_confirm"><?php echo form_error('password_confirm'); ?></span>
										                </div>		                
								              		</div>
								              	</div>
							              	</div>
							              	<!-- /.tab-pane -->
							              	<div class="tab-pane" id="warehouse">
								                <div class="row">
								              		<div class="col-md-6">
								              			<div class="form-group">
										                  	<label for="warehouse_id">Warehouse</label>
										                  	<select class="form-control select2" id="warehouse_id" name="warehouse_id" style="width: 100%;">
										                  		<option>Select</option>
										                  		<?php 
										                  			foreach ($warehouse as $row) {
										                  		?>
									                          	<option value="<?=$row->warehouse_id?>"
									                          			<?php
									                          				if($assigned_warehouse != ""){
									                          					if($assigned_warehouse->warehouse_id == $row->warehouse_id)
									                          						echo "selected"	;
									                          				}
									                          				
									                          			?>
									                          			>
									                          			<?php 
									                          				$warehouse_name = $row->warehouse_name."(".$row->branch_name.")";
									                          				if($row->primary_warehouse == "1"){
									                          					$warehouse_name .= " (Primary)";
									                          				}
									                          				echo $warehouse_name;
									                          			?>
									                          	</option>
									                          	<?php
									                          		}
									                          	?>
									                      	</select>
										                  	<span class="validation-color" id="err_warehouse_id"><?php echo form_error('warehouse_id'); ?></span>
										                </div>		                
								              		</div>
								              	</div>
							              	</div>							              	
							              <!-- /.tab-pane -->
							            </div>
							            <!-- /.tab-content -->
						          	</div>
						          <!-- nav-tabs-custom -->
						        </div>
						        <!-- /.col -->
						    </div>
		              	</div>
		              	<div class="box-footer">
		              		<input type="hidden" name="user_id" value="<?=$user->id?>">
			                <button type="submit" class="btn btn-primary" name="submit" id="submit">Submit</button>
			                <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('user/users')"><!-- Cancel --> <?php echo $this->lang->line('branch_label_cancel'); ?></span>
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