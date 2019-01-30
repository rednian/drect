<section class="content">
	
	<div class="row">

		<div class="col-md-12">
      <table id="table-request" class="table table-hover table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Reference No.</th>
              <th>Name</th>
              <th>Request</th>
              <th>Request Type</th>
              <th>Date Requested</th>
              <th>Status</th>
            </tr>
          </thead>
        </table>
<!-- 			
			<div>
			  <ul class="nav nav-tabs" role="tablist">
			    <li id="tab_request" role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Requests <span style="background:rgb(99, 169, 210)" class="badge">0</span></a></a></li>
			    <li id="tab_task" role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Tasks <span style="background:rgb(99, 169, 210)" class="badge">0</span></a></a></li>
			  </ul>
			  <div class="tab-content" style="padding:10px;background:#FFF">
			    <div role="tabpanel" class="tab-pane active" id="home">
			    	
			    </div>
			    <div role="tabpanel" class="tab-pane" id="profile">
			    	<table id="table-task" class="table table-hover table-bordered">
			    		<thead>
			    			<tr>
			    				<th>#</th>
			    				<th>Reference No.</th>
			    				<th>Name</th>
			    				<th>Task</th>
			    				<th>Task Type</th>
			    				<th>Date Requested</th>
			    				<th>Status</th>
			    			</tr>
			    		</thead>
			    	</table>
			    </div>
			  </div>

			</div> -->

		</div>

	</div>
</section>

<script type="text/javascript">
var tbl_request;
var tbl_task;

$(function(){
	load_requests();
	load_task();
});

	$(document).ready(function () {

         tbl_request = $('#table-request').dataTable({
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
            "dom": 'lfrtip',
            // "tableTools": {
            //     "sSwfPath": "<?php echo base_url('assets/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf'); ?>",
            //     "aButtons": [
            //                     {
            //                         "sExtends": "copy",
            //                         "sButtonText": "<i class='fa fa-copy'></i> Copy"
            //                     },
            //                     {
            //                         "sExtends": "csv",
            //                         "sButtonText": "<i class='fa fa-file-excel-o'></i> Save to CSV"
            //                     },
            //                     {
            //                         "sExtends": "xls",
            //                         "sButtonText": "<i class='fa fa-file-excel-o'></i> Generate Excel"
            //                     },           
            //                     // {
            //                     //   "sExtends": "print",
            //                     //   "sButtonText": "<i class='fa fa-print'></i> Print",
            //                     //   "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Procedure List for approval</small></a></div></div>",
            //                     //   "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
            //                     //         <p>Please use your browser's print function to\
            //                     //         print this table.\
            //                     //         <br />Press <b>escape</b> when finished.</p>",
            //                     // }
            //                 ]
            // }
        });

		 tbl_task = $('#table-task').dataTable({
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
                                  "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Procedure List for approval</small></a></div></div>",
                                  "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                        <p>Please use your browser's print function to\
                                        print this table.\
                                        <br />Press <b>escape</b> when finished.</p>",
                                }
                            ]
            }
        });
    });

	function load_requests(){
        $.ajax({
            url: "<?php echo base_url('request_task_approval/load_list_request') ?>",
            dataType: "JSON",
            success: function(data){

                tbl_request.fnClearTable();

                  for(var i = 0; i < data[0].length; i++) { 

                    var color = "";
                    if(data[0][i]['status'] == 'denied'){
                        color = "label-danger";
                    }
                    else if(data[0][i]['status'] == 'pending'){
                        color = "label-warning";
                    }
                    else if(data[0][i]['status'] == 'cancelled'){
                        color = "label-default";
                    }
                    else if(data[0][i]['status'] == 'endorsed'){
                        color = "label-success";
                    }

                    tbl_request.fnAddData([ 
                                          i+1,
                                          "<a data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Click to view\" href=\"<?php echo base_url('task/endorsement/"+data[0][i]["rt_id"]+"') ?>\"><b>"+data[0][i]['ref_no']+"</b></a>", 
                                          data[0][i]['requestor'], 
                                          data[0][i]['title'], 
                                          data[0][i]['request_type'],
                                          data[0][i]['date'],
                                          "<span class='label "+color+"'>"+data[0][i]['status']+"</span>" 
                                          ]); 

                    $("#tab_request span.badge").html(i+1);

                    if(data[1] > 0){
                      $("span#rtmCountPending").html("<span class='label label-danger'>"+data[1]+"</span>");
                    }
                  } // End For
                  
            },
            error: function(){

            }
        });
    }

    function load_task(){
        $.ajax({
            url: "<?php echo base_url('request_task_approval/load_list_task') ?>",
            dataType: "JSON",
            success: function(data){

                tbl_task.fnClearTable();

                  for(var i = 0; i < data.length; i++) { 

                    var color = "";
                    if(data[i]['status'] == 'denied'){
                        color = "label-danger";
                    }
                    else if(data[i]['status'] == 'pending'){
                        color = "label-warning";
                    }
                    else if(data[i]['status'] == 'cancelled'){
                        color = "label-default";
                    }
                    else if(data[i]['status'] == 'endorsed'){
                        color = "label-success";
                    }

                    tbl_task.fnAddData([ 
                                          i+1,
                                          "<a data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Click to view\" href=\"<?php echo base_url('request_task_approval/view/"+data[i]["rt_id"]+"') ?>\"><b>"+data[i]['ref_no']+"</b></a>", 
                                          data[i]['requestor'], 
                                          data[i]['title'], 
                                          data[i]['request_type'],
                                          data[i]['date'],
                                          "<span class='label "+color+"'>"+data[i]['status']+"</span>" 
                                          ]); 

                    $("#tab_task span.badge").html(i+1);
                  } // End For
                  
            },
            error: function(){

            }
        });
    }
    
</script>