<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('layout/header');
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h5>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('auth/dashboard'); ?>"><i class="fa fa-dashboard"></i> <!-- Dashboard --> <?php echo $this->lang->line('header_dashboard'); ?></a></li>
          <li><a href="<?php echo base_url('composite/index'); ?>">Composite</a></li>
          <li class="active">Add Composite Product</li>
        </ol>
      </h5>    
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <?php $this->load->view('suggestion.php'); ?>
      <!-- right column -->
      <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><!-- Add New Branch --> <?php echo "Add New Composite Product";//$this->lang->line('branch_label_addbranch'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form role="form" id="form" method="post" action="<?php echo base_url('composite/add');?>">
                <div class="box no-border">
                  <div class="box-header">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="type">Composite Product Name</label>
                          <input type="text" class="form-control pull-right" id="composite_product_name" name="composite_product_name" placeholder="Product Name" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="type">Description</label>
                          <input type="text" class="form-control pull-right" id="description" name="description" placeholder="Type Description">
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body no-padding">
                    <table class="table table-striped entry_table" id="entry_table" name="entry_table">
                      <thead>
                        <tr>
                          <th style="width: 25%">Ref No</th>
                          <th style="width: 25%">Product</th>
                          <th style="width: 25%">Quantity</th>
                          <th style="width: 25%">Action</th>
                        </tr>
                      </thead>
                      <tbody class="entry_table_body">

                        <tr>
                          <td>1</td>
                          <td>
                            <select class="form-control select2" name="product_id" id="product_id">
                              <option value="">Select</option>
                              <?php
                                  foreach ($products as $row) {
                              ?>
                                  <option value="<?php echo $row->product_id; ?>">
                                    <?php echo $row->name.'('.$row->code.')'; ?>
                                  </option>
                              <?php  
                                 }
                              ?>
                            </select>
                          </td>
                          <td>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="0">
                          </td>
                          <td>
                            <a title="Add Row" id="addrow" class="btn btn-xs btn-success addRow">
                                <span class="glyphicon glyphicon-plus" ></span> 
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-sm-12">
                  <div class="box-footer">
                    <input type="hidden" name="entry_data" id="entry_data" value="">
                    <button type="submit" id="submit" class="btn btn-info">&nbsp;&nbsp;&nbsp;<?php echo "Save"; ?>&nbsp;&nbsp;&nbsp;</button>
                    <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('composite')"><?php echo "Cancel"; ?></span>
                  </div>
                </div>
              </form>
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
<script>
  $(document).ready(function(){

    var j = 2;
    $('select').select2();

    function add_row(data){


        var select_product = "";
        select_product += '<select class="form-control select2 product_id" id="product_id" name="product_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>';
        select_product += '<option value="">Select</option>';

        for(var a = 0; a<data.length ; a++){
          select_product += '<option value="'+data[a].product_id+'">'+data[a].name+'('+data[a].code+')</option>';  
        }

        select_product += '</select>';

        var text_quantity ='<input type="number" class="form-control" id="quantity" name="quantity" value="0">';
        var button_delete = '<a title="Delete" id="deleterow" class="btn btn-xs btn-danger deleteRow" >'
                           +   '<span class="glyphicon glyphicon-trash"></span>'
                           +'</a>'
        var button_add_row = '<a title="Delete" id="addrow" class="btn btn-xs btn-success addRow" >'
                            +   '<span class="glyphicon glyphicon-plus"></span>'
                            +'</a>';
        
        var newRow = $("<tr>");

        var cols = "";
        cols += '<td>'
                + j
                + '<input type="hidden" name="id" name="id" value="'+j+'">';
                + '</td>';
        cols += '<td>'
              +  select_product
              +'</td>';
        cols += '<td>'
              +  text_quantity 
              +'</td>';
        cols += '<td>'
              +  button_delete
              + '<br/>'
              +  button_add_row
              +'</td>';      
        cols += '</tr>';

        newRow.append(cols);
        $("table.entry_table").append(newRow);
        $('.select2').select2({});

        // var table_data = JSON.stringify(product_data);
        // $('#table_data').val(table_data);
        j++;
    }

    $("table.entry_table").on("click", "a.deleteRow", function (event) {

        if($("table.entry_table tr").length == 2){
          alert('You can not delete first record.');
        }else if($(this).closest("tr").is(":last-child")){
          var row = $(this).closest("tr");
          var prev = row.prev();
          prev.find(".addRow").toggle();
          row.remove();    
        }else{
          var row = $(this).closest("tr");
          row.remove();
        }

    });

    $("table.entry_table").on("click", "a.addRow", function (event) {
        $(this).closest("tr").find('.addRow').toggle();
        $.ajax({
          url: "<?php echo base_url('product/getProducts') ?>",
          type: "GET",
          dataType: "JSON",
          success: function(data){
              add_row(data);
          }
        });
    });
   

  }); 
</script>
<script>
  $(document).ready(function(){

    var entry_data = new Array();
    $("#submit").click(function(event){

      $('.entry_table_body > tr').each(function (i, row){
        $row = $(row);
        entry_data[i] = {};
        entry_data[i].product_id = $row.find('#product_id').val();
        entry_data[i].quantity = $row.find('#quantity').val();      
      });

      $("#entry_data").val(JSON.stringify(entry_data));
      
      if(entry_data.length == 0){
        alert("List is empty");
        return false;
      }else{
        return true;
      }

    });

}); 
</script>