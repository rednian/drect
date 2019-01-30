<script type="text/javascript">
  $(function(){
  show_settings();
});

function getimagefile()
{
    $(".view_image").fadeIn(100).attr('src',URL.createObjectURL(event.target.files[0]));
}

// $(document).ready(function(e){
//   $('#form_change_password').on('submit',function(e){
//     e.preventDefault();

//     $.ajax({
//       url: "<?php echo base_url('settings/change_password') ?>",
//       type: "POST",            
//       data: new FormData(this),
//       dataType: "json",
//       contentType: false,      
//       cache: false,            
//       processData:false,       
//       success: function(data)  
//       {
//        if(data['result'] == 1){ 
//           $("#password").val("");
//           $("#new_password").val("");
//           $("#confirm_password").val("");
//           new PNotify({
//                   title: 'Success',
//                   text: data['msg'],
//                   type: 'success',
//                   icon: 'fa fa-check'
//           });

//         }
//         else if(data['result'] == 0){

//             new PNotify({
//                   title: 'Error',
//                   text: data['msg'],
//                   type: 'error',
//                   icon: 'fa fa-times'
//             });
            
//         }
//       },
//       error:function(data){
        
//       }
//     });
//   });
// });

$(document).ready(function(){
  $("#form_change_profile_image").on("submit", function(e){
    e.preventDefault();
    $.ajax({
      url: "<?php echo base_url('settings/change_profile_picture') ?>",
      type: "POST",            
      data: new FormData(this),
      dataType: "json",
      contentType: false,      
      cache: false,            
      processData:false,       
      success: function(data)  
      {
       if(data['result'] == 1){ 
        
          new PNotify({
                  title: 'Success',
                  text: data['msg'],
                  type: 'success',
                  icon: 'fa fa-check'
          });

        }
        else if(data['result'] == 0){

            new PNotify({
                  title: 'Error',
                  text: data['msg'],
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

function show_settings(){
  $.ajax({
    url: "<?php echo base_url('settings/show_settings') ?>",
    type: "GET",
    dataType: "json",
    success: function(data){
      // // $("select#set_option option[value='"+data['option']+"']").attr("selected",true);
      $('input#set_time_duration').val(data['time']);
    
    },
    error: function(){

    }
  });
}

$('button#btn_show_password').mousedown(function(){
  $("input#password").attr("type","text");
});

$('button#btn_show_password').mouseup(function(){
  $("input#password").attr("type","password");
});

$(document).ready(function(){

      $('#form_change_idle').bootstrapValidator({
          message: 'This value is not valid', 
          feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',  
            invalid: 'glyphicon glyphicon-remove',
            validating: 'fa fa-refresh fa-spin',
          },
          fields: {
            set_time_duration:{
                validators: {
                    notEmpty: {
                      message: 'This field is required and can\'t be empty'
                    },
                    regexp: {
                      regexp: /^[0-9]+$/,
                      message: 'Invalid input. It should be a number.'
                    }
                },

            }
          }
        })  .on('success.form.bv', function(e) {
          
          e.preventDefault();

          var $form     = $(e.target);
         
          $.ajax({
            url: "<?php echo base_url('settings/change_idle') ?>",
            type: "POST",            
            data: new FormData(this),
            dataType: "json",
            contentType: false,      
            cache: false,            
            processData:false,       
            success: function(data)  
            {
             if(data['result'] == 1){ 

                $form
                    .bootstrapValidator('disableSubmitButtons', false)  // Enable the submit buttons
                    

                new PNotify({
                        title: 'Success',
                        text: data['msg'],
                        type: 'success',
                        icon: 'fa fa-check'
                });

              }
              else if(data['result'] == 0){

                  new PNotify({
                        title: 'Error',
                        text: data['msg'],
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

    $(document).ready(function(){

      $('#form_change_password').bootstrapValidator({
          message: 'This value is not valid', 
          // feedbackIcons: {
          //   valid: 'glyphicon glyphicon-ok',  
          //   invalid: 'glyphicon glyphicon-remove',
          //   validating: 'fa fa-refresh fa-spin',
          // },
          fields: {
            password:{
              message: 'The password is not valid',
              validators: {
                notEmpty: {
                  message: 'The password is required and can\'t be empty'
                }
              }
            },
            new_password:{
              message: 'The password is not valid',
              validators: {
                notEmpty: {
                  message: 'The password is required and can\'t be empty'
                },
                stringLength: {
                  min: 5,
                  max: 30,
                  message: 'The username must be more than 4 and less than 30 characters long'
                },
                different:{
                  field: 'password',
                  message: 'New password should not be the same to the old password'
                }
              }
            },
            confirm_password:{
                validators: {
                    notEmpty: {
                      message: 'This field is required and can\'t be empty'
                    },
                    identical:{
                      field: 'new_password',
                      message: 'Confirm password must be the same to the new password'
                    }
                },
            }
          }
        })  .on('success.form.bv', function(e) {
          
          e.preventDefault();

          var $form     = $(e.target);
         
          $.ajax({
            url: "<?php echo base_url('settings/change_password') ?>",
            type: "POST",            
            data: new FormData(this),
            dataType: "json",
            contentType: false,      
            cache: false,            
            processData:false,       
            success: function(data)  
            {
             if(data['result'] == 1){ 

                $("#password").val("");
                $("#new_password").val("");
                $("#confirm_password").val("");

                $form
                    .bootstrapValidator('disableSubmitButtons', false)  // Enable the submit buttons
                    .bootstrapValidator('resetForm', true);             // Reset the for
                new PNotify({
                        title: 'Success',
                        text: data['msg'],
                        type: 'success',
                        icon: 'fa fa-check'
                });

              }
              else if(data['result'] == 0){
                  $form
                    .bootstrapValidator('disableSubmitButtons', false)  // Enable the submit buttons
                    
                  new PNotify({
                        title: 'Error',
                        text: data['msg'],
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

</script>
