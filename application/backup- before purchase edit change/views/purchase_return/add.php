<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layout/header');
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('purchase_return'); ?>"><?php echo $this->lang->line('header_purchase_return'); ?></a></li>
          <li class="active"><?php echo $this->lang->line('purchase_return_add_purchase_return'); ?></li>
        </ol>
      </h5>    
    </section>

  <!-- Main content -->
    <section class="content">
      <div class="row">
      <!-- right column -->
      <?php $this->load->view('suggestion.php'); ?>
        <div class="col-sm-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $this->lang->line('purchase_return_add_new_purchase_return'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <form role="form" id="form" method="post" action="<?php echo base_url('purchase_return/addPurchaseReturn');?>">
                <div class="col-sm-6">
                  <div class="well">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="date"><?php echo $this->lang->line('purchase_date'); ?><span class="validation-color">*</span></label>
                          <input type="text" class="form-control datepicker" id="date" name="date" value="<?php echo date("Y-m-d");  ?>">
                          <span class="validation-color" id="err_date"><?php echo form_error('date'); ?></span>
                        </div>
                        <div class="form-group">
                          <label for="discount">Discount</label>
                          <input class="form-control" id="discount" name="discount">
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <?php
                          if($reference_no==null){
                              $no = sprintf('%06d',intval(1));
                          }
                          else{
                            foreach ($reference_no as $value) {
                              $no = sprintf('%06d',intval($value->id)+1); 
                            }
                          }
                        ?>
                        <div class="form-group">
                          <label for="reference_no"><?php echo $this->lang->line('purchase_reference_no'); ?><span class="validation-color">*</span></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="PR-<?php echo $no;?>" readonly>
                          <span class="validation-color" id="err_reference_no"><?php echo form_error('reference_no'); ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="well">
                    <div class="row">
                      <div class="col-sm-6">
                        <!-- <div class="form-group">
                          <label for="supplier">Supplier<span class="validation-color">*</span></label>
                          <select class="form-control select2" id="supplier" name="supplier" style="width: 100%;">
                            <option value=""><?php echo $this->lang->line('product_select'); ?></option>
                            <?php
                              //foreach ($supplier as $row) {
                            ?>
                                <option value="<?php // echo $row->id?>"><?php // echo $row->first_name." ".$row->last_name?></option>;
                            <?php
                              //}
                            ?>
                          </select>
                           <input type="hidden" name="supplier_state_id" id="supplier_state_id">
                          <span class="validation-color" id="err_supplier"><?php echo form_error('supplier'); ?></span>
                        </div> -->
                        <div class="form-group">
                          <label for="purchase_id">Supplier<span class="validation-color">*</span></label>
                          <select class="form-control select2" id="purchase_id" name="purchase_id" style="width: 100%;">
                            <option value=""><?php echo $this->lang->line('product_select'); ?></option>
                            <?php
                              foreach ($receipts as $row) {
                            ?>
                                <option value="<?php echo $row->purchase_id?>"
                                  <?php 
                                    if(isset($purchase_id) && ($row->purchase_id == $purchase_id))
                                      echo ' selected';
                                  ?> 
                                ><?php echo $row->invoice_no." - ".$row->supplier_name?></option>;
                            <?php
                              }
                            ?>
                          </select>
                          <input type="hidden" name="supplier_state_id" id="supplier_state_id" value="<?php if(isset($supplier_state_id)) echo $supplier_state_id; ?>">
                          <input type="hidden" name="warehouse_state_id" id="warehouse_state_id" value="<?php if(isset($warehouse_state_id)) echo $warehouse_state_id; ?>">
                          <input type="hidden" name="warehouse" id="warehouse" value="<?php if(isset($warehouse_id)) echo $warehouse_id; ?>">
                          <input type="hidden" name="supplier" id="supplier" value="<?php if(isset($supplier_id)) echo $supplier_id; ?>">
                          <input type="hidden" name="biller" id="biller" value="<?php if(isset($biller_id)) echo $biller_id; ?>">
                          <input type="hidden" name="purchase_invoice_number" id="purchase_invoice_number" value="<?php if(isset($purchase_invoice_number)) echo $purchase_invoice_number; ?>">

                          <span class="validation-color" id="err_supplier"><?php echo form_error('supplier'); ?></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- 
                <div class="col-sm-12">
                  <div class="well">
                    <div class="row">
                      <div class="col-sm-12 search_product_code">
                        <input id="input_product_code" class="form-control" autofocus="off" type="text" name="input_product_code" placeholder="Enter Product Code/Name/SKU OR Search with Barcode Scanner" >
                      </div>
                      <div class="col-sm-8">
                        <span class="validation-color" id="err_product"></span>
                      </div>
                    </div>
                  </div>
                </div> -->

                <div class="col-sm-12">
                  <div class="form-group">
                    <label><?php echo $this->lang->line('purchase_inventory_items'); ?></label>
                    
                    <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                      <thead>
                        <tr>
                          <th style="width: 20px;"><img src="<?php  echo base_url(); ?>assets/images/bin1.png" /></th>
                          <th class="span2" width="15%"><?php echo $this->lang->line('purchase_product_description'); ?></th>
                          <th class="span2" width="10%"><?php echo $this->lang->line('product_unit'); ?></th>
                          <th class="span2" width="10%">Qty</th>
                          <th class="span2" width="10%"><?php echo $this->lang->line('product_price'); ?></th>
                          <th class="span2" width="10%"><?php echo $this->lang->line('header_discount'); ?></th>
                          <th class="span2"><?php echo $this->lang->line('purchase_taxable_value'); ?></th>
                          <th class="span2" width="10%">IGST</th>
                          <th class="span2" width="10%">CGST</th>
                          <th class="span2" width="10%">SGST</th>
                          <th class="span2" width="5%">Inclusive ?</th>
                          <th class="span2" width="10%"><?php echo $this->lang->line('purchase_total'); ?></th>
                        </tr>
                      </thead>
                      <tbody id="product_table_body">
                          <?php
                            $i=0;
                            $tot=0;
                            if(isset($items)){
                            foreach ($items as  $key) {

                              foreach ($purchase_returned_items as $row) {
                                if($row->product_id == $key->product_id){
                                  $key->quantity = $key->quantity - $row->quantity;
                                }
                              }
                          ?>
                          <tr>
                            <td>
                              <a class='deleteRow1'> <img src='<?php  echo base_url(); ?>assets/images/bin3.png' /> </a><input type='hidden' name='id' id='id' value="<?php echo $i ?>">
                              <input type='hidden' name='product_id' id='product_id' value="<?php echo $key->product_id ?>">
                            </td>
                            <td><?php echo $key->name; ?><br>HSN: <?php echo $key->hsn_sac_code; ?></td>
                            <td><?php echo $key->unit ?></td>
                            <td>
                              <input type="number" class="form-control text-center" value="<?php echo $key->quantity ?>" data-rule="quantity" name='qty' id='qty' min="1" max="<?php echo $key->quantity ?>">
                            </td>
                            <td>
                              <input type='number' step='0.01' class='form-control text-right' name='price' id='price' value='<?php echo $key->cost ?>'>
                              <span id='sub_total' class='pull-right'><?php echo round($key->gross_total); ?></span>
                              <input type='hidden' class='form-control' style='' value='<?php echo $key->gross_total ?>' name='linetotal' id='linetotal' readonly>
                            </td>
                            <td align="right">
                              <input type="hidden" id="discount_value" name="discount_value" value="<?php echo $key->discount_value;?>">
                              <input type="hidden" id="hidden_discount" name="hidden_discount" value="<?php echo $key->discount;?>">
                              <div class="form-group">
                                <select class="form-control select2" id="item_discount" name="item_discount" style="width: 100%;">
                                  <option value=""><?php echo $this->lang->line('product_select'); ?></option>
                                  <?php foreach ($discount as $dis) {
                                  ?>
                                    <option value='<?php echo $dis->discount_id ?>' 
                                  <?php if($key->discount_id == $dis->discount_id){echo "selected";} ?>>
                                    <?php echo $dis->discount_name.'('.$dis->discount_value.')' ?></option>
                                  <?php
                                    } 
                                  ?>
                                </select>
                              </div>
                            </td>
                            <td align="right">
                              <span id="taxable_value">
                                <?php //echo $key->gross_total - $key->discount 
                                  if($key->tax_type == "Inclusive")
                                    echo ($key->gross_total-$key->discount) - $key->igst_tax - $key->cgst_tax - $key->sgst_tax;
                                  else 
                                    echo ($key->gross_total-$key->discount);
                                ?>
                                </span>
                            </td>
                            <?php if($warehouse_state_id == $supplier_state_id){ ?>
                            <td>
                              <input type="number" step="0.01" name="igst" id="igst" class="form-control" max="100" min="0" value="<?php echo $key->igst; ?>" disabled>
                              <input type="hidden" name="igst_tax" id="igst_tax" class="form-control" value="<?php echo $key->igst_tax; ?>">
                              <span id="igst_tax_lbl" class="pull-right" style="color:red;"><?php echo $key->igst_tax; ?></span>
                            </td>
                            <td>
                              <input type="number" step="0.01" name="cgst" id="cgst" class="form-control" max="100" min="0" value="<?php echo $key->cgst; ?>">
                              <input type="hidden" name="cgst_tax" id="cgst_tax" class="form-control" value="<?php echo $key->cgst_tax; ?>">
                              <span id="cgst_tax_lbl" class="pull-right" style="color:red;"><?php echo $key->cgst_tax; ?></span>
                            </td>
                            <td>
                              <input type="number" step="0.01" name="sgst" id="sgst" class="form-control" max="100" min="0" value="<?php echo $key->sgst; ?>">
                              <input type="hidden" name="sgst_tax" id="sgst_tax" class="form-control" value="<?php echo $key->sgst_tax; ?>">
                              <span id="sgst_tax_lbl" class="pull-right" style="color:red;"><?php echo $key->sgst_tax; ?></span>
                            </td>
                            <?php }else{
                            ?>
                            <td>
                              <input type="number" step="0.01" name="igst" id="igst" class="form-control" max="100" min="0" value="<?php echo $key->igst; ?>">
                              <input type="hidden" name="igst_tax" id="igst_tax" class="form-control" value="<?php echo $key->igst_tax; ?>">
                              <span id="igst_tax_lbl" class="pull-right" style="color:red;"><?php echo $key->igst_tax; ?></span>
                            </td>
                            <td>
                              <input type="number" step="0.01" name="cgst" id="cgst" class="form-control" max="100" min="0" value="<?php echo $key->cgst; ?>" disabled>
                              <input type="hidden" name="cgst_tax" id="cgst_tax" class="form-control" value="<?php echo $key->cgst_tax; ?>">
                              <span id="cgst_tax_lbl" class="pull-right" style="color:red;"><?php echo $key->cgst_tax; ?></span>
                            </td>
                            <td>
                              <input type="number" step="0.01" name="sgst" id="sgst" class="form-control" max="100" min="0" value="<?php echo $key->sgst; ?>" disabled>
                              <input type="hidden" name="sgst_tax" id="sgst_tax" class="form-control" value="<?php echo $key->sgst_tax; ?>">
                              <span id="sgst_tax_lbl" class="pull-right" style="color:red;"><?php echo $key->sgst_tax; ?></span>
                            </td>
                            <?php
                              } 
                            ?>
                            <td>
                              <input type="checkbox" id="tax_type" name="tax_type" value="Inclusive"
                              <?php 
                                if($key->tax_type == "Inclusive") echo ' checked';
                              ?> 
                              >
                            </td>
                            <td align="right">
                              <input type="text" class="form-control text-right" id="product_total" name="product_total" 
                              value="<?php 
                                      //echo $key->gross_total - $key->discount + $key->igst_tax + $key->cgst_tax + $key->sgst_tax;
                                      if($key->tax_type == "Inclusive")
                                      {
                                        echo ($key->gross_total - $key->discount);    
                                      }
                                      else
                                      {
                                        echo ($key->gross_total - $key->discount + $key->igst_tax + $key->cgst_tax + $key->sgst_tax); 
                                      } 
                                      ?>" readonly>
                            </td>
                          </tr>
                          <?php
                                  $product_data[$i] = $key->product_id;
                                  //array_push($product_data,$product);
                                  $i++;
                                  if($key->tax_type == "Inclusive")
                                  { 
                                    $tot += $key->gross_total - $key->igst_tax - $key->cgst_tax - $key->sgst_tax; 
                                  }
                                  else
                                  {
                                    $tot += $key->gross_total; 
                                  } 
                                  //$tot += $key->gross_total;
                                }

                                $grandtotal = $data[0]->total;
                                $product = json_encode($product_data);
                              }
                            //echo "<pre>";
                            //print_r($product_data);
                            

                          ?>
                      </tbody>
                    </table>
                    <input type="hidden" name="total_value" id="total_value" value="">
                    <input type="hidden" name="total_discount" id="total_discount" value="">
                    <input type="hidden" name="total_tax" id="total_tax" value="">
                    <input type="hidden" name="grand_total" id="grand_total" value="">
                    <input type="hidden" name="table_data" id="table_data" value="">
                    
                    <table class="table table-striped table-bordered table-condensed table-hover">
                      <tr>
                        <td align="right" width="80%"><?php echo $this->lang->line('purchase_total_value'); ?></td>
                        <td align='right'><?php echo $this->session->userdata('symbol');?><span id="totalValue">0.00</span></td>
                      </tr>
                      <tr>
                        <td align="right"><?php echo $this->lang->line('purchase_total_discount'); ?></td>
                        <td align='right'><?php echo $this->session->userdata('symbol');?><span id="totalDiscount">0.00</span></td>
                      </tr>
                      <tr>
                        <td align="right"><?php echo $this->lang->line('purchase_total_tax'); ?></td>
                        <td align='right'><?php echo $this->session->userdata('symbol');?><span id="totalTax">0.00</span></td>
                      </tr>
                      <tr>
                        <td align="right"><?php echo $this->lang->line('purchase_total'); ?></td>
                        <td align='right'><?php echo $this->session->userdata('symbol');?><span id="grandTotal">0.00</span></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                      <label for="note"><?php echo $this->lang->line('purchase_note'); ?></label>
                      <textarea class="form-control" id="note" name="note"><?php echo set_value('details'); ?></textarea>
                      <span class="validation-color" id="err_details"><?php echo form_error('details'); ?></span>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="box-footer">
                    <button type="submit" id="submit" class="btn btn-info">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line('product_add'); ?>&nbsp;&nbsp;&nbsp;</button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('purchase_return')"><?php echo $this->lang->line('product_cancel'); ?></span>
                  </div>
                </div>
              </form>
          </div>
          <!-- /.box-body -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php
    $this->load->view('layout/product_footer');
    include('assign_warehouse.php');
  ?>
