  <script type="text/javascript">
  $(function(){
    var url = window.location.href;
    url = url.split("=");

    load_folders_files("../../FILES/");
    if(url.length > 1){
      checkIfPathExist(url[1]);
    }
    
  });
      $(function(){

  // load_folders_files("../../FILES/");

  $("i#cancel_search").hide();
  $('#btncopy').attr("disabled",true);
  $('#btncut').attr("disabled",true);
  $('#btnpaste').attr("disabled",true);
  $('#btndelete').attr("disabled",true);
});

function isNumber(evt){
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

$(document).ready(function(e){
  $('#form-upload').on('submit',function(e){
      e.preventDefault();
    $.ajax({
          url: "<?php echo base_url('external_functions/file/addFiles.php') ?>",
          type: "POST",            
          data: new FormData(this),
          dataType: "json",
          contentType: false,      
          cache: false,            
          processData:false,       
          success: function(data)  
          {
            if(data['result'] == "uploaded"){
              $("div.box#added_files div.box-body").prepend(data['display']);
              load_after_create();
              clear_upload();
            }
            else if(data['result'] == "not uploaded"){
              $("div.box#added_files div.box-body").prepend(data['display']);
              clear_upload();
            }
            else if(data['result'] == "existed"){
              var msg = "<i class=\"fa fa-times\"></i> "+data['display'];
              new PNotify({
                          title: 'Error',
                          text: msg,
                          type: 'error',
                          icon: 'fa fa-times'
                  });
            }
            else if(data['result'] == "invalid directory"){
              var msg = "<i class=\"fa fa-times\"></i> "+data['display'];
              new PNotify({
                          title: 'Error',
                          text: msg,
                          type: 'error',
                          icon: 'fa fa-times'
                  });
            clear_upload()
            }
            else if(data['result'] == "archive"){
              $("div.box#added_files div.box-body").prepend(data['display']);
               new PNotify({
                          title: 'Success',
                          text: "<i class='fa fa-check'></i>&nbsp;&nbsp;&nbsp;File saved to archive successfully",
                          type: 'success',
                          icon: 'fa fa-check'
                  });
              load_after_create();
              clear_upload();
            }
            else if(data['result'] == "not archive"){
              $("div.box#added_files div.box-body").prepend(data['display']);
              new PNotify({
                          title: 'Error',
                          text: "<i class='fa fa-times'></i>&nbsp;&nbsp;&nbsp;Error to save file",
                          type: 'error',
                          icon: 'fa fa-times'
                  });
              clear_upload();
            }
          },
          error:function(data){
           
          }
       });
    
  });
});

function close_upload(){
  $('#uploadModal input.form-control').val("");
  $('#uploadModal textarea.form-control').val("");
  $("#uploadModal select.form-control option.hide").prop("selected", "selected");
  $('#uploadModal').modal('hide');
  $("div.box#added_files div.box-body").html("");
}

function clear_upload(){
  $('#uploadModal input.form-control').val("");
  $('#uploadModal textarea.form-control').val("");
  $("#uploadModal select.form-control option.hide").prop("selected", "selected");
}

function load_folders_files(path){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/load_folder_files.php') ?>",
    data: "load="+path,
    type: "GET",
    dataType: "html",
    success: function(data){
      $("table#table-files tbody").html(data);
      load_path();
      $("#txtsearch").val("");
    },
    error: function(){

    }
  });
}

function show_create_folder_panel(){
  $("#createFolderPanel").slideUp();
  $("#createFolderPanel").slideDown();
  var display = '<div class="box box-solid flat">\
              <div class="box-body" style="background:#F3F3F3;box-shadow: 1px 2px 7px 0px rgba(0,3,51,0.66);">\
                <div class="form-group">\
                  <a onclick="close_create_folder_panel()" href="#" class="pull-right">&times;</a>\
                  Create folder:\
                  <div class="input-group input-group-sm">\
                    <input type="text" id="txtfoldername" class="form-control" placeholder="Folder name" name="txtfoldername">\
                    <span class="input-group-btn">\
                      <button onclick="create_folder($(\'#txtfoldername\').val())" class="btn btn-success" type="button">OK</button>\
                    </span>\
                  </div>\
                </div>\
              </div>\
            </div>';
  $("#createFolderPanel").html(display);
  
}

function close_create_folder_panel(){
  $("#createFolderPanel").slideUp();
  // $("#createFolderPanel").html("");
}

