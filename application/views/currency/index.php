<?php
	$this->load->view('layout/header');
?>
<script type="text/javascript">
	function delete_id(id)
  	{
    	if(confirm('Sure To Remove This Record ?'))
    	{
        	window.location.href='<?php  echo base_url('currency/delete_record/'); ?>'+id;
    	}
  	}

  	$(function() {
	    // setTimeout() function will be fired after page is loaded
	    // it will wait for 5 sec. and then will fire
	    // $("#successMessage").hide() function
	    setTimeout(function() {
	        $(".message").hide('blind', {}, 500)
	    }, 5000);
	});
</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('currency'); ?>"><?php echo "Currency"; ?></a></li>
          <!-- <li class="active"><?php echo "Currency"; //echo $this->lang->line('purchase_add_purchase'); ?></li> -->
        </ol>
      </h5>    
    </section>
    
    <section class="content">
      	<div class="row">
      		<?php $this->load->view('suggestion.php'); ?>
	        <?php 
	          $this->load->view('layout/setting_sidebar');
	        ?>
	        
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
		              	<h3 class="box-title">Currency</h3>
	              		<div class="box-tools">
		                	<a href="<?=base_url('currency/add_currency')?>" class="btn btn-info" >Add Currency</a>
		              	</div>
		            </div>
		            <div class="box-body no-padding">
		            	<div class="box-body with-border">
			              	<table id="example1" class="table table-bordered table-striped">
				                <thead>
					                <tr>
					                  	<th>#</th>
					                  	<th>Name</th>
					                  	<th>Symbol</th>
					                  	<th>Action</th>
					                </tr>
				                </thead>
				                <tbody>
				                	<?php 
				                		$i = 1;
				                      	foreach ($currency as $row) {
				                        	$id = $row->id;
				                    ?>
					                <tr>
					                  	<td><?=$i++?></td>
					                  	<td><?=$row->name?></td>
					                  	<td><?=$row->symbol?></td>
					                  	<td>
					                  				                  		
					                  		<a href="<?php echo base_url('currency/edit_currency/'); ?><?php echo $id; ?>" title="Edit" class="btn btn-xs btn-info">
					                  			<span class="glyphicon glyphicon-edit"></span>
					                  		</a>
			                          		<a href="javascript:delete_id(<?php echo $id;?>)" title="Delete" class="btn btn-xs btn-danger">
			                          			<span class="glyphicon glyphicon-trash"></span>
			                          		</a>
			                          		
					                  	</td>
					                </tr>
					                <?php
					                	}
					                ?>
					           	</tbody>
					            <tfoot>
					                <tr>
					                  	<th>#</th>
					                  	<th>Name</th>
					                  	<th>Symbol</th>
					                  	<th>Action</th>
					                </tr>
					            </tfoot>
				            </table>
				        </div>
		            </div>
			    </div>
			</div>
      	</div>
    </section>
  </div>
<?php
	$this->load->view('layout/footer');
?>