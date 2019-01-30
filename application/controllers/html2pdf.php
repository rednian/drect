<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class html2pdf extends CI_Controller {

	public function index()
	{
		
		$this->load->library('My_dompdf');
		$dompdf = new DOMPDF();

		$content = $this->load->view('sample','',true);
	    
	    $dompdf->load_html($content);
	    $dompdf->set_paper('A4', 'landscape');
		$dompdf->render();
		$dompdf->stream("sample.pdf");
	}
}
