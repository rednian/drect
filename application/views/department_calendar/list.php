<script type="text/javascript">
	$(".content-header").append("<a href='<?php echo base_url("department_calendar") ?>' class=\"form-group pull-right\">\
										<button class=\"btn btn-primary flat\"><i class=\"fa fa-arrow-left\"></i> Back to calendar</button>\
								</a>");
</script>
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
<div class="modal fade" id="confirm_delete_event_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want delete event?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button onclick="delete_event()" id="modal-btn-delete-event" type="button" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<!-- MODAL REMAKRS -->
<div class="modal fade" id="show_remarks_modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class='fa fa-comment-o'></i> Remarks from <span>Allan Atega</span></h4>
      </div>
      <div class="modal-body clearfix">
      	<span></span>
        <p style="text-align:justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>


<script type="text/javascript">
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
			        url: "<?php echo base_url('department_calendar/load_events_table') ?>",
			        data: {Status : 'approved'},
			        type: "GET",
			        dataType: "json",
			        success: function(data){
			          
			          oTable.fnClearTable();

			          for(var i = 0;i<data.length;i++){

			          	var color = "";
			          	var action = "";
	                    if(data[i]['status'] == 'disapproved'){
	                        color = "label-danger";
	                        action = "<span class='pull-right'><a onclick=\"view_event('"+data[i]['event_id']+"')\" title='View' href='#'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;<a onclick=\"show_remarks("+data[i]['event_id']+")\" title='Show remarks' href='#'><i class='fa fa-comment-o'></i></a>&nbsp;&nbsp;&nbsp;<a onclick=\"confirm_delete_event("+data[i]['event_id']+")\" title='Delete' href='#'><i class='fa fa-times'></i></a></span>";
	                    }
	                    else if(data[i]['status'] == 'pending'){
	                        color = "label-warning";
	                        action = "<span class='pull-right'><a onclick=\"view_event('"+data[i]['event_id']+"')\" title='View' href='#'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;<a onclick=\"edit_event('"+data[i]['event_id']+"')\" title='Edit' href='#'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;&nbsp;<a onclick=\"confirm_delete_event("+data[i]['event_id']+")\" href='#' title='Delete'><i class='fa fa-times'></i></a></span>";
	                    }
	                    else if(data[i]['status'] == 'approved'){
	                        color = "label-success";
	                        action = "<span class='pull-right'><a onclick=\"view_event('"+data[i]['event_id']+"')\" title='View' href='#'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;<a onclick=\"confirm_delete_event("+data[i]['event_id']+")\" title='Delete' href='#'><i class='fa fa-times'></i></a></span>";
	                    }
	                    
			              oTable.fnAddData([
			                                  i+1,
			                                  data[i]['event_title'],
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

            var del_event_id;

    function confirm_delete_event(event_id){
      del_event_id = event_id;
      $("#confirm_delete_event_modal").modal('show');
    }

    function delete_event(){
      $.ajax({
        url: "<?php echo base_url('department_calendar/delete_event') ?>",
        data: {event_id : del_event_id},
        type: "GET",
        dataType: "json",
        success: function(data){
            if(data['result'] == 1){ 
            
              $("#confirm_delete_event_modal").modal('hide');
              
              new PNotify({
                      title: 'Success',
                      text: data['msg'],
                      type: 'success',
                      icon: 'fa fa-check'
              });
            }
            else if(data['result'] == 0){
                $("#confirm_delete_event_modal").modal('hide');
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
    }

    function view_event(event_id){
      location.href="<?php echo base_url('department_calendar/view_event').'/'?>"+Base64.encode(event_id);
    }

    function edit_event(event_id){
      location.href="<?php echo base_url('department_calendar/edit_event').'/'?>"+Base64.encode(event_id);
    }

    function show_remarks(event_id){
    	
    	$.ajax({
    		url: "<?php echo base_url('department_calendar/show_remarks') ?>",
    		data: {event_id : event_id},
    		type: "GET",
    		dataType: "json",
    		success: function(data){
    			$("#show_remarks_modal .modal-title span").html(data['from']);
    			$("#show_remarks_modal .modal-body p").html(data['remarks']);
    			$("#show_remarks_modal .modal-body span").html("<img onerror=\"this.onerror=null;this.src='<?php echo base_url("assets/dist/img/default.png")?>';\" src='<?php echo base_url('images/profile_image') ?>/"+data['img_path']+"' style='width:100px;height:100px;margin-right:10px;margin-bottom:10px' class=\"img pull-left\">");

    			$("#show_remarks_modal").modal('show');
    		},
    		error: function(){

    		}
    	});
    }

</script>