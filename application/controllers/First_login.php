<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class First_login extends MY_Controller
{
	public function index(){	
		$this->load->view('first_login');
	} 
}
?>