<script>
 $(document).ready(function(){
    var i = <?php echo $i++; ?>;
    var product_data = new Array();
    var counter = <?php echo count($items); ?>;
    var mapping = { };
    $(function(){
            $('#input_product_code').autoComplete({
                minChars: 1,
                cache: false,
                source: function(term, suggest){
                    term = term.toLowerCase();
                    var warehouse_id = $('#warehouse').val();
                    $.ajax({
                      url: "<?php echo base_url('purchase_return/getBarcodeProducts') ?>/"+term+'/'+warehouse_id,
                      type: "GET",
                      dataType: "json",
                      success: function(data){
                        var suggestions = [];
                        for(var i = 0; i < data.length; ++i) {
                            suggestions.push(data[i].code+' - '+data[i].name);
                            mapping[data[i].code] = data[i].product_id;
                        }

                        suggest(suggestions);
                        }
                    });
                },
                onSelect: function(event, ui) {
                  var str = ui.split(' ');
                  var warehouse_id = $('#warehouse').val();
                  $.ajax({
                    url:"<?php echo base_url('purchase_return/getProductUseCode') ?>/"+mapping[str[0]]+'/'+warehouse_id,
                    type:"GET",
                    dataType:"JSON",
                    success: function(data){
                      add_row(data);
                      $('#input_product_code').val('');
                    }
                  });
                } 
            });
            
      });
    function add_row(data){
      var flag = 0;
      $("table.product_table").find('input[name^="product_id"]').each(function () {
                if(data[0].product_id  == +$(this).val()){
                  flag = 1;
                }
            });
            if(flag == 0){
              var id = data[0].product_id;
              var code = data[0].code;
              var name = data[0].name;
              var hsn_sac_code = data[0].hsn_sac_code;
              var price = data[0].cost;
              var tax_id = data[0].tax_id;
              var tax_value = data[0].tax_value;
              var igst = data[0].igst;
              var cgst = data[0].cgst;
              var sgst = data[0].sgst;
              if(tax_value==null){
                tax_value = 0;
              }
              var product = { "product_id" : id,
                              "price" : price
                            };
              product_data[i] = product;

              length = product_data.length - 1 ;
            
              var select_discount = "";
              select_discount += '<div class="form-group">';
              select_discount += '<select class="form-control select2" id="item_discount" name="item_discount" style="width: 100%;">';
              select_discount += '<option value="">Select</option>';
                for(a=0;a<data['discount'].length;a++){
                  select_discount += '<option value="' + data['discount'][a].discount_id + '">' + data['discount'][a].discount_name+'('+data['discount'][a].discount_value +'%)'+ '</option>';
                }
              select_discount += '</select></div>';
              var warehouse_state_id = $('#warehouse_state_id').val();
              var supplier_state_id = $('#supplier_state_id').val();
              //alert('w '+ warehouse_state_id + " s "+supplier_state_id);
                           
              if(warehouse_state_id==supplier_state_id)
              {
                var igst_input ='<input name="igst" id="igst" class="form-control" value="0" readonly><input type="hidden" name="igst_tax" id="igst_tax" class="form-control" value="0">';
                var cgst_input ='<input type="number" step="0.01" name="cgst" id="cgst" class="form-control" max="100" min="0" value="'+cgst+'"><input type="hidden" name="cgst_tax" id="cgst_tax" class="form-control" value="'+cgst+'">';
                var sgst_input ='<input type="number" step="0.01" name="sgst" id="sgst" class="form-control" max="100" min="0" value="'+sgst+'"><input type="hidden" name="sgst_tax" id="sgst_tax" class="form-control" value="'+sgst+'">';
              }
              else
              {
                var igst_input ='<input type="number" step="0.01" name="igst" id="igst" class="form-control" max="100" min="0" value="'+igst+'"><input type="hidden" name="igst_tax" id="igst_tax" class="form-control" value="'+igst+'">';
                var cgst_input ='<input name="cgst" id="cgst" class="form-control" value="0" readonly><input type="hidden" name="cgst_tax" id="cgst_tax" class="form-control" value="0">';
                var sgst_input ='<input name="sgst" id="sgst" class="form-control" value="0" readonly><input type="hidden" name="sgst_tax" id="sgst_tax" class="form-control" value="0">';
              }
              var color;
              data[0].quantity>10?color="green":color="red";
              var newRow = $("<tr>");
              var cols = "";
              cols += "<td><a class='deleteRow'> <img src='<?php  echo base_url(); ?>assets/images/bin3.png' /> </a><input type='hidden' name='id' name='id' value="+i+"><input type='hidden' name='product_id' name='product_id' value="+id+"></td>";
              cols += "<td>"+name+"<br>HSN:"+hsn_sac_code+"</td>";
              cols += "<td>"+data[0].unit+"</td>";
              cols += "<td>"
                      +"<input type='number' class='form-control text-center' value='0' data-rule='quantity' name='qty"+ counter +"' id='qty"+ counter +"' min='1'>" 
                      +"<span style='color:"+color+";'>Avai Qty: "+data[0].quantity+"</span>"
                    +"</td>";
              cols += "<td>" 
                        +"<span id='price_span'>"
                          +"<input type='number'step='0.01' min='1' class='form-control text-right' name='price"+ counter +"' id='price"+ counter +"' value='"+price
                        +"'>"
                        +"</span>"
                        +"<span id='sub_total' class='pull-right'></span>"
                        +"<input type='hidden' class='form-control text-right' style='' value='0.00' name='linetotal"+ counter +"' id='linetotal"+ counter +"'>"
                      +"</td>";
              cols += '<td>'
                      +'<input type="hidden" id="discount_value" name="discount_value">'
                      +'<input type="hidden" id="hidden_discount" name="hidden_discount">'
                      +select_discount
                    +'</td>';
              cols += '<td align="right"><span id="taxable_value"></span></td>';
              cols += '<td>'
                      +igst_input 
                      +'<span id="igst_tax_lbl" class="pull-right" style="color:red;"></span>'
                    +'</td>';
              cols += '<td>'
                      +cgst_input
                      +'<span id="cgst_tax_lbl" class="pull-right" style="color:red;"></span>'
                    +'</td>';
              cols += '<td>'
                      +sgst_input
                      +'<span id="sgst_tax_lbl" class="pull-right" style="color:red;"></span>'
                    +'</td>';
              cols += '<td>'
                      +'<input type="checkbox" id="tax_type" name="tax_type" value="Inclusive">'
                    +'</td>'; 
              cols += '<td><input type="text" class="form-control text-right" id="product_total" name="product_total" readonly></td>';
              cols += "</tr>";
              counter++;

              newRow.append(cols);
              $("table.product_table").append(newRow);
              var table_data = JSON.stringify(product_data);
              $('#table_data').val(table_data);
              i++;
            }
            else{
              $('#err_product').text('Product Already Added').animate({opacity: '0.0'}, 2000).animate({opacity: '0.0'}, 1000).animate({opacity: '1.0'}, 2000);
            }
    }
    $("table.product_table").on("click", "a.deleteRow", function (event) {
        deleteRow($(this).closest("tr"));
        $(this).closest("tr").remove();
        calculateGrandTotal();
    });

    $("table.product_table").on("click", "a.deleteRow1", function (event) {
        deleteRow($(this).closest("tr"));
        $(this).closest("tr").remove();
        calculateGrandTotal();
    });

    function deleteRow(row){
      var id = +row.find('input[name^="id"]').val();
      var array_id = product_data[id].product_id;
      //product_data.splice(id, 1);
      product_data[id] = null;
      //alert(product_data);
      var table_data = JSON.stringify(product_data);
      $('#table_data').val(table_data);
    }

    $(window).on('load', function() {
      $('tbody#product_table_body > tr').each(function() {
        // alert();
        // alert($(this).find("#id").val());
        calculateRow($(this));
        calculateDiscountTax($(this));
        calculateGrandTotal();
      });
    });

    $("table.product_table").on("change", 'input[name^="price"], input[name^="qty"],input[name^="igst"],input[name^="cgst"],input[name^="sgst"],input[name^="tax_type"]', function (event) {
        calculateRow($(this).closest("tr"));
        calculateDiscountTax($(this).closest("tr"));
        calculateGrandTotal();
    });

    $("table.product_table").on("change",'#item_discount',function (event) {
      var row = $(this).closest("tr");
      var discount = +row.find('#item_discount').val();
      if(discount != ""){
        $.ajax({
          url: '<?php echo base_url('purchase/getDiscountValue/') ?>'+discount,
          type: "GET",
          data:{
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          datatype: JSON,
          success: function(value){
            data = JSON.parse(value);
            row.find('#discount_value').val(data[0].discount_value);
            calculateDiscountTax(row,data[0].discount_value);
            calculateGrandTotal();
          }
        });
      }
      else{
        row.find('#discount_value').val('0');
        calculateDiscountTax(row,0);
        calculateGrandTotal();
      }
    });
    function calculateDiscountTax(row,data = 0,data1 = 0){
      var discount;
      if(data == 0 ){
        discount = +row.find('#discount_value').val();
      }
      else{
        discount = data;
      }
      var sales_total = +row.find('input[name^="linetotal"]').val();
      var total_discount = sales_total*discount/100;
      var taxable_value = sales_total - total_discount;
      row.find('#taxable_value').text(taxable_value.toFixed(2));
      var tax_type = row.find("input[name^='tax_type']:checked").val();  
      var igst = +row.find("#igst").val();
      var cgst = +row.find("#cgst").val();
      var sgst = +row.find("#sgst").val();
      if(tax_type =="Inclusive")
      {
        row.find('#product_total').val(taxable_value);

        var igst_tax = taxable_value - (taxable_value / ((igst /100) + 1 ));
        var cgst_tax = taxable_value - (taxable_value / ((cgst /100) + 1 ));
        var sgst_tax = taxable_value - (taxable_value / ((sgst /100) + 1 ));      
        var tax = igst_tax+cgst_tax+sgst_tax;
      }
      else
      {       
        tax_type = "Exclusive";
        var igst_tax = taxable_value*igst/100;
        var cgst_tax = taxable_value*cgst/100;
        var sgst_tax = taxable_value*sgst/100;
        var tax = igst_tax+cgst_tax+sgst_tax;
        row.find('#product_total').val(taxable_value+tax);
      } 
      row.find('#igst_tax').val(igst_tax);
      row.find('#igst_tax_lbl').text(igst_tax.toFixed(2));
      row.find('#cgst_tax').val(cgst_tax);
      row.find('#cgst_tax_lbl').text(cgst_tax.toFixed(2));
      row.find('#sgst_tax').val(sgst_tax);
      row.find('#sgst_tax_lbl').text(sgst_tax.toFixed(2));

      row.find('#hidden_discount').val(total_discount);

      var key = +row.find('input[name^="id"]').val();
      product_data[key].discount = total_discount;
      product_data[key].discount_value = +row.find('#discount_value').val();
      product_data[key].discount_id = +row.find('#item_discount').val();
      product_data[key].tax_type = tax_type;
      product_data[key].igst = igst;
      product_data[key].igst_tax = igst_tax;
      product_data[key].cgst = cgst;
      product_data[key].cgst_tax = cgst_tax;
      product_data[key].sgst = sgst;
      product_data[key].sgst_tax = sgst_tax;
      var table_data = JSON.stringify(product_data);
      $('#table_data').val(table_data);
    }

    product_data  = [];
    function calculateRow(row) {
      // alert(+row.find('input[name^="id"]').val());
      // var product_data = new Array();
      var key = +row.find('input[name^="id"]').val();
      var product_id = +row.find('input[name^="product_id"]').val();
      var price = +row.find('input[name^="price"]').val();
      var qty = +row.find('input[name^="qty"]').val();
      row.find('input[name^="linetotal"]').val((price * qty).toFixed(2));
      row.find('#sub_total').text((price * qty).toFixed(2));
    
      if(product_data[key]==null){
        var temp = {
          "product_id" : product_id,
          "price" : price,
          "quantity" : qty,
          "total" : (price * qty).toFixed(2)
        };
        product_data[key] = temp;
      }

      product_data[key].quantity = qty;
      
      product_data[key].total = (price * qty).toFixed(2);
      var table_data = JSON.stringify(product_data);
      $('#table_data').val(table_data);
    }
    
     function calculateGrandTotal() {

      var totalValue = 0;
      var totalDiscount = 0;
      var grandTax = 0;
      var grandTotal = 0;
      $("table.product_table").find('input[name^="linetotal"]').each(function () {
        totalValue += +$(this).val();
      });
      $("table.product_table").find('input[name^="hidden_discount"]').each(function () {
        totalDiscount += +$(this).val();
      });
      $("table.product_table").find('input[name^="igst_tax"]').each(function () {
        grandTax += +$(this).val();
      });
      $("table.product_table").find('input[name^="cgst_tax"]').each(function () {
        grandTax += +$(this).val();
      });
      $("table.product_table").find('input[name^="sgst_tax"]').each(function () {
        grandTax += +$(this).val();
      });
      $("table.product_table").find('input[name^="product_total"]').each(function () {
        grandTotal += +$(this).val();
      });
      $('#totalValue').text(totalValue);
      $('#total_value').val(totalValue);
      $('#totalDiscount').text(totalDiscount.toFixed(2));
      $('#total_discount').val(totalDiscount.toFixed(2));
      $('#totalTax').text(grandTax.toFixed(2));
      $('#total_tax').val(grandTax.toFixed(2));
      $('#grandTotal').text(grandTotal.toFixed(2));
      $('#grand_total').val(grandTotal.toFixed(2));
    }
});

</script>

<!-- datepicker  -->
<script src="<?php echo base_url('assets/jquery/jquery-3.1.1.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script type="text/javascript">
$(document).ready(function() {

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "auto",
        todayBtn: true,
        todayHighlight: true,  
    });

});
</script>
<!-- close datepicker  -->

