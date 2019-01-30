<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
class PolicyController extends My_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;

		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}
	}

	public function index(){

		$data['title'] = 'Policy';
		$data['title_view'] = "Policy";

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('policy/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function create(){

		$data['title'] = 'Create New Policy';
		$data['title_view'] = "Create New Policy";

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('policy/create');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function add(){

		$this->load->model("Policy");
		$policy = new Policy;

		$no = $this->input->post("policy_no");
		$title = $this->input->post("policy_title");
		$content = $this->input->post("policy_content");

		$this->db->trans_begin();

		$policy->policy_no      = $no;
		$policy->policy_title   = $title;
		$policy->policy_content = $content;
		$policy->save();

		$policy_id = $policy->db->insert_id();

		// UPLOAD ATTACHMENT
		$config['upload_path']   = "./attachments/policy/";
		$config['allowed_types'] = '*';
		$config['max_size']      = '1024';
		$config['max_width']     = '1920';
		$config['max_height']    = '1280';
		$config['encrypt_name']  = TRUE;

		$this->load->library('upload');
        $this->upload->initialize($config);

        //Perform upload
        if(!empty($_FILES['path']['name'][0])){
        	$attachDB = array();
        	if($this->upload->do_multi_upload("path")) {

	            $dataAtt = $this->upload->get_multi_upload_data();

				foreach ($dataAtt as $key => $value) {
					$attachDB[] = array(
									"policy_id"=>$policy_id,
									"path"=>$value['file_name'],
									);
				}
	        }
	        $this->db->insert_batch('policy_attachment', $attachDB);
        }

		if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(["result"=>false]);    
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(["result"=>true]);
        }
	}

	public function policyList(){

		$this->load->model("Policy");
		$policy = new Policy;

		$list = $policy->get();
		$arr = [];
		if(!empty($list)){
			foreach ($list as $key => $value) {

				$urledit = base_url('policy/'.$value->policy_id.'/edit');

				$arr[] = array(
						$value->policy_no,
						ucwords($value->policy_title),
						"<a href='".$urledit."' class='btn btn-default btn-xs'><i class='fa fa-pencil'></i> Edit</a> <button onclick='removePolicy(".$value->policy_id.")' class='btn btn-default btn-xs'><i class='fa fa-times'></i> Delete</button>"
						);
			}
		}
		echo json_encode(["data"=>$arr]);
	}

	public function editPolicy($policy_id){

		$this->load->model("Policy");
		$this->load->model("Policy_attachment");
		$policy = new Policy;
		$att = new Policy_attachment;

		$details = $policy->search(["policy_id"=>$policy_id]);
		$attachments = $att->search(["policy_id"=>$policy_id]);

		$data['title'] = 'Edit Policy';
		$data['title_view'] = "Edit Policy";
		$data['details'] = $details;
		$data['attachments'] = $attachments;
		$data['policy_id'] = $policy_id;

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('policy/edit');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function updatePolicy(){

		$this->load->model("Policy");
		$policy = new Policy;

		$no = $this->input->post("policy_no");
		$title = $this->input->post("policy_title");
		$content = $this->input->post("policy_content");
		$policy_id = $this->input->post("policy_id");

		$this->db->trans_begin();

		$policy->load($policy_id);
		$policy->policy_no      = $no;
		$policy->policy_title   = $title;
		$policy->policy_content = $content;
		$policy->save();

		// UPLOAD ATTACHMENT
		$config['upload_path']   = "./attachments/policy/";
		$config['allowed_types'] = '*';
		$config['max_size']      = '1024';
		$config['max_width']     = '1920';
		$config['max_height']    = '1280';
		$config['encrypt_name']  = TRUE;

		$this->load->library('upload');
        $this->upload->initialize($config);

        //Perform upload
        if(!empty($_FILES['path']['name'][0])){
        	$attachDB = array();
        	if($this->upload->do_multi_upload("path")) {

	            $dataAtt = $this->upload->get_multi_upload_data();

				foreach ($dataAtt as $key => $value) {
					$attachDB[] = array(
									"policy_id"=>$policy_id,
									"path"=>$value['file_name'],
									);
				}
	        }
	        $this->db->insert_batch('policy_attachment', $attachDB);
        }

		if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(["result"=>false]);    
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(["result"=>true]);
        }
	}

	public function deleteAttachment(){

		$this->load->model("Policy_attachment");
		$att = new Policy_attachment;

		$pol_at_id = $this->input->get("pol_at_id");
		$path    = $this->input->get("path");

		$this->db->trans_begin();

		$this->db->query("DELETE FROM policy_attachment WHERE pol_at_id = {$pol_at_id}");

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
			if(unlink("./attachments/policy/".$path)){
				$this->db->trans_commit();
				echo json_encode(["result"=>true]);
			}
			else{
				$this->db->trans_rollback();
				echo json_encode(["result"=>false]);
			}
		}
	}

	public function loadAttachments(){
		$this->load->model("Policy_attachment");
		$att = new Policy_attachment;

		$policy_id = $this->input->get("policy_id");
		$attachments = $att->search(["policy_id"=>$policy_id]);

		$img = "";

		if(!empty($attachments)){

			foreach ($attachments as $key => $value) {

				$type = pathinfo($value->path, PATHINFO_EXTENSION);
				
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
					$img .= "<div id='rtpa_".$value->pol_at_id."' class='pull-left clearfix rtp_att' style='margin-right:10px'>";
							
								$img .= "<button onclick=\"deleteRtpAtt(".$value->pol_at_id.",'".$value->path."', this)\" data-toggle=\"tooltip\" data-placement=\"left\" title='Delete attachment' type=\"button\" class=\"close\"><span aria-hidden=\"true\">&times;</span></button>";
							
					$img .=	"<img data-toggle=\"tooltip\" data-placement=\"bottom\" title='".$value->path."' path='".$value->path."' src='".$fileIcon[$type]."' class='img img-responsive img-thumbnail' style='width:150px;cursor:pointer;'>
								<h6>".substr($value->path, 10)."</h6>
							</div> ";
				}
				else{
					$img .= "<div id='rtpa_".$value->pol_at_id."' class='pull-left clearfix rtp_att' style='margin-right:10px'>";
							
								$img .= "<button onclick=\"deleteRtpAtt(".$value->pol_at_id.",'".$value->path."', this)\" data-toggle=\"tooltip\" data-placement=\"left\" title='Delete attachment' type=\"button\" class=\"close\"><span aria-hidden=\"true\">&times;</span></button>";
							
					$img .=	"<img onclick=\"viewAttachment($(this))\" path='".$value->path."' src='".base_url("images/tempAttachmentImg/".$value->path)."' class='img img-responsive img-thumbnail' style='width:200px;cursor:pointer;'>
							</div> ";
				}
			}
		}
		echo $img;
	}

	public function deletePolicy(){
		$this->load->model("Policy");
		$policy = new Policy;

		$policy_id = $this->input->get("policy_id");

		if($this->isBeingUsed($policy_id)){
			echo json_encode(["result"=>"used"]);
		}
		else{

			$this->db->trans_begin();

			$policy->load($policy_id);
			$policy->delete();

			$this->deleteAttachments($policy_id);
			$this->db->query("DELETE FROM policy_attachment WHERE policy_id = $policy_id");

			if ($this->db->trans_status() === FALSE)
			{
				
			    $this->db->trans_rollback();
			    echo json_encode(["result"=>false]);
			}
			else
			{
				
				$this->db->trans_commit();
				echo json_encode(["result"=>true]);
			}
		}
	}

	private function deleteAttachments($policy_id){
		$this->load->model("Policy_attachment");
		$att = new Policy_attachment;

		$attachments = $att->search(["policy_id"=>$policy_id]);

		if(!empty($attachments)){
			foreach ($attachments as $key => $value) {
				unlink("./attachments/policy/".$value->path);
			}
		}
	}

	private function isBeingUsed($policy_id){
		$this->load->model("Procedures");
		$procedure = new Procedures;

		$search = $procedure->search(["policy_id"=>$policy_id, "status"=>"approved"]);
		if(!empty($search)){
			return true;
		}
		else{
			return false;
		}
	}
}