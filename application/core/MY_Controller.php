<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  class MY_Controller extends CI_Controller {

    public $userInfo = array();

    public function __construct() {
      parent::__construct();
      $this->load->model('Securitys');
      
      if (!$this->session->userdata('DRECT_logged')) {
        redirect('login' , 'refresh');
      }
   
      $this->userInfo = $this->get_login_info();
    }

    public function get_login_info() {
      $user_id = $this->session->userdata('DRECT_logged');
      $this->load->model('Personal_info');
      $per_info = new Personal_info;
      return $per_info->get_user_login_info($user_id['id']);
    }

    public function check($segment){
      $pia = new Securitys;
      $check = $pia->check_access($segment);

      if(empty($check)){
        return "hide";
      }
    }
    
    public function check_module($mod){
      $pia = new Securitys;
      $check = $pia->check_module($mod);

      if(empty($check)){
        return "hide";
      }
    }

    public function setSideBar(){
      $pia = new Securitys;
      return $pia->setSidebar();
    }
  }
