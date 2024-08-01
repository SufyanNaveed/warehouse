<?php
	$this->load->view('layout/header');
?>

<div class="content-wrapper">
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('stock'); ?>"><?php echo "Stock"; ?></a></li>
          <li class="active"><?php echo "Add/Edit Stock"; //echo $this->lang->line('purchase_add_purchase'); ?></li>
        </ol>
      </h5>    
    </section>

    <!-- Main content -->
    <section class="content">
      	<div class="row">
      		<?php $this->load->view('suggestion.php'); ?>
	        <?php 
	        	$this->load->view('layout/product_sidebar');
	        ?>
	        <div class="col-md-9">
	        	<div class="box box-solid">
        		<?php 
        			if(isset($stock->id)){
        		?>
		            <div class="box-header with-border">
		            	<h3 class="box-title">Edit Stock</h3>
		            </div>
		            <form role="form" action="<?=base_url('stock/edit')?>" method="POST">
	           	<?php 
	           		}else{
	           	?>
		            <div class="box-header with-border">
		            	<h3 class="box-title">Add Stock</h3>
		            </div>
		            <form role="form" action="<?=base_url('stock/add')?>" method="POST">
	           	<?php
	           		}
	           	?>
		              	<div class="box-body">
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  	<label for="product_id">Product</label><span class="validation-color">*</span>
					                  	<select class="form-control select2" id="product_id" name="product_id" style="width: 100%;" required="required">
					                      	<option value=""><?php echo $this->lang->line('add_biller_select'); ?></option>
					                      	<?php
					                        	foreach ($products as $row) {
					                      	?>
					                          		<option value='<?=$row->product_id?>'
					                          		<?php 
					                          			if(isset($stock->product_id)){
					                          				if($stock->product_id == $row->product_id){
					                          					echo " selected";
					                          				}
					                          			}
					                          		?>
					                          		><?=$row->name?></option>

					                        <?php
					                        	}
					                      	?>
					                    </select>
					                    <span class="validation-color" id="err_product_id"><?php echo form_error('product_id'); ?></span>
					                </div>		
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="quantity">Quantity</label><span class="validation-color">*</span>
					                  <input type="number" class="form-control" id="quantity" name="quantity" placeholder="0.00" value="<?php if(isset($stock->quantity)) echo $stock->quantity;?>" required>
					                </div>		                
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  	<label for="warehouse_id">Warehouse</label><span class="validation-color">*</span>
					                  	<select class="form-control select2" id="warehouse_id" name="warehouse_id" style="width: 100%;">
					                      	<option value=""><!-- Select -->
					                        	<?php echo $this->lang->line('add_biller_select'); ?>
					                      	</option>
					                      	<?php
					                        	foreach ($warehouse as $row) {
					                        ?>
					                          		<option value='<?=$row->warehouse_id?>' 
				                          			<?php 
				                          				if(isset($stock->warehouse_id)){
				                          					if($stock->warehouse_id == $row->warehouse_id)
				                          					{
				                          						echo ' selected';
				                          					}
				                          				}
				                          				else
				                          				{
				                          					if($row->primary_warehouse == "1") echo " selected"; 		
				                          				}
				                          			?>
					                          		><?=$row->warehouse_name?></option>;
					                        <?php
					                        	}
					                      	?>
					                    </select>
					                    <span class="validation-color" id="err_warehouse_id"><?php echo form_error('warehouse_id'); ?></span>
					                </div>		
			              		</div>
			              	</div>
			              	<!-- <div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  	<label for="supplier_id">Supplier</label>
					                  	<select class="form-control select2" id="supplier_id" name="supplier_id" style="width: 100%;">
					                      	<option value=""><?php echo $this->lang->line('add_biller_select'); ?></option>
					                      	<?php
					                        	foreach ($suppliers as $row) {
					                        ?>
					                          		<option value='<?=$row->id?>'
					                          		<?php 
					                          			if(isset($stock->supplier_id)){
					                          				if($stock->supplier_id == $row->id){
					                          					echo " selected";
					                          				}
					                          			}
					                          		?>
					                          		><?=$row->first_name.' '.$row->last_name?>
					                          			
					                          		</option>;
					                        <?php
					                        	}
					                      	?>
					                    </select>
					                    <span class="validation-color" id="err_supplier_id"><?php echo form_error('supplier_id'); ?></span>
					                </div>		
			              		</div>
			              	</div> -->
		              	</div>
		              	<div class="box-footer">
		              		<input type="hidden" name="id" value="<?php if(isset($stock->id)) echo $stock->id;?>">
			                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
			                <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('currency')"><!-- Cancel --> <?php echo $this->lang->line('branch_label_cancel'); ?></span>
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