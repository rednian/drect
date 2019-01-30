<section class="content">
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-solid">
				<div class="box-body">
					<table id="table-list-approval" class="table table-hover table-bordered">
			             <thead>
			                <tr class="headings">
			                    <th>#</th>
			                    <th class="col-md-2">Title </th>
			                    <th>Version </th>
			                    <th>Date Modified </th>
			                    <th>Status </th>
			              		<th>Description</th>
			              		<th>Type</th>
			                    <th>Action</th>
			                </tr>
			            </thead>
			            
			          </table>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- MODAL CONFIRMATION -->

<div class="modal fade" id="confirmation_action" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to proceed?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default flat" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
        <button onclick="submit_action()" id="modal-btn-delete-event" type="button" class="btn btn-primary flat"><i class="fa fa-check"></i> Yes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
	var listapprovalTable = $('#table-list-approval').dataTable({
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

	load_list_approval('pending');

	function load_list_approval(stat){
		$.ajax({
			url: "<?php echo base_url('pwi_notification/load_list_for_approval') ?>",
			data: {status : stat},
			dataType: "json",
			type: "GET",
			success: function(data){
				  listapprovalTable.fnClearTable();

			      for(var i = 0; i < data.length; i++) { 

			      	var color = "";
			      	if(data[i]['status'] == 'disapproved'){
			      		color = "label-danger";
			      	}
			      	else if(data[i]['status'] == 'pending'){
			      		color = "label-warning";
			      	}

			        listapprovalTable.fnAddData([ 
										                i+1,
			                              "<b>"+data[i]['title']+"</b>", 
			                              data[i]['version'], 
			                              data[i]['date_created'],
			                              "<span class='label "+color+"'>"+data[i]['status']+"</span>",
                                    		data[i]['desc'],
                                    		data[i]['type'],
			                              data[i]['action']
			                              ]); 

			      } // End For
			},
			error: function(){

			}
		});
	}

	function approve_new(procedure_id){
		$.ajax({
			url: "<?php echo base_url('pwi_notification/approve_new') ?>",
			data: {procedure_id : procedure_id},
			type: "GET",
			dataType: "json",
			success: function(data){
				if(data['result'] == 1){ 
				  load_list_approval('pending');
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
	}

	function approve_edit(edit_id){

		$.ajax({
			url: "<?php echo base_url('pwi_notification/approve_edit') ?>",
			data: {edit_id : edit_id},
			type: "GET",
			dataType: "json",
			success: function(data){
				if(data['result'] == 1){ 
				  load_list_approval('pending');
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
	}

	function approve_delete(delete_id){
		$.ajax({
			url: "<?php echo base_url('pwi_notification/approve_delete') ?>",
			data: {delete_id : delete_id},
			type: "GET",
			dataType: "json",
			success: function(data){
				if(data['result'] == 1){ 
         		  load_list_approval('pending');
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
	}

	var action_type = "";
	var proc_id = "";

	function confirm_action(id, type){
		$("#confirmation_action").modal("show");
		action_type = type;
		proc_id = id;
	}

	function submit_action(){
		if(action_type == 'new'){
			approve_new(proc_id);
		}
		if(action_type == 'edit'){
			approve_edit(proc_id);
		}
		if(action_type == 'delete'){
			approve_delete(proc_id);
		}
		$("#confirmation_action").modal("hide");
	}

	function disapproveProcedure(type, id){
		bootbox.prompt({
		    title: "Are you sure you want to disapprove? Please give us your remarks.",
		    inputType: 'textarea',
		    inputOptions: {required: true},
		    callback: function (result) {
		       
		       if(result !== null){
	       		  if(result.trim() == ""){
				       	new PNotify({
	                          title: 'Information',
	                          text: 'Remarks is required.',
	                          type: 'error',
	                          icon: 'fa fa-times'
	                    });
			       }
			       else{
			       		$.ajax({
			       			url: "<?php echo base_url('pwi_notification/disapproveProcedure') ?>",
			       			data: {type: type, procedure_id: id, remarks : result},
			       			type: "POST",
			       			dataType: "JSON",
			       			success: function(data){
			       				if(data.result == true){
			       					load_list_approval('pending');
			       					new PNotify({
				                          title: 'Success',
				                          text: 'Procedure has been disapproved.',
				                          type: 'success',
				                          icon: 'fa fa-check'
				                    });
			       				}
			       				else{
			       					new PNotify({
				                          title: 'Error',
				                          text: 'Unable to disapprove procedure.',
				                          type: 'error',
				                          icon: 'fa fa-times'
				                    });
			       				}
			       			},
			       			error: function(){

			       			}
			       		});
			       }
		       }
		    }
		});
	}
</script>