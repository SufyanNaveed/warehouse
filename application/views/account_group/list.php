<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layout/header');
?>
<script type="text/javascript">
  function delete_id(id)
  {
     if(confirm('Sure To Remove This Record ?'))
     {
        window.location.href='<?php  echo base_url('account_group/delete/'); ?>'+id;
     }
  }
</script>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
         <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard --> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li class="active">Account Group <!-- <?php echo $this->lang->line('branch_label'); ?> --></li>
        </ol>
      </h5> 
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
      <!-- right column -->
      <?php $this->load->view('suggestion.php'); ?>
      <div class="col-md-12">
         <?php 
            if ($this->session->flashdata('success') != ''){ 
          ?>
          <div class="alert alert-success message">    
            <p><?php echo $this->session->flashdata('success');?></p>
          </div>
          <?php
            }
          ?>

          <?php 
            if ($this->session->flashdata('fail') != ''){ 
          ?>
          <div class="alert alert-danger message">    
            <p><?php echo $this->session->flashdata('fail');?></p>
          </div>
          <?php
            }
          ?>
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Account Groups</h3>
              <a class="btn btn-sm btn-info pull-right" href="<?php echo base_url('account_group/add');?>" title="Add New Branch" onclick="">New Account Group</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="index" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Account Group No</th>
                  <th>Group Title</th>
                  <th>Category</th>
                  <th>Opening Balance<?php echo '('.$this->session->userdata('symbol').')' ?></th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                    foreach ($account_groups as $row) {
                       $id= $row->id;
                  ?>
                  <tr>
                    <td></td>
                    <td><?php echo $row->group_title; ?></td>
                    <td><?php echo $row->category; ?></td>
                    <td align="center"><?php echo $row->opening_balance; ?></td>
                    <td>
                        <!-- <a href="" title="View Details" class="btn btn-xs btn-warning"><span class="fa fa-eye"></span></a>&nbsp;&nbsp; -->
                        <a href="<?php echo base_url('account_group/edit/'); ?><?php echo $id; ?>" title="Edit" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                        <a href="javascript:delete_id(<?php echo $id;?>)" title="Delete" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                    </td>
                  <?php
                    }
                  ?>
                <tfoot>
                <tr>
                  <th>Account Group No</th>
                  <th>Group Title</th>
                  <th>Category</th>
                  <th>Opening Balance<?php echo '('.$this->session->userdata('symbol').')' ?></th>
                  <th>Actions</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
      </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
  $this->load->view('layout/footer');
?>
