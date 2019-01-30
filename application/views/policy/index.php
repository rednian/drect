<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group pull-right">
				<a href="<?php echo base_url('policy/new') ?>" class="btn btn-primary flat"><i class="fa fa-plus"></i> Add New Policy</a>
			</div>
		</div>
		<div class="col-md-12">
			<div class="box box-solid">
				<div class="box-body">
					<table id="tblPolicy" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th class="col-md-2">Policy No.</th>
								<th>Policy Title</th>
								<th class="col-md-2">Action</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	var $table = $("table#tblPolicy").DataTable({
		"ajax" : "<?php echo base_url('PolicyController/policyList') ?>",
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
            	"bSort": false,
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
                                  "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>Inbox</small></a></div></div>",
                                  "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                        <p>Please use your browser's print function to\
                                        print this table.\
                                        <br />Press <b>escape</b> when finished.</p>",
                                }
                            ]
            }
	}); 
	
	function removePolicy(policy_id){

		bootbox.confirm("Are you sure you want delete policy?", function(result) {
		  	if(result == true){
				$.ajax({
					url: "<?php echo base_url('PolicyController/deletePolicy') ?>",
					data: {policy_id : policy_id},
					type: "GET",
					dataType: "JSON",
					success: function(data){
						if(data.result == true){
							$table.ajax.reload();
							new PNotify({
			                          title: 'Success',
			                          text: "Policy has been deleted successfully.",
			                          type: 'success',
			                          icon: 'fa fa-check'
			                  });
						}
						else if(data.result == false){
							new PNotify({
		                          title: 'Error',
		                          text: 'Policy not deleted.',
		                          type: 'error',
		                          icon: 'fa fa-times'
		                    });
						}
						else if(data.result == "used"){
							new PNotify({
		                          title: 'Error',
		                          text: 'Cannot delete policy. Policy is being used',
		                          type: 'error',
		                          icon: 'fa fa-times'
		                    });
						}
					},
					error: function(){

					}
				});
			}	
		}); 
		
	}
</script>