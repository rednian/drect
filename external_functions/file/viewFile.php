<?php
session_start();
require_once('db.php');
require_once('file.php');

$file = new file();
$file_id = $_GET['file_id'];

$search = array('f_id'=>$file_id);
$file->bind($search);

$result = $file->load_files($search);

if($row = $dBAccountUser->fetch_array($result)){

	$form_name = $row['form_name'];
	$path = $row['path'];
	$type = $row['file_type'];
	$size = $row['file_size'];

	$KBsize = $size / 1024;
		if($KBsize < 1024){
		 $DisplaySize = round($KBsize,2)." KB (".$size." bytes)";
		}
		elseif($KBsize >= 1024){
		 $DisplaySize = round(($KBsize/1024),2)." MB (".$size." bytes)";
		}

  $file_path = base_url()."../".str_replace("../../", "", $path.$form_name.".".$type);

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

	   $display= "<iframe src='".$file_path."' width=\"100%\" style=\"height:500px\"></iframe>";

    $header = "<i class='".$image[$type]."'></i>&nbsp;&nbsp;&nbsp;&nbsp;".$form_name;

    echo json_encode(array("header"=>$header,"body"=>$display));
    	
}

?>