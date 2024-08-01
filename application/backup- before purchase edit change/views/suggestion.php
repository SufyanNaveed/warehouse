<?php 

	$branch 		= $this->branch_model->get_records();
	$warehouse		= $this->warehouse_model->get_records();
	
	$sales_person	= $this->user_model->get_user_records_by_role("sales_person");
	$purchaser		= $this->user_model->get_user_records_by_role("purchaser");
	$category		= $this->category_model->getCategory();
	$subcategory	= $this->subcategory_model->getSubcategory();
	$brand			= $this->brand_model->getBrand();
	$product		= $this->product_model->getProducts();
	$payment_method	= $this->payment_method_model->get_records();
	$expense_category	= $this->expense_category_model->get_records();
	$email_setup	= $this->email_setup_model->getEmailSetup();
	$discount		= $this->discount_model->getDiscount();
	$sms_setting	= $this->sms_setting_model->getSmsSetting();
	

	if($branch == null)
	{
?>
		<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create branch first before proceed any further <a href="<?php echo base_url('branch/add') ?>" class="btn btn-default text-blue">Add Branch</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($warehouse == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create warehouse first before proceed any further <a href="<?php echo base_url('warehouse/add') ?>" class="btn btn-default text-blue">Add Warehouse</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($sales_person == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create sales person/biller first before proceed any further <a href="<?php echo base_url('user/add_user') ?>" class="btn btn-default text-blue">Add Sales Person</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($purchaser == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create purchaser first before proceed any further <a href="<?php echo base_url('user/add_user') ?>" class="btn btn-default text-blue">Add Purchaser</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($category == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create category first before proceed any further <a href="<?php echo base_url('category/add') ?>" class="btn btn-default text-blue">Add Category</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($subcategory == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create subcategory first before proceed any further <a href="<?php echo base_url('subcategory/add') ?>" class="btn btn-default text-blue">Add Subcategory</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($brand == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create brand first before proceed any further <a href="<?php echo base_url('brand/add') ?>" class="btn btn-default text-blue">Add Brand</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($product == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create product first before proceed any further <a href="<?php echo base_url('product/add') ?>" class="btn btn-default text-blue">Add Product</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($payment_method == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create payment method first before proceed any further <a href="<?php echo base_url('payment_method/add_payment_method') ?>" class="btn btn-default text-blue">Add Payment Method</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($expense_category == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please create expense category first before proceed any further <a href="<?php echo base_url('expense_category/add') ?>" class="btn btn-default text-blue">Add Expense Category</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($email_setup == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please setup email setting first before proceed any further <a href="<?php echo base_url('email_setup/index') ?>" class="btn btn-default text-blue">Add Email Setup</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
	else if($sms_setting == null)
	{
?>
	<div class="col-md-12">
		  	<div class="callout callout-info">    
		  		<h4>Recommendation</h4>
				<p>Please sms setting first before proceed any further <a href="<?php echo base_url('sms_setting/index') ?>" class="btn btn-default text-blue">Add SMS Setting</a>	    	
				</p>
		  	</div>  
		</div>
<?php
	}
?>