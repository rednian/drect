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
		<form id="form_create_event" method="post" action="<?php echo base_url('department_calendar/is_overlapped') ?>">
			<div class="col-md-9">
				<div class="form-group">
					<button type="submit" class="btn btn-success flat"><i class="fa fa-check"></i> Save</button>
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
									<input required placeholder="Event name" type="text" class="form-control" name="title">
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
							<input value="0" placeholder="Event ID" type="text" class="form-control" name="event_id">
						</div>
						<div class="form-group">
	                        Involve:
	                        <select id="select-state" name="involve[]" multiple class="demo-default" placeholder="Person, Department Involve:">
	                        
	                                      <?php $maxItems = 0; foreach ($users as $key => $value): ?>
	                                      		<option value="<?php echo "person_".$value->pi_id; ?>"><?php echo $value->fullName('f l') ?></option>
	                                      <?php $maxItems++; endforeach ?>
	                                      <?php foreach ($departments as $key => $value): ?>
	                                      		<option value="<?php echo "department_".$value->dep_id; ?>"><?php echo ucwords($value->department); ?></option>
	                                      <?php $maxItems++;endforeach ?>
	                        </select>
	                        <script>
	                                    $('#select-state').selectize({
	                                      maxItems: <?php echo $maxItems; ?>
	                                    });

	                                      // var projectUserIds = $('#select-state').selectize();
	                                      // var selectize = projectUserIds[0].selectize;
	                                      // selectize.setValue([
	                                      //   <?php
	                                          
	                                      //     $person_involve = person_involve($event_involve);
	                                      //     $department_involve = department_involve($event_involve);

	                                      //     $countP = count($person_involve);
	                                      //     $countD = count($department_involve);

	                                      //     for($x = 0;$x<$countP;$x++){
	                                      //       echo "'person_".$person_involve[$x][0]."',";
	                                      //     }
	                                      //     for($y = 0;$y<$countD;$y++){
	                                      //       echo "'department_".$department_involve[$y][0]."',";
	                                      //     }
	                                      //   ?>
	                                        
	                                      // ]);

	                                     
	                        </script>    
	                    </div>
						<div class="row">
							<div class="col-md-12">
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


<!-- MODAL CONFIRM SAVE EVENT -->
<div class="modal fade" id="confirm_save_event_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want save this event? There is no undo.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button onclick="" id="modal-btn-save-new-event" type="button" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
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

	txtdescription = $("#description").wysihtml5();

	$(function(){
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

                	$("#btn-reset").trigger("click");

                     var $select = $('#select-state').selectize();
					 var control = $select[0].selectize;
					 control.clear();

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


	                  var $select = $('#select-state').selectize();
					 var control = $select[0].selectize;
					 control.clear();

	                  $("#btn-reset").trigger("click");

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
				error: function(){

				}
			});
			
		});

		$("#modal-btn-save-new-event").on("click",function(e){
			$("#confirm_save_event_modal").modal('hide');

			$.ajax({
				url: "<?php echo base_url('department_calendar/insert_event') ?>",
				data: $("#form_create_event").serialize(),
				type: "POST",
				dataType: "json",
				success: function(data){
					if(data['result'] == 1){ 


	                  var $select = $('#select-state').selectize();
					 var control = $select[0].selectize;
					 control.clear();

	                  $("#btn-reset").trigger("click");

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
				error: function(){

				}
			});
			
		});
      	
	});    

  	function confirm_save(){
		$("#confirm_save_event_modal").modal("show");
	}
	
</script>