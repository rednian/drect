<section class="content">
	<div class="row">
		<div class="col-lg-12">

			<div>
			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Add Division</a></li>
			    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Add Department</a></li>
			    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Add Position</a></li>
			  </ul>
			  <!-- Tab panes -->
			  <div class="tab-content" style="padding:20px;background:#FFF">
			  	<!-- ADD DIVISION TAB -->
			    <div role="tabpanel" class="tab-pane active" id="home">
			    	<div class="row">
			    		<div class="col-lg-4">
							<form action="<?php echo base_url('org_settings/add_division') ?>" id="form_add_division" method="post">
								<div class="form-group">
									<input type="hidden" name="div_id">
									<input required name="division" placeholder="Enter division here" type="text" class="form-control">
								</div>
								<div class="form-group">
									<button class="btn btn-sm btn-primary flat">Submit</button>
									<button type="reset" class="btn btn-sm btn-danger flat">Cancel</button>
								</div>
							</form>
						</div>
						<div class="col-lg-8">
							<table id="tbl_division" class="table table-hover table-striped table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Division Name</th>
									</tr>
								</thead>
							</table>
						</div>
			    	</div> 
			    </div>
			    <!-- END ADD DEPARTMENT -->
			    <div role="tabpanel" class="tab-pane" id="profile">
			    	<div class="row">
			    		<div class="col-lg-4">
							<form action="<?php echo base_url('org_settings/add_department') ?>" id="form_add_department" method="post">
								<div class="form-group">
									Select Division
									<select name="div_id" class="form-control select_division">
										
									</select>
								</div>
								<div class="form-group">
									<input type="hidden" name="dep_id">
									<input required name="department" placeholder="Enter department here" type="text" class="form-control">
								</div>
								<div class="form-group">
									<button class="btn btn-sm btn-primary flat">Submit</button>
									<button type="reset" class="btn btn-sm btn-danger flat">Cancel</button>
								</div>
							</form>
						</div>
						<div class="col-lg-8">
							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										Sort by division
										<select onchange="sort_dep_by_div($(this).val())" id="sort_div" class="form-control input-sm select_division">
											<option value='all'>All</option>
										</select>
									</div>
								</div>
							</div>
							<table id="tbl_department" class="table table-hover table-striped table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Department Name</th>
									</tr>
								</thead>
							</table>
						</div>
			    	</div>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="messages">
			    	<div class="row">
			    		<div class="col-lg-4">
							<form action="<?php echo base_url('org_settings/add_position') ?>" id="form_add_position" method="post">
								<input type="hidden" name="pos_id">
								<div class="form-group">
									Select Division
									<select onchange="select_sort_dep_by_div($(this).val())" name="div_id" id="add_pos_select_div" class="form-control pos_select_division">
										<option value='all'>All</option>
									</select>
								</div>
								<div class="form-group">
									Select Department
									<select onchange="if($(this).val()=='all'){select_sort_position_type('','all')}else{select_sort_position_type('','')}" name="dep_id" id="add_pos_select_dep" class="form-control">
										<option value='all'>All</option>
									</select>
								</div>
								<div class="form-group">
									Position
									<input required name="position" placeholder="Enter position here" type="text" class="form-control">
								</div>
								<div class="form-group">
									Position Type
									<select name="position_type" id="add_pos_select_type" class="form-control">
										<option value='3'>Board of director</option>
									</select>
								</div>
								<div class="form-group">
									<button class="btn btn-sm btn-primary flat">Submit</button>
									<button type="reset" id="reset_form_add_pos" class="btn btn-sm btn-danger flat">Cancel</button>
								</div>
							</form>
						</div>
						<div class="col-lg-8">
							<div class="row">
								<div class="col-md-5">
									<div class="form-group">
										Sort by department
										<select onchange="sort_pos_by_dep($(this).val())" id="sort_dep" class="form-control input-sm">
											<option value='all'>All</option>
										</select>
									</div>
								</div>
							</div>
							<table id="tbl_position" class="table table-hover table-striped table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Position Name</th>
									</tr>
								</thead>
							</table>
						</div>
			    	</div>
			    </div>
			  </div>

			</div>

		</div>
		
		<!-- -->
	</div>
</section>

<!-- CONFIRM DELETE DIVISION -->
<div class="modal fade" id="confirm_delete_division" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post" action="">
              <div class="modal-header flat with-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Delete</h4>
              </div>
              <div class="modal-body message">
                <p>Are you sure you want to delete division and its subordinate?</p>
              </div>
              <div class="modal-footer flat">
                <button type="button" class="btn btn-default btn-sm flat" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm flat confirmOK" onclick="delete_division()" id="" name= class="btn btn-success btn-sm flat">Ok</button>
              </div>
            </form> 
          </div>
        </div>
      </div>