function create_folder(folder_name){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/createfolder.php') ?>",
    data: "name="+folder_name,
    type: "GET",
    dataType: "json",
    success: function(data){
      
      if(data['result'] == 1){

        close_create_folder_panel();
        load_after_create();

        new PNotify({
                          title: 'Success',
                          text: "Folder Created Successfully",
                          type: 'success',
                          icon: 'fa fa-check'
                  });
      }
      else if(data['result'] == 0){
        new PNotify({
                          title: 'Error',
                          text: "Name Already Exist",
                          type: 'error',
                          icon: 'fa fa-times'
                  });
      }
    },
    error: function(){

    }
  });
}

function load_after_create(){
  $("i#cancel_search").hide();
  $.ajax({
    url: "<?php echo base_url('external_functions/file/load_after.php') ?>",
    type: "GET",
    dataType: "json",
    success: function(data){
      load_folders_files(data['path']);
      reset_selection();
    },
    error: function(){

    }
  });
}


function load_path(){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/load_after.php') ?>",
    type: "GET",
    dataType: "json",
    success: function(data){
      $("div.box-footer span#path").html(data['display']);
      reset_selection();
    },
    error: function(){

    }
  });
}

function search_result_in(){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/load_after.php') ?>",
    data: "result_in="+"",
    type: "GET",
    dataType: "json",
    success: function(data){
      $("div.box-footer span#path").html(data['display']);
    },
    error: function(){

    }
  });
}

function search(key){
  if(key.length == 0 || key.trim() == ""){
    load_after_create();
    $("i#cancel_search").hide();
  }
  else{
    $("i#cancel_search").show();
    $.ajax({
      url: "<?php echo base_url('external_functions/file/search.php') ?>",
      data: "search="+key.trim(" "),
      type: "GET",
      dataType: "html",
      success: function(data){
        $("table#table-files tbody").html(data);
        search_result_in();
      },
      error: function(){

      }
    });
  }
  
}

function info_folder(folder_id){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/getFolderInfo.php') ?>",
    data: "folder_id="+folder_id,
    type: "GET",
    dataType: "json",
    success: function(data){
      $('#infoModal h5#title').html(data['header']);
      $('#infoModal div.modal-body').html(data['body']);
      $('#infoModal div.modal-dialog').removeClass('modal-lg');
      $('#infoModal div.modal-dialog').addClass('modal-properties');
      $('#infoModal').modal('show');

      $('#formRenameFolder').on('submit',function(e){
          e.preventDefault();

          var newname = $("#formRenameFolder input[name=txtfolderName]").val();
          var oldname = $("#formRenameFolder input[name=txtfolderName]").attr('oldname');
          var path = $("#formRenameFolder input[name=txtfolderName]").attr('path');

          $.ajax({
            url: "<?php echo base_url('external_functions/file/renameFolder.php') ?>",
            data: {newname : newname, oldname : oldname, path : path, folder_id : folder_id},
            type: "POST",
            dataType: "json",
            success: function(data){
              load_folders_files(data[0]);
              $('#infoModal').modal('hide');
            },
            error: function(){

            }
          });
        
      });
    },
    error: function(){

    }
  });
  // $('#infoModal').modal('show');
}

function info_files(file_id){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/getFileInfo.php') ?>",
    data: "file_id="+file_id,
    type: "GET",
    dataType: "json",
    success: function(data){
      $('#infoModal h5#title').html(data['header']);
      $('#infoModal div.modal-body').html(data['body']);
      $('#infoModal div.modal-dialog').removeClass('modal-lg');
      $('#infoModal div.modal-dialog').addClass('modal-properties');
      $('#infoModal').modal('show');
    },
    error: function(){

    }
  });
}

function view_file(file_id){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/viewFile.php') ?>",
    data: "file_id="+file_id,
    type: "GET",
    dataType: "json",
    success: function(data){
      $('#infoModal h5#title').html(data['header']);
      $('#infoModal div.modal-body').html(data['body']);
      $('#infoModal div.modal-dialog').removeClass('modal-properties');
      $('#infoModal div.modal-dialog').addClass('modal-lg');
      $('#infoModal').modal('show');
    },
    error: function(){

    }
  });
}

function select(id){
  $("tr").css("background-color", "#FFF");
  document.getElementById(id).checked = true;
  $("tr."+id).css("background-color", "rgba(106,198,246,0.3)");
}

var selected_file = "";

function select_id(element,id){
  if(element == "folder"){
    $('#btncopy').attr("disabled",true);
    $('#btncut').attr("disabled",true);
    $('#btndelete').attr("disabled",true);
  }
  else if(element == "file"){
    $('#btncopy').attr("disabled",false);
    $('#btncut').attr("disabled",false);
    $('#btndelete').attr("disabled",false);
  }
  $.ajax({
    url: "<?php echo base_url('external_functions/file/select.php') ?>",
    data: "element="+element+"&id="+id,
    type: "GET",
    dataType: "html",
    success: function(data){
      
    },
    error: function(){

    }
  });

  selected_file = id;
}

