<?php
	require_once('db.php');

	if(isset($_GET['del'])){
		$id = $_GET['del'];
		$sql = "DELETE FROM official_email_domain where email_id = $id";
		$result = $dBAccountUser->query($sql);

		if($result){
			echo "deleted";
		}
		else{
			echo "not deleted";
		}
	}
?>