<section class="content">
	<div class="col-lg-12">
		<div class="box box-solid">
			<div class="box-body">
				<table id="table-repo" class="table table-hover table-bordered">
		             <thead>
		                <tr class="headings">
		                    <th>#</th>
		                    <th class="col-md-2">Title </th>
		                    <th>Version </th>
		                    <th>Date Modified </th>
		              		<th>Type</th>
		                    <!-- <th>Action</th> -->
		                </tr>
		            </thead>
		            
		          </table>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	var tbl_repo = $('#table-repo').dataTable({
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
								   	// null,
								  
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
                                          "sMessage": "<body class='skin-blue sidebar-mini fixed sidebar-collapse'></body><div class='navbar navbar-default'><div class='navbar-header pull-left'><a class='navbar-brand' href='#'><small>PWI Repository</small></a></div></div>",
                                          "sInfo": "<h3 class='no-margin-top'>Print view</h3>\
                                                <p>Please use your browser's print function to\
                                                print this table.\
                                                <br />Press <b>escape</b> when finished.</p>",
                                        }
                                    ]
                    }});

	load_repo();
	function load_repo(){

		$.ajax({
			url: "<?php echo base_url('pwi_repository/load_list'); ?>",
			dataType: "json",
			success: function(data){

				tbl_repo.fnClearTable();

			      for(var i = 0; i < data.length; i++) { 

			      	var color = "";
			      	if(data[i]['status'] == 'disapproved'){
			      		color = "label-danger";
			      	}
			      	else if(data[i]['status'] == 'pending'){
			      		color = "label-warning";
			      	}

			        tbl_repo.fnAddData([ 
										  i+1,
			                              "<b>"+data[i]['title']+"</b>", 
			                              data[i]['version'], 
			                              data[i]['date_created'],
                                    		data[i]['type'],
			                              // data[i]['action']
			                              ]); 

			      } // End For
				
			},
			error: function(){

			}
		});}
</script>