<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
class Chats extends My_Controller {

	private $last_chat_id;

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Chat");
		session_start();
	}

	public function load_contacts(){

		$chat = new Chat;

		$list = $chat->get_contacts();
		$display = "";

		$hasMessage = "";
		$online = "";
		$offline = "";

		$chatCount = $this->chatCounter1();

		foreach($list as $value){
			$img = base_url("images/profile_image/{$value->img_path}");
			if($value->img_path == "" || is_null($value->img_path)){
				$img = base_url("images/profile_image/default.png");
			}
			$status = "";
			if($value->online == "yes"){
				$status = "<span class='label label-success'>Online</span>";

				if(array_key_exists($value->pi_id, $chatCount["user"])){
					$hasMessage .= "<div onclick=\"get_contact_info(".$value->pi_id.")\" class=\"contact_item clearfix\">
			              <img class=\"img img-circle contact_img pull-left\" src=\"{$img}\">
			              <div class=\"pull-left contact_info\">
			                <div class=\"contact_name\">".$value->fullName('f l')." <span pi_id={$value->pi_id} class=\"label label-danger chatUserCount\">". $chatCount["user"][$value->pi_id]."</span></div> 
			                <div class=\"contact_position\">".$status."</div>
			              </div>
			            </div>";
				}
				else{
					$online .= "<div onclick=\"get_contact_info(".$value->pi_id.")\" class=\"contact_item clearfix\">
			              <img class=\"img img-circle contact_img pull-left\" src=\"{$img}\">
			              <div class=\"pull-left contact_info\">
			                <div class=\"contact_name\">".$value->fullName('f l')." <span pi_id={$value->pi_id} class=\"label label-danger chatUserCount\"></span></div> 
			                <div class=\"contact_position\">".$status."</div>
			              </div>
			            </div>";
			    }
			}
			else{
				$status = "<span class='label label-default'>Offline</span>";
				if(array_key_exists($value->pi_id, $chatCount["user"])){
					$hasMessage .= "<div onclick=\"get_contact_info(".$value->pi_id.")\" class=\"contact_item clearfix\">
			              <img class=\"img img-circle contact_img pull-left\" src=\"{$img}\">
			              <div class=\"pull-left contact_info\">
			                <div class=\"contact_name\">".$value->fullName('f l')." <span pi_id={$value->pi_id} class=\"label label-danger chatUserCount\">". $chatCount["user"][$value->pi_id]."</span></div> 
			                <div class=\"contact_position\">".$status."</div>
			              </div>
			            </div>";
				}
				else{
					$offline .= "<div onclick=\"get_contact_info(".$value->pi_id.")\" class=\"contact_item clearfix\">
			              <img class=\"img img-circle contact_img pull-left\" src=\"{$img}\">
			              <div class=\"pull-left contact_info\">
			                <div class=\"contact_name\">".$value->fullName('f l')." <span pi_id={$value->pi_id} class=\"label label-danger chatUserCount\"></span></div> 
			                <div class=\"contact_position\">".$status."</div>
			              </div>
			            </div>";
				}
				
			}
		}
		echo $hasMessage.$online.$offline;
	}

	public function get_convo(){
		$chat = new Chat;
		$pi_id = $this->input->get("pi_id");
		$convo = $chat->fetch_conversation($pi_id);

		if(!empty($convo)){
			foreach($convo as $value){
				// UPDATE READ STATUS //
				if($value->chat_to == $this->userInfo->pi_id){
					$this->update_read_status($value->chat_id);
				}
				
				$align = "";
				$info = $this->get_chat_contact_info($pi_id);
				$name = $info['name'];
				$image = $info['img_path'];

				// IF YOUR MESSAGE ALIGN RIGHT //
				if($value->chat_from == $this->userInfo->pi_id){
					$align = "right";
					$name = "Me";
					$image= base_url("images/profile_image/{$this->userInfo->img_path}");
				}

				echo "<div class=\"direct-chat-msg {$align}\">
		                <div class=\"direct-chat-info clearfix\">
		                  <span class=\"direct-chat-name pull-left\">{$name}</span>
		                  <span class=\"direct-chat-timestamp pull-right\">{$this->format_date($value->chat_datetime)}</span>
		                </div>
		                <img onerror=\"imgErrorChat(this)\" class=\"direct-chat-img\" src=\"{$image}\" alt=\"message user image\">
		                <div class=\"direct-chat-text\">
		                	{$value->chat_content}
		                </div>
		              </div>";

		       	$_SESSION['chat_last_id'] = $value->chat_id;
			}
		}
		else{
			// echo "No conversation yet. Send a message.";
		}
	}

	public function get_contact_info(){
		$chat = new Chat;
		$pi_id = $this->input->get("pi_id");
		$info = $chat->get_contact_info($pi_id);

		foreach($info as $value){
			echo json_encode(array(
								"pi_id"=>$value->pi_id,
								"name"=>$value->fullName('f l'),
								"position"=>$value->position,
								"img_path"=>base_url("images/profile_image/{$value->img_path}"),
									));			
		}
	}

	public function send_message(){
		$chat = new Chat;
		$data = array(
					"chat_content"=>$this->input->post("chat_content"),
					"chat_from"=>$this->userInfo->pi_id,
					"chat_to"=>$this->input->post("chat_to"),
					"chat_datetime"=>date("Y-m-d H:i:s")
					);
		$result = $chat->add_message($data);
		echo $result;
	}

	public function get_chat_contact_info($pi_id){
		$chat = new Chat;
		$info = $chat->get_contact_info($pi_id);

		foreach($info as $value){
						return array(
								"pi_id"=>$value->pi_id,
								"name"=>$value->fullName('f l'),
								"position"=>$value->position,
								"img_path"=>base_url("images/profile_image/{$value->img_path}"),
								);			
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
				return date_format(date_create($date),"M j g:i A");
			}
			else{
				return date_format(date_create($date),"n-j-y");
			}
		}
	}

	private function update_read_status($chat_id){
		$chat = new Chat;
		$chat->load($chat_id);
		$chat->status = "yes";
		$chat->save();
	}

	// CHECK THE LAST MESSAGE IF ITS FOR YOU OR FROM YOU
	public function check_last_message(){
		// $this->last_chat_id = $value->chat_id;
		$chat = new Chat;
		$pi_id = $this->input->get("pi_id");
		$chat->get_last_entry($pi_id);
		
		if(!empty($chat)){

			if($_SESSION['chat_last_id'] != $chat->chat_id){

				$align = "";
				$info = $this->get_chat_contact_info($pi_id);
				$name = $info['name'];
				$image = $info['img_path'];

				// IF YOUR MESSAGE ALIGN RIGHT //
				if($chat->chat_from == $this->userInfo->pi_id){
					$align = "right";
					$name = "Me";
					$image= base_url("images/profile_image/{$this->userInfo->img_path}");
				}

				if(!empty($chat->chat_content)){

					echo "<div class=\"direct-chat-msg {$align}\">
			                <div class=\"direct-chat-info clearfix\">
			                  <span class=\"direct-chat-name pull-left\">{$name}</span>
			                  <span class=\"direct-chat-timestamp pull-right\">{$this->format_date($chat->chat_datetime)}</span>
			                </div>
			                <img onerror=\"imgErrorChat(this)\" class=\"direct-chat-img\" src=\"{$image}\" alt=\"message user image\">
			                <div class=\"direct-chat-text\">
			                	{$chat->chat_content}
			                </div>
		              </div>";

				}
				
			}
			else{
				echo "0";
			}

			$_SESSION['chat_last_id'] = $chat->chat_id;
		}
		else{
			echo "0";
		}
		
		
	}

	public function search_contact(){

		$key = $this->input->get("search");
		
		if(!empty($key)){

			$chat = new Chat;
			$key = $this->input->get("search");
			$search = $chat->search_contacts($key);
			$online = "";
			$offline = "";
			
			if(!empty($search)){
				// $display = "";
				// foreach($search as $value){
				// 		$img = base_url("images/profile_image/{$value->img_path}");
				// 		$display .= "<div onclick=\"get_contact_info(".$value->pi_id.")\" class=\"contact_item clearfix\">
				// 		              <img onerror=\"imgErrorChat(this)\" class=\"img img-circle img-thumbnail contact_img pull-left\" src='".$img."'>
				// 		              <div class=\"pull-left contact_info\">
				// 		                <div class=\"contact_name\">".$value->fullName('f l')."</div>
				// 		                <div class=\"contact_position\">".ucwords($value->position)."</div>
				// 		              </div>
				// 		            </div>";
				// 	}
				// 	echo $display;
				foreach($search as $value){
					$img = base_url("images/profile_image/{$value->img_path}");
					if($value->img_path == "" || is_null($value->img_path)){
						$img = base_url("images/profile_image/default.png");
					}

					$status = "";
					if($value->online == "yes"){

						$status = "<span class='label label-success'>Online</span>";

						$online .= "<div onclick=\"get_contact_info(".$value->pi_id.")\" class=\"contact_item clearfix\">
					              <img class=\"img img-circle contact_img pull-left\" src=\"{$img}\">
					              <div class=\"pull-left contact_info\">
					                <div class=\"contact_name\">".$value->fullName('f l')." <span pi_id={$value->pi_id} class=\"label label-danger chatUserCount\"></span></div> 
					                <div class=\"contact_position\">".$status."</div>
					              </div>
					            </div>";
					}
					else{
						$status = "<span class='label label-default'>Offline</span>";
						$offline .= "<div onclick=\"get_contact_info(".$value->pi_id.")\" class=\"contact_item clearfix\">
					              <img class=\"img img-circle contact_img pull-left\" src=\"{$img}\">
					              <div class=\"pull-left contact_info\">
					                <div class=\"contact_name\">".$value->fullName('f l')." <span pi_id={$value->pi_id} class=\"label label-danger chatUserCount\"></span></div> 
					                <div class=\"contact_position\">".$status."</div>
					              </div>
					            </div>";
					}
				}
				echo $online.$offline;

			}
			else{
				echo "<div style='padding:10px;background:#f3f3f3'>Search not found.</div>";
			}
		}
		else{
			echo "";
		}
	}

	public function chatCounter(){

		$chat = new Chat;

		$chat->db->where("chat_to", $this->userInfo->pi_id);
		$chat->db->where("(status is null OR status = '')");
		$msg = $chat->get();
		
		$user= array();
		
		if(!empty($msg)){
			// echo json_encode(array("count"=>count($msg)));
			foreach ($msg as $key => $value) {
				$user[$value->chat_from] = 0;
			}
			foreach ($msg as $key => $value) {
				$user[$value->chat_from] += 1;
			}
			echo json_encode(array("count"=>count($msg),"user"=>$user));
		}
		else{
			echo json_encode(array("count"=>""));
		}
	}

	private function chatCounter1(){
		$chat = new Chat;

		$chat->db->where("chat_to", $this->userInfo->pi_id);
		$chat->db->where("(status is null OR status = '')");
		$msg = $chat->get();
		
		$user= array();
		
		if(!empty($msg)){
			// echo json_encode(array("count"=>count($msg)));
			foreach ($msg as $key => $value) {
				$user[$value->chat_from] = 0;
			}
			foreach ($msg as $key => $value) {
				$user[$value->chat_from] += 1;
			}
			return array("count"=>count($msg),"user"=>$user);
		}
		else{
			return array("count"=>"", "user"=>array());
		}
	}

}
?>