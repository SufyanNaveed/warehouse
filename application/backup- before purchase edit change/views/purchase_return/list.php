<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layout/header');
?>
<script type="text/javascript">
  function delete_id(id)
  {
     if(confirm('<?php echo $this->lang->line('product_delete_conform'); ?>'))
     {
        window.location.href='<?php  echo base_url('purchase_return/delete/'); ?>'+id;
     }
  }
</script>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
         <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li class="active"><?php echo $this->lang->line('header_purchase_return'); ?></li>
        </ol>
      </h5> 
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
      <!-- right column -->
      <?php $this->load->view('suggestion.php'); ?>
      <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $this->lang->line('purchase_return_list_purchase_return'); ?></h3>
              <a class="btn btn-sm btn-info pull-right" href="<?php echo base_url('purchase_return/add');?>" title="Add New Purchase"><?php echo $this->lang->line('purchase_return_add_new_purchase_return'); ?> </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="index" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th><?php echo $this->lang->line('product_no'); ?></th>
                  <th><?php echo $this->lang->line('purchase_date'); ?></th>
                  <th><?php echo $this->lang->line('purchase_reference_no'); ?></th>
                  <th><?php echo $this->lang->line('purchase_supplier'); ?></th>
                  <th><?php echo "Returned Product"; ?></th>
                  <th><?php echo $this->lang->line('purchase_total').'('.$this->session->userdata('symbol').')'; ?></th>
                  <th><?php echo $this->lang->line('product_action'); ?></th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                      foreach ($data as $row) {
                         $id= $row->id;
                    ?>
                    <tr>
                      <td></td>
                      <td><?php echo $row->date; ?></td>
                      <td><?php echo $row->reference_no; ?></td>
                      <td><?php echo $row->first_name." ".$row->last_name; ?></td>
                      <td>
                        <?php 
                            $purchase_return_items = $this->purchase_return_model->getPurchaseReturnItems($id,$row->warehouse_id);
                            foreach ($purchase_return_items as $row1) {
                              echo $row1->name."(".$row1->code.")<br/>";
                            }
                        ?>
                      </td>
                      <td><?php echo $row->total; ?></td>
                      <td>
                        <?php 
                          if($row->sales_amount != $row->paid_amount)
                          {
                        ?>
                          <!-- <a href="" title="View Details" class="btn btn-xs btn-warning"><span class="fa fa-eye"></span></a>&nbsp;&nbsp; -->
                          <!-- <a href="<?php echo base_url('purchase_return/edit/'); ?><?php echo $id; ?>" title="Edit" class="btn btn-xs btn-info"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp; -->
                          <a href="<?php echo base_url('purchase_return/view/'); ?><?php echo $id; ?>" title="View Purchase Return" class="btn btn-xs btn-warning">
                            <span class="fa fa-eye"></span>
                          </a>
                          <a href="javascript:delete_id(<?php echo $id;?>)" title="Delete" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-trash"></span></a>

                          <a href="<?php echo base_url('purchase_return/receipt/'); ?><?php echo $id; ?>" title="Receive Money" class="btn btn-xs btn-success"><i class="fa fa-money"></i></a>&nbsp;&nbsp;
                        <?php 
                          }
                          else
                          {
                        ?>
                          <a href="<?php echo base_url('purchase_return/view/'); ?><?php echo $id; ?>" title="View Purchase Return" class="btn btn-xs btn-warning">
                            <span class="fa fa-eye"></span>
                          </a>
                          <span class="btn btn-xs btn-success">Settled</span>
                        <?php
                          }
                        ?>
                          
                      </td>
                    <?php
                      }
                    ?>
                <tfoot>
                <tr>
                  <th><?php echo $this->lang->line('product_no'); ?></th>
                  <th><?php echo $this->lang->line('purchase_date'); ?></th>
                  <th><?php echo $this->lang->line('purchase_reference_no'); ?></th>
                  <th><?php echo $this->lang->line('purchase_supplier'); ?></th>
                  <th><?php echo "Returned Product" ?></th>
                  <th><?php echo $this->lang->line('purchase_total').'('.$this->session->userdata('symbol').')'; ?></th>
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
