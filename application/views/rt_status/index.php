<style type="text/css">
  .pagination{
    margin-top:0px!important;
    margin-bottom:0px!important;
    margin-left:0px!important;
  }
</style>
<section class="content">
	
  <div class="row">
    <div class="col-lg-12">
      <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Without feedback</a></li>
          <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">With feedback</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content" style="padding:10px;background:#FFF">
          <div role="tabpanel" class="tab-pane active" id="home">

            <table id="tbl_no_feedback" class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Ref No.</th>
                  <th>Type</th>
                  <th>Process</th>
                  <th>Signatory</th>
                  <th class="col-md-1">Status</th>
                  <th class="col-md-1 text-right">Elapsed</th>
                </tr>
              </thead>
              
            </table>

          </div>

          <div role="tabpanel" class="tab-pane" id="profile">
            <table id="tbl_with_feedback" class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Ref No.</th>
                  <th>Type</th>
                  <th>Process</th>
                  <th>Signatory</th>
                  <th class="col-md-1">Status</th>
                  <th class="col-md-1 text-right">Elapsed</th>
                  <th></th>
                </tr>
              </thead>
              
            </table>
          </div>

        </div>

      </div>
    </div> 
  </div>
</section>

<!-- modal feedback -->
<div id="modal_feedback" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="signatory_info clearfix">
          <img id="sig_img" src="" class="img pull-left" style="width:70px;height:70px;margin-right: 15px">
          <div class="pull-left">
            <h4>Gian Anduyan</h4>
            <small style="font-size:14px">Programmer</small>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <p style="text-align:justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal_req_details" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="signatory_info clearfix">
          <img id="sig_img_details" src="" class="img pull-left" style="width:70px;height:70px;margin-right: 15px">
          <div class="pull-left">
            <h4>Gian Anduyan</h4>
            <small style="font-size:14px">Programmer</small>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <h2 style="font-weight:bold;margin-top:0px!important"></h2>
        <div class="row">
          <div class="col-sm-8" >
            <p style="text-align:justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <h5 style="font-weight:bold;margin-bottom:2px">Attachments:</h5>
            <div id="attachments">
              <a href="#">requestdocument.doc</a>
            </div>
          </div>
          <div class="col-sm-4" id="process">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
  var tbl_no_feedback = $('#tbl_no_feedback').dataTable({
                    "oLanguage": {
                        "sSearch": "<i class='fa fa-search'></i> ",
                        "oPaginate": 
                                      {
                                          "sNext": '<i class="fa fa-angle-right"></i>',
                                          "sPrevious": '<i class="fa fa-angle-left"></i>',
                                          "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                          "sLast": '<i class="fa fa-angle-double-right"></i>'
                                      }
                    },
                    "bLengthChange": false,
                    "bSort": true,
                    "bInfo": false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    "aoColumns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    { "sClass": "text-right" }
                ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                        "aButtons": []
                    }});
  var tbl_with_feedback = $('#tbl_with_feedback').dataTable({
                    "oLanguage": {
                        "sSearch": "<i class='fa fa-search'></i> ",
                        "oPaginate": 
                                      {
                                          "sNext": '<i class="fa fa-angle-right"></i>',
                                          "sPrevious": '<i class="fa fa-angle-left"></i>',
                                          "sFirst": '<i class="fa fa-angle-double-left"></i>',
                                          "sLast": '<i class="fa fa-angle-double-right"></i>'
                                      }
                    },
                    "bLengthChange": false,
                    "bSort": true,
                    "bInfo": false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    "aoColumns": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    { "sClass": "text-right" },
                    null
                ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                        "aButtons": []
                    }});

  load_without_feeback();
  load_with_feeback();

  function load_without_feeback(){
    $.ajax({
      url: "<?php echo base_url('rt_status_overdue/loadWithoutFeedback') ?>",
      dataType: "json",
      success: function(data){
        tbl_no_feedback.fnClearTable();
        var c = 1;
        $.each(data, function (key, value){

          var color="";
          if(value['rtp_status'] == 'overdue'){
              color = "label-default";
          }

          tbl_no_feedback.fnAddData([
                                    c,
                                    "<a onclick=\"get_request_details("+value['rt_id']+")\" style='cursor:pointer'><b>"+value['rt_ref_no']+"</b></a>",
                                    value['procedure'],
                                    value['rtp_step_detail'],
                                    value['firstname']+" "+value['lastname'],
                                    "<span class='label "+color+"'>"+value['rtp_status']+"</span>" ,
                                    value['warn_notify']
                                    ]);
          c++;
        });
      },
      error: function(){

      }
    });
  }

  function load_with_feeback(){
    $.ajax({
      url: "<?php echo base_url('rt_status_overdue/loadWithFeedback') ?>",
      dataType: "json",
      success: function(data){
        tbl_with_feedback.fnClearTable();
        var c = 1;
        $.each(data, function (key, value){

          var color="";
          if(value['rtp_status'] == 'done'){
              color = "label-success";
          }
          else if(value['rtp_status'] == 'denied'){
            color = "label-danger";
          }
          tbl_with_feedback.fnAddData([
                                    c,
                                    "<a onclick=\"get_request_details("+value['rt_id']+")\" style='cursor:pointer'><b>"+value['rt_ref_no']+"</b></a>",
                                    value['procedure'],
                                    value['rtp_step_detail'],
                                    value['firstname']+" "+value['lastname'],
                                    "<span class='label "+color+"'>"+value['rtp_status']+"</span>" ,
                                    value['warn_notify'],
                                    "<button onclick=\"showFeedback("+value['rtp_id']+")\" class='btn btn-primary btn-xs pull-right'>Show feedback</button>"
                                    ]);
          c++;
        });
      },
      error: function(){

      }
    });
  }

  function showFeedback(rtp_id){
    $.ajax({
      url: "<?php echo base_url('rt_status_overdue/getFeedback') ?>",
      data: {rtp_id : rtp_id},
      type: "GET",
      dataType : "json",
      success: function(data){
        $.each( data, function(key, value){
          $("#modal_feedback .signatory_info img").attr("src", value['img_path']);
          $("#modal_feedback .signatory_info h4").html(value['firstname']+" "+value['lastname']);
          $("#modal_feedback .signatory_info small").html(value['position']);
          $("#modal_feedback .modal-body p").html(value['overdue_reason']);
        });
        $("#modal_feedback").modal("show");
      },
      error: function(){

      }
    });

    // $("#modal_feedback").modal("show");
  }

  $(document).ready(function(){
    $("img#sig_img").on("error", function(){
      imgErrorChat(this);
    });

     $("img#sig_img_details").on("error", function(){
      imgErrorChat(this);
    });
  });
  
  function get_request_details(rt_id){
    $.ajax({
      url: "<?php echo base_url('rt_status_overdue/get_request_details') ?>",
      data: {rt_id : rt_id},
      type: "GET",
      dataType: "json",
      success: function(data){
        console.log(data);
        $.each(data['request'], function(key, value){
          $("#modal_req_details .signatory_info img").attr("src", value['img_path']);
          $("#modal_req_details .signatory_info h4").html(value['firstname']+" "+value['lastname']);
          $("#modal_req_details .signatory_info small").html(value['position']);
          $("#modal_req_details .modal-body p").html(value['rt_content']);
          $("#modal_req_details .modal-body h2").html(value['rt_title']);
        });

        $("#modal_req_details .modal-body #process").html(data['process']);
        $("#modal_req_details .modal-body p table").css({"width":"100%"});
        $("#modal_req_details .modal-body p table td").css({"border":"1px solid #333"});

        if(data['attachment'].length > 0){
          $("#modal_req_details .modal-body div#attachments").html("");
          $.each(data['attachment'], function(key, value){
            $("#modal_req_details .modal-body div#attachments").append(value['rta_attachment']);
          });
        }else{
          $("#modal_req_details .modal-body div#attachments").html("No attachment available");
        }
        
        
        $("#modal_req_details").modal('show');
      },
      error: function(){

      }
    });
  }
</script>