function confirm_delete(){
  delete_file();
}

function delete_file(){
    bootbox.dialog(
        {
                  title: "Are you sure you want to delete file?",
                  message: '<div class="row">  ' +
                      '<div class="col-md-12"> ' +
                      'Enter remarks here' +
                      '<textarea rows=\'5\' class=\'form-control\' id=\'remarks\'></textarea>' +
                      '</div>  </div>',
                  buttons: {
                      success: {
                          label: "Submit",
                          className: "btn-success",
                          callback: function () {
                              var remarks = $('#remarks').val();
                              if(remarks == ""){
                                new PNotify({
                                    title: 'Information',
                                    text: "Please give us remarks why decline.",
                                    type: 'error',
                                    icon: 'fa fa-exclamation'
                                });
                              }
                              else{
                                $.ajax({
                                  url: "<?php echo base_url('external_functions/file/delete_file.php') ?>",
                                  data: {del : selected_file, remarks : remarks},
                                  type: "GET",
                                  dataType: "json",
                                  success: function(data){
                                    if(data['result'] == "deleted"){
                                      $("#confirmDeleteAlert").modal('hide');
                                      load_folders_files(data['path']);
                                          new PNotify({
                                                        title: 'Success',
                                                        text: "File deleted Successfully",
                                                        type: 'success',
                                                        icon: 'fa fa-check'
                                                });
                                    }
                                    else if(data['result'] == "not deleted"){
                                     new PNotify({
                                                        title: 'Error',
                                                        text: "Unable to delete",
                                                        type: 'error',
                                                        icon: 'fa fa-times'
                                                });
                                    }
                                  },
                                  error: function(){

                                  }
                                });
                              }
                          }
                      }
                  }
              }
          );
  
}

function reset_selection(){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/select.php') ?>",
    data: "reset="+"true",
    type: "GET",
    dataType: "html",
    success: function(data){
      $('#btncopy').attr("disabled",true);
      $('#btncut').attr("disabled",true);
      $('#btndelete').attr("disabled",true);
    },
    error: function(){

    }
  });
}

function reset_select_function(){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/select_function.php') ?>",
    data: "reset="+"true",
    type: "GET",
    dataType: "html",
    success: function(data){
      $('#btnpaste').attr("disabled",true); 
    },
    error: function(){

    }
  });
}

function select_function(action){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/select_function.php') ?>",
    data: "action="+action,
    type: "GET",
    dataType: "html",
    success: function(data){
      $('#btnpaste').attr("disabled",false);
    },
    error: function(){

    }
  });
}

function action(){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/action.php') ?>",
    data: "action=paste",
    type: "GET",
    dataType: "json",
    success: function(data){
      reset_select_function();
      load_after_create();
    },
    error: function(){

    }
  });
}

function delete_confirm(id){
  
}

function edit_files(id){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/editFileInfo.php') ?>",
    data: {edit : id},
    type: "GET",
    dataType: "json",
    success: function(data){
      $("#editModal .modal-body input#txtfilename").val(data['filename']);
      $("#editModal .modal-body input#txtf_id").val(data['f_id']);
      $("#editModal .modal-body textarea#txtdescription").val(data['description']);
      $("#editModal").modal('show');
    },
    error: function(){

    }
  });
  
}

$(document).ready(function(e){
  $('#form_save_file_changes').on('submit',function(e){
      e.preventDefault();
    $.ajax({
          url: "<?php echo base_url('external_functions/file/save_file_changes.php') ?>",
          type: "POST",            
          data: new FormData(this),
          dataType: "json",
          contentType: false,      
          cache: false,            
          processData:false,       
          success: function(data)  
          {
            if(data['result'] = "updated"){
              $("#editModal").modal('hide');
              load_folders_files(data['path']);
                
                 new PNotify({
                          title: 'Success',
                          text: "File updated Successfully",
                          type: 'success',
                          icon: 'fa fa-check'
                  });
            }
            else if(data['result'] = "not updated"){
               new PNotify({
                          title: 'Error',
                          text: "Unable to update",
                          type: 'error',
                          icon: 'fa fa-times'
                  });
            }
            
          },
          error:function(data){
          
          }
       });
    
  });
});

function checkIfPathExist(url){
  $.ajax({
    url: "<?php echo base_url('external_functions/file/checkIfPathExist.php') ?>",
    type: "GET",
    data: {url : url},
    dataType: "json",
    success: function(data){
      if(data.result == true){
        load_folders_files(url);
      }
      else{
        load_folders_files("../../FILES/");
      }
    },
    error: function(){

    }
  });
}


    </script>