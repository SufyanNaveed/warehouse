<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class MY_Controller extends CI_Controller {

        function __construct()
        {
            parent::__construct();
            $this->load->model('email_setup_model');
            $this->load->model('sms_setting_model');

            $this->check_login();
        }

        function check_login()
        {
            if ( ! $this->session->userdata('loggedin'))
            { 
                redirect('auth/login');
            }
        }
        
    }
 ?>