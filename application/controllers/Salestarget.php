<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Salestarget extends MY_Controller
{
	function __construct() {
		parent::__construct();
		
    }
	public function index(){
		// get all sales to display list
		// $data['data'] = $this->sales_model->getSales();
		// $data['billers'] = $this->biller_model->getBillers();

		// echo '<pre>';
		// print_r($data);
		// exit;
		$this->load->view('salestarget/index');
	} 
	
}
?>