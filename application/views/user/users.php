<?php
	$this->load->view('layout/header');
?>
<script type="text/javascript">
	function delete_id(id)
  	{
    	if(confirm('Sure To Remove This Record ?'))
    	{
        	window.location.href='<?php  echo base_url('user/delete_user/'); ?>'+id;
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
          <li><a href="<?php echo base_url('user'); ?>"><?php echo "User"; ?></a></li>
          <li class="active"><?php echo "Users"; //echo $this->lang->line('purchase_add_purchase'); ?></li>
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

		        <?php 
		          if($this->session->flashdata('warning') != ''){ 
		        ?>
		        <div class="alert alert-info alert-dismissible">
	                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	                <h4><i class="icon fa fa-info"></i> Alert!</h4>
	                <p><?php echo $this->session->flashdata('warning');?></p>
              	</div>
		        
		        <?php
		          }
		        ?>
	        	<div class="box box-solid">
		            <div class="box-header with-border">
		              	<h3 class="box-title">Users</h3>
	              		<div class="box-tools">
		                	<?php 
	                  			if($this->user_model->has_permission("add_user")){
	                  		?>	                  		
		                	<a href="<?=base_url('user/add_user')?>" class="btn btn-info" >Add User</a>
		                	<?php
		                		}
		                	?>
		              	</div>
		            </div>
            <!-- /.box-header -->
		            <div class="box-body with-border">
		              	<table id="example1" class="table table-bordered table-striped">
			                <thead>
				                <tr>
				                  	<th>#</th>
				                  	<th>Name</th>
				                  	<th>Email</th>
				                  	<th>Role</th>
				                  	<th>Action</th>
				                </tr>
			                </thead>
			                <tbody>
			                	<?php 
			                		$i = 1;
			                      	foreach ($users as $row) {
			                        	$id = $row->id;
			                    ?>
				                <tr>
				                  	<td><?=$i++?></td>
				                  	<td><?=$row->first_name." ".$row->last_name?></td>
				                  	<td><?=$row->email?></td>
				                  	<td><?=$row->group_name?></td>
				                  	<td>		                  		
				                  		<?php 
				                  			if($this->user_model->has_permission("edit_user")){
				                  		?>	                  		
				                  		<a href="<?php echo base_url('user/edit_user/'); ?><?php echo $id; ?>" title="Edit" class="btn btn-xs btn-info">
				                  			<span class="glyphicon glyphicon-edit"></span>
				                  		</a>
				                  		<?php
				                  			}
				                  		?>
				                  		&nbsp;&nbsp;
				                  		<?php 
				                  			if($this->user_model->has_permission("delete_user")){
				                  		?>	                  		
		                          		<a href="javascript:delete_id(<?php echo $id;?>)" title="Delete" class="btn btn-xs btn-danger">
		                          			<span class="glyphicon glyphicon-trash"></span>
		                          		</a>
		                          		<?php 
				                  			}
				                  		?>
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
				                  	<th>Email</th>
				                  	<th>Role</th>
				                  	<th>Action</th>
				                </tr>
				            </tfoot>
			            </table>
			        </div>
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