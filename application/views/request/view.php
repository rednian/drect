<section class="content">

	<?php echo $details ?>

</section>

<style type="text/css">
  table td{
    border:1px solid #333;
    width:100%;
  }
 /* $("#viewReferenceNoContainer table").css({"width":"100%"});
        $("#viewReferenceNoContainer table td").css({"border":"1px solid #333"});*/
</style>

<!-- MODAL SEND REASON -->
<div class="modal fade" id="cancel_request_confirm_modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <form method="post" id="form_submit_reason_cancel">
        <div class="modal-body clearfix">
          <p>Are you sure you want to cancel the request?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
          <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
  var rt_id;
	$(document).ready(function(){
		$("#btn_cancel_request_onprocess").click(function(){
			$("#cancel_request_confirm_modal").modal("show");
			rt_id = $(this).attr('data-rtid');
		});

		$("form#form_submit_reason_cancel").on("submit", function(e){
			e.preventDefault();
			$.ajax({
			   url: "<?php echo base_url("request/cancel_rt_process") ?>",
         data: {rt_id : rt_id},
         type: "POST",
         dataType: "json",
         success: function(data){
            $("#cancel_request_confirm_modal").modal("hide");
            new PNotify({
                    title: data['title'],
                    text: data['msg'],
                    type: data['type'],
                    icon: data['icon'],
            });
            
         }, 
         error: function(){
           new PNotify({
                    title: "Error",
                    text: "Function error",
                    type: "error",
                    icon: "fa fa-times",
            });
         }
			});
		});
	});


</script>