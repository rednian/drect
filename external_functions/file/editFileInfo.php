<?php
	session_start();
	require_once('db.php');
	require_once('file.php');

	if(isset($_GET['edit'])){

		$file_id = $_GET['edit'];
		$sql = "SELECT * FROM file where f_id = $file_id";
		$result = $dBAccountUser->query($sql);

		if($row = $dBAccountUser->fetch_array($result)){

			echo json_encode(
							array(
								"f_id"=>$row['f_id'],
								"filename"=>$row['form_name'],
								"description"=>$row['description']
								)
							);			
		}
	}
?>