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
      <table id="tbl_pending" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Ref No.</th>
            <th class="col-md-2">Type</th>
            <th class="col-md-4">Requestor</th>
            <th>Requested date</th>
            <th class="col-md-1">Status</th>
            <th class="col-md-1 text-right">Elapsed</th>
          </tr>
        </thead>
      </table>
    </div> 
  </div>
</section>

<!-- modal feedback -->
<div id="modal_feedback" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
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
        <h2 style="font-weight:bold;margin-top:0px!important"></h2>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

  var tbl_pending = $('#tbl_pending').dataTable({
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
  
  load_pending_requests();
  function load_pending_requests(){
    $.ajax({
      url: "<?php echo base_url('rt_status_pending/load_pending_requests') ?>",
      dataType: "json",
      success: function(data){
        tbl_pending.fnClearTable();
        var c=1;
        $.each(data, function(key, value){
          tbl_pending.fnAddData([
                               c,
                               "<a onclick=\"get_request_details("+value['rt_id']+")\" style='cursor:pointer'><b>"+value['rt_ref_no']+"</b></a>",
                               value['procedure'],
                               value['firstname']+" "+value['lastname']+" <small>("+value['position']+")</small>",
                               value['rt_date_time'],
                               "<span class='label label-warning'>"+value['rt_status']+"</span>",
                               value['on_notify']
                                ]);
          c++;
        });
      },
      error: function(){

      }
    }); 
  }

  function get_request_details(rt_id){
    $.ajax({
      url: "<?php echo base_url('rt_status_pending/get_request_details') ?>",
      data: {rt_id : rt_id},
      type: "GET",
      dataType: "json",
      success: function(data){
       
        $.each(data['request'], function(key, value){
          $("#modal_feedback .signatory_info img").attr("src", value['img_path']);
          $("#modal_feedback .signatory_info h4").html(value['firstname']+" "+value['lastname']);
          $("#modal_feedback .signatory_info small").html(value['position']);
          $("#modal_feedback .modal-body p").html(value['rt_content']);
          $("#modal_feedback .modal-body h2").html(value['rt_title']);
        });

        if(data['attachment'].length > 0){
          $("#modal_feedback .modal-body div#attachments").html("");
          $.each(data['attachment'], function(key, value){
            $("#modal_feedback .modal-body div#attachments").append(value['rta_attachment']);
          });
        }else{
          $("#modal_feedback .modal-body div#attachments").html("No attachment available");
        }
        
         

        $("#modal_feedback").modal('show');
        $("#modal_feedback table").css({"width":"100%"});
        $("#modal_feedback table td").css({"border":"1px solid #333"});
      },
      error: function(){

      }
    });
  }

  $(document).ready(function(){
    $("img#sig_img").on("error", function(){
      imgErrorChat(this);
    });
  });
</script>
