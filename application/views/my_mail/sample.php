<?php include("css.php") ?>
<style type="text/css">
  .content{
    font-family: 'Open Sans',"Helvetica Neue",Helvetica,Arial,sans-serif;
  }
  .table-email td{
    font-family: 'Open Sans',"Helvetica Neue",Helvetica,Arial,sans-serif;
    font-size: 12px;
  }
  .menu_email{
    font-size: 12px;
  }
  .pagination{
    margin-top:0px!important;
    margin-bottom:0px!important;
    margin-left:5px!important;
  }
  .dataTables_filter{
    display:none!important;
  }
  table tbody tr.read_status_no td{
    background: rgba(60, 141, 188, 0.16)!important;
  }
</style>
<section class="content">
          <div class="row">
            <div class="col-md-3">
              <a onclick="deselect_active()" role="presentation" href="#compose" aria-controls="compose" role="tab" data-toggle="tab" class="btn btn-primary btn-block margin-bottom flat"><i class="fa fa-edit"></i> Compose</a>
              <div class="box box-solid menu_email">
                <div class="box-header with-border">
                  <h3 class="box-title">Folders</h3>
                  <div class="box-tools">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li role="presentation" class="active"><a onclick="form_reset()" href="#inbox" aria-controls="inbox" role="tab" data-toggle="tab"><i class="fa fa-inbox"></i> Inbox <span id="inbox_counter" class="label label-primary pull-right"></span></a></li>
                   
                    <li role="presentation"><a onclick="form_reset()" href="#circulation" aria-controls="circulation" role="tab" data-toggle="tab"><i class="fa fa-envelope-o"></i> From Circulation <span id="cir_counter" class="label label-primary pull-right"></span></a></li>
                     <li role="presentation"><a onclick="form_reset()" href="#sent" aria-controls="sent" role="tab" data-toggle="tab"><i class="fa fa-envelope-o"></i> Sent</a></li>
                    <li role="presentation"><a onclick="form_reset()" href="#trash" aria-controls="trash" role="tab" data-toggle="tab"><i class="fa fa-trash-o"></i> Trash</a></li>
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
              <!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-9" style="padding-left:0px">

            <div class="tab-content">
              <!-- BOX COMPOSE EMAIL -->
              <div role="tabpanel" id="compose" class="box box-solid tab-pane">
                <div class="box-header with-border">
                  <h3 class="box-title">Compose Message</h3>
                  <!-- /.box-tools -->
                </div><!-- /.box-header -->
                <form id="form_send_mail" method="post" action="<?php echo base_url('mail/insert_my_mail') ?>" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                    <select name="txtcontact[]" id="select-to" class="contacts" placeholder="Pick some people..."></select>

                    </select>
                    <script>
                // <select id="select-to"></select>

                  var REGEX_EMAIL = '([a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@' +
                                    '(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)';

                  var formatName = function(item) {
                    return $.trim((item.first_name || '') + ' ' + (item.last_name || ''));
                  };

                  $('#select-to').selectize({
                    persist: false,
                    maxItems: null,
                    valueField: 'email',
                    labelField: 'name',
                    searchField: ['first_name', 'last_name', 'email'],
                    sortField: [
                      {field: 'first_name', direction: 'asc'},
                      {field: 'last_name', direction: 'asc'}
                    ],
                    options: <?php echo $contacts ?>,
                    render: {
                      item: function(item, escape) {
                        var name = formatName(item);
                        return '<div>' +
                          (name ? '<span class="name">' + escape(name) + '</span>' : '') +
                          (item.email ? '<span class="email">' + escape(item.email) + '</span>' : '') +
                        '</div>';
                      },
                      option: function(item, escape) {
                        var name = formatName(item);
                        var label = name || item.email;
                        var caption = name ? item.email : null;
                        return '<div>' +
                          '<span class="label">' + escape(label) + '</span>' +
                          (caption ? '<span class="caption">' + escape(caption) + '</span>' : '') +
                        '</div>';
                      }
                    },
                    createFilter: function(input) {
                      var regexpA = new RegExp('^' + REGEX_EMAIL + '$', 'i');
                      var regexpB = new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i');
                      return regexpA.test(input) || regexpB.test(input);
                    },
                    create: function(input) {
                      if ((new RegExp('^' + REGEX_EMAIL + '$', 'i')).test(input)) {
                        return {email: input};
                      }
                      var match = input.match(new RegExp('^([^<]*)\<' + REGEX_EMAIL + '\>$', 'i'));
                      if (match) {
                        var name       = $.trim(match[1]);
                        var pos_space  = name.indexOf(' ');
                        var first_name = name.substring(0, pos_space);
                        var last_name  = name.substring(pos_space + 1);

                        return {
                          email: match[2],
                          first_name: first_name,
                          last_name: last_name
                        };
                      }
                      alert('Invalid email address.');
                      return false;
                    }
                  });
                  </script>

                  </div>
                  <div class="form-group">
                    <input name="mail_subject" required type="text" class="form-control input-sm" placeholder="Subject">
                  </div>
                  <div class="form-group">
                    <textarea name="mail_content" id="txtmessage" class="textarea" placeholder="Message" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                  </div>
                  <div class="form-group">
                      <label for="exampleInputEmail1">Attachments</label>
                      <input data-allowed-file-extensions='["csv", "txt", "xlsx", "docx", "pptx", "png", "jpg", "xls", "doc", "ppt", "pdf"]' id="input-2" name="rta_attachment[]" type="file" class="file" multiple data-show-upload="false" data-show-caption="true">
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <button type="reset" id="btn_reset_form" class="btn btn-danger btn-sm flat hide">Reset</button>
                  <button type="submit" name="btn-send" class="btn btn-primary btn-sm flat"><i class="fa fa-check"></i> Send</button>
                </div>
                </form>
              </div>
              <!-- !COMPOSE EMAIL -->
              <!-- BOX INBOX -->
              <div role="tabpanel" id="inbox" class="box box-solid tab-pane active">
                <div class="box-header with-border">
                  <h3 class="box-title">Inbox</h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input id="search_inbox" type="text" class="form-control input-sm" placeholder="Search Mail">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <form method="post" action="<?php echo base_url('mail/delete_inbox') ?>" id="form_delete_inbox">
                    <div class="mailbox-controls">
                      <!-- Check all button -->
                      <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                      <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button onclick="load_inbox_mails()" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                      <!-- /.pull-right -->
                    </div>
                    <div class="mailbox-messages">
                      <table id="tbl_inbox" class="table table-hover table-striped table-email">
                        <thead class="hide">
                          <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                      </table><!-- /.table -->
                    </div><!-- /.mail-box-messages -->
                  </form>
                </div><!-- /.box-body -->
                
              </div><!-- /. box -->
              <!-- !BOX INBOX -->

              <!-- BOX SENT -->
              <div role="tabpanel" id="sent" class="box box-solid tab-pane">
                <div class="box-header with-border">
                  <h3 class="box-title">Sent</h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input id="search_sent" type="text" class="form-control input-sm" placeholder="Search Mail">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <form method="post" action="<?php echo base_url('mail/delete_sent') ?>" id="form_delete_sent">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                    <button onclick="load_sent_mails()" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <!-- /.pull-right -->
                  </div>
                  <div class="mailbox-messages">
                    <table id="tbl_sent" class="table table-hover table-striped table-email">
                      <thead class="hide">
                        <tr>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                    </table><!-- /.table -->
                  </div><!-- /.mail-box-messages -->
                  </form>
                </div><!-- /.box-body -->
              </div>
              <!-- !BOX SENT -->

              <!-- BOX TRASH -->
              <div role="tabpanel" id="trash" class="box box-solid tab-pane">
                <div class="box-header with-border">
                  <h3 class="box-title">Trash</h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input id="search_trash" type="text" class="form-control input-sm" placeholder="Search Mail">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <form method="post" action="<?php echo base_url('mail/restore_mail') ?>" id="form_restore_inbox">
                    <div class="mailbox-controls">
                      <!-- Check all button -->
                      <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                      <button type="submit" class="btn btn-default btn-sm">Restore</button>
                      <button onclick="load_trash_inbox()" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                      <!-- /.pull-right -->
                    </div>
                    <div class="mailbox-messages">
                      <table id="tbl_trash" class="table table-hover table-striped table-email">
                        <thead class="hide">
                          <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                      </table><!-- /.table -->
                    </div><!-- /.mail-box-messages -->
                  </form>
                </div><!-- /.box-body -->
              </div>
              <!-- !BOX TRASH -->

              <!-- BOX CIRCULATIOn -->
              <div role="tabpanel" id="circulation" class="box box-solid tab-pane">
                <div class="box-header with-border">
                  <h3 class="box-title">From Circulation</h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input id="search_circulation" type="text" class="form-control input-sm" placeholder="Search Mail">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <form method="post" action="<?php echo base_url('mail/delete_circulation') ?>" id="form_delete_circulation">
                    <div class="mailbox-controls">
                      <!-- Check all button -->
                      <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                      <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button onclick="load_circulation_mail()" type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                      <!-- /.pull-right -->
                    </div>
                    <div class="mailbox-messages">
                      <table id="tbl_circulation" class="table table-hover table-striped table-email">
                        <thead class="hide">
                          <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                      </table><!-- /.table -->
                    </div><!-- /.mail-box-messages -->
                  </form>
                </div><!-- /.box-body -->
              </div>
              <!-- !BOX CIRCULATIOn -->

            </div>

            </div><!-- /.col -->
          </div><!-- /.row -->
        </section>

