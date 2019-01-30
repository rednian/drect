<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	/**
	* 
	*/
	class Notify_done_steps extends MY_Model{

		const DB_TABLE = 'notify_done_steps';
    	const DB_TABLE_PK = 'id';
    	
    	public $id;
		public $rtp_id;
		public $on_notify;

	}

?>