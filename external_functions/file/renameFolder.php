<?php
	session_start();
	
	require_once('db.php');
	require_once('file.php');

	$file = new file();
	$data = $_POST;
	
	// print_r($data);
	$result = $dBAccountUser->query("UPDATE file_folder SET folder_name = '{$data['newname']}',folder_date = '".date('Y-m-d h:i:s a')."' WHERE folder_id = '{$data['folder_id']}'");

	if($result){
		// UPDATE PATH FILES CONTAINING FOLDER
		$newpath = $data['path'].$data['newname']."/";
		$oldpath = $data['path'].$data['oldname']."/";

		rename($oldpath,$newpath);

		$resultUpdateFile = $dBAccountUser->query("UPDATE file SET `path` = '{$newpath}' WHERE `path` = '{$oldpath}'");
		$resultUpdateFile1 = $dBAccountUser->query("UPDATE file_folder SET `folder_path` = '{$newpath}' WHERE `folder_path` = '{$oldpath}'");

		echo json_encode(array($data['path']));
	}
?>