<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends MY_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation','ion_auth'));
        $this->load->model('expense_model');   
        $this->load->model('user_model');
        $this->load->model('ledger_model');
        $this->load->model('receipt_model');
        $this->load->model('payment_method_model');
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
        this function used for display expense list and Expense data form
    */
    public function index()
    {
        if(!$this->user_model->has_permission("list_expense"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $data['data'] = $this->expense_model->expUserData();        
            $this->load->view('expenses/list',$data);    
        }
        
    }


    /*
        this function used for display expense add form
    */
    public function addExpanses()
    {
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        else
        {
            $data['from_ledger']        =   $this->expense_model->get_income_assets_ledger();
            $data['to_ledger']          =   $this->expense_model->get_expense_liabilities_ledger();
            $data['payment_methods']    =   $this->payment_method_model->get_records();
            $data['branch']             =   $this->branch_model->get_records();
            

            $this->load->view('expenses/add',$data);        
        }
    }




   
    /*

    this function used for add new expense record

    */
    public function add()
    {
        if(!$this->user_model->has_permission("add_expense"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $this->form_validation->set_rules('desc', 'Enter desc', 'required');
            $this->form_validation->set_rules('amount', ' Enter Amount ', 'required');
            
            if ($this->form_validation->run() == true)
            {
                    // $from_ledger    = $this->ledger_model->get_single_record($this->input->post('from_ledger'));
                    // $to_ledger      = $this->ledger_model->get_single_record($this->input->post('to_ledger'));    
                    // $from_ledger->closing_balance   = floatval($from_ledger->closing_balance - $this->input->post('amount'));
                    // $to_ledger->closing_balance     = floatval($to_ledger->closing_balance + $this->input->post('amount'));


                    // echo '<pre>';
                    // print_r($from_ledger);

                    // print_r($to_ledger);
                    //                     exit;

                    $expenses = array(
                            'account_id'        => $this->input->post('from_ledger'),
                            'date'              => $this->input->post('date'),
                            'description'       => $this->input->post('desc'),
                            'amount'            => $this->input->post('amount'),
                            'category_id'       => $this->input->post('to_ledger'),
                            'payment_method_id' => $this->input->post('payment_method_id'),
                            'reference_no'      => $this->input->post('reference'),
                            'user_id'           => $this->session->userdata('user_id')
                        );

                    if ($id = $this->expense_model->add_record($expenses))
                    {
                        // $this->ledger_model->edit_record($from_ledger,$this->input->post('from_ledger'));
                        // $this->ledger_model->edit_record($to_ledger,$this->input->post('to_ledger'));
                        
                        

                        $data = array(
                            "transaction_date" => $this->input->post('date'),
                            "expense_id"       => $this->input->post($id),
                            "type"             => $this->input->post('type'),
                            "amount"           => $this->input->post('amount'),
                            "voucher_no"       => $id,
                            "voucher_date"     => $this->input->post('date'),
                            "mode"             => $this->payment_method_model->get_single_record($this->input->post('payment_method_id'))->name,
                            "from_account"     => $this->input->post('from_ledger'),
                            "to_account"       => $this->input->post('to_ledger'),
                            "narration"        => $this->input->post('description'),
                            "invoice_id"       => null,
                            "receipt_id"       => null,
                            "expense_id"       => $id,
                            "voucher_status"   => 1
                        );

                        $this->receipt_model->addReceipt($data);

                        $this->session->set_flashdata('success', 'Expense added successfully.');
                        redirect('expense');
                        // $data['data'] = $this->expense_model->expUserData();

                        // $this->load->view('expenses/list',$data);
                    }       

            }
            else
            {  
                $this->addExpanses();
            }    
        }
        
     }
     /*
         this function used for fetch data at update expense details
     */
    public function edit_data($id)
    {  
        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        if(!$this->user_model->has_permission("edit_expense"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
        
            $data['from_ledger']        =   $this->expense_model->get_income_assets_ledger();
            $data['to_ledger']          =   $this->expense_model->get_expense_liabilities_ledger();
            $data['payment_methods']    =   $this->payment_method_model->get_records();
            $data['data']               =   $this->expense_model->getData($id);
         
            // $data['referense_no']=$this->expense_model->getLastID();

            $this->load->view('expenses/edit',$data);    
        }
        
    }


    public function delete($id)
    {

        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        if(!$this->user_model->has_permission("delete_expense"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            $this->expense_model->delete($id);

            $this->session->set_flashdata('success', 'Expense deleted successfully.');
            redirect("expense");
        }
    }

     
    public function edit()
    {

        if (!$this->ion_auth->logged_in())
        {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }

        if(!$this->user_model->has_permission("edit_expense"))
        {
            $this->load->view('layout/restricted'); 
        }
        else
        {
            

            //$this->form_validation->set_rules('acount', 'Select Acount name ','required');
            //$this->form_validation->set_rules('date', 'date ', 'required');
            $this->form_validation->set_rules('desc', 'Enter Description', 'required');
            $this->form_validation->set_rules('amount', 'Enter  Amount ', 'required');
            //$this->form_validation->set_rules('category', 'Select category Name ', 'required');
            //$this->form_validation->set_rules('reference', 'Name ', 'required');
            
            if ($this->form_validation->run() != true)
            {
                $this->edit_data($id);
            }
            else
            {
                $id = $this->input->post('expense_id');
                $old_expense = $this->expense_model->get_single_record($id);
            
                $expenses = array(
                                'account_id'        => $this->input->post('from_ledger'),
                                'date'              => $this->input->post('date'),
                                'description'       => $this->input->post('desc'),
                                'amount'            => $this->input->post('amount'),
                                'category_id'       => $this->input->post('to_ledger'),
                                'payment_method_id' => $this->input->post('payment_method_id'),
                                'reference_no'      => $this->input->post('reference'),
                                'user_id'           => $this->session->userdata('user_id')
                            );

                if($this->expense_model->edit_record($expenses,$id))
                {
                    // get the current ledger detail
                    $from_ledger    = $this->ledger_model->get_single_record($this->input->post('from_ledger'));
                    $to_ledger      = $this->ledger_model->get_single_record($this->input->post('to_ledger')); 

                    // update closing balance
                    $from_ledger->closing_balance   = floatval($from_ledger->closing_balance + $old_expense->amount);
                    $to_ledger->closing_balance     = floatval($to_ledger->closing_balance - $old_expense->amount);

                    // update ledger details
                    $this->ledger_model->edit_record($from_ledger,$from_ledger->id);
                    $this->ledger_model->edit_record($to_ledger,$to_ledger->id);

                    $transaction_header = $this->db->get_where("transaction_header",array("expense_id"=>$id))->row();

                    $this->db->where("expense_id",$id);
                    $this->db->delete("transaction_header");

                    $this->db->where("transaction_id",$transaction_header->id);
                    $this->db->delete("transaction_detail"); 

                    $data = array(
                        "transaction_date" => $this->input->post('date'),
                        "expense_id"       => $this->input->post($id),
                        "type"             => $this->input->post('type'),
                        "amount"           => $this->input->post('amount'),
                        "voucher_no"       => $id,
                        "voucher_date"     => $this->input->post('date'),
                        "mode"             => $this->payment_method_model->get_single_record($this->input->post('payment_method_id'))->name,
                        "from_account"     => $this->input->post('from_ledger'),
                        "to_account"       => $this->input->post('to_ledger'),
                        "narration"        => $this->input->post('description'),
                        "invoice_id"       => null,
                        "receipt_id"       => null,
                        "expense_id"       => $id,
                        "voucher_status"   => 1
                    );

                    $this->receipt_model->addReceipt($data);


                    $this->session->set_flashdata('success', 'Expense updated successfully.');
                    redirect("expense",'refresh');
                }
                else
                {
                    $this->session->set_flashdata('failure', 'Expense edit failed.');
                    redirect("expense",'refresh');   
                }

            }   
        }

        

    }
        
}
