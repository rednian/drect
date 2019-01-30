<?php
session_start();
require_once('db.php');
  require_once('file.php');

$file = new file();
$file_id = $_GET['file_id'];
// $file_id = 35;

$search = array('f_id'=>$file_id);
$file->bind($search);

$result = $file->load_files($search);

if($row = $dBAccountUser->fetch_array($result)){

	$form_name = $row['form_name'];
	$path = $row['path'];
	$type = $row['file_type'];
	$date_created = $row['date_uploaded'];
	$size = $row['file_size'];

	$KBsize = $size / 1024;
		if($KBsize < 1024){
		 $DisplaySize = round($KBsize,2)." KB (".$size." bytes)";
		}
		elseif($KBsize >= 1024){
		 $DisplaySize = round(($KBsize/1024),2)." MB (".$size." bytes)";
		}

	$image = array(
			"jpg"=>"fa fa-file-image-o fa-1x",
			"docx"=>"fa fa-file-word-o fa-1x docx",
			"doc"=>"fa fa-file-word-o fa-1x docx",
			"ppt"=>"fa fa-file-powerpoint-o fa-1x ppt",
			"pptx"=>"fa fa-file-powerpoint-o fa-1x ppt",
			"xlsx"=>"fa fa-file-excel-o fa-1x xlsx",
			"xls"=>"fa fa-file-excel-o fa-1x xlsx",
			"pdf"=>"fa fa-file-pdf-o fa-1x pdf",
			"txt"=>"fa fa-file-text-o fa-1x",
			"rar"=>"fa fa-file-archive-o fa-1x",
			"zip"=>"fa fa-file-archive-o fa-1x",
			"png"=>"fa fa-file-image-o fa-1x",
			"gif"=>"fa fa-file-image-o fa-1x"
			);

	$display= "<form>
            <div class='form-group'>
              Form name:
              <input value='".$form_name."' type='text' readonly style='width:100%;font-size:12px;padding-left:5px;padding-right:5px'>
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
                <div class='col-lg-8'>".$DisplaySize."</div>
              </div>
            </div>
            <div class='form-group' style='border-top:1px solid #CCC;padding-top:10px'>
              <div class='row'>
                <div class='col-lg-4'>Created:</div>
                <div class='col-lg-8'>".$date_created."</div>
              </div>
            </div>
            <div class='form-group'>
              <button type='button' class='btn btn-success btn-sm flat'>OK</button>
            </div>
          </form>";

    $header = "<i class='".$image[$type]."'></i>&nbsp;&nbsp;&nbsp;&nbsp;".$form_name." Properties";

    echo json_encode(array("header"=>$header,"body"=>$display));
    	
}

?>