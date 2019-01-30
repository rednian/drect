<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <a href="<?php echo base_url('user_account'); ?>" class="btn btn-default flat"><i class="fa fa-table"></i> Back to List</a>
            </div>

            <div class="row">
              
                <form id="form-account-registration" method="POST" action="<?php echo base_url('user_account/update')?>">

                     <div class="col-lg-12" style="padding-bottom:10px">
                        <button class="btn btn-success flat" id="btn-submit" type="submit"><i class="fa fa-save"></i> SAVE CHANGES</button>
                    </div>

                    <div class="col-lg-8">

                      <div class="box box-solid">

                          <div class="box-header with-border">
                              <b><i class="fa fa-user"></i> Personal Information</b>
                          </div>
                          <div class="box-body">

                              <div class="row">
                                  <div class="col-lg-8">
                                      <div class="form-group hide">
                                          <input value="<?php echo $user_information['per_info']['pi_id']?>" required placeholder="First Name" type="text" class="form-control"
                                                 name="pi_id" id="pi_id">
                                      </div>
                                      <div class="form-group">
                                          <input value="<?php echo $user_information['per_info']['firstname']?>" required placeholder="First Name" type="text" class="form-control"
                                                 name="firstname" id="firstname">
                                      </div>
                                      <div class="form-group">
                                          <input value="<?php echo $user_information['per_info']['lastname']?>" required placeholder="Last Name" type="text" class="form-control"
                                                 name="lastname" id="lastname">
                                      </div>
                                      <div class="form-group">
                                          <input value="<?php echo $user_information['per_info']['middlename']?>" placeholder="Middle Name" type="text" class="form-control"
                                                 name="middlename" id="middlename">
                                      </div>
                                      <div class="form-group">
                                          Birthday
                                          <div class="form-group">
                                              <div class="input-group">
                                                  <div class="input-group-addon">
                                                      <i class="fa fa-calendar"></i>
                                                  </div>
                                                  <input value="<?php echo $user_information['per_info']['birthdate']?>" required name="birthdate" id="birthdate" type="text"
                                                         class="form-control" data-inputmask="'alias': 'yyyy-mm-dd'"
                                                         data-mask>
                                              </div><!-- /.input group -->
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          Email Address
                                          <input required placeholder="Email" type="email" class="form-control"
                                                 name="user_email" id="user_email">
                                      </div>
                                      <div class="form-group">
                                          Contact No
                                          <div class="form-group">
                                              <div class="input-group">
                                                  <div class="input-group-addon">
                                                      <i class="fa fa-mobile-phone"></i>
                                                  </div>
                                                  <input value="<?php echo $user_information['per_info']['contact_no']?>" name="contact_no" type="text" class="form-control" data-inputmask='"mask": "+639999999999"' data-mask>
                                              </div><!-- /.input group -->
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          Position
                                          <div class="form-group">
                                              <select name="pos_id" id="pos_id" class="form-control">
                                                  <option selected="selected" disabled="disabled">SELECT POSITION</option>
                                                  <?php 
                                                    foreach($positions as $pos){
                                                      echo "<option value='{$pos->pos_id}'>{$pos->position}</option>";
                                                    }
                                                  ?>
                                              </select>
                                          </div>
                                      </div>
                                  </div>
                              </div>


                          </div>
                      </div>

                      <div class="box box-solid flat">
                          <div class="box-header with-border">
                              <b><i class="fa fa-lock"></i> Account Information</b>
                          </div>
                          <div class="box-body">
                              <div class="row">
                                  <div class="col-lg-8">
                                      <div class="form-group">

                                          <div class="form-group">
                                              <div class="input-group">
                                                  <div class="input-group-addon">
                                                      <i class="fa fa-user"></i>
                                                  </div>
                                                  <input value="<?php echo $user_information['per_info']['username']?>" placeholder="Username" required name="username" id="username"
                                                         type="text" class="form-control">
                                              </div><!-- /.input group -->
                                          </div>
                                      </div>
                                      <div class="form-group">

                                          <div class="form-group">
                                              <div class="input-group">
                                                  <div class="input-group-addon">
                                                      <i class="fa fa-lock"></i>
                                                  </div>
                                                  <input value="<?php echo $user_information['per_info']['password']?>" placeholder="Password" required id="password" name="password"
                                                         type="password" class="form-control">
                                              </div><!-- /.input group -->
                                          </div>
                                      </div>

                                      <div class="form-group">
                                          Account life span
                                          <div class="form-group">
                                              <div class="input-group">
                                                  <div class="input-group-addon">
                                                      <i class="fa fa-clock-o"></i>
                                                  </div>
                                                  <select id="txtlifespan" name="life_span_type"
                                                          onchange="editLifeSpan($(this).val());" class="form-control">
                                                      <option value="1">Lifetime</option>
                                                      <option value="2">Year</option>
                                                      <option value="3">Month</option>
                                                      <option value="4">Week</option>
                                                      <option value="5">Day</option>
                                                  </select>
                                              </div><!-- /.input group -->
                                          </div>
                                      </div>

                                      <div class="row hide" id="date_expires">
                                          <div class="col-lg-4">
                                              <div class="form-group">
                                                  <input onkeyup="get_expire_date($('#txtlifespan').val(),$(this).val())"
                                                         placeholder="Quantity" id="quantity" type="text"
                                                         class="form-control" id="quantity" name="quantity">
                                              </div>
                                          </div>
                                          <div class="col-lg-8">
                                              <div class="form-group">
                                                  <input id="date" placeholder="Date Expires"
                                                         style="border:none;background:#fff" readonly type="text"
                                                         class="form-control" name="life_span" id="date">
                                              </div>
                                          </div>
                                      </div>

                                  </div>
                              </div>
                          </div>
                      </div>

                    </div>

                    <div class="col-lg-4">
                    
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <b><i class="fa fa-lock"></i> Accessibility Options</b>
                            </div>
                            <div class="box-body" style="max-height:500px;overflow:auto">
                                <div class="form-group">
                            
                                          <div class="col-lg-12">
                                            <div class=\"checkbox\">
                                                <label>
                                                    <input id='option_select_all' style='width:18px;height:18px' type="checkbox" name="option_select_all" />&nbsp;&nbsp;&nbsp;&nbsp;Select All
                                                </label>
                                            </div>
                                            <?php echo $access_menu ?>
                                          </div>
                                      </div>
                            </div>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>
