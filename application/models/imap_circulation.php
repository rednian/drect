<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imap_circulation extends My_Model {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public $config;

	public function __construct(){

		parent::__construct();
		$this->load->model("official_email_domain");

		$domain = new official_email_domain;
		$off_email = $domain->get_active_email();

		$this->config = array(
							"hostname" => 'imap.gmail.com:993',
							"username" => $off_email['email'],
							"password" => $off_email['password'],
							);

		if (strpos($off_email['email'], 'yahoo') !== false) {
		    $this->config['hostname'] = "imap.mail.yahoo.com:993";
		}

	}

	public function getMails()
	{
		$config = $this->config;

		$this->load->library('My_imap');
		$this->load->model('Circulation_mail');

		$circulation = new Circulation_mail;

		$mailbox = $config['hostname'];
		$username = $config['username'];
		$password = $config['password'];
		$encryption = 'ssl'; // or ssl or ''

		// open connection
		$imap = new Imap($mailbox, $username, $password, $encryption);

		// stop on error
		if($imap->isConnected()===false){
		    return $imap->getError();
		}

		// select folder Inbox
		$imap->selectFolder('INBOX');

		// count messages in current folder
		$overallMessages = $imap->countMessages();
		$unreadMessages = $imap->countUnreadMessages();

		// fetch all messages in the current folder
		$emails = $imap->getUnreadMessages();

		foreach ($emails as $key => $value) {
			$uid = $value['uid'];

			if(!$circulation->is_email_exist($uid)){

	            $email_data = array(
	                            "mail_no"=>$this->db->escape_str($uid),
	                            "mail_from"=>$this->db->escape_str($value['from']),
	                            "mail_subject"=>$this->db->escape_str($value['subject']),
	                            "mail_message"=>$value['body'],
	                            "mail_date"=>$this->db->escape_str($value['date']),
	                            "on_read"=>'no',
	                            "starred"=>'no'
	                           );

	            $circulation->save_email_db($email_data);

	            if(array_key_exists("attachments", $value)){

	            	foreach ($value['attachments'] as $key1 => $value1) {
						$attachments = $imap->getAttachment($uid, $key1);
						$ifp = fopen( "./attachments/emails/circulation/". $uid . "-" . $value1['name'], "wb" ); 
		  				fwrite( $ifp, $attachments['content'] );

		  				$this->load->model("Circulation_attachments");
		  				$attachments = new Circulation_attachments;
			            $attachments->save_attachments_db($uid, $uid . "-" . $value1['name']);
					}
	            }
	            
	        }
		}
	}
}
