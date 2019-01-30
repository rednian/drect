<section class="content">
	<div class="row">
		<form id="form_create_calendar" method="post">
			<div class="col-md-9">
				<div class="form-group">
					<button type="submit" class="btn btn-success flat"><i class="fa fa-check"></i> Save</button>
					<!-- <a href="<?php echo base_url('personal_calendar') ?>" class="btn btn-danger flat"><i class="fa fa-times"></i> Cancel</a> -->
          <a href="<?php echo base_url('personal_calendar') ?>" class="btn btn-primary flat pull-right"><i class="fa fa-arrow-left"></i> Back to calendar</a>
				</div>
				<div class="box box-solid">
					<div class="box-body">
						<div class="form-group">
							<input placeholder="Calendar name" type="text" class="form-control" name="calendar_name">
						</div>
						<div class="form-group">
							<textarea style="height:250px" placeholder="Description" id="calendar_description" class="form-control" name="calendar_description"></textarea>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</section>

<script type="text/javascript">
  var txtdescription;
	$(function(){
        txtdescription = $("#calendar_description").wysihtml5();
  	});

  	$(document).ready(function(){

      $('#form_create_calendar').bootstrapValidator({
          message: 'This value is not valid', 
          feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',  
            invalid: 'glyphicon glyphicon-remove',
            validating: 'fa fa-refresh fa-spin',
          },
          fields: {
            calendar_name: {
              message: 'The calendar name is not valid',
              validators: {
                notEmpty: {
                  message: 'The calendar name is required and can\'t be empty'
                },
                remote: {
                  url: '<?php echo base_url("personal_calendar/check_calendar_name") ?>',
                  message: 'Calendar name already exist',
                  delay: 1000,
                  type: 'POST',
                  data: {calendar_name : $(this).val()}
                }
              }
            },
            calendar_description: { 
              message: 'The description is not valid',
              validators: {
                notEmpty: {
                  message: 'The description is required and can\'t be empty'
                }
              }
            }        
          }
        })  .on('success.form.bv', function(e) {
          
          e.preventDefault();
          var $form     = $(e.target);
         
          $.ajax({
              url: "<?php echo base_url('personal_calendar/insert_calendar') ?>",
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
                    .bootstrapValidator('resetForm', true);             // Reset the for
                    	
                  new PNotify({
                          title: 'Success',
                          text: data['msg'],
                          type: 'success',
                          icon: 'fa fa-check'
                  });

                  calendar_description.setValue("");
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
</script>