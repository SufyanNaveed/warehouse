<?php
	$this->load->view('layout/header');
?>

<div class="content-wrapper">
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('user'); ?>"><?php echo "User"; ?></a></li>
          <li class="active"><?php echo "Roles"; //echo $this->lang->line('purchase_add_purchase'); ?></li>
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
        		<?php 
        			if(isset($role->id)){
        		?>
		            <div class="box-header with-border">
		            	<h3 class="box-title">edit User Role</h3>
		            </div>
		            <form role="form" action="<?=base_url('user/edit_role')?>" method="POST">

	           	<?php 
	           		}else{
	           	?>
		            <div class="box-header with-border">
		            	<h3 class="box-title">Add User Role</h3>
		            </div>
		            <form role="form" action="<?=base_url('user/add_role')?>" method="POST">
	           	<?php
	           		}
	           	?>
		              	<div class="box-body">
		              		<?php 
		              			if(!isset($role))
		              			{
		              		?>
		              		<div class="row">
			              		<div class="col-md-8">
			              			<div class="form-group">
					                  	<label for="account_group_id">Account Group (<span class="text-red">You won't be able to change the account group later on</span>)</label>
					                  	<select class="form-control select2" name="account_group_id" required="required">
					                  		<option>Select</option>
					                  		<?php 
					                  			foreach ($account_groups as $row) {
					                  		?>
					                  			<option value="<?=$row->id?>"><?=$row->group_title?></option>
					                  		<?php
					                  			}
					                  		?>
					                  	</select>

					                </div>		
			              		</div>
			              	</div>
			              	<?php 
			              		}
			              		else
			              		{
			              	?>
			              	<div class="row">
			              		<div class="col-md-8">
			              			<div class="form-group">
					                  	<label for="account_group_id">Account Group</label>
					                  	<label>
					                  		
					                  	</label>
					                  	<input type="text" class="form-control" name="account_group" value="<?=$role->group_title?>" readonly>
					                  	<input type="hidden" name="account_group_id" value="<?=$role->account_group_id?>">
					                </div>		
			              		</div>
			              	</div>

			              	<?php
			              		}
			              	?>
			              	<div class="row">
			              		<div class="col-md-8">
			              			<div class="form-group">
					                  <label for="name">Role</label>
					                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter Role" value="<?php if(isset($role->name)) echo $role->name;?>" 
					                  		<?php 
					                  			$default_group =array("admin","purchaser","accountant","sales_person","customer","supplier"); 
					                  			if(isset($role->name))
					                  			{
					                  				if(in_array($role->name,$default_group))
					                  				{
					                  					echo ' disabled';
					                  				}
					                  			}

					                  		?>>
					                </div>		
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-8">
			              			<div class="form-group">
					                  <label for="description">Description</label>
					                  <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php if(isset($role->description)) echo $role->description;?>">
					                </div>		                
			              		</div>
			              	</div>
		              	</div>
		              	<div class="box-footer">
		              		<input type="hidden" name="id" value="<?php if(isset($role->id)) echo $role->id;?>">
			                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
			                <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('user')"><!-- Cancel --> <?php echo $this->lang->line('branch_label_cancel'); ?></span>
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