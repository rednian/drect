<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php echo form_open_multipart('PolicyController/updatePolicy', ["id"=>'frmAddPolicy']) ?>
			<input type="hidden" name="policy_id" value="<?php echo $details[$policy_id]->policy_id ?>">
			<div class="box box-solid">
				<div class="box-header with-border">
					<a type="button" href="<?php echo base_url('policy') ?>" class="btn btn-default pull-right flat" style="margin-left:10px">Back to List</a> <button type="submit" class="btn btn-success pull-right flat"><i class="fa fa-save"></i> Save Policy</button>
					<button class="hide" type="reset">reset</button>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Policy No.</label>
								<input autocomplete="off" value="<?php echo $details[$policy_id]->policy_no ?>" type="text" class="form-control" name="policy_no">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Policy Title</label>
								<input autocomplete="off" value="<?php echo $details[$policy_id]->policy_title ?>" type="text" class="form-control" name="policy_title">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Attachment</label>
								<input id="rtp_att" name="path[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="box box-solid">						
								<div class="box-body" id="test">dsfsaf</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" id="text-area-container">
			    				<textarea name="policy_content" id="text_form_procedure"><?php echo $details[$policy_id]->policy_content ?></textarea>
			    			</div>
						</div>
					</div>

				</div>
			</div>
			<?php echo form_close() ?>
		</div>
	</div>
</section>

<script type="text/javascript">

	loadAttachments();

	$(function(){

		tinymce.init({
		  selector: '#text_form_procedure',
		  height: 500,
		  theme: 'modern',
		  plugins: [
		    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
		    'searchreplace wordcount visualblocks visualchars code fullscreen',
		    'insertdatetime media nonbreaking save table contextmenu directionality',
		    'emoticons template paste textcolor colorpicker textpattern imagetools'
		  ],
		  toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		  toolbar2: 'print preview media | forecolor backcolor emoticons',
		  image_advtab: true,
		  templates: [
		    { title: 'Test template 1', content: 'Test 1' },
		    { title: 'Test template 2', content: 'Test 2' }
		  ],
		  content_css: [
		    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
		    '//www.tinymce.com/css/codepen.min.css'
		  ]
		});
		
	});

	function deleteRtpAtt(pol_at_id, path, el){

		bootbox.confirm("Are you sure you want delete the attachment?", function(result) {
		  	if(result == true){
			  	$.ajax({
			  		url: "<?php echo base_url('PolicyController/deleteAttachment') ?>",
			  		data: {pol_at_id : pol_at_id, path : path},
			  		type: "GET",
			  		dataType: "json",
			  		success: function(data){

			  			if(data.result == true){

			  				$(el).parent().fadeOut(1000);
			  				// $("#modalGallery .box-body div#rtpa_"+pol_at_id).fadeOut(1000);

			  				new PNotify({
		                          title: 'Success',
		                          text: 'Attachment deleted successfully.',
		                          type: 'success',
		                          icon: 'fa fa-check'
		                    });

			  			}
			  			else{
			  				new PNotify({
		                          title: 'Error',
		                          text: 'Attachment not deleted.',
		                          type: 'error',
		                          icon: 'fa fa-times'
		                    });

			  			}
			  		},
			  		error: function(){

			  		}
			  	});	
		  	}
		}); 
	}

	function loadAttachments(){
		$.ajax({
			url: "<?php echo base_url('PolicyController/loadAttachments') ?>",
			type: "GET",
			data: {policy_id : <?php echo $details[$policy_id]->policy_id; ?>},
			dataType: "html",
			success: function(data){

				$("div.box-body#test").html(data);
			},
			error: function(){
				alert("error");
			}	
		});
	}

	$(document).ready(function(){

		$("#frmAddPolicy").on("submit", function(e){

			e.preventDefault();
			tinyMCE.triggerSave();

			$.ajax({
              url: "<?php echo base_url('PolicyController/updatePolicy') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {

                if(data['result'] == true){
                	loadAttachments();
                	$("#frmAddPolicy button[type=reset]").trigger("click");
	                  new PNotify({
	                          title: 'Success',
	                          text: "Policy has been updated successfully.",
	                          type: 'success',
	                          icon: 'fa fa-check'
	                  });

                }
                else if(data['result'] == false){

                    new PNotify({
                          title: 'Error',
                          text: 'Policy not updated.',
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