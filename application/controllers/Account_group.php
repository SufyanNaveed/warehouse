<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Account_group extends MY_Controller 
{
    function __construct()
	{
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        if(!$this->user_model->has_permission("list_accountgroup"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $data['account_groups']   = $this->account_group_model->get_records();
            $this->load->view('account_group/list',$data);    
        }
        
    }

    public function add()
    {
        if(!$this->user_model->has_permission("add_accountgroup"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            if($_POST)
            {
                $this->form_validation->set_rules('group_title', 'Group title', 'trim');
                $this->form_validation->set_rules('category', 'Category', 'trim|required');
                $this->form_validation->set_rules('opening_balance', 'Opening Balance', 'trim');

                if($this->form_validation->run() == FALSE)
                {
                    $this->add();
                }
                else
                {
                    $group_title        =   $this->input->post('group_title');
                    $category           =   $this->input->post('category');
                    $opening_balance    =   $this->input->post('opening_balance');

                    $data               =   array(
                                                'group_title'       => $group_title,
                                                'category'          => $category,
                                                'opening_balance'   => $opening_balance
                                            );

                    if($id = $this->account_group_model->add_record($data))
                    {
                        $this->session->set_flashdata('success', $group_title.' is added successfully.');
                        redirect("account_group",'refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata('failure', $group_title.' is failed to add.');
                        redirect("account_group",'refresh');   
                    }
                }

            }
            else
            {
                $data['branch']   = $this->branch_model->get_records(); 
                $this->load->view('account_group/add',$data); 
            } 
        }
        
    }

    public function edit($id = NULL)
    {
        if(!$this->user_model->has_permission("edit_accountgroup"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            if($_POST)
            {
                $this->form_validation->set_rules('group_title', 'Group title', 'trim');
                $this->form_validation->set_rules('category', 'Category', 'trim|required');
                $this->form_validation->set_rules('opening_balance', 'Opening Balance', 'trim');

                if($this->form_validation->run() == FALSE)
                {
                    $this->edit($id);
                }
                else
                {
                    $id = $this->input->post('account_group_id');
                    $group_title        =   $this->input->post('group_title');
                    $category           =   $this->input->post('category');
                    $opening_balance    =   $this->input->post('opening_balance');

                    $data               =   array(
                                                'group_title'       => $group_title,
                                                'category'          => $category,
                                                'opening_balance'   => $opening_balance
                                            );

                    if($this->account_group_model->edit_record($data,$id))
                    { 
                        $this->session->set_flashdata('success', $group_title.' is updated successfully.');
                        redirect("account_group",'refresh');
                    }
                    else
                    {
                        $this->session->set_flashdata('failure', $group_title.' is failed to update.');
                        redirect("account_group",'refresh');   
                    }
                }
            }
            else
            {
                $data['account_group'] = $this->account_group_model->get_single_record($id);
                $data['branch']        = $this->branch_model->get_records(); 
                $this->load->view('account_group/add',$data); 
            } 
        }
        
    }

    public function delete($id)
    {
        if(!$this->user_model->has_permission("delete_accountgroup"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $account_group  =   $this->account_group_model->get_single_record($id);
            if($this->account_group_model->delete_record($id))
            {
                $this->session->set_flashdata('success', $account_group->group_title.' is deleted in system.');
                redirect("account_group",'refresh');   
            }
            else
            {
                $this->session->set_flashdata('failure',  $account_group->group_title.' can not be deleted.');
                redirect("account_group",'refresh');      
            }    
        }
        
    }


}
?>