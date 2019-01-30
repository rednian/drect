<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Circulation_mail extends MY_Model{

		const DB_TABLE = 'circulation_mail';
    	const DB_TABLE_PK = 'mail_id';
    	
    	public $mail_id;
    	public $mail_no;
    	public $mail_from;
		public $mail_subject;
		public $mail_message;
		public $mail_date;
		public $on_delete;
		public $on_read;
		public $starred;

		public $config;

		public function __construct(){

			parent::__construct();
			$this->load->model('Circulation_attachments');
			$this->load->model("official_email_domain");

			$domain = new official_email_domain;
			$off_email = $domain->get_active_email();

			$this->config = array(
								"hostname" => '{imap.gmail.com:993/imap/ssl}INBOX',
								"username" => $off_email['email'],
								"password" => $off_email['password'],
								);

			if (strpos($off_email['email'], 'yahoo') !== false) {
			    $this->config['hostname'] = "{imap.mail.yahoo.com:993/imap/ssl}INBOX";
			}

		}

		public function fetch_emails(){


			set_time_limit(3000); 
 			
 			$config = $this->config;
			/* connect to gmail with your credentials */
			$hostname = $config['hostname'];
			$username = $config['username'];
			$password = $config['password'];
			 
			/* try to connect */
			$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

			if($inbox){
			    /* get all new emails. If set to 'ALL' instead 
			 * of 'NEW' retrieves all the emails, but can be 
			 * resource intensive, so the following variable, 
			 * $max_emails, puts the limit on the number of emails downloaded.
			 * 
			 */
			$emails = imap_search($inbox,'UNSEEN');
			 
			/* useful only if the above search is set to 'ALL' */
			$max_emails = 16;
			 
			 
			/* if any emails found, iterate through each email */
			if($emails) {
			 
			    $count = 1;
			 
			    /* put the newest emails on top */
			    rsort($emails);
			 
			    /* for every email... */
			    foreach($emails as $email_number){
			 
			        /* get information specific to this email */
			        $overview = imap_fetch_overview($inbox,$email_number,0);
			        
			        $subject = $overview[0]->subject;
			        $from = $overview[0]->from;
			        $date_received = $overview[0]->date;
			        /* get mail message, not actually used here. 
			           Refer to http://php.net/manual/en/function.imap-fetchbody.php
			           for details on the third parameter.
			         */
			       // $message = imap_qprint(imap_fetchbody($inbox,$email_number,1));
			       	$message = quoted_printable_decode(imap_fetchbody($inbox,$email_number,1)); 
			        // $message = imap_qprint(imap_body($inbox, $email_number));

			        if(!$this->is_email_exist($email_number)){

			            $email_data = array(
			                            "mail_no"=>$this->db->escape_str($email_number),
			                            "mail_from"=>$this->db->escape_str($from),
			                            "mail_subject"=>$this->db->escape_str($subject),
			                            "mail_message"=>$this->db->escape_str($message),
			                            "mail_date"=>$this->db->escape_str($date_received),
			                            "on_read"=>'no',
			                            "starred"=>'no'
			                           );

			            $this->save_email_db($email_data);
			        }
			        
			        /* get mail structure */
			        
			        $structure = imap_fetchstructure($inbox, $email_number);
			 
			        $attachments = array();
			 
			        /* if any attachments found... */
			        if(isset($structure->parts) && count($structure->parts)) 
			        {
			            for($i = 0; $i < count($structure->parts); $i++) 
			            {
			                $attachments[$i] = array(
			                    'is_attachment' => false,
			                    'filename' => '',
			                    'name' => '',
			                    'attachment' => ''
			                );
			 
			                if($structure->parts[$i]->ifdparameters) 
			                {
			                    foreach($structure->parts[$i]->dparameters as $object) 
			                    {
			                        if(strtolower($object->attribute) == 'filename') 
			                        {
			                            $attachments[$i]['is_attachment'] = true;
			                            $attachments[$i]['filename'] = $object->value;
			                        }
			                    }
			                }
			 
			                if($structure->parts[$i]->ifparameters) 
			                {
			                    foreach($structure->parts[$i]->parameters as $object) 
			                    {
			                        if(strtolower($object->attribute) == 'name') 
			                        {
			                            $attachments[$i]['is_attachment'] = true;
			                            $attachments[$i]['name'] = $object->value;
			                        }
			                    }
			                }
			 
			                if($attachments[$i]['is_attachment']) 
			                {
			                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
			 
			                    /* 3 = BASE64 encoding */
			                    if($structure->parts[$i]->encoding == 3) 
			                    { 
			                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
			                    }
			                    /* 4 = QUOTED-PRINTABLE encoding */
			                    elseif($structure->parts[$i]->encoding == 4) 
			                    { 
			                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
			                    }
			                }
			            }
			        }
			 
			        /* iterate through each attachment and save it */
			        foreach($attachments as $attachment)
			        {
			            if($attachment['is_attachment'] == 1)
			            {
			                $filename = $attachment['name'];
			                if(empty($filename)) $filename = $attachment['filename'];
			 
			                if(empty($filename)) $filename = time() . ".dat";
			 
			                /* prefix the email number to the filename in case two emails
			                 * have the attachment with the same file name.
			                 */
			                $fp = fopen("./attachments/emails/circulation/" . $email_number . "-" . $filename, "w+");
			                
			                if(fwrite($fp, $attachment['attachment'])){
			                	$attachments = new Circulation_attachments;
			                    $attachments->save_attachments_db($email_number,$email_number . "-" . $filename);
			                }
			                fclose($fp);
			            }
			 
			        }
			 
			        if($count++ >= $max_emails) break;
			    }
			 
			}


			}
			elseif(!$inbox){
			    echo "ERROR";
			}

			imap_close($inbox);

		}//END FETCH EMAILS

		public function is_email_exist($mail_no){

			$check = $this->search(array("mail_no"=>$mail_no));
			if(!empty($check)){
				return true;
			}
			else{
				return false;
			}
		}

		public function save_email_db($data = false){
			foreach ($data as $key => $value) {
				$this->$key = $value;
			}
			$this->save();
		}

		public function retrieve_mails(){
			$this->db->order_by("mail_id", "DESC");
			$this->db->where("(on_delete is null or on_delete = '')");
			return $this->get();
		}

		public function retrieve_trash(){
			$this->db->order_by("mail_id", "ASC");
			$this->db->where("(on_delete != '')");
			return $this->get();
		}
	}// END CLASS

?>