<?php
  
  if(is_null($this->db->get('company_settings')->row())){
    $favico = "";  
  }else{
    $favico = $this->db->get('company_settings')->row()->favico;  
  }
  
  if(is_null($this->db->get('company_settings')->row())){
    $theme = "skin-blue";  
  }else{
    $theme = $this->db->get('company_settings')->row()->theme;  
  }
  
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Invento | Dashboard</title>
  <link rel="shortcut icon" type="image/png" href="<?php echo base_url().$favico; ?>" />

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- fullCalendar 2.2.5-->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/fullcalendar/fullcalendar.min.css">
  <!-- Graph -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <!-- Close Graph -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/fullcalendar/fullcalendar.print.css" media="print">
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/colorpicker/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>plugins/select2/select2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>dist/css/AdminLTE.min.css">
  <!-- jquery ui css -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/css'); ?>/jquery-ui.css">
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url('assets/'); ?>dist/css/skins/_all-skins.min.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url('assets/');?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/autocomplite/') ?>jquery.auto-complete.css">
  
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-3.1.1.js"></script> 

  <!-- AdminLTE for demo purposes -->
  <!-- <script src="<?php echo base_url();?>assets/dist/js/adminltedemo.js"></script> -->

  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        
  <!-- Bootstrap 3.3.6 -->
  <script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
  <!-- Bootstrap 3.3.6 -->

  <script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
  <script src="<?php echo base_url();?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="<?php echo base_url();?>assets/plugins/knob/jquery.knob.js"></script>
  <!-- daterangepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="<?php echo base_url();?>assets/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- datepicker -->
  <script src="<?php echo base_url();?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
  <!-- Bootstrap WYSIHTML5 -->
  <script src="<?php echo base_url();?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
  <!-- Slimscroll -->
  <script src="<?php echo base_url();?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="<?php echo base_url();?>assets/plugins/fastclick/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url();?>assets/dist/js/app.min.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="<?php echo base_url();?>assets/dist/js/pages/dashboard.js"></script>
  <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
  <!-- AdminLTE for demo purposes -->
  <!-- <script src="<?php echo base_url();?>assets/dist/js/demo.js"></script>
   -->
<script type="text/javascript">
  $(function() {
    // setTimeout() function will be fired after page is loaded
    // it will wait for 5 sec. and then will fire
    // $("#successMessage").hide() function
    setTimeout(function() {
        $(".message").hide('blind', {}, 500)
    }, 10000);
  });
