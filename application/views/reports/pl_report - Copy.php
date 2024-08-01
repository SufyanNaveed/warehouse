<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layout/header');
?>
<style type="text/css">
  @media print {
    #print_statement {
      transform: rotate(-90deg);
    }
  }
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
         <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard --><?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li class="active">Statement</li>
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
              <h3 class="box-title">Profit & Loss Report For <?php echo $company[0]->name; ?></h3>
              <div class="pull-right">
                <button type="submit" class="btn btn-sm pull-right">
                  <i class="fa fa-file-pdf-o"></i>
                </button>
                <button type="submit" class="btn btn-sm pull-right" onclick="printDiv('print_statement')" style="margin-right: 10px;">
                  <i class="fa fa-print"></i>
                </button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                
                <div class="col-md-12">
                  <div style="border:1px solid #e3e3e3;border-radius: 3px;" id="print_statement" class="col-md-12">
                    <div class="col-md-6">
                      <p style="font-size: 17px;font-weight: bold">Company Details</p>
                      <?php echo $company[0]->name; ?><br>
                      <?php echo $company[0]->street; ?><br>
                      <?php echo $company[0]->city_name; ?>,<?php echo $company[0]->state_name; ?><br>
                      <?php echo $company[0]->country_name; ?>,<?php echo $company[0]->zip_code; ?><br>
                    </div>
                    <div class="col-md-6">
                      <p class="pull-right">
                      <span style="font-size: 18px;font-weight: bold;" class="pull-right">Profit & Loss Report</span><br>
                      01/04/<?php echo date("Y"); ?> To 31/3/<?php echo date('Y', strtotime('+1 year')) ?></p>
                    </div>
                    <div class="col-md-12">
                      <p><center>Showing all invoices and Payments between 01/04/<?php echo date("Y"); ?> To 31/3/<?php echo date('Y', strtotime('+1 year')) ?></center></p>
                    </div>
                    <div class="col-md-12" style="overflow-y: hidden;">
                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <th width="80%">Sales</th>
                          <th>Amount</th>
                        </thead>
                        <tbody>
                          <td>Sales</td>
                          <td><?=$sales?></td>
                        </tbody>
                      </table>
                      <table class="table table-bordered table-striped table-hover">
                        <tbody>
                          <td width="80%">Total Cost of Sales</td>
                          <td><?=$sales_cost?></td>
                        </tbody>
                      </table>
                      <table class="table table-bordered table-striped table-hover">
                        <tbody>
                          <td width="80%">Gross Profit</td>
                          <td><?=$sales_profit_before_tax?></td>
                        </tbody>
                      </table>
                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <th colspan="2">Expense</th>
                        </thead>
                        <tbody>
                          <?php
                            $expense_amount = 0; 
                            foreach ($expense as $row) {
                              $expense_amount += $row->closing_balance;
                          ?>
                          <tr>
                            <td width="80%"><?=$row->title?></td>
                            <td><?=$row->closing_balance?></td>    
                          </tr>                          
                          <?php 
                            }
                          ?>
                          <tr>
                            <td>
                              <b>Total Expense</b>
                            </td>
                            <td>
                              <b><?=$expense_amount?></b>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table class="table table-bordered table-striped table-hover">
                        <tbody>
                          <td width="80%">Net/Profit Before Tax</td>
                          <td><?=$sales_profit_before_tax?></td>
                        </tbody>
                      </table>
                      <table class="table table-bordered table-striped table-hover">
                        <tbody>
                          <td width="80%">Net/Profit After Tax</td>
                          <td><?=$sales_profit_after_tax?></td>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
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

<script type="text/javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
  }
</script>
