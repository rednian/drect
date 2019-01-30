<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Phpmailermodel extends MY_Model{

		public $subject;
		public $message;
		public $to = array();
		public $attachments;
		public $config = array();

		public function __construct(){

			 parent::__construct();
			 
			 $this->load->library('My_PHPMailer');
			 $this->load->model("official_email_domain");

			 $domain = new official_email_domain;
			 $this->config = $domain->get_active_email();

		}
		
		public function send_email(){

			$mail = new PHPMailer();

			$config = $this->config;

			$mail->IsSMTP();

			$mail->Host = "smtp.gmail.com";

			if (strpos($config['email'], 'yahoo') !== false) {
			    $mail->Host = "smtp.mail.yahoo.com";
			}
			
			//$mail->SMTPDebug  = 2;                                              
			$mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);
			$mail->SMTPAuth   = true;                 
			$mail->SMTPSecure = "tls";                 
			$mail->Port       = 587;                 

			$mail->Username   = $config['email']; 
			$mail->Password   = $config['password'];       
			$mail->SetFrom($config['email'], $config['name']);
			$mail->AddReplyTo($config['email'], $config['name']);
		
			$mail->Subject    = $this->subject;
			$mail->MsgHTML($this->message);

			foreach ($this->to as $key => $value) {
				$mail->AddAddress($value, "");
			}

			if(count($this->attachments) > 0){
				foreach($this->attachments as $key => $value){
					$mail->AddAttachment($value);
				}
			}
			
			if($mail->send()){
				return true;
			}
			elseif(!$mail->send()){
				return false;
			}

		}

	}

?>