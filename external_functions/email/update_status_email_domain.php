<?php
	require_once('db.php');

	if(isset($_GET['update'])){
		$id = $_GET['update'];
		$sql = "UPDATE official_email_domain set status = ''";
		$result = $dBAccountUser->query($sql);

		if($result){
			update_status($id);
		}
	}
	function update_status($id){
		global $dBAccountUser;
		$sql = "UPDATE official_email_domain set status = 'active' where email_id = $id";
		$result = $dBAccountUser->query($sql);
		if($result){
			echo "updated";
		}
		else{
			echo "not updated";
		}
	}
?>