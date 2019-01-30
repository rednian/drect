<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Securitys extends MY_Model{
	public function __construct(){
		parent::__construct();
		$this->load->model('Personal_info');
		$this->load->model('personal_info_accessibility');
		$this->load->model("Request_task");
	}
	public function verify_login($username='',$password=''){
		$per_info = new Personal_info;
		$search = $per_info->search(array('username' => $username,'password' => $password, "status"=>"active"));
		if(count($search) > 0){
			return reset($search);
		}
		return false;
	}
	public function login($user = false){
		$array = array(
			'DRECT_USER_ID' => $user->pi_id,
		);
		$this->session->set_userdata("login", $array);
	}
	public function whose_logged(){
		if ($this->session->userdata('login')) {
			$login_session = $this->session->userdata('login');
			$per_info = new Personal_info;
			$per_info->toJoin = array("Position"=>"Personal_info");
			$per_info->load($login_session["DRECT_USER_ID"]);
			return $per_info;
		}
		return false;
	}
	public function redirect_user(){
		if($this->whose_logged()  && $this->uri->segment(1) != 'admin'){
			redirect('/dashboard/','refresh');
		}else if(!$this->whose_logged() && $this->uri->segment(1) == 'admin'){
			redirect('/','refresh');
		}
	}
	public function logout(){
		$this->session->unset_userdata('login');
		$this->redirect_user();
	}

	public function check_access($segment=false){

		$per_access = new Personal_info_accessibility;

		$per_access->db->from("access_menu as access");
		$per_access->db->where("access.segment = '{$segment}'");
		$per_access->db->where("personal_info_accessibility.pi_id", $this->userInfo->pi_id);
		$per_access->db->where("personal_info_accessibility.am_id = access.am_id");

		$check = $per_access->get();

		return $check;
	}

	public function check_module($mod=false){

		$per_access = new Personal_info_accessibility;

		$per_access->db->from("access_menu as access");
		$per_access->db->from("module as modu");
		$per_access->db->where("personal_info_accessibility.pi_id", $this->userInfo->pi_id);
		$per_access->db->where("modu.mod_id", $mod);
		$per_access->db->where("personal_info_accessibility.am_id = access.am_id");
		$per_access->db->where("access.mod_id = modu.mod_id");

		$check = $per_access->get();
		return $check;
	}

	public function check_page($segment){
		
		$check = $this->check_access($segment);

		if(empty($check)){
			redirect('./error/error_404');
		}
	}
	public function setSidebar(){
		$rt = new Request_task;

		$countRTM = $rt->countRTM();

		$sidebar = "";
		if($this->userInfo->user_type == "admin"){
			$menu = array(
					array(
						"id"=>1,
						"module"=>"Mail",
						"link"=>"#",
						"segment"=>"mail",
						"icon"=>"fa fa-envelope-o",
						"sub_module"=>array(
											array(
												"name"=>"My Mail",
												"link"=>base_url("mail"),
												"segment"=>"mail"
												),
											array(
												"name"=>"Circulate Mail",
												"link"=>base_url("mail_circulate"),
												"segment"=>"mail_circulate"
												),
											array(
												"name"=>"Dispatch Mail",
												"link"=>base_url("mail_dispatch"),
												"segment"=>"mail_dispatch"
												),
										   )
						),
					array(
						"id"=>2,
						"module"=>"Approval",
						"link"=>"#",
						"segment"=>"notification",
						"icon"=>"fa fa-check",
						"sub_module"=>array(
											array(
												"name"=>"Post Calendar",
												"link"=>base_url("calendar_notification"),
												"segment"=>"calendar_notification"
												),
											array(
												"name"=>"Procedure & Policy",
												"link"=>base_url("pwi_notification"),
												"segment"=>"pwi_notification"
												),
											array(
												"name"=>"Account Approval",
												"link"=>base_url("account_approval_notification"),
												"segment"=>"account_approval_notification"
												),
										   )
						),

					array(
						"id"=>3,
						"module"=>"Feedback",
						"link"=>"#",
						"segment"=>"feedback",
						"icon"=>"fa fa-comment-o",
						"sub_module"=>array(
											array(
												"name"=>"Account Approval",
												"link"=>base_url("account_approval_feedback"),
												"segment"=>"account_approval_feedback"
												)
										   )
						),
					// array(
					// 	"id"=>4,
					// 	"module"=>"Request / Task",
					// 	"link"=>"#",
					// 	"segment"=>"rt",
					// 	"icon"=>"fa fa-edit",
					// 	"sub_module"=>array(
					// 						array(
					// 							"name"=>"My Request",
					// 							"link"=>base_url("request"),
					// 							"segment"=>"request"
					// 							),
					// 						array(
					// 							"name"=>"My Task",
					// 							"link"=>base_url("task"),
					// 							"segment"=>"task"
					// 							)
					// 					   )
					// 	),
					// array(
					// 	"id"=>5,
					// 	"module"=>"Head RTM Alert",
					// 	"link"=>"#",
					// 	"segment"=>"rt",
					// 	"icon"=>"fa fa-table",
					// 	"sub_module"=>array(
					// 						array(
					// 							"name"=>"Request &amp; Task Alert",
					// 							"link"=>base_url("request_task_approval"),
					// 							"segment"=>"request_task_approval"
					// 							)
					// 					   )
					// 	),
					array(
						"id"=>6,
						"module"=>"Calendar",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-calendar",
						"sub_module"=>array(
											array(
												"name"=>"My Calendar",
												"link"=>base_url("personal_calendar"),
												"segment"=>"personal_calendar"
												),
											// array(
											// 	"name"=>"Department",
											// 	"link"=>base_url("department_calendar"),
											// 	"segment"=>"department_calendar"
											// 	),
											array(
												"name"=>"Institutional",
												"link"=>base_url("institution_calendar"),
												"segment"=>"institution_calendar"
												)
										   )
						),
					array(
						"id"=>7,
						"module"=>"Archive Files",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-book",
						"sub_module"=>array(
											array(
												"name"=>"File Manager",
												"link"=>base_url("file_manager"),
												"segment"=>"file_manager"
												),
											array(
												"name"=>"Procedure",
												"link"=>base_url("pwi_repository"),
												"segment"=>"pwi_repository"
												),
											array(
												"name"=>"Policies",
												"link"=>base_url("policy_repository"),
												"segment"=>"policy_repository"
												),
											array(
												"name"=>"Deleted Files",
												"link"=>base_url("archive_files"),
												"segment"=>"archive_files"
												),
										   )
						),
					array(
						"id"=>11,
						"module"=>"Procedures & Policies",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-book",
						"sub_module"=>array(
											array(
												"name"=>"Policies",
												"link"=>base_url("policy"),
												"segment"=>"policy"
												),
											array(
												"name"=>"Procedure",
												"link"=>base_url("procedure"),
												"segment"=>"procedure"
												)
										   )
						),
					array(
						"id"=>8,
						"module"=>"Security",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-lock",
						"sub_module"=>array(
											array(
												"name"=>"User Account",
												"link"=>base_url("user_account"),
												"segment"=>"user_account"
												),
											// array(
											// 	"name"=>"Log History",
											// 	"link"=>base_url("loghistory"),
											// 	"segment"=>"loghistory"
											// 	),
											// array(
											// 	"name"=>"Procedure",
											// 	"link"=>base_url("procedure"),
											// 	"segment"=>"procedure"
											// 	),
											// array(
											// 	"name"=>"Work Instruction",
											// 	"link"=>base_url("work_instruction"),
											// 	"segment"=>"work_instruction"
											// 	),
											// array(
											// 	"name"=>"PWI Repository",
											// 	"link"=>base_url("pwi_repository"),
											// 	"segment"=>"pwi_repository"
											// 	),
											array(
												"name"=>"Official Email Domain",
												"link"=>base_url("add_email_domain"),
												"segment"=>"add_email_domain"
												)
										   )
						),
					array(
						"id"=>9,
						"module"=>"Settings",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-gear",
						"sub_module"=>array(
											array(
												"name"=>"User Settings",
												"link"=>base_url('settings'),
												"segment"=>"settings"
												),
											array(
												"name"=>"Organization Settings",
												"link"=>base_url('org_settings'),
												"segment"=>"org_settings"
												)
										   )
						),
					array(
						"id"=>10,
						"module"=>"Reports",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-file-o",
						"sub_module"=>array(
											array(
												"name"=>"Request and Task",
												"link"=>base_url('rt_statistics'),
												"segment"=>"rt_statistics"
												),
											array(
												"name"=>"Overdued Request and Task",
												"link"=>base_url('rt_status_overdue'),
												"segment"=>"rt_status_overdue"
												),
											array(
												"name"=>"Pending Request and Task",
												"link"=>base_url('rt_status_pending'),
												"segment"=>"rt_status_pending"
												),
											// array(
											// 	"name"=>"Denied Request and Task",
											// 	"link"=>base_url('rt_status_denied'),
											// 	"segment"=>"rt_status_denied"
											// 	),
											array(
												"name"=>"Log History",
												"link"=>base_url("loghistory"),
												"segment"=>"loghistory"
												)
										   )
						),
				);
			// HEAD AND EMPLOYEE MENU
			foreach($menu as $key => $value){
				// CHECK IF THE USER HAS THIS MODULE
				// $check_mod = $this->check_module($value['id']);
				// if(!empty($check_mod)){
					$count = 0;
					$mod_active = "";
					foreach ($value['sub_module'] as $k1 => $v1) {
						if($this->uri->segment(1) == $v1['segment']){
	              			$count++;
	              		}
					}
					if($count > 0){
						$mod_active = "active";
					}
					$sidebar .= "<li class='".$mod_active." treeview'>
					              <a href=\"#\">
					                <i class='".$value['icon']."'></i>
					                <span>".$value['module']."</span>
					                <i class=\"fa fa-angle-left pull-right\"></i>
					              </a>";
					              if(!empty($value['sub_module'])){
					              	$sidebar .= "<ul class=\"treeview-menu\">";
					              	foreach ($value['sub_module'] as $k => $v) {
					              		// $sub_check = $this->check_access($v['segment']);
					              		// if(!empty($sub_check)){
					              			$sub_active = "";
						              		if($this->uri->segment(1) == $v['segment']){
						              			$sub_active = "active";
						              		}
						              		$sidebar .= "<li class='".$sub_active."'><a href='".$v['link']."'><i class=\"fa fa-caret-right\"></i> ".$v['name']."</a></li>";
					              		// }
					              	}
					              	$sidebar .= "</ul>";
					              }
					$sidebar .= "</li>";
				// }
			}
			return $sidebar;
		}
		else{
			$menu = array(
					array(
						"id"=>1,
						"module"=>"Mail",
						"link"=>"#",
						"segment"=>"mail",
						"icon"=>"fa fa-envelope-o",
						"sub_module"=>array(
											array(
												"name"=>"My Mail",
												"link"=>base_url("mail"),
												"segment"=>"mail"
												),
											array(
												"name"=>"Circulate Mail",
												"link"=>base_url("mail_circulate"),
												"segment"=>"mail_circulate"
												),
											array(
												"name"=>"Dispatch Mail",
												"link"=>base_url("mail_dispatch"),
												"segment"=>"mail_dispatch"
												),
										   )
						),
					array(
						"id"=>2,
						"module"=>"Approval",
						"link"=>"#",
						"segment"=>"notification",
						"icon"=>"fa fa-check",
						"sub_module"=>array(
											array(
												"name"=>"Post Calendar",
												"link"=>base_url("calendar_notification"),
												"segment"=>"calendar_notification"
												),
											array(
												"name"=>"Procedure & Policy",
												"link"=>base_url("pwi_notification"),
												"segment"=>"pwi_notification"
												),
											array(
												"name"=>"Account Approval",
												"link"=>base_url("account_approval_notification"),
												"segment"=>"account_approval_notification"
												),
										   )
						),

					array(
						"id"=>3,
						"module"=>"Feedback",
						"link"=>"#",
						"segment"=>"feedback",
						"icon"=>"fa fa-comment-o",
						"sub_module"=>array(
											array(
												"name"=>"Account Approval",
												"link"=>base_url("account_approval_feedback"),
												"segment"=>"account_approval_feedback"
												)
										   )
						),
					array(
						"id"=>4,
						"module"=>"Request / Task",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-edit",
						"sub_module"=>array(
											array(
												"name"=>"My Request",
												"link"=>base_url("request"),
												"segment"=>"request"
												),
											array(
												"name"=>"My Task",
												"link"=>base_url("task"),
												"segment"=>"task"
												),
											// array(
											// 	"name"=>"RT Approval",
											// 	"link"=>base_url("rt_approval"),
											// 	"segment"=>"rt_approval"
											// 	)
										   )
						),
					// array(
					// 	"id"=>5,
					// 	"module"=>"Head RTM Alert",
					// 	"link"=>"#",
					// 	"segment"=>"rt",
					// 	"icon"=>"fa fa-table",
					// 	"sub_module"=>array(
					// 						array(
					// 							"name"=>"Request &amp; Task Alert",
					// 							"link"=>base_url("request_task_approval"),
					// 							"segment"=>"request_task_approval"
					// 							)
					// 					   )
					// 	),
					array(
						"id"=>6,
						"module"=>"Calendar",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-calendar",
						"sub_module"=>array(
											array(
												"name"=>"My Calendar",
												"link"=>base_url("personal_calendar"),
												"segment"=>"personal_calendar"
												),
											array(
												"name"=>"Department",
												"link"=>base_url("department_calendar"),
												"segment"=>"department_calendar"
												),
											array(
												"name"=>"Institutional",
												"link"=>base_url("institution_calendar"),
												"segment"=>"institution_calendar"
												),
											array(
												"name"=>"View Calendar",
												"link"=>base_url("calendar"),
												"segment"=>"calendar"
												)
										   )
						),
					array(
						"id"=>7,
						"module"=>"Archive Files",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-book",
						"sub_module"=>array(
											array(
												"name"=>"File Manager",
												"link"=>base_url("file_manager"),
												"segment"=>"file_manager"
												),
											array(
												"name"=>"Procedure",
												"link"=>base_url("pwi_repository"),
												"segment"=>"pwi_repository"
												),
											array(
												"name"=>"Policies",
												"link"=>base_url("policy_repository"),
												"segment"=>"policy_repository"
												),
											array(
												"name"=>"Deleted Files",
												"link"=>base_url("archive_files"),
												"segment"=>"archive_files"
												),
										   )
						),
					array(
						"id"=>11,
						"module"=>"Procedures & Policies",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-book",
						"sub_module"=>array(
											array(
												"name"=>"Policies",
												"link"=>base_url("policy"),
												"segment"=>"policy"
												),
											array(
												"name"=>"Procedure",
												"link"=>base_url("procedure"),
												"segment"=>"procedure"
												)
										   )
						),
					array(
						"id"=>8,
						"module"=>"Security",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-lock",
						"sub_module"=>array(
											array(
												"name"=>"User Account",
												"link"=>base_url("user_account"),
												"segment"=>"user_account"
												),
											// array(
											// 	"name"=>"Log History",
											// 	"link"=>base_url("loghistory"),
											// 	"segment"=>"loghistory"
											// 	),
											// array(
											// 	"name"=>"Procedure",
											// 	"link"=>base_url("procedure"),
											// 	"segment"=>"procedure"
											// 	),
											// array(
											// 	"name"=>"Work Instruction",
											// 	"link"=>base_url("work_instruction"),
											// 	"segment"=>"work_instruction"
											// 	),
											// array(
											// 	"name"=>"PWI Repository",
											// 	"link"=>base_url("pwi_repository"),
											// 	"segment"=>"pwi_repository"
											// 	),
											array(
												"name"=>"Official Email Domain",
												"link"=>base_url("add_email_domain"),
												"segment"=>"add_email_domain"
												)
										   )
						),
					array(
						"id"=>9,
						"module"=>"Settings",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-gear",
						"sub_module"=>array(
											array(
												"name"=>"User Settings",
												"link"=>base_url('settings'),
												"segment"=>"settings"
												),
											array(
												"name"=>"Organization Settings",
												"link"=>base_url('org_settings'),
												"segment"=>"org_settings"
												)
										   )
						),
					array(
						"id"=>10,
						"module"=>"Reports",
						"link"=>"#",
						"segment"=>"rt",
						"icon"=>"fa fa-file-o",
						"sub_module"=>array(
											array(
												"name"=>"Request and Task",
												"link"=>base_url('rt_statistics'),
												"segment"=>"rt_statistics"
												),
											array(
												"name"=>"Overdued Request and Task",
												"link"=>base_url('rt_status_overdue'),
												"segment"=>"rt_status_overdue"
												),
											array(
												"name"=>"Pending Request and Task",
												"link"=>base_url('rt_status_pending'),
												"segment"=>"rt_status_pending"
												),
											// array(
											// 	"name"=>"Denied Request and Task",
											// 	"link"=>base_url('rt_status_denied'),
											// 	"segment"=>"rt_status_denied"
											// 	),
											array(
												"name"=>"Log History",
												"link"=>base_url("loghistory"),
												"segment"=>"loghistory"
												)
										   )
						),
				);
			// HEAD AND EMPLOYEE MENU
			foreach($menu as $key => $value){
				// CHECK IF THE USER HAS THIS MODULE
				$check_mod = $this->check_module($value['id']);
				$counter = "";

				if(!empty($check_mod)){
					$count = 0;
					$mod_active = "";
					foreach ($value['sub_module'] as $k1 => $v1) {
						if($this->uri->segment(1) == $v1['segment']){
	              			$count++;
	              		}
					}
					if($count > 0){
						$mod_active = "active";
					}

					if($value['module'] == "Head RTM Alert"){
						if($countRTM > 0){
							$counter = "<span id='counterHeadRTM' class='label label-danger' style='margin-left:10px'>".$countRTM."</span>";
						}
					}

					$sidebar .= "<li class='".$mod_active." treeview'>
					              <a href=\"#\">
					                <i class='".$value['icon']."'></i>
					                <span>".$value['module']."</span>".$counter."<i class=\"fa fa-angle-left pull-right\"></i>
					              </a>";
					              if(!empty($value['sub_module'])){
					              	$sidebar .= "<ul class=\"treeview-menu\">";
					              	foreach ($value['sub_module'] as $k => $v) {
					              		$sub_check = $this->check_access($v['segment']);
					              		if(!empty($sub_check)){
					              			$sub_active = "";
						              		if($this->uri->segment(1) == $v['segment']){
						              			$sub_active = "active";
						              		}
						              		$sidebar .= "<li class='".$sub_active."'><a href='".$v['link']."'><i class=\"fa fa-caret-right\"></i> ".$v['name']."</a></li>";
					              		}
					              	}
					              	$sidebar .= "</ul>";
					              }
					$sidebar .= "</li>";
				}
			}
			return $sidebar;
		}
	}
}