<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Request_task_process_att extends MY_Model{

		const DB_TABLE = 'request_task_process_att';
    	const DB_TABLE_PK = 'rtpa_id';
    	
    	public $rtpa_id;
		public $rtpa_path;
		public $rtp_id;
		
	}

?>