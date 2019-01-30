<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Mail_circulate extends My_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}

		$this->load->model("Circulation_mail");
		$this->load->model('Circulation_attachments');
		$this->load->model('Circulation_mail_sent');
		$this->load->helper("MY_test_helper");
		$this->load->model("Personal_info");
		$this->load->model("My_mail");
		$this->load->model("Ozekimessageout");
		$this->load->model("Imap_circulation");
	}
	public function index()
	{
		// $this->retrieve_emails();
		$data['title'] = 'Circulate Mail';
		$data['title_view'] = "Circulate Mail";
		
		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('circulation/index');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function retrieve_emails(){
		$circulation = new Imap_circulation;
		$circulation->getMails();
	}

	public function load_inbox(){

		$mail = new Circulation_mail;
		$list = $mail->retrieve_mails();

		$inbox = array();
		foreach($list as $key=>$value){
			
			$message = $value->mail_message;

			// if (strpos($message, "Content-Type: text/plain; charset=UTF-8") !== false) {
			//     $message = str_replace("Content-Type: text/plain; charset=UTF-8", "", $value->mail_message);
			//     $message = substr($message, 30, -32);
			// }
			$star = "fa-star";
			if($value->starred == "no"){
				$star = "fa-star-o";
			}

			// $inbox[] = array(
			// 				"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_inbox' name=\"inbox_id[]\" value='".$value->mail_id."' type=\"checkbox\" />",
			// 				"star"=>"<a onclick=\"update_star(".$value->mail_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
			// 				"from"=>"<a href='".base_url('mail_circulate/view/'.base64_encode($value->mail_id))."'><b>".$value->mail_from."</b></a>",
			// 				"subject"=>"<div style=\"width:800px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden\"><b>".$value->mail_subject."</b>&nbsp;&nbsp;&nbsp;&nbsp;</div>",
			// 				"attachment"=>$this->count_att_cir($value->mail_no),
			// 				"date"=>$this->format_date($value->mail_date),
			// 				"read_status"=>$value->on_read,
			// 				);
			$inbox[] = array(
							"<input style='width:16px;height:16px;' class='checkboxmail_inbox' name=\"inbox_id[]\" value='".$value->mail_id."' type=\"checkbox\" />",
							"<a onclick=\"update_star(".$value->mail_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
							"<a href='".base_url('mail_circulate/view/'.base64_encode($value->mail_id))."'><b>".$value->mail_from."</b></a>",
							"<div style=\"width:800px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden\"><b>".$value->mail_subject."</b>&nbsp;&nbsp;&nbsp;&nbsp;</div>",
							$this->count_att_cir($value->mail_no),
							$this->format_date($value->mail_date),
							$value->on_read,
							);
		}
			
		echo json_encode(["data"=>$inbox]);
	}

	private function count_att_cir($mail_no){
		$att = new Circulation_attachments;
		$count = $att->search(array("mail_no"=>$mail_no));

		if(!empty($count)){
			return "<i class='fa fa-paperclip'></i>";
		}
		else{
			return "";
		}
	}

	public function make_important(){
		$mail = new Circulation_mail;
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
			$mail = new Circulation_mail;

			$mail->db->where("mail_id",$mail_id);
			$info = $mail->get();

			if(!empty($info)){

				$this->update_read_stat($info[$mail_id]->mail_id);

				$message = str_replace('\r\n', "<br>", $info[$mail_id]->mail_message);
				// $per_info = $p_info->get_person_info($info[$mail_id]->pi_id);
				// $receiver = $this->get_receiver_dispatch($info[$mail_id]->mail_id);
				$attachments = $this->get_attachments_circulation($info[$mail_id]->mail_no);

				$data['title'] = 'Circulation Mail | View';
				$data['title_view'] = ucwords($info[$mail_id]->mail_subject);

				$data['mail_id'] = $info[$mail_id]->mail_id;
				$data['subject'] = $info[$mail_id]->mail_subject;
				$data['to'] = "me";
				$data['content'] = $message;
				$data["date"] = $this->format_date($info[$mail_id]->mail_date);
				$data["ellapse"] = humanTiming(strtotime($info[$mail_id]->mail_date));
				$data["from"] = $info[$mail_id]->mail_from;
				$data["img_path"] = "default.png";
				$data['attachments'] = $attachments[0];
				$data['att_array'] = $attachments[1];
				$data['contacts'] = $this->load_contacts();
			
				$this->load->view('includes/header', $data);
				$this->load->view('includes/top_nav');
				$this->load->view('includes/sidebar');
				$this->load->view('circulation/view', $data);
				$this->load->view('includes/bottom_nav');
				$this->load->view('includes/footer');
			}
			else{
				// redirect('./mail_dispatch');
			}
	}

	private function update_read_stat($mail_id){
				// UPDATE READ STATUS
		$dis = new Circulation_mail;
		$search = $dis->search(array("mail_id"=>$mail_id));

		if(!empty($search)){
			$dis->load($mail_id);
			$dis->on_read = "yes";
			$dis->save();
		}
	}

	private function get_attachments_circulation($mail_no){
		$mail = new Circulation_attachments;

		$att = $mail->search(array("mail_no"=>$mail_no));

		if(count($att) > 0){
			foreach ($att as $key => $value) {
				$attach[] = $value->mail_path;
				$array_att[] = "./attachments/emails/circulation/".$value->mail_path;
			}

			return array(retrieve_attachments("./attachments/emails/circulation/",$attach), $array_att);
		}
	}

	private function load_contacts(){

		$per_info = new Personal_info;

		$per_info->db->where("status = 'active'");
		$per_info->db->where("pi_id != {$this->userInfo->pi_id}");
		$contact = $per_info->get();

		$options = array();

		foreach ($contact as $key => $value) {
			$options[] = array('email'=>$value->pi_id, 'first_name'=>$value->firstname, 'last_name'=>$value->lastname);
		}
		return json_encode($options);
	}

	public function forward(){
		
		$contact = $this->input->post("txtcontact");
		$mail_id = $this->input->post("mail_id");

		$forSendingSMS = array();

		if(!empty($contact)){
			foreach ($contact as $key => $value) {
				// get mobile numbers 
				$forSendingSMS[] = array(
										"receiver" => $this->getContactNum($value),
										"msg"      => "Test Email SMS",
										"status"   => "send"
										);

				$sent = new Circulation_mail_sent;

				$sent->db->where("mail_id",$mail_id);
				$sent->db->where("pi_id", $value);
				$search = $sent->get();

				if(!empty($search)){

					foreach ($search as $key => $value) {
						$sent_id = $value->sent_id;
					}

					$sent->load($sent_id);
					$sent->sent_date = date("Y-m-d H:i:s");

					$sent->save();
				}
				else{
					$sent->mail_id = $mail_id;
					$sent->pi_id = $value;
					$sent->sent_date = date("Y-m-d H:i:s");
					$sent->on_read = "no";

					$sent->save();
				}
				
			}
			$this->sendEmailSMS($forSendingSMS);
			$result = true;
			$msg = "Your message has been sent.";
		}
		else{
			$result = false;
			$msg = "Please specify atleast one recipient";
		}
		
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	private function getContactNum($pi_id){
		$per_info = new Personal_info;
		$info = $per_info->search(array("pi_id"=>$pi_id));
		foreach ($info as $key => $value) {
			return $value->contact_no;
		}
	}

	private function sendEmailSMS($data){
		$ozeki = new Ozekimessageout;
		$ozeki->sendSMS($data);
	}

	public function delete_inbox(){

		$mail_id = $this->input->post("inbox_id");

		foreach ($mail_id as $sent_id) {
		
			$inbox = new Circulation_mail;

			$inbox->load($sent_id);
			$inbox->on_delete = date("Y-m-d H:i:s");
			$inbox->save();
		}

		$result = true;
		$msg = "The conversation has been moved to the Trash.";
		
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function load_trash(){

		$mail = new Circulation_mail;
		$list = $mail->retrieve_trash();

		$inbox = array();
		foreach($list as $key=>$value){
			
			$message = $value->mail_message;

			// if (strpos($message, "Content-Type: text/plain; charset=UTF-8") !== false) {
			//     $message = str_replace("Content-Type: text/plain; charset=UTF-8", "", $value->mail_message);
			//     $message = substr($message, 30, -32);
			// }
			$star = "fa-star";
			if($value->starred == "no"){
				$star = "fa-star-o";
			}

			$inbox[] = array(
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_trash' name=\"trash_id[]\" value='".$value->mail_id."' type=\"checkbox\" />",
							"star"=>"<a onclick=\"update_star(".$value->mail_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
							"from"=>"<a href='".base_url('mail_circulate/view/'.base64_encode($value->mail_id))."'><b>".$value->mail_from."</b></a>",
							"subject"=>"<div style=\"width:800px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden\"><b>".$value->mail_subject."</b>&nbsp;&nbsp;&nbsp;&nbsp;".strip_tags($value->mail_message)."</div>",
							"attachment"=>$this->count_att_cir($value->mail_no),
							"date"=>$this->format_date($value->mail_date),
							"read_status"=>$value->on_read,
							);
		}
		
		echo json_encode($inbox);
	
	}

	public function restore_inbox(){

		$inbox = new Circulation_mail;
		$mail_id = $this->input->post("trash_id");

		foreach ($mail_id as $sent_id) {
		
			$inbox = new Circulation_mail;

			$inbox->load($sent_id);
			$inbox->on_delete = "";
			$inbox->save();
		}

		$result = true;
		$msg = "The conversation has been moved to the Inbox.";
		
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function load_sent(){
		$mail = new Circulation_mail_sent;
		// $list = $mail->retrieve_sent();
		$mail->toJoin = array("Personal_info"=>"Circulation_mail_sent", "Circulation_mail"=>"Circulation_mail_sent");
		$list = $mail->get();
		
		$sent = array();

		foreach($list as $key=>$value){
			
			$message = $value->mail_message;

			// if (strpos($message, "Content-Type: text/plain; charset=UTF-8") !== false) {
			//     $message = str_replace("Content-Type: text/plain; charset=UTF-8", "", $value->mail_message);
			//     $message = substr($message, 30, -32);
			// }
			$star = "fa-star";
			if($value->starred == "no"){
				$star = "fa-star-o";
			}

			$sent[] = array(
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_sent' name=\"sent_id[]\" value='".$value->mail_id."' type=\"checkbox\" />",
							"star"=>"<a class='hide' onclick=\"update_star(".$value->mail_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
							"from"=>"<a href='".base_url('mail_circulate/view/'.base64_encode($value->mail_id))."'><b>".$value->fullName('f l')."</b></a>",
							"subject"=>"<div style=\"width:800px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden\"><b>".$value->mail_subject."</b>&nbsp;&nbsp;&nbsp;&nbsp;".strip_tags($value->mail_message)."</div>",
							"attachment"=>$this->count_att_cir($value->mail_no),
							"date"=>$this->format_date($value->mail_date),
							);
		}
		
		echo json_encode($sent);
	}
}
?>