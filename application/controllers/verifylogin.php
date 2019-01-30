<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('Personal_info','',TRUE);
   $this->load->model("Log_history");
   if($this->session->userdata('DRECT_logged')){
      redirect('dashboard' , 'refresh');
    }
 }

 function index()
 {
    if($this->check_database($this->input->post("password")) == FALSE){
      echo 'Invalid username or password.';
    }
    else{
      echo "<script>location.reload();</script>";
      redirect('dashboard', 'refresh');
    }
 }
 function check_database($password)
 {  
  $per_info = new Personal_info;
  $log = new Log_history;
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');

   //query the database
   $result = $per_info->login($username, $password);

   if(!empty($result))
   {
     session_start();
     $_SESSION['chat_last_id'] = 0;
     $sess_array = array();
     foreach($result as $row)
     {
       $sess_array = array(
         'id' => $row->pi_id,
         'name' => $row->fullName('f l')
       );

       $this->session->set_userdata('DRECT_logged', $sess_array);
       $log->login_history($row->pi_id);

       // update Online status
       $per_info->load($row->pi_id);
       $per_info->online = "yes";
       $per_info->save();
       
     }
     return TRUE;
   }
   else
   {
     return false;
   }
 }
}
?>