<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ledger extends MY_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('ledger_model');
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
        $this->load->model('account_group_model');
        $this->load->model('email_setup_model');
        $this->load->model('sms_setting_model');
        
        
        //$this->load->model('company_setting_model');
        
    }

    public function index(){
        if(!$this->user_model->has_permission("list_ledger"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $data['data'] = $this->ledger_model->get_records(); 
            $this->load->view('ledger/list',$data);    
        }
    }

     
    
    public function getBranch(){
        //get Branch name and Id
        $data['data'] = $this->biller_model->getBranch(); 
        return $data;
    }
    /* 
        get Branch name and Id  
    */
    public function add(){
        if(!$this->user_model->has_permission("add_ledger"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $data['branch'] = $this->ledger_model->getBranch();
            $data['accountgroup'] = $this->ledger_model->getAccountGroup();
            $this->load->view('ledger/add',$data);    
        }
        
    }
    /* 
        Add New Biller in database 
    */
    function add_ledger()
    {
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('accountgroup','Account Group','required');
        
        if ($this->form_validation->run() == true)
        {
            $data = array(
                    'title'             =>  strtoupper($this->input->post('title')),
                    'accountgroup_id'   =>  $this->input->post('accountgroup'),
                    'opening_balance'   =>  $this->input->post('opening_balance'),
                    'closing_balance'   =>  $this->input->post('closing_balance'),
                );
        }
        
        if($this->ledger_model->addLedger($data))
        {  
            $this->session->set_flashdata('success', 'Ledger added successfully.');
            redirect("ledger",'refresh');
        }
        else
        {  
            redirect("ledger",'refresh');
        }   
    }
    /* 
        call view editr Biller 
    */
    public function edit($id = NULL)
    {
        if(!$this->user_model->has_permission("edit_ledger"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            if($_POST)
            {
                $this->form_validation->set_rules('title', 'Title', 'trim|required');
                $this->form_validation->set_rules('accountgroup','Account Group','required');
                $this->form_validation->set_rules('opening_balance', 'Opening Balance', 'trim');
                $this->form_validation->set_rules('closing_balance', 'Closing Balance', 'trim');
                
                if ($this->form_validation->run() == true)
                {
                    $id = $this->input->post('ledger_id');
                    $data = array(
                                'title'            =>  strtoupper($this->input->post('title')),
                                'accountgroup_id'  =>  $this->input->post('accountgroup'),
                                'opening_balance'  =>  $this->input->post('opening_balance'),
                                'closing_balance'  =>  $this->input->post('closing_balance')
                            );
                    
                    if($this->ledger_model->edit_record($data,$id))
                    {
                        $this->session->set_flashdata('success', 'Ledger updated successfully.');
                        redirect("ledger",'refresh');     
                    }
                    else
                    {
                        $this->session->set_flashdata('failure', 'Ledger failed to update.');
                        redirect("ledger",'refresh');
                    }
                }
            }
            else
            {
                $data['accountgroup'] = $this->account_group_model->get_records();
                $data['ledger'] = $this->ledger_model->get_single_record($id);
                
                $this->load->view('ledger/edit', $data);
                
            }    
        }

        
    }
    
    /* 
        Delete Biller in Database 
    */
    public function delete($ledger_id)
    {
        if(!$this->user_model->has_permission("delete_ledger"))
        {
            $this->load->view('layout/restricted'); 
        }
        
        $id = $ledger_id;
        if($this->ledger_model->deleteLedger($id))
        {
            $this->session->set_flashdata('success', 'Ledger deleted successfully.');
            redirect("ledger",'refresh');
        }
    }

    public function get_ledger_by_branch($branch_id)
    {
        echo json_encode($this->ledger_model->get_records_by_branch($branch_id));
    }


}
?>