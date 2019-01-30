<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Mail_dispatch extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}

		$this->load->model("Dispatch_mail");
		$this->load->model("Dispatch_attachments");
		$this->load->model("Dispatch_sent");
		$this->load->model("Personal_info");
		$this->load->helper("MY_test_helper");
		$this->load->model("Phpmailermodel");
	}
	public function index()
	{
		$data['title'] = 'Dispatch Mail';
		$data['title_view'] = "Dispatch Mail";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('dispatch/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function load_inbox(){

		$mail = new Dispatch_mail;
		$per_info = new Personal_info;
		
		$mail->db->where("(on_delete is null OR on_delete = '')");
		$mail->db->order_by("mail_id", "DESC");
		$dispatch = $mail->get();

		if(!empty($dispatch)){

			$newList = array();

			foreach ($dispatch as $key => $value) {
				$p_info = $per_info->get_person_info($value->pi_id);

				$star = "fa-star";
				if($value->starred == "no"){
					$star = "fa-star-o";
				}

				$newList[] = array(
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_inbox' name=\"inbox_id[]\" value='".$value->mail_id."' type=\"checkbox\" />",
							"star"=>"<a onclick=\"update_star(".$value->mail_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
							"from"=>"<a href='".base_url('mail_dispatch/view/'.base64_encode($value->mail_id))."'><b>".$p_info[0]."</b></a>",
							"subject"=>"<div style=\"width:800px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden\"><b>".$value->mail_subject."</b>&nbsp;&nbsp;&nbsp;&nbsp;".strip_tags($value->mail_content)."</div>",
							"attachment"=>$this->count_att_dis($value->mail_id),
							"date"=>$this->format_date($value->mail_date),
							"read_status"=>$value->read_status,
							"send_status"=>$value->on_sent
							);
			}
			echo json_encode($newList);
		}
		else{
			echo json_encode(array());
		}
	
	}

	private function count_att_dis($mail_id){
		$att = new Dispatch_attachments;
		$count = $att->search(array("mail_id"=>$mail_id));

		if(!empty($count)){
			return "<i class='fa fa-paperclip'></i>";
		}
		else{
			return "";
		}
	}

	private function format_date($date){
		$today = date("Y-m-d");
		$year = date('Y');

		if($today == date_format(date_create($date),"Y-m-d")){
			return date_format(date_create($date),"g:i A");
		}
		else{
			if($year == date_format(date_create($date),"Y")){
				return date_format(date_create($date),"M j");
			}
			else{
				return date_format(date_create($date),"n-j-y");
			}
		}
	}

	public function view($mail_id = false){

			$mail_id = base64_decode($mail_id);
		// GET DATA
			$p_info = new Personal_info;
			$mail = new Dispatch_mail;
			
			$mail->db->where("mail_id",$mail_id);
			$info = $mail->get();

			if(!empty($info)){

				$this->update_read_stat($info[$mail_id]->mail_id);

				$per_info = $p_info->get_person_info($info[$mail_id]->pi_id);
				$receiver = $this->get_receiver_dispatch($info[$mail_id]->mail_id);
				$attachments = $this->get_attachments_dispatch($info[$mail_id]->mail_id);

				$data['title'] = 'Dispatch Mail | View';
				$data['title_view'] = ucwords($info[$mail_id]->mail_subject);

				$data['mail_id'] = $info[$mail_id]->mail_id;
				$data['subject'] = $info[$mail_id]->mail_subject;
				$data['to'] = $receiver[0];
				$data['content'] = $info[$mail_id]->mail_content;
				$data["date"] = $this->format_date($info[$mail_id]->mail_date);
				$data["ellapse"] = humanTiming(strtotime($info[$mail_id]->mail_date));
				$data["from"] = $per_info[0];
				$data["img_path"] = $per_info[1];
				$data['attachments'] = $attachments[0];
				$data['att_array'] = $attachments[1];
				$data['json'] = json_encode($receiver[1]);
				$data['array'] = $receiver[1];

				$this->load->view('includes/header', $data);
				$this->load->view('includes/top_nav');
				$this->load->view('includes/sidebar');
				$this->load->view('dispatch/view', $data);
				$this->load->view('includes/bottom_nav');
				$this->load->view('includes/footer');
			}
			else{
				// redirect('./mail_dispatch');
			}
		
	}

	private function update_read_stat($mail_id){
				// UPDATE READ STATUS
		$dis = new Dispatch_mail;
		$search = $dis->search(array("mail_id"=>$mail_id));

		if(!empty($search)){
			$dis->load($mail_id);
			$dis->read_status = "yes";
			$dis->save();
		}
	}

	private function update_send_stat($mail_id){
				// UPDATE READ STATUS
		$dis = new Dispatch_mail;
		$search = $dis->search(array("mail_id"=>$mail_id));

		if(!empty($search)){
			$dis->load($mail_id);
			$dis->on_sent = "yes";
			$dis->save();
		}
	}

	private function get_receiver_dispatch($mail_id){
		$sent = new Dispatch_sent;

		$sent->db->where("mail_id",$mail_id);
		$sent->db->limit(2);
		$list = $sent->get();

		$names = "";
		$recip = array();
		foreach($list as $l){
			$names .= $l->email.", ";
			$recip[] = array('email'=>$l->email, 'first_name'=>$l->email, 'last_name'=>"");
		}
		return array(substr($names, 0, -2), $recip);
	}

	private function get_attachments_dispatch($mail_id){
		$mail = new Dispatch_attachments;

		$att = $mail->search(array("mail_id"=>$mail_id));

		if(count($att) > 0){
			foreach ($att as $key => $value) {
				$attach[] = $value->attachment_path;
				$array_att[] = "./attachments/emails/dispatch/".$value->attachment_path;
			}

			return array(retrieve_attachments("./attachments/emails/dispatch/",$attach), $array_att);
		}
	}

	public function make_important(){
		$mail = new Dispatch_mail;
		$mail_id = $this->input->get("star");

		$get_stat = $mail->search(array("mail_id"=>$mail_id));

		if($get_stat[$mail_id]->starred == "no"){
			$mail->load($mail_id);
			$mail->starred = "yes";
			$mail->save();
		}
		elseif($get_stat[$mail_id]->starred == "yes"){
			$mail->load($mail_id);
			$mail->starred = "no";
			$mail->save();
		}
		
	}

	public function dispatch(){

		$mail = new Phpmailermodel;

		$mail_id = $this->input->post("mail_id");
		$recipients = $this->input->post('txtcontact');
		$files = unserialize($_POST['attachments']);
		$content = $this->input->post('mail_content');
		$subject = $this->input->post('mail_subject');

		$mail->subject = $subject;
		$mail->message = $content;
		$mail->to = $recipients;
		$mail->attachments = $files;

		if($mail->send_email())
		{
			$result = true;
			$msg = "Your message has been sent.";
			
			$this->update_send_stat($mail_id);

		}
		else{
			$result = false;
			$msg = "Unable to send message.";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function delete_inbox(){

		$inbox = new Dispatch_mail;
		$mail_id = $this->input->post("inbox_id");

		foreach ($mail_id as $sent_id) {
		
			$inbox = new Dispatch_mail;

			$inbox->load($sent_id);
			$inbox->on_delete = date("Y-m-d H:i:s");
			$inbox->save();
		}

		$result = true;
		$msg = "The conversation has been moved to the Trash.";
		
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function load_trash(){

		$mail = new Dispatch_mail;
		$per_info = new Personal_info;
		
		$mail->db->where("(on_delete != '')");
		$mail->db->order_by("mail_id", "DESC");
		$dispatch = $mail->get();

		if(!empty($dispatch)){

			$newList = array();

			foreach ($dispatch as $key => $value) {
				$p_info = $per_info->get_person_info($value->pi_id);

				$star = "fa-star";
				if($value->starred == "no"){
					$star = "fa-star-o";
				}

				$newList[] = array(
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_trash' name=\"trash_id[]\" value='".$value->mail_id."' type=\"checkbox\" />",
							"star"=>"<a onclick=\"update_star(".$value->mail_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
							"from"=>"<a href='".base_url('mail_dispatch/view/'.base64_encode($value->mail_id))."'><b>".$p_info[0]."</b></a>",
							"subject"=>"<div style=\"width:800px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden\"><b>".$value->mail_subject."</b>&nbsp;&nbsp;&nbsp;&nbsp;".strip_tags($value->mail_content)."</div>",
							"attachment"=>$this->count_att_dis($value->mail_id),
							"date"=>$this->format_date($value->mail_date),
							"read_status"=>$value->read_status,
							"send_status"=>$value->on_sent
							);
			}
			echo json_encode($newList);
		}
		else{
			echo json_encode(array());
		}
	
	}

	public function restore_inbox(){

		$inbox = new Dispatch_mail;
		$mail_id = $this->input->post("trash_id");

		foreach ($mail_id as $sent_id) {
		
			$inbox = new Dispatch_mail;

			$inbox->load($sent_id);
			$inbox->on_delete = "";
			$inbox->save();
		}

		$result = true;
		$msg = "The conversation has been moved to the Inbox.";
		
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}


}
?>