<script>
      var tbl_sent;
      var tbl_inbox;
      var tbl_trash;
      var tbl_circulation;

     $(document).ready(function(){
        load_inbox_mails();
        load_sent_mails();
        load_trash_inbox();
        load_circulation_mail();
        enableICheck();

        tbl_inbox = $("#tbl_inbox").dataTable({
          "oLanguage": {
                        "sSearch": "<i class='fa fa-search'></i> ",
                        "sEmptyTable": "No mail available",
                        "oPaginate": 
                                      {
                                          "sNext": '<i class="fa fa-angle-right"></i>',
                                          "sPrevious": '<i class="fa fa-angle-left"></i>',
                                          "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                          "sLast": '<i class="fa fa-angle-double-right"></i>'
                                      }
                    },
                    "bProcessing": true,
                    "bLengthChange": false,
                    "bSort": false,
                    "bFilter" : true,
                    "bInfo":false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    "aoColumns": [
                        null,
                        { "sClass": "mailbox-star", "bSearchable": false },
                        { "sClass": "mailbox-name" },
                        { "sClass": "mailbox-subject", "bSearchable": false },
                        { "sClass": "mailbox-attachment", "bSearchable": false },
                        { "sClass": "mailbox-date", "bSearchable": false }
                    ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                        "aButtons": [
                                    ]
                    }
        });

        tbl_sent = $("#tbl_sent").dataTable({
          "oLanguage": {
                        "sSearch": "<i class='fa fa-search'></i> ",
                        "sEmptyTable": "No mail available",
                        "oPaginate": 
                                      {
                                          "sNext": '<i class="fa fa-angle-right"></i>',
                                          "sPrevious": '<i class="fa fa-angle-left"></i>',
                                          "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                          "sLast": '<i class="fa fa-angle-double-right"></i>'
                                      }
                    },
                    "bLengthChange": false,
                    "bProcessing": true,
                    "bSort": false,
                    "bFilter" : true,
                    "bInfo":false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    "aoColumns": [
                        null,
                        { "sClass": "mailbox-star", "bSearchable": false },
                        { "sClass": "mailbox-name" },
                        { "sClass": "mailbox-subject", "bSearchable": false },
                        { "sClass": "mailbox-attachment", "bSearchable": false },
                        { "sClass": "mailbox-date", "bSearchable": false }
                    ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                        "aButtons": [
                                    ]
                    }
        });

        tbl_trash = $("#tbl_trash").dataTable({
          "oLanguage": {
                        "sSearch": "<i class='fa fa-search'></i> ",
                        "sEmptyTable": "No mail available",
                        "oPaginate": 
                                      {
                                          "sNext": '<i class="fa fa-angle-right"></i>',
                                          "sPrevious": '<i class="fa fa-angle-left"></i>',
                                          "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                          "sLast": '<i class="fa fa-angle-double-right"></i>'
                                      }
                    },
                    "bLengthChange": false,
                    "bProcessing": true,
                    "bSort": false,
                    "bFilter" : true,
                    "bInfo":false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    "aoColumns": [
                        null,
                        { "sClass": "mailbox-star", "bSearchable": false },
                        { "sClass": "mailbox-name" },
                        { "sClass": "mailbox-subject", "bSearchable": false },
                        { "sClass": "mailbox-attachment", "bSearchable": false },
                        { "sClass": "mailbox-date", "bSearchable": false }
                    ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                        "aButtons": [
                                    ]
                    }
        });

        tbl_circulation = $("#tbl_circulation").dataTable({
          "oLanguage": {
                        "sSearch": "<i class='fa fa-search'></i> ",
                        "sEmptyTable": "No mail available",
                        "oPaginate": 
                                      {
                                          "sNext": '<i class="fa fa-angle-right"></i>',
                                          "sPrevious": '<i class="fa fa-angle-left"></i>',
                                          "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                          "sLast": '<i class="fa fa-angle-double-right"></i>'
                                      }
                    },
                    "bLengthChange": false,
                    "bProcessing": true,
                    "bSort": false,
                    "bFilter" : true,
                    "bInfo":false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    "aoColumns": [
                        null,
                        { "sClass": "mailbox-star", "bSearchable": false },
                        { "sClass": "mailbox-name" },
                        { "sClass": "mailbox-subject", "bSearchable": false },
                        { "sClass": "mailbox-attachment", "bSearchable": false },
                        { "sClass": "mailbox-date", "bSearchable": false }
                    ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                        "aButtons": [
                                    ]
                    }
        });

        $("input#search_sent").on("keyup", function(){
          tbl_sent.fnFilter($(this).val());
        });

        $("input#search_inbox").on("keyup", function(){
          tbl_inbox.fnFilter($(this).val());
        });

        $("input#search_trash").on("keyup", function(){
          tbl_trash.fnFilter($(this).val());
        });

        $("input#search_circulation").on("keyup", function(){
          tbl_trash.fnFilter($(this).val());
        });


        $("#tbl_inbox").on('draw.dt', function () {
           enableICheck();
        });

        $("#tbl_sent").on('draw.dt', function () {
           enableICheck();
        });

        $("#tbl_trash").on('draw.dt', function () {
           enableICheck();
        });

        $("#tbl_circulation").on('draw.dt', function () {
           enableICheck();
        });
       
     });

     function enableICheck(){
      $('.mailbox-messages input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
          radioClass: 'iradio_flat-blue'
        });

        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
          var clicks = $(this).data('clicks');
          if (clicks) {
            //Uncheck all checkboxes
            $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
          } else {
            //Check all checkboxes
            $(".mailbox-messages input[type='checkbox']").iCheck("check");
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
          }
          $(this).data("clicks", !clicks);
        });

        //Handle starring for glyphicon and font awesome
        $(".mailbox-star").click(function (e) {
          e.preventDefault();
          //detect type
          var $this = $(this).find("a > i");
          var glyph = $this.hasClass("glyphicon");
          var fa = $this.hasClass("fa");

          //Switch states
          if (glyph) {
            $this.toggleClass("glyphicon-star");
            $this.toggleClass("glyphicon-star-empty");
          }

          if (fa) {
            $this.toggleClass("fa-star");
            $this.toggleClass("fa-star-o");
          }
        });
     }

     function load_sent_mails(){
        $.ajax({
          url : "<?php echo base_url('mail/load_sent_emails'); ?>",
          dataType: "json",
          success: function(data){

            tbl_sent.fnClearTable();
            // LOAD SENT
            $.each(data, function( key, value){
              var newRow = tbl_sent.fnAddData([
                                "<input class='checkboxmail_sent' name=\"checkbox_sent[]\" value='"+value['id']+"' type=\"checkbox\">",
                                "",
                                "<a href=\"read-mail.html\">"+value['to']+"</a>",
                                value['subject'],
                                value['attachment'],
                                value['date']
                                ]);

              var oSettings = tbl_sent.fnSettings();
              var nTr = oSettings.aoData[ newRow[0] ].nTr;
              $(nTr).attr("id", value['id']);
            });
          
          },
          error: function(){

          }
        });
     }

     function load_inbox_mails(){

        var c_unread = 0;

        $.ajax({
          url : "<?php echo base_url('mail/load_inbox'); ?>",
          dataType: "json",
          success: function(data){

            tbl_inbox.fnClearTable();
            // LOAD SENT
            $.each(data, function( key, value){
              var newRow = tbl_inbox.fnAddData([
                                "<input name=\"inbox_id[]\" value='"+value['id']+"' type=\"checkbox\" class='checkboxmail_inbox'>",
                                value['star'],
                                value['from'],
                                value['subject'],
                                value['attachment'],
                                value['date']
                                ]);

              var oSettings = tbl_inbox.fnSettings();
              var nTr = oSettings.aoData[ newRow[0] ].nTr;
              $(nTr).attr("id", value['id']);
              $(nTr).addClass("read_status_"+value['read_status']);

              if(value['read_status'] == "no"){
                c_unread++;
              }
            });

            $("span#inbox_counter").html(c_unread);
            if(c_unread == 0){
              $("span#inbox_counter").html("");
            }

          },
          error: function(){

          }
        });
     }

     function update_star(sent_id){
        $.ajax({
          url: "<?php echo base_url('mail/make_important') ?>",
          data: {star : sent_id},
          type: "GET",
          dataType: "html",
          success: function(data){

          },
          error: function(){

          }
        });
        
      }

