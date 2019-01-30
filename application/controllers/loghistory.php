<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Loghistory extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
		$this->load->model("Log_history");
	}
	public function index()
	{
		$data['title'] = 'Log History';
		$data['title_view'] = "Log History";
		$data['log'] = $this->log();

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('loghistory/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	private function log(){
		$log = new Log_history;

		$log->toJoin = array("Personal_info"=>"Log_history","Position"=>"Personal_info");
		$log->db->order_by("login_date_time", "DESC");
		$list = $log->get();
		return $list;
	}
}
?>