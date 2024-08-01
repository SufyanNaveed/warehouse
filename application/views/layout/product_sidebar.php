

<div class="col-md-3">
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title">Folders</h3>
      
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
          <?php if($this->user_model->has_module_permission("product")){ ?>
          <li <?php if($this->uri->segment(1)=='product') echo "class='active'"; ?>><a href="<?=base_url('product')?>"><i class="fa fa-product-hunt"></i> Products</a></li>          
          <?php } ?>
          <?php if($this->user_model->has_module_permission("stock")){ ?>
          <li <?php if($this->uri->segment(1)=='stock') echo "class='active'"; ?>><a href="<?=base_url('stock')?>"><i class="fa fa-archive"></i> Manage Stock</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("category")){ ?>
          <li <?php if($this->uri->segment(1)=='category') echo "class='active'"; ?>><a href="<?=base_url('category')?>"><i class="fa fa-angle-double-right"></i> Cateogry</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("subcategory")){ ?>
          <li <?php if($this->uri->segment(1)=='subcategory') echo "class='active'"; ?>><a href="<?=base_url('subcategory')?>"><i class="fa fa-angle-right"></i> Subcategory</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("uqc")){ ?>
          <li <?php if($this->uri->segment(1)=='uqc') echo "class='active'"; ?>><a href="<?=base_url('uqc')?>"><i class="fa fa-dot-circle-o"></i> UQC</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("brand")){ ?>
          <li <?php if($this->uri->segment(1)=='brand') echo "class='active'"; ?>><a href="<?=base_url('brand')?>"><i class="fa fa-bolt"></i> Brand</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("import")){ ?>
          <li <?php if($this->uri->segment(2)=='import') echo "class='active'"; ?>><a href="<?=base_url('product/import')?>"><i class="fa fa-upload"></i> Medicine Import</a></li>
          <?php } ?>
        </ul>
    </div>
    <!-- /.box-body -->
  </div>
</div>