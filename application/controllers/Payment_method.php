<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_method extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
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
            $name       = $this->input->post('name');
            $default    = $this->input->post('default');

            $payment = array(
                        'name'        => $name,
                        'default'     => $default       
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
            $payment_method = $this->payment_method_model->get_single_record($id);
        
            if($this->payment_method_model->delete($data))
            {
                $this->session->set_flashdata('success', $payment_method->name.' is Deleted successfully.');
                redirect("payment_method",'refresh');
            }
            else
            {
                $this->session->set_flashdata('failure', $payment_method->name.' is failed to delete.');
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

            $name       = $this->input->post('name');
            $default    = $this->input->post('default');

            $payment = array(
                        'name'        => $name,
                        'default'     => $default       
                        );
            
        }

        if ( ($this->form_validation->run() == true) && $this->payment_method_model->editPayment($payment))
        {
            $this->session->set_flashdata('success', $name.' is edited successfully.');
            redirect("payment_method",'refresh');
        }    
        
        else
        {  
           $this->edit_data($id);
        }
     }

     
}