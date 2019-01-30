<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Mail extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model("Securitys");
		$p_access = new Securitys;
		if($this->userInfo->user_type != "admin"){
			$p_access->check_page($this->uri->segment(1));
		}

		$this->load->model("Personal_info");
		$this->load->model("My_mail");
		$this->load->model("My_mail_sent");
		$this->load->model("My_mail_attachments");
		$this->load->helper("MY_test_helper");
		$this->load->model("Dispatch_mail");
		$this->load->model("Dispatch_sent");
		$this->load->model("Dispatch_attachments");
		$this->load->model("Circulation_mail");
		$this->load->model("Circulation_mail_sent");
		$this->load->model("Circulation_Attachments");
	}
	public function index()
	{
		// $data['title'] = 'My Mail';
		// $data['title_view'] = "My Mail";
		// $data['contacts'] = $this->load_contacts();

		// $this->load->view('includes/header', $data);
		// $this->load->view('includes/top_nav');
		// $this->load->view('includes/sidebar');
		// $this->load->view('my_mail/index', $data);
		// $this->load->view('includes/bottom_nav');
		// $this->load->view('includes/footer');
		$this->sample();
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

	// SAVE IN MY MAIL TABLE
	public function insert_my_mail(){

		$my_mail = new My_mail;

		$recipient = $this->input->post("txtcontact");
		
		if(!empty($recipient)){

			$my_mail->mail_subject = $this->input->post("mail_subject");
			$my_mail->mail_content = $this->input->post("mail_content");
			$my_mail->pi_id = $this->userInfo->pi_id;
			$my_mail->mail_date = date("Y-m-d H:i:s");

			$my_mail->save();

			if($my_mail->db->affected_rows() > 0){

				$mail_id = $my_mail->db->insert_id();

				$path_db = "";
				$title = "";
				$id = "";

				$dispatch_count = 0;
				$my_mail_count = 0;
				foreach($recipient as $rep){

					if(is_numeric($rep)){
						$my_mail_count++;

						if($my_mail_count == 1){

							$path_db = "./attachments/emails/my_mail/";
							$title = "my_mail_att_";
							$id = $mail_id;

							if(!empty($_FILES['rta_attachment']) > 0){
								// UPLOAD ATTACHMENTS
								$files = upload_files($path_db, time().$title.$id, $_FILES['rta_attachment']);
								// SAVE PATH DB
								if( !empty( $files ) )
								{
								    foreach($files as $path){
										$this->save_path_db($mail_id,$path);
									}
								}
							}

						}

						$sent = new My_mail_sent;

						$sent->mail_id = $mail_id;
						$sent->pi_id = $rep;
						$sent->starred = "no";
						$sent->read_status = "no";
						$sent->thread_no = $this->thread_no($rep);
						$sent->notified = "no";

						$sent->save();
					}

					elseif(!is_numeric($rep)){
						// DISPATCH EMAIL
						$dispatch_count++;
						
						if($dispatch_count == 1){
							$dis_id = $this->dispatch_mail($this->input->post("mail_subject"), $this->input->post("mail_content"));

							// CODE FOR UPLOAD AND ATTACHMENTS AND SAVE path_db DB
							$path_db = "./attachments/emails/dispatch/";
							$title = "dispatch_att_";
							$id = $dis_id;

							if(!empty($_FILES['rta_attachment']) > 0){
								// UPLOAD ATTACHMENTS
								$files = upload_files($path_db, time().$title.$id, $_FILES['rta_attachment']);
								// SAVE PATH DB
								if( !empty( $files ) )
								{
								    foreach($files as $path){
										$this->save_path_db_dispatch($dis_id,$path);
									}
								}
							}
						}
						// SAVE DISPATCH_SENT TABLE
						$this->dispatch_sent($dis_id, $rep);
						
					}

				}

				$result = true;
				$msg = "Your message has been sent.";
			}
			else{
				$result = false;
				$msg = "Unable to send message.";
			}
			
		}
		else{
			$result = false;
			$msg = "Please specify atleast one recipient";
		}

		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}

	private function thread_no($r){
		return strtotime(date("Y-m-d H:i:s")).$this->userInfo->pi_id.$r.random_string('alnum', 5);;
	}

	private function save_path_db($mail_id,$path){

		$att = new My_mail_attachments;

		$att->mail_id = $mail_id;
		$att->attachment_path = $path;
		$att->pi_id = $this->userInfo->pi_id;
		$att->save();

		if($att->db->affected_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}

	private function save_path_db_dispatch($mail_id,$path){

		$att = new Dispatch_attachments;

		$att->mail_id = $mail_id;
		$att->attachment_path = $path;

		$att->save();

		if($att->db->affected_rows() > 0){
			return true;
		}
		else{
			return false;
		}
	}

	private function dispatch_mail($subject, $content){

		$dispatch = new Dispatch_mail;

		$dispatch->mail_subject = $subject;
		$dispatch->mail_content = $content;
		$dispatch->pi_id        = $this->userInfo->pi_id;
		$dispatch->mail_date    = date("Y-m-d H:i:s");
		$dispatch->read_status  = "no";
		$dispatch->starred      = "no";
		$dispatch->on_sent      = "no";

		$dispatch->save();

		if($dispatch->db->affected_rows() > 0){
			return $dispatch->db->insert_id();
		}
	}

	private function dispatch_sent($mail_id, $email){
		$dispatch_sent = new Dispatch_sent;

		$dispatch_sent->mail_id = $mail_id;
		$dispatch_sent->email = $email;
		$dispatch_sent->save();
	}

	public function load_sent_emails(){

		$array[] = $this->load_my_mail_sent();
		array_push($array, $this->load_dispatch_sent());

		$newList = array();
		foreach ($array[0] as $key => $value) {
			$newList[] = $value;
		}
		foreach ($array[1] as $key => $value) {
			$newList[] = $value;
		}
		foreach ($newList as $key => $part) {
		    $sort[$key] = strtotime($part['edate']);
		}

		array_multisort($sort, SORT_DESC, $newList);
		echo json_encode($newList);
	}

	public function load_my_mail_sent(){

		$mail = new My_mail;
		// $mail->toJoin = array("My_mail"=>"My_mail_sent");
		$mail->db->from("my_mail_sent");
		$mail->db->where("my_mail.pi_id",$this->userInfo->pi_id);
		$mail->db->where("(my_mail.on_delete_mail is null OR my_mail.on_delete_mail = '')");
		$mail->db->where("my_mail.mail_id = my_mail_sent.mail_id");
		$mail->db->order_by("my_mail.mail_id","ASC");

		$list = $mail->get();

		$lists = array();

		if(!empty($list)){

			foreach ($list as $key => $value) {

				$lists[] = array(
							"id"=>"my_".$value->mail_id,
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_sent' name='checkbox_sent[]' value='my_mail_".$value->mail_id."' type=\"checkbox\" />",
							"to"=>"<a href='".base_url('mail/view_sent/local/'.base64_encode($value->mail_id))."'><b>To:</b> ".$this->get_receiver_my_mail($value->mail_id)."</a>",
							"subject"=>"<b>".$value->mail_subject."</b> - ".substr(strip_tags($value->mail_content), 0, 70),
							"attachment"=>$this->count_att($value->mail_id),
							"date"=>$this->format_date($value->mail_date),
							"edate"=>$value->mail_date
							);
			}
		}
		
		return $lists;
	}

	public function load_dispatch_sent(){
		$mail = new Dispatch_mail;

		$mail->db->where("pi_id",$this->userInfo->pi_id);
		$mail->db->where("(on_delete is null OR on_delete = '')");
		$mail->db->order_by("mail_id","ASC");
		$list = $mail->get();

		$sent = array();

		if(!empty($list)){
			foreach($list as $l){
				$sent[] = array(
							"id"=>"dis_".$l->mail_id,
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_sent' name='checkbox_sent[]' value='dispatch_".$l->mail_id."' type=\"checkbox\" />",
							"to"=>"<a href='".base_url('mail/view_sent/dispatch/'.base64_encode($l->mail_id))."'><b>To:</b> ".$this->get_receiver_dispatch($l->mail_id)."</a>",
							"subject"=>"<b>".$l->mail_subject."</b> - ".substr(strip_tags($l->mail_content), 0, 70),
							"attachment"=>$this->count_att_dis($l->mail_id),
							"date"=>$this->format_date($l->mail_date),
							"edate"=>$l->mail_date
							);
			}
		}
		
		return $sent;
	}

	public function view_sent($type = false, $mail_id = false){

		$mail_id = base64_decode($mail_id);
		// GET DATA
		
		if($type == "local"){

			$mail = new My_mail;
			$mail->db->where("my_mail.mail_id",$mail_id);
			$mail->db->where("pi_id", $this->userInfo->pi_id);
			$info = $mail->get();
			$per_info = $this->get_personal_info($mail_id);

			if(!empty($info)){
			
				$data['title'] = 'My Mail | View';
				$data['title_view'] = ucwords($info[$mail_id]->mail_subject);
				$data['subject'] = $info[$mail_id]->mail_subject;
				$data['to'] = $this->get_receiver_my_mail($info[$mail_id]->mail_id);
				$data['content'] = $info[$mail_id]->mail_content;
				$data["date"] = $this->format_date($info[$mail_id]->mail_date);
				$data["ellapse"] = humanTiming(strtotime($info[$mail_id]->mail_date));
				$data["from"] = $per_info[0];
				$data["img_path"] = $per_info[1];
				$data['attachments'] = $this->get_attachments($info[$mail_id]->mail_id);

				$this->load->view('includes/header', $data);
				$this->load->view('includes/top_nav');
				$this->load->view('includes/sidebar');
				$this->load->view('my_mail/view_sent', $data);
				$this->load->view('includes/bottom_nav');
				$this->load->view('includes/footer');
			}
			else{
				redirect('./mail');
			}
		}
		elseif($type == "dispatch"){
			$p_info = new Personal_info;
			$mail = new Dispatch_mail;
			
			$mail->db->where("mail_id",$mail_id);
			$mail->db->where("pi_id", $this->userInfo->pi_id);
			$info = $mail->get();

			if(!empty($info)){

				$per_info = $p_info->get_person_info($info[$mail_id]->pi_id);

				$data['title'] = 'My Mail | View';
				$data['title_view'] = ucwords($info[$mail_id]->mail_subject);
				$data['subject'] = $info[$mail_id]->mail_subject;
				$data['to'] = $this->get_receiver_dispatch($info[$mail_id]->mail_id);
				$data['content'] = $info[$mail_id]->mail_content;
				$data["date"] = $this->format_date($info[$mail_id]->mail_date);
				$data["ellapse"] = humanTiming(strtotime($info[$mail_id]->mail_date));
				$data["from"] = $per_info[0];
				$data["img_path"] = $per_info[1];
				$data['attachments'] = $this->get_attachments_dispatch($info[$mail_id]->mail_id);

				$this->load->view('includes/header', $data);
				$this->load->view('includes/top_nav');
				$this->load->view('includes/sidebar');
				$this->load->view('my_mail/view_sent', $data);
				$this->load->view('includes/bottom_nav');
				$this->load->view('includes/footer');
			}
			else{
				redirect('./mail');
			}
		}
		else{
			redirect('./mail');
		}
	}

	private function get_receiver_my_mail($mail_id){
		$sent = new My_mail_sent;

		$sent->toJoin = array("Personal_info"=>"My_mail_sent");
		$sent->db->where("mail_id",$mail_id);
		$sent->db->limit(2);
		$list = $sent->get();

		$names = "";

		foreach($list as $l){
			$names .= $l->fullName('f l').", ";
		}

		return substr($names, 0, -2);
	}

	private function get_receiver_dispatch($mail_id){
		$sent = new Dispatch_sent;

		$sent->db->where("mail_id",$mail_id);
		$sent->db->limit(2);
		$list = $sent->get();

		$names = "";

		foreach($list as $l){
			$names .= $l->email.", ";
		}
		return substr($names, 0, -2);
	}

	private function count_att($mail_id){
		$att = new My_mail_attachments;
		$count = $att->search(array("mail_id"=>$mail_id));

		if(!empty($count)){
			return "<i class='fa fa-paperclip'></i>";
		}
		else{
			return "";
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

	public function load_inbox(){

		$mail = new My_mail_sent;
		$inbox = $mail->load_inbox_mails();

		if(!empty($inbox)){

			$list = array();
			foreach($inbox as $l){
				$mail = new My_mail_sent;

				$mail->toJoin = array("Personal_info"=>"My_mail_sent", "My_mail"=>"My_mail_sent");
				$mail->db->where("thread_no", $l->thread_no);
				$mail->db->where("my_mail_sent.pi_id", $this->userInfo->pi_id);
				$mail->load_last_input();

				$list[] = $mail;
			}

			foreach ($list as $key => $value) {
				$star = "fa-star";
				if($value->starred == "no"){
					$star = "fa-star-o";
				}

				$newList[] = array(
							"id"=>$value->sent_id,
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_inbox' name=\"inbox_id[]\" value='".$value->sent_id."' type=\"checkbox\" />",
							"star"=>"<a onclick=\"update_star(".$value->sent_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
							"from"=>"<a href='".base_url('mail/view/'.base64_encode($value->sent_id))."'><b>".$this->get_sender_my_mail($value->mail_id)."</b> ".$this->count_tread($value->thread_no)."</a>",
							"subject"=>"<b>".$value->mail_subject."</b> - ".substr(strip_tags($value->mail_content), 0, 70),
							"attachment"=>$this->count_att($value->mail_id),
							"date"=>$this->format_date($value->mail_date),
							"read_status"=>$value->read_status
							);
			}
			echo json_encode($newList);
		}
		else{
			echo json_encode(array());
		}
	
	}

	private function get_sender_my_mail($mail_id){
		$sent = new My_mail;

		$sent->toJoin = array("Personal_info"=>"My_mail");
		$sent->db->where("mail_id",$mail_id);
		$sent->db->limit(2);
		$list = $sent->get();

		$names = "";

		foreach($list as $l){
			$names .= $l->fullName('f l').", ";
		}

		return substr($names, 0, -2);
	}

	public function make_important(){
		$sent = new My_mail_sent;
		$mail_id = $this->input->get("star");

		$get_stat = $sent->search(array("sent_id"=>$mail_id));

		if($get_stat[$mail_id]->starred == "no"){
			$sent->load($mail_id);
			$sent->starred = "yes";
			$sent->save();
		}
		elseif($get_stat[$mail_id]->starred == "yes"){
			$sent->load($mail_id);
			$sent->starred = "no";
			$sent->save();
		}
		
	}

	public function count_tread($thread_no){
		$sent = new My_mail_sent;
		$list = $sent->search(array("thread_no"=>$thread_no));

		if(count($list) > 1){
			return "(".count($list).")";
		}
		elseif(count($list) == 1){
			return false;
		}
	}

	public function view($sent_id = false){

		$sent_id = base64_decode($sent_id);
		// GET DATA
		$mail = new My_mail_sent;
		$mail->toJoin = array("My_mail"=>"My_mail_sent");
		$mail->db->where("my_mail_sent.sent_id",$sent_id);
		$info = $mail->get();

		if(!empty($info)){
			$this->update_read_stat($sent_id);

			$data['title'] = 'My Mail | View';
			$data['title_view'] = ucwords($info[$sent_id]->mail_subject);
			$data['subject'] = $info[$sent_id]->mail_subject;
			$data['thread'] = $this->fetch_threads($info[$sent_id]->thread_no);
			$data['from'] = $info[$sent_id]->pi_id;
			$data['t_no'] = $info[$sent_id]->thread_no;

			$this->load->view('includes/header', $data);
			$this->load->view('includes/top_nav');
			$this->load->view('includes/sidebar');
			$this->load->view('my_mail/view', $data);
			$this->load->view('includes/bottom_nav');
			$this->load->view('includes/footer');
		}
		else{
			redirect('./mail');
		}

	}

	private function update_read_stat($sent_id){
				// UPDATE READ STATUS
		$sent = new My_mail_sent;
		$search = $sent->search(array("sent_id"=>$sent_id));

		if(!empty($search)){
			$sent->load($sent_id);
			$sent->read_status = "yes";
			$sent->save();
		}
	}

	private function update_read_stat_circ($mail_id){
		$mail = new Circulation_mail_sent;
		$mail->load($mail_id);
		$mail->on_read = "yes";
		$mail->save();
	}

	public function fetch_threads($thread_no){
		$threads = new My_mail_sent;

		$threads->toJoin = array("My_mail"=>"My_mail_sent","Personal_info"=>"My_mail_sent");
		$threads->db->where("thread_no", $thread_no);
		$threads->db->order_by("sent_id","ASC");
		$tr = $threads->get();


		// REQUEST ATTACHMENTS
		
		$list = array();
		foreach ($tr as $key => $value) {
			$per_info = $this->get_personal_info($value->mail_id);
			$list[] = array(
							"from"=>$per_info[0],
							"from_pi_id"=>$per_info[2],
							"img_path"=>$per_info[1],
							"pi_id"=>$value->pi_id,
							"to"=>$value->fullName('f l'),
							"date"=>$this->format_date($value->mail_date),
							"content"=>$value->mail_content,
							"ellapse"=>humanTiming(strtotime($value->mail_date)),
							"attachments"=>$this->get_attachments($value->mail_id)
							);
		}

		return $list;

	}

	private function get_personal_info($mail_id){
		$sent = new My_mail;

		$sent->toJoin = array("Personal_info"=>"My_mail");
		$sent->db->where("mail_id",$mail_id);
		$list = $sent->get();

		$names = "";
		$img_path = "";

		foreach($list as $l){
			$pi_id = $l->pi_id;
			$names = $l->fullName('f l');
			$img_path = $l->img_path;
		}

		return array($names,$img_path,$pi_id);
	}

	private function get_attachments($mail_id){
		$mail = new My_mail_attachments;

		$att = $mail->search(array("mail_id"=>$mail_id));

		if(count($att) > 0){
			foreach ($att as $key => $value) {
				$attach[] = $value->attachment_path;
			}

			return retrieve_attachments("./attachments/emails/my_mail/",$attach);
		}
	}

	private function get_attachments_dispatch($mail_id){
		$mail = new Dispatch_attachments;

		$att = $mail->search(array("mail_id"=>$mail_id));

		if(count($att) > 0){
			foreach ($att as $key => $value) {
				$attach[] = $value->attachment_path;
			}

			return retrieve_attachments("./attachments/emails/dispatch/",$attach);
		}
	}

	public function reply_my_mail(){

		$my_mail = new My_mail;

		$recipient = $this->input->post("txtcontact");
		$thread_no = $this->input->post('thread_no');

		// SAVE MY MAIL TABLE
		$my_mail->mail_subject = $this->input->post("mail_subject");
		$my_mail->mail_content = $this->input->post("mail_content");
		$my_mail->pi_id = $this->userInfo->pi_id;
		$my_mail->mail_date = date("Y-m-d H:i:s");

		$my_mail->save();

		if($my_mail->db->affected_rows() > 0){

			$mail_id = $my_mail->db->insert_id();

			$path_db = "./attachments/emails/my_mail/";
			$title = "my_mail_att_";
			$id = $mail_id;

			if(!empty($_FILES['rta_attachment']) > 0){
				// UPLOAD ATTACHMENTS
				$files = upload_files($path_db, time().$title.$id, $_FILES['rta_attachment']);
				// SAVE PATH DB
				if( !empty( $files ) )
				{
				    foreach($files as $path){
						$this->save_path_db($mail_id,$path);
					}
				}
			}

			// SAVE MY_MAIL SENT TABLE
			$sent = new My_mail_sent;
			$sent->mail_id = $mail_id;
			$sent->pi_id = $recipient;
			$sent->starred = "no";
			$sent->read_status = "no";
			$sent->thread_no = $thread_no;
			$sent->notified = "no";

			$sent->save();
			
			$result = true;
			$msg = "Your message has been sent.";
		}
		else{
			$result = false;
			$msg = "Unable to send message.";
		}
		
		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}

	public function delete_inbox(){


		$inbox = new My_mail_sent;
		$sent = $this->input->post("inbox_id");

		// print_r($sent);
		// LOOP MAIL SELECTED TO BE DELETED
		foreach ($sent as $sent_id) {
			$thread_no = $inbox->search(array("sent_id"=>$sent_id));
			$thread_no = $thread_no[$sent_id]->thread_no;

			// GET ALL EMAIL WITH THE THREAD NUMBER
			$inbox->db->where("thread_no", $thread_no);
			$list = $inbox->get();	

			// UPDATE THE ON_DELETE FIELD IN EACH EMAIL IN A SINGLE THREAD
			foreach($list as $l){
				$inbox = new My_mail_sent;

				$inbox->load($l->sent_id);
				$inbox->on_delete = date("Y-m-d H:i:s");
				$inbox->save();
			}
		}

		$result = true;
		$msg = "The conversation has been moved to the Trash.";
		
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function load_circulation(){
		// FETCH FROM CIRCULATION //
		$circulation = new Circulation_mail_sent;

		$newList = array();
		$query = $circulation->db->query("SELECT * from circulation_mail,circulation_mail_sent where circulation_mail_sent.pi_id = {$this->userInfo->pi_id} and circulation_mail_sent.mail_id = circulation_mail.mail_id and (circulation_mail_sent.on_delete is null OR circulation_mail_sent.on_delete = '')");
		
		$list = $query->result();
		foreach ($list as $key => $value) {
			$star = "fa-star";
			if($value->starred == "no"){
				$star = "fa-star-o";
			}
			$newList[] = array(
						"id"=>$value->sent_id,
						"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_circulation' name=\"cir_id[]\" value='".$value->sent_id."' type=\"checkbox\" />",
						"star"=>"<a onclick=\"update_star(".$value->sent_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
						"from"=>"<a href='".base_url('mail/view_circulation/'.base64_encode($value->sent_id))."'><b>".$value->mail_from."</b></a>",
						"subject"=>"<b>".$value->mail_subject."</b> - ".substr(strip_tags($value->mail_message), 0, 70),
						"attachment"=>$this->count_att($value->mail_id),
						"date"=>$this->format_date($value->mail_date),
						"read_status"=>$value->on_read
						);
		}
		echo json_encode($newList);
	}

	public function view_circulation($mail_id = false){
		$sent_id = base64_decode($mail_id);
	// GET DATA
		$mail_sent = new Circulation_mail_sent;
		$mail = new Circulation_mail;

		$search = $mail_sent->search(array("sent_id"=>$sent_id));
		$mail_id = $search[$sent_id]->mail_id;

		$mail->db->where("mail_id", $mail_id);
		$info = $mail->get();

		if(!empty($info)){

			$this->update_read_stat_circ($sent_id);

			$message = str_replace('\r\n', "<br>", $info[$mail_id]->mail_message);
			// $per_info = $p_info->get_person_info($info[$mail_id]->pi_id);
			// $receiver = $this->get_receiver_dispatch($info[$mail_id]->mail_id);
			$attachments = $this->get_attachments_circulation($info[$mail_id]->mail_no);

			$data['title'] = 'Dispatch Mail | View';
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
			$this->load->view('my_mail/view_circulation', $data);
			$this->load->view('includes/bottom_nav');
			$this->load->view('includes/footer');
		}
		else{
			// redirect('./mail_dispatch');
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

	public function sample(){
		$data['title'] = 'My Mail';
		$data['title_view'] = "My Mail";
		$data['contacts'] = $this->load_contacts();
		// $data['sent_mails'] = $this->return_sent();

		$this->load->view('includes/header', $data);
		$this->load->view('includes/top_nav');
		$this->load->view('includes/sidebar');
		$this->load->view('my_mail/sample');
		$this->load->view('includes/bottom_nav');
		$this->load->view('includes/footer');
	}

	public function delete_circulation(){
		$inbox = new circulation_mail_sent;
		$sent = $this->input->post("cir_id");
		foreach ($sent as $sent_id) {
			$inbox->load($sent_id);
			$inbox->on_delete = date("Y-m-d H:i:s");
			$inbox->save();
		}

		$result = true;
		$msg = "The message has been moved to the Trash.";
		echo json_encode(array("result"=>$result, "msg"=>$msg));
	}

	public function trashEmails(){

		$array[] = $this->load_inbox_trash();
		$array[] = $this->load_trash_my_mail_sent();
		$array[] = $this->load_trash_dispatch_sent();
		array_push($array, $this->load_trash_circulation());

		$newList = array();
		foreach ($array[0] as $key => $value) {
			$newList[] = $value;
		}
		foreach ($array[1] as $key => $value) {
			$newList[] = $value;
		}
		foreach ($array[2] as $key => $value) {
			$newList[] = $value;
		}
		foreach ($array[3] as $key => $value) {
			$newList[] = $value;
		}

		foreach ($newList as $key => $part) {
		    $sort[$key] = strtotime($part['edate']);
		}

		if(!empty($newList)){
			array_multisort($sort, SORT_DESC, $newList);
			echo json_encode($newList);
		}
		else{
			echo json_encode(array());
		}
		
	}

	public function load_inbox_trash(){
		$mail = new My_mail_sent;
		$inbox = $mail->load_inbox_mails_trash();

		if(!empty($inbox)){

			$list = array();
			foreach($inbox as $l){
				$mail = new My_mail_sent;

				$mail->toJoin = array("Personal_info"=>"My_mail_sent", "My_mail"=>"My_mail_sent");
				$mail->db->where("thread_no", $l->thread_no);
				$mail->db->where("my_mail_sent.pi_id", $this->userInfo->pi_id);
				$mail->load_last_input();

				$list[] = $mail;
			}

			foreach ($list as $key => $value) {
				$star = "fa-star";
				if($value->starred == "no"){
					$star = "fa-star-o";
				}

				$newList[] = array(
							"id"=>"inbox_".$value->sent_id,
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_trash' name=\"trash_id[]\" value='inbox_".$value->sent_id."' type=\"checkbox\" />",
							"star"=>"<a onclick=\"update_star(".$value->sent_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
							"from"=>"<a href='".base_url('mail/view/'.base64_encode($value->sent_id))."'><b>".$this->get_sender_my_mail($value->mail_id)."</b> ".$this->count_tread($value->thread_no)."</a>",
							"subject"=>"<b>".$value->mail_subject."</b> - ".substr(strip_tags($value->mail_content), 0, 70),
							"attachment"=>$this->count_att($value->mail_id),
							"date"=>$this->format_date($value->on_delete),
							"read_status"=>$value->read_status,
							"edate"=>$value->on_delete,
							);
			}

			return $newList;

		}
		else{
			return array();
		}
	
	}

	public function load_trash_circulation(){
		// FETCH FROM CIRCULATION //
		$circulation = new Circulation_mail_sent;

		$newList = array();
		$query = $circulation->db->query("SELECT * from circulation_mail,circulation_mail_sent where circulation_mail_sent.pi_id = {$this->userInfo->pi_id} and circulation_mail_sent.mail_id = circulation_mail.mail_id and (circulation_mail_sent.on_delete != '')");
		$list = $query->result();

		if(!empty($list)){
			foreach ($list as $key => $value) {
				$star = "fa-star";
				if($value->starred == "no"){
					$star = "fa-star-o";
				}
				$newList[] = array(
							"id"=>"circ_".$value->sent_id,
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_trash' name=\"trash_id[]\" value='circ_".$value->sent_id."' type=\"checkbox\" />",
							"star"=>"<a onclick=\"update_star(".$value->sent_id.")\"class=\"mailbox-star\" href=\"#\"><i class=\"fa ".$star." text-yellow\"></i></a>",
							"from"=>"<a href='".base_url('mail/view_circulation/'.base64_encode($value->sent_id))."'><b>".$value->mail_from."</b></a>",
							"subject"=>"<b>".$value->mail_subject."</b> - ".substr(strip_tags($value->mail_message), 0, 70),
							"attachment"=>$this->count_att($value->mail_id),
							"date"=>$this->format_date($value->on_delete),
							"read_status"=>$value->on_read,
							"edate"=>$value->on_delete,
							);
			}
			return $newList;
		}
		else{
			return array();
		}
		
	}

	public function load_trash_my_mail_sent(){

		$mail = new My_mail;
		// $mail->toJoin = array("My_mail"=>"My_mail_sent");
		$mail->db->from("my_mail_sent");
		$mail->db->where("my_mail.pi_id",$this->userInfo->pi_id);
		$mail->db->where("(my_mail.on_delete_mail != '')");
		$mail->db->where("my_mail.mail_id = my_mail_sent.mail_id");
		$mail->db->order_by("my_mail.mail_id","ASC");

		$list = $mail->get();

		$lists = array();

		if(!empty($list)){

			foreach ($list as $key => $value) {

				$lists[] = array(
							"id"=>"my_".$value->mail_id,
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_sent' name='checkbox_sent[]' value='my_mail_".$value->mail_id."' type=\"checkbox\" />",
							"from"=>"<a href='".base_url('mail/view_sent/local/'.base64_encode($value->mail_id))."'><b>To:</b> ".$this->get_receiver_my_mail($value->mail_id)."</a>",
							"subject"=>"<b>".$value->mail_subject."</b> - ".substr(strip_tags($value->mail_content), 0, 70),
							"attachment"=>$this->count_att($value->mail_id),
							"date"=>$this->format_date($value->mail_date),
							"edate"=>$value->on_delete_mail
							);
			}
		}
		
		return $lists;
	}

	public function load_trash_dispatch_sent(){
		$mail = new Dispatch_mail;

		$mail->db->where("pi_id",$this->userInfo->pi_id);
		$mail->db->where("(on_delete != '')");
		$mail->db->order_by("mail_id","ASC");
		$list = $mail->get();

		$sent = array();

		if(!empty($list)){
			foreach($list as $l){
				$sent[] = array(
							"id"=>"dis_".$l->mail_id,
							"checkbox"=>"<input style='width:16px;height:16px;' class='checkboxmail_sent' name='checkbox_sent[]' value='dispatch_".$l->mail_id."' type=\"checkbox\" />",
							"from"=>"<a href='".base_url('mail/view_sent/dispatch/'.base64_encode($l->mail_id))."'><b>To:</b> ".$this->get_receiver_dispatch($l->mail_id)."</a>",
							"subject"=>"<b>".$l->mail_subject."</b> - ".substr(strip_tags($l->mail_content), 0, 70),
							"attachment"=>$this->count_att_dis($l->mail_id),
							"date"=>$this->format_date($l->mail_date),
							"edate"=>$l->on_delete,
							);
			}
		}
		
		return $sent;
	}

	public function restore_mail(){

		$inbox = new My_mail_sent;
		$sent = $this->input->post("trash_id");

		foreach ($sent as $sent_id){

			if (strpos($sent_id, 'inbox_') !== false) {

			    $sent_id = str_replace("inbox_","",$sent_id);

			    $thread_no = $inbox->search(array("sent_id"=>$sent_id));
				$thread_no = $thread_no[$sent_id]->thread_no;

				// GET ALL EMAIL WITH THE THREAD NUMBER
				$inbox->db->where("thread_no", $thread_no);
				$list = $inbox->get();	

				// UPDATE THE ON_DELETE FIELD IN EACH EMAIL IN A SINGLE THREAD
				foreach($list as $l){
					$inbox = new My_mail_sent;

					$inbox->load($l->sent_id);
					$inbox->on_delete = "";
					$inbox->save();
				}

			}
			elseif(strpos($sent_id, 'circ_') !== false) {
				$sent_id = str_replace("circ_","",$sent_id);
				$circ = new Circulation_mail_sent;

				$circ->load($sent_id);
				$circ->on_delete = "";
				$circ->save();
			}
			elseif (strpos($sent_id, 'my_') !== false) {
				$mail_id = str_replace("my_","",$sent_id);
				$mail = new My_mail;
				$mail->load($mail_id);
				$mail->on_delete_mail = "";
				$mail->save();
			}
			elseif (strpos($sent_id, 'dis_') !== false) {
				$mail_id = str_replace("dis_","",$sent_id);
				$mail = new Dispatch_mail;
				$mail->load($mail_id);
				$mail->on_delete = "";
				$mail->save();
			}
		}
		
		$result = true;
		$msg = "The conversation has been restored to the inbox.";
		echo json_encode(array("result"=>$result, "msg"=>$msg));
		
	}

	public function delete_sent(){

		$sent = $this->input->post("checkbox_sent");

		foreach ($sent as $key => $value) {

			if (strpos($value, 'my_') !== false) {
				$mail_id = str_replace("my_","",$value);
				$mail = new My_mail;
				$mail->load($mail_id);
				$mail->on_delete_mail = date("Y-m-d H:i:s");
				$mail->save();
			}
			elseif (strpos($value, 'dis_') !== false) {
				$mail_id = str_replace("dis_","",$value);
				$mail = new Dispatch_mail;
				$mail->load($mail_id);
				$mail->on_delete = date("Y-m-d H:i:s");
				$mail->save();
			}

		}

		$result = true;
		$msg = "The message has been moved to the Trash.";
		echo json_encode(array("result"=>$result, "msg"=>$msg));

	}


}
?>