<!-- !CONFIRM DELETE DIVISION -->

<!-- CONFIRM DELETE DIVISION -->
<div class="modal fade" id="confirm_delete_department" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post" action="">
              <div class="modal-header flat with-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Delete</h4>
              </div>
              <div class="modal-body message">
                <p>Are you sure you want to delete department and its subordinate?</p>
              </div>
              <div class="modal-footer flat">
                <button type="button" class="btn btn-default btn-sm flat" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm flat confirmOK" onclick="delete_department()" id="" name= class="btn btn-success btn-sm flat">Ok</button>
              </div>
            </form> 
          </div>
        </div>
      </div>
<!-- !CONFIRM DELETE DIVISION -->

<!-- CONFIRM DELETE DIVISION -->
<div class="modal fade" id="confirm_delete_position" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
          <div class="modal-content">
            <form method="post" action="">
              <div class="modal-header flat with-border">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Delete</h4>
              </div>
              <div class="modal-body message">
                <p>Are you sure you want to delete position?</p>
              </div>
              <div class="modal-footer flat">
                <button type="button" class="btn btn-default btn-sm flat" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm flat confirmOK" onclick="delete_position()" id="" name= class="btn btn-success btn-sm flat">Ok</button>
              </div>
            </form> 
          </div>
        </div>
      </div>
