<style type="text/css">

  .processSuccess{
    border-top-color: #00B285;
  }
  .processSuccess span, .processSuccess h3, .processSuccess .box-body{
    color: #00B285;
  }
  .processSuccess .box-footer{
    background: #00B285;
    color:#FFF;
  }
  .processSuccess .box-footer span{
    color:#FFF;
  }

  /*PROCESSING*/
  .processProcessing{
    border-top-color: #00A3D9;
  }
  .processProcessing span, .processProcessing h3, .processProcessing .box-body{
    color: #00A3D9;
  }
  .processProcessing .box-footer{
    background: #00A3D9;
    color:#FFF;
  }
  .processProcessing .box-footer span{
    color:#FFF;
  }
  /*PENDING*/
  .processPending{
    border-top-color: #FFB973;
  }
  .processPending span, .processPending h3, .processPending .box-body{
    color: #FFB973;
  }
  .processPending .box-footer{
    background: #FFB973;
    color:#FFF;
  }
  .processPending .box-footer span{
    color:#FFF;
  }
  /*DENIED*/
  .processDenied{
    border-top-color: #FF7373;
  }
  .processDenied span, .processDenied h3, .processDenied .box-body{
    color: #FF7373;
  }
  .processDenied .box-footer{
    background: #FF7373;
    color:#FFF;
  }
  .processDenied .box-footer span{
    color:#FFF;
  }
  /*OVERDUE*/
  .processOverdue{
    border-top-color: #999999;
  }
  .processOverdue span, .processOverdue h3, .processOverdue .box-body{
    color: #999999;
  }
  .processOverdue .box-footer{
    background: #999999;
    color:#FFF;
  }
  .processOverdue .box-footer span{
    color:#FFF;
  }
  /*PENDING 2*/
  .processPending2{
    border-top-color: #FFF;
  }
  .processPending2 span, .processPending2 h3, .processPending2 .box-body{
    color: #999;
  }
  .processPending2 .box-footer{
    background: #FFF;
    color:#999;
  }
  .processPending2 .box-footer span{
    color:#999;
  }

</style>

<section class="content" style="padding-top:0px;background:#f3f3f3">
  <div class="row" style="margin-bottom:20px">
    <div id="namePanel" class="col-md-12" style="background:#666;padding:10px;color:#FFF">
      <div class="pull-left">
        <img src="<?php echo base_url('images/profile_image/default.png'); ?>" style="width:40px;height:40px;margin-right:10px">
        <span class="text-bold" style="font-size:20px">JOVANNI G. BAYAWA</span>
      </div>
      <div class="form-group pull-right col-md-3">
        <select id="selectRequest" class="form-control input-sm">
          <option class="hide" selected>Select Request</option>
          <?php foreach ($rt_list as $key => $value): ?>
            <option img="<?php echo $value->img_path; ?>" requestor="<?php echo strtoupper($value->fullName('f, l')); ?>" value="<?php echo $value->rt_id ?>"><?php echo "[".str_pad($value->rt_id, 6 ,"0",STR_PAD_LEFT)."] ".$value->rt_title ?></option>
          <?php endforeach ?>
        </select>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8" id="imgContainer"></div>
    <div class="col-md-4">
      <div id="processContainer" style="min-height:650px;max-height:650px;overflow:auto;padding-right:5px"></div>
    </div>
  </div>
</section><!-- /.content -->
     
<script type="text/javascript">

  var rt_id = 0;
  var refreshStatus;
  var refreshImage;

  if(rt_id != 0){
    refreshStatus = setInterval(showProcess, 1000);
    refreshImage = setInterval(showImage, 1000);
  }

  $("select#selectRequest").on("change", function(){
    rt_id = $(this).val();

    var img = $('option:selected', this).attr("img");
    var user = $('option:selected', this).attr("requestor");

    $("div#namePanel div.pull-left img").attr("src", img);
    $("div#namePanel div.pull-left span").html(user);

    showProcess();
    showImage();
  });

  function showProcess(){
    clearInterval(refreshStatus);
    $.ajax({
      url: "<?php echo base_url('dashboard2/view') ?>",
      data: {rt_id : rt_id},
      type: "GET",
      dataType: "HTML",
      success: function(data){
        $("div#processContainer").html(data);
        refreshStatus = setInterval(showProcess, 1000);
      },
      error: function(){

      }
    });
  }

  function showImage(){
    clearInterval(refreshImage);
    $.ajax({
      url: "<?php echo base_url('dashboard2/checkCurrentState') ?>",
      data: {rt_id : rt_id},
      type: "GET",
      dataType: "HTML",
      success: function(data){
        $("div#imgContainer").html(data);
        refreshImage = setInterval(showImage, 1000);
      },
      error: function(){

      }
    });
  }


</script>