</script>
</head>
<body class="hold-transition <?php echo $theme; ?> sidebar-mini">
  
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IN</b>O</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b> <!-- <img src="<?php  echo base_url(); ?>assets/images/logo2s.png" height="50%" width="100%"/> --></b><span style="font-size: 24px;">I</span>nvent<span style="font-size: 24px;">O</span></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li >
            <a href="<?php echo base_url('customer/sendBirthdayWishes') ?>" style="font-weight: 500"><i class="fa fa-help"></i>Send Birthday Wishes
              <span class="label text-red" style="background-color: white;">
                <?php 
                      echo $this->db->select('COUNT(*) AS count')
                                        ->from('users c')
                                        ->join('users_groups ug','c.id = ug.user_id')
                                        ->join('groups g','g.id = ug.group_id')
                                        ->where('MONTH(c.birth_date)',date('m'))
                                        ->where('DAY(c.birth_date)',date('d'))
                                        ->where('g.name','customer')
                                        ->get()
                                        ->row()
                                        ->count;
                ?>
              </span>

            </a>
          </li>  
          <?php
            if($this->session->userdata('type')=="admin" || $this->session->userdata('type')=="purchaser" ){
          ?>  
          <li>
            <a href="<?php echo base_url('product_alert');?>" >
              Product Alert
              <span class="label text-red" style="background-color: white;">
                <?php 
                  echo $data = $this->db->select('count(*) as count')
                                        ->from('products p1')
                                        ->join('products p2',"p1.product_id = p2.product_id")
                                        ->where('p1.alert_quantity > p2.quantity')
                                        ->where('p1.delete_status',0)
                                        ->get()
                                        ->row()
                                        ->count;
                ?>
              </span>
            </a>
          </li> 
          <?php
            }
          ?> 
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown">
              
              <div style="width:70px;">
                <table>
                <tr>
                  <td>
                    <?php
                      if($this->session->userdata('site_lang')!=null){
                    ?>
                        <img src="<?php echo base_url('assets/images/flag/').$this->session->userdata('site_lang').".png";?>" width="110%">
                    <?php
                      }
                      else{
                    ?>
                      <img src="<?php echo base_url('assets/images/flag/');?>english.png" width="110%">
                    <?php
                      }
                    ?>
                    </td>
                  <td>
                    <?php 
                      if($this->session->userdata('site_lang')!=null){
                        echo ucwords($this->session->userdata('site_lang')); 
                      }
                      else{
                        echo " English";
                      }
                    ?>
                  </td>
                </tr>
              </table>
              </div>
            </a>
            <ul class="dropdown-menu">
              <li>
                <ul class="menu">
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/english'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/english.png');?>" width="9%">English</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/french'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/french.png');?>" width="9%">French</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/russian'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/russian.png');?>" width="9%">Russian</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/hindi'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/hindi.png');?>" width="9%">Hindi</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/spanish'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/spanish.png');?>" width="9%">Spanish</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/telugu'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/telugu.png');?>" width="9%">Telugu</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/arabic'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/arabic.png');?>" width="9%">Arabic</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/japanese'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/japanese.png');?>" width="9%">Japanese</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/gujarati'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/gujarati.png');?>" width="9%">Gujarati</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/portuguese'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/portuguese.png');?>" width="9%">Portuguese</h3></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url('LanguageSwitcher/switchLang/turkish'); ?>"><h3><img src="<?php echo base_url('assets/images/flag/turkish.png');?>" width="9%">Turkish</h3></a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url();?>assets/dist/img/person2.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $this->session->userdata('first_name')." ".$this->session->userdata('last_name'); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url();?>assets/dist/img/person2.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $this->session->userdata('first_name')." ".$this->session->userdata('last_name'); ?>
                  <small></small>
                </p>
              </li>
              <!-- Menu Body -->
              <!-- <li class="user-body"> -->
                <!-- <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="<?php echo base_url('auth/dashboard'); ?>">Inventory</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="<?php echo base_url('auth/service'); ?>">Services</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">CRM</a>
                  </div>
                </div> -->
                <!-- /.row -->
              <!-- </li> -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url()?>auth/edit_user/<?php echo $this->session->userdata('user_id'); ?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url()?>auth/logout" class="btn btn-default btn-flat"><?php echo $this->lang->line('header_sign_out'); ?></a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url();?>assets/dist/img/person2.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $this->session->userdata('first_name')." ".$this->session->userdata('last_name'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> <?php echo $this->lang->line('header_online'); ?></a>
        </div>
      </div>
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header"><?php echo $this->lang->line('header_main_navidation'); ?></li>
        <li class="treeview">
          <a href="<?php echo base_url();?>auth/dashboard">
            <i class="fa fa-dashboard"></i> 
            <span><?php echo $this->lang->line('header_dashboard'); ?></span>
            <span class="pull-right-container">
              <!-- <i class="fa fa-angle-left pull-right"></i> -->
            </span>
          </a>
        </li>
        <?php if($this->user_model->has_permission("gstr1") || $this->user_model->has_permission("gstr2")){ ?>
        <li class="treeview <?php if($this->uri->segment(1)=='gst_return'){ echo 'active'; } ?>">
          <a href="#">
            <i class="fa fa-bookmark-o text-red"></i>
            <span>GST Return</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right text-red"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($this->user_model->has_permission("gstr1")){?>
            <li class="treeview">
              <a href="<?php echo base_url('gst_return');?>"><i class="fa fa-file-o text-yellow"></i>GSTR1</a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("gstr2")){?>
            <li class="treeview">
              <a href="<?php echo base_url('gst_return/gstr2');?>"><i class="fa fa-file-o text-green"></i>GSTR2</a>
            </li>
          <?php } ?>
          </ul>
        </li>
        <?php } ?>

        <!-- <?php // if($this->user_model->has_permission("gstr1") || $this->user_model->has_permission("gstr2")){ ?>
        <li class="treeview <?php // if($this->uri->segment(1)=='gst_return'){ echo 'active'; } ?>">
          <a href="#">
            <i class="fa fa-users text-yellow"></i>
            <span>Contacts</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right text-red"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php // if($this->user_model->has_permission("gstr1")){?>
            <li class="treeview">
              <a href="<?php echo base_url('customer');?>"><i class="fa fa-user text-yellow"></i>Customer</a>
            </li>
            <?php // } ?>
            <?php // if($this->user_model->has_permission("gstr2")){?>
            <li class="treeview">
              <a href="<?php echo base_url('supplier');?>"><i class="fa fa-user text-green"></i>Supplier</a>
            </li>
          <?php // } ?>
          </ul>
        </li>
        <?php // } ?>
 -->
        <?php //if($this->session->userdata('type')=="admin"){ 
          if($this->user_model->has_module_permission("report") || $this->user_model->has_module_permission("ledger") || $this->user_model->has_module_permission("bank_account"))
          {
           
        ?>
        <li class="treeview <?php if($this->uri->segment(1)=='account_group' || $this->uri->segment(1)=='ledger' || $this->uri->segment(1)=='credit_debit_note' || $this->uri->segment(1)=='gst_return' || $this->uri->segment(1)=='bank_account' || $this->uri->segment(1)=='statement' || $this->uri->segment(1)=='cash_flow' || $this->uri->segment(1)=='credit_debit_note'){ echo 'active'; } ?>">
          <a href="#">
            <i class="fa fa-gg text-red"></i>
            <span>Finance</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right text-red"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($this->user_model->has_module_permission("accountgroup")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('account_group');?>">
                <i class="fa fa-money text-maroon"></i>
                <span>Account Group</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("ledger")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('ledger');?>">
                <i class="fa fa-money text-yellow"></i>
                <span>Ledger</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("expense")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('expense');?>">
                <i class="fa fa-money text-green"></i>
                <span>Expense</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("report")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('cash_flow');?>">
                <i class="fa fa-file-o text-green"></i>
                <span>Cash Flow</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("report")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('statement');?>">
                <i class="fa fa-area-chart text-yellow"></i>
                <span>Statement</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("bank_account")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('bank_account');?>">
                <i class="fa fa-lemon-o text-green"></i>
                <span>Bank Account</span>
              </a>
            </li>
            <?php } ?>
            <!-- <li class="treeview">
              <a href="<?php echo base_url('credit_debit_note');?>">
                <i class="fa fa-file-o text-yellow"></i>
                <span>Credit Debit Note</span>
              </a>
            </li> -->
          </ul>
        </li>
        <?php } ?>
        <?php //if($this->session->userdata('type')=="admin"){ 
          if($this->user_model->has_module_permission("customer") || $this->user_model->has_module_permission("supplier") || $this->user_model->has_module_permission("delivery_person"))
          {
        ?>
        <li class="treeview <?php if($this->uri->segment(1)=='customer' || $this->uri->segment(1)=='supplier' || $this->uri->segment(1)=='delivery_person'){ echo 'active'; } ?>">
          <a href="#">
            <i class="fa fa-users text-green"></i>
            <span>Contacts</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right text-blue"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($this->user_model->has_module_permission("supplier")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('supplier');?>">
                <i class="fa fa-user text-maroon"></i>
                <span>Supplier</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("customer")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('customer');?>">
                <i class="fa fa-user text-yellow"></i>
                <span>Customer</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("delivery_person")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('delivery_person');?>">
                <i class="fa fa-user text-blue"></i>
                <span>Delivery Person</span>
              </a>
            </li>
            <?php } ?>
            
            <!-- <li class="treeview">
              <a href="<?php echo base_url('credit_debit_note');?>">
                <i class="fa fa-file-o text-yellow"></i>
                <span>Credit Debit Note</span>
              </a>
            </li> -->
          </ul>
        </li>
        <?php } ?>
        <?php if($this->user_model->has_module_permission("product")){ ?>
        <li class="treeview <?php if($this->uri->segment(1)=='product'){ echo 'active'; } ?>">
          <a href="<?php echo base_url('product');?>">
            <i class="fa fa-cube text-blue"></i>
            <span><?php echo $this->lang->line('header_product') ?></span>
          </a>
        </li>
        <?php } ?>
        <?php if($this->user_model->has_module_permission("purchase") || $this->user_model->has_module_permission("purchase_return")){ ?>
        <li class="treeview <?php if($this->uri->segment(1)=='purchase' || $this->uri->segment(1)=='purchase_return'){ echo 'active'; } ?>">
          <a href="#">
            <i class="fa fa-square text-maroon"></i>
            <span><?php echo $this->lang->line('header_purchase'); ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right text-maroon"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($this->user_model->has_module_permission("purchase")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('purchase');?>">
                <i class="fa fa-bullseye text-blue"></i>
                <span><?php echo $this->lang->line('header_purchase'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("purchase_return")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('purchase_return');?>">
                <i class="fa fa-soccer-ball-o text-red"></i>
                <span><?php echo $this->lang->line('header_purchase_return'); ?></span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        <?php if($this->user_model->has_module_permission("transfer")){ ?>
        <li class="treeview">
          <a href="<?php echo base_url('transfer');?>">
            <i class="fa fa-cube text-blue"></i>
            <span>Transfer</span>
          </a>
        </li>
        <?php } ?>
        <?php if($this->user_model->has_module_permission("sales") || $this->user_model->has_module_permission("sales_return") || $this->user_model->has_module_permission("quotation")){ ?>
        <li class="treeview <?php if($this->uri->segment(1)=='sales' || $this->uri->segment(1)=='sales_return' || $this->uri->segment(1)=='quotation'){ echo 'active'; } ?>">
          <a href="#">
            <i class="fa fa-shopping-cart text-aqua"></i>
            <span><?php echo $this->lang->line('header_sales'); ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right text-aqua"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($this->user_model->has_module_permission("sales")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('sales');?>">
                <i class="fa fa-shopping-cart text-yellow"></i>
                <span><?php echo $this->lang->line('header_sales'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("sales_return")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('sales_return');?>">
                <i class="fa fa-star text-green"></i>
                <span><?php echo $this->lang->line('header_sales_return'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("quotation")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('quotation');?>">
                <i class="fa fa-star text-green"></i>
                <span>Quotation</span>
              </a>
            </li>
            <?php } ?>
            <!-- <li class="treeview">
              <a href="<?php echo base_url('sales/invoice');?>">
                <i class="fa fa-square-o text-green"></i>
                <span><?php echo $this->lang->line('header_invoice'); ?></span>
              </a>
            </li> -->
          </ul>
        </li>
        <?php } ?>
        <?php if($this->user_model->has_module_permission("transaction")){ ?>
        <li class="treeview <?php if($this->uri->segment(1)=='payment' || $this->uri->segment(1)=='receipt'){ echo 'active'; } ?>">
          <a href="#">
            <i class="fa fa-gg text-red"></i>
            <span>Transaction</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right text-red"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <?php if($this->user_model->has_module_permission("transaction")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('payment');?>">
                <i class="fa fa-money text-maroon"></i>
                <span><?php echo $this->lang->line('header_payment'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_module_permission("transaction")){ ?>
            <li class="treeview">
              <a href="<?php echo base_url('receipt');?>">
                <i class="fa fa-money text-maroon"></i>
                <span>Receipt</span>
              </a>
            </li>
            <?php } ?>
          </ul>
        </li>
        <?php
            
          } 
        ?>
        <!-- <li class="treeview">
          <a href="<?php echo base_url('payment');?>">
            <i class="fa fa-database text-maroon"></i>
            <span><?php echo "Tender" ?></span>
          </a>
        </li>
        <li class="treeview">
          <a href="<?php echo base_url('receipt');?>">
            <i class="fa fa-folder-open text-yellow"></i>
            <span>Quotation</span>
          </a>
        </li>
        <li class="treeview">
          <a href="<?php echo base_url('receipt');?>">
            <i class="fa fa-briefcase text-white"></i>
            <span>Work Order</span>
          </a>
        </li> -->
        
        <?php if($this->user_model->has_module_permission("report")){ ?>
       
        <li class="treeview <?php if($this->uri->segment(1)=='reports'){ echo 'active'; } ?>">
          <a href="#">
            <i class="fa fa-file text-yellow"></i>
            <span><?php echo $this->lang->line('header_reports'); ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right text-yellow"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($this->user_model->has_permission("daily_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/daily'); ?>">
                <i class="fa fa-circle-thin text-maroon"></i>
                <span><?php echo $this->lang->line('header_daily'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("product_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/products'); ?>">
                <i class="fa fa-cube text-red"></i>
                <span><?php echo $this->lang->line('header_product'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("warehouse_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/warehouse_report'); ?>">
                <i class="fa fa-university text-suffron"></i>
                <span><?php echo $this->lang->line('header_warehouse_report'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("purchase_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/purchase'); ?>">
                <i class="fa fa-square text-green"></i>
                <span><?php echo $this->lang->line('header_purchase'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("purchase_return_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/purchase_return'); ?>">
                <i class="fa fa-soccer-ball-o text-aqua"></i>
                <span><?php echo $this->lang->line('header_purchase_return'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("sales_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/sales'); ?>">
                <i class="fa fa-shopping-cart text-blue"></i>
                <span><?php echo $this->lang->line('header_sales'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("sales_return_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/sales_return'); ?>">
                <i class="fa fa-star text-suffron"></i>
                <span><?php echo $this->lang->line('header_sales_return'); ?></span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("receivable_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/receivable'); ?>">
                <i class="fa fa-gg text-yellow"></i>
                <span>Receivable</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("payable_report")){ ?>     
            <li class="treeview">
              <a href="<?php echo base_url('reports/payable'); ?>">
                <i class="fa fa-bolt text-green"></i>
                <span>Payable</span>
              </a>
            </li>
            <?php } ?>
            <?php if($this->user_model->has_permission("pl_report")){ ?>     
            <!-- <li class="treeview">
              <a href="<?php echo base_url('reports/pl_report'); ?>">
                <i class="fa fa-money text-green"></i>
                <span>Profit & Loss Report</span>
              </a>
            </li> -->
            <?php } ?>
          </ul>
        </li>
        <?php } ?>
        <?php if($this->user_model->has_module_permission("user") || $this->user_model->has_module_permission("permission") || $this->user_model->has_module_permission("role")){ ?>
        <li class="treeview <?php if($this->uri->segment(1)=='user'){ echo 'active'; } ?>">
          <a href="<?php echo base_url('user');?>">
            <i class="fa fa-users text-green"></i>
            <span>User, Role & Permission</span>
          </a>
        </li>
        <?php } ?>
        <?php if($this->user_model->has_module_permission("setting") || $this->user_model->has_module_permission("expense_category")|| $this->user_model->has_module_permission("currency")|| $this->user_model->has_permission("list_payment_method")|| $this->user_model->has_module_permission("discount")|| $this->user_model->has_module_permission("branch")|| $this->user_model->has_module_permission("brand")|| $this->user_model->has_module_permission("location")){ ?>
        <li class="treeview <?php if($this->uri->segment(1)=='company_setting'  || $this->uri->segment(1)=='branch' || $this->uri->segment(1)=='discount' || $this->uri->segment(1)=='warehouse' || $this->uri->segment(1)=='assign_warehouse' || $this->uri->segment(1)=='email_setup'){ echo 'active'; } ?>">
          <a href="<?=base_url('company_setting')?>">
            <i class="fa fa-cog text-red"></i>
            <span><?php echo $this->lang->line('header_setting'); ?></span>
          </a>
        </li>
        <?php } ?>
        <!-- <li class="treeview">
          <a href="<?php echo base_url('log');?>">
            <i class="fa fa-history text-maroon"></i>
            <span>Logs</span>
          </a>
        </li> -->
        <!-- <li class="treeview">
          <a href="https://codecanyon.net/item/invento-accounting-billing-inventory-management-system/20233171" target="_blank">
            <span><a href=" https://codecanyon.net/item/invento-accounting-billing-inventory-management-system/20233171?ref=dhaval28691" class="btn btn-danger btn-block btn-flat" target="_blank">Buy Now</a></span>
          </a>
        </li> -->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>












































