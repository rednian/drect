<style type="text/css">
	ul.pagination{
		margin-top:0px!important;
		float:right!important;
		margin-bottom: 0px
	}
	#tblListRT_filter{
		display: none;
	}
	#tblListRT tbody tr{
		cursor: pointer;
	}
	#tblListRT tbody tr td{
		font-size: 12px!important
	}
</style>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid">
				<div class="box-body">
					<div class="row">
						<div class="col-lg-2">
							<div class="form-group">
								Select type:
								<select id="selectType" class="form-control input-sm">
									<option value="request">Request</option>
									<option value="task">Task</option>
								</select>
							</div>
						</div>
						<div class="col-lg-2">
							<div class="form-group">
								Select status:
								<select id="selectStatus" class="form-control input-sm">
									<option value="">all</option>
									<option value="pending">pending</option>
									<option value="endorsed">endorsed</option>
									<option value="denied">denied</option>
									<option value="cancelled">cancelled</option>
								</select>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="form-group">
								Search:
								<div class="input-group input-group-sm">
									<span class="input-group-addon">
										<i class="fa fa-search"></i>
									</span>
									<input style="text-transform:uppercase" onkeyup="searchReferenceNumber($(this).val())" placeholder="Search" type="text" class="form-control">
								</div>
							</div>
						</div>
						<div class="col-lg-3 clearfix">
							<div class="pull-right">
								<span style='visibility:hidden'>fdsafs</span>
								<div class="form-group">
									<button type="button" class="btn btn-primary btn-sm flat">Create New</button>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-3">
							<label>List</label>
							<table id="tblListRT" class="table table-hover table-bordered table-striped">
								<thead class="hide">
									<tr>
										<td></td>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="col-md-9">
							<div class="box box-primary">
								<div class="box-header">
									<div class="pull-left">
										<i class="fa fa-file-text-o fa-3x"></i>
									</div>
									<div class="pull-left" style="margin-left:10px">
										<span style="font-weight:bold;font-size:16px">School Supplies</span> <small>( Supplies Requisition )</small><br>
										<span>REFERENCE NO. <b>GFD4C</b></span>&nbsp;&nbsp;&nbsp;<span class="label label-success">Endorsed</span><br>
									</div>
									<button class="btn btn-default btn-sm pull-right">Cancel</button>
								</div>
								<div class="box-body" style="background:#f3f3f3">
									<div class="row">
										<div class="col-md-9">
											<p style="text-align:justify">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
											tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
											quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
											consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
											cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
											proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
										</div>
										<div class="col-md-9"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>
	</div>
</section>

<script type="text/javascript">
	var tblListRT;

	$(function(){
		var type = $("#selectType").val();
		loadList(type);
	});

	$(document).ready(function(){
		tblListRT = $("#tblListRT").dataTable({
						"bLengthChange": false,
						"pageLength": 5,
						"bFilter":true,
						"bInfo":false,
						"pagingType" : "simple",
						"oLanguage": {
					                        "sSearch": "<i class='fa fa-search'></i> ",
					                        "oPaginate": 
					                                      {
					                                          "sNext": '<i class="fa fa-chevron-right"></i>',
					                                          "sPrevious": '<i class="fa fa-chevron-left"></i>',
					                                          "sFirst": '<i class="fa fa-angle-double-left"></i>',
					                                          "sLast": '<i class="fa fa-angle-double-right"></i>'
					                                      }
					                    },
			            "columnDefs": [
						    { className: "clearfix", "targets": [ 0 ] }
						]
					});

		$("#selectType").on("change", function(){
			var type = $(this).val();
			loadList(type);
			// alert(type);
		});

	});

	function loadList(type){
		$.ajax({
			url: "<?php echo base_url('rt_list/loadList') ?>",
			data: {type : type},
			type: "GET",
			dataType: "json",
			success: function(data){

				tblListRT.fnClearTable();

				$.each(data, function(key, value){
					tblListRT.fnAddData([
							"<div class=\"pull-left\">\
								<i class=\"fa fa-file-text-o fa-3x\"></i>\
							</div>\
							<div class=\"pull-left\" style=\"margin-left:10px\">\
								<div><span style='font-size:12px'><small>Ref no:</small> <b>"+value.rt_ref_no+"</b></span></div>\
								<div>"+value.rt_title+"</div>\
								<small>"+value.rt_date_time+"</small><br>"+value.statLabel+"</div>"+value.button
					]);
				});
			},
			error: function(){

			}
		});
	}
</script>