<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('layout/header');
?>
<script type="text/javascript">
  function delete_id(id)
  {
     if(confirm('Sure To Remove This Record ?'))
     {
        window.location.href='<?php  echo base_url('expense_category/delete/'); ?>'+id;
     }
  }
</script>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
         <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard --><?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li class="active">Expense Category</li>
        </ol>
      </h5> 
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <?php $this->load->view('suggestion.php'); ?>
        <?php 
          if ($this->session->flashdata('success') != ''){ 
        ?>
        <div class="col-sm-12">
          <div class="alert alert-success">
            <button class="close" data-dismiss="alert" type="button">×</button>
              <p><?php echo $this->session->flashdata('success');?></p>
            <div class="alerts-con"></div>
          </div>
        </div>
        <?php
          }
        ?>

        <?php 
          if ($this->session->flashdata('fail') != ''){ 
        ?>
        <div class="col-sm-12">
          <div class="alert alert-danger">
            <button class="close" data-dismiss="alert" type="button">×</button>
              <p><?php echo $this->session->flashdata('fail');?></p>
            <div class="alerts-con"></div>
          </div>
        </div>
        
        <?php
          }
        ?>
        
        <?php 
          $this->load->view('layout/setting_sidebar');
        ?>
        <div class="col-md-9">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Expense Category</h3>
              <a class="btn btn-sm btn-info pull-right" href="<?php echo base_url('expense_category/add');?>">Add New Expense Category</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="index" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><!-- No --><?php echo $this->lang->line('category_lable_no'); ?></th>
                  <th>Name</th>
                  <th><!-- Actions --><?php echo $this->lang->line('category_lable_actions'); ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($data as $row) {
                    ?>
                    <tr>
                      <td></td>
                      <td><?php echo $row->name; ?></td>
                      <td>
                          <a href="<?php echo base_url('expense_category/edit/'); ?><?php echo $row->id; ?>" title="Edit" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;
                          <a href="javascript:delete_id(<?php echo $row->id;?>)" title="Delete" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>
                      </td>
                    </tr>
                    <?php
                      }
                    ?>
                <tfoot>
                <tr>
                  <th><!-- No --><?php echo $this->lang->line('category_lable_no'); ?></th>
                  <th>Name</th>
                  <th><!-- Actions --><?php echo $this->lang->line('category_lable_actions'); ?></th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php
  $this->load->view('layout/footer');
?>
