<div class="col-md-3">
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title">Folders</h3>
      
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
          <?php if($this->user_model->has_permission("company_setting")){ ?>
          <li <?php if($this->uri->segment(1)=='company_setting') echo "class='active'"; ?>><a href="<?=base_url('company_setting')?>"><i class="fa fa-sliders"></i> Company Setting</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("location")){ ?>
          <li <?php if($this->uri->segment(1)=='location') echo "class='active'"; ?>><a href="<?=base_url('location')?>"  target="_blank"><i class="fa fa-map-marker"></i> Location</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("branch")){ ?>
          <li <?php if($this->uri->segment(1)=='branch') echo "class='active'"; ?>><a href="<?=base_url('branch')?>"><i class="fa fa-dot-circle-o"></i> Branch</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("discount")){ ?>
          <li <?php if($this->uri->segment(1)=='discount') echo "class='active'"; ?>><a href="<?=base_url('discount')?>"><i class="fa fa-percent"></i> Discount</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("warehouse")){ ?>
          <li <?php if($this->uri->segment(1)=='warehouse') echo "class='active'"; ?>><a href="<?=base_url('warehouse')?>"><i class="fa fa-building-o"></i> Warehouse</a></li>
          <?php } ?>
          <?php if($this->user_model->has_permission("email_setting")){ ?>
          <li <?php if($this->uri->segment(1)=='email_setup') echo "class='active'"; ?>><a href="<?=base_url('email_setup')?>"><i class="fa fa-envelope-o"></i> Email Setup</a></li>
          <?php } ?>
          <?php if($this->user_model->has_permission("sms_setting")){ ?>
          <li <?php if($this->uri->segment(1)=='sms_setting') echo "class='active'"; ?>><a href="<?=base_url('sms_setting')?>"><i class="fa fa-commenting-o"></i> SMS Setting</a></li>
          <?php } ?>
          <?php if($this->user_model->has_permission("invoice_setup")){ ?>
          <li <?php if($this->uri->segment(1)=='invoice_setup') echo "class='active'"; ?>><a href="<?=base_url('invoice_setup')?>"><i class="fa fa-bars"></i> Invoice Setup</a></li>
          <?php } ?>
          <?php if($this->user_model->has_permission("list_payment_method")){ ?>
          <li <?php if($this->uri->segment(1)=='payment_method') echo "class='active'"; ?>><a href="<?=base_url('payment_method')?>"><i class="fa fa-credit-card"></i> Payment Method</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("expense_category")){ ?>
          <li <?php if($this->uri->segment(1)=='expense_category') echo "class='active'"; ?>><a href="<?=base_url('expense_category')?>"><i class="fa fa-usd"></i> Expense Category</a></li>
          <?php } ?>
          <?php if($this->user_model->has_module_permission("currency")){ ?>
          <li <?php if($this->uri->segment(1)=='currency') echo "class='active'"; ?>><a href="<?=base_url('currency')?>"><i class="fa fa-gg-circle"></i> Currency</a></li>
          <?php } ?>
        </ul>
    </div>
  </div>
</div>