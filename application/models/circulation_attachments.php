<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Circulation_attachments extends MY_Model{

		const DB_TABLE = 'circulation_attachments';
    	const DB_TABLE_PK = 'att_id';
    	
    	public $att_id;
    	public $mail_no;
    	public $mail_path;

    	public function save_attachments_db($no, $name){
			
			$this->mail_no = $no;
			$this->mail_path = $name;
			$this->save();
		}
	}

?>