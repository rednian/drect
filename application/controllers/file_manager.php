<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class File_manager extends My_Controller {

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
		$data['title'] = 'File Manager';
		$data['title_view'] = "File Manager";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('filemanager/link_css.php');
		$this->load->view('filemanager/index');
		$this->load->view('filemanager/link_js.php');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}
}
?>