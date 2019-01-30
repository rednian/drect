<?php include("css.php") ?>
<script type="text/javascript">
	$(".content-header small").html("<a href=\"<?php echo base_url('mail') ?>\" class='btn btn-xs btn-primary flat'><i class='fa fa-arrow-left'></i> Back to Mail</a>");
</script>
<style type="text/css">
	textarea {
	    -moz-box-sizing: border-box;
	    box-sizing: border-box;
	    resize: none;
	}
</style>
<section class="content">
	
	<div class="view_email_container">
		
		<div class="row">
			<div class="col-lg-10">
			<?php $CI =& get_instance(); ?>
				<?php $in=""; $count = 1; $tr_count= count($thread); foreach ($thread as $key => $value): ?>
					<?php $to=$value['to']; $content =trim(preg_replace('/\s+/', ' ', strip_tags($value['content']))); if($value['pi_id'] == $this->userInfo->pi_id){$to = "me";}?>
					<div class="panel-group" id="accordion_<?php echo $count; ?>" role="tablist" aria-multiselectable="true">

					  <div class="panel panel-default">
					    <div onclick="collapse_check('collapse_<?php echo $count; ?>','content_summary_<?php echo $count ?>','<?php echo $to; ?>','<?php echo $CI->db->escape_str($content); ?>')" class="panel-heading" role="tab" id="heading_<?php echo $count; ?>" data-toggle="collapse" data-parent="#accordion_<?php echo $count; ?>" href="#collapse_<?php echo $count; ?>" aria-expanded="true" aria-controls="collapseOne">
					      <?php if($count == $tr_count){$in="in";$content="to ".$to; } ?>
					      <h4 class="panel-title clearfix">
					      	<span class="pull-right"><?php echo $value['date']." (".$value['ellapse']." ago)" ?></span>
					      	<img onerror="imgError(this)" src="<?php echo base_url('images/profile_image')."/".$value['img_path'] ?>" class="img pull-left" style="width:40px;height:40px;margin-right:10px">
					        <span style="font-weight:bold">
					          <?php echo $value['from'] ?>
					        </span>
					        <div id="content_summary_<?php echo $count ?>" style="color:#666;width:50%;font-size:14px;padding-top:4px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden"><?php echo $content; ?></div>
					      </h4>
					    </div>
					    <div id="collapse_<?php echo $count; ?>" class="panel-collapse collapse <?php echo $in; ?>" role="tabpanel" aria-labelledby="heading_<?php echo $count; ?>">
					      <div class="panel-body">
					      	<?php echo $value['content']; ?>

					      	<?php if (strlen($value['attachments']) > 0): ?>
					      	
					      	<div class="attachment clearfix" style="margin-top:20px;margin-bottom:20px">
					      		<p><b><i class="fa fa-paperclip"></i> Attachments:</b></p>
					      		<?php echo $value['attachments'] ?>
					      	</div>

					      	<?php endif ?>
					      </div>
					    </div>
					  </div>
					  
					</div>

				<?php $count++; endforeach ?>
				
				
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-lg-10 clearfix">
				
				<div class="pull-left">
					<img onerror="imgError(this)" src="<?php echo base_url('images/profile_image')."/".$this->userInfo->img_path ?>" class="img" style="width:40px;height:40px;margin-right:10px;margin-bottom:5px">
				</div>
				<div style="padding-left:50px;">

					<form id="form_reply" method="post" action="<?php echo base_url('mail/reply_my_mail'); ?>" enctype="multipart/form-data">
						<input class="hide" value="<?php echo $subject; ?>" type="text" name="mail_subject">
						<input class="hide" value="<?php echo $from; ?>" type="text" name="txtcontact">
						<input class="hide" value="<?php echo $t_no; ?>" type="text" name="thread_no">
						<div class="form-group">
							<textarea name="mail_content" style="height:400px" data-autoresize class="form-control" id="txtmessage"></textarea>
						</div>
						<div class="form-group">
			              <label for="exampleInputEmail1">Attachments</label>
			              <input data-allowed-file-extensions='["csv", "txt", "xlsx", "docx", "pptx", "png", "jpg", "xls", "doc", "ppt", "pdf"]' id="input-2" name="rta_attachment[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true">
			          </div>
						<div class="form-group">
							<button type="submit" class="btn btn-success flat"><i class="fa fa-send"></i> Send</button>
							<button type="reset" id="btn_reset" class="btn btn-danger flat"><i class="fa fa-times"></i> Cancel</button>
						</div>
					</form>

				</div>
				
			</div>
		</div>
	</div>

</section>

<script type="text/javascript">

	jQuery.each(jQuery('textarea[data-autoresize]'), function() {
	    var offset = this.offsetHeight - this.clientHeight;
	 
	    var resizeTextarea = function(el) {
	        jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
	    };
	    jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
	});

	var txtmessage = $("#txtmessage").wysihtml5();

	function collapse_check(container,details,to,content){
		$('#'+container).on('show.bs.collapse', function () {
		  $('#'+details).html("to "+to);
		});

		$('#'+container).on('hidden.bs.collapse', function () {
		  $('#'+details).html(content);
		});
	}

	$(document).ready(function(){

		$("form#form_reply").on("submit", function(e){

			e.preventDefault();

			$.ajax({
	          url: "<?php echo base_url('mail/reply_my_mail'); ?>",
	          type: "POST",            
	          data: new FormData(this),
	          dataType: "json",
	          contentType: false,      
	          cache: false,            
	          processData:false,       
	          success: function(data)  
	          {
	            if(data['result'] == 1){ 

	              $("#btn_reset").trigger("click");

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
	           	new PNotify({
                          title: 'Error',
                          text: 'Function Error',
                          type: 'error',
                          icon: 'fa fa-times'
                    });
	          }
	       	});

		});
	});

	
</script>