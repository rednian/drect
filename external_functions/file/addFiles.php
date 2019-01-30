<?php
session_start();
require_once('db.php');
	require_once('file.php');

$file = new file();

$form_name = strtolower($_POST['name']);
// $version = $_POST['version'];
// $classification = $_POST['type'];
$temp_file = $_FILES['file']['tmp_name'];
$file_type = $_FILES["file"]["type"];
$file_size = $_FILES['file']['size'];
$file_name = $_FILES['file']['name'];
// $div = $_POST['div'];
// $dep = $_POST['dep'];
$description = $_POST['description'];

$dir = $_SESSION['active_path'];

$typeArray = explode(".",$file_name);
$type = $typeArray[1];

$newUploadFile = $dir.$form_name.".".$type;

$image = array(
			"jpg"=>"fa fa-file-image-o",
			"docx"=>"fa fa-file-word-o",
			"doc"=>"fa fa-file-word-o",
			"ppt"=>"fa fa-file-powerpoint-o",
			"pptx"=>"fa fa-file-powerpoint-o",
			"xlsx"=>"fa fa-file-excel-o",
			"xls"=>"fa fa-file-excel-o",
			"pdf"=>"fa fa-file-pdf-o",
			"txt"=>"fa fa-file-text-o",
			"rar"=>"fa fa-file-archive-o",
			"zip"=>"fa fa-file-archive-o",
			"png"=>"fa fa-file-image-o",
			"gif"=>"fa fa-file-image-o"
			);
		
if (array_key_exists($type, $image)) {
   $icon = "<i class='".$image[$type]."'></i>";
}
else{
	$icon = "<i class='fa fa-file-o'></i>";
}

if(is_dir($dir)){
	if(!file_exists($newUploadFile)){
		$upload = move_uploaded_file($temp_file, $newUploadFile);
		if($upload){
			echo json_encode(array(
								  "result"=>"uploaded",
								  "display"=>"<div class=\"bg-success\" style=\"padding:5px;margin-bottom:5px\" id='".$form_name."'>
								                <div>".$icon."&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:11px'>".$form_name.".".$type."</span> <small style='font-size:9px'>Uploaded successfully</small>
								                </div>
								              </div>"
								  ));

			save_file();

		}
		elseif(!$upload){
			echo json_encode(array(
								  "result"=>"not uploaded",
								  "display"=>"<div class=\"bg-danger\" style=\"padding:5px;margin-bottom:5px\" id='".$form_name."'>
								                <div>".$icon."&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:11px'>".$form_name.".".$type."</span> <small style='font-size:9px'>Error to upload file</small></div>
								              </div>"
								  ));
		}
	}
	elseif(file_exists($newUploadFile)){
		// echo json_encode(array(
		// 						  "result"=>"existed",
		// 						  "display"=>"File already exist"
		// 						  ));
		archive_file();
	}
}
else{
	echo json_encode(array(
					"result"=>"invalid directory",
					"display"=>"Directory Not Exist"
					));
}


function save_file(){

	global $file;
	global $form_name;
	// global $version; 
	// global $classification;	
	// global $div;
	// global $dep;
	global $newUploadFile;
	global $type;
	global $file_size;
	global $dir;
	global $description;

	date_default_timezone_set("Asia/Manila");
	$date_uploaded = date("Y-m-d h:i:s a");
	
	$data = array(
	  			"form_name"=>$form_name,
	  			"description"=>$description,
	  			"version"=>get_file_info(),
	  			"path"=>$dir,
	  			"file_type"=>$type,
	  			"file_size"=>$file_size,
	  			"date_uploaded"=>$date_uploaded
	  			);

	$file->bind($data);
	$result = $file->insert_file();
	
}

function archive_file(){
	global $icon;
	global $dBAccountUser;
	global $form_name;
	global $version; 
	global $newUploadFile;
	global $type;
	global $file_size;
	global $dir;
	global $temp_file;

	$date_uploaded = date("Y-m-d h:i:s a");
	$info = get_file_info();

	$vrsn = $info['version'] + 1;
	$f_id = $info['f_id'];

	$sql = "UPDATE file
			SET version = '$vrsn',date_uploaded = '$date_uploaded', file_size = $file_size
			WHERE
				form_name = '$form_name'
			AND file_type = '$type'
			AND `path` = '$dir'";

	$result = $dBAccountUser->query($sql);
	if($result){

		$arcPath = "..".ds."..".ds."FILES".ds."archive".ds.$f_id."-".$form_name." - v".($vrsn-1).".".$type;
		$filename = $f_id."-".$form_name." - v".($vrsn-1).".".$type;

		copy($newUploadFile, $arcPath);
		unlink($newUploadFile);

		$upload = move_uploaded_file($temp_file, $newUploadFile);

		archive_db($info['name'],$info['version'],$filename,$info['desc'],$info['size'],$type);

		if($upload){

			echo json_encode(array(
								  "result"=>"archive",
								  "display"=>"<div class=\"bg-success\" style=\"padding:5px;margin-bottom:5px\" id='".$form_name."'>
								                <div>".$icon."&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:11px'>".$form_name.".".$type."</span> <small style='font-size:9px'>Uploaded successfully</small>
								                </div>
								              </div>"
								  ));

		}
		elseif(!$upload){

			echo json_encode(array(
								  "result"=>"not archive",
								  "display"=>"<div class=\"bg-danger\" style=\"padding:5px;margin-bottom:5px\" id='".$form_name."'>
								                <div>".$icon."&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:11px'>".$form_name.".".$type."</span> <small style='font-size:9px'>Error to upload file</small></div>
								              </div>"
								  ));
		}

	}
}

function get_file_info(){

	global $dBAccountUser;
	global $form_name;
	// global $version; 
	global $newUploadFile;
	global $type;
	global $file_size;
	global $dir;
	global $temp_file;

	$sql = "SELECT * from file WHERE
				form_name = '$form_name'
			AND file_type = '$type'
			AND `path` = '$dir'
			AND (on_delete is null or on_delete = '')";

	$result = $dBAccountUser->query($sql);

	if($dBAccountUser->num_rows($result) > 0){
		$row = $dBAccountUser->fetch_array($result);
		return array(
				"version"=>$row['version'],
				"f_id"=>$row['f_id'],
				"name"=>$row['form_name'],
				"desc"=>$row['description'],
				"size"=>$row['file_size'],
				);
	}
	else{
		return 1;
	}

}

function archive_db($name,$version,$path,$desc,$size,$type){
	global $dBAccountUser;
	$date = date('Y-m-d h:i:s a');
	$sql = "INSERT INTO file_archive (af_name, af_version, af_path, af_description, af_date_modified, af_size, af_type)
			VALUES('$name',$version,'$path','$desc','$date',$size,'$type')";
	$result = $dBAccountUser->query($sql);
}

?>