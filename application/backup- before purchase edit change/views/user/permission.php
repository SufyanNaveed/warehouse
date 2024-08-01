<?php
	$this->load->view('layout/header');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('user'); ?>"><?php echo "User"; ?></a></li>
          <li class="active"><?php echo "Permission"; //echo $this->lang->line('purchase_add_purchase'); ?></li>
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
		              	<h3 class="box-title">Permission</h3>
		            </div>
		            <!-- /.box-header -->
		            <form action="<?php echo base_url('user/save_permission') ?>" method="POST" name="permission_form" id="permission_form">
			            <div class="box-body">
			              	<div class="box-group" id="accordion">
			              		<div class="form-group">
					                <label for="role_id">Select User Role</label>
					                <select class="form-control select2" name="role_id" id="role_id" style="width: 100%;">
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
				                <div class="">
				                  	<div class="box-header">
				                    	<input type="checkbox" class="select_all" id="product_all"> Select All
				                  	</div>
				                  	<div id="collapseOne" class="panel-collapse collapse in">
					                    <div class="box-body">
					                    	<div class="row">
					                    		<div class="col-md-12 product_h select_all_h">
					                    			<label>Product</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($product_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 product_h select_all_h">
					                    			<input type="checkbox" class="select_all product" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 quotation_h select_all_h">
					                    			<label>Quotation</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($quotation_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 quotation_h select_all_h">
					                    			<input type="checkbox" class="select_all quotation" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 sales_h select_all_h">
					                    			<label>Sales</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($sales_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 sales_h select_all_h">
					                    			<input type="checkbox" class="select_all sales" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 transfer_h select_all_h">
					                    			<label>Transfer</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($transfer_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 transfer_h select_all_h">
					                    			<input type="checkbox" class="select_all transfer" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 purchase_h select_all_h">
					                    			<label>Purchase</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($purchase_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 purchase_h select_all_h">
					                    			<input type="checkbox" class="select_all purchase" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<!--
					                    		<div class="col-md-12">
					                    			<label>Transfer</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($transfer_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4">
					                    			<input type="checkbox" class="select_all transfer" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?> -->
					                    		<div class="col-md-12 sales_return_h select_all_h">
					                    			<label>Sales Return</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($s_return_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 sales_return_h select_all_h">
					                    			<input type="checkbox" class="select_all sales_return" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 purchase_return_h select_all_h">
					                    			<label>Purchase Return</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($p_return_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 purchase_return_h select_all_h">
					                    			<input type="checkbox" class="select_all purchase_return" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		
					                    		<div class="col-md-12 user_h select_all_h">
					                    			<label>User</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($user_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 user_h select_all_h">
					                    			<input type="checkbox" class="select_all user" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 location_h select_all_h">
					                    			<label>Location</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($location_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 location_h select_all_h">
					                    			<input type="checkbox" class="select_all location" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 category_h select_all_h">
					                    			<label>Category</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($category_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 category_h select_all_h">
					                    			<input type="checkbox" class="select_all category" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 subcategory_h select_all_h">
					                    			<label>Subcategory</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($subcategory_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 subcategory_h select_all_h">
					                    			<input type="checkbox" class="select_all subcategory" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 brand_h select_all_h">
					                    			<label>Brand</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($brand_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 brand_h select_all_h">
					                    			<input type="checkbox" class="select_all brand" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 branch_h select_all_h">
					                    			<label>Branch</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($branch_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 branch_h select_all_h">
					                    			<input type="checkbox" class="select_all branch" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 warehouse_h select_all_h">
					                    			<label>Warehouse</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($warehouse_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 warehouse_h select_all_h">
					                    			<input type="checkbox" class="select_all warehouse" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 discount_h select_all_h">
					                    			<label>Discount</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($discount_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 discount_h select_all_h">
					                    			<input type="checkbox" class="select_all discount" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 ledger_h select_all_h">
					                    			<label>Ledger</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($ledger_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 ledger_h select_all_h">
					                    			<input type="checkbox" class="select_all ledger" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 report_h select_all_h">
					                    			<label>Report</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($report_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 report_h select_all_h">
					                    			<input type="checkbox" class="select_all report" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 bank_account_h select_all_h">
					                    			<label>Bank Account</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($bank_account_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 bank_account_h select_all_h">
					                    			<input type="checkbox" class="select_all bank_account" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 expense_category_h select_all_h">
					                    			<label>Expense Category</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($expense_category_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 expense_category_h select_all_h">
					                    			<input type="checkbox" class="select_all expense_category" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 expense_h select_all_h">
					                    			<label>Expense</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($expense_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 expense_h select_all_h">
					                    			<input type="checkbox" class="select_all expense" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		<div class="col-md-12 setting_h select_all_h">
					                    			<label>Setting</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($setting_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 setting_h select_all_h">
					                    			<input type="checkbox" class="select_all setting" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		
					                    		<div class="col-md-12 transaction_h select_all_h">
					                    			<label>Transaction</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($transaction_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 transaction_h select_all_h">
					                    			<input type="checkbox" class="select_all transaction" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>

					                    		<div class="col-md-12 accountgroup_h select_all_h">
					                    			<label>Account Groups</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($accountgroup_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 accountgroup_h select_all_h">
					                    			<input type="checkbox" class="select_all accountgroup" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>

					                    		<div class="col-md-12 currency_h select_all_h">
					                    			<label>Currency</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($currency_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 currency_h select_all_h">
					                    			<input type="checkbox" class="select_all currency" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>

					                    		<div class="col-md-12 stock_h select_all_h">
					                    			<label>Stock</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($stock_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 stock_h select_all_h">
					                    			<input type="checkbox" class="select_all stock" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>

					                    		<div class="col-md-12 user_group_h select_all_h">
					                    			<label>User Group</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($user_group_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 user_group_h select_all_h">
					                    			<input type="checkbox" class="select_all user_group" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>

					                    		<div class="col-md-12 permission_h select_all_h">
					                    			<label>Permission</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($permission_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 permission_h select_all_h">
					                    			<input type="checkbox" class="select_all permission" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    		
					                    		<div class="col-md-12 role_h select_all_h">
					                    			<label>Role/Group</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($role_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 role_h select_all_h">
					                    			<input type="checkbox" class="select_all role" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>

					                    		<div class="col-md-12 uqc_h select_all_h">
					                    			<label>UQC</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($uqc_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 uqc_h select_all_h">
					                    			<input type="checkbox" class="select_all uqc" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>

					                    		<div class="col-md-12 customer_h select_all_h">
					                    			<label>Customer</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($customer_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 customer_h select_all_h">
					                    			<input type="checkbox" class="select_all customer" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>

					                    		<div class="col-md-12 supplier_h select_all_h">
					                    			<label>Supplier</label>
					                    		</div>
					                    		<?php 
					                    			foreach ($supplier_permissions as $row) {
					                    		?>
					                    		<div class="col-md-4 supplier_h select_all_h">
					                    			<input type="checkbox" class="select_all supplier" name="permission[]" id="<?=$row->id?>" value="<?=$row->id?>"> 
					                    			<?=$row->description?>
					                    		</div>
					                    		<?php 
					                    			}
					                    		?>
					                    							                    		
					                    	</div>
					                    </div>
				                  	</div>
				                </div>
			              	</div>
			            </div>
			            <div class="box-footer" align="center">
			            	<input type="submit" name="submit" value="Save Changes" class="btn btn-primary">
			            	<span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('user')"><!-- Cancel --> <?php echo $this->lang->line('branch_label_cancel'); ?></span>
			            </div>
		            </form>
		            <!-- /.box-body -->
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
<script type="text/javascript">
	$(document).ready(function(){

		// $(".select_all").attr("disabled", true);
		
		// select all permission
	    $('#product_all').on('click',function(){
	        if(this.checked){
	            $('.select_all').each(function(){
	                this.checked = true;
	            });
	        }else{
	            $('.select_all').each(function(){
	                this.checked = false;
	            });
	        }
	    });

	    // submit permission form
	    $("#permission_form").submit(function() {
	    	if($('#role_id').val() == ""){
	    		alert("Please select role first");
	    		return false;
	    	}else{
	    		return true;
	    	}
	  	});

	  	// get existing permission
	    $('#role_id').change(function(){
	    	
	    	var role_id = $('#role_id').val();
	    	var role_text = $("#role_id option:selected").html().toLowerCase()

    		$.ajax({
	          	url: "<?php echo base_url('user/ajax_get_permission_records_by_role') ?>/"+role_id,
	          	type: "GET",
	          	dataType: "JSON",
	          	success: function(data){ 

	          		$('.select_all').each(function(){
		                this.checked = false;
		            });

	          		for (var i = data.length - 1; i >= 0; i--) 
	          		{
	          			$("#"+data[i].id).prop('checked', true);
	          		}

	          		if(role_text == "purchaser")
	          		{
	          			var module_array = ['sales','sales_return','quotation'];
	          			show_permission(['select_all_h']);
	          			hide_permission(module_array);
	          		}

	          		if(role_text == "sales_person")
	          		{
	          			var module_array = ['purchase','purchase_return'];
	          			show_permission(['select_all_h']);
	          			hide_permission(module_array);
	          		}

	          		if(role_text == "admin")
	          		{
	          			show_permission(['select_all_h']);
	          		}

	          		if(role_text != "admin")
	          		{
	          			hide_permission(['user_group_h']);
	          		}
	          		
	          		// if(role_id == "1")
          			// {
          			// 	var module_array = ['select_all'];
          			// 	enable_permission(module_array);
          			// }
          			// else if(role_id == "2")
          			// {
          			// 	var module_array = ['select_all']
          			// }

	          	}
	        });
	    });

	    function disable_permission(module_array)
	    {
	    	for (var i = module_array.length - 1; i >= 0; i--) 
	  		{
	  			$("."+module_array[i]).prop('checked', false);
	  			$("."+module_array[i]).prop("disabled", true);
	  		}
	    }

	    function enable_permission(module_array)
	    {
	    	for (var i = module_array.length - 1; i >= 0; i--) 
	  		{
	  			$("."+module_array[i]).removeAttr("disabled");
	  		}
	    }

	    function hide_permission(module_array)
	    {
	    	for (var i = module_array.length - 1; i >= 0; i--) 
	  		{
	  			$("."+module_array[i]+"_h").hide();
	  		}
	    }

	    function show_permission(module_array)
	    {
	    	for (var i = module_array.length - 1; i >= 0; i--) 
	  		{
	  			$("."+module_array[i]).show();
	  		}
	    }
	});

</script>