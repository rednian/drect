<?php include("css.php") ?>
<script type="text/javascript">
	$(".content-header small").html("<a href=\"<?php echo base_url('mail') ?>\" class='btn btn-xs btn-primary flat'><i class='fa fa-arrow-left'></i> Back to Mail</a>");
</script>

<section class="content">
	
	<div class="view_email_container">
		
		<div class="row">
			<div class="col-lg-10">
					<?php $count = 1; ?>
					<div class="panel-group" id="accordion_<?php echo $count; ?>" role="tablist" aria-multiselectable="true">

					  <div class="panel panel-default">
					    <div class="panel-heading" role="tab" id="heading_<?php echo $count; ?>" data-toggle="collapse" data-parent="#accordion_<?php echo $count; ?>" href="#collapse_<?php echo $count; ?>" aria-expanded="true" aria-controls="collapseOne">

					      <h4 class="panel-title clearfix">
					      	<span class="pull-right"><?php echo $date." (".$ellapse." ago)" ?></span>
					      	<img onerror="imgError(this)" src="<?php echo base_url('images/profile_image')."/".$img_path ?>" class="img pull-left" style="width:40px;height:40px;margin-right:10px">
					        <span style="font-weight:bold">
					          <?php echo $from ?>
					        </span>
					        <div id="content_summary_<?php echo $count ?>" style="color:#666;width:50%;font-size:14px;padding-top:4px;white-space:nowrap;text-overflow:ellipsis;overflow:hidden"><?php echo "to ".$to; ?></div>
					      </h4>
					    </div>
					    <div id="collapse_<?php echo $count; ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_<?php echo $count; ?>">
					      <div class="panel-body">
					      	<?php echo $content; ?>

					      	<?php if (strlen($attachments) > 0): ?>
					      	
					      	<div class="attachment clearfix" style="margin-top:20px;margin-bottom:20px">
					      		<p><b><i class="fa fa-paperclip"></i> Attachments:</b></p>
					      		<?php echo $attachments ?>
					      	</div>

					      	<?php endif ?>

					      </div>
					    </div>
					  </div>
					  
					</div>

			</div>
		</div>
		
	</div>

</section>
