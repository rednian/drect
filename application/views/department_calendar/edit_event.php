<style type="text/css">
	.daterangepicker th,.daterangepicker td{
		border-radius: 0px;
	}
</style>

<script type="text/javascript">
	$(".content-header small").html("Edit");
</script>
<?php 
	$date = date_format(date_create($event_details['from_date']), "m/d/Y")." ".date_format(date_create($event_details['from_time']),"g:i A")." - ".date_format(date_create($event_details['until_date']), "m/d/Y")." ".date_format(date_create($event_details['until_time']),"g:i A");
?>
<section class="content">
	<div class="row">
		<form id="form_create_event" method="post" action="<?php echo base_url('department_calendar/is_overlapped') ?>">
			<div class="col-md-9">
				<div class="form-group">
					<button type="submit" class="btn btn-success flat"><i class="fa fa-check"></i> Save Changes</button>
					<!-- <a href="<?php echo base_url('department_calendar') ?>" class="btn btn-danger flat"><i class="fa fa-times"></i> Cancel</a> -->
					<a href="<?php echo base_url('department_calendar') ?>" class="btn btn-primary flat pull-right"><i class="fa fa-arrow-left"></i> Back to calendar</a>
					<button id="btn-reset" type="reset" class="btn btn-success flat hide"><i class="fa fa-check"></i> Save</button>
				</div>
				<div class="box box-solid">
					<div class="box-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									Title:
									<input value="<?php echo $event_details['event_title']?>" required placeholder="Event name" type="text" class="form-control" name="title">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
					              Date:
					              <div class="form-group">
					              		<div class="input-group">
							                <div class="input-group-addon">
							                  <i class="fa fa-clock-o"></i>
							                </div>
							                <input value="<?php echo $date ?>" required id="datetime" name="datetime" readonly type="text" class="form-control pull-right">
							              </div>
					              </div>
					              <!-- /.input group -->
					            </div>
							</div>
						</div>
						<div class="form-group hide">
							<input value="<?php echo $event_details['event_id']?>" placeholder="Event ID" type="text" class="form-control" name="event_id">
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
					              Venue:
					              <div class="form-group">
					              		<textarea name="place" class="form-control" rows="4" placeholder="Enter venue here"><?php echo $event_details['place']?></textarea>
					              </div>
					              <!-- /.input group -->
					            </div>
							</div>
						</div>
						<div class="form-group">
							<textarea style="height:250px" placeholder="Description" id="description" class="form-control" name="description"><?php echo $event_details['description']?></textarea>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</section>

<!-- CONFIRM CANCEL REQUEST MODAL -->

<div class="modal fade" id="confirm_save_event" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Event conflicts</h4>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button id="modal-btn-save-event" type="button" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
	var txtdescription;
	var datetimepicker;

	$(function(){
        txtdescription = $("#description").wysihtml5();
        datetimepicker = $('#datetime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});

  	});

  	$(document).ready(function(){

  		$("#form_create_event").on("submit",function(e){

  			e.preventDefault();

  			$.ajax({
              url: "<?php echo base_url('department_calendar/is_overlapped') ?>",
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

                  $("#btn-reset").trigger("click");
                  txtdescription.setValue("");
                }
                else if(data['result'] == 0){
                    new PNotify({
                          title: 'Error',
                          text: data['msg'],
                          type: 'error',
                          icon: 'fa fa-times'
                    });
                }
                else if(data['result'] == "overlapped"){

                	var msg = "";

                	if(data['count'] > 1){
                		msg = "This event conflicts with "+data['count']+" other events. Do you want to update this event?";
                	}
                	else if(data['count'] == 1){
                		msg = "This event conflicts with "+data['count']+" other event. Do you want to update this event?";
                	}

                	$("#confirm_save_event .modal-body p").html(msg);
                	$("#confirm_save_event").modal('show');

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

		$("#modal-btn-save-event").on("click",function(e){
			$("#confirm_save_event").modal('hide');
			$.ajax({
				url: "<?php echo base_url('department_calendar/insert_event') ?>",
				data: $("#form_create_event").serialize(),
				type: "POST",
				dataType: "json",
				success: function(data){
					if(data['result'] == 1){ 
	                  new PNotify({
	                          title: 'Success',
	                          text: data['msg'],
	                          type: 'success',
	                          icon: 'fa fa-check'
	                  });

	                  $("#btn-reset").trigger("click");
	                  txtdescription.setValue("");
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
				error: function(){

				}
			});
			
		});
      	
	});    


	
</script>