<!-- !CONFIRM DELETE DIVISION -->
<script type="text/javascript">
	// ADD DIVISION
	var tbl_division;
	var tbl_department;

	$(document).ready(function(){
		tbl_division = $('#tbl_division').dataTable({
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
                    "bFilter": false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    
                    });

		tbl_department = $('#tbl_department').dataTable({
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
                    "bFilter": false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    
                    });

		tbl_position = $('#tbl_position').dataTable({
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
                    "bFilter": false,
                    "aoColumnDefs": [
                        {
                            'bSortable': false,
                            'aTargets': [0]
                        } //disables sorting for column one
                    ],
                    'iDisplayLength': 10,
                    "sPaginationType": "full_numbers",
                    
                    });

		load_division();
		load_department();
		load_position();
	});

	$(document).ready(function(){
		$("#form_add_division").on("submit", function(e){
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('org_settings/add_division') ?>",
				data: $(this).serialize(),
				type: "POST",
				dataType: "json",
				success: function(data){
					if(data['result'] == true){
						$("input[name=division]").val("");
						$("input[name=div_id]").val("");
						load_division();
						show_alert('Information','success', data['msg']);
					}
					else{
						show_alert('Information','error', data['msg']);
					}
				},
				error: function(){

				}
			});
		});
	});

	$(document).ready(function(){
		$("#form_add_department").on("submit", function(e){
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('org_settings/add_department') ?>",
				data: $(this).serialize(),
				type: "POST",
				dataType: "json",
				success: function(data){
					if(data['result'] == true){
						$("input[name=department]").val("");
						$("input[name=dep_id]").val("");
						load_department();
						show_alert('Information','success', data['msg']);
					}
					else{
						show_alert('Information','error', data['msg']);
					}
				},
				error: function(){

				}
			});
		});
	});

	$(document).ready(function(){
		$("#form_add_position").on("submit", function(e){
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('org_settings/add_position') ?>",
				data: $(this).serialize(),
				type: "POST",
				dataType: "json",
				success: function(data){
					if(data['result'] == true){
						$("input[name=position]").val("");
						$("#reset_form_add_pos").trigger("click");
						load_position();
						show_alert('Information','success', data['msg']);
					}
					else{
						show_alert('Information','error', data['msg']);
					}
				},
				error: function(){

				}
			});
		});
	});

	function load_division(){
		$.ajax({
			url: "<?php echo base_url('org_settings/load_division') ?>",
			dataType: "json",
			success: function(data){
				tbl_division.fnClearTable();
				$(".select_division").html("");
				$(".pos_select_division").html("<option value='all'>All</option>");
				var i = 1;
				$.each(data, function (key, value){
					tbl_division.fnAddData([
											i,
											value['division']+"<span class=\"pull-right\"><a onclick='edit_division("+value['div_id']+")' href=\"#\">Edit</a> | <a onclick='confirm_delete_division("+value['div_id']+")' href=\"#\">Delete</a></span>"
											]);

					$(".select_division").append("<option value='"+value['div_id']+"'>"+value['division']+"</option>");
					$(".pos_select_division").append("<option value='"+value['div_id']+"'>"+value['division']+"</option>");
					
					i++;
				});
			}
		});
	}

	function load_department(){
		$.ajax({
			url: "<?php echo base_url('org_settings/load_department') ?>",
			dataType: "json",
			success: function(data){
				tbl_department.fnClearTable();
				$(".select_department").html("");
				$("#add_pos_select_dep").html("<option value='all'>All</option>");
				$("#sort_dep").html("<option value='all'>All</option>");
				var i = 1;
				$.each(data, function (key, value){
					tbl_department.fnAddData([
											i,
											value['department']+"<span class=\"pull-right\"><a onclick='edit_department("+value['dep_id']+")' href=\"#\">Edit</a> | <a onclick='confirm_delete_department("+value['dep_id']+")' href=\"#\">Delete</a></span>"
											]);

					$(".select_department").append("<option value='"+value['dep_id']+"'>"+value['department']+"</option>");
					$("#sort_dep").append("<option value='"+value['dep_id']+"'>"+value['department']+"</option>");
					i++;
				});
			}
		});
	}

	function sort_dep_by_div(div_id){
		if(div_id == 'all'){
			load_department();
		}
		else{
			$.ajax({
				url: "<?php echo base_url('org_settings/sort_department_by_div') ?>",
				data: {div_id : div_id},
				type: "GET",
				dataType: "json",
				success: function(data){
					tbl_department.fnClearTable();
					var i = 1;
					$.each(data, function (key, value){
						tbl_department.fnAddData([
												i,
												value['department']+"<span class=\"pull-right\"><a href=\"#\">Edit</a> | <a href=\"#\">Delete</a></span>"
												]);

						i++;
					});
				}
			});
		}
	}

	function select_sort_dep_by_div(div_id){
		if(div_id == 'all'){
			$("#add_pos_select_dep").html("<option value='all'>All</option>");
			select_sort_position_type('all','');
		}
		else{
			select_sort_position_type('','all');
			$.ajax({
				url: "<?php echo base_url('org_settings/sort_department_by_div') ?>",
				data: {div_id : div_id},
				type: "GET",
				dataType: "json",
				success: function(data){
					$("#add_pos_select_dep").html("<option value='all'>All</option>");
					$.each(data, function (key, value){
						$("#add_pos_select_dep").append("<option value="+value['dep_id']+">"+value['department']+"</option>");
					});
				}
			});
		}
	}

	function select_sort_position_type(div,dep){
		if(div == "all"){
			$("#add_pos_select_type").html("<option value='3'>Board of director</option>");
		}
		else{
			if(dep == "all"){
				$("#add_pos_select_type").html("<option value='2'>Division Head</option>");
			}
			else{
				$("#add_pos_select_type").html("");
				$("#add_pos_select_type").append("<option value='1'>Department Head</option>");
				$("#add_pos_select_type").append("<option value='0'>Staff</option>");
			}
		}
	}

	function load_position(){
		$.ajax({
			url: "<?php echo base_url('org_settings/load_position') ?>",
			dataType: "json",
			success: function(data){
				tbl_position.fnClearTable();
				var i = 1;
				$.each(data, function (key, value){
					tbl_position.fnAddData([
											i,
											value['position']+"<span class=\"pull-right\"><a onclick='edit_position("+value['pos_id']+")' href=\"#\">Edit</a> | <a onclick='confirm_delete_position("+value['pos_id']+")' href=\"#\">Delete</a></span>"
											]);
					i++;
				});
			}
		});
	}

	function sort_pos_by_dep(dep_id){
		if(dep_id == 'all'){
			load_position();
		}
		else{
			$.ajax({
				url: "<?php echo base_url('org_settings/sort_position_by_dep') ?>",
				data: {dep_id : dep_id},
				type: "GET",
				dataType: "json",
				success: function(data){
					tbl_position.fnClearTable();
					var i = 1;
					$.each(data, function (key, value){
						tbl_position.fnAddData([
												i,
												value['position']+"<span class=\"pull-right\"><a href=\"#\">Edit</a> | <a href=\"#\">Delete</a></span>"
												]);

						i++;
					});
				}
			});
		}
	}

	var delDiv;
	var delDep;
	var delPos;

	function confirm_delete_division(div_id){
		delDiv = div_id;
		$("#confirm_delete_division").modal('show');
	}
	function confirm_delete_department(dep_id){
		delDep = dep_id;
		$("#confirm_delete_department").modal('show');
	}
	function confirm_delete_position(pos_id){
		delPos = pos_id;
		$("#confirm_delete_position").modal('show');
	}
	function delete_division(){
		$.ajax({
			url: "<?php echo base_url('org_settings/delete_division') ?>",
			data: {div_id : delDiv},
			type: "GET",
			dataType: "json",
			success: function(data){
				if(data['result'] == true){
					load_division();
					load_department();
					load_position();
					$("#confirm_delete_division").modal('hide');
					show_alert('Information','success', data['msg']);
				}
				else{
					$("#confirm_delete_division").modal('hide');
					show_alert('Information','error', data['msg']);
				}
			},
			error: function(){
				$("#confirm_delete_division").modal('hide');
				show_alert('Information','error', "Function Delete Error");
			}
		})
	}

	function delete_department(){
		$.ajax({
			url: "<?php echo base_url('org_settings/delete_department') ?>",
			data: {dep_id : delDep},
			type: "GET",
			dataType: "json",
			success: function(data){
				if(data['result'] == true){
					load_department();
					load_position();
					$("#confirm_delete_department").modal('hide');
					show_alert('Information','success', data['msg']);
				}
				else{
					$("#confirm_delete_department").modal('hide');
					show_alert('Information','error', data['msg']);
				}
			},
			error: function(){
				$("#confirm_delete_department").modal('hide');
				show_alert('Information','error', "Function Delete Error");
			}
		})
	}

	function delete_position(){
		$.ajax({
			url: "<?php echo base_url('org_settings/delete_position') ?>",
			data: {pos_id : delPos},
			type: "GET",
			dataType: "json",
			success: function(data){
				if(data['result'] == true){
					load_position();
					$("#confirm_delete_position").modal('hide');
					show_alert('Information','success', data['msg']);
				}
				else{
					$("#confirm_delete_position").modal('hide');
					show_alert('Information','error', data['msg']);
				}
			},
			error: function(){
				$("#confirm_delete_position").modal('hide');
				show_alert('Information','error', "Function Delete Error");
			}
		})
	}

	function edit_division(div_id){
		$.ajax({
			url: "<?php echo base_url('org_settings/edit_division') ?>",
			type: "GET",
			data: {div_id : div_id},
			dataType: "json",
			success: function(data){
				$.each(data, function( key, value){
					$("input[name=div_id]").val(value['div_id']);
					$("input[name=division]").val(value['division']);
				});
			}
		});
	}

	function edit_department(dep_id){
		$.ajax({
			url: "<?php echo base_url('org_settings/edit_department') ?>",
			type: "GET",
			data: {dep_id : dep_id},
			dataType: "json",
			success: function(data){
				$.each(data, function( key, value){
					
					$("form#form_add_department select[name=div_id]").val(value['div_id']);
					$("form#form_add_department input[name=dep_id]").val(value['dep_id']);
					$("form#form_add_department input[name=department]").val(value['department']);

				});
			}
		});
	}

	function edit_position(pos_id){
		// // alert(pos_id);
		$.ajax({
			url: "<?php echo base_url('org_settings/edit_position') ?>",
			data: {pos_id : pos_id},
			type: "GET",
			dataType: "json",
			success: function(data){

				$.each( data, function( key, value){

					var dvID;
					var dID;

					$("form#form_add_position input[name=pos_id]").val(value['pos_id']);
					
					if(value['div_id'] == 0){
						dvID = 'all';
					}
					else{
						dvID = value['div_id'];
					}

					$("form#form_add_position select[name=div_id]").val(dvID);
					select_sort_dep_by_div(dvID);

					if(value['dep_id'] == '0'){
						dID = 'all';
					}
					else{
						dID = value['dep_id'];
						select_sort_position_type('','');
						$.ajax({
							url: "<?php echo base_url('org_settings/sort_department_by_div') ?>",
							data: {div_id : dvID},
							type: "GET",
							dataType: "json",
							success: function(data){
								$("#add_pos_select_dep").html("<option value='all'>All</option>");
								$("#add_pos_select_dep").prepend("<option selected class='hide' value='"+value['dep_id']+"'>"+value['department']+"</option>");
								$.each(data, function (key, value){
									$("#add_pos_select_dep").append("<option value="+value['dep_id']+">"+value['department']+"</option>");
								});
							}
						});
					}

					$("form#form_add_position select[name=dep_id]").val(dID);
					$("form#form_add_position select[name=position_type]").val(value['position_type']);
					$("form#form_add_position input[name=position]").val(value['position']);
				
				});
					
			}
		});
	}

</script>