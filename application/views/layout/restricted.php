<?php 
	$this->load->view('layout/header');
?>

<div class="content-wrapper">
    <section class="content">

      <div class="error-page">
        <h2 class="headline text-red"><i class="fa fa-shield"></i></h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-red"></i> You have entered into prohibited area.</h3>

          <p>
            This page is restricted. Please contact Administrator.
            Meanwhile, you may <a href="<?=base_url('auth/dashboard')?>">return to dashboard</a>
          </p>

          <form class="search-form">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search">

              <div class="input-group-btn">
                <button type="submit" name="submit" class="btn btn-danger btn-flat"><i class="fa fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

</div>

<?php 
	$this->load->view('layout/footer');
?>