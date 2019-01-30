<?php
session_start();
	require_once('db.php');
	require_once('file.php');

	$f_id = $_POST['f_id'];
	$filename = $_POST['filename'];
	$description = $_POST['description'];

	$date = date("Y-m-d h:i:s a");

	$fileinfo = get_path($f_id);
	$oldpath = $fileinfo['filepath'].$fileinfo['filename'].".".$fileinfo['filetype'];

	$newpath = $fileinfo['filepath'].$filename.".".$fileinfo['filetype'];

	$sql = "UPDATE file set form_name = '$filename', description = '$description', date_uploaded = '$date' where f_id = $f_id";
	$result = $dBAccountUser->query($sql);

	if($result){
		$rename = rename($oldpath, $newpath);
		if($rename){
			echo json_encode(array("result"=>"updated","path"=>$fileinfo['filepath']));
		}
	}
	else{
		echo json_encode(array("result"=>"not updated"));
	}

	function get_path($f_id){
		global $dBAccountUser;
		$sql = "SELECT * FROM file where f_id = $f_id";
		$result = $dBAccountUser->query($sql);

		if($row = $dBAccountUser->fetch_array($result)){
			return array(
						"filename"=>$row['form_name'],
						"filetype"=>$row['file_type'],
						"filepath"=>$row['path']
						);
		}
	}
?>