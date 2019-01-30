<?php
	require_once('db.php');

	if(isset($_GET['edit'])){
		$id = $_GET['edit'];
		
		$sql = "SELECT * FROM official_email_domain where email_id = $id";
		$result = $dBAccountUser->query($sql);

		if($row = $dBAccountUser->fetch_assoc($result)){
			echo json_encode($row);
		}
	}
?>