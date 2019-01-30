<style type="text/css">
    .info-list{
        padding-left:0px;
    }
    .info-list li{
        margin-bottom:5px;
        margin-right:0px;
    }
</style>

<style type="text/css">
  table td{
    border:1px solid #333;
    width:100%;
  }
 /* $("#viewReferenceNoContainer table").css({"width":"100%"});
        $("#viewReferenceNoContainer table td").css({"border":"1px solid #333"});*/
</style>
<?php 
	if($details['details']['type'] == "request"){
		$path = "./attachments/requests/";
	}
	else{
		$path = "./attachments/tasks/";
	}
?>
<section class="content">

	<div class="row">

		<div class="col-md-12">
			
			<div class="row">
				<div class="col-md-3">
					<div class="box box-solid">
						<div class="box-header with-border">
							<b><i class="fa fa-user"></i> Requestor Information</b>
						</div>
						<div class="box-body">
							<div class="form-group"></div>
							<img onerror="this.onerror=null;this.src='<?php echo base_url("assets/dist/img/default.png")?>';" src="<?php echo base_url("images/profile_image")."/".$details['details']['img_path']?>" class="img img-thumbnail center-block" style="width:100%">
							<div class="form-group">
	                          <h3><?php echo $details['details']['from']?></h3>
	                          <ul class="info-list" style="list-style: none;font-size:14px">
	                              <li class="info-list-position"><i class='fa fa-briefcase'></i> <span><?php echo $details['details']['position']?></span></li>
	                              <li class="info-list-contact_no"><i class='fa fa-phone'></i> <span><?php echo $details['details']['contact_no']?></span></li>
	                          </ul>
	                      </div>
						</div>
					</div>
				</div>
				
				<div class="col-md-9">
					
					<div>
						
						<a href="<?php echo base_url("task") ?>" class="btn btn-default flat pull-right" style="margin-left:5px"><i class="fa fa-table"></i> Back to list</a>
						
						<?php if($details['details']['status'] == "pending"){ ?>
						<button onclick="confirm_decline()" class="btn btn-danger flat pull-right" style="margin-left:5px"><i class="fa fa-times"></i> Decline</button>
					 	<button onclick="confirm_process()" class="btn btn-success flat pull-right"><i class="fa fa-check"></i> Process</button>
					 	<?php } ?>

						<ul class="nav nav-tabs" role="tablist">
					    	<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-file-o"></i> OVERVIEW</a></li>
					    	<li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list"></i> VIEW PROCESS</a></li>
					  	</ul>

					  	<div class="tab-content" style="padding:15px;background:#fff">

					  		<div role="tabpanel" class="tab-pane active" id="home">

						  		<span class="pull-right"><?php echo $details['details']['date']?></span>
						    	<h1 style="font-size:27px;color:#3c8dbc;"><b><?php echo $details['details']['title']?></b></h1>
						    	<b style='font-size:18px'>REF NO.</b>&nbsp;&nbsp;&nbsp;<span style='font-size:18px'><?php echo $details['details']['ref_no']?></span>
						    	<hr>

						    	<?php echo $details['details']['content']?>

						    	<hr>
						    	
						    	<div class="attachment clearfix">
			                        <?php

			                        if(count($details['attach']) > 0){
			                        ?>
			                        	<p>
				                            <span><i class="fa fa-paperclip"></i> <?php echo count($details['attach']) ?> attachments — </span>
				                            <a href="#">Download all attachments</a>                             
				                        </p>
			                        <?php
										echo retrieve_attachments($path, $details['attach']);

									}

									?>
			                 	</div>

					  		</div>

					  		<div role="tabpanel" class="tab-pane" id="profile">

					  			<div class="form-group">
					  				<button class="btn btn-default"><i class="fa fa-refresh"></i> Refresh</button>
					  			</div>
					  			
					  		</div>

					  	</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</section>

<!-- CONFIRM CANCEL REQUEST MODAL -->

<div class="modal fade" id="confirm_process_rt_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to process <?php echo $details['details']['type'] ?> ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="button" onclick="process_request()" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- confirm decline request -->
<div class="modal fade" id="confirm_decline_rt_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <form id="form-decline-request" method="post" action="<?php echo base_url('request_task_approval/decline_request') ?>">
      <div class="modal-body">
      	  <p>Are you sure you want to decline <?php echo $details['details']['type'] ?> ?</p>
          <div class="form-group">
            <input value="<?php echo $details['details']['rt_id'] ?>" type="hidden" class="form-control" id="deactivate_pi_id" name="pi_id">
          </div>
          <div class="form-group">
            <textarea required id="deactivate_reason" name="reason" style="border:none!important" placeholder="Please give your reason . . ." required class="form-control" rows="5"></textarea>
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

</div>
<script type="text/javascript">
	
	get_process();

	function confirm_process(){
		$("#confirm_process_rt_modal").modal('show');
	}

	function confirm_decline(){
		$("#confirm_decline_rt_modal").modal('show')
	}

	$(document).ready(function(){

      $("#form-decline-request").on("submit", function(e){
        e.preventDefault();

        $.ajax({
              url: "<?php echo base_url('request_task_approval/decline_request') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {

                if(data['result'] == 1){ 
                  $("#confirm_decline_rt_modal").modal('hide')
                  $("#confirm_decline_rt_modal #deactivate_reason").val("");

                  new PNotify({
                          title: 'Success',
                          text: data['msg'],
                          type: 'success',
                          icon: 'fa fa-check'
                  });

                  $("#set_remarks_modal").modal('hide');

                }
                else if(data['result'] == 0){
                	$("#confirm_decline_rt_modal").modal('hide')
                	$("#confirm_decline_rt_modal #deactivate_reason").val("");
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

	function process_request(){
		$("#confirm_process_rt_modal").modal('hide');
		$.LoadingOverlay("show", {
		    image       : "<?php echo base_url('assets/images/loading.gif') ?>",
		    fontawesome : ""
		});

		$.ajax({
			url: "<?php echo base_url('request_task_approval/process_request') ?>",
			data: {rt_id : <?php echo $details['details']['rt_id'] ?>},
			dataType: "json",
			success: function(data){
				$.LoadingOverlay("hide");
				if(data['result'] == 1){ 

				  get_process();
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
				$.LoadingOverlay("hide");
				new PNotify({
                      title: 'Error',
                      text: "Function Error",
                      type: 'error',
                      icon: 'fa fa-times'
                });
			}
		});
	}

	function get_process(){
		$.ajax({
			url: "<?php echo base_url('request_task_approval/get_request_process') ?>",
			data: {rt_id : <?php echo $details['details']['rt_id'] ?>},
			dataType: "html",
			success: function(data){
				$(".tab-pane#profile").html(data);
			},
			error: function(){

			}
		});
	}
</script>