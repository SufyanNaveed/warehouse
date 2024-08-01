<?php
	$this->load->view('layout/header');
?>

<div class="content-wrapper">
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('currency'); ?>"><?php echo "User"; ?></a></li>
          <li class="active"><?php echo "Add/Edit Currency"; //echo $this->lang->line('purchase_add_purchase'); ?></li>
        </ol>
      </h5>    
    </section>

    <!-- Main content -->
    <section class="content">
      	<div class="row">
      		<?php $this->load->view('suggestion.php'); ?>
	        <?php 
	        	$this->load->view('layout/setting_sidebar');
	        ?>
	        <div class="col-md-9">
	        	<div class="box box-solid">
        		<?php 
        			if(isset($currency->id)){
        		?>
		            <div class="box-header with-border">
		            	<h3 class="box-title">Edit Currency</h3>
		            </div>
		            <form role="form" action="<?=base_url('currency/edit_currency')?>" method="POST">

	           	<?php 
	           		}else{
	           	?>
		            <div class="box-header with-border">
		            	<h3 class="box-title">Add Currency</h3>
		            </div>
		            <form role="form" action="<?=base_url('currency/add_currency')?>" method="POST">
	           	<?php
	           		}
	           	?>
		              	<div class="box-body">
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="name">Name</label>
					                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter Role" value="<?php if(isset($currency->name)) echo $currency->name;?>">
					                </div>		
			              		</div>
			              	</div>
			              	<div class="row">
			              		<div class="col-md-6">
			              			<div class="form-group">
					                  <label for="symbol">Symbol</label>
					                  <input type="text" class="form-control" id="symbol" name="symbol" placeholder="Description" value="<?php if(isset($currency->symbol)) echo $currency->symbol;?>">
					                </div>		                
			              		</div>
			              	</div>
		              	</div>
		              	<div class="box-footer">
		              		<input type="hidden" name="id" value="<?php if(isset($currency->id)) echo $currency->id;?>">
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