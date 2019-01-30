<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Rt_approval extends My_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data['title'] = 'Request & Task Approval';
		$data['title_view'] = 'Request & Task Approval';
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('request/approval');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function getAttachments(){
		
		$this->load->model("Request_task_process_att");
		$this->load->model("Request_task_process");
		$att = new Request_task_process_att;
		$rtp = new Request_task_process;

		$pi_id   =  $this->input->get("pi_id");
		$rtp_id  =  $this->input->get("rtp_id");
		$list    =  $att->search(array("rtp_id"=>$rtp_id));

		$img = "";

		if(!empty($list)){
			foreach ($list as $key => $value) {

				$type = pathinfo($value->rtpa_path, PATHINFO_EXTENSION);
				
				$arrImg = ["png","jpg","gif","jpeg"];
				
				$fileIcon = [
							"docx" => base_url('assets/images/word1.png'),
							"doc"  => base_url('assets/images/word1.png'),
							"pptx" => base_url('assets/images/power1.png'),
							"ppt"  => base_url('assets/images/power1.png'),
							"xlsx" => base_url('assets/images/excel1.png'),
							"xls"  => base_url('assets/images/excel1.png'),
							"csv"  => base_url('assets/images/excel1.png'),
							"pdf"  => base_url('assets/images/pdf1.png'),
							"rar"  => base_url('assets/images/rar.png'),
							"zip"  => base_url('assets/images/rar.png'),
							];

				if(!in_array($type, $arrImg)){
					if(!array_key_exists($type,$fileIcon)){
						$fileIcon[$type] = base_url('assets/images/unknown1.png');
					}
					$img .= "<div id='rtpa_".$value->rtpa_id."' class='pull-left clearfix rtp_att' style='margin-right:10px'>";
							if($pi_id == $this->userInfo->pi_id){
								$img .= "<button onclick=\"deleteRtpAtt(".$value->rtpa_id.",'".$value->rtpa_path."')\" data-toggle=\"tooltip\" data-placement=\"left\" title='Delete attachment' type=\"button\" class=\"close\"><span aria-hidden=\"true\">&times;</span></button>";
							}
					$img .=	"<img data-toggle=\"tooltip\" data-placement=\"bottom\" title='".$value->rtpa_path."' path='".$value->rtpa_path."' src='".$fileIcon[$type]."' class='img img-responsive img-thumbnail' style='width:150px;cursor:pointer;'>
								<h6>".substr($value->rtpa_path, 10)."</h6>
							</div> ";
				}
				else{
					$img .= "<div id='rtpa_".$value->rtpa_id."' class='pull-left clearfix rtp_att' style='margin-right:10px'>";
							if($pi_id == $this->userInfo->pi_id){
								$img .= "<button onclick=\"deleteRtpAtt(".$value->rtpa_id.",'".$value->rtpa_path."')\" data-toggle=\"tooltip\" data-placement=\"left\" title='Delete attachment' type=\"button\" class=\"close\"><span aria-hidden=\"true\">&times;</span></button>";
							}
					$img .=	"<img onclick=\"viewAttachment($(this))\" path='".$value->rtpa_path."' src='".base_url("images/tempAttachmentImg/".$value->rtpa_path)."' class='img img-responsive img-thumbnail' style='width:200px;cursor:pointer;'>
							</div> ";
				}
			}
			$rtpInfo = $rtp->search(["rtp_id"=>$rtp_id]);
			echo json_encode([$img, $rtpInfo[$rtp_id]->rtp_step_detail]);
		}
	}

	public function downloadAsZip($rtp_id){

		$this->load->library("zip");
		$this->load->helper('download');
		$this->load->model("Request_task_process_att");
		$att = new Request_task_process_att;

		$rtp_id = $rtp_id;
		$list = $att->search(array("rtp_id"=>$rtp_id));

		$data = array();

		if(!empty($list)){
			foreach ($list as $key => $value) {
				$paths = "./images/tempAttachmentImg/".$value->rtpa_path;
				$name = $value->rtpa_path;
				$data = file_get_contents($paths);

				$this->zip->add_data($name, $data);
			}

			$zipName = $this->userInfo->pi_id.$rtp_id.time().".zip";
			$this->zip->archive($zipName);
			$this->zip->download($zipName);
		}
	}

	public function deleteAttachment(){
		$this->load->model("Request_task_process_att");
		$att = new Request_task_process_att;

		$rtpa_id = $this->input->get("rtpa_id");
		$path    = $this->input->get("path");

		$this->db->trans_begin();
		$this->db->query("DELETE FROM request_task_process_att WHERE rtpa_id = {$rtpa_id}");
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
			if(unlink("./images/tempAttachmentImg/".$path)){
				echo json_encode(["result"=>true]);
				$this->db->trans_commit();
			}
			else{
				echo json_encode(["result"=>false]);
				$this->db->trans_rollback();
			}
		}
	}
}
?>