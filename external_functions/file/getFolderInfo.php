<?php
session_start();
require_once('db.php');
	require_once('file.php');

$file = new file();
$folder_id = $_GET['folder_id'];
// $folder_id = 35;

$search = array('folder_id'=>$folder_id);
$file->bind($search);

$result = $file->load_folder($search);

if($row = $dBAccountUser->fetch_array($result)){

	$folder_name = $row['folder_name'];
	$path = $row['folder_path'];
	$type = "File folder";
	$date_created = $row['folder_date'];

	$pathFinal = $path.$folder_name."/";

	$display= "<form id='formRenameFolder' method='post'>
            <div class='form-group'>
              Folder name:
              <input path='".$path."' oldname='".$folder_name."' name='txtfolderName' value='".$folder_name."' type='text' style='width:100%;font-size:12px;padding-left:5px;padding-right:5px'>
            </div>
            <div class='form-group' style='border-top:1px solid #CCC;padding-top:10px'>
              <div class='row'>
                <div class='col-lg-4'>Type:</div>
                <div class='col-lg-8'>".$type."</div>
              </div>
              <div class='row'>
                <div class='col-lg-4'>Location:</div>
                <div class='col-lg-8'>".str_replace("../../", "", $path)."</div>
              </div>
              <div class='row'>
                <div class='col-lg-4'>Size:</div>
                <div class='col-lg-8'>".get_folder_size($pathFinal)."</div>
              </div>
              <div class='row'>
                <div class='col-lg-4'>Contains:</div>
                <div class='col-lg-8'>".count_files($pathFinal)." files, ".count_folder($pathFinal)." folders"."</div>
              </div>
            </div>
            <div class='form-group' style='border-top:1px solid #CCC;padding-top:10px'>
              <div class='row'>
                <div class='col-lg-4'>Created:</div>
                <div class='col-lg-8'>".$date_created."</div>
              </div>
            </div>
            <div class='form-group'>
              <button type='submit' class='btn btn-success btn-sm flat'>OK</button>
            </div>
          </form>";

    $header = "<i style='color:#DED44A' class='fa fa-folder-open fa-1x'></i>&nbsp;&nbsp;&nbsp;&nbsp;".$folder_name." Properties";

    echo json_encode(array("header"=>$header,"body"=>$display));

}


	function get_folder_size($path){
		global $file;
		global $dBAccountUser;

		// $data = array("path"=>$path,"on_delete"=>'');
		// $file->bind($data);
		$size = 0;

		$sql = "SELECT * FROM file where `path` = '$path' and (on_delete is null or on_delete = '')";
		// $result = $file->search('file',$data);
		$result = $dBAccountUser->query($sql);
		while($row = $dBAccountUser->fetch_array($result)){
			$size += $row['file_size'];
		}
		$KBsize = $size / 1024;
		if($KBsize < 1024){
			return $DisplaySize = round($KBsize,2)." KB (".$size." bytes)";
		}
		elseif($KBsize >= 1024){
			return $DisplaySize = round(($KBsize/1024),2)." MB (".$size." bytes)";
		}
	}

	function count_files($path){
		global $file;
		global $dBAccountUser;

		// $data = array("path"=>$path);
		// $file->bind($data);

		// $result = $file->search('file',$data);
		$sql = "SELECT * FROM file where `path` = '$path' and (on_delete is null or on_delete = '')";
		// $result = $file->search('file',$data);
		$result = $dBAccountUser->query($sql);
		$count = $dBAccountUser->num_rows($result);
		return $count;
	}
	function count_folder($path){
		global $file;
		global $dBAccountUser;

		$data = array("folder_path"=>$path);
		$file->bind($data);

		$result = $file->search('file_folder',$data);
		$count = $dBAccountUser->num_rows($result);
		return $count;
	}
?>