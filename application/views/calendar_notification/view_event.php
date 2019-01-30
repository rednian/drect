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
				<a href="<?php echo base_url('calendar_notification') ?>" class="btn btn-primary flat"><i class="fa fa-arrow-left"></i> Back to List</a>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-8">
			<p style="text-align:justify"><?php echo $event_details['description']; ?></p>
		</div>

    <?php if (!empty($involve)): ?>
      
      <div class="col-md-4">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title" style="font-weight:bold">GUESTS</h3>
        </div>
        <div class="box-body">

        <!-- PERSON INVOLVE -->
          <?php if (!empty($involve['person'])): ?>
              
              <b>Personnel:</b>

              <?php foreach ($involve['person'] as $key => $value): ?>
                  
                  <div class="clearfix" style="width:100%;padding:10px;background:#f3f3f3;margin-bottom:3px"><img onerror="this.onerror=null;this.src='<?php echo base_url("assets/dist/img/default.png")?>'" src='<?php echo base_url('images/profile_image')."/".$value['img_path'] ?>' style="width:35px;height:35px;margin-right:10px" class="pull-left"> <?php echo ucwords($value['name']) ?> <div style="font-size:12px;color:#989898;margin-top:0px"><?php echo ucwords($value['position']) ?></div> </div>

              <?php endforeach ?>

          <?php endif ?>

          <!-- DEPARTMENT INVOLVE -->
          <?php if (!empty($involve['department'])): ?>
            
            <b>Departments:</b>

            <?php foreach ($involve['department'] as $key => $value): ?>
                
                <div style="width:100%;padding:10px;background:#f3f3f3;margin-bottom:3px"><?php echo ucwords($value); ?></div>

            <?php endforeach ?>

          <?php endif ?>
        
        </div>
      </div>
    </div>

    <?php endif ?>

	</div>
</section>

<script type="text/javascript">

 

</script>