<style type="text/css">
	.daterangepicker th,.daterangepicker td{
		border-radius: 0px;
	}
</style>

<script type="text/javascript">
	<?php if (is_numeric($cal_id)) { $date = ""; } ?>
</script>

<section class="content">
	<div class="row">
		<form id="form_create_event" method="post" action="<?php echo base_url('institution_calendar/is_overlapped') ?>">
			<div class="col-md-9">
				<div class="form-group">
					<button type="submit" class="btn btn-success flat"><i class="fa fa-check"></i> Save</button>
					<a href="<?php echo base_url('institution_calendar') ?>" class="btn btn-primary flat pull-right"><i class="fa fa-arrow-left"></i> Back to calendar</a>
					<button id="btn-reset" type="reset" class="btn btn-success flat hide"><i class="fa fa-check"></i> Save</button>
				</div>
				<div class="box box-solid">
					<div class="box-body">
						<div class="form-group">
							<input required placeholder="Event name" type="text" class="form-control" name="title">
						</div>
						<div class="form-group hide">
							<input value="0" placeholder="Event ID" type="text" class="form-control" name="event_id">
						</div>
						<div class="row">
							<div class="col-md-5">
								<div class="form-group">
									Select Calendar
									<div class="form-group">
										<select required name="calendar_id" id="calendar_id" class="form-control">
											<option disabled selected></option>
											<?php foreach ($calendars as $key => $value): ?>
												<option value="<?php echo $value->calendar_id ?>"><?php echo $value->calendar_name ?></option>
											<?php endforeach ?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-7">
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
							<div class="col-md-12">
								Involve:
								
								<div class="form-group" style="margin-bottom:5px">
			                        <select id="select-state" name="involve[]" multiple class="demo-default" placeholder="Person, Department Involve:">
			                        
			                                      <?php $maxItems = 0; foreach ($users as $key => $value): ?>
			                                      		<option value="<?php echo "person_".$value->pi_id; ?>"><?php echo $value->fullName('f l') ?></option>
			                                      <?php $maxItems++; endforeach ?>
			                                      <?php foreach ($departments as $key => $value): ?>
			                                      		<option value="<?php echo "department_".$value->dep_id; ?>"><?php echo ucwords($value->department); ?></option>
			                                      <?php $maxItems++;endforeach ?>
			                        </select>
			                        <script>
						                        var stize;
						                        var selectize;
			                                    stize = $('#select-state').selectize();
			                                    selectize = stize[0].selectize;
			                                     
			                        </script>    
			                    </div>
			                    <div class="form-group">
									<input id="toggle-event" data-on="yes" data-onstyle="default" data-off="no" data-size="mini" type="checkbox" checked data-toggle="toggle">&nbsp;&nbsp;&nbsp;All are involve?
								</div>
								<div class="form-group">
					              Venue:
					              <div class="form-group">
					              		<textarea name="place" class="form-control" rows="4" placeholder="Enter venue here"></textarea>
					              </div>
					              <!-- /.input group -->
					            </div>
							</div>
						</div>
						<div class="form-group">
							<textarea style="height:250px" placeholder="Description" id="description" class="form-control" name="description"></textarea>
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

	<?php if (is_numeric($cal_id)) { $date = ""; ?>
		// $("#calendar_id option[value = <?php echo $cal_id ?>]").attr("selected", true);
		$("#calendar_id").val(<?php echo $cal_id ?>);
	<?php } ?>
</script>

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
              url: "<?php echo base_url('institution_calendar/is_overlapped') ?>",
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
                		msg = "This event conflicts with "+data['count']+" other events. Do you want to save this event?";
                	}
                	else if(data['count'] == 1){
                		msg = "This event conflicts with "+data['count']+" other event. Do you want to save this event?";
                	}

                	$("#confirm_save_event .modal-body p").html(msg);
                	$("#confirm_save_event").modal('show');

                	// new PNotify({
                 //          title: 'Error',
                 //          text: data['msg'],
                 //          type: 'error',
                 //          icon: 'fa fa-times'
                 //    });
                }
              },
              error:function(data){
                
              }
            });
  		});

		$("#modal-btn-save-event").on("click",function(e){
			$("#confirm_save_event").modal('hide');
			$.ajax({
				url: "<?php echo base_url('institution_calendar/insert_event') ?>",
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

$(function() {
	if($('#toggle-event').prop('checked') == true){
      	selectize.disable();
	  }
	  else{
	  	selectize.enable();
	  }

    $('#toggle-event').change(function() {
      // alert('Toggle: ' + $(this).prop('checked'))
      if($(this).prop('checked') == true){
      	selectize.disable();
      }
      else{
      	selectize.enable();
      }
    });
  });
	
</script>