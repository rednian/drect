<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <img src="<?php echo base_url('assets/images/DRECT-WHITE.fw.png'); ?>" class="img-responsive">
  </div>
  <div class="login-box-body">
    <!-- <img src="BootstrapPlugin/dist/img/avatar04.png" class="img-circle img-thumbnail img-responsive center-block"  style="height:100px;width:100px"><br> -->
    <!-- <p class="login-box-msg">Sign in</p> -->
    <?php //echo validation_errors(); ?>
    <?php echo form_open('verifylogin',array('id'=>'loginForm' , 'class' => 'form-horizontal center' ,'role' => 'form')); ?>

      <div class="form-group has-feedback">
        <input autocomplete="off" name="username" type="text" class="form-control" placeholder="Username"/>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password"/>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-lg-12" style="padding-left:0px;padding-right:0px">
          <button type="submit" name="login" class="btn btn-primary btn-block btn-flat">Sign In</button>
          <br>
          <center><span id="errorMsg" style="color:red";></span></center>
        </div><!-- /.col -->
      </div>
    <?php echo form_close();?>

  </div>
</div>

<script>
       $(document).ready(function(){
          $('#loginForm').ajaxForm(function(r) {
            $('#errorMsg').html(r);
          });
        });
</script>