<script type="text/javascript">
	
	$(".content-header small").html("");
</script>

	<?php if($event_details['from_date'] == $event_details['until_date']){
			$date = date_format(date_create($event_details['from_date']), 'M j, Y');
		}
		else{
			$date = date_format(date_create($event_details['from_date']), 'M j, Y')." - ".date_format(date_create($event_details['until_date']), 'M j, Y');
		}
	?>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Sunday, Jan 01, 2016 - Jan 05, 2016 8:00 AM - 5:00 PM -->
			<div class="form-group pull-left">
				<b>
					<i class="fa fa-calendar"></i> 
					<?php echo date_format(date_create($event_details['from_date']), 'l')?>, <?php echo $date?> <?php echo date_format(date_create($event_details['from_time']), 'h:i A') ?> - <?php echo date_format(date_create($event_details['until_time']), 'h:i A') ?>
				</b>
				<br>
				<b><i class="fa fa-map-marker"></i> <?php echo $event_details['place']; ?></b>
			</div>
			<div class="form-group pull-right">
				<a href="<?php echo base_url('personal_calendar') ?>" class="btn btn-primary flat"><i class="fa fa-arrow-left"></i> Back to calendar</a>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-8">
			<p style="text-align:justify"><?php echo $event_details['description']; ?></p>
		</div>
	</div>
</section>

<!-- MODAL CONFIRM DELETE EVENT -->
<div class="modal fade" id="confirm_delete_event_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want delete event?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button onclick="delete_event()" id="modal-btn-delete-event" type="button" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
	
	$(".content-header small").html("<a onclick=\"edit_event('<?php echo $event_details["event_id"]?>')\" style='cursor:pointer'><i class='fa fa-pencil'></i> Edit</a>&nbsp;&nbsp;&nbsp;<a onclick=\"confirm_delete_event('<?php echo $event_details["event_id"]?>')\" style='cursor:pointer'><i class='fa fa-times'></i> Delete</a>");


	function edit_event(event_id){
      location.href="<?php echo base_url('personal_calendar/edit_event').'/'?>"+Base64.encode(event_id);
    }

	var del_event_id;

    function confirm_delete_event(event_id){
      del_event_id = event_id;
      $("#confirm_delete_event_modal").modal('show');
    }

    function delete_event(){
      $.ajax({
        url: "<?php echo base_url('personal_calendar/delete_event') ?>",
        data: {event_id : del_event_id},
        type: "GET",
        dataType: "json",
        success: function(data){
            if(data['result'] == 1){ 
            
              $("#confirm_delete_event_modal").modal('hide');
              
              new PNotify({
                      title: 'Success',
                      text: data['msg'],
                      type: 'success',
                      icon: 'fa fa-check'
              });
            }
            else if(data['result'] == 0){
                $("#confirm_delete_event_modal").modal('hide');
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
    }

</script>