$(document).ready(function(){

    $("form#form_delete_inbox").on("submit", function(e){

      e.preventDefault();
      if($('input:checkbox:checked.checkboxmail_inbox').length > 0){
        
        $.ajax({
              url: "<?php echo base_url('mail/delete_inbox') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                  load_inbox_mails();
                  load_trash_inbox();
                  new PNotify({
                            title: 'Success',
                            text: data['msg'],
                            type: 'success',
                            icon: 'fa fa-check'
                    });
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

      }
    });

    // DELETE CIRCULATION
    $("form#form_delete_circulation").on("submit", function(e){
      e.preventDefault();
      if($('input:checkbox:checked.checkboxmail_circulation').length > 0){

        $.ajax({
              url: "<?php echo base_url('mail/delete_circulation') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                  load_circulation_mail();
                  load_trash_inbox();
                  new PNotify({
                            title: 'Success',
                            text: data['msg'],
                            type: 'success',
                            icon: 'fa fa-check'
                    });
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
      }
    });

    // DELETE SENT
    $("form#form_delete_sent").on("submit", function(e){
      e.preventDefault();
      if($('input:checkbox:checked.checkboxmail_sent').length > 0){

        $.ajax({
              url: "<?php echo base_url('mail/delete_sent') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              {
                  load_sent_mails();
                  load_trash_inbox();
                  new PNotify({
                            title: 'Success',
                            text: data['msg'],
                            type: 'success',
                            icon: 'fa fa-check'
                    });
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
      }
    });

    $("form#form_restore_inbox").on("submit", function(e){
      e.preventDefault();

      if($('input:checkbox:checked.checkboxmail_trash').length > 0){
        $.ajax({
              url: "<?php echo base_url('mail/restore_mail') ?>",
              type: "POST",            
              data: new FormData(this),
              dataType: "json",
              contentType: false,      
              cache: false,            
              processData:false,       
              success: function(data)  
              { 

                  load_trash_inbox();
                  load_inbox_mails();
                  load_circulation_mail();
                  load_sent_mails();
                  
                  new PNotify({
                            title: 'Success',
                            text: data['msg'],
                            type: 'success',
                            icon: 'fa fa-check'
                    });
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

      }
      else{

      }
      
    });

});

function deselect_active(){
  $(".menu_email .box-body ul.nav.nav-pills.nav-stacked li").removeClass("active");
}

function form_reset(){
  var $select = $('#select-to').selectize();
  var control = $select[0].selectize;
  control.clear();
  $("button[type=reset]").trigger("click");
}

$(document).ready(function(){
    $("form#form_send_mail").on("submit", function(e){
      e.preventDefault();
      $.ajax({
            url: "<?php echo base_url('mail/insert_my_mail') ?>",
            type: "POST",            
            data: new FormData(this),
            dataType: "json",
            contentType: false,      
            cache: false,            
            processData:false,       
            success: function(data)  
            {
              if(data['result'] == 1){ 

               load_sent_mails();
               form_reset();

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

function load_trash_inbox(){
        $.ajax({
          url : "<?php echo base_url('mail/trashEmails'); ?>",
          dataType: "json",
          success: function(data){
            tbl_trash.fnClearTable();
            // LOAD SENT
            $.each(data, function( key, value){
              tbl_trash.fnAddData([
                                "<input name=\"trash_id[]\" value='"+value['id']+"' type=\"checkbox\" class='checkboxmail_trash'>",
                                "",
                                value['from'],
                                value['subject'],
                                value['attachment'],
                                value['date']
                                ]);
            });

          },
          error: function(){

          }
        });
     }

function load_circulation_mail(){

        var c_unread = 0;

        $.ajax({
          url : "<?php echo base_url('mail/load_circulation'); ?>",
          dataType: "json",
          success: function(data){

            tbl_circulation.fnClearTable();
            // LOAD SENT
            $.each(data, function( key, value){
              var newRow = tbl_circulation.fnAddData([
                                "<input name=\"cir_id[]\" value='"+value['id']+"' type=\"checkbox\" class='checkboxmail_circulation'>",
                                value['star'],
                                value['from'],
                                value['subject'],
                                value['attachment'],
                                value['date']
                                ]);

              var oSettings = tbl_circulation.fnSettings();
              var nTr = oSettings.aoData[ newRow[0] ].nTr;
              $(nTr).attr("id", value['id']);
              $(nTr).addClass("read_status_"+value['read_status']);

              if(value['read_status'] == "no"){
                c_unread++;
              }
            });

            $("span#cir_counter").html(c_unread);
            if(c_unread == 0){
              $("span#cir_counter").html("");
            }

          },
          error: function(){

          }
        });
     }


</script>

  