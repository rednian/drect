<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		 if($this->session->userdata('DRECT_logged')){
	       redirect('dashboard' , 'refresh');
	     }
	}
	public function index()
	{
		$data['title'] = 'Login';
		$this->load->view('includes/header', $data);
		$this->load->view('login');
		$this->load->view('includes/footer');
	}

}
?>