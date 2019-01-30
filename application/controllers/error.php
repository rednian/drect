<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Error extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function error_404()
	{
		$data['title'] = 'Error 404';
		$this->load->view('includes/header', $data);
		$this->load->view('error/page_404');
		$this->load->view('includes/footer');
	}

}
?>