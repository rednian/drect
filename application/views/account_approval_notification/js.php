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
			url: "<?php echo base_url('account_approval_notification/load_activation'); ?>",
			dataType: "json",
			success: function(data){

				tbl_activation.fnClearTable();

				for(var x=0;x<data.length;x++){
					
					var addedData = tbl_activation.fnAddData([
											'',
											data[x]['name'],
											data[x]['position'],
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
	        format(row.child, pi_id);  // create new if not exist
	    }} );
 	
 	function format(callback, id) {
        $.ajax({
	        url:'<?php echo base_url('account_approval_notification/get_info') ?>',
	        data: {get_info : id},
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
    tbl_activation.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );} );

    

    // submit activation //
    var status;

    $(document).ready(function(){

		$("form#form_submit_remarks_activation").on("submit", function(e){
			e.preventDefault();
			$.ajax({
	          url: "<?php echo base_url('account_approval_notification/submit_remarks_activation') ?>",
	          type: "POST",            
	          data: $(this).serialize()+"&status="+status,
	          dataType: "json",
	          success: function(data)  
	          {
	            if(data['result'] == 1){ 
	              load_accounts_for_activation();
	              close_activation_modal();
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
	
	function activateAccount(pi_id){
		$.ajax({
			url: "<?php echo base_url('account_approval_notification/activeAccount') ?>",
			data: {pi_id : pi_id},
			type: "GET",
			dataType: "json",
			success: function(data){
				if(data.result == true){
					load_accounts_for_activation();
					new PNotify({
                      title: 'Success',
                      text: "Account activated successfully",
                      type: 'success',
                      icon: 'fa fa-check'
                  	});
				}
				else{
					new PNotify({
                          title: 'Error',
                          text: 'Error activating account',
                          type: 'error',
                          icon: 'fa fa-times'
                    });
				}
			},
			error: function(){
				new PNotify({
                          title: 'Error',
                          text: 'Function Error',
                          type: 'error',
                          icon: 'fa fa-times'
                    });
			}
		});
	}

    function approve_activation(pi_id){
    	status = "active";
    	$("#set_remarks_modal_activation #activate_pi_id").val(pi_id);
    	$("#set_remarks_modal_activation .modal-title").html("Are you sure you want to approve request?");
    	$("#set_remarks_modal_activation #activate_remarks").attr("required", false);
    	$("#set_remarks_modal_activation").modal("show");
    }
    function disapprove_activation(pi_id){
    	status = "disapproved";
    	$("#set_remarks_modal_activation #activate_pi_id").val(pi_id);
    	$("#set_remarks_modal_activation .modal-title").html("Are you sure you want to disapprove request?");
    	$("#set_remarks_modal_activation #activate_remarks").attr("required", true);
    	$("#set_remarks_modal_activation").modal("show");
    }

    function close_activation_modal(){
    	$("#set_remarks_modal_activation #activate_remarks").val("");
    	$("#set_remarks_modal_activation").modal("hide");
    }
  
    
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
			url: "<?php echo base_url('account_approval_notification/load_deactivation'); ?>",
			dataType: "json",
			success: function(data){

				tbl_deactivation.fnClearTable();

				for(var x=0;x<data.length;x++){
					
					var addedData = tbl_deactivation.fnAddData([
											'',
											data[x]['name'],
											data[x]['position'],
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
	        format(row.child, pi_id);  // create new if not exist
	    }} );
 	
 	function format(callback, id) {
        $.ajax({
	        url:'<?php echo base_url('account_approval_notification/get_info') ?>',
	        data: {get_info : id},
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

    var deact_id;

    function approve_deactivation(pi_id,id){
    	status = "inactive";
    	$("#set_remarks_modal_deactivation #deactivate_pi_id").val(pi_id);
    	$("#set_remarks_modal_deactivation .modal-title").html("Are you sure you want to approve deactivation request?");
    	$("#set_remarks_modal_deactivation #deactivate_remarks").attr("required", false);
    	$("#set_remarks_modal_deactivation").modal("show");
    	deact_id = id;
    }
    function disapprove_deactivation(pi_id,id){
    	status = "active";
    	$("#set_remarks_modal_deactivation #deactivate_pi_id").val(pi_id);
    	$("#set_remarks_modal_deactivation .modal-title").html("Are you sure you want to disapprove deactivation request?");
    	$("#set_remarks_modal_deactivation #deactivate_remarks").attr("required", true);
    	$("#set_remarks_modal_deactivation").modal("show");
    	deact_id = id;

    }

    function close_deactivation_modal(){
    	$("#set_remarks_modal_deactivation #deactivate_remarks").val("");
    	$("#set_remarks_modal_deactivation").modal("hide");
    }

    //submit activation //
    var status;

    $(document).ready(function(){

		$("form#form_submit_remarks_deactivation").on("submit", function(e){
			e.preventDefault();
			$.ajax({
	          url: "<?php echo base_url('account_approval_notification/submit_remarks_deactivation') ?>",
	          type: "POST",            
	          data: $(this).serialize()+"&status="+status+"&deact_id="+deact_id,
	          dataType: "json",
	          success: function(data)  
	          {
	            if(data['result'] == 1){ 
	              load_accounts_for_deactivation();
	              load_accounts_for_activation();
	              close_deactivation_modal();
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


	// ------------------------------------------- MODIFICATION --------------------------------------------------------

	// ----------------------------------------------------------------------------------------------------------------
	// FOR DEACTIVATION //

	var table_modification = $('#table_modification').dataTable({
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
                                          "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Account List For Modification</small></a></div></div>",
                                          "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                                <p>Please use your browser's print function to\
                                                print this table.\
                                                <br />Press <b>escape</b> when finished.</p>",
                                        }
                                    ]
                    }});


	load_accounts_for_modification();

	function load_accounts_for_modification(){

		$.ajax({
			url: "<?php echo base_url('account_approval_notification/load_modification'); ?>",
			dataType: "json",
			success: function(data){

				table_modification.fnClearTable();

				for(var x=0;x<data.length;x++){
					
					var addedData = table_modification.fnAddData([
											'',
											data[x]['name'],
											data[x]['position'],
											data[x]['status'],
											data[x]['action']
											]);

					var theNode = $('#table_modification').dataTable().fnSettings().aoData[addedData[0]].nTr;
					theNode.setAttribute('id', data[x]['pi_id']);
				}
				
			},
			error: function(){

			}
		});}

	// Array to track the ids of the details displayed rows
    var detailRows = [];
 
    $('#table_modification tbody').on( 'click', 'tr td.details-control', function () {
        var tr = $(this).closest('tr');
	    var row = $('#table_modification').DataTable().row(tr);

	    if (row.child.isShown()) {
	        // This row is already open - close it
	        row.child.hide();
	        tr.removeClass('shown');
	    }
	    else {
	    	tr.addClass('shown');
	    	var pi_id = $('#table_modification tbody tr.shown').attr("id");
	        format(row.child, pi_id);  // create new if not exist
	    }} );
 	
 	function format(callback, id) {
        $.ajax({
	        url:'<?php echo base_url('account_approval_notification/get_info') ?>',
	        data: {get_info : id},
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
    table_modification.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td.details-control').trigger( 'click' );
        } );} );

    var mod_id;

    function approve_modification(pi_id,id){
    	status = "approved";
    	$("#set_remarks_modal_modification #modify_pi_id").val(pi_id);
    	$("#set_remarks_modal_modification .modal-title").html("Are you sure you want to approve modification request?");
    	$("#set_remarks_modal_modification #modify_remarks").attr("required", false);
    	$("#set_remarks_modal_modification").modal("show");
    	mod_id = id;
    }
    function disapprove_modification(pi_id,id){
    	status = "disapproved";
    	$("#set_remarks_modal_modification #modify_pi_id").val(pi_id);
    	$("#set_remarks_modal_modification .modal-title").html("Are you sure you want to disapprove modification request?");
    	$("#set_remarks_modal_modification #modify_remarks").attr("required", true);
    	$("#set_remarks_modal_modification").modal("show");
    	mod_id = id;
    }

    function close_modification_modal(){
    	$("#set_remarks_modal_modification #modify_remarks").val("");
    	$("#set_remarks_modal_modification").modal("hide");
    }

    //submit activation //
    var status;

    $(document).ready(function(){

		$("form#form_submit_remarks_modification").on("submit", function(e){
			e.preventDefault();
			$.ajax({
	          url: "<?php echo base_url('account_approval_notification/submit_remarks_modification') ?>",
	          type: "POST",            
	          data: $(this).serialize()+"&status="+status+"&mod_id="+mod_id,
	          dataType: "json",
	          success: function(data)  
	          {
	            if(data['result'] == 1){ 
	              load_accounts_for_modification();
	              close_modification_modal();
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
             	console.log(data);
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
</script>