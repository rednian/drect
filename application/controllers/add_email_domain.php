<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Add_email_domain extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
	}
	
	public function index()
	{
		$data['title'] = 'Add Email Domain';
		$data['title_view'] = "Add Email Domain";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('email_domain/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}
}
?>