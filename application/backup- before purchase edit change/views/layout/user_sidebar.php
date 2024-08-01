<div class="col-md-3">
  	<div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Folders</h3>

          <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body no-padding">
          	<ul class="nav nav-pills nav-stacked">
          		  <?php if($this->user_model->has_module_permission("role")){ ?>
                <li  <?php if($this->uri->segment(2)=='index' || $this->uri->segment(2)=='add_role' || $this->uri->segment(2)=='edit_role') echo "class='active'"; ?>><a href="<?=base_url('user/index')?>"><i class="fa fa-users"></i> Roles
                <?php } ?>
                <?php if($this->user_model->has_module_permission("permission")){ ?>
                  <!-- <span class="label label-primary pull-right">12</span> --></a></li>
                <li <?php if($this->uri->segment(2)=='permission') echo "class='active'"; ?>><a href="<?=base_url('user/permission')?>"><i class="fa fa-shield"></i> Permission</a></li>
                <?php } ?>
            	 <?php if($this->user_model->has_module_permission("user")){ ?>
                <li <?php if($this->uri->segment(2)=='users' || $this->uri->segment(2)=='add_user' || $this->uri->segment(2)=='edit_user') echo "class='active'"; ?>><a href="<?=base_url('user/users')?>"><i class="fa fa-user"></i> Users</a></li>
                <?php } ?>
          	</ul>
        </div>
        <!-- /.box-body -->
    </div>
</div>