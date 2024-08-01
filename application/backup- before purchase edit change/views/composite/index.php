<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->view('layout/header');
?>
<script type="text/javascript">
  function delete_id(id)
  {
     if(confirm('<?php echo $this->lang->line('product_delete_conform'); ?>'))
     {
        window.location.href='<?php  echo base_url('composite/delete/'); ?>'+id;
     }
  }
  $(function() {    
    setTimeout(function() {
        $(".message").hide('blind', {}, 100)
    }, 2000);
  });
</script>
<div class="content-wrapper">
    <section class="content-header">
      <h5>
         <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li class="active"><?php echo "Composite" ?></li>
        </ol>
      </h5> 
    </section>
    <section class="content">
      <div class="row">
      
      <?php $this->load->view('suggestion.php'); ?>
      <!-- right column -->
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
              <h3 class="box-title"><?php echo "Composite Product"; ?></h3>
              <a class="btn btn-sm btn-info pull-right" href="<?php echo base_url('composite/add');?>" title="Add New Composite Product"><?php echo "Composite Product" ?> </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-y: auto;">
              <table id="log_datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $this->lang->line('product_no'); ?></th>
                  <th><?php echo "Composite Product" ?></th>
                  <th><?php echo "Description" ?></th>
                  <th><?php echo $this->lang->line('product_action'); ?></th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                      $i = 1;

                      foreach($data as $row){
                        $id = $row->composite_id;
                  ?>
                  <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->composite_product_name; ?></td>
                      <td><?php echo $row->description; ?></td>
                      <td>
                        <a href="<?php echo base_url('composite/edit/'); ?><?php echo $id; ?>" title="Edit" class="btn btn-xs btn-info">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>&nbsp;&nbsp;

                        <a href="javascript:delete_id(<?php echo $id;?>)" title="Delete" class="btn btn-xs btn-danger">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </td>
                  </tr>
                  <?php 
                        $i++;
                      }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?php echo $this->lang->line('product_no'); ?></th>
                    <th><?php echo "Composite Product" ?></th>
                    <th><?php echo "Description" ?></th>
                    <th><?php echo $this->lang->line('product_action'); ?></th>
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