<script>
  $(document).ready(function(){

    $("#purchase_id").change(function(event){
      var purchase_id = $('#purchase_id :selected').val();
      
      //alert(user_id);
      var biller_state_id;
      var biller_id;
      var warehouse;
      $('#product_table_body').empty();
      $('#table_data').val('clear');
      $('#last_total').val('');
      $('#grand_total').val('');
      $('#grandtotal').text('0.00');
      $('#totaldiscount').text('0.00');
      $('#lasttotal').text('0.00');
      if(purchase_id==""){
        $("#err_purchase_id").text("Please select Receipt");
        $('#purchase_id').focus();
        return false;
      }
      else{
        $("#err_purchase_id").text("");

        window.location.href = "<?=base_url("purchase_return/add")?>/"+purchase_id
       
      }
    });
    $("#submit").click(function(event){
      var name_regex = /^[a-zA-Z]+$/;
      var sname_regex = /^[a-zA-Z0-9]+$/;
      var num_regex = /^[0-9]+$/;
      var date_regex = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
      var date = $('#date').val();
      var reference_no = $('#reference_no').val();
      var warehouse = $('#warehouse').val();
      var supplier = $('#supplier').val();
      var product = $('#product').val();
      var editor = $('#editor').val();
      var grand_total = $('#grand_total').val();
      var table_data = $('#table_data').val();
      var table_data_array = new Array();
      
      table_data_array = JSON.parse(table_data);
      for(i = 0 ; i < table_data_array.length ; i++){
        if(table_data_array[i] == "delete"){
          table_data_array[i] = existing_product_id[i];
        }
      }
      
      $('#table_data').val(JSON.stringify(table_data_array));

        if(date==null || date==""){
          $("#err_date").text("Please Enter Date");
          $('#date').focus();
          return false;
        }
        else{
          $("#err_date").text("");
        }
        if (!date.match(date_regex) ) {
          $('#err_date').text(" Please Enter Valid Date ");   
          $('#date').focus();
          return false;
        }
        else{
          $("#err_date").text("");
        }
//date codevalidation complite.

        if(warehouse==""){
          $("#err_warehouse").text("Please Enter Warehouse");
          $('#warehouse').focus();
          return false;
        }
        else{
          $("#err_warehouse").text("");
        }
//warehouse code validation complite.

        if(supplier==""){
          $("#err_supplier").text("Please Enter Supplier");
          $('#supplier').focus();
          return false;
        }
        else{
          $("#err_supplier").text("");
        }
//supplier code validation complite.

        if(grand_total=="" || grand_total==null || grand_total==0.00){
          $("#err_product").text("Please Select Product");
          $('#product').focus();
          return false;
        }
        else{
          $("#err_product").text("");
        }


    }); 

    $("#date").blur(function(event){
      var date = $('#date').val(); 
      var date_regex = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
      if(date==null || date==""){
          $("#err_date").text("Please Enter Date");
          $('#date').focus();
          return false;
        }
        else{
          $("#err_date").text("");
        }
        if (!date.match(date_regex) ) {
          $('#err_date').text(" Please Enter Valid Date ");   
          $('#date').focus();
          return false;
        }
        else{
          $("#err_date").text("");
        }
    });
    
    $("#biller").change(function(event){
      var biller = $('#biller').val();
      $('#product_table_body').empty();
      $('#table_data').val('');
      $('#grand_total').val('');
      if(warehouse==""){
        $("#err_warehouse").text("Please Enter Warehouse");
        $('#biller').focus();
        return false;
      }
      else{
        $("#err_warehouse").text("");
        $("#err_product").text("");
        $.ajax({
          url: "<?php echo base_url('purchase_return/getWarehouseStateId') ?>/"+biller,
          type: "GET",
          dataType: "json",
          success: function(data){
            
            $('#warehouse_state_id').val(data.warehouse_state_id);
            $('#warehouse').val(data.warehouse);
            //$('#warehouse').val(data.warehouse);
          }
        });
      }
    });
    /*$("#biller").change(function(event){
      var biller = $('#biller').val();
      if(biller==""){
        $("#err_biller").text("Please Enter Biller");
        $('#biller').focus();
        return false;
      }
      else{
        $.ajax({
          url: "<?php// echo base_url('purchase/getWarehouseState') ?>/"+biller,
          type: "GET",
          dataType: "json",
          success: function(data){
            $('#biller_state_id').val(data.state);
            $('#warehouse').val(data.warehouse);
          }
        });
      }
    });*/
    // $("#warehouse").change(function(event){
    //   var warehouse = $('#warehouse').val();

    //   if(warehouse==""){
    //     $("#err_warehouse").text("Please Enter Warehouse");
    //     $('#warehouse').focus();
    //     return false;
    //   }
    //   else{
    //     $.ajax({
    //       url: "<?php echo base_url('purchase_return/getWarehouseState') ?>/"+warehouse,
    //       type: "GET",
    //       dataType: "json",
    //       success: function(data){
    //         alert(data.warehouse_address[0].warehouse_state_id);
    //         $('#warehouse_state_id').val(data.warehouse_address[0].warehouse_state_id);
    //         //$('#warehouse').val(data.warehouse);
    //       }
    //     });
    //   }
    // });
    $("#supplier").change(function(event){
      var supplier = $('#supplier').val();
      if(supplier==""){
        $("#err_supplier").text("Please Enter Supplier");
        $('#supplier').focus();
        return false;
      }
      else{
        $.ajax({
          url: "<?php echo base_url('purchase/getSupplierState') ?>/"+supplier,
          type: "GET",
          dataType: "text",
          success: function(data){
            $('#supplier_state_id').val(data);
          }
        });
        $("#err_supplier").text("");
      }
    });
    $("#product").blur(function(event){
      var sname_regex = /^[a-zA-Z0-9]+$/;
      var product = $('#product').val();
      if(product==null || product==""){
        $("#err_product").text("Please Enter Product Code/Name");
        $('#product').focus();
        return false;
      }
      else{
        $("#err_product").text("");
      }
      if (!date.match(sname_regex) ) {
        $('#err_product').text(" Please Enter Valid Product Code/Name ");  
        $('#product').focus(); 
        return false;
      }
      else{
        $("#err_product").text("");
      }
    });

  }); 
</script>
<script src="<?php echo base_url('assets/plugins/autocomplite/') ?>jquery.auto-complete.js"></script>