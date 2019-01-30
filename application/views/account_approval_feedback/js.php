<script type="text/javascript">

	// FOR ACTIVATION //
	var tbl_activation = $('#table_activation').dataTable({
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
                    "aoColumns": [
								    { "sClass": "details-control" },
								   	null,
								   	null,
								   	null,
								   	null,
								   	null
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
                                          "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Account List For Activation</small></a></div></div>",
                                          "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                                <p>Please use your browser's print function to\
                                                print this table.\
                                                <br />Press <b>escape</b> when finished.</p>",
                                        }
                                    ]
                    }});
	
	load_accounts_for_activation();

	function load_accounts_for_activation(){

		

		$.ajax({
			url: "<?php echo base_url('account_approval_feedback/load_activation'); ?>",
			dataType: "json",
			success: function(data){

				tbl_activation.fnClearTable();

				for(var x=0;x<data.length;x++){
					
					var addedData = tbl_activation.fnAddData([
											'',
											data[x]['name'],
											data[x]['position'],
											data[x]['department'],
											data[x]['status'],
											data[x]['action']
											]);

					var theNode = $('#table_activation').dataTable().fnSettings().aoData[addedData[0]].nTr;
					theNode.setAttribute('id', data[x]['pi_id']);
				}
				
			},
			error: function(){

			}
		});}

	// Array to track the ids of the details displayed rows
    var detailRows = [];
 
    $('#table_activation tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
	    var row = $('#table_activation').DataTable().row(tr);

	    if (row.child.isShown()) {
	        // This row is already open - close it
	        row.child.hide();
	        tr.removeClass('shown');
	    }
	    else {
	    	tr.addClass('shown');
	    	var pi_id = $('#table_activation tbody tr.shown').attr("id");
	        format(row.child, pi_id, 'activate');  // create new if not exist
	    }} );
 	
    // On each draw, loop over the `detailRows` array and show any child rows
    tbl_activation.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );} );
    
// ----------------------------------------------------------------------------------------------------------------
	// FOR DEACTIVATION //

	var tbl_deactivation = $('#table_deactivation').dataTable({
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
                    "aoColumns": [
								    { "sClass": "details-control" },
								   	null,
								   	null,
								   	null,
								   	null,
								   	null
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
                                          "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Account List For Activation</small></a></div></div>",
                                          "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                                <p>Please use your browser's print function to\
                                                print this table.\
                                                <br />Press <b>escape</b> when finished.</p>",
                                        }
                                    ]
                    }});

	load_accounts_for_deactivation();

	function load_accounts_for_deactivation(){

		$.ajax({
			url: "<?php echo base_url('account_approval_feedback/load_deactivation'); ?>",
			dataType: "json",
			success: function(data){

				tbl_deactivation.fnClearTable();

				for(var x=0;x<data.length;x++){
					
					var addedData = tbl_deactivation.fnAddData([
											'',
											data[x]['name'],
											data[x]['position'],
											data[x]['department'],
											data[x]['status'],
											data[x]['action']
											]);

					var theNode = $('#table_deactivation').dataTable().fnSettings().aoData[addedData[0]].nTr;
					theNode.setAttribute('id', data[x]['pi_id']);
				}
				
			},
			error: function(){

			}
		});}

	// Array to track the ids of the details displayed rows
    var detailRows = [];
 
    $('#table_deactivation tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
	    var row = $('#table_deactivation').DataTable().row(tr);

	    if (row.child.isShown()) {
	        // This row is already open - close it
	        row.child.hide();
	        tr.removeClass('shown');
	    }
	    else {
	    	tr.addClass('shown');
	    	var pi_id = $('#table_deactivation tbody tr.shown').attr("id");
	        format(row.child, pi_id, 'deactivate');  // create new if not exist
	    }} );
 	
 	function format(callback, id, type) {
        $.ajax({
	        url:'<?php echo base_url('account_approval_feedback/get_info') ?>',
	        data: {get_info : id, type : type},
	        dataType: "json",
	        success: function (response) {
	         
	            var display = "<div class='row'><div class='col-md-6'><div style=\"padding:5px;width:100%;\" class=\"clearfix\">\
								<img style=\"width:20%\" class=\"pull-left\" onerror=\"this.onerror=null;this.src='"+response['error_img']+"'\" src='"+response['img_path']+"'>\
								<div class=\"pull-left\" style=\"padding-left:20px;width:80%\">\
									<table style='width:100%' class=\"table table-striped no-border\">\
										<tr>\
											<th>Name</th>\
											<td>"+response['name']+"</td>\
										</tr>\
										<tr>\
											<th>Position</th>\
											<td>"+response['position']+"</td>\
										</tr>\
										<tr>\
											<th>Department</th>\
											<td>"+response['department']+"</td>\
										</tr>\
										<tr>\
											<th>Birthday</th>\
											<td>"+response['birth']+"</td>\
										</tr>\
										<tr>\
											<th>Account Life Span</th>\
											<td>"+response['accnt_span']+"</td>\
										</tr>\
										<tr>\
											<th>Contact No.</th>\
											<td>"+response['contact']+"</td>\
										</tr>\
									</table>\
								</div>\
							</div>\
							</div>\
							<div class=\"col-md-6\">\
								<div style=\"padding:5px;width:100%;\">\
									<table class=\"table no-border\">\
										<tr>\
											<th style='background:#f3f3f3' colpan='2'>Accessibilties</th>\
										</tr>\
										"+response['access']+"\
									</table>\
								</div>\
							</div>\
							<div class=\"col-md-12\">\
								<div style=\"padding:5px;width:100%;\">\
									<table class=\"table no-border\">\
										<tr>\
											<th style='background:#f3f3f3'>Remarks</th>\
										</tr>\
										<tr>\
										<td>\
										"+response['remarks']+"\
			   						</td>\
									</table>\
								</div>\
							</div>\
							</div>";

				  $(".text-remarks").wysihtml5();

	              callback($(display)).show();
	        },
	        error: function () {
	            $('#output').html('Bummer: there was an error!');
	        }
	    });
    }
    // On each draw, loop over the `detailRows` array and show any child rows
    tbl_deactivation.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );} );

    function delete_deactivation(id){
    	$.ajax({
    		url: "<?php echo base_url('account_approval_feedback/delete_deactivation') ?>",
    		data: {delete_acc : id},
    		type: "GET",
    		dataType: "json",
    		success: function(data){
    			if(data['result'] == 1){ 
	              load_accounts_for_deactivation();
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
    			 new PNotify({
                          title: 'Error',
                          text: "Function error!",
                          type: 'error',
                          icon: 'fa fa-times'
                    });
    		}
    	});
    }

    function delete_activation(id){
    	$.ajax({
    		url: "<?php echo base_url('account_approval_feedback/delete_activation') ?>",
    		data: {delete_acc : id},
    		type: "GET",
    		dataType: "json",
    		success: function(data){
    			if(data['result'] == 1){ 
	              load_accounts_for_activation();
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
    			new PNotify({
                          title: 'Error',
                          text: "Function error!",
                          type: 'error',
                          icon: 'fa fa-times'
                    });
    		}
    	});
    }
</script>