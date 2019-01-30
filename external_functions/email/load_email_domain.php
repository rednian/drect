<?php
	require_once('db.php');
	
	$data = array();

	$sql = "SELECT * FROM official_email_domain";
	$result = $dBAccountUser->query($sql);
	while($row = $dBAccountUser->fetch_assoc($result)){
		$data[] = $row;
	}
	
	echo json_encode($data);
	
?>