</section><!-- /.content -->

<script type="text/javascript">
$(document).ready(function () {
    $("#pos_id").select2();
    $("#birthdate").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    $("[data-mask]").inputmask();
});

$(document).ready(function(){

      $('#form-account-registration').bootstrapValidator({
          message: 'This value is not valid', 
          feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',  
            invalid: 'glyphicon glyphicon-remove',
            validating: 'fa fa-refresh fa-spin',
          },
          fields: {
            firstname: { 
              message: 'The username is not valid',
              validators: {
                notEmpty: {
                  message: 'The first name is required and can\'t be empty'
                },
                regexp: {
                  regexp: /^[a-zA-Z ]+$/,
                  message: 'The first name can only consist of alphabetical'
                }
              }
            },
            lastname: {
              validators: {
                notEmpty: {
                  message: 'The last name is required and can\'t be empty'
                },
                regexp: {
                  regexp: /^[a-zA-Z ]+$/,
                  message: 'The last name can only consist of alphabetical'
                }
              }
            },
            middlename: {
              validators: {
                regexp: {
                  regexp: /^[a-zA-Z ]+$/,
                  message: 'The middle name can only consist of alphabetical'
                }
              }
            },
            birthdate: {
              validators: {
                date:{
                    format: 'YYYY-MM-DD',
                    message: 'Date is invalid'
                },
                notEmpty: {
                  message: 'The birthday is required and can\'t be empty'
                }
              }
            },
            contact_no:{
              validators: {
                notEmpty: {
                  message: 'The contact_no is required and can\'t be empty'
                },
                stringLength: {
                  min: 13,
                  max: 13,
                  message: 'Invalid contact no'
                },
              }
            },
            user_email: {
                notEmpty: {
                  message: 'The email is required and can\'t be empty'
                },
                validators: {
                    emailAddress: {
                        message: 'The value is not a valid email address'
                    }
                }
            },
            username: {
              message: 'The username is not valid',
              validators: {
                notEmpty: {
                  message: 'The username is required and can\'t be empty'
                },
                stringLength: {
                  min: 6,
                  max: 30,
                  message: 'The username must be more than 6 and less than 30 characters long'
                },
                regexp: {
                  regexp: /^[a-zA-Z0-9_\.]+$/,
                  message: 'The username can only consist of alphabetical, number, dot and underscore'
                },
                // remote: {
                //   url: '<?php echo base_url("user_account/check_username") ?>',
                //   message: 'Username already in used',
                //   delay: 1000,
                //   type: 'POST',
                //   data: {username : $(this).val()}
                // }
              }
            },
            password: { 
              message: 'The password is not valid',
              validators: {
                notEmpty: {
                  message: 'The password is required and can\'t be empty'
                },
                identical: {
                  field: 'confirmPassword',
                  message: 'The password and its confirm are not the same'
                }
              }
            },
            pos_id:{
                message: 'The account life span is not valid',
                  validators: {
                    notEmpty: {
                      message: 'Please select position'
                    }
                  }
            },
            life_span_type:{
                message: 'The account life span is not valid',
                  validators: {
                    notEmpty: {
                      message: 'The account life span is required and can\'t be empty'
                    }
                  }
            },
            quantity:{
                validators: {
                    notEmpty: {
                      message: 'This field is required and can\'t be empty'
                    },
                    regexp: {
                      regexp: /^[0-9]+$/,
                      message: 'Invalid input'
                    }
                },

            },
            'option[]': {
                    validators: {
                        choice: {
                            min: 1,
                            message: 'Please choose accessibility'
                        }
                    }
                },
            
          }
        })  .on('success.form.bv', function(e) {
        e.preventDefault();

          var $form     = $(e.target);
         
          $.ajax({
              url: "<?php echo base_url('user_account/update') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                if(data['result'] == 1){ 
                  // reset_form();
                  $form
                    .bootstrapValidator('disableSubmitButtons', false)  // Enable the submit buttons
                  //   .bootstrapValidator('resetForm', true);             // Reset the for

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

function reset_form(){
    $('span#select2-pos_id-container').html('SELECT POSITION');            // Reset the form
    $("#date_expires").addClass('hide');
}

function getimagefile()
{
    $(".view_image").fadeIn(100).attr('src',URL.createObjectURL(event.target.files[0]));
}

function editLifeSpan(val){
    if(val != 1){
        $("#date_expires").removeClass('hide');
        $("#date_expires #date").val("");
        $("#date_expires #quantity").val("");
    }
    else{
        $("#date_expires").addClass('hide');
        $("#date_expires #date").val("");
        $("#date_expires #quantity").val("");
    }
}

function get_expire_date(type,val){
 
    if(val.trim().length == 0){
        $('#date_expires input#date').val("");
    }
    else{
        $.ajax({
          url: "<?php echo base_url("user_account/generate_expire_date") ?>",
          data: {type:type,value:val},
          type: "GET",
          dataType: "html",
          success: function(data){
            $('#date_expires input#date').val(data);
          }
      });
    }
    

}

//SELECT ALL ACCESSIBILITIES //

$('#option_select_all').change(function() {
 
    
        $('.option').prop("checked", this.checked);
     
});

$('.option').change(function(){

    if($('input:checkbox:checked.option').length === $("input:checkbox.option").length)
    {
        $('#option_select_all').prop("checked",true);
    }
    else
    {
        $('#option_select_all').prop("checked",false);
    }

});

// EDIT PROFILE DATA //
edit_profile_data();

function edit_profile_data(){
  $("#pos_id").val("<?php echo $user_information['per_info']['pos_id']?>");
  $("#select2-pos_id-container").html("<?php echo $user_information['per_info']['position']?>");

  <?php foreach($user_information["access"] as $key => $value){ ?>
    
    $("input.option[value=<?php echo $value ?>]").prop("checked",true);

  <?php } ?>

  $("#txtlifespan").val("<?php echo $user_information['per_info']['life_span_type'] ?>");
  editLifeSpan("<?php echo $user_information['per_info']['life_span_type'] ?>");
  $("#date_expires #quantity").val("<?php echo $user_information['per_info']['life_span_qty'] ?>");
  $("#date_expires #date").val("<?php echo $user_information['per_info']['life_span'] ?>");
}

</script>
