<?php

	defined('BASEPATH') OR exit('No direct script access allowed');

	 function upload_files($path, $title, $files)
     {
     	$CI =& get_instance();

         $config = array(
             'upload_path'   => $path,
             'allowed_types' => 'jpg|png|txt|doc|ppt|xls|pdf|docx|pptx|xlsx|jpeg',
             'overwrite'     => FALSE,       
         );

         $CI->load->library('upload', $config);

         $images = array();

         $count = 1;
         
         foreach ($files['name'] as $key => $image) {

             $_FILES['images[]']['name']= $files['name'][$key];
             $_FILES['images[]']['type']= $files['type'][$key];
             $_FILES['images[]']['tmp_name']= $files['tmp_name'][$key];
             $_FILES['images[]']['error']= $files['error'][$key];
             $_FILES['images[]']['size']= $files['size'][$key];

             $fileName = $title."-".$count;
             $images[] = $fileName;
             
             $files_attached[] = $fileName.".".pathinfo($files['name'][$key], PATHINFO_EXTENSION);

             $config['file_name'] = $fileName;

             $CI->upload->initialize($config);

             if ($CI->upload->do_upload('images[]')) {

                 $CI->upload->data();
                 $count++;

             } else {
                 return false;
             }
         }

         return $files_attached;
     }

     function retrieve_attachments($dir, $files){
       
        $file_display = "";
        $ext = array(
                    "pdf" => "<i class='fa fa-file-pdf-o text-pdf'></i>",
                    "docx" => "<i class='fa fa-file-word-o text-docx'></i>",
                    "doc" => "<i class='fa fa-file-word-o text-docx'></i>",
                    "pptx" => "<i class='fa fa-file-powerpoint-o text-ppt'></i>",
                    "ppt" => "<i class='fa fa-file-powerpoint-o text-ppt'></i>",
                    "xlsx" => "<i class='fa fa-file-excel-o text-excel'></i>",
                    "xls" => "<i class='fa fa-file-excel-o text-excel'></i>",
                    "txt" => "<i class='fa fa-file-file-o'></i>",
                    "zip" => "<i class='fa fa-file-zip-o'></i>",
                    "rar" => "<i class='fa fa-file-zip-o'></i>",
                    "jpg" => "<i class='fa fa-file-photo-o'></i>",
                    "png" => "<i class='fa fa-file-photo-o'></i>",
                    "gif" => "<i class='fa fa-file-photo-o'></i>",
                    );

        foreach ($files as $key => $value) {

            $file_ext = pathinfo($value, PATHINFO_EXTENSION);
            $file_size = filesize($dir.$value);

            $file_display .= "<div class=\"item pull-left\" style=\"width:300px;margin-right:5px\">
                            <div class=\"input-group\">
                              <span class=\"input-group-addon\">";
                                 if(array_key_exists($file_ext, $ext)){
                                    $file_display .= $ext[$file_ext];
                                 }
                                 else{
                                    $file_display .= "<i class='fa fa-file-o'></i>";
                                 }
                            $file_display .= "</span>
                              <input disabled value='".$value." - ".FileSizeConvert($file_size)."' type='text' class='form-control'>
                              <span class=\"input-group-btn\">
                                <a href='".base_url($dir."/".$value)."' data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Click to download\" class=\"btn btn-default\"><i class=\"fa fa-download\"></i></a>
                              </span>
                            </div>
                        </div>";
        }
        return $file_display;

     }

    function FileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
            $arBytes = array(
                0 => array(
                    "UNIT" => "TB",
                    "VALUE" => pow(1024, 4)
                ),
                1 => array(
                    "UNIT" => "GB",
                    "VALUE" => pow(1024, 3)
                ),
                2 => array(
                    "UNIT" => "MB",
                    "VALUE" => pow(1024, 2)
                ),
                3 => array(
                    "UNIT" => "KB",
                    "VALUE" => 1024
                ),
                4 => array(
                    "UNIT" => "B",
                    "VALUE" => 1
                ),
            );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(",", "." , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }

    function humanTiming ($time)
    {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }

    function remainTime($futureTime, $currentTime){

        $currentTime = new DateTime();
        $futureTime = new DateTime($futureTime);

        $interval = $futureTime->diff($currentTime);

        return $interval->format("%a d %h:%i");
    }

    function doEncrypt($pass){

    }
    
 ?>