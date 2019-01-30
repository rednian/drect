<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Circulation_mail_sent extends MY_Model{

		const DB_TABLE = 'circulation_mail_sent';
    	const DB_TABLE_PK = 'sent_id';

    	const FIRST_NAME = 'firstname';
    	const LAST_NAME = 'lastname';
    	const MIDDLE_NAME = 'middlename';
    	
    	public $sent_id;
    	public $mail_id;
    	public $pi_id;
    	public $sent_date;
    	public $on_delete;

    	public function retrieve_sent(){
			$this->toJoin = array("Personal_info"=>"Circulation_mail_sent", "Circulation_mail"=>"Circulation_mail_sent");
			$this->get();
		}
		
	}

?>