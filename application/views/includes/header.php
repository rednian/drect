<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DRECT PROJECT | <?php echo $title ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/drect_sm_icon.ico') ?>" />
    <!-- Bootstrap 3.3.5 -->
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet">
    <!-- Bootstrap File Input Master -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/plugins/bootstrap-fileinput-master/css/fileinput.css") ?>">

    <link rel="stylesheet" href="<?php echo base_url("assets/plugins/formValidation.css") ?>"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css');?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/ionicons/css/ionicons.min.css'); ?>">

    <link rel="stylesheet" href="<?php echo base_url("assets/plugins/select2/select2.min.css") ?>">
    <!-- Full Calendar -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/fullcalendar/fullcalendar.min.css') ?>">

    <link rel="stylesheet" type="text/css" media="print" href="<?php echo base_url('assets/plugins/fullcalendar/fullcalendar.print.css') ?>">
    <!-- assets Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <!-- iCheck -->
    <link href="<?php echo base_url('assets/plugins/iCheck/flat/blue.css');?>" rel="stylesheet">
    <!-- switchery -->
    <link href="<?php echo base_url('assets/plugins/switchery/switchery.min.css');?>" rel="stylesheet">
    <!-- Morris chart -->
    <link href="<?php echo base_url('assets/plugins/morris/morris.css');?>" rel="stylesheet">
    <!-- jvectormap -->
    <link href="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css');?>" rel="stylesheet">

    <!-- Date Picker -->
    <link href="<?php echo base_url('assets/plugins/datepicker/datepicker3.css');?>" rel="stylesheet">
    <!-- Daterange picker -->
    <link href="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker-bs3.css');?>" rel="stylesheet">
    <!-- bootstrap wysihtml5 - text editor -->
    <link href="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');?>" rel="stylesheet">
    <!-- bootstrap editable -->
    <link href="<?php echo base_url('assets/plugins/bootstrap-editable/bootstrap3-editable/css/bootstrap-editable.css');?>" rel="stylesheet">
    
    <!-- dataTable -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/datatable/media/css/dataTables.bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/datatable/extensions/Buttons/css/buttons.dataTables.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/datatable/extensions/Buttons/css/buttons.bootstrap.min.css') ?>">
    <!-- end datatable -->
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/dropzone/dropzone.css') ?>">

    <link href="<?php echo base_url('assets/plugins/notify/animate.min.css') ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url("assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") ?>">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/plugins/countdown/jquery.countdown.css') ?>">
    <!-- Theme style -->
    <link href="<?php echo base_url('assets/dist/css/AdminLTE.min.css');?>" rel="stylesheet">
    
    <link href="<?php echo base_url('assets/dist/css/skins/_all-skins.min.css');?>" rel="stylesheet">
    <!-- SELECTIZE -->
    <link href="<?php echo base_url('assets/plugins/selectize/dist/css/selectize.custom.css'); ?>" rel="stylesheet">
    <!-- MORRIS -->
    <link href="<?php echo base_url('assets/plugins/morris/morris.css'); ?>" rel="stylesheet" type="text/css" />
    <!-- BUTTON TOGGLE -->
    <link href="<?php echo base_url('assets/plugins/btn-toggle/bootstrap-toggle.min.css');?>" rel="stylesheet">
    <!-- CUSTOMIZE CSS -->
    <link href="<?php echo base_url('assets/css/custom1.css');?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/plugins/pace.css');?>" rel="stylesheet" />

    <script src="<?php echo base_url('assets/plugins/jQuery/jQuery-2.1.4.min.js');?>"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="<?php echo base_url('assets/plugins/jquery_ui.min.js') ?>"></script>
    <!-- SELECTIZE -->
    <script type="text/javascript" src="<?php echo base_url('assets/plugins/selectize/dist/js/standalone/selectize.js') ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/selectize/examples/js/index.js') ?>"></script>

    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/bootstrap-fileinput-master/js/fileinput.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/countdown/jquery.countdown.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/formValidation.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/framework/bootstrap.js') ?>"></script>
    <!-- bootstrap editable -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-editable/bootstrap3-editable/js/bootstrap-editable.min.js'); ?>"></script>

    <script src="<?php echo base_url("assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") ?>"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="<?php echo base_url('assets/plugins/morris/morris.min.js');?>"></script>
    <!-- Sparkline -->
    <script src="<?php echo base_url('assets/plugins/sparkline/jquery.sparkline.min.js');?>"></script>
    <!-- jvectormap -->
    <script src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'); ?>"></script>
    <!-- switchery -->
    <script src="<?php echo base_url('assets/plugins/iCheck/icheck.min.js'); ?>"></script>
    
    <script src="<?php echo base_url('assets/plugins/switchery/switchery.min.js'); ?>"></script>

    <!--<script src="<?php echo base_url('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');?>"></script>-->
    <!-- jQuery Knob Chart -->
    <script src="<?php echo base_url('assets/plugins/knob/jquery.knob.js');?>"></script>
    <!-- daterangepicker -->
    <script src="<?php echo base_url('assets/plugins/moment.min.js'); ?>"></script>
    <!-- Full Calendar -->
    <script src="<?php echo base_url('assets/plugins/fullcalendar/fullcalendar.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/daterangepicker/daterangepicker.js');?>"></script>

    <script src="<?php echo base_url('assets/plugins/dropzone/dropzone.js');?>"></script>
    <!-- datepicker -->
    <script src="<?php echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js');?>"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js');?>"></script>
    <!-- Slimscroll -->
    <script src="<?php echo base_url('assets/plugins/slimScroll/jquery.slimscroll.min.js');?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url('assets/plugins/fastclick/fastclick.min.js');?>"></script>
    <!-- assets App -->
    <script src="<?php echo base_url('assets/dist/js/app.min.js');?>"></script>
    <!-- assets dashboard demo (This is only for demo purposes) -->
    <!--<script src="<?php //echo base_url('assets/dist/js/pages/dashboard.js');?>"></script>-->
    <!-- assets for demo purposes -->
    <script src="<?php echo base_url('assets/dist/js/demo.js');?>"></script>

    <!-- assets for dataTable -->
    <script src="<?php echo base_url('assets/plugins/datatable/media/js/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/datatable/media/js/dataTables.bootstrap.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/datatable/extensions/Buttons/js/dataTables.buttons.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/datatable/extensions/Buttons/js/buttons.bootstrap.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/datatable/extensions/Buttons/js/buttons.colVis.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/datatable/extensions/Buttons/js/buttons.print.min.js') ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/datatable/extensions/Buttons/js/buttons.html5.min.js') ?>" type="text/javascript"></script>
    <!-- end datatables -->

    <script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.date.extensions.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.extensions.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/select2/select2.full.min.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/tinymce/js/tinymce/tinymce.min.js') ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/notify/pnotify.core.js') ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/notify/pnotify.buttons.js') ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/plugins/notify/pnotify.nonblock.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/validator/js/bootstrapValidator.min.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/validator/js/bootstrapValidator.min.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/jquery_form.min.js') ?>"></script>

    <script src="<?php echo base_url('assets/js/base_64.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/jquery_loader/src/loadingoverlay.js') ?>"></script>

    <!--<script src="<?php //echo base_url('assets/plugins/pace.js') ?>"></script>-->

    <script src="<?php echo base_url('assets/plugins/chartjs/Chart.min.js'); ?>" type="text/javascript"></script>

    <!-- ERROR PLUGIN -->
    <script src="<?php echo base_url('assets/plugins/flot/jquery.flot.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/flot/jquery.flot.resize.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/flot/jquery.flot.pie.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('assets/plugins/flot/jquery.flot.categories.js'); ?>" type="text/javascript"></script>

    <!-- BUTTON TOGGLE -->
    <script src="<?php echo base_url('assets/plugins/btn-toggle/bootstrap-toggle.min.js') ?>"></script>
    
    <script src="<?php echo base_url('assets/plugins/jssor/js/jssor.slider-21.1.6.mini.js') ?>"></script>

    <script src="<?php echo base_url('assets/plugins/bootbox.min.js') ?>"></script>
    
    <script type="text/javascript">
        $(document).bind("contextmenu", function (event) {
            // Avoid the real one
            // event.preventDefault();
        });

        function imgError(image) {
            image.onerror = "";
            image.src = "<?php echo base_url('images/default_onerror.png') ?>";
            return true;
        }

        function imgErrorChat(image) {
            image.onerror = "";
            image.src = "<?php echo base_url('images/profile_image/default.png') ?>";
            return true;
        }
    </script>
    <style type="text/css">
    body{
            font-family: calibri!important;
            color:#444;
        }
    </style>
  </head>



     