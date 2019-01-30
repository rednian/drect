<script type="text/javascript">
	$(".content-header small").html("<?php echo ucwords($subname); ?>");
</script>
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="form-group"><a class="btn btn-primary flat pull-right" href="<?php echo base_url('pwi_notification') ?>">Back to List</a></div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-5">
			<div class="box box-solid">
				<div class="box-header with-border">
					<b>Step Process:</b>
				</div>
				<div class="box-body">

					<?php foreach ($steps as $stp): ?>
						
						<div style="padding:10px;background:#f3f3f3;margin-bottom:5px" class="clearfix">
							<img onerror="this.onerror=null;this.src='<?php echo base_url("assets/dist/img/default.png")?>'" src="<?php echo base_url("images/profile_image/{$stp['img']}") ?>" style="width:50px;height:50px;" class="img pull-left">
							<div class="pull-left" style="padding:10px;padding-top:0px;padding-bottom: 0px">
								<h2 style="margin-top:0px"><?php echo $stp['step'] ?></h2>
								<b><?php echo $stp['signatory'] ?></b><br>
								<small><i class="fa fa-clock-o"></i> <?php echo $stp['duration'] ?></small>
							</div>
						</div>

					<?php endforeach ?>

				</div>	
			</div>

			<div class="box box-solid">
				<div class="box-header with-border">
					<b>Description:</b>
				</div>
				<div class="box-body">

					<p style="text-align:justify"><?php echo $description ?></p>

				</div>	
			</div>
		</div>
		<div class="col-md-7">
			
			<div class="box box-solid">
				<div class="box-header">
					<b>Form Used</b>
				</div>
				<div class="box-body" style="min-height:80px;max-height:500px;overflow:auto;background:#f3f3f3">
					<?php if(strlen($form) > 0){
							echo "<p>".$form."</p>";
						}else{
							echo "<p>No form available . . .</p>";
							} ?>
				</div>	
			</div>
			
		</div>
	</div>
</section>
