<style type="text/css">
  div.toggle.btn {
    width: 120px!important;
  }
</style>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid">
				<div class="box-body">
					<table id="tbl_event_list" class="table table-hover table-bordered">
						<thead>
							<tr>
								<th class="col-md-1">#</th>
								<th>Title</th>
                <th>Department</th>
								<th>Date</th>
								<th>Time</th>
								<th class="col-md-1">Status</th>
								<th class="col-md-1 text-right">Action</th>
							</tr>
						</thead>
						
					</table>
				</div>
			</div>
		</div>
	</div>
</section>


<!-- MODAL CONFIRM DELETE EVENT -->
<div class="modal fade" id="change_remarks_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <form method="post" id="form_submit_remarks" action="<?php echo base_url('calendar_notification/change_remarks'); ?>">
      <div class="modal-body">
        <input id="event_id" type="hidden" name="event_id">
        <div class="form-group">
          <input id="toggle-event" type="checkbox" data-size="mini" checked data-toggle="toggle" data-on="<i class='fa fa-check'></i>  Approve" data-off="<i class='fa fa-times'></i> Disapprove" data-onstyle="success" data-offstyle="danger">
        </div>
        <div class="form-group" id="message">
          Are you sure you want to approve this event?
        </div>
        <div class="form-group">
          <textarea placeholder="Remarks here ..." id="remarks" rows="5" class="form-control" name="remarks"></textarea>
        </div>
        <!-- <div class="form-group">
          <input type="radio" checked style="width:16px;height:16px" name="status" value="disapproved">Disapprove&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" style="width:16px;height:16px" name="status" value="approved">Approve
        </div> -->
        
      </div>
      <div class="modal-footer">
        <button class="hide" type="reset" id="btn-reset">Reset</button>
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button type="submit" class="btn btn-primary flat"><i class="fa fa-check"></i> Submit</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

      // var txtdescription = $("textarea#remarks").wysihtml5();

			var oTable;
            $(document).ready(function () {

                oTable = $('#tbl_event_list').dataTable({
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
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    "dom": 'T<"clear">lfrtip',
                    "tableTools": {
                        "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
                        "aButtons": [
                                        {
                                            "sExtends": "copy",
                                            "sButtonText": "<i class='fa fa-copy'></i> Copy"
                                        },
                                        {
                                            "sExtends": "csv",
                                            "sButtonText": "<i class='fa fa-file-excel-o'></i> Save to CSV"
                                        },
                                        {
                                            "sExtends": "xls",
                                            "sButtonText": "<i class='fa fa-file-excel-o'></i> Generate Excel"
                                        },           
                                        {
                                          "sExtends": "print",
                                          "sButtonText": "<i class='fa fa-print'></i> Print",
                                          "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Event List</small></a></div></div>",
                                          "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                                <p>Please use your browser's print function to\
                                                print this table.\
                                                <br />Press <b>escape</b> when finished.</p>",
                                        }
                                    ]
                    }
                });
                
            });

            load_events();

            function load_events(){
            	$.ajax({
			        url: "<?php echo base_url('calendar_notification/load_events_table') ?>",
			        data: {Status : 'approved'},
			        type: "GET",
			        dataType: "json",
			        success: function(data){
			          
			          oTable.fnClearTable();

			          for(var i = 0;i<data.length;i++){

			          	var color = "";
			          	var action = "";
	                    
	                    if(data[i]['status'] == 'pending'){
	                        color = "label-warning";
	                        action = "<span class='pull-right'><a onclick=\"view_event('"+data[i]['event_id']+"')\" title='View' href='#'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;<a onclick=\"approve_disapprove('"+data[i]['event_id']+"')\" title='Approve or Disapprove' href='#'><i class='fa fa-edit'></i></a></span>";
	                    }
	                   
	                    
			              oTable.fnAddData([
			                                  i+1,
			                                  data[i]['event_title'],
                                        data[i]['department'],
			                                  data[i]['date'],
			                                  data[i]['time'],
			                                  "<span class='label "+color+"'>"+data[i]['status']+"</span>",
			                                  action
			                                ]); 
			          }
			         
			        },
			        error: function(){

			        }
			      });
            }

    function view_event(event_id){
      location.href="<?php echo base_url('calendar_notification/view_event').'/'?>"+Base64.encode(event_id);
    }

    function approve_disapprove(event_id){
      $("#change_remarks_modal input#event_id").val(event_id);
      $("#change_remarks_modal").modal('show');
    }

    $("input[type=radio][name=status]").click(function(){
      var stat = $(this).val();
      if(stat == 'disapproved'){
        $("textarea#remarks").prop("required", true);
      }
      else if(stat == "approved"){
        $("textarea#remarks").prop("required", false);
      }
    });


  var stat_for_app;

  $(function() {
    if($('#toggle-event').prop('checked') == true){
          stat_for_app = "approved";
          // $("#remarks").addClass("hide");
          $("#remarks").fadeOut();
      }
      else{
        stat_for_app = "disapproved";
        // $("#remarks").removeClass("hide");
        $("#remarks").fadeIn();
      }

      $('#toggle-event').change(function() {
        // alert('Toggle: ' + $(this).prop('checked'))
        if($(this).prop('checked') == true){
          stat_for_app = "approved";
          // $("#remarks").addClass("hide");
          $("#remarks").fadeOut();
          $("#message").html("Are you sure you want to approve this event?");
        }
        else{
          stat_for_app = "disapproved";
          // $("#remarks").removeClass("hide");
          $("#remarks").fadeIn();
          $("#remarks").prop("required", true);
          $("#message").html("Are you sure you want to disapprove this event?");
        }
      });
  });

    $(document).ready(function(){
      $("#form_submit_remarks").on("submit", function(e){

        e.preventDefault();
        $.ajax({
          url: "<?php echo base_url('calendar_notification/change_remarks') ?>",
          data: $(this).serialize()+"&status="+stat_for_app,
          type: "POST",
          dataType: "json",
          success: function(data){
            if(data['result'] == 1){ 

                    $("#btn-reset").trigger("click");
                    $("#change_remarks_modal").modal('hide');
                    load_events();

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
          error: function(){

          }
        });
      });
    });
</script>