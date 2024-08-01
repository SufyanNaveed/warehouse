<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_method extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('form_validation','ion_auth'));
		
        $this->load->model('log_model');
        $this->load->model('branch_model');
        $this->load->model('warehouse_model');
        $this->load->model('user_model');
        $this->load->model('category_model');
        $this->load->model('subcategory_model');
        $this->load->model('brand_model');
        $this->load->model('product_model');
        $this->load->model('payment_method_model');
        $this->load->model('expense_category_model');
        $this->load->model('discount_model');
        $this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');
	}

    /*
       this function used for display paymentmethod data
    */
	public function index()
	{
        if(!$this->user_model->has_permission("list_payment_method"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $data['data'] = $this->payment_method_model->payUserData();
            $this->load->view('payment_method/list',$data);     
        }
		
	}

    /*

       this function used for add new payment_method form

    */
	public function add_payment_method()
	{
        if(!$this->user_model->has_permission("add_payment_method"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $this->load->view('payment_method/add');    
        }
	}

    /*

       this function used for insert new paymentmethod data

    */
	public function add()
    {
        $this->form_validation->set_rules('name', 'Name ', 'required');
        $this->form_validation->set_rules('default', 'select ', 'required');
      
        if ($this->form_validation->run() == true)
        {
            $payment = array(
                        'name'        => $this->input->post('name'),
                        'default'     => $this->input->post('default')       
                        );

            if($this->payment_method_model->addPayment($payment))
            {
                $data['data'] = $this->payment_method_model->payUserData();
                $this->load->view('payment_method/list',$data);
            }
            else
            {
                $this->add_payment_method();
            }
        }
        
     }

    /*

     this function used for delete paymentmethod data

   */
    public function delete($id)
    {
        if(!$this->user_model->has_permission("delete_payment_method"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $data =    array(
                        'delete_status'   => 1,
                        'delete_date'     => date('Y-m-d'),
                        'id'              => $id
                    );
        
            if($this->payment_method_model->delete($data))
            {
                $this->session->set_flashdata('success', 'Payment method Deleted successfully.');
                redirect("payment_method",'refresh');
            }
        }

	    
    }

	public function edit_payment($id)
    {	  
        if(!$this->user_model->has_permission("edit_payment_method"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $data['data'] = $this->payment_method_model->getData($id);
            $this->load->view('payment_method/edit',$data);    
        }
        
    } 

    public function edit()
    {
    	
        $this->form_validation->set_rules('name', 'Name ', 'required');
        $this->form_validation->set_rules('default', 'select ', 'required');
        
        if ($this->form_validation->run() == true)
        {
           $id = $this->input->post('id');

            $payment = array(
                
                    'name'        =>  $this->input->post('name'),
                    'default'     =>  $this->input->post('default'),
                    'id'=>$id        
            );
            
        }

        if ( ($this->form_validation->run() == true) && $this->payment_method_model->editPayment($payment))
        {
            $this->session->set_flashdata('success', 'Payment method edit successfully.');
            redirect("payment_method",'refresh');
        }    
        
        else
        {  
           $this->edit_data($id);
        }
     }

     
}